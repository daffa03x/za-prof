<?php

namespace App\Http\Controllers;

use App\Exports\ExportVolunteer;
use App\Models\Volunteer;
use Illuminate\Http\JsonResponse;
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

        $data = Volunteer::withCount('transaksis')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.volunteer.index', compact('title', 'data'));
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
     * Search volunteers by name, email, or phone.
     */
    public function search(Request $request): View
    {
        $title = 'Cari Volunteer';

        $data = Volunteer::withCount('transaksis')
            ->orderByDesc('created_at')
            ->when($request->search, fn($q) =>
                $q->where(fn($query) =>
                    $query->where('name', 'like', '%' . $request->search . '%')
                          ->orWhere('email', 'like', '%' . $request->search . '%')
                          ->orWhere('telepon', 'like', '%' . $request->search . '%')
                )
            )
            ->paginate(10);

        return view('admin.volunteer.index', compact('title', 'data'));
    }

    /**
     * Export volunteers to Excel.
     */
    public function export(Request $request)
    {
        $data = Volunteer::withCount('transaksis')
            ->orderByDesc('created_at')
            ->when($request->search, fn($q) =>
                $q->where(fn($query) =>
                    $query->where('name', 'like', '%' . $request->search . '%')
                          ->orWhere('email', 'like', '%' . $request->search . '%')
                          ->orWhere('telepon', 'like', '%' . $request->search . '%')
                )
            )
            ->get();

        $formatted = $data->map(fn($item) => [
            'id'              => $item->id,
            'name'            => $item->name,
            'email'           => $item->email,
            'telepon'         => $item->telepon,
            'jumlah_transaksi'=> $item->transaksis_count,
            'created_at'      => $item->created_at?->format('d-m-Y H:i'),
        ]);

        Log::info('Volunteer export requested', [
            'total_records' => $formatted->count(),
            'user_id'       => auth()->id(),
        ]);

        return Excel::download(new ExportVolunteer($formatted), 'Volunteer.xlsx');
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