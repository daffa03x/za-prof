@extends('component.layout.app')

@section('content')
<div class="container mt-4">
    
    <div class="row justify-content-center">
        <div class="col-md-8">
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
                    <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Show Transaksi</div>

                <div class="card-body">
                    <p><strong class="h5">Detail pemesanan.</strong></p>
                    <div class="border border-secondary rounded pt-1">
                        <div class="d-flex justify-content-between align-items-stretch">
                            <div class="h-100 p-3 d-flex flex-column justify-content-center w-100">
                                <p><strong>Nama Lengkap</strong></p>
                                <p>{{ $data->name }}</p>
                            </div>
                            <div class="h-100 p-3 d-flex flex-column justify-content-center w-100">
                                <p><strong>Email</strong></p>
                                <p>{{ $data->email }}</p>
                            </div>
                            <div class="h-100 p-3 d-flex flex-column justify-content-center w-100">
                                <p><strong>Telepon</strong></p>
                                <p>{{ $data->telepon }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-row bd-highlight mb-3">
                        @if($data->jenis_kelamin === 'P')
                            <div class="p-2 bd-highlight"><strong>Jenis Kelamin:</strong> Laki - Laki,</div>
                        @else
                            <div class="p-2 bd-highlight"><strong>Jenis Kelamin:</strong> Perempuan,</div>
                        @endif
                        <div class="p-2 bd-highlight"><strong>Tanggal Lahir:</strong> {{ $data->tanggal_lahir }}</div>
                    </div>

                    <p><strong class="h5 mt-4 pt-2">Detail pembayaran.</strong></p>
                    <div class="border border-secondary rounded pt-1 mb-4">
                        <div class="d-flex justify-content-between align-items-stretch">
                            <div class="h-100 p-3 d-flex flex-column justify-content-center w-100">
                                <p><strong>Waktu Registrasi</strong></p>
                                <p>{{ $data->tanggal_register }}</p>
                            </div>
                            <div class="h-100 p-3 d-flex flex-column justify-content-center w-100">
                                <p><strong>Waktu Pembayaran</strong></p>
                                <p>
                                    @if($data->tanggal_pembayaran === null)
                                        Belum Bayar
                                    @else
                                        {{ $data->tanggal_pembayaran }}
                                    @endif
                                </p>
                            </div>
                            <div class="h-100 p-3 d-flex flex-column justify-content-center w-100">
                                <p><strong>Metode Pembayaran</strong></p>
                                <p>{{ $data->payment->name }}</p>
                            </div>
                            <div class="h-100 p-3 d-flex flex-column justify-content-center w-100">
                                <p><strong>Status Pembayaran</strong></p>
                                <p>
                                    @if($data->status_pembayaran === 'Success')
                                        <span class="badge badge-success">Success</span>
                                    @elseif($data->status_pembayaran === 'Failed')
                                        <span class="badge badge-danger">Failed</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="border border-secondary rounded pt-1 mb-4">
                        <div class="d-flex justify-content-between align-items-stretch">
                            <div class="h-100 p-3 d-flex flex-column justify-content-center w-100">
                                <p><strong>No</strong></p>
                                <p>{{ $data->name }}</p>
                            </div>
                            <div class="h-100 p-3 d-flex flex-column justify-content-center w-100">
                                <p><strong>Event</strong></p>
                                <p>{{ $data->event->name }}</p>
                            </div>
                            <div class="h-100 p-3 d-flex flex-column justify-content-center w-100">
                                <p><strong>Judul</strong></p>
                                <p>Isi</p>
                            </div>
                            <div class="h-100 p-3 d-flex flex-column justify-content-center w-100">
                                <p><strong>Jumlah</strong></p>
                                <p>{{ $data->jumlah_tiket }}</p>
                            </div>
                            <div class="h-100 p-3 d-flex flex-column justify-content-center w-100">
                                <p><strong>Total</strong></p>
                                <p>@rupiah( $data->total_pembayaran )</p>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-row-reverse bd-highlight justify-items-center">
                        <div class="p-2 bd-highlight h4">@rupiah( $data->total_pembayaran )</div>
                        <div class="p-2 bd-highlight"><strong>Total Pembayaran</strong></div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection
