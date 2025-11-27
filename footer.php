<?php
/**
 * Footer template
 *
 * @package giaimatuky
 */
?>

</main><!-- #site-main -->

<footer class="gmk-footer">
    <div class="gmk-shell space-y-10">
        <div class="gmk-footer__grid">
            <div class="space-y-3">
                <div class="gmk-footer__brand"><?php bloginfo('name'); ?></div>
                <p class="gmk-footer__note">
                    <?php bloginfo('description'); ?>
                </p>
                <div class="flex items-center gap-3 text-xl text-white/70">
                    <a href="<?php echo esc_url(home_url('/rss')); ?>" class="hover:text-white" aria-label="RSS">
                        <i class="fa-solid fa-rss"></i>
                    </a>
                    <a href="https://facebook.com" class="hover:text-white" target="_blank" rel="noopener">
                        <i class="fa-brands fa-facebook"></i>
                    </a>
                    <a href="https://youtube.com" class="hover:text-white" target="_blank" rel="noopener">
                        <i class="fa-brands fa-youtube"></i>
                    </a>
                </div>
            </div>

            <?php if (is_active_sidebar('footer-1')) : ?>
                <div class="footer-widget text-sm text-white/80">
                    <?php dynamic_sidebar('footer-1'); ?>
                </div>
            <?php endif; ?>

            <?php if (is_active_sidebar('footer-2')) : ?>
                <div class="footer-widget text-sm text-white/80">
                    <?php dynamic_sidebar('footer-2'); ?>
                </div>
            <?php endif; ?>

            <?php if (is_active_sidebar('footer-3')) : ?>
                <div class="footer-widget text-sm text-white/80">
                    <?php dynamic_sidebar('footer-3'); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 text-sm text-white/60">
            <div>
                &copy; <?php echo esc_html(date('Y')); ?> <?php bloginfo('name'); ?> — <?php esc_html_e('Bản quyền thuộc về tác giả.', 'giaimatuky'); ?>
            </div>
            <div class="gmk-top-nav text-white/60">
                <a href="<?php echo esc_url(home_url('/chinh-sach-bao-mat')); ?>"><?php esc_html_e('Chính sách bảo mật', 'giaimatuky'); ?></a>
                <a href="<?php echo esc_url(home_url('/dieu-khoan-su-dung')); ?>"><?php esc_html_e('Điều khoản', 'giaimatuky'); ?></a>
                <a href="<?php echo esc_url(home_url('/lien-he')); ?>"><?php esc_html_e('Liên hệ', 'giaimatuky'); ?></a>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
