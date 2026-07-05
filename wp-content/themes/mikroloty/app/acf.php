<?php

/**
 * Advanced Custom Fields Pro integration.
 *
 * Field group definitions live in the theme as local JSON (acf-json/ directory),
 * so fields are versioned in git and portable across environments — editors don't
 * click them by hand, and on production a single "Sync" is enough.
 *
 * @link https://www.advancedcustomfields.com/resources/local-json/
 */

namespace App;

/**
 * Save field group changes to acf-json/ inside the theme.
 */
add_filter('acf/settings/save_json', function () {
    return get_theme_file_path('acf-json');
});

/**
 * Load field groups from acf-json/ inside the theme (instead of the default path).
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
 * Gentle admin notice when ACF Pro is not active.
 * The theme works without ACF, but CPT fields will then be empty.
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
 * Theme options pages — global, editable content (hero, CTA, top bar,
 * footer, contact details). Lets editors change them without touching code.
 */
add_action('acf/init', function () {
    if (! function_exists('acf_add_options_page')) {
        return;
    }

    acf_add_options_page([
        'page_title' => __('Ustawienia motywu', 'mikroloty'),
        'menu_title' => __('Ustawienia motywu', 'mikroloty'),
        'menu_slug' => 'theme-settings',
        'capability' => 'edit_theme_options',
        'icon_url' => 'dashicons-admin-customizer',
        'position' => 59,
        'redirect' => true,
    ]);

    acf_add_options_sub_page([
        'page_title' => __('Strona główna', 'mikroloty'),
        'menu_title' => __('Strona główna', 'mikroloty'),
        'menu_slug' => 'settings-homepage',
        'parent_slug' => 'theme-settings',
        'capability' => 'edit_theme_options',
    ]);

    acf_add_options_sub_page([
        'page_title' => __('Nagłówek i stopka', 'mikroloty'),
        'menu_title' => __('Nagłówek i stopka', 'mikroloty'),
        'menu_slug' => 'settings-header-footer',
        'parent_slug' => 'theme-settings',
        'capability' => 'edit_theme_options',
    ]);

    acf_add_options_sub_page([
        'page_title' => __('Kontakt', 'mikroloty'),
        'menu_title' => __('Kontakt', 'mikroloty'),
        'menu_slug' => 'settings-contact',
        'parent_slug' => 'theme-settings',
        'capability' => 'edit_theme_options',
    ]);
});
