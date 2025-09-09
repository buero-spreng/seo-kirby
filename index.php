<?php

use Kirby\Cms\App as Kirby;
use Kirby\Cms\Response;
use Kirby\Filesystem\F;

Kirby::plugin('kesabr/kb-seo-kirby', [
  // Make plugin snippets available as snippet('seo/head')
  'snippets' => [
    'seo/head' => __DIR__ . '/snippets/head.php',
    'seo/sitemap' => __DIR__ . '/snippets/sitemap.php',
  ],

  'blueprints' => [
    // use these outside the plugin
    'seo/global' => __DIR__ . '/blueprints/global.yml',
    'seo/page' => __DIR__ . '/blueprints/page.yml',

    // section blueprints
    'sections/seo-main'   => __DIR__ . '/blueprints/sections/seo-main.yml',
    'sections/seo-og'     => __DIR__ . '/blueprints/sections/seo-og.yml',
    'sections/seo-robots' => __DIR__ . '/blueprints/sections/seo-robots.yml',

    // field blueprints
    'fields/show-warnings' => __DIR__ . '/blueprints/fields/show-warnings.yml',
  ],

  'routes' => [
    [
      'pattern' => 'sitemap.xml',
      'action'  => function () {
        // Include only published pages => listed and unlisted (not draft)
        $pages = site()->pages()->index()->published();

        // fetch the pages to ignore from the config settings,
        // if nothing is set, we ignore the error page
        $ignore = kirby()->option('kesabr.seo-kirby.sitemap.ignore', ['error']);

        $pages = $pages->filter(function ($p) use ($ignore) {
          if (in_array($p->uri(), $ignore, true)) return false;

          $pageNoIndex = $p->content()->get('seoIndex')->value() === 'noindex';
          $siteNoIndex = site()->content()->get('seoIndex')->value() === 'noindex';

          // page setting wins; fall back to site default
          return $pageNoIndex ? false : !$siteNoIndex;
        });

        $content = snippet('seo/sitemap', compact('pages', 'ignore'), true);

        // return response with correct header type
        return new Response($content, 'application/xml; charset=utf-8');
      }
    ],
    [
      'pattern' => 'sitemap',
      'action'  => function () {
        return go('sitemap.xml', 301);
      }
    ],
    [
      'pattern' => 'sitemap.xsl',
      'action'  => function () {
        $path = __DIR__ . '/snippets/sitemap.xsl.php';
        return new Response(F::read($path), 'application/xslt+xml; charset=utf-8');
      }
    ],
    [
      'pattern' => 'robots.txt',
      'action'  => function () {
        $lines = [
          'User-agent: *',
          'Allow: /',
          'Disallow: /panel',
          'Sitemap: ' . url('sitemap.xml'),
        ];
        return new Response(implode("\n", $lines) . "\n", 'text/plain; charset=utf-8');
      }
    ]
  ]
]);
