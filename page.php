<?php
/**
 * Page template
 *
 * @package giaimatuky
 */

get_header();
?>

<div class="gmk-shell max-w-[1000px] mx-auto space-y-10">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white rounded-3xl shadow-xl border border-slate-100 px-4 sm:px-8 md:px-12 py-10'); ?>>
            <header class="mb-6 text-center space-y-3">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400"><?php esc_html_e('Chuyên mục', 'giaimatuky'); ?></p>
                <h1 class="text-3xl font-bold text-slate-900"><?php the_title(); ?></h1>
            </header>

            <?php if (has_post_thumbnail()) : ?>
                <figure class="mb-8">
                    <div class="rounded-2xl overflow-hidden shadow-2xl">
                        <?php the_post_thumbnail('large', array('class' => 'w-full object-cover')); ?>
                    </div>
                </figure>
            <?php endif; ?>

            <div class="markdown-content prose max-w-none">
                <?php
                the_content();
                wp_link_pages(array(
                    'before' => '<div class="page-links font-semibold mt-8">' . esc_html__('Trang:', 'giaimatuky'),
                    'after'  => '</div>',
                ));
                ?>
            </div>
        </article>

        <?php if (comments_open() || get_comments_number()) : ?>
            <div class="bg-white/80 rounded-3xl shadow-xl border border-slate-100 px-6 py-8">
                <?php comments_template(); ?>
            </div>
        <?php endif; ?>
    <?php endwhile; ?>
</div>

<?php
get_footer();

