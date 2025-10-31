<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Payment;
use App\Models\Transaksi;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        $event = Event::orderByDesc('id')->limit(6)->get();

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

        $request->validate([
            'jumlah_tiket' => 'required|integer|min:1',
            'payment' => 'required',
            'pengunjung' => 'required|array',
            'pengunjung.*.name' => 'required|string',
            'pengunjung.*.telepon' => 'required|string',
            'pengunjung.*.email' => 'required|email',
        ]);

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
                'total_pembayaran' => $request->price,
                'name' => $request->pengunjung[0]['name'],
                'telepon' => $request->pengunjung[0]['telepon'],
                'email' => $request->pengunjung[0]['email'],
                'status_pembayaran' => 'Pending',
                'tanggal_register' => now(),
                'tanggal_pembayaran' => null,
                'id_payment' => $request->payment,
            ];

            $transaksi = Transaksi::create($query);

            // Loop untuk setiap pengunjung dan simpan ke pivot table
            foreach ($request->pengunjung as $pengunjungData) {
                // Cek apakah volunteer dengan email ini sudah ada
                $volunteer = Volunteer::where('email', $pengunjungData['email'])->first();

                if (!$volunteer) {
                    // Jika belum ada, buat baru
                    $volunteer = Volunteer::create([
                        'name' => $pengunjungData['name'],
                        'telepon' => $pengunjungData['telepon'],
                        'email' => $pengunjungData['email'],
                    ]);
                }

                // Hubungkan ke transaksi (gunakan attach untuk hindari duplikat insert manual)
                $transaksi->volunteers()->syncWithoutDetaching([$volunteer->id]);
            }

            DB::commit();
            return redirect("/invoice/$transaksi->invoice")->with('success', 'Success');

        } catch (\Exception $e) {
            DB::rollback();
            return dd($e->getMessage());
        }
    }

    public function invoice($invoice) 
    {
        try {
            $data = Transaksi::with('event','payment','volunteers')->where('invoice',$invoice)->first();
            if($data == null){
                return view('portal.error_tiket');
            }else{
                // Ambil tanggal pembuatan data
                $tanggalDibuat = Carbon::parse($data->tanggal_register); // Pastikan ini mengakses kolom created_at yang benar

                // Hitung tanggal 1 hari dari hari ini
                $batasWaktu = Carbon::now()->subDays(1); // Ini akan menjadi "kemarin"

                // Periksa apakah waktu pembuatan lebih dari 1 hari yang lalu (yaitu, sebelum kemarin)
                if ($tanggalDibuat->lessThan($batasWaktu)) {
                    // Jika sudah lebih dari 1 hari, arahkan ke view lain (misalnya halaman kadaluarsa)
                    // return view('portal.invoice_kadaluarsa');
                    return view('portal.error_tiket');
                } else {
                    // Jika belum kadaluarsa, tampilkan view invoice biasa
                    return view('portal.invoice', compact('data'));
                }
            }
        } catch (\Exception $e) {
            return redirect()->route('portal.checkout', ['invoice' => $invoice])->with('error', 'Failed');
        }
    }

    public function program(Request $request)
    {
        $event = Event::whereNull('deleted_at')
                ->orderBy('created_at', 'desc')
                ->paginate(9);
        
        return view('portal.program', compact('event'));
    }

    public function tiket($invoice)
    {
        try {
            $transaksi = Transaksi::with("event", "volunteers")->where('invoice',$invoice)->where('status_pembayaran',"Success")->first();
            if($transaksi){
                return view('portal.tiket', compact('transaksi'));
            }else{
                return view('portal.error_tiket');
            }
        } catch (\Exception $e) {
            return redirect('portal.error-tiket')->with('error', 'Failed');
        }
    }
}
