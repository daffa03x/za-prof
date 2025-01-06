<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Event;
use App\Exports\EventExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Event';

        $data = Event::whereNull('deleted_at')
                        ->orderBy('created_at', 'desc')
                        ->paginate(5);
                        
        return view('admin.event.index', compact('title', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Add Event';

        return view('admin.event.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request)
    {
        
        try {
            $data = $request->validated();

            // Cek apakah ada file logo yang diupload
            if ($request->hasFile('image')) {
                
                $year = date('Y');  
                $month = date('m'); 

                // Tentukan path folder berdasarkan tahun dan bulan
                $folderPath = 'images/' . $year . '/' . $month;

                // Simpan file ke folder berdasarkan tahun dan bulan
                $imagePath = $request->file('image')->store($folderPath, 'public');
                
                // Simpan path gambar ke dalam data array untuk disimpan ke database
                $data['image'] = $imagePath;

            }

            // Status default
            $data['status'] = 1;

            Event::create($data);

            return redirect()->route('event.index')->with('success', 'Success');
        } catch (\Exception $e) {
            return redirect()->route('event.create')->with('error', 'Failed');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $title = 'Show Event';
        $data = $event;

        return view('admin.event.show', compact('title', 'data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $title = 'Edit Event';

        $data = $event;

        return view('admin.event.edit', compact('title', 'data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        try {
            // Validasi data request
            $data = $request->validated();

            // Cek apakah ada file logo yang diupload
            if ($request->hasFile('image')) {
                
                $year = date('Y');  
                $month = date('m'); 

                // Tentukan path folder berdasarkan tahun dan bulan
                $folderPath = 'images/' . $year . '/' . $month;

                // Simpan file ke folder berdasarkan tahun dan bulan
                $imagePath = $request->file('image')->store($folderPath, 'public');
                
                // Simpan path gambar ke dalam data array untuk disimpan ke database
                $data['image'] = $imagePath;
            }

            // Update data event yang ada
            $event->update($data);

            return redirect()->route('event.index')->with('success', 'Event updated successfully');
        } catch (\Exception $e) {
            return redirect()->route('event.edit', $data->id)->with('error', 'Failed to update event');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        try {
            $event->delete(); // Melakukan soft delete
            return redirect()->route('event.index')->with('success', 'success');
        } catch (\Exception $e) {
            return redirect()->route('event.index')->with('error', 'failed');
        }
    }

    public function export(Request $request)
    {
        $waktu_awal = $request->waktu_awal;
        $waktu_akhir = $request->waktu_akhir;
        $mitra = $request->mitra;
        $status = $request->status;

        $data = Event::select(
                        'id',
                        'name',
                        'mitra',
                        'website',
                        'status',
                        'waktu_mulai',
                        'waktu_berakhir',
                        'nama_tempat',
                        'alamat',
                        'kota',
                        'jumlah_tiket',
                        'harga',
                        'created_at'
                    )
                    ->whereNull('deleted_at')
                    ->orderBy('created_at', 'desc')
                    ->when($waktu_awal && $waktu_akhir, function ($query) use ($waktu_awal, $waktu_akhir) {
                        return $query->whereDate('created_at', '>=', $waktu_awal)
                                    ->whereDate('created_at', '<=', $waktu_akhir);
                    })
                    ->when($waktu_awal && !$waktu_akhir, function ($query) use ($waktu_awal) {
                        return $query->whereDate('created_at', $waktu_awal);
                    })
                    ->when($mitra, function ($query) use ($mitra) {
                        return $query->where('mitra', 'like', '%' . $mitra . '%');
                    })
                    ->when(isset($status), function ($query) use ($status) {
                        return $query->where('status', $status);
                    })
                    ->get();
            
                // Convert waktu di buat
                $formattedEvents = $data->map(function ($data) {
                    return [
                        'id' => $data->id,
                        'name' => $data->name,
                        'mitra' => $data->mitra,
                        'website' => $data->website,
                        'status' => $data->status == 1 ? 'Aktif' : 'Tidak Aktif',
                        'waktu_mulai' => Carbon::parse($data->waktu_mulai)->format('d-m-Y'),
                        'waktu_berakhir' => Carbon::parse($data->waktu_berakhir)->format('d-m-Y'),
                        'nama_tempat' => $data->nama_tempat,
                        'alamat' => $data->alamat,
                        'kota' => $data->kota,
                        'jumlah_tiket' => $data->jumlah_tiket,
                        'harga' => $data->harga,
                        'created_at' => Carbon::parse($data->created_at)->format('d-m-Y h:i A'),
                    ];
                });

        return Excel::download(new EventExport($formattedEvents), 'Event.xlsx');
    }

    public function filter(Request $request)
    {
        $waktu_awal = $request->waktu_awal;
        $waktu_akhir = $request->waktu_akhir;
        $mitra = $request->mitra;
        $status = $request->status;

        $title = 'Filter Event';

        $data = Event::whereNull('deleted_at')
                        ->orderBy('created_at', 'desc')
                        ->when($waktu_awal && $waktu_akhir, function ($query) use ($waktu_awal, $waktu_akhir) {
                            return $query->whereDate('created_at', '>=', $waktu_awal)
                                        ->whereDate('created_at', '<=', $waktu_akhir);
                        })
                        ->when($waktu_awal && !$waktu_akhir, function ($query) use ($waktu_awal) {
                            return $query->whereDate('created_at', $waktu_awal);
                        })
                        ->when(isset($status), function ($query) use ($status) {
                            return $query->where('status', $status);
                        })
                        ->when($mitra, function ($query) use ($mitra) {
                            return $query->where('mitra', 'like', '%' . $mitra . '%');
                        })
                        ->paginate(5);
        
        return view('admin.event.index', compact('title', 'data'));
        
    }

    public function search(Request $request)
    {
        $seacrh = $request->search;

        $title = 'Search event';

        $query = Event::whereNull('deleted_at')
                    ->orderBy('created_at', 'desc');

                    if ($seacrh) {
                        $query->where(function ($q) use ($seacrh){
                            $q->where('name', 'like', '%' . $seacrh . '%')
                            ->orWhere('id', 'like', '%' . $seacrh . '%')
                            ->orWhere('mitra', 'like', '%' . $seacrh . '%')
                            ->orWhere('website', 'like', '%' . $seacrh . '%');
                        });
                    }

        $data = $query->paginate(5);

        return view('admin.event.index', compact('title', 'data'));
    }
}
