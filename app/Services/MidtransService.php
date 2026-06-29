<?php

namespace App\Services;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Midtrans\Config;
use Midtrans\Snap;

/**
 * Class MidtransService
 *
 * Mengelola integrasi dengan Midtrans Payment Gateway.
 * Handles: Snap token creation, payment notification verification.
 */
class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Buat Snap token untuk transaksi yang diberikan.
     * Kembalikan string token atau lempar exception jika gagal.
     *
     * @throws \Exception
     */
    public function createSnapToken(Transaksi $transaksi): string
    {
        $params = [
            'transaction_details' => [
                'order_id' => $transaksi->invoice,
                'gross_amount' => (int) $transaksi->total_pembayaran,
            ],
            'customer_details' => [
                'first_name' => $transaksi->name,
                'email' => $transaksi->email,
                'phone' => $transaksi->telepon,
            ],
            'item_details' => [
                [
                    'id' => 'TIKET-'.$transaksi->id_event,
                    'price' => (int) ($transaksi->total_pembayaran / $transaksi->jumlah_tiket),
                    'quantity' => (int) $transaksi->jumlah_tiket,
                    'name' => 'Tiket '.($transaksi->event?->name ?? 'Event'),
                ],
            ],
            'callbacks' => [
                'finish' => url('/midtrans/finish/'.$transaksi->invoice),
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        Log::info('Midtrans Snap token created', [
            'invoice' => $transaksi->invoice,
            'order_id' => $transaksi->invoice,
            'gross_amount' => $transaksi->total_pembayaran,
        ]);

        return $snapToken;
    }

    /**
     * Verifikasi dan parsing notifikasi dari Midtrans.
     * Kembalikan array dengan order_id, transaction_status, fraud_status.
     *
     * @throws \Exception
     */
    public function parseNotification(Request $request): array
    {
        $payload = $request->json()->all();

        if (empty($payload)) {
            $payload = $request->all();
        }

        foreach (['order_id', 'transaction_status', 'gross_amount', 'status_code', 'signature_key'] as $field) {
            if (empty($payload[$field])) {
                throw new InvalidArgumentException("Missing Midtrans notification field: {$field}.");
            }
        }

        $serverKey = config('midtrans.server_key');
        if (empty($serverKey)) {
            throw new InvalidArgumentException('Midtrans server key is not configured.');
        }

        $expectedSignature = hash(
            'sha512',
            $payload['order_id'].$payload['status_code'].$payload['gross_amount'].$serverKey
        );

        if (! hash_equals($expectedSignature, (string) $payload['signature_key'])) {
            throw new InvalidArgumentException('Invalid Midtrans signature.');
        }

        $orderId = (string) $payload['order_id'];
        $transactionStatus = (string) $payload['transaction_status'];
        $fraudStatus = $payload['fraud_status'] ?? null;
        $paymentType = $payload['payment_type'] ?? null;
        $grossAmount = (string) $payload['gross_amount'];

        Log::info('Midtrans notification received', [
            'order_id' => $orderId,
            'transaction_status' => $transactionStatus,
            'fraud_status' => $fraudStatus,
            'payment_type' => $paymentType,
        ]);

        return [
            'order_id' => $orderId,
            'transaction_status' => $transactionStatus,
            'fraud_status' => $fraudStatus,
            'payment_type' => $paymentType,
            'gross_amount' => $grossAmount,
            'status_code' => (string) $payload['status_code'],
        ];
    }

    /**
     * Tentukan status_pembayaran berdasarkan transaction_status dan fraud_status Midtrans.
     */
    public function resolveStatus(string $transactionStatus, ?string $fraudStatus): ?string
    {
        if ($transactionStatus === 'capture') {
            return $fraudStatus === 'accept' ? 'Success' : 'Failed';
        }

        if ($transactionStatus === 'settlement') {
            return 'Success';
        }

        if (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            return 'Failed';
        }

        if ($transactionStatus === 'pending') {
            return 'Pending';
        }

        return null; // Status tidak dikenali, abaikan
    }
}
