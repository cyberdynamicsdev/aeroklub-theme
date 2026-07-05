<?php

/**
 * Custom Post Type registration.
 *
 * Data model (post-type keys are English; rewrite slugs stay Polish for SEO):
 *  - competition — event calendar and results
 *  - athlete     — one post per national-squad athlete (Kadra list + profile)
 *  - document    — downloadable PDF files
 *  - faq         — questions and answers
 *  - news        — native WordPress posts + categories (Zawody / Kadra / Komisja / Szkolenie)
 *
 * Fields are provided by ACF Pro (see app/acf.php and the acf-json/ directory).
 */

namespace App;

/**
 * CPT: Competition — calendar and results.
 */
add_action('init', function () {
    register_post_type('competition', [
        'labels' => [
            'name' => __('Zawody', 'mikroloty'),
            'singular_name' => __('Zawody', 'mikroloty'),
            'menu_name' => __('Zawody', 'mikroloty'),
            'add_new' => __('Dodaj zawody', 'mikroloty'),
            'add_new_item' => __('Dodaj nowe zawody', 'mikroloty'),
            'edit_item' => __('Edytuj zawody', 'mikroloty'),
            'view_item' => __('Zobacz zawody', 'mikroloty'),
            'search_items' => __('Szukaj zawodów', 'mikroloty'),
            'not_found' => __('Nie znaleziono zawodów', 'mikroloty'),
            'all_items' => __('Wszystkie zawody', 'mikroloty'),
            'archives' => __('Kalendarz zawodów', 'mikroloty'),
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
 * CPT: Athlete — national squad. One post = one athlete.
 * Menu label "Kadra"; a single post is the athlete profile.
 */
add_action('init', function () {
    register_post_type('athlete', [
        'labels' => [
            'name' => __('Kadra', 'mikroloty'),
            'singular_name' => __('Zawodnik', 'mikroloty'),
            'menu_name' => __('Kadra', 'mikroloty'),
            'add_new' => __('Dodaj zawodnika', 'mikroloty'),
            'add_new_item' => __('Dodaj nowego zawodnika', 'mikroloty'),
            'edit_item' => __('Edytuj zawodnika', 'mikroloty'),
            'view_item' => __('Zobacz profil', 'mikroloty'),
            'search_items' => __('Szukaj zawodników', 'mikroloty'),
            'not_found' => __('Nie znaleziono zawodników', 'mikroloty'),
            'all_items' => __('Wszyscy zawodnicy', 'mikroloty'),
            'archives' => __('Kadra narodowa', 'mikroloty'),
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
 * CPT: Document — downloadable PDF files (regulations, rules, forms…).
 */
add_action('init', function () {
    register_post_type('document', [
        'labels' => [
            'name' => __('Dokumenty', 'mikroloty'),
            'singular_name' => __('Dokument', 'mikroloty'),
            'menu_name' => __('Dokumenty', 'mikroloty'),
            'add_new' => __('Dodaj dokument', 'mikroloty'),
            'add_new_item' => __('Dodaj nowy dokument', 'mikroloty'),
            'edit_item' => __('Edytuj dokument', 'mikroloty'),
            'view_item' => __('Zobacz dokument', 'mikroloty'),
            'search_items' => __('Szukaj dokumentów', 'mikroloty'),
            'not_found' => __('Nie znaleziono dokumentów', 'mikroloty'),
            'all_items' => __('Wszystkie dokumenty', 'mikroloty'),
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

/**
 * CPT: FAQ — questions and answers, grouped by topic.
 */
add_action('init', function () {
    register_post_type('faq', [
        'labels' => [
            'name' => __('FAQ', 'mikroloty'),
            'singular_name' => __('Pytanie FAQ', 'mikroloty'),
            'menu_name' => __('FAQ', 'mikroloty'),
            'add_new' => __('Dodaj pytanie', 'mikroloty'),
            'add_new_item' => __('Dodaj nowe pytanie', 'mikroloty'),
            'edit_item' => __('Edytuj pytanie', 'mikroloty'),
            'view_item' => __('Zobacz pytanie', 'mikroloty'),
            'search_items' => __('Szukaj w FAQ', 'mikroloty'),
            'not_found' => __('Nie znaleziono pytań', 'mikroloty'),
            'all_items' => __('Wszystkie pytania', 'mikroloty'),
        ],
        'public' => true,
        'publicly_queryable' => false,
        'has_archive' => false,
        'menu_position' => 23,
        'menu_icon' => 'dashicons-editor-help',
        'supports' => ['title', 'page-attributes'],
        'rewrite' => false,
        'show_in_rest' => true,
    ]);
}, 10);

/**
 * Default news categories — created once, on theme activation.
 * Editors get ready-made filters: Zawody / Kadra / Komisja / Szkolenie.
 */
add_action('after_switch_theme', function () {
    $categories = [
        'zawody' => __('Zawody', 'mikroloty'),
        'kadra' => __('Kadra', 'mikroloty'),
        'komisja' => __('Komisja', 'mikroloty'),
        'szkolenie' => __('Szkolenie', 'mikroloty'),
    ];

    foreach ($categories as $slug => $name) {
        if (! term_exists($slug, 'category')) {
            wp_insert_term($name, 'category', ['slug' => $slug]);
        }
    }
});
