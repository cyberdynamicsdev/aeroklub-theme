# Motyw Mikroloty

Motyw WordPress dla **mikroloty.com** — oficjalnej strony Komisji Mikrolotowej
Aeroklubu Polskiego. Zbudowany na **Sage 11** (Tailwind CSS v4, Vite, Laravel
Blade) + **ACF Pro**.

Standard kodu: **kod, komentarze, nazwy pól i klas — po angielsku**; teksty
widoczne dla użytkownika, etykiety w panelu oraz slugi URL — po polsku.

---

## Stack

- WordPress 6.6+ / PHP 8.2+
- Sage 11 (Acorn, Blade, Vite)
- Tailwind CSS v4 (konfiguracja w CSS — `resources/css/app.css`)
- ACF Pro (własne pola i strony opcji)
- Alpine.js (filtr zawodów, wyszukiwarka dokumentów)
- Fonty **self-hostowane** (Archivo + IBM Plex Sans przez `@fontsource`) —
  bez CDN Google (zgodność z RODO)

---

## Wymagania i instalacja (dev)

```bash
cd wp-content/themes/mikroloty
composer install      # zależności PHP (Acorn)
npm install           # zależności front-end
npm run dev           # serwer Vite z HMR (praca lokalna)
npm run build         # build produkcyjny do public/build
```

Aktywuj motyw w panelu WP, zainstaluj wtyczki (niżej), a w **Custom Fields →
Narzędzia / Sync** zsynchronizuj grupy pól z katalogu `acf-json/`.

---

## Struktura katalogów

```
mikroloty/
├── app/
│   ├── setup.php          # theme support, rejestracja menu (primary + footer)
│   ├── filters.php        # filtry Sage (domyślne)
│   ├── helpers.php        # funkcje: format dat PL, status zawodów, czas czytania
│   ├── post-types.php     # rejestracja CPT + domyślne kategorie aktualności
│   ├── acf.php            # ładowanie acf-json + strony opcji motywu
│   └── contact-form.php   # obsługa formularza kontaktowego (admin-post + wp_mail)
├── acf-json/              # definicje pól ACF (wersjonowane w git)
│   ├── group_competition.json
│   ├── group_athlete.json
│   ├── group_document.json
│   ├── group_faq.json
│   ├── group_home.json     # opcje: Strona główna (hero, statystyki, galeria, CTA)
│   ├── group_layout.json   # opcje: Nagłówek i stopka
│   └── group_contact.json  # opcje: Kontakt
├── resources/
│   ├── css/app.css        # Tailwind v4: design tokens, komponenty, warstwa bazowa
│   ├── js/app.js          # Alpine.js
│   └── views/             # szablony Blade (patrz niżej)
├── public/build/          # skompilowane assety (NIE edytować ręcznie)
├── theme.json             # paleta/fonty do edytora Gutenberga (generowane z Tailwinda)
└── style.css              # nagłówek motywu
```

---

## Model danych (wszystko edytowalne przez WordPress)

### Custom Post Types
Klucze typów są angielskie; slugi URL — polskie (SEO).

| CPT (klucz) | Slug URL | Menu | Opis |
|---|---|---|---|
| `competition` | `/zawody/` | Zawody | Kalendarz i wyniki zawodów |
| `athlete` | `/kadra/` | Kadra | Zawodnik kadry (1 wpis = 1 osoba, bez podglądu) |
| `judge` | `/sedziowie/` | Sędziowie | Sędzia zawodów (jak kadra, wg lat) |
| `national_team` | `/reprezentacja/` | Reprezentacja | Zespół wyjazdowy na MŚ (jak kadra, wg lat) |
| `document` | `/dokumenty/` | Dokumenty | Pliki PDF do pobrania |
| `faq` | — | FAQ | Pytania i odpowiedzi |
| `post` (natywne) | `/aktualnosci/` | Wpisy | Aktualności + kategorie |

### Pola ACF (nazwy pól = angielskie)

- **competition**: `start_date`, `end_date`, `location`,
  `status` (planned/ongoing/finished), `flyresult_id`,
  `results` (repeater: place/pilot/score), `documents` (repeater: name/file).
- **athlete**: `role` (pilot/nawigator/trener) + **taksonomia `sezon`** — lata
  reprezentacji, wpisywane po przecinku (np. „2025, 2026"). Nazwisko = tytuł,
  zdjęcie = obraz wyróżniający. Bez podglądu pojedynczego zawodnika (single
  przekierowuje na listę Kadry).
- **document**: `file` (PDF), `category`, `year`, `description`.
- **faq**: `group` (start/licenses/squad), `answer` (WYSIWYG). Pytanie = tytuł.

- **judge** i **national_team**: identyczna struktura jak `athlete` — `role` +
  własna taksonomia lat (`sezon_sedziow`, `sezon_reprezentacji`), bez podglądu.

**Kadra / Sędziowie / Reprezentacja** działają tak samo: archiwum pokazuje skład
bieżącego roku + pasek linków do pozostałych sezonów; widok konkretnego roku pod
`/{sekcja}-sezon/{rok}/` (np. `/kadra-sezon/2025/`, `/sedziowie-sezon/2026/`).
Lata wpisujesz po przecinku przy osobie (taksonomia sezonów, styl tagów).
Wspólny kod: `partials/people-archive.blade.php` + `partials/people-list.blade.php`.

### Strony opcji (ACF Pro → menu „Ustawienia motywu")
- **Strona główna** (`settings-homepage`): hero (obraz, tytuł, lead, 2 przyciski),
  statystyki, galeria, baner CTA.
- **Nagłówek i stopka** (`settings-header-footer`): pasek górny, opis/adres w stopce,
  kolumny linków, dolne linki, copyright.
- **Kontakt** (`settings-contact`): adres, e-mail, telefon, kontakty tematyczne, mapa.

---

## Szablony Blade (`resources/views/`)

| Plik | Renderuje |
|---|---|
| `layouts/app.blade.php` | Główny layout (head, nav, main, footer) |
| `sections/header.blade.php` | Pasek górny + sticky nav (logo, menu WP, przełącznik PL/EN) |
| `sections/footer.blade.php` | Granatowa stopka z opcji ACF |
| `front-page.blade.php` | Strona główna: hero+statystyki, zawody, aktualności, CTA, galeria, kadra |
| `index.blade.php` | Archiwum aktualności + filtr kategorii + paginacja |
| `single.blade.php` | Wpis: nagłówek, zdjęcie wiodące, treść, udostępnij, powiązane |
| `archive-competition.blade.php` | Kalendarz zawodów (lista) |
| `single-competition.blade.php` | Zawody: meta, treść, tabela wyników, dokumenty |
| `archive-{athlete,judge,national_team}.blade.php` | Kadra / Sędziowie / Reprezentacja — bieżący rok + nawigacja lat |
| `taxonomy-sezon*.blade.php` | Widok konkretnego roku dla każdej z trzech grup |
| `partials/people-archive.blade.php` | Wspólny szablon archiwum (nagłówek + zapytanie + lista) |
| `partials/people-list.blade.php` | Wspólna siatka osób + nawigacja lat |
| `page.blade.php` | Strona statyczna (O komisji, Jak zacząć) — nagłówek + treść |
| `template-documents.blade.php` | Szablon „Dokumenty": grupy wg kategorii + wyszukiwarka |
| `template-faq.blade.php` | Szablon „FAQ": grupy + rozwijane `<details>` |
| `template-contact.blade.php` | Szablon „Kontakt": dane + formularz |
| `components/` | `logo`, `page-header`, `section-head`, `competition-card`, `athlete-card`, `news-card` |
| `partials/language-switcher.blade.php` | Przełącznik PL/EN (Polylang) |

> **Uwaga o Blade:** w tej wersji Acorn nie mieszaj w jednym pliku formy
> `@php(...)` (callable) z blokiem `@php ... @endphp`. Szablony treści używają
> konsekwentnie bloku; `layouts/app.blade.php` (i domyślne partiale Sage) — callable.

### Konfiguracja stron w panelu
1. Utwórz stronę i ustaw ją jako **Stronę główną** (Ustawienia → Czytanie).
2. Utwórz stronę „Aktualności" i ustaw jako **stronę wpisów**.
3. Utwórz strony **Dokumenty / FAQ / Kontakt** i przypisz im odpowiedni
   **Szablon** (Atrybuty strony).
4. Zbuduj **Menu główne** i przypisz do lokalizacji „Menu główne".

---

## Edytowalność treści

Dwie warstwy — wszystko da się zmienić bez dotykania kodu:

1. **Treść i teksty sekcji → pola WP / ACF**
   - Wpisy, zawody, kadra, dokumenty, FAQ, strony — panel WP.
   - Hero, statystyki, galeria, CTA, **nagłówki sekcji strony głównej**,
     **wprowadzenia archiwów** → Ustawienia motywu → *Strona główna*.
   - Pasek górny, stopka, kontakt → Ustawienia motywu.
   - Menu, kategorie → panel WP.

2. **Mikrocopy UI → wtyczka tłumaczeń (Loco Translate)**
   Przyciski („Czytaj więcej", „Pobierz", „Wróć…"), etykiety formularza,
   komunikaty, banner FAQ itd. są opakowane w funkcje tłumaczeń
   (`__('…', 'mikroloty')`) i edytowalne bez kodu:
   - Zainstaluj **Loco Translate**.
   - Loco → Motywy → **Mikroloty** → utwórz język **polski (pl_PL)**.
   - Jako szablon wskaż `resources/lang/mikroloty.pot`; zapisz do lokalizacji
     **„System"** (`wp-content/languages/themes/`) — przetrwa aktualizacje motywu.
   - Edytuj dowolny string; WP automatycznie ładuje `mikroloty-pl_PL.mo`.

   Aktualizacja szablonu po zmianach w kodzie: `npm run translate:pot`
   (generuje `resources/lang/mikroloty.pot`; wykrywa też stringi z Blade).

## Wielojęzyczność (PL / EN)

Oparta na **Polylang** (darmowy). Motyw jest w pełni przygotowany:

- **Przełącznik PL|EN** w nawigacji — podpina się automatycznie, gdy Polylang
  jest aktywny (partial `language-switcher`).
- **CPT i taksonomia sezonów** są rejestrowane jako tłumaczalne
  (`pll_get_post_types` / `pll_get_taxonomies` w `app/polylang.php`) — działają
  archiwa per język (np. `/en/kadra/`).
- **Mikrocopy UI** — angielskie tłumaczenie dołączone w
  `resources/lang/mikroloty-en_US.mo` (ładuje się dla wersji EN). Poprawki przez
  Loco Translate.
- **Globalne teksty ACF** (hero, CTA, nagłówki sekcji, stopka, kontakt) są
  rejestrowane jako stringi Polylang → tłumaczysz je w **Języki → Tłumaczenia
  ciągów** (grupa „Mikroloty").

### Konfiguracja Polylang (jednorazowo)
1. Zainstaluj i aktywuj **Polylang**.
2. Dodaj języki: **Polski (pl_PL)** jako domyślny i **English (en_US)**.
3. Przypisz istniejącą treść **oraz terminy** (sezony) do języka domyślnego —
   Polylang zaproponuje to powiadomieniem. Bez tego archiwa lat (np.
   `/kadra-sezon/2025/`) mogą zwracać 404. Następnie **przebuduj bezpośrednie
   odnośniki** (Ustawienia → Bezpośrednie odnośniki → Zapisz).
4. Tłumacz treść wg potrzeb: strony (główna, O komisji, Jak zacząć), wpisy,
   opcjonalnie CPT. Etykiety UI działają automatycznie (EN dołączony).

> Zakres dwujęzyczności treści zależy od redakcji — strony i aktualności zwykle
> w obu językach; zawody/kadra/dokumenty można trzymać w jednym języku.

## Design tokens

Wszystkie kolory/fonty w `resources/css/app.css` (blok `@theme`). Zmiana tam
propaguje się do utility Tailwinda **oraz** do edytora Gutenberga (przez
`theme.json` generowany przy buildzie).

- Granat `#0e1f42`, złoto `#f3c200`, styl instytucjonalny (ostre krawędzie,
  płaskie, hover = zmiana koloru obramowania).

---

## Wtyczki (panel WP)

- **Advanced Custom Fields PRO** — wymagane (pola + strony opcji + repeatery)
- **Polylang** — wielojęzyczność PL/EN (przełącznik w nav podpina się automatycznie)
- **Yoast SEO** — SEO
- **Loco Translate** — edycja mikrocopy UI bez kodu (patrz „Edytowalność treści")
- **WP Super Cache** / **W3 Total Cache** — cache

> Repeatery i strony opcji wymagają ACF **Pro**. Bez Pro motyw działa, ale
> sekcje oparte na repeaterach (wyniki, sprzęt, galeria, kolumny stopki) pozostają
> puste (ukrywają się).

---

## Deploy na OVH (FTP)

Motyw kompilujemy lokalnie i wgrywamy gotowy katalog — na serwerze nie ma
Node/Composera.

1. **Zbuduj lokalnie:**
   ```bash
   composer install --no-dev --optimize-autoloader
   npm ci && npm run build
   ```
2. **Wgraj przez FTP** cały katalog motywu do
   `wp-content/themes/mikroloty/` na serwerze, w tym:
   - `app/`, `resources/`, `acf-json/`, `vendor/`, `public/build/`,
     `functions.php`, `index.php`, `style.css`, `theme.json`, `composer.json`
   - **pomiń**: `node_modules/`, `.git/` (ale `vendor/` i `public/build/` są
     wymagane w runtime).
3. **W panelu WP** (pierwsze wdrożenie): aktywuj motyw, zainstaluj wtyczki,
   w ACF wykonaj **Sync** grup pól, ustaw strony (sekcja „Konfiguracja stron").
4. **Cache Acorn:** po wgraniu wyczyść `wp-content/cache/acorn` (lub `wp acorn
   view:clear`), by przekompilować szablony Blade.

> Aktualizacja motywu = powtórz krok 1–2 (wgraj zmienione pliki + `public/build/`),
> następnie wyczyść cache Acorn.
