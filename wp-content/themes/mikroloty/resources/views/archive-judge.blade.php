@extends('layouts.app')

@section('content')
    @include('partials.people-archive', [
        'cpt' => 'judge',
        'taxonomy' => 'sezon_sedziow',
        'baseTitle' => __('Sędziowie', 'mikroloty'),
        'lead' => __('Sędziowie zawodów mikrolotowych w danym sezonie.', 'mikroloty'),
        'crumbLabel' => __('Sędziowie', 'mikroloty'),
        'emptyText' => __('Brak sędziów do wyświetlenia.', 'mikroloty'),
        'term' => null,
    ])
@endsection
