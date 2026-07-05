# mikroloty.com — prompt startowy dla Claude Code

## Kontekst projektu

Robimy nowy motyw WordPress dla **mikroloty.com** — oficjalnej strony Komisji Mikrolotowej Aeroklubu Polskiego (dział sportu ministerstwa sportu). Zostajemy na istniejącej instalacji WordPress na OVH, wymieniamy tylko motyw. Wgrywamy skompilowany motyw przez FTP.

Obecna strona: https://www.mikroloty.com/

---

## Stack techniczny

- **WordPress** — istniejąca instalacja, nie ruszamy bazy ani hostingu
- **Sage 11** — motyw (Tailwind v4, Vite, Laravel Blade templates)
- **ACF Pro** — własne typy treści i pola
- **@tailwindcss/typography** — style dla treści z Gutenberga
- **PHP 8.2+**, lokalny dev przez Laravel Herd lub XAMPP/Laragon
- Deploy: `npm run build` → wgranie folderu `public/` przez FTP

---

## Zasady projektu

- Żadnych page builderów (Elementor, Divi, WPBakery)
- Żadnych gotowych motywów jako bazy
- Minimum pluginów — tylko to co niezbędne
- Każdy deweloper znający WP musi móc to przejąć bez specjalistycznej wiedzy
- Wideo tylko przez embed YouTube / Vimeo — nigdy upload bezpośrednio na serwer

---

## Instalacja Sage 11

```bash
cd wp-content/themes/
composer create-project roots/sage mikroloty
cd mikroloty
npm install
npm run build
```

Aktywuj motyw w panelu WordPress. Następnie zainstaluj ACF Pro przez panel pluginów.

---

## Struktura szablonów Blade

```
resources/views/
  layouts/
    app.blade.php          # główny layout (nav + footer)
  index.blade.php          # strona główna
  single.blade.php         # artykuł / aktualność
  archive.blade.php        # lista aktualności
  page.blade.php           # strona statyczna
  archive-zawody.blade.php # lista zawodów
  single-zawody.blade.php  # szczegóły zawodów + wyniki
  page-dokumenty.blade.php # dokumenty PDF
  page-kadra.blade.php     # kadra z filtrem po roku
  page-jak-zaczac.blade.php # CTA dla nowych zawodników
  components/
    zawody-card.blade.php
    dokument-row.blade.php
    aktualnosc-card.blade.php
    hero.blade.php
    cta-banner.blade.php   # baner zachęcający do startu w zawodach
    kadra-rok.blade.php    # skład kadry dla jednego roku
```

---

## Kolory i typografia

Paleta marki:
- Granat: `#003366` — główny kolor, nagłówki, nav
- Niebieski nieba: `#87CEEB` — akcenty, linki
- Złoto: `#C9A84C` — CTA, wyróżnienia
- Biel: `#FFFFFF` — tła
- Szary: `#F5F5F5` — tła sekcji, karty

Fonty:
- Nagłówki: **Inter** (700)
- Body: **system-ui**

Tailwind `theme.config.js` musi eksportować te kolory do `theme.json` WordPressa żeby były dostępne w edytorze Gutenberga.

---

## Custom Post Types i pola ACF

### CPT: `zawody`
Pola ACF:
- `data_start` (date picker)
- `data_koniec` (date picker)
- `miejsce` (text)
- `klasa_statku` (checkbox: ULM / Paralotnia / Inne)
- `status` (select: Planowane / W trakcie / Zakończone)
- `flyresult_id` (text) — ID zawodów w flyresult.com do przyszłej integracji API
- `wyniki` (repeater: miejsce, pilot, wynik) — używane gdy nie ma integracji z flyresult
- `dokumenty` (repeater: nazwa, plik PDF)

### CPT: `kadra`
Pola ACF:
- `rok` (number)
- `zawodnicy` (repeater: imie_nazwisko, klub, klasa_statku, zdjecie, osiagniecia)

Archiwum kadry: filtrowanie po roku bez przeładowania strony (Alpine.js lub prosty JS).
Redaktor nie dotyka szablonów — wszystko przez panel WP.

### CPT: `dokumenty`
Pola ACF:
- `plik_pdf` (file)
- `kategoria` (select: Regulamin / Przepis / Formularz / Katalog konkurencji / Wyniki)
- `rok` (number)
- `opis` (textarea)

---

## Struktura nawigacji

Menu główne:
- Aktualności
- O komisji (historia, zarząd, kontakt)
- Zawody (kalendarz, wyniki, archiwum)
- Kadra (skład per rok, sędziowie)
- Dokumenty (regulaminy, katalogi, formularze)
- Jak zacząć startować ← strona z CTA
- EN ← przełącznik języka (Polylang lub WPML)

Stopka:
- Przydatne linki (PANSA, IMGW, FAI, Aeroklub Polski)
- FAQ / Poradnik
- Kontakt

---

## Strona główna — sekcje

1. **Hero** — zdjęcie z zawodów, hasło, dwa przyciski: "Nadchodzące zawody" i "Jak zacząć startować"
2. **Najbliższe zawody** — 3 karty z CPT `zawody` gdzie status = Planowane
3. **Ostatnie aktualności** — 3 najnowsze wpisy
4. **CTA banner** — "Brakuje nam zawodników. Weź udział w zawodach." z przyciskiem do strony jak-zaczac
5. **Kadra w skrócie** — aktualna kadra, link do pełnej strony

---

## Strona "Jak zacząć startować"

Strona dedykowana rekrutacji nowych zawodników. Powinna zawierać:
- Wymagania (licencja pilota, klasa statku)
- Jak wygląda pierwszy start w zawodach
- Kontakt do komisji
- Link do najbliższych zawodów
- Duże, wyraźne CTA

---

## Treści z Gutenberga — stylowanie

W `single.blade.php` i `page.blade.php`:

```blade
<div class="prose prose-lg max-w-none
            prose-headings:text-[#003366]
            prose-a:text-[#87CEEB] prose-a:no-underline hover:prose-a:underline
            prose-img:rounded-xl prose-img:shadow-md">
  @php(the_content())
</div>
```

Pliki PDF w treści:
```css
.wp-block-file {
  @apply flex items-center gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200 my-4;
}
```

---

## Wielojęzyczność

Zainstaluj plugin **Polylang** (darmowy). Przetłumacz przynajmniej:
- Stronę główną
- O komisji
- Jak zacząć startować
- Zawody i wyniki (etykiety UI)

Przełącznik języka PL / EN w prawym górnym rogu nav.

---

## Dev workflow

```bash
npm run dev    # Vite dev server z HMR
npm run build  # build produkcyjny do /public
```

Po buildzie wgraj folder `public/` przez FTP na serwer OVH.

---

## Pluginy do zainstalowania

Przez panel WordPress:
- Advanced Custom Fields PRO (licencja)
- Polylang (darmowy, wielojęzyczność)
- Yoast SEO (darmowy)
- WP Super Cache lub W3 Total Cache (cache)

Opcjonalnie później:
- wtyczka własna do integracji z flyresult.com API

---

## Na końcu sesji pokaż mi

1. Pełną strukturę katalogów projektu
2. Listę wszystkich zarejestrowanych CPT i pól ACF
3. Wszystkie szablony Blade z opisem co renderują
4. Instrukcję deployu przez FTP krok po kroku

---

*Projekt: mikroloty.com | Komisja Mikrolotowa Aeroklubu Polskiego*
*Stack: WordPress + Sage 11 + Tailwind v4 + ACF Pro*
