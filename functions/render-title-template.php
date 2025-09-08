<?php
// helpers/seo.php or inline at top of snippets/seo/head.php
function renderTitleTemplate(string $tpl, $site, $page): string
{

    $map = [
        'title'       => $site?->title()->value(), // default: page title
        'site.title'  => $site?->title()->value(),
        'page.title'  => $page?->title()->value(),
        'site.url'    => $site?->url(),
        'page.url'    => $page?->url(),
    ];

    return preg_replace_callback('/\{\{\s*([a-z0-9._\s]+)\s*\}\}/i', function ($m) use ($map) {
        // normalize: lower-case and remove inner spaces, so "Site . Title" works
        $key = strtolower(preg_replace('/\s+/', '', trim($m[1])));
        return $map[$key] ?? $m[0]; // leave unknown tokens untouched
    }, $tpl);
}
