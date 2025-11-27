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

<header class="bg-white/95 backdrop-blur border-b border-slate-100 sticky top-0 z-50 shadow-sm">
    <div class="gmk-shell h-16 flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <button class="menu-toggle md:hidden inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 text-slate-600 hover:text-slate-900 transition" aria-label="<?php esc_attr_e('Mở menu', 'giaimatuky'); ?>" aria-expanded="false">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
            <?php
            $custom_logo_id = get_theme_mod('custom_logo');
            $custom_logo_markup = '';
            if ($custom_logo_id) {
                $custom_logo_markup = wp_get_attachment_image(
                    $custom_logo_id,
                    'full',
                    false,
                    array(
                        'class' => 'h-8 w-auto object-contain',
                        'alt'   => get_bloginfo('name'),
                    )
                );
            }
            ?>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="flex items-center gap-2 group">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-sky-500 text-white shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M12 21s-6-4.35-6-9a3.5 3.5 0 0 1 6-2.45A3.5 3.5 0 0 1 18 12c0 4.65-6 9-6 9z" fill="currentColor" stroke="none"/>
                    </svg>
                </span>
                <?php if ($custom_logo_markup) : ?>
                    <span class="inline-flex items-center">
                        <?php echo $custom_logo_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </span>
                <?php else : ?>
                    <span class="text-lg font-bold tracking-tight text-sky-700 group-hover:text-sky-800 transition">
                        <?php bloginfo('name'); ?>
                    </span>
                <?php endif; ?>
            </a>
        </div>
        <nav class="main-navigation hidden md:flex items-center gap-6 text-sm font-semibold text-slate-600" role="navigation" aria-label="<?php esc_attr_e('Menu chính', 'giaimatuky'); ?>">
            <?php
            if (has_nav_menu('primary')) {
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class'     => 'flex flex-col md:flex-row gap-4 md:gap-6 text-base md:text-sm font-medium',
                    'container'      => false,
                ));
            } else {
                ?>
                <ul class="flex flex-col md:flex-row gap-4 md:gap-6 text-base md:text-sm font-medium">
                    <li><a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-sky-700 transition"><?php esc_html_e('Trang chủ', 'giaimatuky'); ?></a></li>
                    <li>
                        <a href="<?php echo esc_url(home_url('/form-cau-hoi/#videos')); ?>" class="flex items-center gap-1 hover:text-sky-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M10 16.5l6-4.5-6-4.5v9z"/>
                                <path d="M4 5h9a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2z"/>
                            </svg>
                            <?php esc_html_e('Video Mới', 'giaimatuky'); ?>
                        </a>
                    </li>
                </ul>
                <?php
            }
            ?>
        </nav>
        <div class="flex items-center gap-3">
            <a href="https://www.youtube.com/channel/UC8RRqVV1gZyor3hPvyxjjNw" target="_blank" rel="noreferrer" class="hidden md:inline-flex items-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:border-sky-500 hover:text-sky-600 transition">
                <i class="fa-brands fa-youtube text-red-600"></i>
                <?php esc_html_e('Youtube', 'giaimatuky'); ?>
            </a>
            <a href="<?php echo esc_url(home_url('/#gui-cau-hoi')); ?>" class="hidden md:inline-flex items-center gap-2 rounded-full bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-sky-700 transition gmk-cta-button">
                <?php esc_html_e('Gửi câu hỏi', 'giaimatuky'); ?>
            </a>
        </div>
    </div>
    <nav class="mobile-navigation md:hidden hidden flex-col gap-4 border-t border-slate-100 bg-white px-4 py-4 text-base font-medium text-slate-600" role="navigation" aria-label="<?php esc_attr_e('Menu di động', 'giaimatuky'); ?>">
        <?php
        if (has_nav_menu('primary')) {
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'menu_class'     => 'flex flex-col gap-4',
                'container'      => false,
            ));
        } else {
            ?>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-sky-700 transition"><?php esc_html_e('Trang chủ', 'giaimatuky'); ?></a>
            <a href="<?php echo esc_url(home_url('/form-cau-hoi/#videos')); ?>" class="hover:text-sky-700 transition"><?php esc_html_e('Video Mới', 'giaimatuky'); ?></a>
        <?php } ?>
        <a href="https://www.youtube.com/channel/UC8RRqVV1gZyor3hPvyxjjNw" target="_blank" rel="noreferrer" class="inline-flex items-center justify-center rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:border-sky-500 hover:text-sky-600 transition gap-2">
            <i class="fa-brands fa-youtube text-red-600"></i>
            <?php esc_html_e('Youtube', 'giaimatuky'); ?>
        </a>
        <a href="<?php echo esc_url(home_url('/#gui-cau-hoi')); ?>" class="inline-flex items-center justify-center rounded-full bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-sky-700 transition gmk-cta-button">
            <?php esc_html_e('Gửi câu hỏi', 'giaimatuky'); ?>
        </a>
    </nav>
</header>

<main id="site-main" class="py-10">

