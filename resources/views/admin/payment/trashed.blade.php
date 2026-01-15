@extends('components.layout.app')

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
                        <a href="{{ route('payment.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Payment
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="fas fa-trash"></i> Payment Terhapus</h5>
                    </div>
                    <div class="card-body">
                        @if ($data->count() > 0)
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Rekening</th>
                                        <th>Dihapus Pada</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if ($item->image)
                                                    <img src="{{ asset($item->image) }}" alt="" height="40px"
                                                        width="110px" />
                                                @else
                                                    <span class="text-muted">No Image</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->no_rek }}</td>
                                            <td>{{ $item->deleted_at->format('d-m-Y H:i') }}</td>
                                            <td>
                                                <form action="{{ route('payment.restore', $item->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success m-1"
                                                        onclick="return confirm('Pulihkan payment ini?')">
                                                        <i class="fas fa-undo"></i> Pulihkan
                                                    </button>
                                                </form>
                                                <form action="{{ route('payment.forceDelete', $item->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger m-1"
                                                        onclick="return confirm('Hapus permanen payment ini? Tindakan ini tidak dapat dibatalkan!')">
                                                        <i class="fas fa-trash-alt"></i> Hapus Permanen
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end">
                                {!! $data->appends(request()->query())->links('pagination::bootstrap-4') !!}
                            </div>
                        @else
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle"></i> Tidak ada payment terhapus.
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
