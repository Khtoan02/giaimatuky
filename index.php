<?php
/**
 * Main template file
 *
 * @package giaimatuky
 */

get_header();
?>

<div class="gmk-shell max-w-[1200px] mx-auto space-y-14">
    <?php
    $cat_slug = get_theme_mod('giaimatuky_section1_category_slug', '');
    if ($cat_slug) {
        $cat = get_category_by_slug($cat_slug);
        if ($cat) {
            $hero_query = new WP_Query(array(
                'cat'            => $cat->term_id,
                'posts_per_page' => 7,
            ));
            if ($hero_query->have_posts()) :
                $hero_posts = array();
                while ($hero_query->have_posts()) :
                    $hero_query->the_post();
                    $hero_posts[] = array(
                        'ID'        => get_the_ID(),
                        'link'      => get_permalink(),
                        'title'     => get_the_title(),
                        'excerpt'   => get_the_excerpt(),
                        'thumbnail' => get_the_post_thumbnail_url(get_the_ID(), 'giaimatuky-hero-169'),
                        'date'      => get_the_date('H:i d/m/Y'),
                    );
                endwhile;
                ?>
                <section class="space-y-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-3xl font-semibold text-slate-900">
                            <?php echo esc_html($cat->name); ?>
                        </h2>
                        <a href="<?php echo esc_url(get_category_link($cat)); ?>" class="inline-flex items-center text-sm font-semibold text-blue-600">
                            <?php esc_html_e('Xem tất cả', 'giaimatuky'); ?> →
                        </a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="gmk-hero-card">
                            <?php if (!empty($hero_posts[0])) :
                                $primary = $hero_posts[0]; ?>
                                <?php if (!empty($primary['thumbnail'])) : ?>
                                    <img src="<?php echo esc_url($primary['thumbnail']); ?>" alt="<?php echo esc_attr($primary['title']); ?>" loading="lazy">
                                <?php endif; ?>
                                <div class="gmk-hero-card__content">
                                    <span class="gmk-badge bg-white/10 text-white"><?php echo esc_html($primary['date']); ?></span>
                                    <h3 class="gmk-hero-card__title"><?php echo esc_html($primary['title']); ?></h3>
                                    <p class="line-clamp-3 text-white/80 text-base"><?php echo esc_html($primary['excerpt']); ?></p>
                                    <div>
                                        <a href="<?php echo esc_url($primary['link']); ?>" class="gmk-pill bg-white/20 backdrop-blur hover:bg-white/30 transition">
                                            <?php esc_html_e('Đọc ngay', 'giaimatuky'); ?>
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex flex-col gap-4">
                            <?php for ($i = 1; $i <= 2; $i++) :
                                if (!empty($hero_posts[$i])) :
                                    $item = $hero_posts[$i]; ?>
                                    <article class="gmk-card flex-row overflow-hidden">
                                        <a href="<?php echo esc_url($item['link']); ?>" class="gmk-card__media w-48 md:w-56 flex-shrink-0">
                                            <?php if (!empty($item['thumbnail'])) : ?>
                                                <img src="<?php echo esc_url($item['thumbnail']); ?>" alt="<?php echo esc_attr($item['title']); ?>" loading="lazy">
                                            <?php endif; ?>
                                            <span class="gmk-badge absolute top-3 right-3 bg-black/70 text-white text-xs">
                                                <?php echo esc_html($item['date']); ?>
                                            </span>
                                        </a>
                                        <div class="gmk-card__body flex-1">
                                            <h4 class="text-lg font-semibold leading-tight">
                                                <a href="<?php echo esc_url($item['link']); ?>" class="hover:text-blue-600">
                                                    <?php echo esc_html($item['title']); ?>
                                                </a>
                                            </h4>
                                            <p class="text-sm text-slate-500 line-clamp-3"><?php echo esc_html($item['excerpt']); ?></p>
                                            <a href="<?php echo esc_url($item['link']); ?>" class="text-sm font-semibold text-blue-600">
                                                <?php esc_html_e('Chi tiết', 'giaimatuky'); ?>
                                            </a>
                                        </div>
                                    </article>
                                <?php endif;
                            endfor; ?>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <?php for ($i = 3; $i < 7; $i++) :
                            if (!empty($hero_posts[$i])) :
                                $item = $hero_posts[$i]; ?>
                                <article class="gmk-card">
                                    <div class="gmk-card__media">
                                        <?php if (!empty($item['thumbnail'])) : ?>
                                            <img src="<?php echo esc_url($item['thumbnail']); ?>" alt="<?php echo esc_attr($item['title']); ?>" loading="lazy">
                                        <?php endif; ?>
                                        <span class="gmk-badge absolute top-3 right-3 bg-black/70 text-white text-xs">
                                            <?php echo esc_html($item['date']); ?>
                                        </span>
                                    </div>
                                    <div class="gmk-card__body">
                                        <h4 class="gmk-card__title line-clamp-2"><?php echo esc_html($item['title']); ?></h4>
                                        <p class="gmk-card__excerpt line-clamp-3 text-sm"><?php echo esc_html($item['excerpt']); ?></p>
                                        <a href="<?php echo esc_url($item['link']); ?>" class="text-sm font-semibold text-blue-600">
                                            <?php esc_html_e('Đọc tiếp', 'giaimatuky'); ?>
                                        </a>
                                    </div>
                                </article>
                            <?php endif;
                        endfor; ?>
                    </div>
                </section>
                <?php
                wp_reset_postdata();
            endif;
        }
    }
    ?>

    <section id="latest-posts" class="space-y-6 gmk-latest">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-semibold text-slate-900"><?php esc_html_e('Bài viết mới nhất', 'giaimatuky'); ?></h2>
            <span class="text-sm text-slate-500"><?php esc_html_e('Cập nhật liên tục', 'giaimatuky'); ?></span>
        </div>
        <div id="latest-listing">
            <?php
            $latest = new WP_Query(array(
                'posts_per_page' => 10,
                'paged'          => 1,
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
    sentinel.id = 'gmk-load-more';
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
