@props([
    'variant' => 'nav',
])

@php
    // Wariant nav (na jasnym tle) vs footer (na granacie).
    $ring = $variant === 'footer' ? '#f3c200' : '#0e1f42';
    $markSize = $variant === 'footer' ? 17 : 20;
    $subColor = $variant === 'footer' ? '#7f90b3' : '#7c88a3';
    $markColor = $variant === 'footer' ? '#ffffff' : '#0e1f42';
@endphp

<a href="{{ home_url('/') }}" {{ $attributes->merge(['class' => 'flex items-center gap-3 shrink-0']) }}>
    <span class="inline-flex" aria-hidden="true">
        <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
            <circle cx="20" cy="20" r="18.5" fill="#f3c200" stroke="{{ $ring }}" stroke-width="2" />
            <path d="M9 25 L20 11 L31 25 L20 20 Z" fill="#0e1f42" />
            <path d="M13.5 27.5 L20 22.5 L26.5 27.5" stroke="#0e1f42" stroke-width="2" stroke-linecap="round" fill="none" />
        </svg>
    </span>
    <span class="flex flex-col leading-none">
        <span class="font-heading font-extrabold tracking-[0.1em]" style="font-size:{{ $markSize }}px;color:{{ $markColor }};">MIKROLOTY</span>
        <span class="font-semibold mt-1 tracking-[0.24em]" style="font-size:10px;color:{{ $subColor }};">AEROKLUB POLSKI</span>
    </span>
</a>
