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
 * Menu label "Kadra". No public single view (see redirect below); athletes are
 * only listed on the Kadra archive and per-season taxonomy archives.
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
            'search_items' => __('Szukaj zawodników', 'mikroloty'),
            'not_found' => __('Nie znaleziono zawodników', 'mikroloty'),
            'all_items' => __('Wszyscy zawodnicy', 'mikroloty'),
            'archives' => __('Kadra narodowa', 'mikroloty'),
        ],
        'public' => true,
        'has_archive' => true,
        'menu_position' => 21,
        'menu_icon' => 'dashicons-groups',
        'supports' => ['title', 'thumbnail', 'revisions'],
        'rewrite' => ['slug' => 'kadra', 'with_front' => false],
        'show_in_rest' => true,
    ]);

    /**
     * Taxonomy: Sezon — years an athlete represented the squad.
     * Non-hierarchical (tag-style), so editors type years comma-separated
     * ("2025, 2026"). Powers the per-year Kadra archives.
     */
    register_taxonomy('sezon', 'athlete', [
        'labels' => [
            'name' => __('Sezony', 'mikroloty'),
            'singular_name' => __('Sezon', 'mikroloty'),
            'menu_name' => __('Sezony kadry', 'mikroloty'),
            'add_new_item' => __('Dodaj rok', 'mikroloty'),
            'new_item_name' => __('Rok (np. 2026)', 'mikroloty'),
            'separate_items_with_commas' => __('Lata po przecinku, np. 2025, 2026', 'mikroloty'),
        ],
        'public' => true,
        'hierarchical' => false,
        'show_admin_column' => true,
        'rewrite' => ['slug' => 'kadra-sezon', 'with_front' => false],
        'show_in_rest' => true,
    ]);
}, 10);

/**
 * CPT: Judge — competition judges. Same shape as athletes: one post per person,
 * a role, and years (taxonomy). No public single view.
 */
add_action('init', function () {
    register_post_type('judge', [
        'labels' => [
            'name' => __('Sędziowie', 'mikroloty'),
            'singular_name' => __('Sędzia', 'mikroloty'),
            'menu_name' => __('Sędziowie', 'mikroloty'),
            'add_new' => __('Dodaj sędziego', 'mikroloty'),
            'add_new_item' => __('Dodaj nowego sędziego', 'mikroloty'),
            'edit_item' => __('Edytuj sędziego', 'mikroloty'),
            'search_items' => __('Szukaj sędziów', 'mikroloty'),
            'not_found' => __('Nie znaleziono sędziów', 'mikroloty'),
            'all_items' => __('Wszyscy sędziowie', 'mikroloty'),
            'archives' => __('Sędziowie', 'mikroloty'),
        ],
        'public' => true,
        'has_archive' => true,
        'menu_position' => 22,
        'menu_icon' => 'dashicons-hammer',
        'supports' => ['title', 'thumbnail', 'revisions'],
        'rewrite' => ['slug' => 'sedziowie', 'with_front' => false],
        'show_in_rest' => true,
    ]);

    register_taxonomy('sezon_sedziow', 'judge', [
        'labels' => [
            'name' => __('Sezony', 'mikroloty'),
            'singular_name' => __('Sezon', 'mikroloty'),
            'menu_name' => __('Sezony (sędziowie)', 'mikroloty'),
            'add_new_item' => __('Dodaj rok', 'mikroloty'),
            'new_item_name' => __('Rok (np. 2026)', 'mikroloty'),
            'separate_items_with_commas' => __('Lata po przecinku, np. 2025, 2026', 'mikroloty'),
        ],
        'public' => true,
        'hierarchical' => false,
        'show_admin_column' => true,
        'rewrite' => ['slug' => 'sedziowie-sezon', 'with_front' => false],
        'show_in_rest' => true,
    ]);
}, 10);

/**
 * CPT: National team — the away team selected for the FAI world championships
 * in a given year. Same shape: one post per person, a role, and years.
 */
add_action('init', function () {
    register_post_type('national_team', [
        'labels' => [
            'name' => __('Reprezentacja', 'mikroloty'),
            'singular_name' => __('Reprezentant', 'mikroloty'),
            'menu_name' => __('Reprezentacja', 'mikroloty'),
            'add_new' => __('Dodaj reprezentanta', 'mikroloty'),
            'add_new_item' => __('Dodaj nowego reprezentanta', 'mikroloty'),
            'edit_item' => __('Edytuj reprezentanta', 'mikroloty'),
            'search_items' => __('Szukaj reprezentantów', 'mikroloty'),
            'not_found' => __('Nie znaleziono reprezentantów', 'mikroloty'),
            'all_items' => __('Cała reprezentacja', 'mikroloty'),
            'archives' => __('Reprezentacja', 'mikroloty'),
        ],
        'public' => true,
        'has_archive' => true,
        'menu_position' => 23,
        'menu_icon' => 'dashicons-flag',
        'supports' => ['title', 'thumbnail', 'revisions'],
        'rewrite' => ['slug' => 'reprezentacja', 'with_front' => false],
        'show_in_rest' => true,
    ]);

    register_taxonomy('sezon_reprezentacji', 'national_team', [
        'labels' => [
            'name' => __('Sezony', 'mikroloty'),
            'singular_name' => __('Sezon', 'mikroloty'),
            'menu_name' => __('Sezony (reprezentacja)', 'mikroloty'),
            'add_new_item' => __('Dodaj rok', 'mikroloty'),
            'new_item_name' => __('Rok (np. 2026)', 'mikroloty'),
            'separate_items_with_commas' => __('Lata po przecinku, np. 2025, 2026', 'mikroloty'),
        ],
        'public' => true,
        'hierarchical' => false,
        'show_admin_column' => true,
        'rewrite' => ['slug' => 'reprezentacja-sezon', 'with_front' => false],
        'show_in_rest' => true,
    ]);
}, 10);

/**
 * No individual profile for people CPTs — redirect any single URL to the
 * matching archive.
 */
add_action('template_redirect', function () {
    if (is_singular(['athlete', 'judge', 'national_team'])) {
        wp_safe_redirect(get_post_type_archive_link(get_post_type()), 301);
        exit;
    }
});

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
