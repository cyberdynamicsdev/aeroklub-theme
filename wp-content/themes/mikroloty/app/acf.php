<?php

/**
 * Integracja z Advanced Custom Fields Pro.
 *
 * Definicje grup pól trzymamy w motywie jako lokalny JSON (katalog acf-json/).
 * Dzięki temu pola są wersjonowane w git i przenoszalne między środowiskami —
 * redaktor nie musi ich klikać ręcznie, a na produkcji wystarczy „Sync”.
 *
 * @link https://www.advancedcustomfields.com/resources/local-json/
 */

namespace App;

/**
 * Zapisuj zmiany grup pól do acf-json/ w motywie.
 */
add_filter('acf/settings/save_json', function () {
    return get_theme_file_path('acf-json');
});

/**
 * Wczytuj grupy pól z acf-json/ w motywie (zamiast domyślnej ścieżki).
 *
 * @param  array<int, string>  $paths
 * @return array<int, string>
 */
add_filter('acf/settings/load_json', function ($paths) {
    unset($paths[0]);
    $paths[] = get_theme_file_path('acf-json');

    return $paths;
});

/**
 * Delikatne przypomnienie w panelu, gdy ACF Pro nie jest aktywny.
 * Motyw działa bez ACF, ale pola CPT będą wtedy puste.
 */
add_action('admin_notices', function () {
    if (function_exists('acf_get_field_groups')) {
        return;
    }

    printf(
        '<div class="notice notice-warning"><p>%s</p></div>',
        esc_html__('Motyw mikroloty wymaga wtyczki Advanced Custom Fields PRO do obsługi pól zawodów, kadry i dokumentów.', 'mikroloty')
    );
});

/**
 * Strony opcji motywu — globalne, edytowalne treści (hero, CTA, pasek górny,
 * stopka, dane kontaktowe). Dzięki temu redaktor zmienia je bez dotykania kodu.
 */
add_action('acf/init', function () {
    if (! function_exists('acf_add_options_page')) {
        return;
    }

    acf_add_options_page([
        'page_title' => __('Ustawienia motywu', 'mikroloty'),
        'menu_title' => __('Ustawienia motywu', 'mikroloty'),
        'menu_slug' => 'ustawienia-motywu',
        'capability' => 'edit_theme_options',
        'icon_url' => 'dashicons-admin-customizer',
        'position' => 59,
        'redirect' => true,
    ]);

    acf_add_options_sub_page([
        'page_title' => __('Strona główna', 'mikroloty'),
        'menu_title' => __('Strona główna', 'mikroloty'),
        'menu_slug' => 'ustawienia-strona-glowna',
        'parent_slug' => 'ustawienia-motywu',
        'capability' => 'edit_theme_options',
    ]);

    acf_add_options_sub_page([
        'page_title' => __('Nagłówek i stopka', 'mikroloty'),
        'menu_title' => __('Nagłówek i stopka', 'mikroloty'),
        'menu_slug' => 'ustawienia-naglowek-stopka',
        'parent_slug' => 'ustawienia-motywu',
        'capability' => 'edit_theme_options',
    ]);

    acf_add_options_sub_page([
        'page_title' => __('Kontakt', 'mikroloty'),
        'menu_title' => __('Kontakt', 'mikroloty'),
        'menu_slug' => 'ustawienia-kontakt',
        'parent_slug' => 'ustawienia-motywu',
        'capability' => 'edit_theme_options',
    ]);
});
