@props([
    'name',
    'label',
    'currentImage' => null,
    'required' => false,
    'hint' => null,
    'accept' => 'image/*',
])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label fw-semibold">
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    
    @if($hint)
        <small class="text-muted d-block mb-1">{{ $hint }}</small>
    @endif
    
    @if($currentImage)
        <div class="mb-2">
            <img src="{{ asset($currentImage) }}" alt="Current Image" 
                class="img-thumbnail" style="max-height: 120px;">
            <small class="text-muted d-block">Gambar saat ini</small>
        </div>
    @endif
    
    <input 
        type="file" 
        class="form-control @error($name) is-invalid @enderror" 
        id="{{ $name }}" 
        name="{{ $name }}"
        accept="{{ $accept }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes }}
    >
    
    {{-- Image Preview --}}
    <div id="{{ $name }}-preview" class="mt-2" style="display: none;">
        <img src="" alt="Preview" class="img-thumbnail" style="max-height: 150px;">
        <small class="text-muted d-block">Preview gambar baru</small>
    </div>
    
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

@push('scripts')
<script>
document.getElementById('{{ $name }}').addEventListener('change', function(e) {
    const preview = document.getElementById('{{ $name }}-preview');
    const img = preview.querySelector('img');
    
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(this.files[0]);
    } else {
        preview.style.display = 'none';
    }
});
</script>
@endpush
