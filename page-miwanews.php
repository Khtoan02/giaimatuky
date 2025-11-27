<?php
/**
 * Template Name: MiwaNews Layout
 * Description: Trang tạp chí với hero, xu hướng và danh mục nổi bật giống MiwaNews.
 *
 * @package giaimatuky
 */

get_header();

$featured_slug  = get_theme_mod('giaimatuky_section1_category_slug', '');
$trending_tag   = get_theme_mod('giaimatuky_trending_tag_slug', '');
$showcase_input = get_theme_mod('giaimatuky_showcase_categories', '');
$showcase_slugs = array_filter(array_map('sanitize_title', array_map('trim', explode(',', $showcase_input))));

if (!function_exists('giaimatuky_collect_posts')) {
    function giaimatuky_collect_posts(WP_Query $query) {
        $items = [];
        while ($query->have_posts()) {
            $query->the_post();
            $items[] = [
                'id'         => get_the_ID(),
                'title'      => get_the_title(),
                'link'       => get_permalink(),
                'excerpt'    => wp_strip_all_tags(get_the_excerpt()),
                'thumb'      => get_the_post_thumbnail_url(get_the_ID(), 'giaimatuky-hero-169'),
                'date'       => get_the_date('d/m/Y'),
                'time'       => get_the_date('H:i'),
                'author'     => get_the_author(),
                'categories' => get_the_category(),
            ];
        }
        wp_reset_postdata();
        return $items;
    }
}

$hero_args = [
    'posts_per_page'      => 5,
    'ignore_sticky_posts' => true,
];
$featured_cat = null;
if ($featured_slug) {
    $featured_cat = get_category_by_slug($featured_slug);
    if ($featured_cat) {
        $hero_args['cat'] = $featured_cat->term_id;
    }
}
$hero_query = new WP_Query($hero_args);
$hero_posts = $hero_query->have_posts() ? giaimatuky_collect_posts($hero_query) : [];

$trending_args = [
    'posts_per_page'      => 8,
    'orderby'             => 'comment_count',
    'ignore_sticky_posts' => true,
];
if ($trending_tag) {
    $trending_args['tag'] = $trending_tag;
}
$trending_query = new WP_Query($trending_args);
$trending_posts = $trending_query->have_posts() ? giaimatuky_collect_posts($trending_query) : [];

$spotlight_posts = [];
$sticky_ids = get_option('sticky_posts');
if (!empty($sticky_ids)) {
    rsort($sticky_ids);
    $spotlight_query = new WP_Query([
        'post__in'            => $sticky_ids,
        'posts_per_page'      => 3,
        'ignore_sticky_posts' => false,
    ]);
    $spotlight_posts = $spotlight_query->have_posts() ? giaimatuky_collect_posts($spotlight_query) : [];
}

if (!empty($showcase_slugs)) {
    $showcase_categories = get_categories([
        'slug'       => $showcase_slugs,
        'hide_empty' => false,
    ]);
} else {
    $showcase_categories = get_categories([
        'orderby'    => 'count',
        'order'      => 'DESC',
        'number'     => 3,
        'hide_empty' => false,
    ]);
}
?>

<main class="bg-gray-50">
  <div class="mx-auto max-w-6xl px-4 py-10 space-y-16">
    <section class="space-y-6">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs uppercase tracking-[0.3em] text-gray-400"><?php esc_html_e("Editor's pick", 'giaimatuky'); ?></p>
          <h2 class="text-3xl font-bold text-gray-800"><?php esc_html_e('Tiêu điểm hôm nay', 'giaimatuky'); ?></h2>
        </div>
        <?php if (!empty($featured_cat)) : ?>
          <a href="<?php echo esc_url(get_category_link($featured_cat)); ?>" class="text-sm font-semibold text-blue-600 hover:text-blue-800">
            <?php esc_html_e('Xem tất cả', 'giaimatuky'); ?> &rarr;
          </a>
        <?php endif; ?>
      </div>

      <?php if (!empty($hero_posts)) : ?>
        <div class="grid gap-6 lg:grid-cols-3">
          <?php $main = $hero_posts[0]; ?>
          <article class="relative overflow-hidden rounded-3xl bg-gray-900 text-white lg:col-span-2">
            <?php if (!empty($main['thumb'])) : ?>
              <img src="<?php echo esc_url($main['thumb']); ?>" alt="<?php echo esc_attr($main['title']); ?>" class="absolute inset-0 h-full w-full object-cover" loading="lazy">
              <span class="absolute inset-0 bg-gradient-to-tr from-black/90 via-black/60 to-black/10"></span>
            <?php endif; ?>
            <div class="relative z-10 flex h-full flex-col justify-end space-y-4 p-8">
              <div class="flex gap-3 text-xs uppercase tracking-widest text-white/80">
                <?php foreach ($main['categories'] as $cat_item) : ?>
                  <a href="<?php echo esc_url(get_category_link($cat_item->term_id)); ?>" class="rounded-full border border-white/40 px-3 py-1 hover:bg-white/10">
                    <?php echo esc_html($cat_item->name); ?>
                  </a>
                <?php endforeach; ?>
              </div>
              <h3 class="gmk-hero-card__title text-3xl font-bold leading-tight text-white">
                <a href="<?php echo esc_url($main['link']); ?>" class="gmk-hero-card__title-link"><?php echo esc_html($main['title']); ?></a>
              </h3>
              <p class="line-clamp-3 text-base text-white/90"><?php echo esc_html($main['excerpt']); ?></p>
              <div class="flex items-center justify-between text-sm text-white/80">
                <span><?php echo esc_html($main['author']); ?> • <?php echo esc_html($main['time']); ?> • <?php echo esc_html($main['date']); ?></span>
                <a href="<?php echo esc_url($main['link']); ?>" class="rounded-full bg-white/20 px-4 py-2 font-semibold hover:bg-white/40"><?php esc_html_e('Đọc ngay', 'giaimatuky'); ?></a>
              </div>
            </div>
          </article>
          <div class="grid gap-4">
            <?php foreach (array_slice($hero_posts, 1, 3) as $item) : ?>
              <article class="flex gap-4 rounded-2xl bg-white p-4 shadow-sm">
                <?php if (!empty($item['thumb'])) : ?>
                  <a href="<?php echo esc_url($item['link']); ?>" class="relative block h-24 w-32 flex-shrink-0 overflow-hidden rounded-xl">
                    <img src="<?php echo esc_url($item['thumb']); ?>" alt="<?php echo esc_attr($item['title']); ?>" class="h-full w-full object-cover" loading="lazy">
                  </a>
                <?php endif; ?>
                <div class="flex flex-1 flex-col">
                  <div class="text-[11px] uppercase tracking-[0.2em] text-gray-400"><?php echo esc_html($item['date']); ?></div>
                  <h4 class="mt-1 line-clamp-2 text-base font-semibold text-gray-900">
                    <a href="<?php echo esc_url($item['link']); ?>" class="hover:text-blue-600"><?php echo esc_html($item['title']); ?></a>
                  </h4>
                  <p class="mt-1 line-clamp-2 text-sm text-gray-500"><?php echo esc_html($item['excerpt']); ?></p>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        </div>
      <?php else : ?>
        <p class="text-gray-500"><?php esc_html_e('Chưa có bài viết để hiển thị hero.', 'giaimatuky'); ?></p>
      <?php endif; ?>
    </section>

    <section class="space-y-4">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs uppercase tracking-[0.3em] text-gray-400">trending now</p>
          <h2 class="text-2xl font-bold text-gray-800"><?php esc_html_e('Xu hướng đọc nhiều', 'giaimatuky'); ?></h2>
        </div>
        <div class="flex gap-2">
          <button type="button" class="rounded-full border border-gray-300 p-2 text-gray-700 hover:bg-gray-100" data-trending-prev aria-label="Prev">
            <i class="fa-solid fa-chevron-left"></i>
          </button>
          <button type="button" class="rounded-full border border-gray-300 p-2 text-gray-700 hover:bg-gray-100" data-trending-next aria-label="Next">
            <i class="fa-solid fa-chevron-right"></i>
          </button>
        </div>
      </div>
      <?php if (!empty($trending_posts)) : ?>
        <div class="relative">
          <div class="flex gap-4 overflow-x-auto scroll-smooth pb-4" data-trending-carousel>
            <?php foreach ($trending_posts as $item) : ?>
              <article class="w-72 flex-shrink-0 rounded-2xl bg-white p-4 shadow hover:-translate-y-1 hover:shadow-lg transition">
                <div class="flex items-center gap-2 text-xs text-amber-600">
                  <i class="fa-solid fa-fire"></i>
                  <span><?php echo esc_html($item['date']); ?></span>
                </div>
                <h3 class="mt-2 line-clamp-2 text-lg font-semibold text-gray-900">
                  <a href="<?php echo esc_url($item['link']); ?>" class="hover:text-blue-600"><?php echo esc_html($item['title']); ?></a>
                </h3>
                <p class="mt-2 line-clamp-3 text-sm text-gray-500"><?php echo esc_html($item['excerpt']); ?></p>
                <div class="mt-4 flex items-center justify-between text-xs text-gray-400">
                  <span><?php echo esc_html($item['author']); ?></span>
                  <span><?php echo esc_html($item['time']); ?></span>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        </div>
      <?php else : ?>
        <p class="text-sm text-gray-500"><?php esc_html_e('Chưa có dữ liệu xu hướng.', 'giaimatuky'); ?></p>
      <?php endif; ?>
    </section>

    <?php if (!empty($spotlight_posts)) : ?>
      <section class="rounded-3xl bg-gray-900 p-8 text-white">
        <div class="flex items-center justify-between">
          <h2 class="text-2xl font-bold">Spotlight</h2>
          <span class="text-xs uppercase tracking-[0.4em] text-white/60">sticky picks</span>
        </div>
        <div class="mt-8 grid gap-6 md:grid-cols-3">
          <?php foreach ($spotlight_posts as $item) : ?>
            <article class="space-y-3 rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur">
              <div class="text-[11px] uppercase tracking-[0.25em] text-white/60">Sticky</div>
              <h3 class="text-xl font-semibold leading-tight">
                <a href="<?php echo esc_url($item['link']); ?>" class="hover:text-amber-300"><?php echo esc_html($item['title']); ?></a>
              </h3>
              <p class="line-clamp-3 text-sm text-white/80"><?php echo esc_html($item['excerpt']); ?></p>
              <div class="text-xs text-white/60"><?php echo esc_html($item['author']); ?> • <?php echo esc_html($item['date']); ?></div>
            </article>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endif; ?>

    <section class="space-y-8">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs uppercase tracking-[0.3em] text-gray-400">curated topics</p>
          <h2 class="text-2xl font-bold text-gray-800"><?php esc_html_e('Danh mục nổi bật', 'giaimatuky'); ?></h2>
        </div>
      </div>
      <?php if (!empty($showcase_categories)) : ?>
        <div class="grid gap-10 lg:grid-cols-3">
          <?php foreach ($showcase_categories as $cat_item) :
            $cat_query = new WP_Query([
              'cat'                 => $cat_item->term_id,
              'posts_per_page'      => 3,
              'ignore_sticky_posts' => true,
            ]);
            $cat_posts = $cat_query->have_posts() ? giaimatuky_collect_posts($cat_query) : [];
          ?>
            <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm hover:shadow-lg transition">
              <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-gray-800"><?php echo esc_html($cat_item->name); ?></h3>
                <a href="<?php echo esc_url(get_category_link($cat_item->term_id)); ?>" class="text-sm font-medium text-blue-600 hover:text-blue-800"><?php esc_html_e('Xem tất cả', 'giaimatuky'); ?></a>
              </div>
              <p class="mt-1 text-sm text-gray-500"><?php echo esc_html(wp_strip_all_tags($cat_item->description)); ?></p>
              <div class="mt-4 space-y-4">
                <?php if (!empty($cat_posts)) : ?>
                  <?php foreach ($cat_posts as $item) : ?>
                    <article class="flex gap-3 border-b border-gray-100 pb-3 last:border-0">
                      <?php if (!empty($item['thumb'])) : ?>
                        <a href="<?php echo esc_url($item['link']); ?>" class="block h-16 w-24 flex-shrink-0 overflow-hidden rounded-xl">
                          <img src="<?php echo esc_url($item['thumb']); ?>" alt="<?php echo esc_attr($item['title']); ?>" class="h-full w-full object-cover" loading="lazy">
                        </a>
                      <?php endif; ?>
                      <div class="flex-1">
                        <div class="text-[11px] uppercase tracking-[0.2em] text-gray-400"><?php echo esc_html($item['date']); ?></div>
                        <h4 class="line-clamp-2 text-base font-semibold text-gray-900">
                          <a href="<?php echo esc_url($item['link']); ?>" class="hover:text-blue-600"><?php echo esc_html($item['title']); ?></a>
                        </h4>
                        <p class="line-clamp-2 text-sm text-gray-500"><?php echo esc_html($item['excerpt']); ?></p>
                      </div>
                    </article>
                  <?php endforeach; ?>
                <?php else : ?>
                  <p class="text-sm text-gray-500"><?php esc_html_e('Chưa có bài viết.', 'giaimatuky'); ?></p>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else : ?>
        <p class="text-sm text-gray-500"><?php esc_html_e('Không tìm thấy danh mục nào.', 'giaimatuky'); ?></p>
      <?php endif; ?>
    </section>

    <section class="rounded-3xl bg-white p-10 shadow-lg">
      <div class="grid gap-8 md:grid-cols-2">
        <div>
          <p class="text-xs uppercase tracking-[0.3em] text-gray-400">newsletter</p>
          <h2 class="mt-2 text-3xl font-bold text-gray-900"><?php esc_html_e('Nhận tin nóng mỗi sáng', 'giaimatuky'); ?></h2>
          <p class="mt-3 text-gray-600"><?php esc_html_e('Bản tin chọn lọc gồm tin tiêu điểm, phân tích chuyên sâu và các bài viết hay.', 'giaimatuky'); ?></p>
          <ul class="mt-4 space-y-2 text-sm text-gray-500">
            <li><i class="fa-solid fa-check text-emerald-500 mr-2"></i><?php esc_html_e('Không spam, hủy đăng ký bất cứ lúc nào.', 'giaimatuky'); ?></li>
            <li><i class="fa-solid fa-check text-emerald-500 mr-2"></i><?php esc_html_e('Tùy chọn chủ đề phù hợp sở thích.', 'giaimatuky'); ?></li>
            <li><i class="fa-solid fa-check text-emerald-500 mr-2"></i><?php esc_html_e('Ưu tiên nội dung độc quyền.', 'giaimatuky'); ?></li>
          </ul>
        </div>
        <div class="rounded-2xl border border-gray-100 bg-gray-50 p-6">
          <form class="space-y-4" action="#" method="post">
            <div>
              <label for="giaimatuky-newsletter-name" class="text-sm font-semibold text-gray-700"><?php esc_html_e('Họ tên', 'giaimatuky'); ?></label>
              <input type="text" id="giaimatuky-newsletter-name" class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:outline-none" placeholder="<?php esc_attr_e('Nguyễn Văn A', 'giaimatuky'); ?>">
            </div>
            <div>
              <label for="giaimatuky-newsletter-email" class="text-sm font-semibold text-gray-700"><?php esc_html_e('Email', 'giaimatuky'); ?></label>
              <input type="email" id="giaimatuky-newsletter-email" class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:outline-none" placeholder="ban@email.com">
            </div>
            <div>
              <label for="giaimatuky-newsletter-topics" class="text-sm font-semibold text-gray-700"><?php esc_html_e('Chủ đề quan tâm', 'giaimatuky'); ?></label>
              <select id="giaimatuky-newsletter-topics" multiple class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:outline-none">
                <?php foreach (get_categories(['hide_empty' => true]) as $topic_cat) : ?>
                  <option value="<?php echo esc_attr($topic_cat->slug); ?>"><?php echo esc_html($topic_cat->name); ?></option>
                <?php endforeach; ?>
              </select>
              <p class="mt-1 text-xs text-gray-400"><?php esc_html_e('Giữ Ctrl/Command để chọn nhiều mục.', 'giaimatuky'); ?></p>
            </div>
            <button type="submit" class="w-full rounded-2xl bg-gray-900 py-3 text-center text-white font-semibold hover:bg-gray-700"><?php esc_html_e('Đăng ký ngay', 'giaimatuky'); ?></button>
          </form>
        </div>
      </div>
    </section>

    <section id="latest-posts" class="space-y-6">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs uppercase tracking-[0.3em] text-gray-400">latest stories</p>
          <h2 class="text-2xl font-bold text-gray-900"><?php esc_html_e('Bài viết mới nhất', 'giaimatuky'); ?></h2>
        </div>
        <span class="text-sm text-gray-500"><?php esc_html_e('Cập nhật liên tục', 'giaimatuky'); ?></span>
      </div>
      <div id="latest-listing" class="space-y-6">
        <?php
        $latest = new WP_Query([
          'posts_per_page' => 10,
          'paged'          => 1,
        ]);
        if ($latest->have_posts()) :
          while ($latest->have_posts()) :
            $latest->the_post();
            ?>
            <article class="mb-8 pb-8 border-b border-gray-200 flex flex-col sm:flex-row gap-4">
              <?php if (has_post_thumbnail()) : ?>
                <a href="<?php the_permalink(); ?>" class="block w-full sm:w-48 flex-shrink-0">
                  <?php the_post_thumbnail('medium_large', ['class' => 'w-full h-32 sm:h-32 object-cover rounded']); ?>
                </a>
              <?php endif; ?>
              <div class="flex-1">
                <h3 class="text-lg font-semibold mb-2"><a href="<?php the_permalink(); ?>" class="hover:text-blue-600"><?php the_title(); ?></a></h3>
                <div class="text-sm text-gray-500 mb-2"><?php echo esc_html(get_the_date()); ?></div>
                <div class="line-clamp-3 text-gray-700 text-sm mb-2"><?php the_excerpt(); ?></div>
                <a href="<?php the_permalink(); ?>" class="text-blue-600 hover:underline font-medium"><?php esc_html_e('Đọc tiếp', 'giaimatuky'); ?> &rarr;</a>
              </div>
            </article>
          <?php endwhile; wp_reset_postdata(); ?>
        <?php else : ?>
          <p class="text-sm text-gray-500"><?php esc_html_e('Chưa có bài viết nào.', 'giaimatuky'); ?></p>
        <?php endif; ?>
      </div>
    </section>
  </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const carousel = document.querySelector('[data-trending-carousel]');
  const prevBtn = document.querySelector('[data-trending-prev]');
  const nextBtn = document.querySelector('[data-trending-next]');
  if (carousel && prevBtn && nextBtn) {
    const scrollAmount = 320;
    prevBtn.addEventListener('click', () => carousel.scrollBy({ left: -scrollAmount, behavior: 'smooth' }));
    nextBtn.addEventListener('click', () => carousel.scrollBy({ left: scrollAmount, behavior: 'smooth' }));
  }

  const wrapper = document.getElementById('latest-posts');
  const listing = document.getElementById('latest-listing');
  if (!wrapper || !listing) return;
  let isLoading = false;
  let paged = 1;
  let ended = false;

  const sentinel = document.createElement('div');
  sentinel.id = 'load-more-sentinel';
  sentinel.className = 'text-center text-sm text-gray-500 py-4';
  sentinel.textContent = '<?php echo esc_js(__('Đang tải...', 'giaimatuky')); ?>';
  wrapper.appendChild(sentinel);

  function loadMore() {
    if (isLoading || ended) return;
    isLoading = true;
    paged++;
    sentinel.textContent = '<?php echo esc_js(__('Đang tải...', 'giaimatuky')); ?>';
    fetch(window.giaimatuky_ajax_url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'action=giaimatuky_miwanews_load_more&paged=' + paged
    })
    .then(res => res.json())
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
      sentinel.textContent = '<?php echo esc_js(__('Tải lỗi. Thử lại.', 'giaimatuky')); ?>';
      isLoading = false;
    });
  }

  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver(entries => {
      entries.forEach(entry => entry.isIntersecting && loadMore());
    }, { rootMargin: '120px' });
    io.observe(sentinel);
  } else {
    window.addEventListener('scroll', () => {
      if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 200) {
        loadMore();
      }
    });
  }
});
</script>

<?php
get_footer();

