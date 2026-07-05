@props(['id' => null])

@php
    $pid = $id ?: get_the_ID();
    $class = get_field('aircraft_class', $pid);
    $status = mikroloty_competition_status(get_field('status', $pid));
    $date = mikroloty_date_range(get_field('start_date', $pid), get_field('end_date', $pid));
    $location = get_field('location', $pid);
    $permalink = get_permalink($pid);
@endphp

<article class="card flex flex-col overflow-hidden">
    <a href="{{ $permalink }}" class="relative block aspect-video bg-placeholder overflow-hidden">
        @if (has_post_thumbnail($pid))
            {!! get_the_post_thumbnail($pid, 'large', ['class' => 'w-full h-full object-cover block']) !!}
        @endif
        @if ($class)
            <span class="absolute top-0 left-0 bg-navy text-white uppercase font-bold" style="font-size:11px;letter-spacing:0.06em;padding:6px 12px;">{{ $class }}</span>
        @endif
        <span class="absolute top-0 right-0 uppercase font-bold {{ $status['classes'] }}" style="font-size:11px;letter-spacing:0.05em;padding:5px 11px;">{{ $status['label'] }}</span>
    </a>
    <div class="flex-1 flex flex-col" style="padding:22px 24px 24px;">
        @if ($date)
            <div class="flex items-center gap-2.5 text-gold-dark uppercase font-bold mb-3" style="font-size:12.5px;letter-spacing:0.04em;">
                <span class="bg-gold" style="width:16px;height:2px;"></span>{{ $date }}
            </div>
        @endif
        <h3 class="font-heading font-bold text-ink m-0 mb-3.5" style="font-size:20px;line-height:1.25;">
            <a href="{{ $permalink }}" class="hover:text-navy">{{ get_the_title($pid) }}</a>
        </h3>
        @if ($location)
            <div class="flex items-center gap-2 text-ink-3 mt-auto" style="font-size:14px;">
                <span class="text-navy">◆</span>{{ $location }}
            </div>
        @endif
    </div>
</article>
