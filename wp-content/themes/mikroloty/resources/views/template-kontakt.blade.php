{{--
  Template Name: Kontakt
--}}

@extends('layouts.app')

@section('content')
    @php
        the_post();
        $tytul = get_the_title();
        $lead = has_excerpt() ? get_the_excerpt() : 'Masz pytanie o zawody, licencje albo chcesz zacząć startować? Napisz do Komisji Mikrolotowej — odpowiadamy w ciągu kilku dni roboczych.';

        $adres = get_field('kontakt_adres', 'option');
        $email = get_field('kontakt_email', 'option') ?: 'biuro@mikroloty.com';
        $telefon = get_field('kontakt_telefon', 'option');
        $osoby = get_field('kontakt_osoby', 'option') ?: [];
        $mapa = get_field('kontakt_mapa', 'option');

        $info = array_filter([
            ['icon' => '@', 'label' => 'E-mail', 'value' => $email],
            $telefon ? ['icon' => '☎', 'label' => 'Telefon', 'value' => $telefon] : null,
            $adres ? ['icon' => '⌖', 'label' => 'Adres', 'value' => wp_strip_all_tags($adres)] : null,
        ]);

        $topics = ['Chcę zacząć startować', 'Pytanie o zawody', 'Licencje i przepisy', 'Media / współpraca', 'Inne'];
        $status = $_GET['kontakt'] ?? null;
        $inputCls = 'border border-line-2 bg-white outline-none w-full text-ink';
        $inputStyle = 'padding:12px 14px;font-size:14.5px;';
    @endphp

    <x-page-header :title="$tytul" :lead="$lead" :crumbs="[['label' => $tytul]]" />

    <section class="bg-white" style="padding-block:clamp(48px,7vw,80px);">
        <div class="container-site grid gap-x-16 gap-y-9 items-start" style="grid-template-columns:repeat(auto-fit,minmax(300px,1fr));">

            {{-- INFO --}}
            <div>
                <h2 class="font-heading font-extrabold text-navy m-0 mb-6 pb-3.5 border-b-2 border-navy" style="font-size:24px;">Dane kontaktowe</h2>
                <div class="flex flex-col gap-[22px]">
                    @foreach ($info as $c)
                        <div class="flex gap-4 items-start">
                            <span class="flex-shrink-0 bg-navy text-gold flex items-center justify-center font-heading font-bold" style="width:44px;height:44px;">{{ $c['icon'] }}</span>
                            <div>
                                <div class="uppercase font-bold text-gold-dark mb-1" style="font-size:12px;letter-spacing:0.08em;">{{ $c['label'] }}</div>
                                <div class="text-ink" style="font-size:15.5px;line-height:1.5;">{{ $c['value'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($osoby)
                    <h3 class="font-heading font-bold text-navy uppercase mt-9 mb-3.5" style="font-size:15px;letter-spacing:0.04em;">Kontakty tematyczne</h3>
                    <div class="border border-line">
                        @foreach ($osoby as $o)
                            <div class="flex justify-between gap-4 border-b border-line-3" style="padding:15px 18px;">
                                <div>
                                    <div class="font-semibold text-ink" style="font-size:14.5px;">{{ $o['osoba'] }}</div>
                                    @if (! empty($o['rola']))<div class="text-ink-5" style="font-size:13px;">{{ $o['rola'] }}</div>@endif
                                </div>
                                @if (! empty($o['email']))
                                    <a href="mailto:{{ $o['email'] }}" class="text-navy font-semibold self-center" style="font-size:13.5px;">{{ $o['email'] }}</a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                @if ($mapa)
                    <div class="mt-7.5 border border-line overflow-hidden [&_iframe]:w-full [&_iframe]:block" style="aspect-ratio:16/9;">
                        {!! $mapa !!}
                    </div>
                @endif
            </div>

            {{-- FORM --}}
            <div class="bg-surface border border-line" style="padding:clamp(28px,3.5vw,40px);">
                <h2 class="font-heading font-extrabold text-navy m-0 mb-6" style="font-size:24px;">Napisz do nas</h2>

                @if ($status === 'ok')
                    <div class="mb-5 border border-[var(--color-status-live)] text-[var(--color-status-live)] bg-white" style="padding:14px 16px;font-size:14px;">Dziękujemy! Wiadomość została wysłana.</div>
                @elseif ($status === 'blad')
                    <div class="mb-5 border border-[#c0392b] text-[#c0392b] bg-white" style="padding:14px 16px;font-size:14px;">Nie udało się wysłać wiadomości. Sprawdź pola i spróbuj ponownie.</div>
                @endif

                <form action="{{ esc_url(admin_url('admin-post.php')) }}" method="post" class="flex flex-col gap-[18px]">
                    <input type="hidden" name="action" value="mikroloty_kontakt">
                    {!! wp_nonce_field('mikroloty_kontakt', '_kontakt_nonce', true, false) !!}
                    {{-- honeypot --}}
                    <div style="position:absolute;left:-9999px;" aria-hidden="true">
                        <label>Strona WWW<input type="text" name="strona_www" tabindex="-1" autocomplete="off"></label>
                    </div>

                    <div class="grid gap-[18px]" style="grid-template-columns:repeat(auto-fit,minmax(160px,1fr));">
                        <label class="flex flex-col gap-[7px] font-semibold text-ink-2" style="font-size:13px;">Imię i nazwisko
                            <input type="text" name="imie" required placeholder="Jan Kowalski" class="{{ $inputCls }}" style="{{ $inputStyle }}">
                        </label>
                        <label class="flex flex-col gap-[7px] font-semibold text-ink-2" style="font-size:13px;">Adres e-mail
                            <input type="email" name="email" required placeholder="jan@example.com" class="{{ $inputCls }}" style="{{ $inputStyle }}">
                        </label>
                    </div>
                    <label class="flex flex-col gap-[7px] font-semibold text-ink-2" style="font-size:13px;">Temat
                        <select name="temat" class="{{ $inputCls }}" style="{{ $inputStyle }}">
                            @foreach ($topics as $t)<option>{{ $t }}</option>@endforeach
                        </select>
                    </label>
                    <label class="flex flex-col gap-[7px] font-semibold text-ink-2" style="font-size:13px;">Wiadomość
                        <textarea name="wiadomosc" rows="6" required placeholder="Twoja wiadomość..." class="{{ $inputCls }}" style="{{ $inputStyle }}"></textarea>
                    </label>
                    <label class="flex gap-2.5 items-start text-ink-3" style="font-size:12.5px;line-height:1.5;">
                        <input type="checkbox" name="zgoda" required style="margin-top:3px;">
                        Wyrażam zgodę na przetwarzanie danych w celu udzielenia odpowiedzi zgodnie z polityką prywatności.
                    </label>
                    <button type="submit" class="btn text-white bg-navy hover:bg-navy-light self-start" style="border:none;">Wyślij wiadomość</button>
                </form>
            </div>
        </div>
    </section>
@endsection
