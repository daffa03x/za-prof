<?php

namespace App\Http\Controllers;

use App\Models\Pixel;
use App\Models\Event;
use App\Http\Requests\StorePixelRequest;
use App\Http\Requests\UpdatePixelRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

/**
 * Class PixelController
 *
 * Handles CRUD operations for tracking pixels including soft delete management.
 */
class PixelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $title = 'Pixel';

        $data = Pixel::with('event')
            ->orderByDesc('created_at')
            ->paginate(5);

        return view('admin.pixel.index', compact('title', 'data'));
    }

    /**
     * Display a listing of trashed resources.
     */
    public function trashed(): View
    {
        $title = 'Pixel Terhapus';

        $data = Pixel::onlyTrashed()
            ->with('event')
            ->orderByDesc('deleted_at')
            ->paginate(5);

        return view('admin.pixel.trashed', compact('title', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $title = 'Add Pixel';
        $events = Event::select(['id', 'name'])->orderByDesc('id')->get();

        return view('admin.pixel.create', compact('title', 'events'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePixelRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['status'] = true;

            $pixel = Pixel::create($data);

            Log::info('Pixel created', [
                'pixel_id' => $pixel->id,
                'pixel_name' => $pixel->name,
                'event_id' => $pixel->id_event,
                'user_id' => auth()->id(),
            ]);

            return redirect()->route('pixel.index')->with('success', 'Pixel berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('Error creating pixel', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);
            return redirect()->route('pixel.create')->with('error', 'Gagal menambahkan pixel');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pixel $pixel): View
    {
        $title = 'Show Pixel';
        $pixel->load('event');

        return view('admin.pixel.show', compact('title', 'pixel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pixel $pixel): View
    {
        $title = 'Edit Pixel';
        $events = Event::select(['id', 'name'])->orderByDesc('id')->get();

        return view('admin.pixel.edit', compact('title', 'pixel', 'events'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePixelRequest $request, Pixel $pixel): RedirectResponse
    {
        try {
            $data = $request->validated();
            $pixel->update($data);

            Log::info('Pixel updated', [
                'pixel_id' => $pixel->id,
                'pixel_name' => $pixel->name,
                'user_id' => auth()->id(),
            ]);

            return redirect()->route('pixel.index')->with('success', 'Pixel berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Error updating pixel', [
                'pixel_id' => $pixel->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);
            return redirect()->route('pixel.index')->with('error', 'Gagal memperbarui pixel');
        }
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(Pixel $pixel): RedirectResponse
    {
        try {
            $pixelId = $pixel->id;
            $pixelName = $pixel->name;
            
            $pixel->delete();
            
            Log::info('Pixel soft deleted', [
                'pixel_id' => $pixelId,
                'pixel_name' => $pixelName,
                'user_id' => auth()->id(),
            ]);
            
            return redirect()->route('pixel.index')->with('success', 'Pixel berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting pixel', [
                'pixel_id' => $pixel->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);
            return redirect()->route('pixel.index')->with('error', 'Gagal menghapus pixel');
        }
    }

    /**
     * Restore the specified trashed resource.
     */
    public function restore(int $id): RedirectResponse
    {
        try {
            $pixel = Pixel::onlyTrashed()->findOrFail($id);
            $pixel->restore();

            Log::info('Pixel restored', [
                'pixel_id' => $pixel->id,
                'pixel_name' => $pixel->name,
                'user_id' => auth()->id(),
            ]);

            return redirect()->route('pixel.trashed')->with('success', 'Pixel berhasil dipulihkan');
        } catch (\Exception $e) {
            Log::error('Error restoring pixel', [
                'pixel_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);
            return redirect()->route('pixel.trashed')->with('error', 'Gagal memulihkan pixel');
        }
    }

    /**
     * Permanently delete the specified trashed resource.
     */
    public function forceDelete(int $id): RedirectResponse
    {
        try {
            $pixel = Pixel::onlyTrashed()->findOrFail($id);
            $pixelName = $pixel->name;
            
            $pixel->forceDelete();

            Log::warning('Pixel permanently deleted', [
                'pixel_id' => $id,
                'pixel_name' => $pixelName,
                'user_id' => auth()->id(),
            ]);

            return redirect()->route('pixel.trashed')->with('success', 'Pixel berhasil dihapus permanen');
        } catch (\Exception $e) {
            Log::error('Error force deleting pixel', [
                'pixel_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);
            return redirect()->route('pixel.trashed')->with('error', 'Gagal menghapus pixel permanen');
        }
    }

    /**
     * Search pixels.
     */
    public function search(Request $request): View
    {
        $title = "Search Pixel";

        $data = Pixel::with('event')
            ->orderByDesc('created_at')
            ->when($request->search, fn($q) => 
                $q->where(fn($query) => 
                    $query->where('name', 'like', '%' . $request->search . '%')
                          ->orWhere('type', 'like', '%' . $request->search . '%')
                          ->orWhereHas('event', fn($eq) => 
                              $eq->where('name', 'like', '%' . $request->search . '%')
                          )
                )
            )
            ->paginate(5);

        return view('admin.pixel.index', compact('title', 'data'));
    }
}
