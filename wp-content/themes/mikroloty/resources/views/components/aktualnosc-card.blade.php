@props(['id' => null])

@php
    $pid = $id ?: get_the_ID();
    $permalink = get_permalink($pid);
    $kategorie = get_the_category($pid);
    $tag = $kategorie ? $kategorie[0]->name : '';
    $data = get_the_date('d.m.Y', $pid);
@endphp

<article class="card flex flex-col overflow-hidden">
    <a href="{{ $permalink }}" class="relative block aspect-video bg-placeholder overflow-hidden">
        @if (has_post_thumbnail($pid))
            {!! get_the_post_thumbnail($pid, 'large', ['class' => 'w-full h-full object-cover block']) !!}
        @endif
        <span class="absolute left-0 bottom-0 bg-navy text-white font-semibold" style="font-size:12px;padding:7px 13px;">{{ $data }}</span>
    </a>
    <div class="flex-1 flex flex-col" style="padding:24px 26px 26px;">
        @if ($tag)
            <span class="uppercase font-bold text-gold-dark mb-3" style="font-size:11px;letter-spacing:0.1em;">{{ $tag }}</span>
        @endif
        <h3 class="font-heading font-bold text-ink m-0 mb-3" style="font-size:19px;line-height:1.3;">
            <a href="{{ $permalink }}" class="hover:text-navy">{{ get_the_title($pid) }}</a>
        </h3>
        <p class="text-ink-3 m-0 mb-5" style="font-size:15px;line-height:1.6;">{{ get_the_excerpt($pid) }}</p>
        <a href="{{ $permalink }}" class="mt-auto inline-flex items-center gap-2 uppercase font-bold text-navy" style="font-size:13px;letter-spacing:0.04em;">
            {{ __('Czytaj więcej', 'mikroloty') }} <span class="text-gold">→</span>
        </a>
    </div>
</article>
