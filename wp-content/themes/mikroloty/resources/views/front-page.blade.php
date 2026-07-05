@extends('layouts.app')

@section('content')
    @php
        // --- Theme options (Homepage) ---
        $heroImage = get_field('hero_image', 'option');
        $heroEyebrow = get_field('hero_eyebrow', 'option') ?: 'Sport lotniczy · klasa mikrolotowa';
        $heroTitle = get_field('hero_title', 'option') ?: 'Lataj, rywalizuj,<br>reprezentuj Polskę.';
        $heroLead = get_field('hero_lead', 'option') ?: 'Oficjalny serwis Komisji Mikrolotowej Aeroklubu Polskiego — aktualności, kalendarz zawodów, wyniki i kadra narodowa sportu mikrolotowego.';
        $btn1Label = get_field('hero_btn1_label', 'option') ?: 'Nadchodzące zawody';
        $btn1Link = get_field('hero_btn1_link', 'option');
        $btn2Label = get_field('hero_btn2_label', 'option') ?: 'Jak zacząć startować';
        $btn2Link = get_field('hero_btn2_link', 'option');
        $heroCaption = get_field('hero_caption', 'option');

        $stats = get_field('stats', 'option') ?: [
            ['value' => '2', 'label' => 'klasy sportowe'],
            ['value' => '14', 'label' => 'zawodów w sezonie'],
            ['value' => '48', 'label' => 'zawodników w kadrze'],
            ['value' => 'FAI', 'label' => 'ranga międzynarodowa'],
        ];

        $gallery = get_field('gallery', 'option') ?: [];
        $galleryLink = get_field('gallery_link', 'option');

        $ctaEyebrow = get_field('cta_eyebrow', 'option') ?: 'Dołącz do sportu';
        $ctaTitle = get_field('cta_title', 'option') ?: 'Masz licencję i marzenie o zawodach? Zacznij od pierwszego startu.';
        $ctaText = get_field('cta_text', 'option') ?: 'Prowadzimy zawodników od pierwszych zawodów regionalnych aż po kadrę narodową i mistrzostwa świata FAI. Sprawdź, jak dołączyć do rywalizacji.';
        $ctaBtnLabel = get_field('cta_btn_label', 'option') ?: 'Dowiedz się jak zacząć';
        $ctaBtnLink = get_field('cta_btn_link', 'option');

        // --- Queries ---
        $competitionsQ = new WP_Query([
            'post_type' => 'competition',
            'posts_per_page' => 3,
            'meta_key' => 'start_date',
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
            'meta_query' => [
                ['key' => 'status', 'value' => ['planned', 'ongoing'], 'compare' => 'IN'],
            ],
        ]);

        $newsQ = new WP_Query(['post_type' => 'post', 'posts_per_page' => 3]);

        $currentSeason = mikroloty_current_season_term();
        $athArgs = ['post_type' => 'athlete', 'posts_per_page' => 6];
        if ($currentSeason) {
            $athArgs['tax_query'] = [['taxonomy' => 'sezon', 'field' => 'term_id', 'terms' => $currentSeason->term_id]];
        }
        $athletesQ = new WP_Query($athArgs);

        $linkUrl = fn ($link, $fallback = '#') => is_array($link) ? ($link['url'] ?? $fallback) : ($link ?: $fallback);
    @endphp

    {{-- ============ HERO ============ --}}
    <section class="relative bg-navy text-white overflow-hidden flex items-stretch" style="min-height:clamp(460px,62vh,640px);">
        @if ($heroImage)
            <img src="{{ $heroImage['url'] }}" alt="{{ $heroImage['alt'] ?: '' }}" class="absolute inset-0 w-full h-full object-cover" style="object-position:center 40%;" />
        @endif
        <div class="absolute inset-0" style="background:linear-gradient(90deg,rgba(10,22,48,0.95) 0%,rgba(10,22,48,0.82) 46%,rgba(10,22,48,0.35) 100%);"></div>

        <div class="relative z-[2] w-full container-site flex flex-col justify-center" style="padding-block:clamp(56px,9vw,104px);">
            <div style="max-width:700px;">
                <div class="eyebrow eyebrow--onnavy mb-6" style="letter-spacing:0.18em;">{{ $heroEyebrow }}</div>
                <h1 class="font-heading font-extrabold m-0 mb-[22px]" style="font-size:clamp(36px,5.6vw,62px);line-height:1.05;letter-spacing:-0.01em;">{!! $heroTitle !!}</h1>
                <p class="text-onnavy m-0 mb-[34px]" style="font-size:clamp(15px,1.5vw,18px);line-height:1.65;max-width:540px;">{{ $heroLead }}</p>
                <div class="flex flex-wrap items-center gap-3.5">
                    <a href="{{ $linkUrl($btn1Link, '#competitions') }}" class="btn btn-gold">{{ $btn1Label }}</a>
                    <a href="{{ $linkUrl($btn2Link, '#start') }}" class="btn btn-outline-light">{{ $btn2Label }}</a>
                </div>
            </div>
        </div>

        @if ($heroCaption)
            <div class="absolute bottom-3 z-[2]" style="right:clamp(20px,4vw,40px);font-size:11px;color:rgba(255,255,255,0.45);">{{ $heroCaption }}</div>
        @endif

        {{-- Stats strip --}}
        <div class="absolute left-0 right-0 bottom-0 z-[2]" style="background:rgba(8,18,40,0.55);border-top:1px solid rgba(255,255,255,0.12);">
            <div class="container-site grid" style="grid-template-columns:repeat(auto-fit,minmax(140px,1fr));">
                @foreach ($stats as $s)
                    <div class="flex items-baseline gap-2.5" style="padding:16px 4px;border-right:1px solid rgba(255,255,255,0.10);">
                        <span class="font-heading font-extrabold text-white" style="font-size:24px;">{{ $s['value'] }}</span>
                        <span style="font-size:12.5px;color:#a4b1cc;">{{ $s['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ UPCOMING COMPETITIONS ============ --}}
    @if ($competitionsQ->have_posts())
        <section id="competitions" class="section-y bg-white">
            <div class="container-site">
                <x-section-head
                    eyebrow="Kalendarz sportowy"
                    title="Nadchodzące zawody"
                    :link="get_post_type_archive_link('competition')"
                    link-label="Pełny kalendarz" />
                <div class="grid gap-6" style="grid-template-columns:repeat(auto-fit,minmax(300px,1fr));">
                    @while ($competitionsQ->have_posts())
                        @php $competitionsQ->the_post(); @endphp                        <x-competition-card />
                    @endwhile
                    @php wp_reset_postdata(); @endphp                </div>
            </div>
        </section>
    @endif

    {{-- ============ LATEST NEWS ============ --}}
    @if ($newsQ->have_posts())
        @php
            $newsPosts = $newsQ->posts;
            $featured = $newsPosts[0];
            $side = array_slice($newsPosts, 1, 2);
            $newsUrl = get_permalink(get_option('page_for_posts')) ?: home_url('/');
        @endphp
        <section id="news" class="section-y bg-surface">
            <div class="container-site">
                <x-section-head
                    eyebrow="Z życia komisji"
                    title="Ostatnie aktualności"
                    :link="$newsUrl"
                    link-label="Wszystkie aktualności" />

                <div class="grid gap-[26px] items-start" style="grid-template-columns:repeat(auto-fit,minmax(320px,1fr));">
                    {{-- Featured --}}
                    <article class="card flex flex-col overflow-hidden">
                        <a href="{{ get_permalink($featured) }}" class="relative block aspect-video bg-placeholder overflow-hidden">
                            @if (has_post_thumbnail($featured))
                                {!! get_the_post_thumbnail($featured, 'large', ['class' => 'w-full h-full object-cover block']) !!}
                            @endif
                            <span class="absolute left-0 bottom-0 bg-navy text-white font-semibold" style="font-size:12px;padding:7px 13px;">{{ get_the_date('d.m.Y', $featured) }}</span>
                        </a>
                        <div class="flex-1 flex flex-col" style="padding:26px 28px 28px;">
                            @php $featuredCat = get_the_category($featured->ID); @endphp                            @if ($featuredCat)
                                <span class="uppercase font-bold text-gold-dark mb-3" style="font-size:11px;letter-spacing:0.1em;">{{ $featuredCat[0]->name }}</span>
                            @endif
                            <h3 class="font-heading font-bold text-ink m-0 mb-3" style="font-size:clamp(22px,2.4vw,27px);line-height:1.2;">
                                <a href="{{ get_permalink($featured) }}" class="hover:text-navy">{{ get_the_title($featured) }}</a>
                            </h3>
                            <p class="text-ink-3 m-0 mb-5" style="font-size:15px;line-height:1.6;">{{ get_the_excerpt($featured) }}</p>
                            <a href="{{ get_permalink($featured) }}" class="mt-auto inline-flex items-center gap-2 uppercase font-bold text-navy" style="font-size:13px;letter-spacing:0.04em;">
                                Czytaj więcej <span class="text-gold">→</span>
                            </a>
                        </div>
                    </article>

                    {{-- Side list --}}
                    <div class="flex flex-col gap-[18px]">
                        @foreach ($side as $post)
                            @php setup_postdata($post); @endphp                            <article class="card flex overflow-hidden">
                                <a href="{{ get_permalink() }}" class="flex-none bg-placeholder overflow-hidden" style="flex-basis:130px;">
                                    @if (has_post_thumbnail())
                                        {!! get_the_post_thumbnail(get_the_ID(), 'medium', ['class' => 'w-full h-full object-cover block']) !!}
                                    @endif
                                </a>
                                <div class="flex-1" style="padding:18px 20px;">
                                    <div class="flex gap-3 items-center mb-2">
                                        @php $sideCat = get_the_category(); @endphp                                        @if ($sideCat)
                                            <span class="uppercase font-bold text-gold-dark" style="font-size:10.5px;letter-spacing:0.1em;">{{ $sideCat[0]->name }}</span>
                                        @endif
                                        <span class="text-ink-5" style="font-size:12px;">{{ get_the_date('d.m.Y') }}</span>
                                    </div>
                                    <h3 class="font-heading font-bold text-ink m-0" style="font-size:17px;line-height:1.3;">
                                        <a href="{{ get_permalink() }}" class="hover:text-navy">{{ get_the_title() }}</a>
                                    </h3>
                                </div>
                            </article>
                        @endforeach
                        @php wp_reset_postdata(); @endphp                        <a href="{{ $newsUrl }}" class="border border-line-2 text-center uppercase font-bold text-navy hover:bg-[#e9ecf2]" style="padding:16px;font-size:13px;letter-spacing:0.04em;">Wczytaj więcej aktualności</a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ============ CTA BANNER ============ --}}
    <section id="start" class="bg-navy text-white relative overflow-hidden">
        <div class="relative container-site grid gap-9 items-center" style="grid-template-columns:repeat(auto-fit,minmax(280px,1fr));padding-block:clamp(52px,7vw,80px);">
            <div>
                <div class="eyebrow eyebrow--onnavy mb-[18px]" style="letter-spacing:0.18em;">{{ $ctaEyebrow }}</div>
                <h2 class="font-heading font-extrabold m-0 mb-4" style="font-size:clamp(26px,4vw,42px);line-height:1.1;letter-spacing:-0.01em;">{{ $ctaTitle }}</h2>
                <p class="text-onnavy m-0" style="font-size:16px;line-height:1.65;max-width:560px;">{{ $ctaText }}</p>
            </div>
            <div class="flex">
                <a href="{{ $linkUrl($ctaBtnLink) }}" class="btn btn-gold" style="padding:18px 32px;">{{ $ctaBtnLabel }} →</a>
            </div>
        </div>
    </section>

    {{-- ============ GALLERY ============ --}}
    @if (! empty($gallery))
        <section id="gallery" class="section-y bg-white">
            <div class="container-site">
                <x-section-head
                    eyebrow="Fotorelacje z zawodów"
                    title="Galeria zawodów"
                    :link="$galleryLink['url'] ?? null"
                    :link-label="$galleryLink['title'] ?? ($galleryLink ? 'Wszystkie zdjęcia' : null)" />
                <div class="grid gap-3" style="grid-template-columns:repeat(4,1fr);grid-auto-rows:1fr;">
                    @foreach (array_slice($gallery, 0, 5) as $i => $image)
                        <div class="relative overflow-hidden bg-placeholder {{ $i === 0 ? 'col-span-2 row-span-2' : '' }}" style="aspect-ratio:1/1;">
                            <img src="{{ $image['sizes']['large'] ?? $image['url'] }}" alt="{{ $image['alt'] ?: 'Galeria zawodów mikrolotowych' }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 hover:scale-105" />
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============ CURRENT SQUAD ============ --}}
    @if ($athletesQ->have_posts())
        <section id="squad" class="section-y bg-surface">
            <div class="container-site">
                <x-section-head
                    :eyebrow="'Sezon ' . ($currentSeason?->name ?: date('Y'))"
                    title="Aktualna kadra narodowa"
                    :link="get_post_type_archive_link('athlete')"
                    link-label="Zobacz pełną kadrę" />
                <div class="grid gap-5" style="grid-template-columns:repeat(auto-fill,minmax(200px,1fr));">
                    @while ($athletesQ->have_posts())
                        @php $athletesQ->the_post(); @endphp                        <x-athlete-card />
                    @endwhile
                    @php wp_reset_postdata(); @endphp                </div>
            </div>
        </section>
    @endif
@endsection
