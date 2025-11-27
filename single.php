<?php
/**
 * Single post template
 *
 * @package giaimatuky
 */

get_header();
?>

<div class="container">
    <div class="content-area <?php echo is_active_sidebar('sidebar-1') ? 'has-sidebar' : ''; ?>">
        <div class="main-content">
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
                    <header class="entry-header">
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                        <div class="entry-meta">
                            <span><?php echo esc_html(get_the_date()); ?></span>
                            <?php if (get_the_category()) : ?>
                                <span> / </span>
                                <span><?php the_category(', '); ?></span>
                            <?php endif; ?>
                            <?php if (get_the_author()) : ?>
                                <span> / </span>
                                <span><?php esc_html_e('Bởi', 'giaimatuky'); ?> <?php the_author(); ?></span>
                            <?php endif; ?>
                        </div>
                    </header>

                    <?php if (has_post_thumbnail()) : ?>
                        <div class="post-thumbnail">
                            <?php the_post_thumbnail('large'); ?>
                        </div>
                    <?php endif; ?>

                    <div class="entry-content">
                        <?php
                        the_content();

                        wp_link_pages(array(
                            'before' => '<div class="page-links">' . esc_html__('Trang:', 'giaimatuky'),
                            'after'  => '</div>',
                        ));
                        ?>
                    </div>

                    <?php if (get_the_tags()) : ?>
                        <footer class="entry-footer">
                            <div class="post-tags">
                                <strong><?php esc_html_e('Thẻ:', 'giaimatuky'); ?></strong>
                                <?php the_tags('', ', ', ''); ?>
                            </div>
                        </footer>
                    <?php endif; ?>
                </article>

                <?php
                // Post navigation
                the_post_navigation(array(
                    'prev_text' => '<span class="nav-subtitle">' . esc_html__('Bài trước:', 'giaimatuky') . '</span> <span class="nav-title">%title</span>',
                    'next_text' => '<span class="nav-subtitle">' . esc_html__('Bài sau:', 'giaimatuky') . '</span> <span class="nav-title">%title</span>',
                ));

                // Comments
                if (comments_open() || get_comments_number()) {
                    comments_template();
                }
                ?>
            <?php endwhile; ?>
        </div>

        <?php if (is_active_sidebar('sidebar-1')) : ?>
            <aside class="sidebar">
                <?php dynamic_sidebar('sidebar-1'); ?>
            </aside>
        <?php endif; ?>
    </div>
</div>

<?php
get_footer();

