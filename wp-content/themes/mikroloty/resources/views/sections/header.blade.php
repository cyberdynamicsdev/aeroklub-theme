@php
    $barText = get_field('bar_text', 'option') ?: 'Komisja Mikrolotowa · Aeroklub Polski';
    $barEmail = get_field('bar_email', 'option') ?: 'biuro@mikroloty.com';
    $barLinkLabel = get_field('bar_link_label', 'option');
    $barLinkUrl = get_field('bar_link_url', 'option');
@endphp

{{-- Top utility bar --}}
<div class="bg-navy text-onnavy-2" style="font-size:12.5px;">
    <div class="container-site flex flex-wrap items-center justify-between gap-x-6 gap-y-2 py-2">
        <span class="uppercase font-semibold tracking-[0.05em]" style="font-size:11.5px;">{{ $barText }}</span>
        <div class="flex items-center gap-[22px]">
            <a href="mailto:{{ $barEmail }}" class="text-onnavy-2 hover:text-white">{{ $barEmail }}</a>
            @if ($barLinkLabel)
                <a href="{{ $barLinkUrl ?: '#' }}" class="text-onnavy-2 hover:text-white">{{ $barLinkLabel }}</a>
            @endif
        </div>
    </div>
</div>

{{-- Sticky header --}}
<header class="sticky top-0 z-50 bg-white border-b border-line">
    <div class="container-site flex items-center justify-between gap-7 py-4">
        <x-logo variant="nav" />

        <nav class="flex items-center gap-7" aria-label="{{ __('Menu główne', 'mikroloty') }}">
            @if (has_nav_menu('primary_navigation'))
                {!! wp_nav_menu([
                    'theme_location' => 'primary_navigation',
                    'menu_class' => 'primary-menu',
                    'container' => false,
                    'depth' => 1,
                    'echo' => false,
                ]) !!}
            @else
                <ul class="primary-menu">
                    <li><a href="{{ get_post_type_archive_link('post') ?: home_url('/') }}">Aktualności</a></li>
                    <li><a href="#">O komisji</a></li>
                    <li><a href="{{ get_post_type_archive_link('competition') ?: '#' }}">Zawody</a></li>
                    <li><a href="{{ get_post_type_archive_link('athlete') ?: '#' }}">Kadra</a></li>
                    <li><a href="{{ get_post_type_archive_link('document') ?: '#' }}">Dokumenty</a></li>
                    <li><a href="#">Jak zacząć</a></li>
                </ul>
            @endif

            @include('partials.language-switcher')
        </nav>
    </div>
</header>
