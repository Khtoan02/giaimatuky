<?php
/**
 * Header template
 *
 * @package giaimatuky
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class('bg-slate-50 text-slate-900 antialiased'); ?>>
<?php wp_body_open(); ?>

<header class="bg-white/90 backdrop-blur border-b border-slate-100 sticky top-0 z-50">
    <div class="gmk-shell h-16 flex items-center justify-between relative">
        <div class="flex items-center gap-3">
            <button class="menu-toggle md:hidden inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 text-slate-600 hover:text-slate-900" aria-label="<?php esc_attr_e('Mở menu', 'giaimatuky'); ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
            <div class="site-logo text-xl font-semibold tracking-tight">
                <?php
                if (has_custom_logo()) {
                    the_custom_logo();
                } else {
                    ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="text-slate-900 hover:text-blue-600 transition">
                        <?php bloginfo('name'); ?>
                    </a>
                    <?php
                }
                ?>
            </div>
        </div>
        <nav class="main-navigation md:flex items-center gap-6 text-sm font-medium text-slate-600" role="navigation" aria-label="<?php esc_attr_e('Menu chính', 'giaimatuky'); ?>">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'menu_class'     => 'flex flex-col md:flex-row gap-4 md:gap-6 text-base md:text-sm font-medium',
                'container'      => false,
                'fallback_cb'    => 'wp_page_menu',
            ));
            ?>
        </nav>
        <div class="hidden md:flex items-center gap-3 text-sm text-slate-500">
            <a href="<?php echo esc_url(home_url('/gioi-thieu')); ?>" class="hover:text-slate-900"><?php esc_html_e('Giới thiệu', 'giaimatuky'); ?></a>
            <span class="h-1 w-1 rounded-full bg-slate-300"></span>
            <a href="<?php echo esc_url(home_url('/lien-he')); ?>" class="hover:text-slate-900"><?php esc_html_e('Liên hệ', 'giaimatuky'); ?></a>
        </div>
    </div>
</header>

<main id="site-main" class="py-10">

