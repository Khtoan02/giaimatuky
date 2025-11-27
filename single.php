<?php
/**
 * Single post template
 *
 * @package giaimatuky
 */

get_header();
?>

<div class="gmk-shell max-w-[1200px] mx-auto space-y-12">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white rounded-3xl shadow-2xl border border-slate-100 px-4 sm:px-6 md:px-10 lg:px-14 py-8 md:py-12'); ?>>
            <div class="flex flex-col items-center text-center space-y-4">
                <div class="flex flex-wrap gap-2 justify-center text-xs uppercase tracking-wide text-slate-500">
                    <?php
                    $categories = get_the_category();
                    if ($categories) {
                        foreach ($categories as $category) {
                            echo '<span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600">' . esc_html($category->name) . '</span>';
                        }
                    }
                    ?>
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold leading-tight text-slate-900">
                    <?php the_title(); ?>
                </h1>
                <div class="text-sm text-slate-500">
                    <?php echo esc_html(get_the_date('l, d/m/Y H:i')); ?> · <?php the_author(); ?>
                </div>
                <?php if (has_excerpt()) : ?>
                    <p class="max-w-3xl text-base md:text-lg text-slate-600"><?php echo esc_html(get_the_excerpt()); ?></p>
                <?php endif; ?>
            </div>

            <?php if (has_post_thumbnail()) : ?>
                <figure class="my-8">
                    <div class="rounded-3xl overflow-hidden shadow-2xl">
                        <?php the_post_thumbnail('large', array('class' => 'w-full max-h-[520px] object-cover')); ?>
                    </div>
                    <?php
                    $thumb_id = get_post_thumbnail_id();
                    if ($thumb_id) {
                        $caption = get_post($thumb_id)->post_excerpt ?? '';
                        if ($caption) :
                            ?>
                            <figcaption class="mt-3 text-sm text-slate-500 text-center"><?php echo esc_html($caption); ?></figcaption>
                        <?php endif;
                    }
                    ?>
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

            <?php if (get_the_tags()) : ?>
                <footer class="mt-8 flex flex-wrap gap-2">
                    <?php the_tags('<span class="text-xs uppercase tracking-wide text-slate-500">', '</span><span class="text-xs uppercase tracking-wide text-slate-500">', '</span>'); ?>
                </footer>
            <?php endif; ?>
        </article>

        <div class="flex flex-col md:flex-row justify-between gap-4 text-sm font-semibold text-slate-600">
            <div><?php previous_post_link('%link', '← ' . esc_html__('Bài trước', 'giaimatuky')); ?></div>
            <div class="text-right"><?php next_post_link('%link', esc_html__('Bài sau', 'giaimatuky') . ' →'); ?></div>
        </div>

        <?php if (comments_open() || get_comments_number()) : ?>
            <div class="bg-white/80 rounded-3xl shadow-xl border border-slate-100 px-6 py-8">
                <?php comments_template(); ?>
            </div>
        <?php endif; ?>
    <?php endwhile; ?>

    <section id="latest-posts" class="space-y-6 gmk-latest">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-slate-900"><?php esc_html_e('Bài viết liên quan', 'giaimatuky'); ?></h2>
            <span class="text-sm text-slate-500"><?php esc_html_e('Được đề xuất cho bạn', 'giaimatuky'); ?></span>
        </div>
        <div id="latest-listing">
            <?php
            $latest = new WP_Query(array(
                'posts_per_page' => 10,
                'paged'          => 1,
                'post__not_in'   => array(get_the_ID()),
            ));
            if ($latest->have_posts()) :
                while ($latest->have_posts()) :
                    $latest->the_post();
                    giaimatuky_render_post_teaser();
                endwhile;
                wp_reset_postdata();
            else :
                ?>
                <p class="text-slate-500"><?php esc_html_e('Chưa có bài viết nào.', 'giaimatuky'); ?></p>
            <?php endif; ?>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.getElementById('latest-posts');
    const listing = document.getElementById('latest-listing');
    if (!wrapper || !listing) return;

    let paged = 1;
    let isLoading = false;
    let ended = false;

    const sentinel = document.createElement('div');
    sentinel.id = 'gmk-single-load-more';
    sentinel.className = 'text-center text-sm text-slate-500 py-6';
    sentinel.textContent = '<?php echo esc_js(__('Đang tải...', 'giaimatuky')); ?>';
    wrapper.appendChild(sentinel);

    function loadMore() {
        if (isLoading || ended) return;
        isLoading = true;
        paged++;
        fetch(window.giaimatuky_ajax_url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=giaimatuky_load_more_posts&paged=' + paged
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success || !data.data.html.trim()) {
                sentinel.textContent = '<?php echo esc_js(__('Không còn bài viết nào.', 'giaimatuky')); ?>';
                ended = true;
            } else {
                listing.insertAdjacentHTML('beforeend', data.data.html);
                sentinel.textContent = '';
            }
            isLoading = false;
        })
        .catch(() => {
            sentinel.textContent = '<?php echo esc_js(__('Có lỗi xảy ra. Thử lại sau.', 'giaimatuky')); ?>';
            isLoading = false;
        });
    }

    if ('IntersectionObserver' in window) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    loadMore();
                }
            });
        }, { rootMargin: '120px' });
        io.observe(sentinel);
    } else {
        window.addEventListener('scroll', function () {
            if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 200) {
                loadMore();
            }
        });
    }
});
</script>

<?php
get_footer();
