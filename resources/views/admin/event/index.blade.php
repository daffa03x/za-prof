@extends('components.layout.app')

@section('content')

    <style>
        th,
        td {
            white-space: nowrap;
            /* Mencegah teks melipat ke baris baru */
        }
    </style>
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
                        <a href="{{ route('event.create') }}" class="btn btn-success me-1">Tambah</a>
                        <!-- <button type="button" class="btn btn-info me-1 text-white" data-toggle="modal"
                                data-target="#exportEvent">Export</button>
                            <button type="button" class="btn btn-warning text-white me-1" data-toggle="modal"
                                data-target="#filterEvent">Filter</button> -->
                        <a href="{{ route('event.trashed') }}" class="btn btn-danger">
                            Terhapus
                        </a>
                    </div>

                    <form class="d-flex ml-2" method="GET" action="{{ route('event.search') }}">
                        <input class="form-control me-2" type="search" id="search" name="search" placeholder="Search"
                            aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </div>

                <!-- Modal export campaign -->
                @include('components.modal.exportEvent')

                <!-- Modal filter campaign -->
                @include('components.modal.filterEvent')
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Event</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Waktu Mulai</th>
                                <th>Waktu Berakhir</th>
                                <th>Kota</th>
                                <th>Jumlah Tiket</th>
                                {{-- <th>Terjual</th> --}}
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>@rupiah($item->harga)</td>
                                    </td>
                                    <td>
                                        <form action="{{ route('event.update.status', $item->slug) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            @if ($item->status == 1)
                                                <button type="submit" class="btn btn-sm badge bg-success m-1">Y</button>
                                            @else
                                                <button type="submit" class="btn btn-sm badge bg-danger m-1">N</button>
                                            @endif
                                        </form>
                                    </td>
                                    <td>{{ $item->waktu_mulai }}</td>
                                    <td>{{ $item->waktu_berakhir }}</td>
                                    <td>{{ $item->kota }}</td>
                                    <td>{{ $item->jumlah_tiket }}</td>
                                    {{-- <td>20</td> --}}
                                    <td>
                                        <a href="{{ route('event.show', $item->slug) }}"
                                            class="btn btn-sm btn-info m-1 text-white">Lihat</a>
                                        <a href="{{ route('event.edit', $item->slug) }}"
                                            class="btn btn-sm btn-warning m-1 text-white">Edit</a>
                                        <form action="{{ route('event.destroy', $item->slug) }}" method="POST"
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
