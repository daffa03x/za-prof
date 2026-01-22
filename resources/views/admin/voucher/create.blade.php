@extends('components.layout.app')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ $title }}</h5>
                    </div>
                    <div class="card-body">
                        <x-form-alerts />

                        <form action="{{ route('voucher.store') }}" method="POST">
                            @csrf

                            <x-form-select name="id_event" label="Event" :options="$events" optionValue="id"
                                optionLabel="name" required="true" />

                            <x-form-input name="name_voucher" label="Nama Voucher" placeholder="Contoh: Diskon Tahun Baru"
                                required="true" />

                            <x-form-input name="kode" label="Kode Voucher" placeholder="Contoh: TAHUNBARU2024"
                                required="true" hint="Kode akan otomatis diubah ke huruf kapital" />

                            <x-form-input name="nilai_diskon" label="Nilai Diskon (Rp)" type="number"
                                placeholder="Contoh: 50000" required="true" />

                            <x-form-input name="kuota" label="Kuota Penggunaan" type="number" placeholder="Contoh: 100"
                                required="true" />

                            <x-form-input name="tanggal_kadaluarsa" label="Tanggal Kadaluarsa" type="date"
                                required="true" />

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('voucher.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
