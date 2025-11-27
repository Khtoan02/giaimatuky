<?php
/**
 * Theme functions and definitions
 *
 * @package giaimatuky
 */

if (!defined('GIAIMATUKY_VERSION')) {
    define('GIAIMATUKY_VERSION', '1.0.0');
}

/**
 * Setup theme
 */
function giaimatuky_setup() {
    // Load text domain
    load_theme_textdomain('giaimatuky', get_template_directory() . '/languages');

    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    add_theme_support('custom-logo');
    add_theme_support('automatic-feed-links');

    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Menu chính', 'giaimatuky'),
        'footer'  => __('Menu chân trang', 'giaimatuky'),
    ));
}
add_action('after_setup_theme', 'giaimatuky_setup');

/**
 * Enqueue scripts and styles
 */
function giaimatuky_scripts() {
    // Tailwind & base styles
    wp_enqueue_script(
        'giaimatuky-tailwind-browser',
        'https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4',
        array(),
        null,
        false
    );
    wp_enqueue_style(
        'giaimatuky-tailwind',
        'https://cdn.jsdelivr.net/npm/tailwindcss@3.4.3/dist/tailwind.min.css',
        array(),
        '3.4.3'
    );
    wp_enqueue_style(
        'giaimatuky-fontawesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css',
        array(),
        '6.4.2'
    );
    wp_enqueue_style(
        'giaimatuky-style',
        get_stylesheet_uri(),
        array('giaimatuky-tailwind'),
        GIAIMATUKY_VERSION
    );

    // Scripts
    wp_enqueue_script('giaimatuky-navigation', get_template_directory_uri() . '/js/navigation.js', array(), GIAIMATUKY_VERSION, true);

    // Comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    // Notebook page specific assets
    if (is_page_template('page-notebook.php')) {
        // Google Fonts
        wp_enqueue_style(
            'notebook-fonts',
            'https://fonts.googleapis.com/css2?family=Patrick+Hand&family=Roboto:wght@300;400;700&display=swap',
            array(),
            null
        );

        // Notebook CSS
        wp_enqueue_style(
            'notebook-style',
            get_template_directory_uri() . '/css/notebook.css',
            array('notebook-fonts'),
            GIAIMATUKY_VERSION
        );

        // Notebook JS
        wp_enqueue_script(
            'notebook-script',
            get_template_directory_uri() . '/js/notebook.js',
            array(),
            GIAIMATUKY_VERSION,
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'giaimatuky_scripts');

/**
 * Register widget areas
 */
function giaimatuky_widgets_init() {
    register_sidebar(array(
        'name'          => __('Sidebar', 'giaimatuky'),
        'id'            => 'sidebar-1',
        'description'   => __('Widgets hiển thị ở sidebar', 'giaimatuky'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => __('Footer 1', 'giaimatuky'),
        'id'            => 'footer-1',
        'description'   => __('Widgets hiển thị ở cột 1 footer', 'giaimatuky'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Footer 2', 'giaimatuky'),
        'id'            => 'footer-2',
        'description'   => __('Widgets hiển thị ở cột 2 footer', 'giaimatuky'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Footer 3', 'giaimatuky'),
        'id'            => 'footer-3',
        'description'   => __('Widgets hiển thị ở cột 3 footer', 'giaimatuky'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'giaimatuky_widgets_init');

/**
 * Image sizes for hero blocks
 */
add_action('after_setup_theme', function () {
    add_image_size('giaimatuky-hero', 1140, 360, true);
    add_image_size('giaimatuky-hero-169', 1280, 720, true);
});

/**
 * Customizer options for homepage sections
 */
function giaimatuky_sanitize_csv_slugs($value) {
    $parts = array_filter(array_map('sanitize_title', array_map('trim', explode(',', $value))));
    return implode(', ', $parts);
}

add_action('customize_register', function ($wp_customize) {
    $wp_customize->add_section('giaimatuky_featured_category', array(
        'title'    => __('Trang chủ: Danh mục nổi bật', 'giaimatuky'),
        'priority' => 25,
    ));

    $categories   = get_categories(array('hide_empty' => false));
    $cat_choices  = array('' => '— Chọn danh mục —');
    foreach ($categories as $cat) {
        $cat_choices[$cat->slug] = $cat->name;
    }

    $wp_customize->add_setting('giaimatuky_section1_category_slug', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('giaimatuky_section1_category_slug', array(
        'section'     => 'giaimatuky_featured_category',
        'label'       => __('Danh mục cho Hero', 'giaimatuky'),
        'type'        => 'select',
        'choices'     => $cat_choices,
        'description' => __('Chọn danh mục hiển thị ở khối đầu tiên trên trang chủ.', 'giaimatuky'),
    ));

    $wp_customize->add_section('giaimatuky_home_magazine', array(
        'title'       => __('Trang chủ: Tuỳ chỉnh thêm', 'giaimatuky'),
        'priority'    => 26,
        'description' => __('Thiết lập các khối danh mục/hashtag nổi bật.', 'giaimatuky'),
    ));

    $wp_customize->add_setting('giaimatuky_trending_tag_slug', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('giaimatuky_trending_tag_slug', array(
        'section'     => 'giaimatuky_home_magazine',
        'label'       => __('Tag xu hướng', 'giaimatuky'),
        'type'        => 'text',
        'description' => __('Nhập slug tag để lọc phần Xu hướng. Bỏ trống để tự chọn theo số bình luận.', 'giaimatuky'),
    ));

    $wp_customize->add_setting('giaimatuky_showcase_categories', array(
        'default'           => '',
        'sanitize_callback' => 'giaimatuky_sanitize_csv_slugs',
    ));

    $wp_customize->add_control('giaimatuky_showcase_categories', array(
        'section'     => 'giaimatuky_home_magazine',
        'label'       => __('Danh sách danh mục nổi bật', 'giaimatuky'),
        'type'        => 'text',
        'description' => __('Nhập slug, cách nhau bằng dấu phẩy (ví dụ: tin-nong, cong-nghe). Bỏ trống để lấy danh mục phổ biến.', 'giaimatuky'),
    ));
});

/**
 * Custom excerpt length
 */
function giaimatuky_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'giaimatuky_excerpt_length');

/**
 * Custom excerpt more
 */
function giaimatuky_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'giaimatuky_excerpt_more');

/**
 * Add wp_body_open support for older WordPress versions
 */
if (!function_exists('wp_body_open')) {
    function wp_body_open() {
        do_action('wp_body_open');
    }
}

/**
 * AJAX: load more posts for infinite listing
 */
function giaimatuky_render_post_teaser() {
    ?>
    <article class="mb-8 pb-8 border-b border-slate-200 flex flex-col sm:flex-row gap-4">
        <?php if (has_post_thumbnail()) : ?>
            <a href="<?php the_permalink(); ?>" class="block w-full sm:w-48 flex-shrink-0">
                <?php the_post_thumbnail('medium_large', array('class' => 'w-full h-32 sm:h-32 object-cover rounded-xl')); ?>
            </a>
        <?php endif; ?>
        <div class="flex-1">
            <h3 class="text-lg font-semibold mb-2 leading-snug">
                <a href="<?php the_permalink(); ?>" class="hover:text-blue-600 transition"><?php the_title(); ?></a>
            </h3>
            <div class="text-sm text-slate-500 mb-2"><?php echo esc_html(get_the_date()); ?></div>
            <div class="line-clamp-3 text-slate-600 text-sm mb-3"><?php the_excerpt(); ?></div>
            <a href="<?php the_permalink(); ?>" class="inline-flex items-center gap-1 text-blue-600 font-medium text-sm hover:underline">
                <?php esc_html_e('Đọc tiếp', 'giaimatuky'); ?> →
            </a>
        </div>
    </article>
    <?php
}

function giaimatuky_load_more_posts() {
    $paged = isset($_POST['paged']) ? max(1, intval($_POST['paged'])) : 1;
    $query = new WP_Query(array(
        'posts_per_page' => 10,
        'paged'          => $paged,
    ));

    ob_start();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            giaimatuky_render_post_teaser();
        }
        wp_reset_postdata();
    }
    $html = ob_get_clean();

    wp_send_json_success(
        array(
            'html'        => $html,
            'found_posts' => $query->found_posts,
        )
    );
}
add_action('wp_ajax_giaimatuky_load_more_posts', 'giaimatuky_load_more_posts');
add_action('wp_ajax_nopriv_giaimatuky_load_more_posts', 'giaimatuky_load_more_posts');

/**
 * AJAX handler for MiwaNews-style template
 */
function giaimatuky_miwanews_load_more_posts() {
    $paged = isset($_POST['paged']) ? max(1, intval($_POST['paged'])) : 1;
    $query = new WP_Query(array(
        'posts_per_page' => 10,
        'paged'          => $paged,
    ));

    ob_start();
    if ($query->have_posts()) :
        while ($query->have_posts()) :
            $query->the_post();
            ?>
            <article class="mb-8 pb-8 border-b border-gray-200 flex flex-col sm:flex-row gap-4">
                <?php if (has_post_thumbnail()) : ?>
                    <a href="<?php the_permalink(); ?>" class="block w-full sm:w-48 flex-shrink-0">
                        <?php the_post_thumbnail('medium_large', array('class' => 'w-full h-32 sm:h-32 object-cover rounded')); ?>
                    </a>
                <?php endif; ?>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold mb-2">
                        <a href="<?php the_permalink(); ?>" class="hover:text-blue-600"><?php the_title(); ?></a>
                    </h3>
                    <div class="text-sm text-gray-500 mb-2"><?php echo esc_html(get_the_date()); ?></div>
                    <div class="line-clamp-3 text-gray-700 text-sm mb-2"><?php the_excerpt(); ?></div>
                    <a href="<?php the_permalink(); ?>" class="text-blue-600 hover:underline font-medium">
                        <?php esc_html_e('Đọc tiếp', 'giaimatuky'); ?> &rarr;
                    </a>
                </div>
            </article>
            <?php
        endwhile;
        wp_reset_postdata();
    endif;

    $html = ob_get_clean();
    wp_send_json_success(
        array(
            'html'        => $html,
            'found_posts' => $query->found_posts,
        )
    );
}
add_action('wp_ajax_giaimatuky_miwanews_load_more', 'giaimatuky_miwanews_load_more_posts');
add_action('wp_ajax_nopriv_giaimatuky_miwanews_load_more', 'giaimatuky_miwanews_load_more_posts');

/**
 * Expose AJAX url
 */
add_action('wp_footer', function () {
    echo '<script>window.giaimatuky_ajax_url = "' . esc_url(admin_url('admin-ajax.php')) . '";</script>';
});

