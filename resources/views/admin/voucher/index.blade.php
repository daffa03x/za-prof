@extends('components.layout.app')

@section('content')
    <div class="container mt-4">

        <div class="row justify-content-center">
            <div class="col-md-12">
                <x-form-alerts />

                <div class="py-4 d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('voucher.create') }}" class="btn btn-success me-1">
                            <i class="fas fa-plus"></i> Tambah Voucher
                        </a>
                        <a href="{{ route('voucher.trashed') }}" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Terhapus
                        </a>
                    </div>

                    <form class="d-flex ml-2" method="GET" action="{{ route('voucher.search') }}">
                        <input class="form-control mr-2" type="search" id="search" name="search"
                            placeholder="Cari voucher..." aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Cari</button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Voucher</th>
                                <th>Event</th>
                                <th>Diskon</th>
                                <th>Kuota</th>
                                <th>Digunakan</th>
                                <th>Kadaluarsa</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                                <tr>
                                    <td>{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</td>
                                    <td>
                                        <code class="bg-light px-2 py-1 rounded">{{ $item->kode }}</code>
                                        @if ($item->is_external)
                                            <span class="badge bg-primary ms-1" title="Voucher dari API Eksternal">
                                                <i class="fas fa-link"></i> External
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $item->name_voucher }}</td>
                                    <td>{{ $item->event->name ?? '-' }}</td>
                                    <td>Rp {{ number_format($item->nilai_diskon, 0, ',', '.') }}</td>
                                    <td>{{ $item->kuota }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $item->digunakan >= $item->kuota ? 'bg-danger' : 'bg-success' }}">
                                            {{ $item->digunakan }} / {{ $item->kuota }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($item->tanggal_kadaluarsa < now())
                                            <span
                                                class="text-danger">{{ $item->tanggal_kadaluarsa->format('d-m-Y') }}</span>
                                        @else
                                            {{ $item->tanggal_kadaluarsa->format('d-m-Y') }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->status)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('voucher.edit', $item->id) }}"
                                            class="btn btn-sm btn-warning m-1 text-white">
                                            Edit
                                        </a>
                                        <form action="{{ route('voucher.destroy', $item->id) }}" method="POST"
                                            style="display:inline;" onsubmit="return confirm('Hapus voucher ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger m-1">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-4">
                                        Belum ada voucher
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
