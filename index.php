<?php
/**
 * ========================================
 * ポートフォリオ / Portfolio Homepage
 * ========================================
 * Retro 同人サイト aesthetic × VT Branded
 */
require_once __DIR__ . '/includes/config.php';

// Get visitor counter
$visitor_count = getAndIncrementCounter();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php
$page_title = 'Ryu Gray — CS @ Virginia Tech';
$page_description = 'Computer Science student at Virginia Tech graduating Spring 2026. Cybersecurity, AI/ML, and web development. Bilingual Japanese/English.';
$page_lang = 'en';
$page_path = '/';
require __DIR__ . '/includes/head.php';
?>
    <link rel="stylesheet" href="css/portfolio.css">
</head>
<body>

<!-- Skip link (accessibility) -->
<a href="#main-content" class="skip-link">Skip to main content</a>

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
        <p class="site-subtitle">Computer Science · Virginia Tech · ポートフォリオ</p>
        <p class="header-info">
            B.S. Spring 2026
            <span class="info-divider">|</span>
            M.Eng. Fall 2026 – Fall 2027
            <span class="info-divider">|</span>
            Bilingual: EN / JP
        </p>
    </header>

    <!-- Main Layout -->
    <div class="main-layout">

        <!-- Left Sidebar -->
        <aside class="sidebar sidebar-left">
            <nav class="nav-menu" role="navigation" aria-label="Main navigation">
                <div class="menu-title">- MENU -</div>
                <ul>
                    <li class="current"><a href="/"><span class="nav-icon">◈</span> Portfolio</a></li>
                    <li><a href="about.php"><span class="nav-icon">◈</span> About Me</a></li>
                    <li><a href="hobby/"><span class="nav-icon">◈</span> Hobby Site</a></li>
                    <li><a href="hobby/gallery.php"><span class="nav-icon">◈</span> Gallery</a></li>
                    <li><a href="hobby/guestbook.php"><span class="nav-icon">◈</span> Guestbook</a></li>
                </ul>
            </nav>

            <div class="counter-box">
                <div class="counter-title">☆ VISITORS ☆</div>
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

            <!-- Contact quick links -->
            <div class="mini-links">
                <div class="links-title">- CONTACT -</div>

            <!-- Language switch -->
            <div class="mini-links">
                <div class="links-title">- LANGUAGE -</div>
                <ul>
                    <li><a href="/">English ←</a></li>
                    <li><a href="/ja/">日本語</a></li>
                </ul>
            </div>
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
                    <li><a href="/">English ←</a></li>
                    <li><a href="/ja/">日本語</a></li>
                </ul>
            </div>
        </aside>

        <!-- Main Content -->
        <main id="main-content" class="main-content" role="main">

            <!-- Intro -->
            <section class="content-section" aria-label="Introduction">
                <h2 class="section-title">
                    <span class="title-line"></span>
                    Welcome
                    <span class="title-line"></span>
                </h2>
                <div class="intro-box">
                    <p>
                        <span class="intro-name">Ryu Gray</span>
                        <span class="badge">OPEN TO OPPORTUNITIES</span>
                    </p>
                    <p class="intro-detail">
                        Senior Computer Science student at Virginia Tech, graduating Spring 2026.<br>
                        Continuing into the M.Eng. Computer Science program (Fall 2026 – Fall 2027).<br>
                        Focused on <strong>cybersecurity</strong>, <strong>AI/ML</strong>, and <strong>web development</strong>.<br>
                        Bilingual professional — fluent in English and Japanese (日本語).
                    </p>
                    <p class="intro-detail">
                        Previously shadowed at <strong>Cisco ARC</strong> in Tokyo, observing cloud security
                        auditing processes and iSMAP compliance. I used to be President of
                        the <strong>Nikkei Student Union</strong> at Virginia Tech.
                    </p>
                </div>
            </section>

            <!-- Projects -->
            <section class="content-section" aria-label="Featured projects">
                <h2 class="section-title">
                    <span class="title-line"></span>
                    Projects / 作品
                    <span class="title-line"></span>
                </h2>

                <article class="project-card">
                    <h3>Personal Web & Video Server</h3>
                    <p>Engineered a multi-client HTTP 1.1 server supporting persistent connections
                       over TCP with IPv4/IPv6 compatibility. Built a stateless authentication API
                       using JSON Web Tokens (JWT) signed with HMAC to secure private file access.
                       Optimized performance to meet scalability benchmarks for high client loads.</p>
                    <ul class="tech-tags" aria-label="Technologies used">
                        <li>C</li>
                        <li>TCP/IP</li>
                        <li>HTTP 1.1</li>
                        <li>JWT/HMAC</li>
                        <li>IPv4/IPv6</li>
                    </ul>
                </article>

                <article class="project-card">
                    <h3>Retro Japanese Personal Site ← You're here!</h3>
                    <p>Full-stack web application inspired by 2000s 同人サイト (doujin site) culture.
                       Features a visitor counter with kiriban milestone system, guestbook/BBS,
                       photo gallery with category filtering, and an admin panel — all built from
                       scratch with PHP and MySQL on shared hosting. Includes security headers,
                       robots.txt, security.txt, Open Graph meta tags, and JSON-LD structured data.</p>
                    <ul class="tech-tags" aria-label="Technologies used">
                        <li>PHP</li>
                        <li>MySQL</li>
                        <li>Apache</li>
                        <li>HTML/CSS</li>
                        <li>REST API</li>
                    </ul>
                </article>

                <article class="project-card">
                    <h3>iOS Photo Gallery App</h3>
                    <p>Native iOS application built with SwiftUI featuring photo browsing,
                       categorization, and a clean modern interface. Implemented using
                       Apple's latest frameworks and design patterns including MVVM architecture.</p>
                    <ul class="tech-tags" aria-label="Technologies used">
                        <li>Swift</li>
                        <li>SwiftUI</li>
                        <li>iOS</li>
                        <li>MVVM</li>
                        <li>Xcode</li>
                    </ul>
                </article>
            </section>

            <!-- Skills -->
            <section class="content-section" aria-label="Technical skills">
                <h2 class="section-title">
                    <span class="title-line"></span>
                    Skills / スキル
                    <span class="title-line"></span>
                </h2>
                <table class="skills-table">
                    <tr>
                        <th>Languages</th>
                        <td>C, Python, Java, JavaScript, Go, Swift, PHP, HTML/CSS</td>
                    </tr>
                    <tr>
                        <th>Systems</th>
                        <td>RISC-V, TCP/IP, HTTP, Linux, Apache, MySQL</td>
                    </tr>
                    <tr>
                        <th>Tools</th>
                        <td>Git, Xcode, VS Code, cPanel, SSH, Vim</td>
                    </tr>
                    <tr>
                        <th>Interests</th>
                        <td>Cybersecurity, AI/ML, Cloud Security, Reverse Engineering</td>
                    </tr>
                    <tr>
                        <th>Languages</th>
                        <td>English (fluent), Japanese (fluent / 日本語ネイティブ)</td>
                    </tr>
                </table>
            </section>

            <!-- Education -->
            <section class="content-section" aria-label="Education">
                <h2 class="section-title">
                    <span class="title-line"></span>
                    Education / 学歴
                    <span class="title-line"></span>
                </h2>
                <div class="intro-box">
                    <p><strong>B.S. Computer Science</strong> — Virginia Tech (Expected May 2026)</p>
                    <p class="intro-detail">Virginia Tech Scholarship recipient. Coursework: Data Structures &amp; Algorithms,
                       Computer Networks, Operating Systems, Reverse Engineering, Machine Learning.</p>
                    <p><strong>M.Eng. Computer Science</strong> — Virginia Tech (Fall 2026 – Fall 2027)</p>
                    <p class="intro-detail">Accelerated UG/G program.</p>
                    <p><strong>A.S. Computer Science, Magna Cum Laude</strong> — NOVA (Dec 2024) — GPA: 3.66</p>
                </div>
            </section>

            <!-- Resume -->
            <section class="content-section" style="text-align: center;" aria-label="Resume download">
                <a href="files/resume.pdf" class="btn-retro btn-vt" target="_blank">📄 Download Resume (PDF)</a>
            </section>

        </main>

        <!-- Right Sidebar -->
        <aside class="sidebar sidebar-right">
            <div class="profile-mini">
                <div class="profile-title">- PROFILE -</div>
                <div class="profile-icon">
                    <img src="images/avatar.gif" alt="Ryu Gray"
                         onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect fill=%22%23861F41%22 width=%22100%22 height=%22100%22/><text x=%2250%22 y=%2260%22 text-anchor=%22middle%22 font-size=%2240%22 fill=%22white%22>R</text></svg>'">
                </div>
                <div class="profile-name">Ryu Gray</div>
                <div class="profile-bio">CS @ Virginia Tech<br>Class of 2026</div>
            </div>

            <div class="mini-links">
                <div class="links-title">- LINKS -</div>
                <ul>
                    <li><a href="https://github.com/HarukoCinder" target="_blank" rel="noopener">GitHub</a></li>
                    <li><a href="https://www.linkedin.com/in/ryugray" target="_blank" rel="noopener">LinkedIn</a></li>
                    <li><a href="mailto:ryug@vt.edu">Email</a></li>
                    <li><a href="files/resume.pdf" target="_blank">Resume (PDF)</a></li>
                </ul>
            </div>

            <!-- Hobby site teaser -->
            <div class="hobby-box">
                <div>🎮 Hobby Site</div>
                <a href="hobby/">Enter →</a>
                <div style="font-size:10px;color:#999;margin-top:4px;">
                    Gallery · Guestbook · More
                </div>
            </div>

            <div style="text-align:center;margin-top:var(--spacing-lg);">
                <a href="#" class="btn-retro">▲ TOP</a>
            </div>
        </aside>

    </div>

    <!-- Footer -->
    <footer class="site-footer" role="contentinfo">
        <div class="footer-deco">✦ ═══════════════════════════════ ✦</div>
        <p>&copy; <?= date('Y') ?> Ryu Gray · Virginia Tech · Ut Prosim</p>
        <p style="font-size:10px;">
            <a href="hobby/">Hobby Site</a>
            <span class="info-divider">|</span>
            <a href="about.php">About</a>
            <span class="info-divider">|</span>
            <a href="https://github.com/HarukoCinder" target="_blank" rel="noopener">GitHub</a>
        </p>
        <div class="footer-deco">✦ ═══════════════════════════════ ✦</div>
    </footer>

</div>

</body>
</html>
