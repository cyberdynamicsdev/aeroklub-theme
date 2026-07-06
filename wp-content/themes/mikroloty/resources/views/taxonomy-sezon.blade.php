@extends('layouts.app')

@section('content')
    @include('partials.people-archive', [
        'cpt' => 'athlete',
        'taxonomy' => 'sezon',
        'baseTitle' => __('Kadra narodowa', 'mikroloty'),
        'lead' => mikroloty_t(get_field('archive_squad_lead', 'option') ?: 'Zawodnicy reprezentujący Polskę w sporcie mikrolotowym.'),
        'crumbLabel' => __('Kadra', 'mikroloty'),
        'emptyText' => __('Brak zawodników do wyświetlenia.', 'mikroloty'),
        'term' => get_queried_object(),
    ])
@endsection
