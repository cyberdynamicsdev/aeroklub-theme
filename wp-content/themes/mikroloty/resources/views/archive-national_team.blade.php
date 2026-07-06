@extends('layouts.app')

@section('content')
    @include('partials.people-archive', [
        'cpt' => 'national_team',
        'taxonomy' => 'sezon_reprezentacji',
        'baseTitle' => __('Reprezentacja', 'mikroloty'),
        'lead' => __('Zespół reprezentujący Polskę na mistrzostwach świata FAI w danym roku.', 'mikroloty'),
        'crumbLabel' => __('Reprezentacja', 'mikroloty'),
        'emptyText' => __('Brak reprezentantów do wyświetlenia.', 'mikroloty'),
        'term' => null,
    ])
@endsection
