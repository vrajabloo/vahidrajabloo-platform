<?php
/**
 * VahidRajabloo Theme functions and definitions
 *
 * @package VahidRajabloo_Theme
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Theme Constants
define( 'VAHIDRAJABLOO_THEME_VERSION', '1.6.0' );
define( 'VAHIDRAJABLOO_THEME_PATH', get_template_directory() );
define( 'VAHIDRAJABLOO_THEME_URL', get_template_directory_uri() );
define( 'VAHIDRAJABLOO_THEME_ASSETS_URL', VAHIDRAJABLOO_THEME_URL . '/assets/' );

// Include custom blocks
require_once VAHIDRAJABLOO_THEME_PATH . '/inc/blocks.php';
require_once VAHIDRAJABLOO_THEME_PATH . '/inc/customizer.php';
require_once VAHIDRAJABLOO_THEME_PATH . '/inc/post-types.php';

/**
 * Theme Setup
 */
function vahidrajabloo_theme_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails
    add_theme_support( 'post-thumbnails' );
    set_post_thumbnail_size( 1200, 630, true );

    // Custom logo support
    add_theme_support( 'custom-logo', array(
        'height'      => 100,
        'width'       => 350,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    // Register Navigation Menus
    register_nav_menus( array(
        'primary'   => esc_html__( 'Primary Menu', 'vahidrajabloo-theme' ),
        'footer'    => esc_html__( 'Footer Menu 1', 'vahidrajabloo-theme' ),
        'footer-2'  => esc_html__( 'Footer Menu 2', 'vahidrajabloo-theme' ),
        'footer-3'  => esc_html__( 'Footer Menu 3', 'vahidrajabloo-theme' ),
    ));

    // HTML5 support
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Wide alignment support for Gutenberg
    add_theme_support( 'align-wide' );

    // Responsive embeds
    add_theme_support( 'responsive-embeds' );

    // Editor styles
    add_theme_support( 'editor-styles' );
    add_editor_style( 'assets/css/editor-style.css' );

    // WooCommerce support
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'vahidrajabloo_theme_setup' );

/**
 * Content Width
 */
function vahidrajabloo_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'vahidrajabloo_content_width', 1280 );
}
add_action( 'after_setup_theme', 'vahidrajabloo_content_width', 0 );

/**
 * Enqueue Scripts and Styles
 */
function vahidrajabloo_enqueue_scripts() {
    // Google Fonts - Inter
    wp_enqueue_style(
        'vahidrajabloo-google-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap',
        array(),
        null
    );

    // Main Stylesheet
    wp_enqueue_style(
        'vahidrajabloo-style',
        get_stylesheet_uri(),
        array(),
        VAHIDRAJABLOO_THEME_VERSION
    );

    // Theme CSS
    wp_enqueue_style(
        'vahidrajabloo-theme-style',
        VAHIDRAJABLOO_THEME_ASSETS_URL . 'css/theme.css',
        array( 'vahidrajabloo-style' ),
        VAHIDRAJABLOO_THEME_VERSION
    );

    // Theme JavaScript
    wp_enqueue_script(
        'vahidrajabloo-theme-script',
        VAHIDRAJABLOO_THEME_ASSETS_URL . 'js/theme.js',
        array(),
        VAHIDRAJABLOO_THEME_VERSION,
        true
    );
}
add_action( 'wp_enqueue_scripts', 'vahidrajabloo_enqueue_scripts' );

/**
 * Elementor Theme Locations
 */
function vahidrajabloo_register_elementor_locations( $elementor_theme_manager ) {
    $elementor_theme_manager->register_all_core_location();
}
add_action( 'elementor/theme/register_locations', 'vahidrajabloo_register_elementor_locations' );

/**
 * Check if Elementor is building the page
 */
function vahidrajabloo_is_elementor() {
    return defined( 'ELEMENTOR_VERSION' ) && \Elementor\Plugin::$instance->preview->is_preview_mode();
}

/**
 * Customizer Settings
 */
function vahidrajabloo_customize_register( $wp_customize ) {
    
    // === HEADER SECTION ===
    $wp_customize->add_section( 'vahidrajabloo_header', array(
        'title'    => __( 'Header Settings', 'vahidrajabloo-theme' ),
        'priority' => 30,
    ));

    // Header CTA Button Text
    $wp_customize->add_setting( 'header_cta_text', array(
        'default'           => 'Join',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control( 'header_cta_text', array(
        'label'   => __( 'CTA Button Text', 'vahidrajabloo-theme' ),
        'section' => 'vahidrajabloo_header',
        'type'    => 'text',
    ));

    // Header CTA Button URL
    $wp_customize->add_setting( 'header_cta_url', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control( 'header_cta_url', array(
        'label'   => __( 'CTA Button URL', 'vahidrajabloo-theme' ),
        'section' => 'vahidrajabloo_header',
        'type'    => 'url',
    ));

    // Header Sign In Text
    $wp_customize->add_setting( 'header_signin_text', array(
        'default'           => 'Sign in',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control( 'header_signin_text', array(
        'label'   => __( 'Sign In Button Text', 'vahidrajabloo-theme' ),
        'section' => 'vahidrajabloo_header',
        'type'    => 'text',
    ));

    // Header Sign In URL
    $wp_customize->add_setting( 'header_signin_url', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control( 'header_signin_url', array(
        'label'   => __( 'Sign In Button URL', 'vahidrajabloo-theme' ),
        'section' => 'vahidrajabloo_header',
        'type'    => 'url',
    ));

    // === HERO SECTION ===
    $wp_customize->add_section( 'vahidrajabloo_hero', array(
        'title'    => __( 'Hero Section', 'vahidrajabloo-theme' ),
        'priority' => 35,
    ));

    // Hero Headline
    $wp_customize->add_setting( 'hero_headline', array(
        'default'           => 'Building systems that serve everyone',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control( 'hero_headline', array(
        'label'   => __( 'Headline', 'vahidrajabloo-theme' ),
        'section' => 'vahidrajabloo_hero',
        'type'    => 'text',
    ));

    // Hero Subtext
    $wp_customize->add_setting( 'hero_subtext', array(
        'default'           => 'We create digital experiences that make a difference in people\'s lives.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control( 'hero_subtext', array(
        'label'   => __( 'Subtext', 'vahidrajabloo-theme' ),
        'section' => 'vahidrajabloo_hero',
        'type'    => 'textarea',
    ));

    // Hero Image
    $wp_customize->add_setting( 'hero_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'hero_image', array(
        'label'   => __( 'Hero Image', 'vahidrajabloo-theme' ),
        'section' => 'vahidrajabloo_hero',
    )));

    // Hero Primary Button Text
    $wp_customize->add_setting( 'hero_btn_primary_text', array(
        'default'           => 'Get Started',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control( 'hero_btn_primary_text', array(
        'label'   => __( 'Primary Button Text', 'vahidrajabloo-theme' ),
        'section' => 'vahidrajabloo_hero',
        'type'    => 'text',
    ));

    // Hero Primary Button URL
    $wp_customize->add_setting( 'hero_btn_primary_url', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control( 'hero_btn_primary_url', array(
        'label'   => __( 'Primary Button URL', 'vahidrajabloo-theme' ),
        'section' => 'vahidrajabloo_hero',
        'type'    => 'url',
    ));

    // === FEATURES SECTION ===
    $wp_customize->add_section( 'vahidrajabloo_features', array(
        'title'    => __( 'Features Section', 'vahidrajabloo-theme' ),
        'priority' => 40,
    ));

    // Features Tagline
    $wp_customize->add_setting( 'features_tagline', array(
        'default'           => 'Features',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control( 'features_tagline', array(
        'label'   => __( 'Tagline', 'vahidrajabloo-theme' ),
        'section' => 'vahidrajabloo_features',
        'type'    => 'text',
    ));

    // Features Title
    $wp_customize->add_setting( 'features_title', array(
        'default'           => 'What we build and why it matters',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control( 'features_title', array(
        'label'   => __( 'Title', 'vahidrajabloo-theme' ),
        'section' => 'vahidrajabloo_features',
        'type'    => 'text',
    ));

    // Feature Cards (4 features)
    for ( $i = 1; $i <= 4; $i++ ) {
        $wp_customize->add_setting( "feature_{$i}_title", array(
            'default'           => "Feature {$i}",
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control( "feature_{$i}_title", array(
            'label'   => sprintf( __( 'Feature %d Title', 'vahidrajabloo-theme' ), $i ),
            'section' => 'vahidrajabloo_features',
            'type'    => 'text',
        ));

        $wp_customize->add_setting( "feature_{$i}_text", array(
            'default'           => 'Feature description goes here.',
            'sanitize_callback' => 'sanitize_textarea_field',
        ));
        $wp_customize->add_control( "feature_{$i}_text", array(
            'label'   => sprintf( __( 'Feature %d Description', 'vahidrajabloo-theme' ), $i ),
            'section' => 'vahidrajabloo_features',
            'type'    => 'textarea',
        ));

        $wp_customize->add_setting( "feature_{$i}_icon", array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, "feature_{$i}_icon", array(
            'label'   => sprintf( __( 'Feature %d Icon', 'vahidrajabloo-theme' ), $i ),
            'section' => 'vahidrajabloo_features',
        )));

        // Feature Link URL
        $wp_customize->add_setting( "feature_{$i}_link", array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control( "feature_{$i}_link", array(
            'label'   => sprintf( __( 'Feature %d Link URL', 'vahidrajabloo-theme' ), $i ),
            'section' => 'vahidrajabloo_features',
            'type'    => 'url',
        ));
    }

    // === NEWSLETTER SECTION ===
    $wp_customize->add_section( 'vahidrajabloo_newsletter', array(
        'title'    => __( 'Newsletter Section', 'vahidrajabloo-theme' ),
        'priority' => 50,
    ));

    // Newsletter Title
    $wp_customize->add_setting( 'newsletter_title', array(
        'default'           => 'Stay in the loop',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control( 'newsletter_title', array(
        'label'   => __( 'Title', 'vahidrajabloo-theme' ),
        'section' => 'vahidrajabloo_newsletter',
        'type'    => 'text',
    ));

    // Newsletter Description
    $wp_customize->add_setting( 'newsletter_description', array(
        'default'           => 'Subscribe to our newsletter for the latest updates.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control( 'newsletter_description', array(
        'label'   => __( 'Description', 'vahidrajabloo-theme' ),
        'section' => 'vahidrajabloo_newsletter',
        'type'    => 'textarea',
    ));

    // Newsletter Button Text
    $wp_customize->add_setting( 'newsletter_btn_text', array(
        'default'           => 'Subscribe',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control( 'newsletter_btn_text', array(
        'label'   => __( 'Button Text', 'vahidrajabloo-theme' ),
        'section' => 'vahidrajabloo_newsletter',
        'type'    => 'text',
    ));

    // === FOOTER SECTION ===
    $wp_customize->add_section( 'vahidrajabloo_footer', array(
        'title'    => __( 'Footer Settings', 'vahidrajabloo-theme' ),
        'priority' => 60,
    ));

    // Footer Copyright
    $wp_customize->add_setting( 'footer_copyright', array(
        'default'           => '© ' . date('Y') . ' VahidRajabloo. All rights reserved.',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control( 'footer_copyright', array(
        'label'   => __( 'Copyright Text', 'vahidrajabloo-theme' ),
        'section' => 'vahidrajabloo_footer',
        'type'    => 'text',
    ));

    // Social Links
    $social_networks = array( 'facebook', 'twitter', 'instagram', 'linkedin', 'youtube', 'wikipedia' );
    foreach ( $social_networks as $network ) {
        $wp_customize->add_setting( "footer_social_{$network}", array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control( "footer_social_{$network}", array(
            'label'   => sprintf( __( '%s URL', 'vahidrajabloo-theme' ), ucfirst( $network ) ),
            'section' => 'vahidrajabloo_footer',
            'type'    => 'url',
        ));
    }

    // === COLORS SECTION ===
    $wp_customize->add_setting( 'theme_primary_color', array(
        'default'           => '#4361EE',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'theme_primary_color', array(
        'label'   => __( 'Primary Color (Blue)', 'vahidrajabloo-theme' ),
        'section' => 'colors',
    )));

    $wp_customize->add_setting( 'theme_accent_color', array(
        'default'           => '#7C3AED',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'theme_accent_color', array(
        'label'   => __( 'Accent Color (Purple)', 'vahidrajabloo-theme' ),
        'section' => 'colors',
    )));

    $wp_customize->add_setting( 'theme_accent_light_color', array(
        'default'           => '#22D3EE',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'theme_accent_light_color', array(
        'label'   => __( 'Accent Light Color (Cyan)', 'vahidrajabloo-theme' ),
        'section' => 'colors',
    )));

    $wp_customize->add_setting( 'theme_background_alt_color', array(
        'default'           => '#F8FAFC',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'theme_background_alt_color', array(
        'label'   => __( 'Background Alt Color (Light Gray)', 'vahidrajabloo-theme' ),
        'section' => 'colors',
    )));
}
add_action( 'customize_register', 'vahidrajabloo_customize_register' );

/**
 * Output Customizer CSS
 */
function vahidrajabloo_customizer_css() {
    $primary_color = get_theme_mod( 'theme_primary_color', '#4361EE' );
    $accent_color = get_theme_mod( 'theme_accent_color', '#7C3AED' );
    $accent_light_color = get_theme_mod( 'theme_accent_light_color', '#22D3EE' );
    $background_alt_color = get_theme_mod( 'theme_background_alt_color', '#F8FAFC' );
    
    ?>
    <style type="text/css">
        :root {
            --color-primary: <?php echo esc_attr( $primary_color ); ?>;
            --color-accent: <?php echo esc_attr( $accent_color ); ?>;
            --color-accent-light: <?php echo esc_attr( $accent_light_color ); ?>;
            --color-background-alt: <?php echo esc_attr( $background_alt_color ); ?>;
        }
    </style>
    <?php
}
add_action( 'wp_head', 'vahidrajabloo_customizer_css' );

/**
 * Custom Logo
 */
function vahidrajabloo_custom_logo() {
    if ( has_custom_logo() ) {
        the_custom_logo();
    } else {
        echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="site-logo">';
        echo '<span class="site-title">' . get_bloginfo( 'name' ) . '</span>';
        echo '</a>';
    }
}

/**
 * Widget Areas
 */
function vahidrajabloo_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Footer Column 1', 'vahidrajabloo-theme' ),
        'id'            => 'footer-1',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));

    register_sidebar( array(
        'name'          => __( 'Footer Column 2', 'vahidrajabloo-theme' ),
        'id'            => 'footer-2',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));

    register_sidebar( array(
        'name'          => __( 'Footer Column 3', 'vahidrajabloo-theme' ),
        'id'            => 'footer-3',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));

    register_sidebar( array(
        'name'          => __( 'Footer Column 4', 'vahidrajabloo-theme' ),
        'id'            => 'footer-4',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));
}
add_action( 'widgets_init', 'vahidrajabloo_widgets_init' );

/**
 * Excerpt Length
 */
function vahidrajabloo_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'vahidrajabloo_excerpt_length' );

/**
 * Excerpt More
 */
function vahidrajabloo_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'vahidrajabloo_excerpt_more' );
