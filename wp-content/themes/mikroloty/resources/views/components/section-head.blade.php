@props([
    'eyebrow' => '',
    'title' => '',
    'link' => null,
    'linkLabel' => null,
])

<div class="section-head">
    <div>
        @if ($eyebrow)
            <div class="eyebrow mb-2.5">{{ $eyebrow }}</div>
        @endif
        <h2 class="section-title">{{ $title }}</h2>
    </div>
    @if ($link && $linkLabel)
        <a href="{{ $link }}" class="section-link">{{ $linkLabel }} →</a>
    @endif
</div>
