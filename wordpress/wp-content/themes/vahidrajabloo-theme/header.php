<!DOCTYPE html>
<?php
$vr_html_language_attributes = get_language_attributes();
if (! preg_match('/\blang=/', $vr_html_language_attributes)) {
    $vr_html_language_attributes = trim($vr_html_language_attributes . ' lang="en-US"');
}
?>
<html <?php echo $vr_html_language_attributes; ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
    <style id="dark-mode-overrides">
        [data-theme="dark"] .section--gray,
        [data-theme="dark"] #features,
        [data-theme="dark"] .features-section {
            background-color: #1e293b !important;
            background-image: none !important;
            color: #cbd5e1 !important;
        }
    </style>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to main content', 'vahidrajabloo-theme' ); ?></a>

<?php if (get_theme_mod('announcement_bar_enable', false) && get_theme_mod('announcement_bar_text', '')) : ?>
<div class="announcement-bar" id="announcement-bar">
    <div class="container">
        <p class="announcement-bar__text">
            <?php echo esc_html(get_theme_mod('announcement_bar_text', '')); ?>
        </p>
        <?php if (get_theme_mod('announcement_bar_show_close', true)) : ?>
        <button class="announcement-bar__close" id="announcement-bar-close" aria-label="<?php esc_attr_e('Close announcement', 'vahidrajabloo-theme'); ?>">×</button>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<header class="site-header" id="site-header">
    <div class="container">
        <div class="header-inner flex flex--between">
            <!-- Logo -->
            <div class="header-logo">
                <?php vahidrajabloo_custom_logo(); ?>
            </div>

            <!-- Navigation -->
            <nav class="header-nav hide-mobile" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'vahidrajabloo-theme' ); ?>">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'menu_class'     => 'nav-menu flex flex--gap-lg',
                    'container'      => false,
                    'fallback_cb'    => false,
                ));
                ?>
            </nav>

            <!-- Header Actions -->
            <div class="header-actions flex flex--gap-md hide-mobile">
                <?php
                $signin_text = get_theme_mod( 'header_signin_text', 'Sign in' );
                $signin_url = get_theme_mod( 'header_signin_url', '#' );
                $cta_text = get_theme_mod( 'header_cta_text', 'Join' );
                $cta_url = get_theme_mod( 'header_cta_url', '#' );
                ?>
                <a href="<?php echo esc_url( $signin_url ); ?>" class="btn btn--link">
                    <?php echo esc_html( $signin_text ); ?>
                </a>
                <a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn--primary">
                    <?php echo esc_html( $cta_text ); ?>
                </a>
                
                <!-- Dark Mode Toggle -->
                <button id="theme-toggle" class="theme-toggle-btn" aria-label="Toggle Dark Mode" aria-pressed="false">
                    <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                    <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                </button>
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle hide-desktop" aria-label="<?php esc_attr_e( 'Toggle Menu', 'vahidrajabloo-theme' ); ?>" aria-expanded="false">
                <span class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <nav class="mobile-nav hide-desktop" role="navigation" aria-label="<?php esc_attr_e( 'Mobile Menu', 'vahidrajabloo-theme' ); ?>">
        <div class="container">
            <?php
            wp_nav_menu( array(
                'theme_location' => 'primary',
                'menu_class'     => 'mobile-menu',
                'container'      => false,
                'fallback_cb'    => false,
            ));
            ?>
            <div class="mobile-nav-actions">
                <a href="<?php echo esc_url( $signin_url ); ?>" class="btn btn--secondary btn--full">
                    <?php echo esc_html( $signin_text ); ?>
                </a>
                <a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn--primary btn--full">
                    <?php echo esc_html( $cta_text ); ?>
                </a>
                
                <!-- Dark Mode Toggle Mobile -->
                <button id="theme-toggle-mobile" class="theme-toggle-btn theme-toggle-mobile" aria-label="Toggle Dark Mode" aria-pressed="false">
                     <span class="theme-toggle-text"><?php esc_html_e('Toggle Dark Mode', 'vahidrajabloo-theme'); ?></span>
                     <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                     <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                </button>
            </div>
        </div>
    </nav>
</header>

<main id="main" class="site-main">
