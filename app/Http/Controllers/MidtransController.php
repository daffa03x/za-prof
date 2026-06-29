<?php

namespace App\Http\Controllers;

use App\Mail\SendTicket;
use App\Models\Transaksi;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Class MidtransController
 *
 * Menangani webhook notifikasi dari Midtrans dan halaman finish setelah pembayaran.
 */
class MidtransController extends Controller
{
    protected MidtransService $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Halaman finish setelah user selesai di popup Midtrans Snap.
     * GET /midtrans/finish/{invoice}
     *
     * Tampilkan halaman "Pembayaran Berhasil" dengan detail transaksi.
     */
    public function finish(string $invoice)
    {
        $transaksi = Transaksi::with(['event', 'payment', 'volunteers'])
            ->where('invoice', $invoice)
            ->first();

        if (! $transaksi) {
            return redirect('/')->with('error', 'Transaksi tidak ditemukan.');
        }

        return view('portal.payment-success', ['data' => $transaksi]);
    }

    /**
     * Endpoint webhook notifikasi Midtrans.
     * POST /midtrans/notification — exempt dari CSRF.
     *
     * Midtrans mengirim HTTP POST ke sini setiap kali status pembayaran berubah.
     */
    public function notification(Request $request): JsonResponse
    {
        try {
            $notif = $this->midtransService->parseNotification($request);

            $invoice = $notif['order_id'];
            $transactionStatus = $notif['transaction_status'];
            $fraudStatus = $notif['fraud_status'];

            $newStatus = $this->midtransService->resolveStatus($transactionStatus, $fraudStatus);

            if ($newStatus === null) {
                Log::info('Midtrans: status tidak dikenali, diabaikan', [
                    'invoice' => $invoice,
                    'transaction_status' => $transactionStatus,
                ]);

                return response()->json(['message' => 'Status not handled'], 200);
            }

            $transaksi = Transaksi::with(['volunteers', 'event'])->where('invoice', $invoice)->first();

            if (! $transaksi) {
                Log::warning('Midtrans notification: transaksi tidak ditemukan', [
                    'invoice' => $invoice,
                ]);

                return response()->json(['message' => 'Transaction not found'], 404);
            }

            // Jangan proses ulang jika status sudah final
            if ($transaksi->status_pembayaran === 'Success' && $newStatus !== 'Success') {
                Log::info('Midtrans: transaksi sudah Success, notifikasi diabaikan', [
                    'invoice' => $invoice,
                ]);

                return response()->json(['message' => 'Already processed'], 200);
            }

            DB::beginTransaction();

            try {
                if ($newStatus === 'Success' && $transaksi->status_pembayaran !== 'Success') {
                    // Kirim email tiket ke semua volunteer
                    $failedEmails = [];
                    foreach ($transaksi->volunteers as $volunteer) {
                        try {
                            Mail::to($volunteer->email)->send(
                                new SendTicket(
                                    $transaksi->invoice,
                                    url('/tiket/'.$transaksi->invoice)
                                )
                            );
                        } catch (\Exception $e) {
                            $failedEmails[] = $volunteer->email;
                            Log::error('Midtrans: gagal kirim email tiket', [
                                'invoice' => $invoice,
                                'volunteer_email' => $volunteer->email,
                                'error' => $e->getMessage(),
                            ]);
                        }
                    }

                    // Jika ada email yang gagal, rollback dan jangan ubah status
                    if (! empty($failedEmails)) {
                        DB::rollBack();
                        Log::error('Midtrans: rollback karena email gagal', [
                            'invoice' => $invoice,
                            'failed_emails' => $failedEmails,
                        ]);

                        return response()->json([
                            'message' => 'Email delivery failed',
                            'failed_emails' => $failedEmails,
                        ], 500);
                    }

                    $transaksi->update([
                        'status_pembayaran' => 'Success',
                        'tanggal_pembayaran' => now(),
                    ]);

                    Log::info('Midtrans: transaksi berhasil, tiket terkirim', [
                        'invoice' => $invoice,
                    ]);

                } elseif ($newStatus === 'Failed' && $transaksi->status_pembayaran === 'Pending') {
                    // Kembalikan stok tiket jika gagal dari pending
                    if ($transaksi->event) {
                        $transaksi->event->increment('jumlah_tiket', $transaksi->jumlah_tiket);
                    }

                    $transaksi->update([
                        'status_pembayaran' => 'Failed',
                    ]);

                    Log::info('Midtrans: transaksi gagal, stok tiket dikembalikan', [
                        'invoice' => $invoice,
                    ]);

                } elseif ($newStatus === 'Pending') {
                    // Tidak perlu update jika sudah pending
                    DB::rollBack();

                    return response()->json(['message' => 'Still pending'], 200);
                }

                DB::commit();

                return response()->json(['message' => 'Notification processed', 'status' => $newStatus], 200);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Midtrans: error saat proses notifikasi', [
                    'invoice' => $invoice,
                    'error' => $e->getMessage(),
                ]);

                return response()->json(['message' => 'Internal server error'], 500);
            }

        } catch (\Exception $e) {
            Log::error('Midtrans: gagal parse notifikasi', [
                'error' => $e->getMessage(),
            ]);

            return response()->json(['message' => 'Failed to parse notification'], 400);
        }
    }
}
