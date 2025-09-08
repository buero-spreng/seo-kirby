<?= '<?xml version="1.0" encoding="utf-8"?>' . "\n"; ?>
<?= '<?xml-stylesheet type="text/xsl" href="' . url('sitemap.xsl') . '"?>' . "\n"; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php foreach ($pages as $p): ?>
        <url>
            <loc><?= html($p->url()) ?></loc>
            <lastmod><?= $p->modified('c') ?></lastmod>
            <priority><?= $p->isHomePage() ? '1.0' : number_format(max(0.1, 0.5 / max(1, $p->depth())), 1) ?></priority>
        </url>
    <?php endforeach ?>
</urlset>