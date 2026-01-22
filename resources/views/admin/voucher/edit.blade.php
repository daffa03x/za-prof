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

                        <form action="{{ route('voucher.update', $voucher->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <x-form-select name="id_event" label="Event" :options="$events" optionValue="id"
                                optionLabel="name" :selected="$voucher->id_event" required="true" />

                            <x-form-input name="name_voucher" label="Nama Voucher" placeholder="Contoh: Diskon Tahun Baru"
                                :value="$voucher->name_voucher" required="true" />

                            <x-form-input name="kode" label="Kode Voucher" placeholder="Contoh: TAHUNBARU2024"
                                :value="$voucher->kode" required="true" hint="Kode akan otomatis diubah ke huruf kapital" />

                            <x-form-input name="nilai_diskon" label="Nilai Diskon (Rp)" type="number"
                                placeholder="Contoh: 50000" :value="$voucher->nilai_diskon" required="true" />

                            <x-form-input name="kuota" label="Kuota Penggunaan" type="number" placeholder="Contoh: 100"
                                :value="$voucher->kuota" required="true" />

                            <div class="mb-3">
                                <label class="form-label">Sudah Digunakan</label>
                                <input type="text" class="form-control" value="{{ $voucher->digunakan }} kali" readonly
                                    disabled>
                            </div>

                            <x-form-input name="tanggal_kadaluarsa" label="Tanggal Kadaluarsa" type="date"
                                :value="$voucher->tanggal_kadaluarsa->format('Y-m-d')" required="true" />

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="1" {{ $voucher->status ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ !$voucher->status ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('voucher.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save"></i> Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
