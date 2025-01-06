<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Payment;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTransaksiPortalRequest;
use Illuminate\Support\Facades\DB;

class PortalController extends Controller
{
    public function event_search(Request $request)
    {
        $search = $request->search;

        // Mulai query
        $query = Event::whereNull('deleted_at')
                        ->orderBy('created_at', 'desc');

        // Jika ada pencarian, terapkan filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('mitra', 'like', '%' . $search . '%');
            });
        }

        // Ambil data dengan paginasi
        $event = $query->paginate(12);

        return view('portal.program', compact('event'));
    }

    public function index(Request $request)
    {
        $event = Event::get();

        return view('portal.index', compact('event'));
    }

    public function view_content(Request $request, $id)
    {
        $data = Event::where('id', $id)->first();

        return view('portal.view_content', compact('data'));
    }

    public function checkout(Request $request, $id)
    {
        $data = Event::where('id', $id)->first();
        $payment = Payment::get();

        return view('portal.checkout', compact('data', 'payment'));
    }

    public function invoice(StoreTransaksiPortalRequest $request, $id) 
    {
        try {
            $event = Event::find($id);
            
            // Buat nomor invoice
            $transaksi = Transaksi::orderBy('ID', 'desc')->first();
            $invoice = date('Ymd') . $id + 1;

            $query = [
                'id_event' => $id,
                'invoice' => $invoice,
                'jumlah_tiket' => $request->price / $event->harga,
                'total_pembayaran' => $request->price,
                'name' => $request->name,
                'telepon' => $request->telepon,
                'email' => $request->email,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'status_pembayaran' => 'Pending',
                'tanggal_register' => now(),
                'tanggal_pembayaran' => null,
                'id_payment' => $request->payment,
            ];

            Transaksi::create($query);

            $data = Transaksi::with('event')->where('invoice', $invoice)->first();

            return view('portal.invoice', compact('data'));
        } catch (\Exception $e) {
            return redirect()->route('portal.checkout', ['id' => $id])->with('error', 'Failed');
        }
    }

    public function program(Request $request)
    {
            $event = Event::whereNull('deleted_at')
                    ->orderBy('created_at', 'desc')
                    ->paginate(12);
            
            return view('portal.program', compact('event'));
    }
    
}
