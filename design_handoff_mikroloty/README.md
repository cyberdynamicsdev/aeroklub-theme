# Handoff: mikroloty.com — strona Komisji Mikrolotowej Aeroklubu Polskiego

## Overview
Kompletny szablon wizualny oficjalnego serwisu Komisji Mikrolotowej Aeroklubu Polskiego (mikroloty.com): strona główna + podstrony (Aktualności z widokiem wpisu, O komisji, Zawody, Kadra z profilem zawodnika, Dokumenty, Jak zacząć startować, FAQ, Kontakt). Serwis instytucjonalny/sportowy — poważny, ale otwarty na nowych zawodników, mobile-first, z dużą ilością whitespace i miejsca na zdjęcia.

## About the Design Files
Pliki w tej paczce to **referencje projektowe wykonane w HTML** — prototypy pokazujące docelowy wygląd i zachowanie, **nie kod produkcyjny do skopiowania 1:1**. Zadaniem jest **odtworzenie tych projektów w docelowym środowisku**: **WordPress z motywem Sage 11 (Tailwind CSS + Laravel Blade)**, zgodnie z konwencjami tego stacka (szablony Blade, komponenty, pola ACF / bloki Gutenberga dla treści redagowanej).

Uwaga techniczna: pliki mają rozszerzenie `.dc.html` i przy podglądzie korzystają z lekkiego runtime'u (`support.js`) do renderowania szablonu + drobnej interaktywności. To wyłącznie wygoda prototypu — **do implementacji traktuj je jak statyczny HTML/CSS**. Cała warstwa danych (listy zawodów, aktualności, kadra itd.) jest w prototypie zaszyta w JS jako dane przykładowe; w WordPressie ma pochodzić z CMS (pola ACF / CPT).

## Fidelity
**High-fidelity (hifi).** Finalne kolory, typografia, odstępy i stany interakcji. UI należy odtworzić wiernie, używając Tailwinda i komponentów Blade. Wartości (hex, rozmiary fontów, odstępy) podane niżej są wiążące.

## Design Tokens

### Kolory
- Granat (podstawowy / tła sekcji ciemnych, nagłówki): `#0e1f42`
- Granat jaśniejszy (paski statystyk, hover przycisku ciemnego): `#16294a`
- Złoto (akcent, CTA): `#f3c200`  · hover złota: `#ffd633`
- Złoto ciemniejsze (eyebrow / etykiety tekstowe na jasnym tle): `#b58a00`
- Tekst podstawowy: `#1a2233`  · tekst drugorzędny: `#33405c` / `#57627a`  · tekst wyciszony: `#7c88a3` / `#8792a8`
- Tło jasne sekcji naprzemiennych: `#f3f4f6`
- Tło placeholderów zdjęć: `#e6e9ef`
- Obramowania: `#dfe3ea` (jasne), `#cfd6e2` (kontrolki), `#eef1f5` (wiersze list)
- Tekst na granacie: `#ffffff`, drugorzędny `#c1cbe0`, wyciszony `#9fb0d0` / `#8797b8`

### Typografia
- Nagłówki / liczby / etykiety: **Archivo** (500–900), Google Fonts
- Tekst / UI: **IBM Plex Sans** (400–700), Google Fonts
- H1 stron: `clamp(30px,4.6vw,48px)`, Archivo 800, letter-spacing -0.01em
- H1 hero (strona główna): `clamp(36px,5.6vw,62px)`, Archivo 800, line-height 1.05
- H2 sekcji: `clamp(26px,3.6vw,38px)`, Archivo 800
- H3 kart: 16.5–20px, Archivo 700
- Body: 14.5–17px, IBM Plex Sans, line-height 1.5–1.75
- Eyebrow/etykiety: 11–12.5px, weight 700, letter-spacing .1–.16em, UPPERCASE

### Kształty i efekty
- **Ostre krawędzie** — brak zaokrągleń (border-radius 0) to domyślny, celowy wygląd instytucjonalny. (Prototyp ma przełącznik `roundedCards`, ale domyślnie wyłączony.)
- Brak cieni „SaaS" — hover kart to **zmiana koloru obramowania** na granat (`#0e1f42`), nie unoszenie/cień.
- Akcent nagłówków sekcji: gruba linia granatowa 2px pod nagłówkiem; złota kreska 22–34px jako eyebrow.
- Przyciski: płaskie, prostokątne, UPPERCASE, letter-spacing .04em. Główny = złoto na granacie; drugorzędny = obrys 1.5px.

### Layout / spacing
- Kontener: `max-width: 1240px`, padding poziomy `clamp(20px,4vw,40px)`
- Padding pionowy sekcji: `clamp(48–56px, 7–8vw, 80–96px)`
- Siatki kart: `grid-template-columns: repeat(auto-fit/auto-fill, minmax(200–320px, 1fr))`, gap 20–26px
- Nawigacja: sticky, wysokość ~112px z paskiem użytkowym

## Screens / Views

### Wspólne komponenty
- **Nav (`MikrolotyNav`)** — górny pasek użytkowy (granat, e-mail + link do portalu AP), pod nim sticky header: logo (roundel SVG + „MIKROLOTY / AEROKLUB POLSKI”) po lewej, menu po prawej (Aktualności / O komisji / Zawody / Kadra / Dokumenty / Jak zacząć), przełącznik PL|EN. Aktywna pozycja: złote podkreślenie 2px. Prop `active` ustawia podświetlenie.
- **Footer (`MikrolotyFooter`)** — granat; kolumna z logo + adresem, 3 kolumny linków (Komisja / Sport / Pomoc), pasek dolny z prawami autorskimi „© 1999–2026 Aeroklub Polski”.

### Strona główna (`Mikroloty AP.dc.html`)
Sekcje od góry: pasek użytkowy → nav → **Hero** (zdjęcie w tle + gradient granatowy, hasło, 2 przyciski, pasek 4 statystyk) → **Nadchodzące zawody** (3 karty: pas foto z etykietą klasy ULM/Paralotnia + status Planowane/W trakcie, data, nazwa, miejsce) → **Ostatnie aktualności** (1 wyróżniony wpis ze zdjęciem + lista boczna 2 wpisów + „wczytaj więcej”) → **CTA banner** (granat, zachęta dla nowych zawodników, przycisk „Dowiedz się jak zacząć”) → **Galeria zawodów** (mozaika 5 zdjęć, zoom na hover) → **Kadra narodowa** (siatka 6 zawodników) → footer.

### Aktualności — lista (`Aktualnosci.dc.html`)
Nagłówek strony (breadcrumb + tytuł + opis) → sticky pasek filtrów (Wszystkie / Zawody / Kadra / Komisja / Szkolenie, aktywny = złote podkreślenie inset) → siatka kart (zdjęcie + data na zdjęciu, tag, tytuł, zajawka, „Czytaj więcej”) → paginacja. Karta linkuje do wpisu.

### Aktualność — wpis (`Aktualnosc-wpis.dc.html`)
Nagłówek granatowy węższy (max 820px): breadcrumb, etykieta tagu (złoto), tytuł, meta (data · autor · czas czytania) → duże zdjęcie wiodące (nachodzi na granat) → treść (max 720px, lead + akapity + H2 + lista) → pasek „wróć / udostępnij” → sekcja „Powiązane aktualności” (3 karty).

### O komisji (`O-komisji.dc.html`), Zawody (`Zawody.dc.html`), Dokumenty (`Dokumenty.dc.html`), Jak zacząć (`Jak-zaczac.dc.html`)
Utrzymane w tym samym systemie (nagłówek granatowy + sekcje treści z granatową linią nagłówka). Zawody = kalendarz/karty z klasą i statusem; Dokumenty = lista plików do pobrania; Jak zacząć = kroki wejścia w sport.

### Kadra — lista (`Kadra.dc.html`)
Nagłówek → grupy „Klasa ULM” i „Klasa paralotniowa” (naprzemienne tła biały/`#f3f4f6`), każda z siatką kart zawodnika (portret 4:5 z etykietą grupy „Kadra A/B”, nazwisko + rola, górna krawędź karty granatowa 2px). Karta linkuje do profilu. Na końcu ciemna sekcja „Sztab szkoleniowy”.

### Kadra — profil (`Kadra-profil.dc.html`)
Nagłówek granatowy: portret 4:5 + nazwisko, grupa/rola, klub/rocznik/licencja → pasek statystyk (granat jaśniejszy) → dwie kolumny: Sylwetka + tabela „Sprzęt zawodnika”, oraz „Najważniejsze wyniki” (pozycje z medalami — złote pole dla podium).

### FAQ (`FAQ.dc.html`)
Nagłówek → pytania pogrupowane (Start w zawodach / Licencje i przepisy / Kadra) jako natywne `<details>` (rozwijane, „+” po prawej) → baner „Nie znalazłeś odpowiedzi?” z linkiem do Kontaktu.

### Kontakt (`Kontakt.dc.html`)
Nagłówek → dwie kolumny: lewa = dane kontaktowe (ikona w kwadracie granat/złoto), kontakty tematyczne (tabela osoba/rola/e-mail), placeholder mapy; prawa = formularz (imię, e-mail, temat select, wiadomość, zgoda RODO, przycisk „Wyślij”).

## Interactions & Behavior
- **Nav**: sticky top; przełącznik PL|EN zmienia stan aktywnego języka (w prototypie tylko UI — w WP podłączyć pod wtyczkę wielojęzyczną, np. Polylang/WPML).
- **Hover kart**: `border-color` → `#0e1f42` (transition .18s). Galeria: `transform: scale(1.05)` na obrazie (.3s).
- **Filtry aktualności**: zmiana aktywnej zakładki (inset box-shadow złoty). W WP: filtrowanie po kategorii/taksonomii.
- **FAQ**: rozwijanie natywnym `<details>/<summary>`.
- **Formularz kontaktowy**: `preventDefault` w prototypie; w WP podpiąć pod wtyczkę formularzy (np. Contact Form 7 / WPForms) lub REST.
- **Responsywność**: bez media queries — użyte `clamp()` + `grid auto-fit/auto-fill minmax()`. W Sage/Tailwind można to odwzorować utility-klasami i breakpointami (`sm: md: lg:`), zachowując te same progi.

## Zgodność z WordPress (redakcja bez dotykania kodu)
Każda sekcja jest mapowalna na pola redagowalne. Sugerowane CPT / pola ACF:
- **Zawody** (CPT `zawody`): nazwa, data/zakres, miejsce, klasa (ULM|Paralotnia), status (Planowane|W trakcie|Zakończone), zdjęcie, treść, wyniki.
- **Aktualności** (posty): tytuł, data, kategoria/tag, obraz wyróżniający, zajawka, treść, autor.
- **Kadra** (CPT `zawodnik`): imię i nazwisko, klasa, grupa (Kadra A/B), rola, klub, rocznik, nr licencji, portret, sylwetka, sprzęt (repeater), wyniki (repeater).
- **Dokumenty** (CPT `dokument`): tytuł, kategoria, plik, data.
- **FAQ** (CPT `faq`): grupa, pytanie, odpowiedź.
- Hero, CTA, dane kontaktowe: opcje motywu / ACF Options.

## Assets
Zdjęcia przykładowe w `images/` (dostarczone przez klienta — samoloty ULM, motolotnia, wiatrakowce, mapa nawigacyjna, puchar). To materiały poglądowe do wizualizacji; w produkcji redakcja podmienia je przez media WordPress. Logo/roundel narysowany jako inline SVG (granat + złoto, motyw skrzydła/steru) — inspirowany identyfikacją AP, nie kopia 1:1; docelowo zastąpić finalnym logo komisji.
Fonty: Archivo + IBM Plex Sans z Google Fonts.

## Files
- `Mikroloty AP.dc.html` — strona główna
- `MikrolotyNav.dc.html`, `MikrolotyFooter.dc.html` — wspólne nagłówek/stopka
- `Aktualnosci.dc.html`, `Aktualnosc-wpis.dc.html` — aktualności lista + wpis
- `O-komisji.dc.html`, `Zawody.dc.html`, `Dokumenty.dc.html`, `Jak-zaczac.dc.html`
- `Kadra.dc.html`, `Kadra-profil.dc.html` — kadra lista + profil
- `FAQ.dc.html`, `Kontakt.dc.html`
- `support.js` — runtime podglądu (tylko do prototypu, nie do produkcji)
- `images/` — zdjęcia poglądowe
