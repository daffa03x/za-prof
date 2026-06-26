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
                        <i class="fas fa-edit me-2"></i>Edit Payment
                    </h4>
                    <a href="{{ route('payment.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <i class="fas fa-edit me-2"></i>Form Edit Payment
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('payment.update', $payment->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            {{-- Image Upload --}}
                            <x-form-file name="image" label="Logo Payment"
                                hint="Upload logo bank/e-wallet (PNG/JPG, max 1MB)" :current-image="$payment->image" />

                            {{-- Payment Type --}}
                            <div class="mb-3">
                                <label for="type" class="form-label fw-semibold">Tipe Payment <span class="text-danger">*</span></label>
                                <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="manual" {{ (old('type', $payment->type ?? 'manual') === 'manual') ? 'selected' : '' }}>Manual (Transfer Bank / QRIS)</option>
                                    <option value="midtrans" {{ (old('type', $payment->type ?? '') === 'midtrans') ? 'selected' : '' }}>Midtrans (Otomatis)</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Pilih <strong>Midtrans</strong> untuk pembayaran otomatis via Snap.</small>
                            </div>

                            {{-- Payment Name --}}
                            <x-form-input name="name" label="Nama Payment" placeholder="Contoh: BCA, Mandiri, GoPay"
                                :value="$payment->name" :required="true" />

                            {{-- Account Number --}}
                            <x-form-input name="no_rek" label="Nomor Rekening"
                                placeholder="Nomor rekening / kosongkan jika Midtrans" :value="$payment->no_rek"
                                :required="false" />

                            {{-- Submit --}}
                            <div class="d-flex gap-2 pt-3 border-top">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save me-1"></i>Update Payment
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