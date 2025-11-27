<?php
/**
 * Footer template
 *
 * @package giaimatuky
 */
?>

</main><!-- #site-main -->

<footer class="bg-white border-t border-slate-100 py-12">
    <div class="container mx-auto px-4 text-center space-y-6">
        <div class="flex justify-center items-center gap-2 text-slate-800 font-bold text-lg">
            <i class="fa-solid fa-heart text-sky-500"></i>
            <span><?php bloginfo('name'); ?></span>
        </div>
        <p class="text-slate-500 text-sm md:text-base max-w-2xl mx-auto leading-relaxed">
            <?php
            $footer_message = get_theme_mod(
                'gmk_footer_message',
                __('Nơi tổng hợp các thắc mắc thực tế từ phụ huynh để xây dựng nội dung hữu ích và lan tỏa sự thấu hiểu trong cộng đồng.', 'giaimatuky')
            );
            echo esc_html($footer_message);
            ?>
        </p>
        <div class="flex justify-center gap-6 text-sm font-semibold text-slate-600 uppercase tracking-wider">
            <a href="https://www.youtube.com/channel/UC8RRqVV1gZyor3hPvyxjjNw" target="_blank" rel="noreferrer" class="hover:text-sky-600">
                Youtube
            </a>
            <a href="https://www.facebook.com" target="_blank" rel="noreferrer" class="hover:text-sky-600">
                Facebook
            </a>
            <a href="https://www.tiktok.com" target="_blank" rel="noreferrer" class="hover:text-sky-600">
                Tiktok
            </a>
        </div>
        <div class="text-xs text-slate-400">
            &copy; <?php echo esc_html(date('Y')); ?> <?php bloginfo('name'); ?> · <?php esc_html_e('All rights reserved.', 'giaimatuky'); ?>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
