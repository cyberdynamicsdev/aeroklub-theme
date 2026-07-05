@extends('layouts.app')

@section('content')
    @while (have_posts())
        @php the_post(); @endphp        <x-page-header :title="get_the_title()" :lead="has_excerpt() ? get_the_excerpt() : null" :crumbs="[['label' => get_the_title()]]" />

        @if (get_the_content())
            <section class="bg-white" style="padding-block:clamp(48px,7vw,80px);">
                <div class="container-site" style="max-width:820px;">
                    <div class="prose prose-lg max-w-none prose-headings:font-heading prose-headings:text-navy prose-a:text-navy prose-img:shadow-sm">
                        @php the_content(); @endphp                    </div>
                </div>
            </section>
        @endif
    @endwhile
@endsection
