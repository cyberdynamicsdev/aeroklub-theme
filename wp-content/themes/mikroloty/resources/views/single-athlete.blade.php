@extends('layouts.app')

@section('content')
    @while (have_posts())
        @php the_post(); @endphp        @php
            $class = get_field('aircraft_class');
            $squad = get_field('squad');
            $role = get_field('role');
            $club = get_field('club');
            $birthYear = get_field('birth_year');
            $license = get_field('license_no');
            $stats = get_field('stats') ?: [];
            $equipment = get_field('equipment') ?: [];
            $results = get_field('results') ?: [];
            $meta = array_filter([
                $club ? '◆ ' . $club : null,
                $birthYear ? '◆ Rocznik ' . $birthYear : null,
                $license ? '◆ Lic. ' . $license : null,
            ]);
        @endphp

        {{-- Profile header --}}
        <section class="bg-navy text-white">
            <div class="container-site" style="padding-block:clamp(32px,4vw,44px);">
                <nav class="mb-7 text-onnavy-2" style="font-size:12.5px;">
                    <a href="{{ home_url('/') }}" class="text-onnavy-2 hover:text-white">Home</a>
                    <span class="mx-2">/</span>
                    <a href="{{ get_post_type_archive_link('athlete') }}" class="text-onnavy-2 hover:text-white">Kadra</a>
                    <span class="mx-2">/</span>
                    <span class="text-white">{{ get_the_title() }}</span>
                </nav>
                <div class="grid gap-x-12 gap-y-6 items-end" style="grid-template-columns:minmax(0,260px) 1fr;">
                    <div class="relative flex items-end justify-center aspect-[4/5]" style="background:#16294a;border:1px solid rgba(255,255,255,0.14);">
                        @if (has_post_thumbnail())
                            {!! get_the_post_thumbnail(get_the_ID(), 'large', ['class' => 'absolute inset-0 w-full h-full object-cover']) !!}
                        @else
                            <svg width="96" height="96" viewBox="0 0 24 24" fill="#8393b5" class="opacity-40" aria-hidden="true"><circle cx="12" cy="9" r="4" /><path d="M4 21 C4 16.5 7.5 14 12 14 C16.5 14 20 16.5 20 21 Z" /></svg>
                        @endif
                        @if ($class)
                            <span class="absolute top-0 left-0 bg-gold text-navy uppercase font-bold" style="font-size:11px;letter-spacing:0.06em;padding:6px 12px;">{{ $class }}</span>
                        @endif
                    </div>
                    <div class="pb-2">
                        @if ($squad || $role)
                            <div class="uppercase font-bold text-gold mb-3.5" style="font-size:12.5px;letter-spacing:0.16em;">{{ trim(implode(' · ', array_filter([$squad, $role]))) }}</div>
                        @endif
                        <h1 class="font-heading font-extrabold m-0 mb-4" style="font-size:clamp(32px,5vw,54px);line-height:1.02;letter-spacing:-0.01em;">{{ get_the_title() }}</h1>
                        @if ($meta)
                            <div class="flex flex-wrap gap-x-5 gap-y-2 text-onnavy" style="font-size:14.5px;">
                                @foreach ($meta as $item)<span>{{ $item }}</span>@endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        {{-- Stats strip --}}
        @if ($stats)
            <div class="text-white" style="background:#16294a;">
                <div class="container-site grid" style="grid-template-columns:repeat(auto-fit,minmax(150px,1fr));">
                    @foreach ($stats as $s)
                        <div class="flex items-baseline gap-2.5" style="padding:22px 4px;border-right:1px solid rgba(255,255,255,0.10);">
                            <span class="font-heading font-extrabold text-gold" style="font-size:28px;">{{ $s['value'] }}</span>
                            <span style="font-size:12.5px;color:#a4b1cc;">{{ $s['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Main content --}}
        <section class="bg-white" style="padding-block:clamp(48px,7vw,80px);">
            <div class="container-site grid gap-y-14" style="grid-template-columns:1fr;">
                <div class="grid gap-x-14 gap-y-14 items-start" style="grid-template-columns:repeat(auto-fit,minmax(300px,1fr));">
                    {{-- Bio + equipment --}}
                    <div>
                        <h2 class="font-heading font-extrabold text-navy m-0 mb-4 pb-3.5 border-b-2 border-navy" style="font-size:24px;">Sylwetka</h2>
                        @if (get_the_content())
                            <div class="prose max-w-none prose-p:text-ink-2 prose-headings:font-heading prose-headings:text-navy">
                                @php the_content(); @endphp                            </div>
                        @endif

                        @if ($equipment)
                            <h3 class="font-heading font-bold text-navy uppercase mt-8 mb-3.5" style="font-size:16px;letter-spacing:0.04em;">Sprzęt zawodnika</h3>
                            <div class="border border-line">
                                @foreach ($equipment as $row)
                                    <div class="flex justify-between gap-4 border-b border-line-3" style="padding:13px 18px;font-size:14.5px;">
                                        <span class="text-ink-4">{{ $row['name'] }}</span>
                                        <span class="font-semibold text-ink">{{ $row['value'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Results --}}
                    @if ($results)
                        <div>
                            <h2 class="font-heading font-extrabold text-navy m-0 mb-4 pb-3.5 border-b-2 border-navy" style="font-size:24px;">Najważniejsze wyniki</h2>
                            <div class="flex flex-col">
                                @foreach ($results as $row)
                                    @php $place = (int) preg_replace('/\D/', '', (string) ($row['place'] ?? '')); @endphp
                                    <div class="flex gap-4 items-center border-b border-line-3" style="padding:15px 0;">
                                        <span class="font-heading font-extrabold flex items-center justify-center {{ $place >= 1 && $place <= 3 ? 'bg-gold text-navy' : 'bg-line-3 text-ink-3' }}" style="flex:0 0 44px;width:44px;height:44px;font-size:16px;">{{ $row['place'] }}</span>
                                        <div class="flex-1">
                                            <div class="font-semibold text-ink" style="font-size:15px;">{{ $row['event'] }}</div>
                                        </div>
                                        <span class="font-heading font-bold text-navy" style="font-size:15px;">{{ $row['year'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <a href="{{ get_post_type_archive_link('athlete') }}" class="uppercase font-bold text-navy justify-self-start" style="font-size:13px;letter-spacing:0.04em;">← Wróć do kadry</a>
            </div>
        </section>
    @endwhile
@endsection
