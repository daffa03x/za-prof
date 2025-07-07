<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Payment;
use App\Models\Transaksi;
use Illuminate\Http\Request;
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

    public function transaksiPost(Request $request, $id)
    {
        // ... sebelum try ...
        DB::beginTransaction();
        try {
            $affectedRows = Event::where('id', $id)
                                ->where('jumlah_tiket', '>=', $request->jumlah_tiket)
                                ->decrement('jumlah_tiket', $request->jumlah_tiket);

            if ($affectedRows === 0) {
                // Ini berarti tiket tidak cukup atau event tidak ditemukan
                throw new \Exception("Tiket tidak mencukupi atau event tidak valid.");
            }

            $event = Event::find($id); // Ambil data event yang sudah terupdate
            if (!$event) { // Tambahkan pengecekan jika event tidak ditemukan setelah decrement
                throw new \Exception("Event tidak ditemukan setelah decrement.");
            }

            // Buat nomor invoice
            $invoice = date('YmdHis') . uniqid();

            $query = [
                'id_event' => $id,
                'invoice' => $invoice,
                'jumlah_tiket' => $request->jumlah_tiket,
                'total_pembayaran' => $request->price, // HATI-HATI DENGAN INI (lihat poin 2)
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

            $transaksi = Transaksi::create($query);
            DB::commit();
            return redirect("/invoice/$transaksi->id")->with('success', 'Success');

        } catch (\Exception $e) {
            DB::rollback();
            return dd($e->getMessage());
        }
    }

    public function invoice($id) 
    {
        try {
            $data = Transaksi::with('event','payment')->find($id);
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
