<?php
/**
 * 404 template
 *
 * @package giaimatuky
 */

get_header();
?>

<div class="container">
    <div class="content-area">
        <div class="main-content">
            <article class="single-post">
                <header class="entry-header">
                    <h1 class="entry-title"><?php esc_html_e('404 - Không tìm thấy trang', 'giaimatuky'); ?></h1>
                </header>

                <div class="entry-content">
                    <p><?php esc_html_e('Xin lỗi, trang bạn đang tìm kiếm không tồn tại.', 'giaimatuky'); ?></p>
                    <p>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn">
                            <?php esc_html_e('Về trang chủ', 'giaimatuky'); ?>
                        </a>
                    </p>
                </div>
            </article>
        </div>
    </div>
</div>

<?php
get_footer();

