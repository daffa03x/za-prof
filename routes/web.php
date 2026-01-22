<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PixelController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\VoucherController;

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

// Portal
Route::get('/', [PortalController::class, 'index'])->name('index');
Route::get('/view_content/{slug}', [PortalController::class, 'viewContent'])->name('view_content');
Route::get('/checkout/{slug}', [PortalController::class, 'checkout'])->name('checkout');
Route::get('/invoice/{invoice}', [PortalController::class, 'invoice'])->name('invoice');
Route::post('/transaksi/post/{slug}', [PortalController::class, 'transaksiPost'])->name('transaksi.post');
Route::get('/event-sostrip', [PortalController::class, 'program'])->name('event.sostrip');
Route::get('/portal/search', [PortalController::class, 'eventSearch'])->name('portal.search');
Route::get('/tiket/{invoice}', [PortalController::class, 'tiket'])->name('portal.tiket');


// Auth
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/auth', [AuthController::class, 'auth'])->name('auth');


Route::middleware('auth')->group(function () {
    // Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Search
    Route::get('/event/search', [EventController::class, 'search'])->name('event.search');
    Route::get('/transaksi/search', [TransaksiController::class, 'search'])->name('transaksi.search');
    Route::get('/payment/search', [PaymentController::class, 'search'])->name('payment.search');
    Route::get('/pixel/search', [PixelController::class, 'search'])->name('pixel.search');

    // Filter
    Route::get('/event/filter', [EventController::class, 'filter'])->name('event.filter');
    Route::get('/transaksi/filter', [TransaksiController::class, 'filter'])->name('transaksi.filter');

    // Export
    Route::post('/event/export', [EventController::class, 'export'])->name('event.export');
    Route::post('/transaksi/export', [TransaksiController::class, 'export'])->name('transaksi.export');

    // Status Update
    Route::post('/transaksi/update', [TransaksiController::class, 'updateStatus'])->name('transaksi.update');
    Route::put('/event/update-status/{event}', [EventController::class, 'updateStatus'])->name('event.update.status');

    // Event - Soft Delete Management
    Route::get('/event/trashed', [EventController::class, 'trashed'])->name('event.trashed');
    Route::post('/event/{id}/restore', [EventController::class, 'restore'])->name('event.restore');
    Route::delete('/event/{id}/force-delete', [EventController::class, 'forceDelete'])->name('event.forceDelete');

    // Transaksi - Soft Delete Management
    Route::get('/transaksi/trashed', [TransaksiController::class, 'trashed'])->name('transaksi.trashed');
    Route::post('/transaksi/{id}/restore', [TransaksiController::class, 'restore'])->name('transaksi.restore');
    Route::delete('/transaksi/{id}/force-delete', [TransaksiController::class, 'forceDelete'])->name('transaksi.forceDelete');

    // Payment - Soft Delete Management
    Route::get('/payment/trashed', [PaymentController::class, 'trashed'])->name('payment.trashed');
    Route::post('/payment/{id}/restore', [PaymentController::class, 'restore'])->name('payment.restore');
    Route::delete('/payment/{id}/force-delete', [PaymentController::class, 'forceDelete'])->name('payment.forceDelete');

    // Pixel - Soft Delete Management
    Route::get('/pixel/trashed', [PixelController::class, 'trashed'])->name('pixel.trashed');
    Route::post('/pixel/{id}/restore', [PixelController::class, 'restore'])->name('pixel.restore');
    Route::delete('/pixel/{id}/force-delete', [PixelController::class, 'forceDelete'])->name('pixel.forceDelete');

    // Voucher - Search
    Route::get('/voucher/search', [VoucherController::class, 'search'])->name('voucher.search');

    // Voucher - Soft Delete Management
    Route::get('/voucher/trashed', [VoucherController::class, 'trashed'])->name('voucher.trashed');
    Route::post('/voucher/{id}/restore', [VoucherController::class, 'restore'])->name('voucher.restore');
    Route::delete('/voucher/{id}/force-delete', [VoucherController::class, 'forceDelete'])->name('voucher.forceDelete');

    // Resource Routes
    Route::resource('/event', EventController::class);
    Route::resource('/transaksi', TransaksiController::class);
    Route::resource('/payment', PaymentController::class);
    Route::resource('/pixel', PixelController::class);
    Route::resource('/voucher', VoucherController::class);
});