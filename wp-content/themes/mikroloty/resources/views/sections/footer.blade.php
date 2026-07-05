@php
    $description = get_field('footer_description', 'option') ?: 'Komisja Mikrolotowa Aeroklubu Polskiego — organizacja sportu mikrolotowego w Polsce.';
    $address = get_field('footer_address', 'option') ?: "Aeroklub Polski<br>ul. Wybrzeże Gdyńskie 4<br>01-531 Warszawa";
    $columns = get_field('footer_columns', 'option') ?: [];
    $bottomLinks = get_field('footer_bottom_links', 'option') ?: [];
    $copyright = get_field('footer_copyright', 'option') ?: '© 1999–2026 Aeroklub Polski. Wszelkie prawa zastrzeżone.';
@endphp

<footer class="bg-navy text-onnavy-2">
    <div class="container-site grid gap-10" style="grid-template-columns:repeat(auto-fit,minmax(200px,1fr));padding-block:clamp(48px,7vw,72px);">
        {{-- Logo + address column --}}
        <div>
            <x-logo variant="footer" class="mb-[18px]" />
            <p class="mb-4 text-onnavy-3" style="font-size:13.5px;line-height:1.6;max-width:260px;">{{ mikroloty_t($description) }}</p>
            <div class="text-onnavy-3" style="font-size:13px;line-height:1.7;">{!! mikroloty_t($address) !!}</div>
        </div>

        {{-- Link columns --}}
        @forelse ($columns as $column)
            <div>
                <h4 class="font-heading font-bold uppercase text-white mb-[18px]" style="font-size:12.5px;letter-spacing:0.12em;">{{ mikroloty_t($column['title']) }}</h4>
                <ul class="list-none m-0 p-0 flex flex-col gap-[11px]">
                    @foreach (($column['links'] ?: []) as $link)
                        <li><a href="{{ $link['url'] }}" class="text-onnavy-2 hover:text-gold" style="font-size:14px;">{{ mikroloty_t($link['label']) }}</a></li>
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
            @foreach ($fallback as $title => $links)
                <div>
                    <h4 class="font-heading font-bold uppercase text-white mb-[18px]" style="font-size:12.5px;letter-spacing:0.12em;">{{ $title }}</h4>
                    <ul class="list-none m-0 p-0 flex flex-col gap-[11px]">
                        @foreach ($links as $label)
                            <li><a href="#" class="text-onnavy-2 hover:text-gold" style="font-size:14px;">{{ $label }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        @endforelse
    </div>

    {{-- Bottom bar --}}
    <div style="border-top:1px solid rgba(255,255,255,0.10);">
        <div class="container-site flex flex-wrap items-center justify-between gap-x-6 gap-y-2.5 py-5" style="font-size:12.5px;color:#6f7fa0;">
            <span>{{ mikroloty_t($copyright) }}</span>
            @if ($bottomLinks)
                <div class="flex gap-5">
                    @foreach ($bottomLinks as $link)
                        <a href="{{ $link['url'] }}" style="color:#6f7fa0;" class="hover:text-onnavy-2">{{ mikroloty_t($link['label']) }}</a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</footer>
