<?php
// === gray.vt.domains — Head Template ===
// Set these variables BEFORE including this file on each page.
// Defaults are for the homepage.

$page_title       = $page_title       ?? 'Ryu Gray — CS @ Virginia Tech';
$page_description = $page_description ?? 'Computer Science student at Virginia Tech graduating Spring 2026. Cybersecurity focus, bilingual Japanese/English. Projects in systems programming, web development, and security.';
$page_url         = $page_url         ?? 'https://gray.vt.domains';
$page_image       = $page_image       ?? 'https://gray.vt.domains/images/og-image.png';
$page_lang        = $page_lang        ?? 'en';
$page_path        = $page_path        ?? '/';
$page_locale      = $page_lang === 'ja' ? 'ja_JP' : 'en_US';
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($page_title) ?></title>
<meta name="description" content="<?= htmlspecialchars($page_description) ?>">
<meta name="author" content="Ryu Gray">

<!-- Open Graph (LinkedIn, Slack, Discord previews) -->
<meta property="og:title" content="<?= htmlspecialchars($page_title) ?>">
<meta property="og:description" content="<?= htmlspecialchars($page_description) ?>">
<meta property="og:image" content="<?= htmlspecialchars($page_image) ?>">
<meta property="og:url" content="<?= htmlspecialchars($page_url) ?>">
<meta property="og:type" content="website">
<meta property="og:locale" content="<?= $page_locale ?>">
<?php if ($page_lang === 'en'): ?>
<meta property="og:locale:alternate" content="ja_JP">
<?php else: ?>
<meta property="og:locale:alternate" content="en_US">
<?php endif; ?>

<!-- Twitter/X Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= htmlspecialchars($page_title) ?>">
<meta name="twitter:description" content="<?= htmlspecialchars($page_description) ?>">
<meta name="twitter:image" content="<?= htmlspecialchars($page_image) ?>">

<!-- Canonical + Language Alternates -->
<link rel="canonical" href="<?= htmlspecialchars($page_url) ?>">
<link rel="alternate" hreflang="en" href="https://gray.vt.domains<?= htmlspecialchars($page_path) ?>">
<link rel="alternate" hreflang="ja" href="https://gray.vt.domains/ja<?= htmlspecialchars($page_path) ?>">
<link rel="alternate" hreflang="x-default" href="https://gray.vt.domains<?= htmlspecialchars($page_path) ?>">

<!-- Favicon -->
<link rel="icon" href="/favicon.ico" sizes="32x32">
<link rel="apple-touch-icon" href="/images/apple-touch-icon.png">

<!-- JSON-LD Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Person",
    "name": "Ryu Gray",
    "url": "https://gray.vt.domains",
    "email": "ryug@vt.edu",
    "sameAs": [
        "https://github.com/HarukoCinder",
        "https://www.linkedin.com/in/ryugray"
    ],
    "jobTitle": "Computer Science Student",
    "knowsAbout": ["Cybersecurity", "Systems Programming", "Web Development"],
    "knowsLanguage": ["en", "ja"],
    "affiliation": {
        "@type": "CollegeOrUniversity",
        "name": "Virginia Tech",
        "url": "https://www.vt.edu"
    }
}
</script>
