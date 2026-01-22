@props([
    'type' => 'text',
    'name',
    'label',
    'value' => null,
    'placeholder' => '',
    'required' => false,
    'hint' => null,
    'class' => '',
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
    
    <input 
        type="{{ $type }}" 
        class="form-control {{ $class }} @error($name) is-invalid @enderror" 
        id="{{ $name }}" 
        name="{{ $name }}" 
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes }}
    >
    
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
