@extends('component.layout.app')

@section('content')
<div class="container mt-4">

    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Menampilkan Notifikasi Sukses -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Menampilkan Notifikasi Error -->
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Menampilkan Pesan Validasi Error -->
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="py-4 d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('event.create') }}" class="btn btn-success">Add</a>
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#exportEvent">Export</button>
                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#filterEvent">Filter</a>
                </div>
                
                <form class="d-flex ml-2" method="GET" action="{{ route('event.search') }}">
                    <input class="form-control mr-2" type="search" id="search" name="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>

            <!-- Modal export campaign -->
            @include('component.modal.exportEvent')

            <!-- Modal filter campaign -->
             @include('component.modal.filterEvent')
            
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Harga</th>
                        <th>Website</th>
                        <th>Status</th>
                        <th>Waktu Mulai</th>
                        <th>Waktu Berakhir</th>
                        <th>Kota</th>
                        <th>Jumlah Tiket</th>
                        <th>Terjual</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>@rupiah($item->harga)</td>
                        <td><a href="{{ $item->website }}" class="btn btn-sm btn-secondary m-1">Kunjungi</a></td>
                        <td>
                            @if($item->status == 0)
                                <a href="{{ $item->status }}" class="btn btn-sm btn-success m-1">Y</a>
                            @else
                                <a href="{{ $item->status }}" class="btn btn-sm btn-danger m-1">N</a>
                            @endif
                        </td>
                        <td>{{ $item->waktu_mulai }}</td>
                        <td>{{ $item->waktu_berakhir }}</td>
                        <td>{{ $item->kota }}</td>
                        <td>{{ $item->jumlah_tiket }}</td>
                        <td>20</td>
                        <td>
                            <a href="{{ route('event.show', $item->id) }}" class="btn btn-sm btn-info m-1">Lihat</a>
                            <a href="{{ route('event.edit', $item->id) }}" class="btn btn-sm btn-warning m-1">Edit</a>
                            <form action="{{ route('event.destroy', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete();">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger m-1">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-end">
                {!! $data->appends(request()->query())->links('pagination::bootstrap-4') !!}
            </div>

        </div>
    </div>
</div>
@endsection