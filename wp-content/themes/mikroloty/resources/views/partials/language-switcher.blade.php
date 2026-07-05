{{--
  Przełącznik języka PL|EN.
  Podpięty pod Polylang, jeśli wtyczka jest aktywna; w przeciwnym razie
  pokazuje statyczny wskaźnik (PL aktywny) do czasu instalacji wielojęzyczności.
--}}
@php
    $languages = function_exists('pll_the_languages')
        ? pll_the_languages(['raw' => 1, 'hide_if_empty' => 0])
        : [];
@endphp

<div class="inline-flex border border-line-2 text-xs font-bold" style="margin-left:6px;">
    @if (! empty($languages))
        @foreach ($languages as $lang)
            <a href="{{ $lang['url'] }}"
               class="px-[13px] py-[6px] {{ $lang['current_lang'] ? 'bg-navy text-white' : 'text-ink-3' }}">
                {{ strtoupper($lang['slug']) }}
            </a>
        @endforeach
    @else
        <span class="px-[13px] py-[6px] bg-navy text-white">PL</span>
        <span class="px-[13px] py-[6px] text-ink-3">EN</span>
    @endif
</div>
