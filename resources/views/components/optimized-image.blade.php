@props([
    'src',
    'alt' => '',
    'class' => '',
    'width' => null,
    'height' => null,
    'lazy' => true,
    'useThumbnail' => false,
])

@php
    $imagePath = $src;

    // Use thumbnail if requested and exists
    if ($useThumbnail && $src) {
        $info = pathinfo($src);
        $thumbnailPath =
            ($info['dirname'] ?? '') . '/' . ($info['filename'] ?? '') . '_thumb.' . ($info['extension'] ?? 'jpg');
        if (file_exists(public_path($thumbnailPath))) {
            $imagePath = $thumbnailPath;
        }
    }
@endphp

<img src="{{ asset($imagePath) }}" alt="{{ $alt }}"
    @if ($class) class="{{ $class }}" @endif
    @if ($width) width="{{ $width }}" @endif
    @if ($height) height="{{ $height }}" @endif
    @if ($lazy) loading="lazy" @endif {{ $attributes }}>
