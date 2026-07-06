<?php

/**
 * Theme helper functions (global namespace — convenient to call from Blade).
 */

if (! function_exists('mikroloty_month_genitive')) {
    /**
     * Polish month name in the genitive case (e.g. 7 → "lipca").
     * Independent of the installed WP locale — guarantees correct grammar.
     */
    function mikroloty_month_genitive(int $m): string
    {
        $months = [
            1 => 'stycznia', 2 => 'lutego', 3 => 'marca', 4 => 'kwietnia',
            5 => 'maja', 6 => 'czerwca', 7 => 'lipca', 8 => 'sierpnia',
            9 => 'września', 10 => 'października', 11 => 'listopada', 12 => 'grudnia',
        ];

        return $months[$m] ?? '';
    }
}

if (! function_exists('mikroloty_month_abbr')) {
    /**
     * Three-letter Polish month abbreviation (1 → "Sty", 7 → "Lip").
     */
    function mikroloty_month_abbr(int $m): string
    {
        $abbr = [
            1 => 'Sty', 2 => 'Lut', 3 => 'Mar', 4 => 'Kwi',
            5 => 'Maj', 6 => 'Cze', 7 => 'Lip', 8 => 'Sie',
            9 => 'Wrz', 10 => 'Paź', 11 => 'Lis', 12 => 'Gru',
        ];

        return $abbr[$m] ?? '';
    }
}

if (! function_exists('mikroloty_date_range')) {
    /**
     * Formats a competition date range from ACF fields (Ymd format).
     * Examples: "18–20 lipca 2026", "28 czerwca – 2 lipca 2026", "5 września 2026".
     */
    function mikroloty_date_range($start, $end = null): string
    {
        if (! $start) {
            return '';
        }

        $ds = \DateTime::createFromFormat('Ymd', (string) $start);
        if (! $ds) {
            return '';
        }

        $de = $end ? \DateTime::createFromFormat('Ymd', (string) $end) : null;

        $day = fn ($d) => (int) $d->format('j');
        $month = fn ($d) => mikroloty_month_genitive((int) $d->format('n'));
        $year = fn ($d) => $d->format('Y');

        if (! $de || $ds == $de) {
            return sprintf('%d %s %s', $day($ds), $month($ds), $year($ds));
        }

        // Same month and year: "18–20 lipca 2026"
        if ($ds->format('Ym') === $de->format('Ym')) {
            return sprintf('%d–%d %s %s', $day($ds), $day($de), $month($de), $year($de));
        }

        // Same year, different months: "28 czerwca – 2 lipca 2026"
        if ($ds->format('Y') === $de->format('Y')) {
            return sprintf('%d %s – %d %s %s', $day($ds), $month($ds), $day($de), $month($de), $year($de));
        }

        // Different years
        return sprintf(
            '%d %s %s – %d %s %s',
            $day($ds), $month($ds), $year($ds),
            $day($de), $month($de), $year($de)
        );
    }
}

if (! function_exists('mikroloty_competition_status')) {
    /**
     * Returns the label and pill classes for a competition status.
     *
     * @return array{label: string, classes: string}
     */
    function mikroloty_competition_status(?string $status): array
    {
        return match ($status) {
            'ongoing' => [
                'label' => __('W trakcie', 'mikroloty'),
                'classes' => 'bg-[var(--color-status-live)] text-white',
            ],
            'finished' => [
                'label' => __('Zakończone', 'mikroloty'),
                'classes' => 'bg-navy text-onnavy-2',
            ],
            default => [
                'label' => __('Planowane', 'mikroloty'),
                'classes' => 'bg-white text-navy border border-line-2',
            ],
        };
    }
}

if (! function_exists('mikroloty_t')) {
    /**
     * Translate a global (ACF options) string via Polylang, when active.
     * Falls back to the original text if Polylang is not installed.
     * Strings must be registered first (see app/polylang.php).
     */
    function mikroloty_t($text)
    {
        if (($text || $text === '0') && function_exists('pll__')) {
            return pll__($text);
        }

        return $text;
    }
}

if (! function_exists('mikroloty_current_season_term')) {
    /**
     * The "current" season term for a people taxonomy: the current year if it
     * exists, otherwise the most recent season. Null if no seasons defined.
     */
    function mikroloty_current_season_term(string $taxonomy = 'sezon'): ?\WP_Term
    {
        $year = date('Y');
        $term = get_term_by('name', $year, $taxonomy);

        if ($term instanceof \WP_Term) {
            return $term;
        }

        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => true,
            'orderby' => 'name',
            'order' => 'DESC',
            'number' => 1,
        ]);

        return (! is_wp_error($terms) && ! empty($terms)) ? $terms[0] : null;
    }
}

if (! function_exists('mikroloty_reading_time')) {
    /**
     * Estimated reading time (minutes), ~200 words/min.
     */
    function mikroloty_reading_time(?string $content): int
    {
        $words = str_word_count(wp_strip_all_tags((string) $content));

        return max(1, (int) ceil($words / 200));
    }
}
