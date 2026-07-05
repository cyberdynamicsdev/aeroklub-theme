<?php

/**
 * Polylang integration.
 *
 * Registers global texts stored in ACF options (hero, CTA, section headers,
 * footer, contact…) as Polylang strings, so they can be translated per language
 * under Languages → String translations. Output is wrapped with mikroloty_t()
 * in the templates.
 *
 * Post/page/CPT content is translated the standard Polylang way (each language
 * gets its own post). Theme UI microcopy is handled via .mo files (text domain).
 */

namespace App;

/**
 * Make the theme's custom post types and the season taxonomy translatable in
 * Polylang, so they get per-language URLs and archives (e.g. /en/kadra/).
 * Whether each item is actually translated is the editor's choice.
 */
add_filter('pll_get_post_types', function ($post_types, $is_settings) {
    return array_merge($post_types, [
        'competition' => 'competition',
        'athlete' => 'athlete',
        'document' => 'document',
        'faq' => 'faq',
    ]);
}, 10, 2);

add_filter('pll_get_taxonomies', function ($taxonomies, $is_settings) {
    return array_merge($taxonomies, ['sezon' => 'sezon']);
}, 10, 2);

add_action('init', function () {
    if (! function_exists('pll_register_string') || ! function_exists('get_field')) {
        return;
    }

    $group = 'Mikroloty';

    $reg = function (string $label, $value, bool $multiline = false) use ($group) {
        if (is_string($value) && $value !== '') {
            pll_register_string($label, $value, $group, $multiline);
        }
    };

    // Top bar
    $reg('Pasek — tekst', get_field('bar_text', 'option'));
    $reg('Pasek — link', get_field('bar_link_label', 'option'));

    // Hero
    $reg('Hero — etykieta', get_field('hero_eyebrow', 'option'));
    $reg('Hero — tytuł', get_field('hero_title', 'option'), true);
    $reg('Hero — opis', get_field('hero_lead', 'option'), true);
    $reg('Hero — przycisk 1', get_field('hero_btn1_label', 'option'));
    $reg('Hero — przycisk 2', get_field('hero_btn2_label', 'option'));
    $reg('Hero — podpis zdjęcia', get_field('hero_caption', 'option'));

    // Homepage section headers
    $reg('Sekcja Zawody — etykieta', get_field('sec_comp_eyebrow', 'option'));
    $reg('Sekcja Zawody — tytuł', get_field('sec_comp_title', 'option'));
    $reg('Sekcja Aktualności — etykieta', get_field('sec_news_eyebrow', 'option'));
    $reg('Sekcja Aktualności — tytuł', get_field('sec_news_title', 'option'));
    $reg('Sekcja Galeria — etykieta', get_field('sec_gallery_eyebrow', 'option'));
    $reg('Sekcja Galeria — tytuł', get_field('sec_gallery_title', 'option'));
    $reg('Sekcja Kadra — etykieta', get_field('sec_squad_eyebrow', 'option'));
    $reg('Sekcja Kadra — tytuł', get_field('sec_squad_title', 'option'));

    // Archive intros
    $reg('Archiwum Zawody — tytuł', get_field('archive_comp_title', 'option'));
    $reg('Archiwum Zawody — opis', get_field('archive_comp_lead', 'option'), true);
    $reg('Archiwum Kadra — opis', get_field('archive_squad_lead', 'option'), true);

    // CTA banner
    $reg('CTA — etykieta', get_field('cta_eyebrow', 'option'));
    $reg('CTA — tytuł', get_field('cta_title', 'option'));
    $reg('CTA — tekst', get_field('cta_text', 'option'), true);
    $reg('CTA — przycisk', get_field('cta_btn_label', 'option'));

    // Footer
    $reg('Stopka — opis', get_field('footer_description', 'option'), true);
    $reg('Stopka — adres', get_field('footer_address', 'option'), true);
    $reg('Stopka — copyright', get_field('footer_copyright', 'option'));

    // Contact
    $reg('Kontakt — adres', get_field('contact_address', 'option'), true);
    $reg('Kontakt — telefon', get_field('contact_phone', 'option'));

    // Stats (repeater)
    foreach ((get_field('stats', 'option') ?: []) as $i => $row) {
        $reg('Statystyka '.($i + 1).' — opis', $row['label'] ?? '');
    }

    // Footer columns + links (nested repeater)
    foreach ((get_field('footer_columns', 'option') ?: []) as $i => $col) {
        $reg('Stopka kolumna '.($i + 1), $col['title'] ?? '');
        foreach (($col['links'] ?? []) as $j => $link) {
            $reg('Stopka kolumna '.($i + 1).' link '.($j + 1), $link['label'] ?? '');
        }
    }
    foreach ((get_field('footer_bottom_links', 'option') ?: []) as $i => $link) {
        $reg('Stopka dolny link '.($i + 1), $link['label'] ?? '');
    }

    // Contact people (repeater)
    foreach ((get_field('contact_people', 'option') ?: []) as $i => $person) {
        $reg('Kontakt osoba '.($i + 1).' — imię', $person['name'] ?? '');
        $reg('Kontakt osoba '.($i + 1).' — rola', $person['role'] ?? '');
    }
}, 20);
