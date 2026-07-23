@extends('components.layout.app')

@section('content')
    <div class="container-fluid px-3 px-lg-4 mt-3">

        <x-form-alerts />

        {{-- Page header --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
            <div>
                <h2 class="page-title">Volunteer</h2>
                <p class="page-subtitle mb-0">Daftar volunteer yang terdaftar melalui checkout tiket.</p>
            </div>
            <div class="d-flex gap-2">
                {{-- Export Modal trigger --}}
                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"
                    data-bs-target="#exportModal">
                    <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
                </button>
                <a href="{{ route('volunteer.trashed') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-trash3 me-1"></i>Terhapus
                </a>
            </div>
        </div>

        {{-- Data card --}}
        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <span>Daftar Volunteer</span>
                <form method="GET" action="{{ route('volunteer.search') }}" role="search"
                    style="width: min(320px, 100%);">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                        <input class="form-control" type="search" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama, email, telepon..." aria-label="Cari volunteer">
                        <button class="btn btn-primary" type="submit">Cari</button>
                    </div>
                </form>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive nowrap-table"
                    style="border:0;box-shadow:none;border-radius:0;background:transparent;">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Transaksi</th>
                                <th>Tanggal Daftar</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                                <tr>
                                    <td>{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</td>
                                    <td class="fw-semibold">{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->telepon }}</td>
                                    <td>
                                        <span class="badge {{ $item->transaksis_count > 0 ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $item->transaksis_count }}
                                        </span>
                                    </td>
                                    <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
                                    <td class="text-end">
                                        <form action="{{ route('volunteer.destroy', $item->id) }}" method="POST"
                                            class="m-0 d-inline"
                                            onsubmit="return confirm('Hapus volunteer {{ $item->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Belum ada volunteer.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-3">
            {!! $data->appends(request()->query())->links('pagination::bootstrap-4') !!}
        </div>

    </div>

    {{-- Export Modal --}}
    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">
                        <i class="bi bi-file-earmark-excel me-2 text-success"></i>Export Volunteer ke Excel
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form method="POST" action="{{ route('volunteer.export') }}">
                    @csrf
                    <div class="modal-body">
                        <p class="text-muted mb-3">
                            Data yang diexport akan mengikuti filter pencarian aktif saat ini.
                        </p>
                        <div class="mb-0">
                            <label for="exportSearch" class="form-label">Filter Pencarian (opsional)</label>
                            <input type="text" class="form-control" id="exportSearch" name="search"
                                value="{{ request('search') }}"
                                placeholder="Kosongkan untuk export semua data">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-download me-1"></i>Download Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .nowrap-table th,
        .nowrap-table td {
            white-space: nowrap;
        }
    </style>
@endsection