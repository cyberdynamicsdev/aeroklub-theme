{{--
  Unified Kadra body: season navigation + three sections for the given season:
  1. Reprezentacja (national_team) — only if any
  2. Zawodnicy (athlete)
  3. Sędziowie (judge) — only if any
  Expects: $term (WP_Term|null) — the active season.
--}}
@php
    $activeTerm = $term ?? null;

    $sections = [
        ['cpt' => 'national_team', 'eyebrow' => __('Mistrzostwa świata FAI', 'mikroloty'), 'title' => __('Reprezentacja', 'mikroloty')],
        ['cpt' => 'athlete', 'eyebrow' => __('Skład kadry', 'mikroloty'), 'title' => __('Zawodnicy', 'mikroloty')],
        ['cpt' => 'judge', 'eyebrow' => __('Panel sędziowski', 'mikroloty'), 'title' => __('Sędziowie', 'mikroloty')],
    ];

    $rendered = 0;
@endphp

@include('partials.season-nav', ['activeTermId' => $activeTerm?->term_id])

@foreach ($sections as $section)
    @php
        $args = ['post_type' => $section['cpt'], 'posts_per_page' => -1, 'orderby' => 'menu_order title', 'order' => 'ASC'];
        if ($activeTerm) {
            $args['tax_query'] = [['taxonomy' => 'sezon', 'field' => 'term_id', 'terms' => $activeTerm->term_id]];
        }
        $q = new WP_Query($args);
    @endphp

    @if ($q->have_posts())
        @php $bg = $rendered % 2 === 0 ? 'bg-white' : 'bg-surface'; $rendered++; @endphp
        <section class="{{ $bg }}" style="padding-block:clamp(48px,7vw,80px);">
            <div class="container-site">
                <div class="section-head">
                    <div>
                        <div class="eyebrow mb-2.5">{{ $section['eyebrow'] }}</div>
                        <h2 class="section-title">{{ $section['title'] }}</h2>
                    </div>
                    <span class="text-ink-3" style="font-size:13.5px;">{{ $q->found_posts }} {{ _n('osoba', 'osób', $q->found_posts, 'mikroloty') }}</span>
                </div>
                <div class="grid gap-[22px]" style="grid-template-columns:repeat(auto-fill,minmax(210px,1fr));">
                    @while ($q->have_posts())
                        @php $q->the_post(); @endphp
                        <x-athlete-card />
                    @endwhile
                    @php wp_reset_postdata(); @endphp
                </div>
            </div>
        </section>
    @endif
@endforeach

@if ($rendered === 0)
    <section class="bg-white" style="padding-block:clamp(48px,7vw,80px);">
        <div class="container-site">
            <p class="text-ink-3">{{ __('Brak osób do wyświetlenia w tym sezonie.', 'mikroloty') }}</p>
        </div>
    </section>
@endif
