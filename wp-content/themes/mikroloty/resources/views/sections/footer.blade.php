@php
    $opis = get_field('footer_opis', 'option') ?: 'Komisja Mikrolotowa Aeroklubu Polskiego — organizacja sportu mikrolotowego w klasach ULM i paralotniowej.';
    $adres = get_field('footer_adres', 'option') ?: "Aeroklub Polski<br>ul. Wybrzeże Gdyńskie 4<br>01-531 Warszawa";
    $kolumny = get_field('footer_kolumny', 'option') ?: [];
    $dolne = get_field('footer_dolne_linki', 'option') ?: [];
    $copyright = get_field('footer_copyright', 'option') ?: '© 1999–2026 Aeroklub Polski. Wszelkie prawa zastrzeżone.';
@endphp

<footer class="bg-navy text-onnavy-2">
    <div class="container-site grid gap-10" style="grid-template-columns:repeat(auto-fit,minmax(200px,1fr));padding-block:clamp(48px,7vw,72px);">
        {{-- Kolumna z logo + adresem --}}
        <div>
            <x-logo variant="footer" class="mb-[18px]" />
            <p class="mb-4 text-onnavy-3" style="font-size:13.5px;line-height:1.6;max-width:260px;">{{ $opis }}</p>
            <div class="text-onnavy-3" style="font-size:13px;line-height:1.7;">{!! $adres !!}</div>
        </div>

        {{-- Kolumny linków --}}
        @forelse ($kolumny as $kol)
            <div>
                <h4 class="font-heading font-bold uppercase text-white mb-[18px]" style="font-size:12.5px;letter-spacing:0.12em;">{{ $kol['tytul'] }}</h4>
                <ul class="list-none m-0 p-0 flex flex-col gap-[11px]">
                    @foreach (($kol['linki'] ?: []) as $link)
                        <li><a href="{{ $link['url'] }}" class="text-onnavy-2 hover:text-gold" style="font-size:14px;">{{ $link['etykieta'] }}</a></li>
                    @endforeach
                </ul>
            </div>
        @empty
            @php
                $fallback = [
                    'Komisja' => ['O komisji', 'Zarząd', 'Kadra narodowa', 'Kontakt'],
                    'Sport' => ['Kalendarz zawodów', 'Wyniki', 'Przepisy sportowe', 'Jak zacząć startować'],
                    'Pomoc' => ['FAQ', 'Dokumenty do pobrania', 'Przydatne linki', 'aeroklubpolski.pl'],
                ];
            @endphp
            @foreach ($fallback as $tytul => $linki)
                <div>
                    <h4 class="font-heading font-bold uppercase text-white mb-[18px]" style="font-size:12.5px;letter-spacing:0.12em;">{{ $tytul }}</h4>
                    <ul class="list-none m-0 p-0 flex flex-col gap-[11px]">
                        @foreach ($linki as $etykieta)
                            <li><a href="#" class="text-onnavy-2 hover:text-gold" style="font-size:14px;">{{ $etykieta }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        @endforelse
    </div>

    {{-- Pasek dolny --}}
    <div style="border-top:1px solid rgba(255,255,255,0.10);">
        <div class="container-site flex flex-wrap items-center justify-between gap-x-6 gap-y-2.5 py-5" style="font-size:12.5px;color:#6f7fa0;">
            <span>{{ $copyright }}</span>
            @if ($dolne)
                <div class="flex gap-5">
                    @foreach ($dolne as $link)
                        <a href="{{ $link['url'] }}" style="color:#6f7fa0;" class="hover:text-onnavy-2">{{ $link['etykieta'] }}</a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</footer>
