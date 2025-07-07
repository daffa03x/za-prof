<!-- resources/views/companies/create.blade.php -->

@extends('component.layout.app')

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

                <div class="card">
                    <div class="card-header">{{ $title }}</div>

                    <div class="card-body">
                        <form action="{{ route('event.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="image">Unggah gambar/poster/banner</label>
                                <p class="text-danger small">Direkomendasikan 724 x 340px dan tidak lebih dari 2Mb</p>
                                @if ($data->image)
                                    <div class="mb-2">
                                        <img src="{{ asset($data->image) }}" alt="Event Image" style="width: 100px;">
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                    id="image" name="image">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $data->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="website">Website</label>
                                    <input type="url" class="form-control @error('website') is-invalid @enderror"
                                        id="website" name="website" value="{{ old('website', $data->website) }}">
                                    @error('website')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="mitra">Mitra</label>
                                    <input type="text" class="form-control @error('mitra') is-invalid @enderror"
                                        id="mitra" name="mitra" value="{{ old('mitra', $data->mitra) }}">
                                    @error('mitra')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="waktu_mulai">Tanggal Mulai</label>
                                    <input type="datetime-local"
                                        class="form-control @error('waktu_mulai') is-invalid @enderror" name="waktu_mulai"
                                        value="{{ old('waktu_mulai', $data->waktu_mulai) }}">
                                    @error('waktu_mulai')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="waktu_berakhir">Tanggal Berakhir</label>
                                    <input type="datetime-local"
                                        class="form-control @error('waktu_berakhir') is-invalid @enderror"
                                        name="waktu_berakhir" value="{{ old('waktu_berakhir', $data->waktu_berakhir) }}">
                                    @error('tanggal_berakhir')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-8">
                                    <label for="nama_tempat">Nama Tempat</label>
                                    <input type="text" class="form-control @error('nama_tempat') is-invalid @enderror"
                                        name="nama_tempat" value="{{ old('nama_tempat', $data->nama_tempat) }}">
                                    @error('nama_tempat')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="kota">Kota</label>
                                    <input type="text" class="form-control @error('kota') is-invalid @enderror"
                                        name="kota" value="{{ old('kota', $data->kota) }}">
                                    @error('kota')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <input type="text" class="form-control @error('alamat') is-invalid @enderror"
                                    name="alamat" value="{{ old('alamat', $data->alamat) }}">
                                @error('alamat')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-row">
                                <div class="form-group flex-nowrap col-md-8">
                                    <label for="harga">Harga</label>
                                    <input type="text" class="form-control @error('harga') is-invalid @enderror"
                                        name="harga" id="harga" oninput="formatNumber(this)"
                                        value="{{ old('harga', $data->harga) }}">
                                    @error('harga')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="jumlah_tiket">Jumlah Tiket</label>
                                    <input type="number"
                                        class="form-control @error('jumlah_tiket') is-invalid @enderror"
                                        name="jumlah_tiket" value="{{ old('jumlah_tiket', $data->jumlah_tiket) }}"
                                        readonly>
                                    @error('jumlah_tiket')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="deskripsi">Deskripsi/Syarat dan Ketentuan</label>
                                <!-- Create the editor container -->
                                <div id="editor">
                                    {{ old('deskripsi', strip_tags($data->deskripsi)) }}
                                </div>
                                <input type="hidden" name="deskripsi" id="content">
                            </div>

                            <button type="submit" class="btn btn-primary mt-4">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
