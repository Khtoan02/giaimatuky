<?php
/**
 * Main template file
 *
 * @package giaimatuky
 */

get_header();
?>

<div class="container">
    <div class="content-area <?php echo is_active_sidebar('sidebar-1') ? 'has-sidebar' : ''; ?>">
        <div class="main-content">
            <?php if (have_posts()) : ?>
                <div class="posts-grid">
                    <?php while (have_posts()) : the_post(); ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium_large', array('class' => 'post-thumbnail')); ?>
                                </a>
                            <?php endif; ?>

                            <div class="post-content">
                                <div class="post-meta">
                                    <span><?php echo esc_html(get_the_date()); ?></span>
                                    <?php if (get_the_category()) : ?>
                                        <span> / </span>
                                        <span><?php the_category(', '); ?></span>
                                    <?php endif; ?>
                                </div>

                                <h2 class="post-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>

                                <div class="post-excerpt">
                                    <?php the_excerpt(); ?>
                                </div>

                                <a href="<?php the_permalink(); ?>" class="read-more">
                                    <?php esc_html_e('Đọc tiếp', 'giaimatuky'); ?> &rarr;
                                </a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <div class="pagination">
                    <?php
                    the_posts_pagination(array(
                        'mid_size'  => 2,
                        'prev_text' => __('&laquo; Trước', 'giaimatuky'),
                        'next_text' => __('Sau &raquo;', 'giaimatuky'),
                    ));
                    ?>
                </div>
            <?php else : ?>
                <div class="no-posts">
                    <h2><?php esc_html_e('Không tìm thấy bài viết', 'giaimatuky'); ?></h2>
                    <p><?php esc_html_e('Xin lỗi, không có nội dung nào được tìm thấy.', 'giaimatuky'); ?></p>
                </div>
            <?php endif; ?>
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

