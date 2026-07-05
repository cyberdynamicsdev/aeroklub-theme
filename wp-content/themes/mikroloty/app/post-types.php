<?php

/**
 * Rejestracja własnych typów treści (Custom Post Types).
 *
 * Motyw mikroloty.com definiuje trzy CPT: zawody, kadra, dokumenty.
 * Pola do tych typów dodaje ACF Pro (patrz katalog acf-json/).
 */

namespace App;

/**
 * CPT: Zawody
 *
 * Kalendarz i wyniki zawodów mikrolotowych. Archiwum renderuje listę,
 * pojedynczy wpis pokazuje szczegóły + wyniki (repeater ACF lub API flyresult).
 */
add_action('init', function () {
    register_post_type('zawody', [
        'labels' => [
            'name' => __('Zawody', 'mikroloty'),
            'singular_name' => __('Zawody', 'mikroloty'),
            'menu_name' => __('Zawody', 'mikroloty'),
            'add_new' => __('Dodaj zawody', 'mikroloty'),
            'add_new_item' => __('Dodaj nowe zawody', 'mikroloty'),
            'edit_item' => __('Edytuj zawody', 'mikroloty'),
            'new_item' => __('Nowe zawody', 'mikroloty'),
            'view_item' => __('Zobacz zawody', 'mikroloty'),
            'view_items' => __('Zobacz zawody', 'mikroloty'),
            'search_items' => __('Szukaj zawodów', 'mikroloty'),
            'not_found' => __('Nie znaleziono zawodów', 'mikroloty'),
            'not_found_in_trash' => __('Brak zawodów w koszu', 'mikroloty'),
            'all_items' => __('Wszystkie zawody', 'mikroloty'),
            'archives' => __('Archiwum zawodów', 'mikroloty'),
        ],
        'public' => true,
        'has_archive' => true,
        'menu_position' => 20,
        'menu_icon' => 'dashicons-awards',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions'],
        'rewrite' => ['slug' => 'zawody', 'with_front' => false],
        'show_in_rest' => true,
    ]);
}, 10);

/**
 * CPT: Kadra
 *
 * Skład kadry narodowej per rok. Archiwum filtruje zawodników po roku
 * (pole ACF `rok`) bez przeładowania strony.
 */
add_action('init', function () {
    register_post_type('kadra', [
        'labels' => [
            'name' => __('Kadra', 'mikroloty'),
            'singular_name' => __('Kadra', 'mikroloty'),
            'menu_name' => __('Kadra', 'mikroloty'),
            'add_new' => __('Dodaj kadrę', 'mikroloty'),
            'add_new_item' => __('Dodaj nową kadrę', 'mikroloty'),
            'edit_item' => __('Edytuj kadrę', 'mikroloty'),
            'new_item' => __('Nowa kadra', 'mikroloty'),
            'view_item' => __('Zobacz kadrę', 'mikroloty'),
            'search_items' => __('Szukaj kadry', 'mikroloty'),
            'not_found' => __('Nie znaleziono kadry', 'mikroloty'),
            'not_found_in_trash' => __('Brak kadry w koszu', 'mikroloty'),
            'all_items' => __('Cała kadra', 'mikroloty'),
            'archives' => __('Archiwum kadry', 'mikroloty'),
        ],
        'public' => true,
        'has_archive' => true,
        'menu_position' => 21,
        'menu_icon' => 'dashicons-groups',
        'supports' => ['title', 'editor', 'thumbnail', 'revisions'],
        'rewrite' => ['slug' => 'kadra', 'with_front' => false],
        'show_in_rest' => true,
    ]);
}, 10);

/**
 * CPT: Dokumenty
 *
 * Regulaminy, przepisy, formularze, katalogi konkurencji, wyniki — pliki PDF.
 * Kategoria i rok jako pola ACF; strona page-dokumenty grupuje i filtruje.
 */
add_action('init', function () {
    register_post_type('dokumenty', [
        'labels' => [
            'name' => __('Dokumenty', 'mikroloty'),
            'singular_name' => __('Dokument', 'mikroloty'),
            'menu_name' => __('Dokumenty', 'mikroloty'),
            'add_new' => __('Dodaj dokument', 'mikroloty'),
            'add_new_item' => __('Dodaj nowy dokument', 'mikroloty'),
            'edit_item' => __('Edytuj dokument', 'mikroloty'),
            'new_item' => __('Nowy dokument', 'mikroloty'),
            'view_item' => __('Zobacz dokument', 'mikroloty'),
            'search_items' => __('Szukaj dokumentów', 'mikroloty'),
            'not_found' => __('Nie znaleziono dokumentów', 'mikroloty'),
            'not_found_in_trash' => __('Brak dokumentów w koszu', 'mikroloty'),
            'all_items' => __('Wszystkie dokumenty', 'mikroloty'),
            'archives' => __('Archiwum dokumentów', 'mikroloty'),
        ],
        'public' => true,
        'has_archive' => false,
        'menu_position' => 22,
        'menu_icon' => 'dashicons-media-document',
        'supports' => ['title', 'revisions'],
        'rewrite' => ['slug' => 'dokumenty', 'with_front' => false],
        'show_in_rest' => true,
    ]);
}, 10);
