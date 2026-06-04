@extends('components.layout.app')

<style>
    th,
    td {
        white-space: nowrap
    }
</style>

@section('content')
    <div class="container mt-4">

        <div class="row justify-content-center">
            <div class="col-md-12">
                <!-- Menampilkan Notifikasi Sukses -->
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Menampilkan Notifikasi Error -->
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="py-4 d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Transaksi
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="fas fa-trash"></i> Transaksi Terhapus</h5>
                    </div>
                    <div class="card-body">
                        @if ($data->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Invoice</th>
                                            <th>Event</th>
                                            <th>Nama Pembeli</th>
                                            <th>Email</th>
                                            <th>Total Pembayaran</th>
                                            <th>Status</th>
                                            <th>Dihapus Pada</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->invoice }}</td>
                                                <td>{{ $item->event?->name ?? 'N/A' }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->email }}</td>
                                                <td>@rupiah($item->total_pembayaran)</td>
                                                <td>
                                                    @if ($item->status_pembayaran === 'Success')
                                                        <span class="badge bg-success">Success</span>
                                                    @elseif($item->status_pembayaran === 'Failed')
                                                        <span class="badge bg-danger">Failed</span>
                                                    @else
                                                        <span class="badge bg-warning">Pending</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item->deleted_at->format('d-m-Y H:i') }}</td>
                                                <td>
                                                    <form action="{{ route('transaksi.restore', $item->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success m-1"
                                                            onclick="return confirm('Pulihkan transaksi ini? Tiket akan dikurangi dari event.')">
                                                            <i class="fas fa-undo"></i> Pulihkan
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('transaksi.forceDelete', $item->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger m-1"
                                                            onclick="return confirm('Hapus permanen transaksi ini? Tindakan ini tidak dapat dibatalkan!')">
                                                            <i class="fas fa-trash-alt"></i> Hapus Permanen
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end">
                                {!! $data->appends(request()->query())->links('pagination::bootstrap-4') !!}
                            </div>
                        @else
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle"></i> Tidak ada transaksi terhapus.
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
