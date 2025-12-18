<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

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
            </div>
        </div>
    </nav>
</header>

<main id="main" class="site-main">
