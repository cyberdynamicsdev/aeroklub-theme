{{--
  Kadra: season navigation (year links) + athlete grid.
  Expects: $athletes (WP_Query), $activeTermId (int|null).
--}}
@php
    $seasons = get_terms([
        'taxonomy' => 'sezon',
        'hide_empty' => true,
        'orderby' => 'name',
        'order' => 'DESC',
    ]);
    $seasons = is_wp_error($seasons) ? [] : $seasons;
    $archiveUrl = get_post_type_archive_link('athlete');
@endphp

@if (count($seasons) > 1)
    <div class="border-b border-line bg-white">
        <div class="container-site flex flex-wrap items-center gap-2.5 py-[18px]">
            <span class="uppercase font-bold text-ink-4 mr-2" style="font-size:11px;letter-spacing:0.1em;">{{ __('Sezon:', 'mikroloty') }}</span>
            @foreach ($seasons as $season)
                @php $active = $season->term_id === $activeTermId; @endphp
                <a href="{{ get_term_link($season) }}"
                   class="border font-semibold transition-colors {{ $active ? 'bg-navy text-white border-navy' : 'border-line-2 text-ink-2 hover:border-navy' }}"
                   style="font-size:13px;letter-spacing:0.03em;padding:8px 16px;">{{ $season->name }}</a>
            @endforeach
        </div>
    </div>
@endif

<section class="bg-white" style="padding-block:clamp(48px,7vw,80px);">
    <div class="container-site">
        @if ($athletes->have_posts())
            <div class="grid gap-[22px]" style="grid-template-columns:repeat(auto-fill,minmax(210px,1fr));">
                @while ($athletes->have_posts())
                    @php $athletes->the_post(); @endphp
                    <x-athlete-card />
                @endwhile
                @php wp_reset_postdata(); @endphp
            </div>
        @else
            <p class="text-ink-3">{{ __('Brak zawodników do wyświetlenia.', 'mikroloty') }}</p>
        @endif
    </div>
</section>
