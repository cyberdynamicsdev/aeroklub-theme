@extends('layouts.app')

@section('content')
    @php $term = get_queried_object(); @endphp

    <x-page-header
        :title="__('Kadra narodowa', 'mikroloty') . ' ' . $term->name"
        :lead="mikroloty_t(get_field('archive_squad_lead', 'option') ?: 'Reprezentacja, kadra narodowa i sędziowie sportu mikrolotowego.')"
        :crumbs="[['label' => __('Kadra', 'mikroloty'), 'url' => get_post_type_archive_link('athlete')], ['label' => $term->name]]" />

    @include('partials.kadra-body', ['term' => $term])
@endsection
