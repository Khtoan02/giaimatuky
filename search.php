<?php
/**
 * Search results template
 *
 * @package giaimatuky
 */

get_header();
?>

<div class="gmk-shell max-w-[1100px] mx-auto space-y-10">
    <header class="text-center space-y-3">
        <p class="text-xs uppercase tracking-[0.3em] text-slate-400"><?php esc_html_e('Kết quả tìm kiếm', 'giaimatuky'); ?></p>
        <h1 class="text-3xl font-bold text-slate-900">
            <?php
            printf(
                esc_html__('Từ khóa: "%s"', 'giaimatuky'),
                esc_html(get_search_query())
            );
            ?>
        </h1>
    </header>

    <?php if (have_posts()) : ?>
        <div class="space-y-6 gmk-latest">
            <?php
            while (have_posts()) :
                the_post();
                giaimatuky_render_post_teaser();
            endwhile;
            ?>
        </div>

        <div class="mt-8">
            <?php
            the_posts_pagination(array(
                'mid_size'  => 2,
                'prev_text' => __('&laquo; Trước', 'giaimatuky'),
                'next_text' => __('Sau &raquo;', 'giaimatuky'),
            ));
            ?>
        </div>
    <?php else : ?>
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 px-6 py-10 text-center space-y-4">
            <p class="text-lg font-semibold text-slate-700"><?php esc_html_e('Không tìm thấy kết quả phù hợp.', 'giaimatuky'); ?></p>
            <div class="text-slate-500"><?php esc_html_e('Hãy thử lại với từ khóa khác hoặc xem các bài viết mới nhất.', 'giaimatuky'); ?></div>
            <?php get_search_form(); ?>
        </div>
    <?php endif; ?>
</div>

<?php
get_footer();

