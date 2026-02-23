<?php
/**
 * ========================================
 * メインページ / Main Page
 * ========================================
 */
require_once __DIR__ . '/includes/config.php';

// Get and increment visitor counter
$visitor_count = getAndIncrementCounter();
$is_kiriban = checkKiriban($visitor_count);
$upcoming_kiriban = getUnclaimedKiriban();

// Get site settings from database (or use defaults)
try {
    $db = getDB();
    $stmt = $db->query("SELECT setting_key, setting_value FROM site_settings");
    $settings = [];
    while ($row = $stmt->fetch()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
} catch (Exception $e) {
    $settings = [];
}

$site_title = $settings['site_title'] ?? SITE_TITLE;
$last_update = $settings['last_update'] ?? date('Y.m.d');
$status = $settings['current_status'] ?? '通常営業';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<?php
$page_title = $site_title;
$page_description = h(OWNER_NAME) . 'の個人サイト - 写真ギャラリーとポートフォリオ';
$page_lang = 'ja';
$page_path = '/';
require __DIR__ . '/includes/head.php';
?>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- ★ キリ番おめでとう！ Kiriban Celebration ★ -->
    <?php if ($is_kiriban): ?>
    <div id="kiriban-modal" class="modal-overlay">
        <div class="modal-content kiriban-celebration">
            <div class="sparkle">★☆★</div>
            <h2>🎊 キリ番おめでとう！ 🎊</h2>
            <p class="kiriban-number"><?= number_format($visitor_count) ?></p>
            <p>あなたは <?= number_format($visitor_count) ?> 番目の訪問者です！</p>
            <form action="kiriban_claim.php" method="POST" class="kiriban-form">
                <input type="hidden" name="milestone" value="<?= $visitor_count ?>">
                <div class="form-group">
                    <label>お名前 (任意)</label>
                    <input type="text" name="name" maxlength="100" placeholder="ななしさん">
                </div>
                <div class="form-group">
                    <label>一言メッセージ (任意)</label>
                    <textarea name="message" maxlength="500" placeholder="キリ番ゲット！"></textarea>
                </div>
                <button type="submit" class="btn-retro">登録する</button>
                <button type="button" class="btn-retro btn-secondary" onclick="closeKiriban()">閉じる</button>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <!-- ========== MAIN CONTAINER ========== -->
    <div class="site-container">
        
        <!-- ★ HEADER ★ -->
        <header class="site-header">
            <div class="header-deco top-left">◆</div>
            <div class="header-deco top-right">◆</div>
            
            <h1 class="site-title">
                <span class="title-deco">✧</span>
                <?= h($site_title) ?>
                <span class="title-deco">✧</span>
            </h1>
            <p class="site-subtitle"><?= h(SITE_SUBTITLE) ?></p>
            
            <div class="header-info">
                <span class="info-item">管理人: <?= h(OWNER_NAME) ?></span>
                <span class="info-divider">｜</span>
                <span class="info-item">Since <?= h(ESTABLISHED_YEAR) ?></span>
                <span class="info-divider">｜</span>
                <span class="info-item status-<?= strpos($status, '休') !== false ? '休止' : 'active' ?>">
                    <?= h($status) ?>
                </span>
            </div>
        </header>

        <!-- ★ MARQUEE / お知らせ ★ -->
        <div class="marquee-container">
            <div class="marquee-content">
                <span>★ ようこそ！このサイトは工事中です ★</span>
                <span>最終更新: <?= h($last_update) ?></span>
                <span>★ 写真ギャラリー更新しました ★</span>
                <span>次のキリ番: <?= !empty($upcoming_kiriban) ? number_format($upcoming_kiriban[0]) : '???' ?></span>
            </div>
        </div>

        <!-- ========== MAIN LAYOUT ========== -->
        <div class="main-layout">
            
            <!-- ★ LEFT SIDEBAR ★ -->
            <aside class="sidebar sidebar-left">
                <nav class="nav-menu">
                    <div class="menu-title">- MENU -</div>
                    <ul>
                        <li><a href="index.php"><span class="nav-icon">◈</span> トップ / TOP</a></li>
                        <li><a href="about.php"><span class="nav-icon">◈</span> 自己紹介 / ABOUT</a></li>
                        <li><a href="gallery.php"><span class="nav-icon">◈</span> ギャラリー / GALLERY</a></li>
                        <li><a href="portfolio.php"><span class="nav-icon">◈</span> 作品集 / WORKS</a></li>
                        <li><a href="guestbook.php"><span class="nav-icon">◈</span> 掲示板 / BBS</a></li>
                        <li><a href="links.php"><span class="nav-icon">◈</span> リンク / LINKS</a></li>
                    </ul>
                </nav>

                <!-- カウンター -->
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
                    <div class="counter-label">あなたは <?= number_format($visitor_count) ?> 人目の訪問者です</div>
                </div>

                <!-- 次のキリ番 -->
                <?php if (!empty($upcoming_kiriban)): ?>
                <div class="kiriban-box">
                    <div class="kiriban-title">★ 次のキリ番 ★</div>
                    <div class="kiriban-target"><?= number_format($upcoming_kiriban[0]) ?></div>
                    <div class="kiriban-remaining">
                        あと <?= number_format($upcoming_kiriban[0] - $visitor_count) ?> 人！
                    </div>
                </div>
                <?php endif; ?>
            </aside>

            <!-- ★ MAIN CONTENT ★ -->
            <main class="main-content">
                
                <!-- Welcome Message -->
                <section class="content-section welcome-section">
                    <h2 class="section-title">
                        <span class="title-line"></span>
                        Welcome!
                        <span class="title-line"></span>
                    </h2>
                    <div class="welcome-box">
                        <div class="welcome-icon">📷</div>
                        <div class="welcome-text">
                            <p>いらっしゃいませ！</p>
                            <p>このサイトは<?= h(OWNER_NAME) ?>の個人サイトです。<br>
                            趣味で撮った写真やプログラミング関連の作品を置いています。</p>
                            <p>ゆっくりしていってね！</p>
                        </div>
                    </div>
                </section>

                <!-- 最新情報 / Updates -->
                <section class="content-section updates-section">
                    <h2 class="section-title">
                        <span class="title-line"></span>
                        更新履歴
                        <span class="title-line"></span>
                    </h2>
                    <div class="updates-list">
                        <dl>
                            <dt>2026.01.22</dt>
                            <dd>サイト開設！</dd>
                            <dt>2026.01.22</dt>
                            <dd>ギャラリーページ作成中...</dd>
                            <dt>----.--.--</dt>
                            <dd>Coming soon...</dd>
                        </dl>
                    </div>
                </section>

                <!-- ギャラリー Preview -->
                <section class="content-section gallery-preview">
                    <h2 class="section-title">
                        <span class="title-line"></span>
                        New Photos
                        <span class="title-line"></span>
                    </h2>
                    <div class="gallery-grid-small">
                        <?php
                        // Fetch latest 4 gallery images
                        try {
                            $stmt = $db->query("SELECT * FROM gallery WHERE is_visible = 1 ORDER BY uploaded_at DESC LIMIT 4");
                            $recent_photos = $stmt->fetchAll();
                        } catch (Exception $e) {
                            $recent_photos = [];
                        }
                        
                        if (!empty($recent_photos)):
                            foreach ($recent_photos as $photo):
                        ?>
                        <a href="gallery.php?id=<?= $photo['id'] ?>" class="gallery-thumb">
                            <img src="uploads/thumbnails/<?= h($photo['filename']) ?>" 
                                 alt="<?= h($photo['title'] ?? 'Photo') ?>">
                        </a>
                        <?php 
                            endforeach;
                        else:
                        ?>
                        <div class="no-photos">
                            <p>写真を準備中...</p>
                            <p class="small">Coming soon!</p>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="section-more">
                        <a href="gallery.php" class="btn-retro">もっと見る →</a>
                    </div>
                </section>

            </main>

            <!-- ★ RIGHT SIDEBAR ★ -->
            <aside class="sidebar sidebar-right">
                
                <!-- Profile Mini -->
                <div class="profile-mini">
                    <div class="profile-title">- PROFILE -</div>
                    <div class="profile-icon">
                        <img src="images/avatar.webp" alt="Avatar" onerror="this.src='images/default_avatar.png'">
                    </div>
                    <div class="profile-name"><?= h(OWNER_NAME) ?></div>
                    <div class="profile-bio">
                        VT '26 / CS / 📷
                    </div>
                    <a href="about.php" class="profile-link">詳細 →</a>
                </div>

                <!-- お気に入りリンク -->
                <div class="mini-links">
                    <div class="links-title">- LINKS -</div>
                    <ul>
                        <li><a href="#">友達のサイト</a></li>
                        <li><a href="#">素材屋さん</a></li>
                        <li><a href="https://github.com" target="_blank" rel="noopener">GitHub</a></li>
                    </ul>
                </div>

                <!-- 拍手ボタン風 -->
                <div class="clap-button">
                    <button onclick="sendClap()" class="btn-clap" title="応援する！">
                        <span class="clap-icon">👏</span>
                        <span class="clap-text">拍手</span>
                    </button>
                    <div class="clap-count">今日: <span id="clap-today">0</span></div>
                </div>

                <!-- バナー -->
                <div class="site-banner">
                    <div class="banner-title">- BANNER -</div>
                    <img src="images/banner.gif" alt="Site Banner" class="banner-img" 
                         onerror="this.style.display='none'">
                    <div class="banner-info">
                        <small>リンクフリーです<br>200×40px</small>
                    </div>
                </div>

            </aside>
        </div>

        <!-- ★ FOOTER ★ -->
        <footer class="site-footer">
            <div class="footer-deco">✦ ═══════════════════════════════════════ ✦</div>
            <div class="footer-content">
                <p>&copy; <?= date('Y') ?> <?= h(OWNER_NAME) ?> All Rights Reserved.</p>
                <p class="footer-info">
                    Since <?= h(ESTABLISHED_YEAR) ?> ｜ 
                    Last Update: <?= h($last_update) ?>
                </p>
                <p class="footer-buttons">
                    <a href="#top" class="btn-small">▲ TOP</a>
                </p>
            </div>
            <div class="footer-deco">✦ ═══════════════════════════════════════ ✦</div>
        </footer>

    </div>

    <script>
    // Kiriban modal close
    function closeKiriban() {
        document.getElementById('kiriban-modal').style.display = 'none';
    }
    
    // Simple clap button (stores in localStorage for demo)
    function sendClap() {
        let today = new Date().toDateString();
        let claps = JSON.parse(localStorage.getItem('siteClaps') || '{}');
        claps[today] = (claps[today] || 0) + 1;
        localStorage.setItem('siteClaps', JSON.stringify(claps));
        document.getElementById('clap-today').textContent = claps[today];
        
        // Visual feedback
        let btn = document.querySelector('.btn-clap');
        btn.classList.add('clapped');
        setTimeout(() => btn.classList.remove('clapped'), 300);
    }
    
    // Load today's claps on page load
    document.addEventListener('DOMContentLoaded', function() {
        let today = new Date().toDateString();
        let claps = JSON.parse(localStorage.getItem('siteClaps') || '{}');
        document.getElementById('clap-today').textContent = claps[today] || 0;
    });
    </script>
</body>
</html>
