<?php

namespace App\Http\Controllers;

use App\Models\Pixel;
use App\Http\Requests\StorePixelRequest;
use App\Http\Requests\UpdatePixelRequest;
use Illuminate\Http\Request;

class PixelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Pixel';

        $data = Pixel::whereNull('deleted_at')
                    ->orderBy('created_at', 'desc')
                    ->paginate(5);
        
        return view('admin.pixel.index', compact('title', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Add Pixel';

        return view('admin.pixel.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePixelRequest $request)
    {
        try {
            $data = $request->validated();

            // Status default
            $data['status'] = 1;

            Pixel::create($data);

            return redirect()->route('pixel.index')->with('success', 'Success');
        } catch (\Exception $e) {
            return redirect()->route('pixel.create')->with('error', 'Failed');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pixel $pixel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pixel $pixel)
    {
        $title = 'Edit Pixel';

        $data = $pixel;

        return view('admin.pixel.edit', compact('title', 'data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePixelRequest $request, Pixel $pixel)
    {
        try {
            $data = $request->validated();

            // Status default
            $data['status'] = 1;

            $pixel->update($data);

            return redirect()->route('pixel.index')->with('success', 'Success');
        } catch (\Exception $e) {
            return redirect()->route('pixel.create')->with('error', 'Failed');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pixel $pixel)
    {
        try {
            $pixel->delete(); // Melakukan soft delete
            return redirect()->route('pixel.index')->with('success', 'success');
        } catch (\Exception $e) {
            return redirect()->route('pixel.index')->with('error', 'failed');
        }
    }

    public function search(Request $request)
    {
        $title = "Search Pixel";   
        $search = $request->search;

        // Mulai query
        $query = Pixel::whereNull('deleted_at')
                        ->orderBy('created_at', 'desc');

        // Jika ada pencarian, terapkan filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('type', 'like', '%' . $search . '%')
                ->orWhere('id_event', 'like', '%' . $search . '%');
            });
        }

        // Ambil data dengan paginasi
        $data = $query->paginate(5);

        return view('admin.pixel.index', compact('title', 'data'));
    }
}
