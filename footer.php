<?php
/**
 * Footer template
 *
 * @package giaimatuky
 */
?>

</main><!-- .site-main -->

<footer class="site-footer">
    <div class="container">
        <div class="footer-inner">
            <?php if (is_active_sidebar('footer-1')) : ?>
                <div class="footer-widget">
                    <?php dynamic_sidebar('footer-1'); ?>
                </div>
            <?php endif; ?>

            <?php if (is_active_sidebar('footer-2')) : ?>
                <div class="footer-widget">
                    <?php dynamic_sidebar('footer-2'); ?>
                </div>
            <?php endif; ?>

            <?php if (is_active_sidebar('footer-3')) : ?>
                <div class="footer-widget">
                    <?php dynamic_sidebar('footer-3'); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="site-info">
            <p>
                &copy; <?php echo esc_html(date('Y')); ?> 
                <a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>. 
                <?php esc_html_e('Tất cả quyền được bảo lưu.', 'giaimatuky'); ?>
            </p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>

