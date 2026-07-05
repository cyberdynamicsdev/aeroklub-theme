@extends('layouts.app')

@section('content')
    @php
        $grupy = [
            ['klasa' => 'ULM', 'eyebrow' => 'Samoloty ultralekkie', 'title' => 'Klasa ULM', 'bg' => 'bg-white'],
            ['klasa' => 'Paralotnia', 'eyebrow' => 'Paralotnie', 'title' => 'Klasa paralotniowa', 'bg' => 'bg-surface'],
        ];

        $sztabQ = new WP_Query([
            'post_type' => 'zawodnik',
            'posts_per_page' => -1,
            'meta_query' => [['key' => 'grupa', 'value' => 'Sztab', 'compare' => '=']],
        ]);
    @endphp

    <x-page-header
        :title="'Kadra narodowa ' . date('Y')"
        lead="Zawodnicy reprezentujący Polskę w sporcie mikrolotowym — w klasach ULM i paralotniowej."
        :crumbs="[['label' => 'Kadra']]" />

    @foreach ($grupy as $g)
        @php
            $q = new WP_Query([
                'post_type' => 'zawodnik',
                'posts_per_page' => -1,
                'meta_query' => [
                    'relation' => 'AND',
                    ['key' => 'klasa', 'value' => $g['klasa'], 'compare' => '='],
                    ['key' => 'grupa', 'value' => 'Sztab', 'compare' => '!='],
                ],
            ]);
        @endphp
        @if ($q->have_posts())
            <section class="{{ $g['bg'] }}" style="padding-block:clamp(52px,7vw,80px);">
                <div class="container-site">
                    <div class="section-head">
                        <div>
                            <div class="eyebrow mb-2.5">{{ $g['eyebrow'] }}</div>
                            <h2 class="section-title">{{ $g['title'] }}</h2>
                        </div>
                        <span class="text-ink-3" style="font-size:13.5px;">{{ $q->found_posts }} {{ _n('zawodnik', 'zawodników', $q->found_posts, 'mikroloty') }}</span>
                    </div>
                    <div class="grid gap-[22px]" style="grid-template-columns:repeat(auto-fill,minmax(210px,1fr));">
                        @while ($q->have_posts()) @php($q->the_post())
                            @php($grupa = get_field('grupa'))
                            <a href="{{ get_permalink() }}" class="card block overflow-hidden">
                                <span class="relative flex items-end justify-center aspect-[4/5] bg-placeholder overflow-hidden">
                                    @if (has_post_thumbnail())
                                        {!! get_the_post_thumbnail(get_the_ID(), 'medium_large', ['class' => 'absolute inset-0 w-full h-full object-cover']) !!}
                                    @else
                                        <svg width="78" height="78" viewBox="0 0 24 24" fill="#9aa7c0" class="opacity-35 translate-y-1" aria-hidden="true"><circle cx="12" cy="9" r="4" /><path d="M4 21 C4 16.5 7.5 14 12 14 C16.5 14 20 16.5 20 21 Z" /></svg>
                                    @endif
                                    @if ($grupa)
                                        <span class="absolute top-0 left-0 bg-gold text-navy uppercase font-bold" style="font-size:10.5px;letter-spacing:0.06em;padding:5px 10px;">{{ $grupa }}</span>
                                    @endif
                                </span>
                                <span class="block border-t-2 border-navy" style="padding:16px 18px 18px;">
                                    <span class="block font-heading font-bold text-ink mb-1" style="font-size:16.5px;">{{ get_the_title() }}</span>
                                    <span class="block text-ink-4" style="font-size:12.5px;">{{ get_field('rola') }}</span>
                                </span>
                            </a>
                        @endwhile
                        @php(wp_reset_postdata())
                    </div>
                </div>
            </section>
        @endif
    @endforeach

    {{-- Sztab szkoleniowy --}}
    @if ($sztabQ->have_posts())
        <section class="bg-navy text-white" style="padding-block:clamp(52px,7vw,88px);">
            <div class="container-site">
                <div class="pb-4 mb-9" style="border-bottom:2px solid rgba(255,255,255,0.2);">
                    <div class="eyebrow eyebrow--onnavy mb-2.5">Wsparcie</div>
                    <h2 class="font-heading font-extrabold m-0" style="font-size:clamp(24px,3.4vw,36px);">Sztab szkoleniowy</h2>
                </div>
                <div class="grid gap-6" style="grid-template-columns:repeat(auto-fit,minmax(220px,1fr));">
                    @while ($sztabQ->have_posts()) @php($sztabQ->the_post())
                        <div style="border:1px solid rgba(255,255,255,0.16);padding:24px 26px;">
                            <div class="uppercase font-bold text-gold mb-2" style="font-size:11px;letter-spacing:0.08em;">{{ get_field('rola') ?: 'Sztab' }}</div>
                            <h3 class="font-heading font-bold m-0" style="font-size:18px;">{{ get_the_title() }}</h3>
                        </div>
                    @endwhile
                    @php(wp_reset_postdata())
                </div>
            </div>
        </section>
    @endif
@endsection
