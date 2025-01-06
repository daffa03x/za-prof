<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Transaksi;
use App\Models\Event;
use App\Models\Payment;
use App\Exports\ExportTransaksi;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\StoreTransaksiRequest;
use App\Http\Requests\UpdateTransaksiRequest;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Transaksi';

        $payment = Payment::get();

        $data = Transaksi::select('transaksis.*', 'payments.name as name_payment', 'events.name as name_event')
                    ->whereNull('transaksis.deleted_at')
                    ->orderBy('transaksis.created_at', 'desc')
                    ->leftJoin('payments', 'transaksis.id_payment', '=', 'payments.id')
                    ->leftJoin('events', 'transaksis.id_event', '=', 'events.id')
                    ->paginate(5);
        
        return view('admin.transaksi.index', compact('title', 'data', 'payment'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Create Transaksi';
        $payment = Payment::get();

        return view('admin.transaksi.create', compact('title', 'payment'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransaksiRequest $request)
    {
        try {
            $query = $request->validated();
            
            $event = Event::find($query['id_event']);
            
            // Buat nomor invoice
            $transaksi = Transaksi::orderBy('ID', 'desc')->first();
            $invoice = date('Ymd') . $transaksi->id + 1;

            $data = [
                'id_event' => $query['id_event'],
                'invoice' => $invoice,
                'jumlah_tiket' => $query['jumlah_tiket'],
                'total_pembayaran' => $query['jumlah_tiket'] * $event->harga,
                'name' => $query['name'],
                'email' => $query['email'],
                'telepon' => $query['telepon'],
                'jenis_kelamin' => $query['jenis_kelamin'],
                'tanggal_lahir' => $query['tanggal_lahir'],
                'status_pembayaran' => 'Pending',
                'tanggal_register' => now(),
                'tanggal_pembayaran' => null,
                'id_payment' => $query['id_payment']
            ];

            $transaksi = Transaksi::create($data);
            
            return redirect()->route('transaksi.index')->with('success', 'Success');
        } catch (\Exception $e) {
            return redirect()->route('transaksi.create')->with('error', 'Failed');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Transaksi $transaksi)
    {
        $title = 'Show Transaksi';

        $data = $transaksi->load('event', 'payment');

        return view('admin.transaksi.show', compact('title', 'data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        $title = 'Edit Transaksi';
        $payment = Payment::get();
        $data = $transaksi;

        return view('admin.transaksi.edit', compact('title','data', 'payment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransaksiRequest $request, Transaksi $transaksi)
    {
        try {
            $query = $request->validated();

            $event = Event::find($query['id_event']);

            $data = [
                'id_event' => $query['id_event'],
                'jumlah_tiket' => $query['jumlah_tiket'],
                'total_pembayaran' => $query['jumlah_tiket'] * $event->harga,
                'name' => $query['name'],
                'email' => $query['email'],
                'telepon' => $query['telepon'],
                'jenis_kelamin' => $query['jenis_kelamin'],
                'tanggal_lahir' => $query['tanggal_lahir'],
                'status_pembayaran' => 'Pending', // Jika status tetap Pending
                'tanggal_register' => now(),
                'tanggal_pembayaran' => null,
                'id_payment' => $query['id_payment']
            ];

            // Hanya update data selain invoice
            $transaksi->update($data);

            return redirect()->route('transaksi.index')->with('success', 'Success');
        } catch (\Exception $e) {
            return redirect()->route('transaksi.index')->with('error', 'Failed');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {
        try {
            $transaksi->delete(); //soft delete
            return redirect()->route('transaksi.index')->with('success', 'success');
        } catch (\Exception $e) {
            return redirect()->route('transaksi.index')->with('error', 'failed');
        }
    }

    public function export(Request $request)
    {
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;
        $id_event = $request->id_event;
        $status_pembayaran = $request->status_pembayaran;
        $payment_id = $request->payment_id;
        
        $data = Transaksi::select(
                            'transaksis.id',
                            'transaksis.id_event', 
                            'transaksis.invoice',
                            'events.name as name_event',
                            'transaksis.invoice',
                            'transaksis.name',
                            'transaksis.email',
                            'transaksis.telepon',
                            'transaksis.jenis_kelamin',
                            'transaksis.tanggal_lahir',
                            'transaksis.status_pembayaran',
                            'transaksis.tanggal_register',
                            'transaksis.tanggal_pembayaran',
                            'payments.name as name_payment',
                            'transaksis.created_at'
                        )
                        ->whereNull('transaksis.deleted_at')
                        ->leftJoin('payments', 'transaksis.payment_id', '=', 'payments.id')
                        ->leftJoin('events', 'transaksis.id_event', '=', 'events.id')
                        ->orderBy('transaksis.created_at', 'desc')
                        ->when($tanggal_awal && $tanggal_akhir, function ($query) use ($tanggal_awal, $tanggal_akhir) {
                            return $query->whereDate('transaksis.created_at', '>=', $tanggal_awal)
                                        ->whereDate('transaksis.created_at', '<=', $tanggal_akhir);
                        })
                        ->when($tanggal_awal && !$tanggal_akhir, function ($query) use ($tanggal_awal) {
                            return $query->whereDate('transaksis.created_at', $tanggal_awal);
                        })
                        ->when($id_event, function ($query) use ($id_event) {
                            return $query->where('transaksis.id_event', $id_event);
                        })
                        ->when($status_pembayaran, function ($query) use ($status_pembayaran) {
                            return $query->where('status_pembayaran', $status_pembayaran);
                        })
                        ->when($payment_id, function ($query) use ($payment_id) {
                            return $query->where('transaksis.payment_id', $payment_id);
                        })
                    ->get();
                    
            // Convert tanggal di buat
            $formattedTransaksi = $data->map(function ($data) {
                return [
                    'id' => $data->id,
                    'id_event' => $data->id_event,
                    'event' => $data->name_event,
                    'invoice' => $data->invoice,
                    'name' => $data->name,
                    'email' => $data->email,
                    'telepon' => $data->telepon,
                    'jenis_kelamin' =>$data->jenis_kelamin,
                    'tanggal_lahir' =>Carbon::parse($data->tanggal_lahir)->format('d-m-y'),
                    'status_pembayaran' =>$data->status_pembayaran,
                    'tanggal_register' =>Carbon::parse($data->tanggal_register)->format('d-m-y h:i A'),
                    'tanggal_pembayaran' =>Carbon::parse($data->tanggal_pembayaran)->format('d-m-y h:i A'),
                    'payment_id' => $data->name_payment,
                    'created_at' => Carbon::parse($data->created_at)->format('d-m-Y h:i A'),
                ];
            });

        return Excel::download(new ExportTransaksi($formattedTransaksi), 'Transaksi.xlsx');
    }

    public function filter(Request $request)
    {
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;
        $id_event =  $request->id_event;
        $status_pembayaran = $request->status_pembayaran;
        $payment_id = $request->payment_id;

        $title = 'Filter Transaksi';
        $payment = Payment::get();
        $data = Transaksi::select('transaksis.*', 'events.name as name_event', 'payments.name as name_payment')
                        ->whereNull('transaksis.deleted_at')
                        ->orderBy('transaksis.created_at', 'desc')
                        ->leftJoin('events', 'transaksis.id_event', '=', 'events.id')
                        ->leftJoin('payments', 'transaksis.payment', '=', 'payments.id')
                        ->when($tanggal_awal && $tanggal_akhir, function ($query) use ($tanggal_awal, $tanggal_akhir) {
                            return $query->whereDate('transaksis.created_at', '>=', $tanggal_awal)
                                        ->whereDate('transaksis.created_at', '<=', $tanggal_akhir);
                        })
                        ->when($tanggal_awal && !$tanggal_akhir, function ($query) use ($tanggal_awal) {
                            return $query->whereDate('transaksis.created_at', $tanggal_awal);
                        })
                        ->when($id_event, function ($query) use ($id_event) {
                            return $query->where('transaksis.id_event', $id_event);
                        })
                        ->when($status_pembayaran, function ($query) use ($status_pembayaran) {
                            return $query->where('status_pembayaran', $status_pembayaran);
                        })
                        ->when($payment_id, function ($query) use ($payment_id) {
                            return $query->where('transaksis.payment', $payment_id);
                        })
                        ->paginate(5);
                        
        return view('admin.transaksi.index', compact('title', 'data', 'payment'));
    }

    public function search(Request $request)
    {
        $search = $request->search;

        $title = 'Search Transaksi';
        
        $payment = Payment::get();

        $query = Transaksi::select('transaksis.*', 'events.name as name_event', 'payments.name as name_payment')
                        ->whereNull('transaksis.deleted_at')
                        ->orderBy('transaksis.created_at', 'desc')
                        ->leftJoin('events', 'transaksis.id_event', '=', 'events.id')
                        ->leftJoin('payments', 'transaksis.id_payment', '=', 'payments.id');

                        if ($search) {
                            $query->where(function ($q) use ($search){
                                $q->where('transaksis.id', 'like', '%' . $search . '%')
                                ->orWhere('transaksis.invoice', 'like', '%' . $search . '%')
                                ->orWhere('transaksis.name', 'like', '%' . $search . '%')
                                ->orWhere('transaksis.telepon', 'like', '%' . $search . '%')
                                ->orWhere('transaksis.email', 'like', '%' . $search . '%')
                                ->orWhere('events.name', 'like', '%' . $search . '%');
                            });
                        }

        $data =  $query->paginate(5);

        return view('admin.transaksi.index', compact('title', 'data', 'payment'));
    }

    public function update_status(Request $request)
    {
        $id = $request->id;

        $transaksi = Transaksi::find($id);

        if ($transaksi) {
            $data = [
                'status_pembayaran' => 'Success',
                'tanggal_pembayaran' => now(),
            ];

            $transaksi->update($data);

            return response()->json(['message' => 'Status berhasil diperbarui!'], 200);
        }

        return response()->json(['message' => 'Item tidak ditemukan!'], 404);
    }

}
