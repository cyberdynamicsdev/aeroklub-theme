{{--
  Template Name: Kontakt
--}}

@extends('layouts.app')

@section('content')
    @php
        the_post();
        $title = get_the_title();
        $lead = has_excerpt() ? get_the_excerpt() : 'Masz pytanie o zawody, licencje albo chcesz zacząć startować? Napisz do Komisji Mikrolotowej — odpowiadamy w ciągu kilku dni roboczych.';

        $address = get_field('contact_address', 'option');
        $email = get_field('contact_email', 'option') ?: 'biuro@mikroloty.com';
        $phone = get_field('contact_phone', 'option');
        $people = get_field('contact_people', 'option') ?: [];
        $map = get_field('contact_map', 'option');

        $info = array_filter([
            ['icon' => '@', 'label' => __('E-mail', 'mikroloty'), 'value' => $email],
            $phone ? ['icon' => '☎', 'label' => __('Telefon', 'mikroloty'), 'value' => $phone] : null,
            $address ? ['icon' => '⌖', 'label' => __('Adres', 'mikroloty'), 'value' => wp_strip_all_tags($address)] : null,
        ]);

        $topics = [
            __('Chcę zacząć startować', 'mikroloty'),
            __('Pytanie o zawody', 'mikroloty'),
            __('Licencje i przepisy', 'mikroloty'),
            __('Media / współpraca', 'mikroloty'),
            __('Inne', 'mikroloty'),
        ];
        $status = $_GET['contact'] ?? null;
        $inputCls = 'border border-line-2 bg-white outline-none w-full text-ink';
        $inputStyle = 'padding:12px 14px;font-size:14.5px;';
    @endphp

    <x-page-header :title="$title" :lead="$lead" :crumbs="[['label' => $title]]" />

    <section class="bg-white" style="padding-block:clamp(48px,7vw,80px);">
        <div class="container-site grid gap-x-16 gap-y-9 items-start" style="grid-template-columns:repeat(auto-fit,minmax(300px,1fr));">

            {{-- Info --}}
            <div>
                <h2 class="font-heading font-extrabold text-navy m-0 mb-6 pb-3.5 border-b-2 border-navy" style="font-size:24px;">{{ __('Dane kontaktowe', 'mikroloty') }}</h2>
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

                @if ($people)
                    <h3 class="font-heading font-bold text-navy uppercase mt-9 mb-3.5" style="font-size:15px;letter-spacing:0.04em;">{{ __('Kontakty tematyczne', 'mikroloty') }}</h3>
                    <div class="border border-line">
                        @foreach ($people as $person)
                            <div class="flex justify-between gap-4 border-b border-line-3" style="padding:15px 18px;">
                                <div>
                                    <div class="font-semibold text-ink" style="font-size:14.5px;">{{ $person['name'] }}</div>
                                    @if (! empty($person['role']))<div class="text-ink-5" style="font-size:13px;">{{ $person['role'] }}</div>@endif
                                </div>
                                @if (! empty($person['email']))
                                    <a href="mailto:{{ $person['email'] }}" class="text-navy font-semibold self-center" style="font-size:13.5px;">{{ $person['email'] }}</a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                @if ($map)
                    <div class="mt-7.5 border border-line overflow-hidden [&_iframe]:w-full [&_iframe]:block" style="aspect-ratio:16/9;">
                        {!! $map !!}
                    </div>
                @endif
            </div>

            {{-- Form --}}
            <div class="bg-surface border border-line" style="padding:clamp(28px,3.5vw,40px);">
                <h2 class="font-heading font-extrabold text-navy m-0 mb-6" style="font-size:24px;">{{ __('Napisz do nas', 'mikroloty') }}</h2>

                @if ($status === 'sent')
                    <div class="mb-5 border border-[var(--color-status-live)] text-[var(--color-status-live)] bg-white" style="padding:14px 16px;font-size:14px;">{{ __('Dziękujemy! Wiadomość została wysłana.', 'mikroloty') }}</div>
                @elseif ($status === 'error')
                    <div class="mb-5 border border-[#c0392b] text-[#c0392b] bg-white" style="padding:14px 16px;font-size:14px;">{{ __('Nie udało się wysłać wiadomości. Sprawdź pola i spróbuj ponownie.', 'mikroloty') }}</div>
                @endif

                <form action="{{ esc_url(admin_url('admin-post.php')) }}" method="post" class="flex flex-col gap-[18px]">
                    <input type="hidden" name="action" value="mikroloty_contact">
                    {!! wp_nonce_field('mikroloty_contact', '_contact_nonce', true, false) !!}
                    {{-- honeypot --}}
                    <div style="position:absolute;left:-9999px;" aria-hidden="true">
                        <label>Strona WWW<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                    </div>

                    <div class="grid gap-[18px]" style="grid-template-columns:repeat(auto-fit,minmax(160px,1fr));">
                        <label class="flex flex-col gap-[7px] font-semibold text-ink-2" style="font-size:13px;">{{ __('Imię i nazwisko', 'mikroloty') }}
                            <input type="text" name="name" required placeholder="Jan Kowalski" class="{{ $inputCls }}" style="{{ $inputStyle }}">
                        </label>
                        <label class="flex flex-col gap-[7px] font-semibold text-ink-2" style="font-size:13px;">{{ __('Adres e-mail', 'mikroloty') }}
                            <input type="email" name="email" required placeholder="jan@example.com" class="{{ $inputCls }}" style="{{ $inputStyle }}">
                        </label>
                    </div>
                    <label class="flex flex-col gap-[7px] font-semibold text-ink-2" style="font-size:13px;">{{ __('Temat', 'mikroloty') }}
                        <select name="subject" class="{{ $inputCls }}" style="{{ $inputStyle }}">
                            @foreach ($topics as $t)<option>{{ $t }}</option>@endforeach
                        </select>
                    </label>
                    <label class="flex flex-col gap-[7px] font-semibold text-ink-2" style="font-size:13px;">{{ __('Wiadomość', 'mikroloty') }}
                        <textarea name="message" rows="6" required placeholder="{{ __('Twoja wiadomość...', 'mikroloty') }}" class="{{ $inputCls }}" style="{{ $inputStyle }}"></textarea>
                    </label>
                    <label class="flex gap-2.5 items-start text-ink-3" style="font-size:12.5px;line-height:1.5;">
                        <input type="checkbox" name="consent" required style="margin-top:3px;">
                        {{ __('Wyrażam zgodę na przetwarzanie danych w celu udzielenia odpowiedzi zgodnie z polityką prywatności.', 'mikroloty') }}
                    </label>
                    <button type="submit" class="btn text-white bg-navy hover:bg-navy-light self-start" style="border:none;">{{ __('Wyślij wiadomość', 'mikroloty') }}</button>
                </form>
            </div>
        </div>
    </section>
@endsection
