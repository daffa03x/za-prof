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

                <div class="py-4 d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('event.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Buat Event</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('event.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="image">Unggah gambar/poster/banner</label>
                                <p class="text-danger small">Direkomendasikan 724 x 340px dan tidak lebih dari 2Mb</p>
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                    id="image" name="image">
                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="name">Nama Event</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="website">Website</label>
                                    <input type="text" class="form-control @error('website') is-invalid @enderror"
                                        name="website">
                                    @error('website')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="mitra">Mitra</label>
                                    <input type="text" class="form-control @error('mitra') is-invalid @enderror"
                                        name="mitra">
                                    @error('mitra')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="waktu_mulai">Waktu Mulai</label>
                                    <input type="datetime-local"
                                        class="form-control @error('waktu_mulai') is-invalid @enderror" name="waktu_mulai">
                                    @error('waktu_mulai')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="waktu_berakhir">Waktu Berakhir</label>
                                    <input type="datetime-local"
                                        class="form-control @error('waktu_berakhir') is-invalid @enderror"
                                        name="waktu_berakhir">
                                    @error('waktu_berakhir')
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
                                        name="nama_tempat">
                                    @error('nama_tempat')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="kota">Kota</label>
                                    <input type="text" class="form-control @error('kota') is-invalid @enderror"
                                        name="kota">
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
                                    name="alamat">
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
                                        name="harga" id="harga" oninput="formatNumber(this)">
                                    @error('harga')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="jumlah_tiket">Jumlah Tiket</label>
                                    <input type="number" class="form-control @error('jumlah_tiket') is-invalid @enderror"
                                        name="jumlah_tiket">
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
                                </div>
                                <input type="hidden" name="deskripsi" id="content">
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
