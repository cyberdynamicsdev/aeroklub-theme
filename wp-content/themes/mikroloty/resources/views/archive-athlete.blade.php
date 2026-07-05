@extends('layouts.app')

@section('content')
    @php
        $current = mikroloty_current_season_term();
        $title = __('Kadra narodowa', 'mikroloty') . ($current ? ' ' . $current->name : '');

        $args = ['post_type' => 'athlete', 'posts_per_page' => -1, 'orderby' => 'menu_order title', 'order' => 'ASC'];
        if ($current) {
            $args['tax_query'] = [['taxonomy' => 'sezon', 'field' => 'term_id', 'terms' => $current->term_id]];
        }
        $athletes = new WP_Query($args);
        $activeTermId = $current?->term_id;
    @endphp

    <x-page-header
        :title="$title"
        :lead="mikroloty_t(get_field('archive_squad_lead', 'option') ?: 'Zawodnicy reprezentujący Polskę w sporcie mikrolotowym.')"
        :crumbs="[['label' => __('Kadra', 'mikroloty')]]" />

    @include('partials.kadra-list', ['athletes' => $athletes, 'activeTermId' => $activeTermId])
@endsection
