{{--
  People archive body (kadra / sędziowie / reprezentacja).

  Expects:
    $cpt        (string)         — post type
    $taxonomy   (string)         — its season taxonomy
    $baseTitle  (string)         — title without the year
    $lead       (string)
    $crumbLabel (string)
    $emptyText  (string)
    $term       (WP_Term|null)   — a specific season (taxonomy view) or null (current)
--}}
@php
    $activeTerm = ($term ?? null) ?: mikroloty_current_season_term($taxonomy);
    $fullTitle = $baseTitle . ($activeTerm ? ' ' . $activeTerm->name : '');

    $args = ['post_type' => $cpt, 'posts_per_page' => -1, 'orderby' => 'menu_order title', 'order' => 'ASC'];
    if ($activeTerm) {
        $args['tax_query'] = [['taxonomy' => $taxonomy, 'field' => 'term_id', 'terms' => $activeTerm->term_id]];
    }
    $people = new WP_Query($args);

    $crumbs = ($term ?? null)
        ? [['label' => $crumbLabel, 'url' => get_post_type_archive_link($cpt)], ['label' => $activeTerm->name]]
        : [['label' => $crumbLabel]];
@endphp

<x-page-header :title="$fullTitle" :lead="$lead" :crumbs="$crumbs" />

@include('partials.people-list', [
    'people' => $people,
    'activeTermId' => $activeTerm?->term_id,
    'taxonomy' => $taxonomy,
    'emptyText' => $emptyText,
])
