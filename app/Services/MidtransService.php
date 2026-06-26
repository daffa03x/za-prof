<?php

namespace App\Services;

use App\Models\Transaksi;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

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
        Config::$serverKey    = config('midtrans.server_key');
        Config::$clientKey    = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = config('midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.is_3ds');
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
                'order_id'     => $transaksi->invoice,
                'gross_amount' => (int) $transaksi->total_pembayaran,
            ],
            'customer_details' => [
                'first_name' => $transaksi->name,
                'email'      => $transaksi->email,
                'phone'      => $transaksi->telepon,
            ],
            'item_details' => [
                [
                    'id'       => 'TIKET-' . $transaksi->id_event,
                    'price'    => (int) ($transaksi->total_pembayaran / $transaksi->jumlah_tiket),
                    'quantity' => (int) $transaksi->jumlah_tiket,
                    'name'     => 'Tiket ' . ($transaksi->event?->name ?? 'Event'),
                ],
            ],
            'callbacks' => [
                'finish' => url('/midtrans/finish/' . $transaksi->invoice),
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        Log::info('Midtrans Snap token created', [
            'invoice'    => $transaksi->invoice,
            'order_id'   => $transaksi->invoice,
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
    public function parseNotification(): array
    {
        $notification = new Notification();

        $orderId           = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $fraudStatus       = $notification->fraud_status;
        $paymentType       = $notification->payment_type;
        $grossAmount       = $notification->gross_amount;

        Log::info('Midtrans notification received', [
            'order_id'           => $orderId,
            'transaction_status' => $transactionStatus,
            'fraud_status'       => $fraudStatus,
            'payment_type'       => $paymentType,
        ]);

        return [
            'order_id'           => $orderId,
            'transaction_status' => $transactionStatus,
            'fraud_status'       => $fraudStatus,
            'payment_type'       => $paymentType,
            'gross_amount'       => $grossAmount,
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