@extends('components.layout.app')

@section('content')
    <div class="container mt-4">

        <div class="row justify-content-center">
            <div class="col-md-8">
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
                        <a href="{{ route('campaign.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Show Campaign</div>

                    <div class="card-body">
                        <img src="{{ $data->image ?: '/image/campaign.png' }}" class="card-img-top"
                            alt="{{ $data->id }}" style="width: 200px; height: 200px;">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <h5 class="card-title">{{ $data->name }}</h5>
                            </li>
                            <li class="list-group-item">
                                {{ $data->mitra }}
                            </li>
                        </ul>
                        <div class="card-body">
                            <p class="card-text">{{ $data->deskripsi }}</p>
                        </div>
                        <div class="card-body">
                            <a href="{{ $data->website }}" class="card-link">Lihat Campaign</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
