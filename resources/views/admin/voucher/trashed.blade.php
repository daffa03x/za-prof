@extends('components.layout.app')

@section('content')
    <div class="container mt-4">

        <div class="row justify-content-center">
            <div class="col-md-12">
                <x-form-alerts />

                <div class="py-4 d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('voucher.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                    <h5 class="mb-0">{{ $title }}</h5>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Voucher</th>
                                <th>Event</th>
                                <th>Dihapus</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                                <tr>
                                    <td>{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</td>
                                    <td><code>{{ $item->kode }}</code></td>
                                    <td>{{ $item->name_voucher }}</td>
                                    <td>{{ $item->event->name ?? '-' }}</td>
                                    <td>{{ $item->deleted_at->format('d-m-Y H:i') }}</td>
                                    <td>
                                        <form action="{{ route('voucher.restore', $item->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success m-1">
                                                <i class="fas fa-undo"></i> Pulihkan
                                            </button>
                                        </form>
                                        <form action="{{ route('voucher.forceDelete', $item->id) }}" method="POST"
                                            style="display:inline;"
                                            onsubmit="return confirm('Hapus permanen? Data tidak dapat dikembalikan!');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger m-1">
                                                <i class="fas fa-trash-alt"></i> Hapus Permanen
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        Tidak ada voucher yang dihapus
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end">
                    {!! $data->appends(request()->query())->links('pagination::bootstrap-4') !!}
                </div>

            </div>
        </div>
    </div>
@endsection
