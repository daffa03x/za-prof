<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Transaksi;
use Illuminate\View\View;

/**
 * Class DashboardController
 *
 * Handles the dashboard display.
 */
class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(): View
    {
        $title = 'Dashboard';

        $stats = [
            'events'            => Event::count(),
            'transaksi_total'   => Transaksi::count(),
            'transaksi_sukses'  => Transaksi::where('status_pembayaran', 'Success')->count(),
            'transaksi_pending' => Transaksi::where('status_pembayaran', 'Pending')->count(),
            'pendapatan'        => (int) Transaksi::where('status_pembayaran', 'Success')->sum('total_pembayaran'),
        ];

        $recent = Transaksi::latest('id')->take(6)->get();

        return view('admin.dashboard.index', compact('title', 'stats', 'recent'));
    }
}
