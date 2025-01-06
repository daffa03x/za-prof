<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Campaign;
use App\Exports\CampaignExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreCampaignRequest;
use App\Http\Requests\UpdateCampaignRequest;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "campaign";

        $data = Campaign::whereNull('deleted_at')
                    ->orderBy('created_at', 'desc')
                    ->paginate(5);
        
        return view('admin.campaign.index', compact('title', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "add campaign";

        return view('admin.campaign.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCampaignRequest $request)
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

            Campaign::create($data);

            return redirect()->route('campaign.index')->with('success', 'Success');
        } catch (\Exception $e) {
            return redirect()->route('campaign.create')->with('error', 'Failed');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Campaign $campaign)
    {
        $title = "show campaign";
        $data = $campaign;

        return view('admin.campaign.show', compact('title', 'data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Campaign $campaign)
    {
        $title = "edit campaign";
        $data = $campaign;

        return view('admin.campaign.edit', compact('title', 'data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCampaignRequest $request, Campaign $campaign)
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

            // Update data campaign yang ada
            $campaign->update($data);

            return redirect()->route('campaign.index')->with('success', 'Campaign updated successfully');
        } catch (\Exception $e) {
            return redirect()->route('campaign.edit', $campaign->id)->with('error', 'Failed to update campaign');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $campaign)
    {
        try {
            $campaign->delete(); // Melakukan soft delete
            return redirect()->route('campaign.index')->with('success', 'success');
        } catch (\Exception $e) {
            return redirect()->route('campaign.index')->with('error', 'failed');
        }
    }

    public function export(Request $request)
    {
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;
        $mitra = $request->mitra;

        $data = Campaign::select(
                        'id',
                        'name',
                        'mitra',
                        'website',
                        'created_at'
                    )
                    ->whereNull('deleted_at')
                    ->orderBy('created_at', 'desc')
                    ->when($tanggal_awal && $tanggal_akhir, function ($query) use ($tanggal_awal, $tanggal_akhir) {
                        return $query->whereDate('created_at', '>=', $tanggal_awal)
                                    ->whereDate('created_at', '<=', $tanggal_akhir);
                    })
                    ->when($tanggal_awal && !$tanggal_akhir, function ($query) use ($tanggal_awal) {
                        return $query->whereDate('created_at', $tanggal_awal);
                    })
                    ->when($mitra, function ($query) use ($mitra) {
                        return $query->where('mitra', 'like', '%' . $mitra . '%');
                    })
                    ->get();
            
            // Convert tanggal di buat
            $formattedCampaigns = $data->map(function ($data) {
                return [
                    'id' => $data->id,
                    'name' => $data->name,
                    'mitra' => $data->mitra,
                    'website' => $data->website,
                    'created_at' => Carbon::parse($data->created_at)->format('d-m-Y h:i A'),
                ];
            });

        return Excel::download(new CampaignExport($formattedCampaigns), 'Campaign.xlsx');
    }

    public function filter(Request $request)
    {
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;
        $mitra = $request->mitra;

        $title = "campaign";

        $data = Campaign::whereNull('deleted_at')
                    ->orderBy('created_at', 'desc')
                    ->when($tanggal_awal && $tanggal_akhir, function ($query) use ($tanggal_awal, $tanggal_akhir) {
                        return $query->whereDate('created_at', '>=', $tanggal_awal)
                                    ->whereDate('created_at', '<=', $tanggal_akhir);
                    })
                    ->when($tanggal_awal && !$tanggal_akhir, function ($query) use ($tanggal_awal) {
                        return $query->whereDate('created_at', $tanggal_awal);
                    })
                    ->when($mitra, function ($query) use ($mitra) {
                        return $query->where('mitra', 'like', '%' . $mitra . '%');
                    })
                    ->paginate(5);

        return view('admin.campaign.index', compact('title', 'data'));
    }

    public function search(Request $request)
    {
        $title = "Search Campaign";   
        $search = $request->search;

        // Mulai query
        $query = Campaign::whereNull('deleted_at')
                        ->orderBy('created_at', 'desc');

        // Jika ada pencarian, terapkan filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('mitra', 'like', '%' . $search . '%')
                ->orWhere('website', 'like', '%' . $search . '%');
            });
        }

        // Ambil data dengan paginasi
        $data = $query->paginate(5);

        return view('admin.campaign.index', compact('title', 'data'));
    }


}
