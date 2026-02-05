<?php
/**
 * ========================================
 * ギャラリー / Photo Gallery
 * ========================================
 */
require_once __DIR__ . '/includes/config.php';

$db = getDB();

// Get categories
$stmt = $db->query("SELECT * FROM gallery_categories ORDER BY sort_order ASC");
$categories = $stmt->fetchAll();

// Current filter
$current_category = $_GET['category'] ?? 'all';
$current_photo_id = isset($_GET['id']) ? (int)$_GET['id'] : null;

// Build query for photos
if ($current_category === 'all') {
    $stmt = $db->query("SELECT * FROM gallery WHERE is_visible = 1 ORDER BY uploaded_at DESC");
} else {
    $stmt = $db->prepare("SELECT * FROM gallery WHERE is_visible = 1 AND category = ? ORDER BY uploaded_at DESC");
    $stmt->execute([$current_category]);
}
$photos = $stmt->fetchAll();

// If viewing single photo
$single_photo = null;
if ($current_photo_id) {
    $stmt = $db->prepare("SELECT * FROM gallery WHERE id = ? AND is_visible = 1");
    $stmt->execute([$current_photo_id]);
    $single_photo = $stmt->fetch();
    
    // Increment view count
    if ($single_photo) {
        $db->prepare("UPDATE gallery SET view_count = view_count + 1 WHERE id = ?")->execute([$current_photo_id]);
    }
}

// Get visitor count (for display)
$stmt = $db->query("SELECT total_count FROM visitor_counter WHERE id = 1");
$visitor_count = (int)($stmt->fetch()['total_count'] ?? 0);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ギャラリー - <?= h(SITE_TITLE) ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/gallery.css">
</head>
<body>
    <div class="site-container">
        
        <!-- Header -->
        <header class="site-header">
            <h1 class="site-title">
                <span class="title-deco">✧</span>
                <?= h(SITE_TITLE) ?>
                <span class="title-deco">✧</span>
            </h1>
            <p class="site-subtitle"><?= h(SITE_SUBTITLE) ?></p>
        </header>

        <!-- Breadcrumb -->
        <nav class="breadcrumb">
            <a href="index.php">TOP</a> &gt; 
            <span>ギャラリー</span>
            <?php if ($single_photo): ?>
            &gt; <span><?= h($single_photo['title'] ?? 'Photo #' . $single_photo['id']) ?></span>
            <?php endif; ?>
        </nav>

        <!-- Main Layout -->
        <div class="main-layout">
            
            <!-- Left Sidebar -->
            <aside class="sidebar sidebar-left">
                <nav class="nav-menu">
                    <div class="menu-title">- MENU -</div>
                    <ul>
                        <li><a href="index.php"><span class="nav-icon">◈</span> トップ</a></li>
                        <li><a href="about.php"><span class="nav-icon">◈</span> 自己紹介</a></li>
                        <li class="current"><a href="gallery.php"><span class="nav-icon">◈</span> ギャラリー</a></li>
                        <li><a href="portfolio.php"><span class="nav-icon">◈</span> 作品集</a></li>
                        <li><a href="guestbook.php"><span class="nav-icon">◈</span> 掲示板</a></li>
                        <li><a href="links.php"><span class="nav-icon">◈</span> リンク</a></li>
                    </ul>
                </nav>

                <!-- Category Filter -->
                <div class="category-filter">
                    <div class="menu-title">- CATEGORY -</div>
                    <ul class="category-list">
                        <li class="<?= $current_category === 'all' ? 'active' : '' ?>">
                            <a href="gallery.php">すべて</a>
                        </li>
                        <?php foreach ($categories as $cat): ?>
                        <li class="<?= $current_category === $cat['slug'] ? 'active' : '' ?>">
                            <a href="gallery.php?category=<?= h($cat['slug']) ?>">
                                <?= h($cat['name_ja']) ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Counter -->
                <div class="counter-box">
                    <div class="counter-title">☆ COUNTER ☆</div>
                    <div class="counter-display">
                        <?php
                        $count_str = str_pad($visitor_count, 7, '0', STR_PAD_LEFT);
                        for ($i = 0; $i < strlen($count_str); $i++):
                        ?>
                        <span class="counter-digit"><?= $count_str[$i] ?></span>
                        <?php endfor; ?>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="main-content gallery-page">
                
                <?php if ($single_photo): ?>
                <!-- ========== SINGLE PHOTO VIEW ========== -->
                <section class="photo-single">
                    <div class="photo-main">
                        <img src="uploads/gallery/<?= h($single_photo['filename']) ?>" 
                             alt="<?= h($single_photo['title'] ?? 'Photo') ?>"
                             class="photo-full">
                    </div>
                    
                    <div class="photo-info">
                        <h2 class="photo-title"><?= h($single_photo['title'] ?? 'Untitled') ?></h2>
                        
                        <?php if ($single_photo['description']): ?>
                        <p class="photo-description"><?= nl2br(h($single_photo['description'])) ?></p>
                        <?php endif; ?>
                        
                        <dl class="photo-meta">
                            <?php if ($single_photo['taken_date']): ?>
                            <dt>撮影日</dt>
                            <dd><?= formatDateJa($single_photo['taken_date']) ?></dd>
                            <?php endif; ?>
                            
                            <?php if ($single_photo['camera_info']): ?>
                            <dt>カメラ</dt>
                            <dd><?= h($single_photo['camera_info']) ?></dd>
                            <?php endif; ?>
                            
                            <dt>閲覧数</dt>
                            <dd><?= number_format($single_photo['view_count']) ?></dd>
                            
                            <dt>サイズ</dt>
                            <dd><?= $single_photo['width'] ?>×<?= $single_photo['height'] ?>px</dd>
                        </dl>
                    </div>
                    
                    <div class="photo-nav">
                        <a href="gallery.php<?= $current_category !== 'all' ? '?category=' . h($current_category) : '' ?>" class="btn-retro">
                            ← 一覧に戻る
                        </a>
                    </div>
                </section>
                
                <?php else: ?>
                <!-- ========== GALLERY GRID VIEW ========== -->
                <section class="gallery-view">
                    <h2 class="section-title">
                        <span class="title-line"></span>
                        ギャラリー / Gallery
                        <span class="title-line"></span>
                    </h2>
                    
                    <?php if ($current_category !== 'all'): ?>
                    <p class="filter-info">
                        カテゴリ: <strong><?= h($current_category) ?></strong>
                        <a href="gallery.php" class="clear-filter">[クリア]</a>
                    </p>
                    <?php endif; ?>
                    
                    <?php if (empty($photos)): ?>
                    <div class="no-photos-large">
                        <div class="no-photos-icon">📷</div>
                        <p>写真がまだありません</p>
                        <p class="small">Coming soon...</p>
                    </div>
                    
                    <?php else: ?>
                    <div class="gallery-grid">
                        <?php foreach ($photos as $photo): ?>
                        <a href="gallery.php?id=<?= $photo['id'] ?><?= $current_category !== 'all' ? '&category=' . h($current_category) : '' ?>" 
                           class="gallery-item">
                            <div class="gallery-item-inner">
                                <img src="uploads/thumbnails/<?= h($photo['filename']) ?>" 
                                     alt="<?= h($photo['title'] ?? 'Photo') ?>"
                                     loading="lazy">
                                <div class="gallery-item-overlay">
                                    <span class="photo-title-mini"><?= h($photo['title'] ?? 'Untitled') ?></span>
                                </div>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="photo-count">
                        全 <?= count($photos) ?> 枚
                    </div>
                    <?php endif; ?>
                </section>
                <?php endif; ?>
                
            </main>

            <!-- Right Sidebar -->
            <aside class="sidebar sidebar-right">
                <div class="gallery-tips">
                    <div class="menu-title">- INFO -</div>
                    <p class="tips-text">
                        写真をクリックすると<br>
                        大きく表示されます
                    </p>
                </div>
                
                <div class="back-link">
                    <a href="index.php" class="btn-retro">← TOP へ</a>
                </div>
            </aside>
        </div>

        <!-- Footer -->
        <footer class="site-footer">
            <div class="footer-deco">✦ ═════════════════════════ ✦</div>
            <p>&copy; <?= date('Y') ?> <?= h(OWNER_NAME) ?></p>
            <p class="footer-buttons">
                <a href="#" class="btn-small">▲ TOP</a>
            </p>
        </footer>

    </div>

    <!-- Lightbox Script -->
    <script>
    document.querySelectorAll('.photo-full').forEach(img => {
        img.addEventListener('click', function() {
            if (this.classList.contains('expanded')) {
                this.classList.remove('expanded');
            } else {
                this.classList.add('expanded');
            }
        });
    });
    </script>
</body>
</html>
