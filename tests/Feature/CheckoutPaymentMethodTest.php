<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Payment;
use Illuminate\Support\Collection;
use Tests\TestCase;

class CheckoutPaymentMethodTest extends TestCase
{
    public function test_checkout_hides_payment_method_choices_and_uses_midtrans_default(): void
    {
        $event = new Event([
            'id' => 10,
            'name' => 'Social Trip',
            'slug' => 'social-trip',
            'mitra' => 'Zillenial Action',
            'waktu_mulai' => now()->addWeek(),
            'nama_tempat' => 'Bandung',
            'status' => true,
            'jumlah_tiket' => 10,
            'harga' => 150000,
        ]);
        $manual = new Payment([
            'name' => 'Transfer Manual',
            'image' => 'assets/img/payment/manual.png',
            'no_rek' => '1234567890',
            'type' => 'manual',
            'status' => true,
        ]);
        $manual->id = 1;
        $midtrans = new Payment([
            'name' => 'Midtrans',
            'image' => 'assets/img/payment/midtrans.png',
            'no_rek' => 'MIDTRANS',
            'type' => 'midtrans',
            'status' => true,
        ]);
        $midtrans->id = 2;
        $payment = new Collection([$manual, $midtrans]);
        $defaultPayment = $midtrans;

        $this->withViewErrors([]);

        $response = $this->view('portal.checkout', compact('event', 'payment', 'defaultPayment') + [
            'data' => $event,
        ]);

        $response->assertDontSee('Metode Pembayaran');
        $response->assertDontSee('type="radio"', false);
        $response->assertSee('type="hidden" name="payment" value="'.$midtrans->id.'"', false);
    }
}
