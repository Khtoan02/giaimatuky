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
    // Styles
    wp_enqueue_style('giaimatuky-style', get_stylesheet_uri(), array(), GIAIMATUKY_VERSION);

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

