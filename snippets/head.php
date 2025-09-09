<?php

/**
 * SEO head snippet
 * Usage: <?= snippet('seo/head') ?>
 */

use Kirby\Toolkit\Str;

if (!function_exists('renderTitleTemplate')) {
    require_once __DIR__ . '/../functions/render-title-template.php';
}

// TITLE
if ($page->seoTitle()->isNotEmpty()) {
    $titleString = $page->seoTitle();
    $seoTitle = renderTitleTemplate($titleString, $site, $page);
} elseif ($site->seoTitle()->isNotEmpty()) {
    // Allow "{{ title }}" placeholder at site level
    $titleString = $site->seoTitle();
    $seoTitle = renderTitleTemplate($titleString, $site, $page);
} else {
    $seoTitle = $page->title();
}

// DESCRIPTION
if ($page->seoDescription()->isNotEmpty()) {
    $seoDesc = $page->seoDescription();
} elseif ($site->seoDescription()->isNotEmpty()) {
    $seoDesc = $site->seoDescription();
} else {
    // Fallback: short excerpt from first text-ish field
    $seoDesc = null;
    foreach ($page->content()->data() as $v) {
        if (is_string($v) && trim($v) !== '') {
            $seoDesc = Str::excerpt(strip_tags($v), 160);
            break;
        }
    }
}

// KEYWORDS
if ($page->seoKeywords()->isNotEmpty()) {
    $seoKeywords = $page->seoKeywords();
} elseif ($site->seoKeywords()->isNotEmpty()) {
    $seoKeywords = $site->seoKeywords();
} else {
    $seoKeywords = null;
}

// ROBOTS (compose from toggles; page overrides site)
$pick = function ($key, $fallback) use ($page, $site) {
    $p = $page->content()->get($key)->value();
    if ($p !== null && $p !== '') return $p;
    $s = $site->content()->get($key)->value();
    return $s !== null && $s !== '' ? $s : $fallback;
};

$robotsParts = [
    $pick('seoIndex', 'index'),
    $pick('seoLinks', 'follow'),
    $pick('seoArchive', 'archive'),
    $pick('seoImageIndex', 'imageindex') === 'noimageindex' ? 'noimageindex' : null,
    $pick('seoSnippets', 'snippets') === 'nosnippets' ? 'nosnippet' : null,
];
$robots = implode(', ', array_filter($robotsParts));

// CANONICAL
$canonical = $page->url();

// OG/Twitter
$ogTitle = $page->ogTitle()->or($site->ogTitle())->or($seoTitle);
$ogDesc  = $page->ogDescription()->or($site->ogDescription())->or($seoDesc);
$ogFile  = $page->ogImage()->toFile() ?? $site->ogImage()->toFile();
$ogImg   = $ogFile ? $ogFile->url() : null;

// HREFLANG (if multilang)
$hreflangs = [];
if (kirby()->multilang()) {
    foreach ($site->languages() as $lang) {
        $hreflangs[$lang->code()] = $page->url($lang->code());
    }
}
?>


<title><?= html($seoTitle) ?></title>
<?php if ($seoDesc): ?>
    <meta name="description" content="<?= html($seoDesc) ?>">
<?php endif ?>
<?php if ($seoKeywords): ?>
    <meta name="keywords" content="<?= $seoKeywords ?>">
<?php endif ?>

<link rel="canonical" href="<?= $canonical ?>">

<?php if ($robots): ?>
    <meta name="robots" content="<?= $robots ?>">
<?php endif ?>

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:title" content="<?= html($ogTitle) ?>">
<?php if ($ogDesc): ?>
    <meta property="og:description" content="<?= html($ogDesc) ?>">
<?php endif ?>
<meta property="og:url" content="<?= $canonical ?>">
<?php if ($ogImg): ?>
    <meta property="og:image" content="<?= $ogImg ?>">
<?php endif ?>

<!-- Twitter -->
<meta name="twitter:card" content="<?= $ogImg ? 'summary_large_image' : 'summary' ?>">
<meta name="twitter:title" content="<?= html($ogTitle) ?>">
<?php if ($ogDesc): ?>
    <meta name="twitter:description" content="<?= html($ogDesc) ?>">
<?php endif ?>
<?php if ($ogImg): ?>
    <meta name="twitter:image" content="<?= $ogImg ?>">
<?php endif ?>

<?php if (!empty($hreflangs)): ?>
    <?php foreach ($hreflangs as $code => $url): ?>
        <link rel="alternate" href="<?= $url ?>" hreflang="<?= $code ?>">
    <?php endforeach ?>
<?php endif ?>