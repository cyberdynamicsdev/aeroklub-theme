@extends('layouts.app')

@section('content')
    <x-page-header
        title="Kalendarz zawodów"
        lead="Pełna lista zawodów mikrolotowych — terminy, miejsca, klasy i wyniki zakończonych rund."
        :crumbs="[['label' => 'Zawody']]" />

    <div x-data="{ klasa: 'Wszystkie' }">
        {{-- Filtry klas --}}
        <div class="border-b border-line bg-white">
            <div class="container-site flex flex-wrap items-center gap-x-7 gap-y-3.5 py-[18px]">
                <div class="flex gap-1.5">
                    @foreach (['Wszystkie', 'ULM', 'Paralotnia'] as $t)
                        <button type="button"
                            @click="klasa = '{{ $t }}'"
                            class="border border-line-2 bg-white cursor-pointer font-semibold transition-colors"
                            :class="klasa === '{{ $t }}' ? 'bg-navy text-white border-navy' : 'text-[#3a465f]'"
                            style="font-size:13px;letter-spacing:0.03em;padding:9px 16px;">{{ $t }}</button>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Lista --}}
        <section style="padding-block:clamp(40px,5vw,64px);" class="bg-white">
            <div class="container-site">
                @if (have_posts())
                    <div class="flex flex-col border-t border-line">
                        @while (have_posts()) @php(the_post())
                            @php
                                $klasa = get_field('klasa_statku');
                                $status = mikroloty_status_zawodow(get_field('status'));
                                $start = get_field('data_start');
                                $ds = $start ? DateTime::createFromFormat('Ymd', $start) : null;
                                $miejsce = get_field('miejsce');
                                $ctaLabel = match (get_field('status')) {
                                    'w_trakcie' => 'Śledź na żywo',
                                    'zakonczone' => 'Wyniki',
                                    default => 'Szczegóły',
                                };
                            @endphp
                            <div x-show="klasa === 'Wszystkie' || klasa === '{{ $klasa }}'"
                                 class="flex flex-wrap items-center gap-x-6 gap-y-4 border-b border-line transition-colors hover:bg-[#f7f8fb]" style="padding:24px 4px;">
                                <div class="text-center border-r-2 border-line-3 pr-5" style="flex:0 0 84px;">
                                    <div class="font-heading font-black text-navy" style="font-size:30px;line-height:1;">{{ $ds ? $ds->format('j') : '—' }}</div>
                                    <div class="uppercase font-bold text-ink-3 mt-[3px]" style="font-size:12.5px;">{{ $ds ? mikroloty_miesiac_skrot((int) $ds->format('n')) : '' }}</div>
                                </div>
                                <div style="flex:1 1 300px;min-width:240px;">
                                    <div class="flex items-center gap-2.5 mb-1.5">
                                        @if ($klasa)
                                            <span class="uppercase font-bold text-navy" style="font-size:10.5px;letter-spacing:0.06em;background:#eef1f8;border:1px solid #dbe2ef;padding:3px 9px;">{{ $klasa }}</span>
                                        @endif
                                        <span class="uppercase font-bold {{ $status['classes'] }}" style="font-size:10.5px;letter-spacing:0.05em;padding:3px 9px;">{{ $status['label'] }}</span>
                                    </div>
                                    <h3 class="font-heading font-bold text-ink m-0 mb-1" style="font-size:18.5px;">
                                        <a href="{{ get_permalink() }}" class="hover:text-navy">{{ get_the_title() }}</a>
                                    </h3>
                                    @if ($miejsce)
                                        <div class="text-ink-3" style="font-size:13.5px;">◆ {{ $miejsce }}</div>
                                    @endif
                                </div>
                                <a href="{{ get_permalink() }}" class="uppercase font-bold text-navy border border-line-2 hover:bg-navy hover:text-white transition-colors" style="flex:0 0 auto;font-size:12.5px;letter-spacing:0.04em;padding:11px 18px;">{{ $ctaLabel }} →</a>
                            </div>
                        @endwhile
                    </div>

                    <div class="mt-12">{!! paginate_links(['type' => 'list', 'prev_text' => '←', 'next_text' => '→']) !!}</div>
                @else
                    <p class="text-ink-3">{{ __('Brak zawodów do wyświetlenia.', 'mikroloty') }}</p>
                @endif
            </div>
        </section>
    </div>
@endsection
