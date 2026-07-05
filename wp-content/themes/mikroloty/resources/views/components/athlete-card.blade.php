@props(['id' => null])

@php
    $pid = $id ?: get_the_ID();
    $role = get_field('role', $pid);
@endphp

<article class="card overflow-hidden">
    <div class="relative flex items-end justify-center aspect-[4/5] bg-placeholder overflow-hidden">
        @if (has_post_thumbnail($pid))
            {!! get_the_post_thumbnail($pid, 'medium_large', ['class' => 'absolute inset-0 w-full h-full object-cover']) !!}
        @else
            <svg width="78" height="78" viewBox="0 0 24 24" fill="#9aa7c0" class="opacity-35 translate-y-1" aria-hidden="true">
                <circle cx="12" cy="9" r="4" />
                <path d="M4 21 C4 16.5 7.5 14 12 14 C16.5 14 20 16.5 20 21 Z" />
            </svg>
        @endif
    </div>
    <div class="border-t-2 border-navy" style="padding:16px 18px 18px;">
        <h3 class="font-heading font-bold text-ink m-0 mb-[3px]" style="font-size:16.5px;">{{ get_the_title($pid) }}</h3>
        @if ($role)
            <div class="text-ink-4" style="font-size:12.5px;">{{ $role }}</div>
        @endif
    </div>
</article>
