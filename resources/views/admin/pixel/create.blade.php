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
                        <i class="fas fa-code me-2"></i>Tambah Pixel
                    </h4>
                    <a href="{{ route('pixel.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-edit me-2"></i>Form Pixel
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('pixel.store') }}">
                            @csrf

                            {{-- Pixel Name --}}
                            <x-form-input name="name" label="Nama Pixel" placeholder="Contoh: FB Pixel Utama"
                                :required="true" />

                            {{-- Pixel Type --}}
                            <x-form-select name="type" label="Tipe Pixel" :options="['Meta' => 'Meta (Facebook/Instagram)', 'Tiktok' => 'TikTok']" :required="true" />

                            {{-- Pixel Code --}}
                            <x-form-input name="pixel_code" label="Kode Pixel" type="text"
                                placeholder="Masukkan ID Pixel dari platform (contoh: 123456789)" :required="true" />

                            {{-- Submit --}}
                            <div class="d-flex gap-2 pt-3 border-top">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Simpan Pixel
                                </button>
                                <a href="{{ route('pixel.index') }}" class="btn btn-outline-secondary">
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
