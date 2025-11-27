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
 * Handle câu hỏi từ trang form-cau-hoi.php
 */
function giaimatuky_handle_question_submission() {

    $topic   = isset($_POST['topic']) ? sanitize_text_field(wp_unslash($_POST['topic'])) : '';
    $message = isset($_POST['message']) ? trim(wp_unslash($_POST['message'])) : '';

    if ('' === $topic || '' === $message) {
        giaimatuky_redirect_question_form('missing_fields', $topic);
    }

    if (mb_strlen($message) < 20) {
        giaimatuky_redirect_question_form('short_message', $topic);
    }

    $topic_labels = array(
        'giao-tiep-ngon-ngu' => 'Giao tiếp & Ngôn ngữ',
        'hanh-vi-cam-xuc'    => 'Hành vi & Xử lý cảm giác',
        'tieu-hoa-sinh-hoat' => 'Tiêu hoá & Sinh hoạt',
        'nhan-thuc'          => 'Nhận thức & Tư duy',
        'can-thiep-giao-duc' => 'Can thiệp & Giáo dục',
        'tuong-lai'          => 'Tương lai & Khả năng sống độc lập',
        'nguyen-nhan'        => 'Nguyên nhân & Chẩn đoán',
        'ho-tro-gia-dinh'    => 'Hỗ trợ gia đình',
        'khac'               => 'Vấn đề khác',
    );

    $topic_label = isset($topic_labels[ $topic ]) ? $topic_labels[ $topic ] : $topic;
    $clean_message = sanitize_textarea_field($message);

    $admin_email = get_option('admin_email');
    $subject = sprintf('[Giải Mã Tự Kỷ] Câu hỏi mới (%s)', $topic_label);
    $body = "Chủ đề: {$topic_label}\n----------------------\n{$clean_message}\n\nNguồn trang: " . (wp_get_referer() ? esc_url_raw(wp_get_referer()) : home_url('/'));

    $mail_sent = wp_mail(
        $admin_email,
        $subject,
        $body,
        array('Content-Type: text/plain; charset=UTF-8')
    );

    if (!$mail_sent) {
        giaimatuky_redirect_question_form('send_failed', $topic);
    }

    giaimatuky_redirect_question_form('');
}
add_action('admin_post_nopriv_gmk_submit_question', 'giaimatuky_handle_question_submission');
add_action('admin_post_gmk_submit_question', 'giaimatuky_handle_question_submission');

/**
 * Redirect helper cho form câu hỏi
 *
 * @param string $error_code .
 * @param string $topic .
 */
function giaimatuky_redirect_question_form($error_code = '', $topic = '') {
    $redirect_url = wp_get_referer() ? wp_get_referer() : home_url('/');
    $args = array();
    if ($error_code) {
        $args['form_status'] = 'error';
        $args['form_error']  = $error_code;
        if ($topic) {
            $args['topic'] = $topic;
        }
    } else {
        $args['form_status'] = 'success';
    }
    $redirect_url = add_query_arg($args, $redirect_url);
    wp_safe_redirect($redirect_url);
    exit;
}

/**
 * Lấy danh sách video mới nhất từ YouTube RSS feed.
 *
 * @param array $args Tham số bổ sung.
 *
 * @return array
 */
function giaimatuky_get_youtube_videos($args = array()) {
    $defaults = array(
        'max_results' => 8,
        'cache_ttl'   => HOUR_IN_SECONDS,
        'channel_id'  => '',
    );
    $args = wp_parse_args($args, $defaults);

    $channel_id = $args['channel_id'] ? $args['channel_id'] : trim(get_theme_mod('gmk_youtube_channel_id', ''));
    if ('' === $channel_id) {
        return array();
    }

    $max_results = max(1, min(15, absint($args['max_results'])));
    $cache_key   = 'gmk_yt_rss_' . md5($channel_id . '|' . $max_results);
    $cached      = get_transient($cache_key);
    if (false !== $cached) {
        return $cached;
    }

    $feed_url = add_query_arg(
        array(
            'channel_id' => $channel_id,
        ),
        'https://www.youtube.com/feeds/videos.xml'
    );

    $response = wp_remote_get($feed_url, array('timeout' => 12));
    if (is_wp_error($response)) {
        return array();
    }

    $body = wp_remote_retrieve_body($response);
    if (empty($body)) {
        return array();
    }

    $xml = simplexml_load_string($body);
    if (false === $xml || empty($xml->entry)) {
        return array();
    }

    $videos   = array();
    $count    = 0;
    foreach ($xml->entry as $entry) {
        if ($count >= $max_results) {
            break;
        }

        $namespaces = $entry->getNamespaces(true);
        $yt         = isset($namespaces['yt']) ? $entry->children($namespaces['yt']) : null;
        $media      = isset($namespaces['media']) ? $entry->children($namespaces['media']) : null;

        $video_id = '';
        if ($yt && isset($yt->videoId)) {
            $video_id = (string) $yt->videoId;
        }
        if ('' === $video_id && isset($entry->id)) {
            $parts = explode(':', (string) $entry->id);
            $video_id = end($parts);
        }

        $title = isset($entry->title) ? (string) $entry->title : '';
        $description = '';
        $thumbnail   = '';
        if ($media && isset($media->group)) {
            $group = $media->group;
            if (isset($group->description)) {
                $description = (string) $group->description;
            }
            if (isset($group->thumbnail)) {
                $thumb_attrs = $group->thumbnail->attributes();
                if ($thumb_attrs && isset($thumb_attrs['url'])) {
                    $thumbnail = (string) $thumb_attrs['url'];
                }
            }
        }

        if ('' === $description && isset($entry->content)) {
            $description = (string) $entry->content;
        }

        if ('' === $thumbnail && '' !== $video_id) {
            $thumbnail = sprintf('https://img.youtube.com/vi/%s/hqdefault.jpg', $video_id);
        }

        if ('' === $video_id || '' === $title) {
            continue;
        }

        $videos[] = array(
            'id'        => $video_id,
            'title'     => $title,
            'desc'      => $description,
            'thumbnail' => $thumbnail,
            'url'       => sprintf('https://www.youtube.com/watch?v=%s', $video_id),
        );
        $count++;
    }

    set_transient($cache_key, $videos, $args['cache_ttl']);

    return $videos;
}

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

    $wp_customize->add_section('giaimatuky_youtube', array(
        'title'       => __('YouTube channel', 'giaimatuky'),
        'priority'    => 40,
        'description' => __('Nhập Channel ID để tự động hiển thị video mới nhất thông qua RSS feed.', 'giaimatuky'),
    ));

    $wp_customize->add_setting('gmk_youtube_channel_id', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('gmk_youtube_channel_id', array(
        'section'     => 'giaimatuky_youtube',
        'label'       => __('Channel ID', 'giaimatuky'),
        'type'        => 'text',
        'description' => __('Ví dụ: UCxxxxxxxxxx (xem trong phần Advanced settings của YouTube Studio).', 'giaimatuky'),
    ));

    $wp_customize->add_setting('gmk_youtube_max_items', array(
        'default'           => 8,
        'sanitize_callback' => function ($value) {
            $value = absint($value);
            if ($value < 1) {
                $value = 1;
            }
            if ($value > 15) {
                $value = 15;
            }
            return $value;
        },
    ));
    $wp_customize->add_control('gmk_youtube_max_items', array(
        'section'     => 'giaimatuky_youtube',
        'label'       => __('Số video muốn hiển thị', 'giaimatuky'),
        'type'        => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 15,
        ),
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

/**
 * Custom post type: Chuyên gia
 */
add_action('init', 'gmk_register_expert_post_type');
function gmk_register_expert_post_type() {
    $labels = array(
        'name'               => __('Chuyên gia', 'giaimatuky'),
        'singular_name'      => __('Chuyên gia', 'giaimatuky'),
        'menu_name'          => __('Chuyên gia', 'giaimatuky'),
        'name_admin_bar'     => __('Chuyên gia', 'giaimatuky'),
        'add_new'            => __('Thêm chuyên gia', 'giaimatuky'),
        'add_new_item'       => __('Thêm chuyên gia mới', 'giaimatuky'),
        'edit_item'          => __('Chỉnh sửa chuyên gia', 'giaimatuky'),
        'new_item'           => __('Chuyên gia mới', 'giaimatuky'),
        'view_item'          => __('Xem chuyên gia', 'giaimatuky'),
        'all_items'          => __('Danh sách chuyên gia', 'giaimatuky'),
        'search_items'       => __('Tìm chuyên gia', 'giaimatuky'),
        'not_found'          => __('Chưa có chuyên gia nào', 'giaimatuky'),
        'not_found_in_trash' => __('Không có chuyên gia trong thùng rác', 'giaimatuky'),
    );

    register_post_type(
        'gmk_expert',
        array(
            'labels'        => $labels,
            'public'        => true,
            'menu_icon'     => 'dashicons-groups',
            'supports'      => array('title', 'editor', 'thumbnail', 'revisions', 'page-attributes'),
            'has_archive'   => false,
            'show_in_rest'  => true,
            'rewrite'       => array('slug' => 'chuyen-gia'),
        )
    );

    $tax_labels = array(
        'name'          => __('Lĩnh vực chuyên môn', 'giaimatuky'),
        'singular_name' => __('Lĩnh vực chuyên môn', 'giaimatuky'),
        'search_items'  => __('Tìm lĩnh vực', 'giaimatuky'),
        'all_items'     => __('Tất cả lĩnh vực', 'giaimatuky'),
        'edit_item'     => __('Chỉnh sửa lĩnh vực', 'giaimatuky'),
        'update_item'   => __('Cập nhật lĩnh vực', 'giaimatuky'),
        'add_new_item'  => __('Thêm lĩnh vực mới', 'giaimatuky'),
        'menu_name'     => __('Lĩnh vực', 'giaimatuky'),
    );

    register_taxonomy(
        'gmk_expert_domain',
        'gmk_expert',
        array(
            'labels'            => $tax_labels,
            'hierarchical'      => false,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'rewrite'           => array('slug' => 'linh-vuc-chuyen-gia'),
        )
    );
}

/**
 * Expert meta box
 */
add_action('add_meta_boxes', 'gmk_add_expert_meta_box');
function gmk_add_expert_meta_box() {
    add_meta_box(
        'gmk_expert_details',
        __('Thông tin chuyên gia', 'giaimatuky'),
        'gmk_render_expert_meta_box',
        'gmk_expert',
        'normal',
        'high'
    );
}

function gmk_render_expert_meta_box($post) {
    wp_nonce_field('gmk_expert_meta_nonce', 'gmk_expert_meta_nonce');

    $job_title   = get_post_meta($post->ID, 'gmk_expert_job_title', true);
    $location    = get_post_meta($post->ID, 'gmk_expert_location', true);
    $credentials = get_post_meta($post->ID, 'gmk_expert_credentials', true);
    $avatar_id   = (int) get_post_meta($post->ID, 'gmk_expert_avatar_id', true);
    $avatar_url  = $avatar_id ? wp_get_attachment_image_url($avatar_id, 'medium') : '';
    ?>
    <div class="gmk-expert-meta-grid" style="display:grid;gap:1rem;">
        <p>
            <label for="gmk_expert_job_title" style="display:block;font-weight:600;margin-bottom:4px;"><?php _e('Chức danh / vai trò', 'giaimatuky'); ?></label>
            <input type="text" id="gmk_expert_job_title" name="gmk_expert_job_title" class="widefat"
                   value="<?php echo esc_attr($job_title); ?>" placeholder="<?php esc_attr_e('VD: Tiến sĩ Tâm lý giáo dục', 'giaimatuky'); ?>">
        </p>
        <p>
            <label for="gmk_expert_location" style="display:block;font-weight:600;margin-bottom:4px;"><?php _e('Khu vực làm việc', 'giaimatuky'); ?></label>
            <input type="text" id="gmk_expert_location" name="gmk_expert_location" class="widefat"
                   value="<?php echo esc_attr($location); ?>" placeholder="<?php esc_attr_e('VD: Kuala Lumpur, Malaysia', 'giaimatuky'); ?>">
        </p>
        <p>
            <label for="gmk_expert_credentials" style="display:block;font-weight:600;margin-bottom:4px;"><?php _e('Thành tựu / chứng chỉ', 'giaimatuky'); ?></label>
            <textarea id="gmk_expert_credentials" name="gmk_expert_credentials" class="widefat" rows="6"
                      placeholder="<?php esc_attr_e('Mỗi dòng là một chứng chỉ hoặc thành tựu...', 'giaimatuky'); ?>"><?php echo esc_textarea($credentials); ?></textarea>
            <small><?php _e('Nhập mỗi dòng một gạch đầu dòng để hiển thị trong giao diện.', 'giaimatuky'); ?></small>
        </p>
        <div>
            <label style="display:block;font-weight:600;margin-bottom:8px;"><?php _e('Ảnh chuyên gia', 'giaimatuky'); ?></label>
            <div class="gmk-expert-avatar-preview" style="margin-bottom:0.75rem;">
                <?php if ($avatar_url) : ?>
                    <img src="<?php echo esc_url($avatar_url); ?>" alt="" style="max-width:180px;border-radius:12px;">
                <?php endif; ?>
            </div>
            <input type="hidden" id="gmk_expert_avatar_id" name="gmk_expert_avatar_id" value="<?php echo esc_attr($avatar_id); ?>">
            <button type="button" class="button gmk-expert-avatar-select"><?php _e('Chọn ảnh', 'giaimatuky'); ?></button>
            <button type="button" class="button gmk-expert-avatar-remove <?php echo $avatar_id ? '' : 'is-hidden'; ?>"
                    style="margin-left:8px;"><?php _e('Xoá ảnh', 'giaimatuky'); ?></button>
        </div>
    </div>
    <?php
}

add_action('save_post_gmk_expert', 'gmk_save_expert_meta');
function gmk_save_expert_meta($post_id) {
    if (!isset($_POST['gmk_expert_meta_nonce']) || !wp_verify_nonce($_POST['gmk_expert_meta_nonce'], 'gmk_expert_meta_nonce')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $job_title   = isset($_POST['gmk_expert_job_title']) ? sanitize_text_field($_POST['gmk_expert_job_title']) : '';
    $location    = isset($_POST['gmk_expert_location']) ? sanitize_text_field($_POST['gmk_expert_location']) : '';
    $credentials = isset($_POST['gmk_expert_credentials']) ? sanitize_textarea_field($_POST['gmk_expert_credentials']) : '';
    $avatar_id   = isset($_POST['gmk_expert_avatar_id']) ? absint($_POST['gmk_expert_avatar_id']) : 0;

    update_post_meta($post_id, 'gmk_expert_job_title', $job_title);
    update_post_meta($post_id, 'gmk_expert_location', $location);
    update_post_meta($post_id, 'gmk_expert_credentials', $credentials);
    update_post_meta($post_id, 'gmk_expert_avatar_id', $avatar_id);
}

/**
 * Admin assets for expert media field
 */
add_action('admin_enqueue_scripts', 'gmk_enqueue_expert_admin_assets');
function gmk_enqueue_expert_admin_assets($hook) {
    $screen = get_current_screen();
    if (!$screen || 'gmk_expert' !== $screen->post_type) {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_script(
        'gmk-expert-admin',
        get_template_directory_uri() . '/js/gmk-expert-admin.js',
        array('jquery'),
        '1.0.0',
        true
    );
}

