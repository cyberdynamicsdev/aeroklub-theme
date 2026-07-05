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
