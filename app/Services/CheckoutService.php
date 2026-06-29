<?php

namespace App\Services;

use App\Models\Event;
use App\Models\KodeVoucher;
use App\Models\Payment;
use App\Models\Transaksi;
use App\Models\Volunteer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckoutService
{
    public function __construct(protected MidtransService $midtransService) {}

    /**
     * Proses checkout: potong stok, buat transaksi, attach volunteer, redeem voucher, buat snap token.
     * Total pembayaran dihitung server-side; jangan percaya nilai dari client.
     *
     * @param  array<int, array{name: string, email: string, telepon: string}>  $pengunjung
     * @throws \Exception
     */
    public function process(
        Event $event,
        int $jumlahTiket,
        Payment $payment,
        array $pengunjung,
        ?string $voucherCode = null
    ): Transaksi {
        DB::beginTransaction();

        try {
            // 1. Potong stok secara atomik
            $affected = Event::where('id', $event->id)
                ->where('jumlah_tiket', '>=', $jumlahTiket)
                ->decrement('jumlah_tiket', $jumlahTiket);

            if ($affected === 0) {
                throw new \Exception('Tiket tidak mencukupi atau event tidak valid.');
            }

            // 2. Proses voucher
            $voucherId         = null;
            $discountPerTicket = 0;
            $appliedVoucher    = null;

            if ($voucherCode) {
                $appliedVoucher = KodeVoucher::where('kode', strtoupper(trim($voucherCode)))
                    ->where('id_event', $event->id)
                    ->where('status', true)
                    ->where('tanggal_kadaluarsa', '>=', now()->startOfDay())
                    ->first();

                if ($appliedVoucher) {
                    $remaining = $appliedVoucher->kuota - $appliedVoucher->digunakan;
                    if ($remaining < $jumlahTiket) {
                        throw new \Exception("Kuota voucher tidak mencukupi. Tersisa {$remaining} kuota, dibutuhkan {$jumlahTiket}.");
                    }
                    $voucherId         = $appliedVoucher->id;
                    $discountPerTicket = $appliedVoucher->nilai_diskon;
                    $appliedVoucher->increment('digunakan', $jumlahTiket);
                }
            }

            // 3. Hitung total server-side
            $totalPembayaran = max(0, ($event->harga - $discountPerTicket) * $jumlahTiket);

            // 4. Buat transaksi
            $invoice   = date('YmdHis') . uniqid();
            $utamaData = $pengunjung[0];

            $transaksi = Transaksi::create([
                'id_event'           => $event->id,
                'invoice'            => $invoice,
                'jumlah_tiket'       => $jumlahTiket,
                'total_pembayaran'   => $totalPembayaran,
                'name'               => $utamaData['name'],
                'telepon'            => $this->formatPhone($utamaData['telepon']),
                'email'              => $utamaData['email'],
                'status_pembayaran'  => 'Pending',
                'tanggal_register'   => now(),
                'tanggal_pembayaran' => null,
                'id_payment'         => $payment->id,
                'id_voucher'         => $voucherId,
            ]);

            // 5. Attach volunteer
            foreach ($pengunjung as $p) {
                $volunteer = Volunteer::firstOrCreate(
                    ['email' => $p['email']],
                    [
                        'name'    => $p['name'],
                        'telepon' => $this->formatPhone($p['telepon']),
                    ]
                );
                $transaksi->volunteers()->syncWithoutDetaching([$volunteer->id]);
            }

            // 6. Redeem voucher eksternal (lempar exception → rollback jika gagal)
            if ($appliedVoucher && $appliedVoucher->is_external) {
                $this->redeemExternalVoucher($appliedVoucher, $transaksi);
            }

            // 7. Generate Snap token (non-fatal jika gagal)
            if ($payment->type === 'midtrans') {
                try {
                    $transaksi->load('event');
                    $snapToken = $this->midtransService->createSnapToken($transaksi);
                    $transaksi->update(['snap_token' => $snapToken]);
                    $transaksi->snap_token = $snapToken;
                } catch (\Exception $e) {
                    Log::error('Gagal membuat Snap token Midtrans', [
                        'invoice' => $transaksi->invoice,
                        'error'   => $e->getMessage(),
                    ]);
                }
            }

            DB::commit();

            Log::info('Transaction created', [
                'invoice'         => $transaksi->invoice,
                'event_id'        => $event->id,
                'total'           => $totalPembayaran,
                'ticket_count'    => $jumlahTiket,
                'voucher_applied' => $voucherId !== null,
                'payment_type'    => $payment->type,
            ]);

            return $transaksi;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function formatPhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = substr($phone, 1);
        }
        if (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }
        return '+' . $phone;
    }

    private function redeemExternalVoucher(KodeVoucher $voucher, Transaksi $transaksi): void
    {
        $cfg = config('services.chatkebaikan');
        $url = $cfg['redeem_url'] ?? '';

        if (empty($url)) {
            throw new \Exception('Endpoint redeem voucher eksternal belum dikonfigurasi.');
        }

        $method  = strtoupper($cfg['redeem_method'] ?? 'POST');
        $payload = ['kode' => $voucher->kode];

        $response = Http::timeout($cfg['timeout'])
            ->acceptJson()
            ->withOptions(['allow_redirects' => ['strict' => true]])
            ->send($method, $url, ['json' => $payload]);

        if (!$response->successful()) {
            Log::error('External voucher redeem failed', [
                'kode'    => $voucher->kode,
                'invoice' => $transaksi->invoice,
                'status'  => $response->status(),
            ]);
            throw new \Exception('Gagal redeem voucher eksternal (HTTP ' . $response->status() . ').');
        }

        Log::info('External voucher redeemed', [
            'kode'    => $voucher->kode,
            'invoice' => $transaksi->invoice,
        ]);
    }
}
