<?php

/**
 * Funkcje pomocnicze motywu (globalny namespace — wygodne wywołanie w Blade).
 */

if (! function_exists('mikroloty_miesiac_dopelniacz')) {
    /**
     * Polska nazwa miesiąca w dopełniaczu (np. 7 → „lipca").
     * Niezależne od zainstalowanej lokalizacji WP — gwarantuje poprawną gramatykę.
     */
    function mikroloty_miesiac_dopelniacz(int $m): string
    {
        $miesiace = [
            1 => 'stycznia', 2 => 'lutego', 3 => 'marca', 4 => 'kwietnia',
            5 => 'maja', 6 => 'czerwca', 7 => 'lipca', 8 => 'sierpnia',
            9 => 'września', 10 => 'października', 11 => 'listopada', 12 => 'grudnia',
        ];

        return $miesiace[$m] ?? '';
    }
}

if (! function_exists('mikroloty_zakres_dat')) {
    /**
     * Formatuje zakres dat zawodów z pól ACF (format Ymd).
     * Przykłady: „18–20 lipca 2026", „28 czerwca – 2 lipca 2026", „5 września 2026".
     */
    function mikroloty_zakres_dat($start, $koniec = null): string
    {
        if (! $start) {
            return '';
        }

        $ds = \DateTime::createFromFormat('Ymd', (string) $start);
        if (! $ds) {
            return '';
        }

        $de = $koniec ? \DateTime::createFromFormat('Ymd', (string) $koniec) : null;

        $dzien = fn ($d) => (int) $d->format('j');
        $mies = fn ($d) => mikroloty_miesiac_dopelniacz((int) $d->format('n'));
        $rok = fn ($d) => $d->format('Y');

        if (! $de || $ds == $de) {
            return sprintf('%d %s %s', $dzien($ds), $mies($ds), $rok($ds));
        }

        // Ten sam miesiąc i rok: „18–20 lipca 2026"
        if ($ds->format('Ym') === $de->format('Ym')) {
            return sprintf('%d–%d %s %s', $dzien($ds), $dzien($de), $mies($de), $rok($de));
        }

        // Ten sam rok, różne miesiące: „28 czerwca – 2 lipca 2026"
        if ($ds->format('Y') === $de->format('Y')) {
            return sprintf('%d %s – %d %s %s', $dzien($ds), $mies($ds), $dzien($de), $mies($de), $rok($de));
        }

        // Różne lata
        return sprintf(
            '%d %s %s – %d %s %s',
            $dzien($ds), $mies($ds), $rok($ds),
            $dzien($de), $mies($de), $rok($de)
        );
    }
}

if (! function_exists('mikroloty_status_zawodow')) {
    /**
     * Zwraca etykietę i klasy pigułki statusu zawodów.
     *
     * @return array{label: string, classes: string}
     */
    function mikroloty_status_zawodow(?string $status): array
    {
        return match ($status) {
            'w_trakcie' => [
                'label' => __('W trakcie', 'mikroloty'),
                'classes' => 'bg-[var(--color-status-live)] text-white',
            ],
            'zakonczone' => [
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

if (! function_exists('mikroloty_czas_czytania')) {
    /**
     * Szacowany czas czytania wpisu (min), ~200 słów/min.
     */
    function mikroloty_czas_czytania(?string $tresc): int
    {
        $slowa = str_word_count(wp_strip_all_tags((string) $tresc));

        return max(1, (int) ceil($slowa / 200));
    }
}
