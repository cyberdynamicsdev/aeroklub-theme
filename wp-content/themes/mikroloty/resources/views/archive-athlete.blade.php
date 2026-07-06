@extends('layouts.app')

@section('content')
    @php
        $current = mikroloty_current_season_term('sezon');
        $title = __('Kadra narodowa', 'mikroloty') . ($current ? ' ' . $current->name : '');
    @endphp

    <x-page-header
        :title="$title"
        :lead="mikroloty_t(get_field('archive_squad_lead', 'option') ?: 'Reprezentacja, kadra narodowa i sędziowie sportu mikrolotowego.')"
        :crumbs="[['label' => __('Kadra', 'mikroloty')]]" />

    @include('partials.kadra-body', ['term' => $current])
@endsection
