<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PixelController;
use App\Http\Controllers\PortalController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Portal
Route::get('/', [PortalController::class, 'index'])->name('index');
Route::get('/view_content/{id}', [PortalController::class, 'view_content'])->name('view_content');
Route::get('/checkout/{id}', [PortalController::class, 'checkout'])->name('checkout');
Route::get('/invoice/{id}', [PortalController::class, 'invoice'])->name('invoice');
Route::post('/transaksi/post/{id}', [PortalController::class, 'transaksiPost'])->name('transaksi.post');
Route::get('/program', [PortalController::class, 'program'])->name('program');
Route::get('/portal/search', [PortalController::class, 'event_search'])->name('portal.search');
Route::get('/tiket/{invoice}', [PortalController::class, 'tiket'])->name('portal.tiket');


// Auth
Route::get('/login', [AuthController::class, 'login'])->name('login');
// Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/auth', [AuthController::class, 'auth'])->name('auth');

// Route::get('/send-email',function(){
//     $transactionId = 'TXN-123456789'; // Ganti dengan ID transaksi aktual dari database/response pembayaran
//     $ticketUrl = 'https://your-app.com/tickets/view/abc123def'; // Ganti dengan URL tiket aktual Anda

//     Mail::to('dafaputra876@gmail.com')->send(new SendTicket($transactionId, $ticketUrl));
   
//     dd("Berikut no invoice $transactionId dan Link tiket $ticketUrl");
// });


Route::middleware('auth')->group(function () {
    // Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Search
    Route::get('/campaign/search', [CampaignController::class, 'search'])->name('campaign.search');
    Route::get('/event/search', [EventController::class, 'search'])->name('event.search');
    Route::get('/transaksi/search', [TransaksiController::class, 'search'])->name('transaksi.search');
    Route::get('/payment/search', [PaymentController::class, 'search'])->name('payment.search');
    Route::get('/pixel/search', [PixelController::class, 'search'])->name('pixel.search');

    // Filter
    Route::get('/campaign/filter', [CampaignController::class, 'filter'])->name('campaign.filter');
    Route::get('/event/filter', [EventController::class, 'filter'])->name('event.filter');
    Route::get('/transaksi/filter', [TransaksiController::class, 'filter'])->name('transaksi.filter');

    // Export
    Route::post('/campaign/export', [CampaignController::class, 'export'])->name('campaign.export');
    Route::post('/event/export', [EventController::class, 'export'])->name('event.export');
    Route::post('/transaksi/export', [TransaksiController::class, 'export'])->name('transaksi.export');
    Route::post('/transaksi/update', [TransaksiController::class, 'update_status'])->name('transaksi.update');
    Route::put('/event/update-status/{id}', [EventController::class, 'update_status'])->name('event.update.status');

    // Campaign
    Route::resource('/campaign', CampaignController::class);

    // Event
    Route::resource('/event', EventController::class);

    // Transaksi
    Route::resource('/transaksi', TransaksiController::class);

    // Payment
    Route::resource('/payment', PaymentController::class);

    // Pixel
    Route::resource('/pixel', PixelController::class);
});