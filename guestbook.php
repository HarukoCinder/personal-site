<?php
/**
 * ========================================
 * 掲示板 / Guestbook (BBS)
 * ========================================
 */
require_once __DIR__ . '/includes/config.php';

$db = getDB();
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'post') {
        $name = trim($_POST['name'] ?? '');
        $website = trim($_POST['website'] ?? '');
        $message = trim($_POST['message'] ?? '');
        $icon_id = (int)($_POST['icon'] ?? 1);
        $ip_hash = hashIP($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
        
        // Validation
        if (empty($name)) {
            $name = '名無しさん';
        }
        if (strlen($name) > 100) {
            $error = 'お名前が長すぎます';
        }
        if (empty($message)) {
            $error = 'メッセージを入力してください';
        }
        if (strlen($message) > 2000) {
            $error = 'メッセージが長すぎます（2000文字以内）';
        }
        
        // Check spam cooldown
        if (!$error) {
            $stmt = $db->prepare("SELECT created_at FROM guestbook WHERE ip_hash = ? ORDER BY created_at DESC LIMIT 1");
            $stmt->execute([$ip_hash]);
            $last_post = $stmt->fetch();
            
            if ($last_post) {
                $last_time = strtotime($last_post['created_at']);
                if (time() - $last_time < SPAM_COOLDOWN) {
                    $remaining = SPAM_COOLDOWN - (time() - $last_time);
                    $error = "連続投稿はできません。あと {$remaining} 秒お待ちください";
                }
            }
        }
        
        // Insert message
        if (!$error) {
            $stmt = $db->prepare("INSERT INTO guestbook (name, website, message, icon_id, ip_hash) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $website, $message, $icon_id, $ip_hash]);
            $success = '書き込みありがとうございます！';
            
            // Clear POST to prevent resubmission
            header('Location: guestbook.php?posted=1');
            exit;
        }
    }
}

if (isset($_GET['posted'])) {
    $success = '書き込みありがとうございます！';
}

// Pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Get total count
$total = (int)$db->query("SELECT COUNT(*) FROM guestbook WHERE is_approved = 1")->fetchColumn();
$total_pages = max(1, ceil($total / $per_page));

// Get messages
$stmt = $db->prepare("SELECT * FROM guestbook WHERE is_approved = 1 ORDER BY created_at DESC LIMIT ? OFFSET ?");
$stmt->execute([$per_page, $offset]);
$messages = $stmt->fetchAll();

// Get visitor count
$stmt = $db->query("SELECT total_count FROM visitor_counter WHERE id = 1");
$visitor_count = (int)($stmt->fetch()['total_count'] ?? 0);

// Icon options for guestbook
$icons = [
    1 => '😊',
    2 => '😄',
    3 => '🎉',
    4 => '📷',
    5 => '💻',
    6 => '🎮',
    7 => '🌸',
    8 => '⭐',
    9 => '🐱',
    10 => '🐶',
];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>掲示板 - <?= h(SITE_TITLE) ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/guestbook.css">
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
            <span>掲示板</span>
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
                        <li><a href="gallery.php"><span class="nav-icon">◈</span> ギャラリー</a></li>
                        <li><a href="portfolio.php"><span class="nav-icon">◈</span> 作品集</a></li>
                        <li class="current"><a href="guestbook.php"><span class="nav-icon">◈</span> 掲示板</a></li>
                        <li><a href="links.php"><span class="nav-icon">◈</span> リンク</a></li>
                    </ul>
                </nav>

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
                
                <!-- BBS Stats -->
                <div class="bbs-stats">
                    <div class="menu-title">- STATS -</div>
                    <p>総投稿数: <?= number_format($total) ?></p>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="main-content guestbook-page">
                
                <h2 class="section-title">
                    <span class="title-line"></span>
                    掲示板 / Guestbook
                    <span class="title-line"></span>
                </h2>

                <!-- Rules -->
                <div class="bbs-rules">
                    <p>✿ お気軽にコメントどうぞ！ ✿</p>
                    <p class="small">※ 荒らし・宣伝・不適切な内容はご遠慮ください</p>
                </div>

                <!-- Error/Success Messages -->
                <?php if ($error): ?>
                <div class="message-error"><?= h($error) ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                <div class="message-success"><?= h($success) ?></div>
                <?php endif; ?>

                <!-- Post Form -->
                <div class="post-form-container">
                    <div class="form-title">✎ 新規書き込み</div>
                    <form method="POST" class="post-form">
                        <input type="hidden" name="action" value="post">
                        
                        <div class="form-row">
                            <label>お名前</label>
                            <input type="text" name="name" maxlength="100" placeholder="名無しさん">
                        </div>
                        
                        <div class="form-row">
                            <label>サイト (任意)</label>
                            <input type="url" name="website" maxlength="255" placeholder="https://">
                        </div>
                        
                        <div class="form-row">
                            <label>アイコン</label>
                            <div class="icon-select">
                                <?php foreach ($icons as $id => $emoji): ?>
                                <label class="icon-option">
                                    <input type="radio" name="icon" value="<?= $id ?>" <?= $id === 1 ? 'checked' : '' ?>>
                                    <span class="icon-emoji"><?= $emoji ?></span>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <label>メッセージ <span class="required">*</span></label>
                            <textarea name="message" rows="4" maxlength="2000" required placeholder="コメントをどうぞ..."></textarea>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn-retro">書き込む</button>
                            <button type="reset" class="btn-retro btn-secondary">クリア</button>
                        </div>
                    </form>
                </div>

                <!-- Messages List -->
                <div class="messages-container">
                    <div class="messages-header">
                        <span>コメント一覧</span>
                        <span class="message-count"><?= number_format($total) ?> 件</span>
                    </div>
                    
                    <?php if (empty($messages)): ?>
                    <div class="no-messages">
                        <p>まだコメントがありません</p>
                        <p class="small">最初の書き込みをどうぞ！</p>
                    </div>
                    
                    <?php else: ?>
                    <div class="messages-list">
                        <?php foreach ($messages as $index => $msg): ?>
                        <article class="message-item">
                            <div class="message-header">
                                <span class="message-icon"><?= $icons[$msg['icon_id']] ?? '😊' ?></span>
                                <span class="message-name">
                                    <?php if ($msg['website']): ?>
                                    <a href="<?= h($msg['website']) ?>" target="_blank" rel="noopener nofollow">
                                        <?= h($msg['name']) ?>
                                    </a>
                                    <?php else: ?>
                                    <?= h($msg['name']) ?>
                                    <?php endif; ?>
                                </span>
                                <span class="message-date"><?= formatDateShort($msg['created_at']) ?></span>
                                <span class="message-number">No.<?= $total - $offset - $index ?></span>
                            </div>
                            <div class="message-body">
                                <?= nl2br(h($msg['message'])) ?>
                            </div>
                        </article>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                    <nav class="pagination">
                        <?php if ($page > 1): ?>
                        <a href="?page=<?= $page - 1 ?>" class="page-link">&laquo; 前</a>
                        <?php endif; ?>
                        
                        <?php 
                        $start = max(1, $page - 2);
                        $end = min($total_pages, $page + 2);
                        
                        if ($start > 1): ?>
                        <a href="?page=1" class="page-link">1</a>
                        <?php if ($start > 2): ?><span class="page-dots">...</span><?php endif; ?>
                        <?php endif; ?>
                        
                        <?php for ($i = $start; $i <= $end; $i++): ?>
                        <a href="?page=<?= $i ?>" class="page-link <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                        <?php endfor; ?>
                        
                        <?php if ($end < $total_pages): ?>
                        <?php if ($end < $total_pages - 1): ?><span class="page-dots">...</span><?php endif; ?>
                        <a href="?page=<?= $total_pages ?>" class="page-link"><?= $total_pages ?></a>
                        <?php endif; ?>
                        
                        <?php if ($page < $total_pages): ?>
                        <a href="?page=<?= $page + 1 ?>" class="page-link">次 &raquo;</a>
                        <?php endif; ?>
                    </nav>
                    <?php endif; ?>
                    
                    <?php endif; ?>
                </div>

            </main>

            <!-- Right Sidebar -->
            <aside class="sidebar sidebar-right">
                <div class="bbs-info">
                    <div class="menu-title">- INFO -</div>
                    <p class="info-text">
                        感想・質問・<br>
                        雑談なんでも<br>
                        歓迎です！
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
</body>
</html>
