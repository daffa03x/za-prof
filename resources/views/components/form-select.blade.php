@props([
    'name',
    'label',
    'options' => [],
    'optionValue' => null,
    'optionLabel' => null,
    'selected' => null,
    'value' => null,
    'placeholder' => 'Pilih...',
    'required' => false,
])

@php
    $selectedValue = old($name, $selected ?? $value);
@endphp

<div class="mb-3">
    <label for="{{ $name }}" class="form-label fw-semibold">
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    
    <select 
        class="form-control @error($name) is-invalid @enderror" 
        id="{{ $name }}" 
        name="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes }}
    >
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $key => $option)
            @php
                // Support both associative arrays and Eloquent collections
                if ($optionValue && $optionLabel && is_object($option)) {
                    $val = $option->{$optionValue};
                    $lbl = $option->{$optionLabel};
                } elseif (is_array($option) && $optionValue && $optionLabel) {
                    $val = $option[$optionValue];
                    $lbl = $option[$optionLabel];
                } else {
                    $val = $key;
                    $lbl = $option;
                }
            @endphp
            <option value="{{ $val }}" {{ $selectedValue == $val ? 'selected' : '' }}>
                {{ $lbl }}
            </option>
        @endforeach
    </select>
    
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
