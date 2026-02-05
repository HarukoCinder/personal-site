<?php
/**
 * ========================================
 * 自己紹介 / About Me
 * ========================================
 */
require_once __DIR__ . '/includes/config.php';

$db = getDB();

// Get visitor count
$stmt = $db->query("SELECT total_count FROM visitor_counter WHERE id = 1");
$visitor_count = (int)($stmt->fetch()['total_count'] ?? 0);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>自己紹介 - <?= h(SITE_TITLE) ?></title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .profile-section { margin-bottom: 30px; }
        .profile-card {
            display: flex;
            gap: 20px;
            background: var(--bg-sidebar);
            border: 2px groove var(--border-main);
            padding: 20px;
        }
        .profile-avatar {
            width: 120px;
            height: 120px;
            border: 3px double var(--border-main);
            flex-shrink: 0;
        }
        .profile-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .profile-details h3 { margin: 0 0 10px; font-family: var(--font-title); }
        .profile-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .profile-table th, .profile-table td { 
            padding: 8px; 
            border: 1px dashed var(--border-light); 
            text-align: left;
            font-size: 12px;
        }
        .profile-table th { 
            background: var(--accent-soft); 
            width: 100px;
            font-weight: normal;
        }
        .likes-list { display: flex; flex-wrap: wrap; gap: 5px; }
        .like-tag {
            display: inline-block;
            padding: 2px 8px;
            background: var(--bg-content);
            border: 1px solid var(--border-light);
            font-size: 11px;
        }
        @media (max-width: 600px) {
            .profile-card { flex-direction: column; align-items: center; text-align: center; }
        }
    </style>
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
        <nav class="breadcrumb" style="background:var(--accent-soft);padding:8px 16px;font-size:11px;border-bottom:1px dashed var(--border-light);">
            <a href="index.php">TOP</a> &gt; <span>自己紹介</span>
        </nav>

        <!-- Main Layout -->
        <div class="main-layout">
            
            <!-- Left Sidebar -->
            <aside class="sidebar sidebar-left">
                <nav class="nav-menu">
                    <div class="menu-title">- MENU -</div>
                    <ul>
                        <li><a href="index.php"><span class="nav-icon">◈</span> トップ</a></li>
                        <li class="current"><a href="about.php"><span class="nav-icon">◈</span> 自己紹介</a></li>
                        <li><a href="gallery.php"><span class="nav-icon">◈</span> ギャラリー</a></li>
                        <li><a href="portfolio.php"><span class="nav-icon">◈</span> 作品集</a></li>
                        <li><a href="guestbook.php"><span class="nav-icon">◈</span> 掲示板</a></li>
                        <li><a href="links.php"><span class="nav-icon">◈</span> リンク</a></li>
                    </ul>
                </nav>

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
            <main class="main-content">
                
                <h2 class="section-title">
                    <span class="title-line"></span>
                    自己紹介 / About Me
                    <span class="title-line"></span>
                </h2>

                <!-- Profile Card -->
                <section class="profile-section">
                    <div class="profile-card">
                        <div class="profile-avatar">
                            <img src="images/avatar.gif" alt="Avatar" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect fill=%22%23ddd%22 width=%22100%22 height=%22100%22/><text x=%2250%22 y=%2255%22 text-anchor=%22middle%22 font-size=%2240%22>📷</text></svg>'">
                        </div>
                        <div class="profile-details">
                            <h3><?= h(OWNER_NAME) ?></h3>
                            <p>いらっしゃいませ！<br>
                            このサイトの管理人です。</p>
                            <p>写真を撮ったりプログラミングをしたりしています。<br>
                            よろしくお願いします！</p>
                        </div>
                    </div>
                </section>

                <!-- Profile Table -->
                <section class="profile-section">
                    <table class="profile-table">
                        <tr>
                            <th>名前</th>
                            <td><?= h(OWNER_NAME) ?></td>
                        </tr>
                        <tr>
                            <th>所在地</th>
                            <td>Virginia, USA</td>
                        </tr>
                        <tr>
                            <th>職業</th>
                            <td>CS Student @ Virginia Tech</td>
                        </tr>
                        <tr>
                            <th>使用カメラ</th>
                            <td>（カメラ情報を入力）</td>
                        </tr>
                        <tr>
                            <th>趣味</th>
                            <td>
                                <div class="likes-list">
                                    <span class="like-tag">📷 写真</span>
                                    <span class="like-tag">💻 プログラミング</span>
                                    <span class="like-tag">🎮 ゲーム</span>
                                    <span class="like-tag">⌨️ キーボード</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>言語</th>
                            <td>日本語 / English / 學習中文</td>
                        </tr>
                        <tr>
                            <th>好きなもの</th>
                            <td>
                                <div class="likes-list">
                                    <span class="like-tag">Apex Legends</span>
                                    <span class="like-tag">Cloud Security</span>
                                    <span class="like-tag">Mechanical Keyboards</span>
                                </div>
                            </td>
                        </tr>
                    </table>
                </section>

                <!-- Message -->
                <section class="content-section">
                    <h3 class="section-title">
                        <span class="title-line"></span>
                        一言
                        <span class="title-line"></span>
                    </h3>
                    <div class="welcome-box">
                        <p>このサイトは趣味で作った個人サイトです。</p>
                        <p>感想やメッセージは<a href="guestbook.php">掲示板</a>にどうぞ！</p>
                    </div>
                </section>

            </main>

            <!-- Right Sidebar -->
            <aside class="sidebar sidebar-right">
                <div class="mini-links">
                    <div class="links-title">- SNS -</div>
                    <ul>
                        <li><a href="https://github.com" target="_blank">GitHub</a></li>
                        <li><a href="#">LinkedIn</a></li>
                    </ul>
                </div>
                
                <div style="text-align:center;margin-top:20px;">
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
</body>
</html>
