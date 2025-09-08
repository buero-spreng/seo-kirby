# Seo Kirby

Lightweight SEO utilities for **Kirby 5** — no Panel JS or build step.  
Adds reusable SEO tabs for Site & Pages, a `<head>` snippet with smart fallbacks, a sitemap at `/sitemap.xml`, and an automatic `robots.txt`.

## Installation

Clone into your project’s plugins folder:

```bash
git clone https://github.com/kesabr/kb-seo-kirby.git site/plugins/kb-seo-kirby
```

(or copy the folder manually to `site/plugins/kb-seo-kirby`)

> Requires Kirby 5 (PHP 8.2+). After installation, hard‑reload the Panel; if tabs don’t show, clear `site/cache/*` and `media/*`.

## Add the SEO tabs

### Site (global defaults)
```yml
# site/blueprints/site.yml
title: Site
tabs:
  content:
    sections:
      pages: { type: pages }
  seo: seo/global   # ← from the plugin
```

### Pages
```yml
# site/blueprints/pages/default.yml (example)
title: Page
tabs:
  content:
    sections:
      fields:
        type: fields
        fields:
          text: { type: textarea }
  seo: seo/page     # ← from the plugin
```

## Output meta tags

Add this once in your HTML `<head>` (e.g., `site/templates/default.php`):

```php
<?= snippet('seo/head') ?>
```

This outputs `<title>`, meta description, robots, canonical, Open Graph, Twitter Card and (in multilang) `hreflang` links. Page values override Site defaults; if empty, the snippet falls back to sensible defaults.

## Title template placeholders

In the **Site** SEO tab, editors can set a title template, e.g.:

```
{{ page.title }} – {{ site.title }}
```

Supported (case/space‑insensitive):
- `{{ title }}` (page title by default)
- `{{ page.title }}`
- `{{ site.title }}`

Unknown tokens are left as‑is.

## Sitemap & robots.txt

- **Sitemap**: available at **`/sitemap.xml`**. Includes published pages and respects `seoIndex: noindex`.  
  Optional ignore list in `site/config/config.php`:
  ```php
  return [
    'kesabr.seo-kirby.sitemap.ignore' => ['error', 'sitemap'],
  ];
  ```

- **robots.txt**: available at **`/robots.txt`**. Allows all, disallows `/panel`, and links to the sitemap.

> For staging, prefer HTTP auth or set `X-Robots-Tag: noindex, nofollow` via config headers.

## License

MIT © 2025 kesabr
