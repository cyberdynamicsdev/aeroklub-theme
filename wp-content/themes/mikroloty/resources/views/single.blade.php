@extends('layouts.app')

@section('content')
    @while (have_posts()) @php(the_post())
        @php
            $kat = get_the_category();
            $tag = $kat ? $kat[0]->name : '';
            $czas = mikroloty_czas_czytania(get_the_content());
            $postsPage = get_option('page_for_posts');
            $archUrl = $postsPage ? get_permalink($postsPage) : home_url('/');
        @endphp

        {{-- Nagłówek --}}
        <section class="bg-navy text-white">
            <div class="container-site" style="max-width:820px;padding-block:clamp(40px,6vw,64px);">
                <nav class="mb-[22px] text-onnavy-2" style="font-size:12.5px;">
                    <a href="{{ home_url('/') }}" class="text-onnavy-2 hover:text-white">Home</a>
                    <span class="mx-2">/</span>
                    <a href="{{ $archUrl }}" class="text-onnavy-2 hover:text-white">Aktualności</a>
                    <span class="mx-2">/</span>
                    <span class="text-white">Wpis</span>
                </nav>
                @if ($tag)
                    <span class="inline-block uppercase font-bold text-navy bg-gold mb-[18px]" style="font-size:11px;letter-spacing:0.1em;padding:5px 11px;">{{ $tag }}</span>
                @endif
                <h1 class="font-heading font-extrabold m-0 mb-5" style="font-size:clamp(28px,4.4vw,44px);line-height:1.12;letter-spacing:-0.01em;">{{ get_the_title() }}</h1>
                <div class="flex flex-wrap gap-x-[22px] gap-y-2" style="font-size:13.5px;color:#aab6d2;">
                    <span>{{ get_the_date('j F Y') }}</span><span>·</span>
                    <span>{{ get_the_author() ?: 'Redakcja mikroloty.com' }}</span><span>·</span>
                    <span>{{ $czas }} min czytania</span>
                </div>
            </div>
        </section>

        {{-- Zdjęcie wiodące --}}
        @if (has_post_thumbnail())
            <div class="bg-navy">
                <div class="container-site" style="max-width:1040px;">
                    {!! get_the_post_thumbnail(get_the_ID(), 'full', ['class' => 'w-full object-cover block', 'style' => 'aspect-ratio:16/8;transform:translateY(clamp(-28px,-4vw,-40px));']) !!}
                </div>
            </div>
        @endif

        {{-- Treść --}}
        <section class="bg-white" style="padding:clamp(20px,3vw,32px) 0 clamp(52px,7vw,80px);">
            <div class="container-site" style="max-width:720px;">
                @if (has_excerpt())
                    <p class="text-navy font-medium m-0 mb-7" style="font-size:19px;line-height:1.6;">{{ get_the_excerpt() }}</p>
                @endif
                <div class="prose prose-lg max-w-none prose-headings:font-heading prose-headings:text-navy prose-a:text-navy prose-img:shadow-sm">
                    @php(the_content())
                </div>

                {{-- Wróć / udostępnij --}}
                <div class="flex flex-wrap gap-4 items-center justify-between mt-11 pt-[26px] border-t border-line">
                    <a href="{{ $archUrl }}" class="uppercase font-bold text-navy" style="font-size:13px;letter-spacing:0.04em;">← Wróć do aktualności</a>
                    <div class="flex gap-2.5 items-center">
                        <span class="text-ink-5" style="font-size:13px;">Udostępnij:</span>
                        @php
                            $url = urlencode(get_permalink());
                            $tytulShare = urlencode(get_the_title());
                        @endphp
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $url }}" target="_blank" rel="noopener" class="flex items-center justify-center border border-line-2 text-navy font-bold hover:border-navy" style="width:36px;height:36px;font-size:12px;">FB</a>
                        <a href="https://twitter.com/intent/tweet?url={{ $url }}&text={{ $tytulShare }}" target="_blank" rel="noopener" class="flex items-center justify-center border border-line-2 text-navy font-bold hover:border-navy" style="width:36px;height:36px;font-size:12px;">X</a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $url }}" target="_blank" rel="noopener" class="flex items-center justify-center border border-line-2 text-navy font-bold hover:border-navy" style="width:36px;height:36px;font-size:12px;">in</a>
                    </div>
                </div>
            </div>
        </section>

        {{-- Powiązane --}}
        @php
            $relatedQ = new WP_Query([
                'post_type' => 'post',
                'posts_per_page' => 3,
                'post__not_in' => [get_the_ID()],
                'category__in' => $kat ? [$kat[0]->term_id] : [],
                'ignore_sticky_posts' => true,
            ]);
        @endphp
        @if ($relatedQ->have_posts())
            <section class="bg-surface" style="padding-block:clamp(48px,6vw,72px);">
                <div class="container-site">
                    <div class="border-b-2 border-navy mb-8" style="padding-bottom:18px;">
                        <h2 class="font-heading font-extrabold text-navy m-0" style="font-size:clamp(22px,3vw,30px);">Powiązane aktualności</h2>
                    </div>
                    <div class="grid gap-[26px]" style="grid-template-columns:repeat(auto-fill,minmax(300px,1fr));">
                        @while ($relatedQ->have_posts()) @php($relatedQ->the_post())
                            <x-aktualnosc-card />
                        @endwhile
                        @php(wp_reset_postdata())
                    </div>
                </div>
            </section>
        @endif
    @endwhile
@endsection
