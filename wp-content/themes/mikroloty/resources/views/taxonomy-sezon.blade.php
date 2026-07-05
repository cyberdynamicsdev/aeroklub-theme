@extends('layouts.app')

@section('content')
    @php
        $term = get_queried_object();
        $athletes = new WP_Query([
            'post_type' => 'athlete',
            'posts_per_page' => -1,
            'orderby' => 'menu_order title',
            'order' => 'ASC',
            'tax_query' => [['taxonomy' => 'sezon', 'field' => 'term_id', 'terms' => $term->term_id]],
        ]);
    @endphp

    <x-page-header
        :title="'Kadra narodowa ' . $term->name"
        lead="Skład reprezentacji Polski w sezonie."
        :crumbs="[['label' => 'Kadra', 'url' => get_post_type_archive_link('athlete')], ['label' => $term->name]]" />

    @include('partials.kadra-list', ['athletes' => $athletes, 'activeTermId' => $term->term_id])
@endsection
