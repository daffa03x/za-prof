<?php

namespace App\Http\Controllers;

use App\Exports\ExportVolunteer;
use App\Models\Event;
use App\Models\Volunteer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class VolunteerController
 *
 * Handles listing, searching, exporting, and soft-delete management for volunteers.
 */
class VolunteerController extends Controller
{
    /**
     * Display a listing of volunteers.
     */
    public function index(): View
    {
        $title = 'Volunteer';
        $events = Event::orderBy('name')->get(['id', 'name']);

        $data = Volunteer::withCount('transaksis')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.volunteer.index', compact('title', 'data', 'events'));
    }

    /**
     * Display a listing of soft-deleted volunteers.
     */
    public function trashed(): View
    {
        $title = 'Volunteer Terhapus';

        $data = Volunteer::onlyTrashed()
            ->orderByDesc('deleted_at')
            ->paginate(10);

        return view('admin.volunteer.trashed', compact('title', 'data'));
    }

    /**
     * Search / filter volunteers by name, email, phone, or event.
     */
    public function search(Request $request): View
    {
        $title = 'Cari Volunteer';
        $events = Event::orderBy('name')->get(['id', 'name']);

        $data = Volunteer::withCount('transaksis')
            ->orderByDesc('created_at')
            ->when($request->search, fn($q) =>
                $q->where(fn($query) =>
                    $query->where('name', 'like', '%' . $request->search . '%')
                          ->orWhere('email', 'like', '%' . $request->search . '%')
                          ->orWhere('telepon', 'like', '%' . $request->search . '%')
                )
            )
            ->when($request->event_id, fn($q) =>
                $q->whereHas('transaksis', fn($query) =>
                    $query->where('id_event', $request->event_id)
                )
            )
            ->paginate(10)
            ->appends($request->only(['search', 'event_id']));

        return view('admin.volunteer.index', compact('title', 'data', 'events'));
    }

    /**
     * Export volunteers to Excel, optionally filtered by event.
     */
    public function export(Request $request)
    {
        $eventName = null;
        if ($request->event_id) {
            $event = Event::find($request->event_id);
            $eventName = $event?->name;
        }

        $data = Volunteer::withCount('transaksis')
            ->orderByDesc('created_at')
            ->when($request->event_id, fn($q) =>
                $q->whereHas('transaksis', fn($query) =>
                    $query->where('id_event', $request->event_id)
                )
            )
            ->get();

        $formatted = $data->map(fn($item) => [
            'id'              => $item->id,
            'name'            => $item->name,
            'email'           => $item->email,
            'telepon'         => $item->telepon,
            'jenis_kelamin'   => $item->jenis_kelamin ?? '-',
            'jumlah_transaksi'=> $item->transaksis_count,
            'created_at'      => $item->created_at?->format('d-m-Y H:i'),
        ]);

        $filename = $eventName
            ? 'Volunteer-' . str($eventName)->slug() . '.xlsx'
            : 'Volunteer.xlsx';

        Log::info('Volunteer export requested', [
            'total_records' => $formatted->count(),
            'event_id'      => $request->event_id,
            'user_id'       => auth()->id(),
        ]);

        return Excel::download(new ExportVolunteer($formatted), $filename);
    }

    /**
     * Restore a soft-deleted volunteer.
     */
    public function restore(int $id): RedirectResponse
    {
        try {
            $volunteer = Volunteer::onlyTrashed()->findOrFail($id);
            $volunteer->restore();

            Log::info('Volunteer restored', [
                'volunteer_id' => $id,
                'user_id'      => auth()->id(),
            ]);

            return redirect()->route('volunteer.trashed')->with('success', 'Volunteer berhasil dipulihkan');
        } catch (\Exception $e) {
            Log::error("Error restoring volunteer ID {$id}: " . $e->getMessage());

            return redirect()->route('volunteer.trashed')->with('error', 'Gagal memulihkan volunteer');
        }
    }

    /**
     * Permanently delete a soft-deleted volunteer.
     */
    public function forceDelete(int $id): RedirectResponse
    {
        try {
            $volunteer = Volunteer::onlyTrashed()->findOrFail($id);

            // Detach all associated transactions before permanent deletion
            $volunteer->transaksis()->detach();
            $volunteer->forceDelete();

            Log::warning('Volunteer permanently deleted', [
                'volunteer_id' => $id,
                'user_id'      => auth()->id(),
            ]);

            return redirect()->route('volunteer.trashed')->with('success', 'Volunteer berhasil dihapus permanen');
        } catch (\Exception $e) {
            Log::error("Error force deleting volunteer ID {$id}: " . $e->getMessage());

            return redirect()->route('volunteer.trashed')->with('error', 'Gagal menghapus volunteer permanen');
        }
    }

    /**
     * Soft-delete a volunteer.
     */
    public function destroy(Volunteer $volunteer): RedirectResponse
    {
        try {
            $volunteerId = $volunteer->id;
            $volunteer->delete();

            Log::info('Volunteer soft deleted', [
                'volunteer_id' => $volunteerId,
                'user_id'      => auth()->id(),
            ]);

            return redirect()->route('volunteer.index')->with('success', 'Volunteer berhasil dihapus');
        } catch (\Exception $e) {
            Log::error("Error deleting volunteer ID {$volunteer->id}: " . $e->getMessage());

            return redirect()->route('volunteer.index')->with('error', 'Gagal menghapus volunteer');
        }
    }
}