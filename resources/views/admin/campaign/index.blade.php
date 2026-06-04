@extends('components.layout.app')

@section('content')
    <div class="container mt-4">

        <div class="row justify-content-center">
            <div class="col-md-10">
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
                        <a href="{{ route('campaign.create') }}" class="btn btn-success">Add</a>
                        <button type="button" class="btn btn-info" data-toggle="modal"
                            data-target="#exportCampaign">Export</button>
                        <button type="button" class="btn btn-warning" data-toggle="modal"
                            data-target="#filterCampaign">Filter</a>
                    </div>

                    <form class="d-flex ml-2" method="GET" action="{{ route('campaign.search') }}">
                        <input class="form-control mr-2" type="search" id="search" name="search" placeholder="Search"
                            aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </div>

                <!-- Modal export campaign -->
                @include('components.modal.exportCampaign')

                <!-- Modal filter campaign -->
                @include('components.modal.filterCampaign')

                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Mitra</th>
                            <th>Website</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->mitra }}</td>
                                <td><a href="{{ $item->website }}" class="btn btn-sm btn-secondary m-1">Kunjungi</a></td>
                                <td>
                                    <a href="{{ route('campaign.show', $item->id) }}"
                                        class="btn btn-sm btn-info m-1">Lihat</a>
                                    <a href="{{ route('campaign.edit', $item->id) }}"
                                        class="btn btn-sm btn-warning m-1">Edit</a>
                                    <form action="{{ route('campaign.destroy', $item->id) }}" method="POST"
                                        style="display:inline;" onsubmit="return confirmDelete();">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger m-1">Delete</button>
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
