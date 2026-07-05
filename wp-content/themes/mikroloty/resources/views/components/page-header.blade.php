@props([
    'title' => '',
    'lead' => null,
    'crumbs' => [],   // [['label' => ..., 'url' => ...], ...] — ostatni bez url = bieżąca
    'width' => 'wide', // wide (1240) | narrow (820)
])

@php
    $maxw = $width === 'narrow' ? 'max-width:820px;' : '';
@endphp

<section class="bg-navy text-white">
    <div class="container-site" style="{{ $maxw }}padding-block:clamp(40px,6vw,68px);">
        <nav class="mb-4 text-onnavy-2" style="font-size:12.5px;" aria-label="{{ __('Okruszki', 'mikroloty') }}">
            <a href="{{ home_url('/') }}" class="text-onnavy-2 hover:text-white">Home</a>
            @foreach ($crumbs as $c)
                <span class="mx-2">/</span>
                @if (! empty($c['url']))
                    <a href="{{ $c['url'] }}" class="text-onnavy-2 hover:text-white">{{ $c['label'] }}</a>
                @else
                    <span class="text-white">{{ $c['label'] }}</span>
                @endif
            @endforeach
        </nav>

        <h1 class="font-heading font-extrabold m-0" style="font-size:clamp(30px,4.6vw,48px);letter-spacing:-0.01em;">{{ $title }}</h1>

        @if ($lead)
            <p class="text-onnavy m-0 mt-3.5" style="font-size:clamp(15px,1.4vw,17px);max-width:640px;">{{ $lead }}</p>
        @endif

        {{ $slot }}
    </div>
</section>
