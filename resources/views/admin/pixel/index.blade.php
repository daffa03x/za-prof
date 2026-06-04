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

                <!-- Menampilkan Pesan Validasi Error -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="py-4 d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('pixel.create') }}" class="btn btn-success me-1">Tambah</a>
                        <a href="{{ route('pixel.trashed') }}" class="btn btn-danger">
                            Terhapus
                        </a>
                    </div>

                    <form class="d-flex ml-2" method="GET" action="{{ route('pixel.search') }}">
                        <input class="form-control mr-2" type="search" id="search" name="search" placeholder="Search"
                            aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Id Pixel</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->type }}</td>
                                    <td>{{ $item->id_event }}</td>
                                    <td>
                                        @if ($item->status == 0)
                                            <a href="" class="btn btn-sm btn-success m-1">Aktif</a>
                                        @else
                                            <a href="" class="btn btn-sm btn-danger m-1">Tidak Aktif</a>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('pixel.edit', $item->id) }}"
                                            class="btn btn-sm btn-warning m-1 text-white">Edit</a>
                                        <form action="{{ route('pixel.destroy', $item->id) }}" method="POST"
                                            style="display:inline;" onsubmit="return confirmDelete();">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger m-1">Hapus</button>
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

            </div>
        </div>
    </div>
@endsection
