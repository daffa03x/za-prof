@extends('components.layout.app')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">

                {{-- Alerts --}}
                <x-form-alerts />

                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>Tambah Payment
                    </h4>
                    <a href="{{ route('payment.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-edit me-2"></i>Form Payment
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('payment.store') }}" enctype="multipart/form-data">
                            @csrf

                            {{-- Image Upload --}}
                            <x-form-file name="image" label="Logo Payment"
                                hint="Upload logo bank/e-wallet (PNG/JPG, max 1MB)" :required="true" />

                            {{-- Payment Name --}}
                            <x-form-input name="name" label="Nama Payment" placeholder="Contoh: BCA, Mandiri, GoPay"
                                :required="true" />

                            {{-- Account Number --}}
                            <x-form-input name="no_rek" label="Nomor Rekening" placeholder="Masukkan nomor rekening/akun"
                                :required="true" />

                            {{-- Submit --}}
                            <div class="d-flex gap-2 pt-3 border-top">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Simpan Payment
                                </button>
                                <a href="{{ route('payment.index') }}" class="btn btn-outline-secondary">
                                    Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
