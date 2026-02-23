<?php
/**
 * ========================================
 * ポートフォリオ（日本語版）
 * Portfolio Homepage — Japanese Version
 * ========================================
 */
require_once __DIR__ . '/../includes/config.php';

// Get visitor counter (shared with main site)
$visitor_count = getAndIncrementCounter();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<?php
$page_title = 'Ryu Gray — バージニア工科大学 CS';
$page_description = 'バージニア工科大学コンピュータサイエンス学部（2026年春卒業予定）。サイバーセキュリティ、AI/ML、Web開発。日英バイリンガル。';
$page_lang = 'ja';
$page_path = '/';
require __DIR__ . '/../includes/head.php';
?>
    <link rel="stylesheet" href="/css/portfolio.css">
</head>
<body>

<!-- Skip link -->
<a href="#main-content" class="skip-link">メインコンテンツへ</a>

<div class="site-container">

    <!-- Header -->
    <header class="site-header" role="banner">
        <span class="header-deco top-left">✧</span>
        <span class="header-deco top-right">✧</span>
        <h1 class="site-title">
            <span class="title-deco">★</span>
            Ryu Gray
            <span class="title-deco">★</span>
        </h1>
        <p class="site-subtitle">コンピュータサイエンス · バージニア工科大学 · ポートフォリオ</p>
        <p class="header-info">
            B.S. 2026年春卒業予定
            <span class="info-divider">|</span>
            M.Eng. 2026年秋 – 2027年秋
            <span class="info-divider">|</span>
            日英バイリンガル
        </p>
    </header>

    <!-- Main Layout -->
    <div class="main-layout">

        <!-- Left Sidebar -->
        <aside class="sidebar sidebar-left">
            <nav class="nav-menu" role="navigation" aria-label="メインナビゲーション">
                <div class="menu-title">- メニュー -</div>
                <ul>
                    <li class="current"><a href="/ja/"><span class="nav-icon">◈</span> ポートフォリオ</a></li>
                    <li><a href="/about.php"><span class="nav-icon">◈</span> 自己紹介</a></li>
                    <li><a href="/hobby/"><span class="nav-icon">◈</span> 趣味サイト</a></li>
                    <li><a href="/hobby/gallery.php"><span class="nav-icon">◈</span> ギャラリー</a></li>
                    <li><a href="/hobby/guestbook.php"><span class="nav-icon">◈</span> 掲示板</a></li>
                </ul>
            </nav>

            <div class="counter-box">
                <div class="counter-title">☆ カウンター ☆</div>
                <div class="counter-display">
                    <?php
                    $count_str = str_pad($visitor_count, 7, '0', STR_PAD_LEFT);
                    for ($i = 0; $i < strlen($count_str); $i++):
                    ?>
                    <span class="counter-digit"><?= $count_str[$i] ?></span>
                    <?php endfor; ?>
                </div>
                <div class="counter-label">since 2025</div>
            </div>

            <!-- Contact -->
            <div class="mini-links">
                <div class="links-title">- 連絡先 -</div>
                <ul>
                    <li><a href="mailto:ryug@vt.edu">ryug@vt.edu</a></li>
                    <li><a href="https://github.com/HarukoCinder" target="_blank" rel="noopener">GitHub</a></li>
                    <li><a href="https://www.linkedin.com/in/ryugray" target="_blank" rel="noopener">LinkedIn</a></li>
                </ul>
            </div>

            <!-- Language switch -->
            <div class="mini-links">
                <div class="links-title">- LANGUAGE -</div>
                <ul>
                    <li><a href="/">English</a></li>
                    <li><a href="/ja/">日本語 ←</a></li>
                </ul>
            </div>
        </aside>

        <!-- Main Content -->
        <main id="main-content" class="main-content" role="main">

            <!-- Intro -->
            <section class="content-section" aria-label="自己紹介">
                <h2 class="section-title">
                    <span class="title-line"></span>
                    ようこそ
                    <span class="title-line"></span>
                </h2>
                <div class="intro-box">
                    <p>
                        <span class="intro-name">Ryu Gray</span>
                        <span class="badge">就職活動中</span>
                    </p>
                    <p class="intro-detail">
                        バージニア工科大学コンピュータサイエンス学部4年（2026年春卒業予定）。<br>
                        修士課程（M.Eng. Computer Science）に進学予定（2026年秋 – 2027年秋）。<br>
                        <strong>サイバーセキュリティ</strong>、<strong>AI/機械学習</strong>、<strong>Web開発</strong>を専門としています。<br>
                        日本語・英語のバイリンガルです。
                    </p>
                    <p class="intro-detail">
                        2025年夏には<strong>Cisco ARC（東京・港区）</strong>にてクラウドセキュリティ監査プロセスと
                        iSMAP認証の実務を見学。現在はバージニア工科大学の<strong>日系学生会（Nikkei Student Union）</strong>の
                        会長を務めています。
                    </p>
                </div>
            </section>

            <!-- Projects -->
            <section class="content-section" aria-label="プロジェクト">
                <h2 class="section-title">
                    <span class="title-line"></span>
                    作品 / Projects
                    <span class="title-line"></span>
                </h2>

                <article class="project-card">
                    <h3>自作Webサーバー & 動画配信サーバー</h3>
                    <p>TCP上でHTTP 1.1の持続的接続をサポートするマルチクライアントサーバーをC言語で設計・実装。
                       HMAC署名によるJWT認証APIを構築し、プライベートファイルへのアクセスを保護。
                       IPv4/IPv6両対応。高負荷テストのベンチマークをクリアするパフォーマンス最適化を実施。</p>
                    <ul class="tech-tags" aria-label="使用技術">
                        <li>C</li>
                        <li>TCP/IP</li>
                        <li>HTTP 1.1</li>
                        <li>JWT/HMAC</li>
                        <li>IPv4/IPv6</li>
                    </ul>
                </article>

                <article class="project-card">
                    <h3>レトロ個人サイト ← いまここ！</h3>
                    <p>2000年代の同人サイト文化にインスパイアされたフルスタックWebアプリケーション。
                       キリ番システム付きアクセスカウンター、掲示板（BBS）、カテゴリ付き写真ギャラリー、
                       管理画面を全てPHP/MySQLでゼロから構築。セキュリティヘッダー、robots.txt、
                       security.txt、Open Graphメタタグ、JSON-LD構造化データを実装済み。</p>
                    <ul class="tech-tags" aria-label="使用技術">
                        <li>PHP</li>
                        <li>MySQL</li>
                        <li>Apache</li>
                        <li>HTML/CSS</li>
                        <li>REST API</li>
                    </ul>
                </article>

                <article class="project-card">
                    <h3>iOS写真ギャラリーアプリ</h3>
                    <p>SwiftUIで開発したネイティブiOSアプリケーション。
                       写真の閲覧・カテゴリ分類機能を備え、MVVMアーキテクチャで設計。
                       Appleの最新フレームワークとデザインパターンを活用。</p>
                    <ul class="tech-tags" aria-label="使用技術">
                        <li>Swift</li>
                        <li>SwiftUI</li>
                        <li>iOS</li>
                        <li>MVVM</li>
                        <li>Xcode</li>
                    </ul>
                </article>
            </section>

            <!-- Skills -->
            <section class="content-section" aria-label="スキル">
                <h2 class="section-title">
                    <span class="title-line"></span>
                    スキル / Skills
                    <span class="title-line"></span>
                </h2>
                <table class="skills-table">
                    <tr>
                        <th>言語</th>
                        <td>C, Python, Java, JavaScript, Go, Swift, PHP, HTML/CSS</td>
                    </tr>
                    <tr>
                        <th>システム</th>
                        <td>RISC-V, TCP/IP, HTTP, Linux, Apache, MySQL</td>
                    </tr>
                    <tr>
                        <th>ツール</th>
                        <td>Git, Xcode, VS Code, cPanel, SSH, Vim</td>
                    </tr>
                    <tr>
                        <th>関心分野</th>
                        <td>サイバーセキュリティ、AI/機械学習、クラウドセキュリティ、リバースエンジニアリング</td>
                    </tr>
                    <tr>
                        <th>自然言語</th>
                        <td>英語（流暢）、日本語（ネイティブ）</td>
                    </tr>
                </table>
            </section>

            <!-- Education -->
            <section class="content-section" aria-label="学歴">
                <h2 class="section-title">
                    <span class="title-line"></span>
                    学歴 / Education
                    <span class="title-line"></span>
                </h2>
                <div class="intro-box">
                    <p><strong>B.S. コンピュータサイエンス</strong> — バージニア工科大学（2026年5月卒業予定）</p>
                    <p class="intro-detail">バージニア工科大学奨学金受給。履修科目：データ構造とアルゴリズム、
                       コンピュータネットワーク、OS、リバースエンジニアリング、機械学習。</p>
                    <p><strong>M.Eng. コンピュータサイエンス</strong> — バージニア工科大学（2026年秋 – 2027年秋）</p>
                    <p class="intro-detail">学部・大学院一貫プログラム（Accelerated UG/G）。</p>
                    <p><strong>A.S. コンピュータサイエンス（Magna Cum Laude）</strong> — NOVA（2024年12月） — GPA: 3.66</p>
                </div>
            </section>

            <!-- Resume -->
            <section class="content-section" style="text-align: center;" aria-label="履歴書ダウンロード">
                <a href="/files/resume.pdf" class="btn-retro btn-vt" target="_blank">📄 履歴書ダウンロード (PDF)</a>
            </section>

        </main>

        <!-- Right Sidebar -->
        <aside class="sidebar sidebar-right">
            <div class="profile-mini">
                <div class="profile-title">- プロフィール -</div>
                <div class="profile-icon">
                    <img src="/images/avatar.gif" alt="Ryu Gray"
                         onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect fill=%22%23861F41%22 width=%22100%22 height=%22100%22/><text x=%2250%22 y=%2260%22 text-anchor=%22middle%22 font-size=%2240%22 fill=%22white%22>R</text></svg>'">
                </div>
                <div class="profile-name">Ryu Gray</div>
                <div class="profile-bio">CS @ Virginia Tech<br>2026年卒業予定</div>
            </div>

            <div class="mini-links">
                <div class="links-title">- リンク -</div>
                <ul>
                    <li><a href="https://github.com/HarukoCinder" target="_blank" rel="noopener">GitHub</a></li>
                    <li><a href="https://www.linkedin.com/in/ryugray" target="_blank" rel="noopener">LinkedIn</a></li>
                    <li><a href="mailto:ryug@vt.edu">メール</a></li>
                    <li><a href="/files/resume.pdf" target="_blank">履歴書 (PDF)</a></li>
                </ul>
            </div>

            <!-- Hobby site teaser -->
            <div class="hobby-box">
                <div>🎮 趣味サイト</div>
                <a href="/hobby/">入口 →</a>
                <div style="font-size:10px;color:#999;margin-top:4px;">
                    ギャラリー · 掲示板 · その他
                </div>
            </div>

            <div style="text-align:center;margin-top:var(--spacing-lg);">
                <a href="#" class="btn-retro">▲ トップへ</a>
            </div>
        </aside>

    </div>

    <!-- Footer -->
    <footer class="site-footer" role="contentinfo">
        <div class="footer-deco">✦ ═══════════════════════════════ ✦</div>
        <p>&copy; <?= date('Y') ?> Ryu Gray · バージニア工科大学 · Ut Prosim</p>
        <p style="font-size:10px;">
            <a href="/hobby/">趣味サイト</a>
            <span class="info-divider">|</span>
            <a href="/about.php">自己紹介</a>
            <span class="info-divider">|</span>
            <a href="/">English</a>
        </p>
        <div class="footer-deco">✦ ═══════════════════════════════ ✦</div>
    </footer>

</div>

</body>
</html>
