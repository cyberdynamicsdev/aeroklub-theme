@extends('layouts.app')

@section('content')
    @php
        $postsPage = get_option('page_for_posts');
        $bazaUrl = $postsPage ? get_permalink($postsPage) : home_url('/');
        $tytul = is_category() ? single_cat_title('', false) : ($postsPage ? get_the_title($postsPage) : 'Aktualności');
        $lead = $postsPage ? get_the_excerpt($postsPage) : '';
        $lead = $lead ?: 'Najnowsze informacje z życia Komisji Mikrolotowej — relacje z zawodów, komunikaty kadry oraz sprawy organizacyjne i sportowe.';

        $filtry = [['label' => 'Wszystkie', 'url' => $bazaUrl, 'active' => ! is_category()]];
        foreach (['zawody', 'kadra', 'komisja', 'szkolenie'] as $slug) {
            $term = get_category_by_slug($slug);
            if ($term) {
                $filtry[] = [
                    'label' => $term->name,
                    'url' => get_category_link($term->term_id),
                    'active' => is_category($term->term_id),
                ];
            }
        }
    @endphp

    <x-page-header :title="$tytul" :lead="$lead" :crumbs="[['label' => 'Aktualności']]" />

    {{-- Filtry kategorii --}}
    <div class="border-b border-line bg-white sticky top-0 z-40">
        <div class="container-site flex gap-1.5 overflow-x-auto">
            @foreach ($filtry as $f)
                <a href="{{ $f['url'] }}"
                   class="font-semibold whitespace-nowrap transition-colors {{ $f['active'] ? 'text-navy' : 'text-ink-3 hover:text-navy' }}"
                   style="font-size:13.5px;letter-spacing:0.03em;padding:16px 18px;{{ $f['active'] ? 'box-shadow:inset 0 -3px 0 var(--color-gold);' : '' }}">{{ $f['label'] }}</a>
            @endforeach
        </div>
    </div>

    <section class="bg-white" style="padding-block:clamp(44px,6vw,72px);">
        <div class="container-site">
            @if (have_posts())
                <div class="grid gap-[26px]" style="grid-template-columns:repeat(auto-fill,minmax(300px,1fr));">
                    @while (have_posts()) @php(the_post())
                        <x-aktualnosc-card />
                    @endwhile
                </div>
                <div class="mt-12">{!! paginate_links(['type' => 'list', 'prev_text' => '←', 'next_text' => '→']) !!}</div>
            @else
                <p class="text-ink-3">{{ __('Brak aktualności do wyświetlenia.', 'mikroloty') }}</p>
            @endif
        </div>
    </section>
@endsection
