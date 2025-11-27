<?php
/**
 * Template Name: Notebook Page
 * 
 * Page template cho Wiki Notebook với tính năng bảo mật
 *
 * @package giaimatuky
 */

// Sử dụng header riêng cho notebook
get_header('notebook');
?>

<!-- Nút mở menu trên Mobile -->
<button class="notebook-mobile-toggle" onclick="toggleSidebar()">☰</button>

<div id="notebook-security-alert">
    <h2>🚫 MÀN HÌNH ĐƯỢC BẢO VỆ</h2>
    <p>Hệ thống đã tự động che nội dung khi phát hiện chụp màn hình.</p>
    <button class="notebook-btn-unlock" onclick="hideAlert()">Mở lại</button>
</div>

<aside class="notebook-sidebar" id="notebook-sidebar">
    <div class="notebook-brand">📒 Wiki Mobile</div>
    <ul class="notebook-menu-list">
        <li class="notebook-menu-item active" onclick="loadContent(1)">
            <span>📌</span> Tổng quan
        </li>
        <li class="notebook-menu-item" onclick="loadContent(2)">
            <span>📱</span> Giao diện Mobile
        </li>
        <li class="notebook-menu-item" onclick="loadContent(3)">
            <span>🍏</span> Bảo mật macOS
        </li>
        <li class="notebook-menu-item" onclick="loadContent(4)">
            <span>🔒</span> Admin Only
        </li>
    </ul>
    <div class="notebook-contact-box">
        Bảo mật tối đa cho<br><strong>Mobile & Desktop</strong>
    </div>
</aside>

<main class="notebook-main-container" id="notebook-main-view">
    <div class="notebook-paper">
        <div class="notebook-watermark">BẢN QUYỀN <br> (MOBILE FRIENDLY)</div>
        <div class="notebook-repeat-watermark"></div>

        <div class="notebook-content-area">
            <?php while (have_posts()) : the_post(); ?>
                <h1><?php the_title(); ?></h1>
                <?php the_content(); ?>
            <?php endwhile; ?>
        </div>
    </div>
</main>

<?php
get_footer('notebook');

