{{--
  Template Name: Dokumenty
--}}

@extends('layouts.app')

@section('content')
    @php
        the_post();
        $kategorie = [
            'regulamin' => 'Regulaminy',
            'przepis' => 'Przepisy sportowe',
            'formularz' => 'Formularze',
            'katalog_konkurencji' => 'Katalogi konkurencji',
            'wyniki' => 'Wyniki',
        ];
        $lead = has_excerpt() ? get_the_excerpt() : 'Przepisy sportowe, regulaminy zawodów, formularze zgłoszeniowe i materiały dla zawodników klasy mikrolotowej.';
        $tytul = get_the_title();
    @endphp

    <div x-data="{ q: '' }">
        <x-page-header :title="$tytul" :lead="$lead" :crumbs="[['label' => $tytul]]">
            <div class="flex items-center max-w-[460px] bg-white mt-6">
                <input type="text" x-model="q" placeholder="Szukaj dokumentu…"
                       class="flex-1 border-none outline-none text-ink" style="padding:14px 18px;font-size:14.5px;" />
                <span class="bg-gold text-navy font-bold" style="padding:14px 22px;font-size:14px;">Szukaj</span>
            </div>
        </x-page-header>

        <section class="bg-white" style="padding-block:clamp(48px,6vw,80px);">
            <div class="container-site" style="max-width:1000px;">
                @foreach ($kategorie as $slug => $label)
                    @php
                        $q = new WP_Query([
                            'post_type' => 'dokument',
                            'posts_per_page' => -1,
                            'orderby' => 'title',
                            'order' => 'ASC',
                            'meta_query' => [['key' => 'kategoria', 'value' => $slug, 'compare' => '=']],
                        ]);
                        $nazwy = [];
                        foreach ($q->posts as $p) {
                            $nazwy[] = mb_strtolower(get_the_title($p));
                        }
                    @endphp
                    @if ($q->have_posts())
                        <div class="mb-11" x-show="q === '' || {{ \Illuminate\Support\Js::from($nazwy) }}.some(n => n.includes(q.toLowerCase()))">
                            <div class="flex items-baseline gap-3 border-b-2 border-navy pb-3.5 mb-2">
                                <h2 class="font-heading font-extrabold text-navy m-0" style="font-size:clamp(20px,2.6vw,26px);">{{ $label }}</h2>
                                <span class="text-ink-5" style="font-size:13px;">{{ $q->found_posts }} {{ _n('dokument', 'dokumentów', $q->found_posts, 'mikroloty') }}</span>
                            </div>
                            @while ($q->have_posts()) @php($q->the_post())
                                @php
                                    $plik = get_field('plik_pdf');
                                    $opis = get_field('opis');
                                    $format = $plik ? strtoupper(pathinfo($plik['filename'], PATHINFO_EXTENSION)) : 'PDF';
                                    $rozmiar = $plik && ! empty($plik['filesize']) ? size_format($plik['filesize']) : '';
                                    $metaParts = array_filter([$format ?: null, $rozmiar ?: null, 'akt. ' . get_the_modified_date('d.m.Y')]);
                                @endphp
                                <a href="{{ $plik['url'] ?? '#' }}" target="_blank" rel="noopener"
                                   x-show="q === '' || {{ \Illuminate\Support\Js::from(mb_strtolower(get_the_title())) }}.includes(q.toLowerCase())"
                                   class="flex items-center gap-[18px] border-b hover:bg-[#f7f8fb] transition-colors" style="padding:18px 8px;border-color:#e6eaf1;">
                                    <span class="font-heading font-extrabold bg-navy text-gold flex items-center justify-center" style="flex:0 0 auto;width:40px;height:48px;font-size:11px;">{{ $format }}</span>
                                    <span class="flex-1 min-w-0">
                                        <span class="block font-semibold text-ink" style="font-size:15.5px;">{{ get_the_title() }}</span>
                                        <span class="block text-ink-5 mt-[3px]" style="font-size:13px;">{{ $opis ? $opis . ' · ' : '' }}{{ implode(' · ', $metaParts) }}</span>
                                    </span>
                                    <span class="uppercase font-bold text-navy inline-flex items-center gap-2" style="flex:0 0 auto;font-size:12.5px;letter-spacing:0.04em;">Pobierz <span class="text-gold" style="font-size:15px;">↓</span></span>
                                </a>
                            @endwhile
                            @php(wp_reset_postdata())
                        </div>
                    @endif
                @endforeach
            </div>
        </section>
    </div>
@endsection
