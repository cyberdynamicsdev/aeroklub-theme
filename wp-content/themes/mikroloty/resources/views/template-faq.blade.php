{{--
  Template Name: FAQ
--}}

@extends('layouts.app')

@section('content')
    @php
        the_post();
        $groups = [
            'start' => 'Start w zawodach',
            'licenses' => 'Licencje i przepisy',
            'squad' => 'Kadra narodowa',
        ];
        $lead = has_excerpt() ? get_the_excerpt() : 'Odpowiedzi na pytania, które najczęściej otrzymujemy od pilotów rozważających start w zawodach mikrolotowych.';
        $title = get_the_title();
        // Contact page (if it exists) for the "Didn't find an answer?" banner
        $contactPage = get_page_by_path('kontakt');
        $contactUrl = $contactPage ? get_permalink($contactPage) : home_url('/kontakt/');
    @endphp

    <x-page-header :title="$title" :lead="$lead" :crumbs="[['label' => $title]]" />

    <section class="bg-white" style="padding-block:clamp(48px,7vw,80px);">
        <div class="container-site" style="max-width:900px;">
            @foreach ($groups as $slug => $label)
                @php
                    $q = new WP_Query([
                        'post_type' => 'faq',
                        'posts_per_page' => -1,
                        'orderby' => 'menu_order',
                        'order' => 'ASC',
                        'meta_query' => [['key' => 'group', 'value' => $slug, 'compare' => '=']],
                    ]);
                @endphp
                @if ($q->have_posts())
                    <div class="mb-11">
                        <div class="flex items-center gap-3 mb-[18px]">
                            <span class="bg-gold" style="width:22px;height:2px;"></span>
                            <h2 class="font-heading font-extrabold text-navy m-0" style="font-size:clamp(20px,2.6vw,26px);">{{ $label }}</h2>
                        </div>
                        <div class="border-t border-line">
                            @while ($q->have_posts())
                                @php $q->the_post(); @endphp                                <details class="border-b border-line">
                                    <summary class="cursor-pointer flex justify-between gap-5 items-center font-heading font-bold text-ink list-none" style="padding:20px 4px;font-size:17px;">
                                        {{ get_the_title() }}
                                        <span class="faq-toggle flex-shrink-0 flex items-center justify-center border border-line-2 text-navy" style="width:26px;height:26px;font-size:18px;"></span>
                                    </summary>
                                    <div class="prose max-w-none text-ink-2" style="padding:0 4px 22px;font-size:15.5px;line-height:1.7;max-width:760px;">
                                        {!! get_field('answer') !!}
                                    </div>
                                </details>
                            @endwhile
                        </div>
                    </div>
                    @php wp_reset_postdata(); @endphp                @endif
            @endforeach

            {{-- Didn't find an answer? --}}
            <div class="bg-navy text-white flex flex-wrap gap-5 items-center justify-between mt-5" style="padding:clamp(28px,4vw,40px);">
                <div>
                    <h3 class="font-heading font-extrabold m-0 mb-1.5" style="font-size:22px;">Nie znalazłeś odpowiedzi?</h3>
                    <p class="m-0 text-onnavy" style="font-size:15px;">Napisz do nas — chętnie pomożemy postawić pierwsze kroki w sporcie mikrolotowym.</p>
                </div>
                <a href="{{ $contactUrl }}" class="btn btn-gold whitespace-nowrap">Skontaktuj się →</a>
            </div>
        </div>
    </section>
@endsection
