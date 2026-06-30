<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransaksiStatusResource;
use App\Models\Transaksi;
use Illuminate\Http\JsonResponse;

class TransaksiController extends Controller
{
    public function show(string $invoice): JsonResponse
    {
        $transaksi = Transaksi::with(['event', 'payment'])
            ->where('invoice', $invoice)
            ->first();

        if (!$transaksi) {
            return response()->json(['success' => false, 'message' => 'Transaksi tidak ditemukan.'], 404);
        }

        return response()->json(['data' => new TransaksiStatusResource($transaksi)]);
    }
}
