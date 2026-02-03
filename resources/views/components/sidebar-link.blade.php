@props(['active' => false])

@php
$classes = $active
    ? 'sidebar-link active'
    : 'sidebar-link';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
