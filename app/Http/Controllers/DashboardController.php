<?php

namespace App\Http\Controllers;

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

        return view('admin.dashboard.index', compact('title'));
    }
}
