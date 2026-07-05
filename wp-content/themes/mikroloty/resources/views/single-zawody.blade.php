@extends('layouts.app')

@section('content')
    @while (have_posts()) @php(the_post())
        @php
            $klasa = get_field('klasa_statku');
            $status = mikroloty_status_zawodow(get_field('status'));
            $data = mikroloty_zakres_dat(get_field('data_start'), get_field('data_koniec'));
            $miejsce = get_field('miejsce');
            $wyniki = get_field('wyniki') ?: [];
            $dokumenty = get_field('dokumenty') ?: [];
        @endphp

        <x-page-header
            :title="get_the_title()"
            :crumbs="[['label' => 'Zawody', 'url' => get_post_type_archive_link('zawody')], ['label' => get_the_title()]]">
            <div class="flex flex-wrap gap-x-6 gap-y-2 mt-5 text-onnavy" style="font-size:14.5px;">
                @if ($data)<span>◆ {{ $data }}</span>@endif
                @if ($miejsce)<span>◆ {{ $miejsce }}</span>@endif
                @if ($klasa)<span>◆ {{ $klasa }}</span>@endif
                <span class="uppercase font-bold {{ $status['classes'] }}" style="font-size:11px;letter-spacing:0.05em;padding:3px 10px;">{{ $status['label'] }}</span>
            </div>
        </x-page-header>

        @if (has_post_thumbnail())
            <div class="bg-navy">
                <div class="container-site" style="max-width:1040px;">
                    {!! get_the_post_thumbnail(get_the_ID(), 'full', ['class' => 'w-full object-cover block', 'style' => 'aspect-ratio:16/8;transform:translateY(clamp(-28px,-4vw,-40px));']) !!}
                </div>
            </div>
        @endif

        <section style="padding-block:clamp(32px,5vw,64px);" class="bg-white">
            <div class="container-site grid gap-y-14 gap-x-14" style="grid-template-columns:1fr;max-width:1000px;">

                @if (get_the_content())
                    <div class="prose prose-lg max-w-none prose-headings:font-heading prose-headings:text-navy prose-a:text-navy">
                        @php(the_content())
                    </div>
                @endif

                {{-- Wyniki --}}
                @if ($wyniki)
                    <div>
                        <h2 class="font-heading font-extrabold text-navy m-0 mb-4 pb-3.5 border-b-2 border-navy" style="font-size:24px;">Wyniki</h2>
                        <div class="border border-line">
                            <div class="flex items-center gap-4 px-[18px] py-3 bg-surface uppercase font-bold text-ink-4" style="font-size:11.5px;letter-spacing:0.06em;">
                                <span style="flex:0 0 48px;">Msc.</span>
                                <span class="flex-1">Pilot</span>
                                <span>Wynik</span>
                            </div>
                            @foreach ($wyniki as $w)
                                @php $poz = (int) ($w['miejsce'] ?? 0); @endphp
                                <div class="flex items-center gap-4 px-[18px] py-3.5 border-t border-line-3" style="font-size:15px;">
                                    <span class="font-heading font-extrabold flex items-center justify-center {{ $poz >= 1 && $poz <= 3 ? 'bg-gold text-navy' : 'bg-line-3 text-ink-3' }}" style="flex:0 0 40px;width:40px;height:40px;font-size:15px;">{{ $w['miejsce'] }}</span>
                                    <span class="flex-1 font-semibold text-ink">{{ $w['pilot'] }}</span>
                                    <span class="text-ink-3">{{ $w['wynik'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Dokumenty zawodów --}}
                @if ($dokumenty)
                    <div>
                        <h2 class="font-heading font-extrabold text-navy m-0 mb-4 pb-3.5 border-b-2 border-navy" style="font-size:24px;">Dokumenty</h2>
                        <div class="flex flex-col">
                            @foreach ($dokumenty as $d)
                                @php $plik = $d['plik'] ?? null; @endphp
                                @if ($plik)
                                    <a href="{{ $plik['url'] }}" target="_blank" rel="noopener" class="flex items-center gap-[18px] border-b border-line hover:bg-[#f7f8fb] transition-colors" style="padding:16px 8px;">
                                        <span class="font-heading font-extrabold bg-navy text-gold flex items-center justify-center" style="flex:0 0 auto;width:40px;height:48px;font-size:11px;">PDF</span>
                                        <span class="flex-1 font-semibold text-ink" style="font-size:15.5px;">{{ $d['nazwa'] }}</span>
                                        <span class="uppercase font-bold text-navy" style="font-size:12.5px;letter-spacing:0.04em;">Pobierz <span class="text-gold">↓</span></span>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <a href="{{ get_post_type_archive_link('zawody') }}" class="uppercase font-bold text-navy justify-self-start" style="font-size:13px;letter-spacing:0.04em;">← Wróć do kalendarza</a>
            </div>
        </section>
    @endwhile
@endsection
