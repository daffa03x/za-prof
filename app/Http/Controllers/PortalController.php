<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Payment;
use App\Models\Transaksi;
use App\Models\Volunteer;
use App\Models\KodeVoucher;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Class PortalController
 *
 * Handles public-facing portal pages for events, checkout, and tickets.
 */
class PortalController extends Controller
{
    /**
     * Search events for portal.
     */
    public function eventSearch(Request $request): View
    {
        $event = Event::query()
            ->orderByDesc('created_at')
            ->when($request->search, fn($q) => 
                $q->where(fn($query) => 
                    $query->where('name', 'like', '%' . $request->search . '%')
                          ->orWhere('mitra', 'like', '%' . $request->search . '%')
                )
            )
            ->paginate(12);

        return view('portal.program', compact('event'));
    }

    /**
     * Display portal homepage.
     */
    public function index(): View
    {
        // Cache homepage events for 10 minutes
        $event = Cache::remember('homepage_events', 600, function () {
            return Event::query()
                ->select(['id', 'name', 'slug', 'image', 'harga', 'waktu_mulai', 'kota', 'mitra'])
                ->where('status', true)
                ->orderByDesc('id')
                ->limit(6)
                ->get();
        });

        return view('portal.index', compact('event'));
    }

    /**
     * View event content.
     */
    public function viewContent(string $slug): View
    {
        $data = Event::where('slug', $slug)->firstOrFail();

        return view('portal.view_content', compact('data'));
    }

    /**
     * Checkout page.
     */
    public function checkout(string $slug): View
    {
        $data = Event::where('slug', $slug)->firstOrFail();
        
        // Cache payment methods for 30 minutes (rarely changes)
        $payment = Cache::remember('active_payment_methods', 1800, function () {
            return Payment::select(['id', 'name', 'image', 'no_rek', 'type'])
                ->where('status', true)
                ->orderBy('id')
                ->get();
        });
        $defaultPayment = $payment->firstWhere('type', 'midtrans') ?? $payment->first();

        return view('portal.checkout', compact('data', 'payment', 'defaultPayment'));
    }

    /**
     * Process transaction.
     */
    public function transaksiPost(Request $request, string $slug): RedirectResponse
    {
        $request->validate([
            'jumlah_tiket' => 'required|integer|min:1',
            'payment' => 'required|exists:payments,id',
            'pengunjung' => 'required|array|min:1',
            'pengunjung.*.name' => ['required', 'string', 'min:3', 'max:100', 'regex:/^[a-zA-Z\s]+$/'],
            'pengunjung.*.telepon' => ['required', 'string', 'min:9', 'max:13', 'regex:/^[0-9]+$/'],
            'pengunjung.*.email' => ['required', 'email:rfc,dns', 'max:255'],
            'voucher_code' => 'nullable|string',
        ], [
            'pengunjung.*.name.required' => 'Nama wajib diisi',
            'pengunjung.*.name.min' => 'Nama minimal 3 karakter',
            'pengunjung.*.name.regex' => 'Nama hanya boleh berisi huruf dan spasi',
            'pengunjung.*.telepon.required' => 'Nomor ponsel wajib diisi',
            'pengunjung.*.telepon.min' => 'Nomor ponsel minimal 9 digit',
            'pengunjung.*.telepon.max' => 'Nomor ponsel maksimal 13 digit',
            'pengunjung.*.telepon.regex' => 'Nomor ponsel hanya boleh berisi angka',
            'pengunjung.*.email.required' => 'Email wajib diisi',
            'pengunjung.*.email.email' => 'Format email tidak valid',
        ]);

        DB::beginTransaction();

        try {
            $event = Event::where('slug', $slug)->firstOrFail();

            $affectedRows = Event::where('id', $event->id)
                ->where('jumlah_tiket', '>=', $request->jumlah_tiket)
                ->decrement('jumlah_tiket', $request->jumlah_tiket);

            if ($affectedRows === 0) {
                throw new \Exception("Tiket tidak mencukupi atau event tidak valid.");
            }

            $invoice = date('YmdHis') . uniqid();
            
            // Handle voucher if provided
            $voucherId = null;
            $appliedVoucher = null;
            if ($request->voucher_code) {
                $appliedVoucher = KodeVoucher::where('kode', $request->voucher_code)
                    ->where('id_event', $event->id)
                    ->where('status', true)
                    ->where('tanggal_kadaluarsa', '>=', now()->startOfDay())
                    ->first();

                if ($appliedVoucher) {
                    // Calculate remaining quota
                    $remainingQuota = $appliedVoucher->kuota - $appliedVoucher->digunakan;

                    // Check if voucher has enough quota for all tickets
                    if ($remainingQuota < $request->jumlah_tiket) {
                        throw new \Exception("Kuota voucher tidak mencukupi. Tersisa {$remainingQuota} kuota, dibutuhkan {$request->jumlah_tiket}.");
                    }

                    $voucherId = $appliedVoucher->id;
                    // Increment voucher usage by ticket count
                    $appliedVoucher->increment('digunakan', $request->jumlah_tiket);
                }
            }

            // Helper function to format phone number with +62 prefix
            $formatPhone = function($phone) {
                $phone = preg_replace('/[^0-9]/', '', $phone); // Remove non-digits
                if (str_starts_with($phone, '0')) {
                    $phone = substr($phone, 1); // Remove leading 0
                }
                if (!str_starts_with($phone, '62')) {
                    $phone = '62' . $phone;
                }
                return '+' . $phone;
            };

            $transaksi = Transaksi::create([
                'id_event' => $event->id,
                'invoice' => $invoice,
                'jumlah_tiket' => $request->jumlah_tiket,
                'total_pembayaran' => $request->price,
                'name' => $request->pengunjung[0]['name'],
                'telepon' => $formatPhone($request->pengunjung[0]['telepon']),
                'email' => $request->pengunjung[0]['email'],
                'status_pembayaran' => 'Pending',
                'tanggal_register' => now(),
                'tanggal_pembayaran' => null,
                'id_payment' => $request->payment,
                'id_voucher' => $voucherId,
            ]);

            // Process volunteers
            foreach ($request->pengunjung as $pengunjungData) {
                $volunteer = Volunteer::firstOrCreate(
                    ['email' => $pengunjungData['email']],
                    [
                        'name' => $pengunjungData['name'],
                        'telepon' => $formatPhone($pengunjungData['telepon']),
                    ]
                );

                $transaksi->volunteers()->syncWithoutDetaching([$volunteer->id]);
            }

            // Redeem voucher eksternal ke sistem chatkebaikan; gagal = rollback.
            if ($appliedVoucher && $appliedVoucher->is_external) {
                $this->redeemExternalVoucher($appliedVoucher, $transaksi);
            }

            // Generate Snap token jika metode pembayaran adalah Midtrans
            $payment = Payment::find($request->payment);
            if ($payment && $payment->type === 'midtrans') {
                try {
                    $midtransService = app(MidtransService::class);
                    $transaksi->load('event');
                    $snapToken = $midtransService->createSnapToken($transaksi);
                    $transaksi->update(['snap_token' => $snapToken]);
                } catch (\Exception $e) {
                    // Snap token gagal dibuat: log dan lanjutkan (invoice tetap tampil, user bisa hubungi admin)
                    Log::error('Gagal membuat Snap token Midtrans', [
                        'invoice' => $transaksi->invoice,
                        'error'   => $e->getMessage(),
                    ]);
                }
            }

            DB::commit();

            Log::info('Transaction created via portal', [
                'invoice'         => $transaksi->invoice,
                'event_id'        => $event->id,
                'event_name'      => $event->name,
                'total'           => $request->price,
                'ticket_count'    => $request->jumlah_tiket,
                'voucher_applied' => $voucherId !== null,
                'payment_type'    => $payment?->type ?? 'unknown',
                'ip'              => $request->ip(),
            ]);

            return redirect("/invoice/{$transaksi->invoice}")->with('success', 'Transaksi berhasil');

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Transaction failed via portal', [
                'event_slug' => $slug,
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
            ]);
            
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display invoice.
     */
    public function invoice(string $invoice): View
    {
        $data = Transaksi::with(['event', 'payment', 'volunteers'])
            ->where('invoice', $invoice)
            ->first();

        if (!$data) {
            return view('portal.error_tiket');
        }

        $tanggalDibuat = Carbon::parse($data->tanggal_register);
        $batasWaktu = Carbon::now()->subDays(1);

        if ($tanggalDibuat->lessThan($batasWaktu)) {
            return view('portal.error_tiket');
        }

        return view('portal.invoice', compact('data'));
    }

    /**
     * Display program list.
     */
    public function program(): View
    {
        $event = Event::query()
            ->orderByDesc('created_at')
            ->paginate(9);

        return view('portal.program', compact('event'));
    }

    /**
     * Display ticket.
     */
    public function tiket(string $invoice): View
    {
        $transaksi = Transaksi::with(['event', 'volunteers'])
            ->where('invoice', $invoice)
            ->where('status_pembayaran', 'Success')
            ->first();

        if (!$transaksi) {
            return view('portal.error_tiket');
        }

        return view('portal.tiket', compact('transaksi'));
    }

    /**
     * Validate voucher code via API (checkout).
     * Cek DB lokal dulu; jika tidak ada, validasi ke API eksternal lalu buat voucher.
     */
    public function validateVoucher(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
            'event_id' => 'required|integer|exists:events,id',
            'jumlah_tiket' => 'nullable|integer|min:1',
        ]);

        $code = strtoupper(trim($request->code));
        $jumlahTiket = (int) ($request->jumlah_tiket ?? 1);

        $voucher = KodeVoucher::where('kode', $code)
            ->where('id_event', $request->event_id)
            ->where('status', true)
            ->first();

        if ($voucher) {
            return $this->respondWithLocalVoucher($voucher);
        }

        return $this->validateExternalVoucher($code, (int) $request->event_id, $jumlahTiket);
    }

    /**
     * Bangun response untuk voucher yang sudah ada di DB lokal (cek expiry & kuota).
     */
    private function respondWithLocalVoucher(KodeVoucher $voucher): JsonResponse
    {
        if ($voucher->tanggal_kadaluarsa < now()->startOfDay()) {
            return response()->json([
                'success' => false,
                'message' => 'Kode voucher sudah kadaluarsa.',
            ], 400);
        }

        if ($voucher->digunakan >= $voucher->kuota) {
            return response()->json([
                'success' => false,
                'message' => 'Kuota voucher sudah habis.',
            ], 400);
        }

        Log::info('Voucher validated successfully', [
            'voucher_id' => $voucher->id,
            'voucher_code' => $voucher->kode,
            'event_id' => $voucher->id_event,
            'discount_per_ticket' => $voucher->nilai_diskon,
            'is_external' => (bool) $voucher->is_external,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Voucher '{$voucher->name_voucher}' berhasil diterapkan! Diskon Rp "
                . number_format($voucher->nilai_diskon, 0, ',', '.') . " per tiket",
            'discount_per_ticket' => $voucher->nilai_diskon,
            'voucher_id' => $voucher->id,
            'voucher_name' => $voucher->name_voucher,
            'is_external' => (bool) $voucher->is_external,
        ]);
    }

    /**
     * Validasi kode ke API eksternal; jika valid buat voucher lokal (kuota=1).
     */
    private function validateExternalVoucher(string $code, int $eventId, int $jumlahTiket): JsonResponse
    {
        $event = Event::findOrFail($eventId);
        $cfg = config('services.chatkebaikan');
        $url = rtrim($cfg['base_url'], '/') . str_replace('{kode}', $code, $cfg['validate_path']);

        try {
            $response = Http::timeout($cfg['timeout'])->get($url);
        } catch (\Throwable $e) {
            Log::error('External voucher validate request failed', [
                'kode' => $code,
                'error' => $e->getMessage(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghubungi sistem voucher eksternal. Coba lagi.',
            ], 400);
        }

        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghubungi sistem voucher eksternal. Coba lagi.',
            ], 400);
        }

        $api = $response->json();

        if (!($api['valid'] ?? false)) {
            return response()->json([
                'success' => false,
                'message' => $api['reason'] ?? 'Kode voucher tidak valid di sistem eksternal.',
            ], 400);
        }

        if ($jumlahTiket > 1) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher ini hanya berlaku untuk 1 volunteer (1 tiket).',
            ], 400);
        }

        $discountPercent = (int) ($api['discount_percent'] ?? 0);
        $nilaiDiskon = (int) round($event->harga * $discountPercent / 100);

        try {
            $voucher = KodeVoucher::create([
                'id_event' => $event->id,
                'name_voucher' => $api['reward_name'] ?? $code,
                'kode' => $code,
                'nilai_diskon' => $nilaiDiskon,
                'kuota' => 1,
                'digunakan' => 0,
                'tanggal_kadaluarsa' => Carbon::parse($api['berlaku_sampai'])->toDateString(),
                'status' => true,
                'is_external' => true,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Race / dobel: kode sudah dibuat proses lain. Pakai yang sudah ada.
            $existing = KodeVoucher::where('kode', $code)->where('id_event', $event->id)->first();
            if (!$existing) {
                throw $e;
            }
            return $this->respondWithLocalVoucher($existing);
        }

        Log::info('External voucher created at checkout', [
            'voucher_id' => $voucher->id,
            'kode' => $code,
            'event_id' => $event->id,
            'discount_percent' => $discountPercent,
            'nilai_diskon' => $nilaiDiskon,
        ]);

        return $this->respondWithLocalVoucher($voucher);
    }

    /**
     * Beri tahu API eksternal bahwa voucher sudah terpakai.
     * Lempar exception bila gagal agar transaksi di-rollback.
     */
    private function redeemExternalVoucher(KodeVoucher $voucher, Transaksi $transaksi): void
    {
        $cfg = config('services.chatkebaikan');
        $url = $cfg['redeem_url'] ?? '';

        if (empty($url)) {
            throw new \Exception('Endpoint redeem voucher eksternal belum dikonfigurasi.');
        }

        $method = strtoupper($cfg['redeem_method'] ?? 'POST');
        $payload = [
            'kode' => $voucher->kode,
        ];

        $response = Http::timeout($cfg['timeout'])
            ->acceptJson()
            ->withOptions(['allow_redirects' => ['strict' => true]]) // jaga POST tetap POST jika ada redirect 301/302
            ->send($method, $url, ['json' => $payload]);

        if (!$response->successful()) {
            Log::error('External voucher redeem failed', [
                'kode' => $voucher->kode,
                'invoice' => $transaksi->invoice,
                'status' => $response->status(),
            ]);
            throw new \Exception('Gagal redeem voucher eksternal (HTTP ' . $response->status() . ').');
        }

        Log::info('External voucher redeemed', [
            'kode' => $voucher->kode,
            'invoice' => $transaksi->invoice,
        ]);
    }
}
