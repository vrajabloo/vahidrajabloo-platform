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
define( 'VAHIDRAJABLOO_THEME_VERSION', '2.0.4' );
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
    
    // Newsletter script data (must be after wp_enqueue_script)
    wp_localize_script( 'vahidrajabloo-theme-script', 'vrNewsletter', [
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'newsletter_nonce' ),
    ]);
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

    // ========================================================================
    // === THEME COLORS PANEL ===
    // ========================================================================
    $wp_customize->add_panel( 'vahidrajabloo_colors_panel', array(
        'title'       => __( 'Theme Colors', 'vahidrajabloo-theme' ),
        'description' => __( 'Customize all theme colors from here.', 'vahidrajabloo-theme' ),
        'priority'    => 25,
    ));

    // --- BRAND COLORS SECTION ---
    $wp_customize->add_section( 'vahidrajabloo_brand_colors', array(
        'title'    => __( 'Brand Colors', 'vahidrajabloo-theme' ),
        'panel'    => 'vahidrajabloo_colors_panel',
        'priority' => 10,
    ));

    // Primary Color
    $wp_customize->add_setting( 'theme_primary_color', array(
        'default'           => '#4361EE',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'theme_primary_color', array(
        'label'       => __( 'Primary Color', 'vahidrajabloo-theme' ),
        'description' => __( 'Main brand color used for buttons, links, etc.', 'vahidrajabloo-theme' ),
        'section'     => 'vahidrajabloo_brand_colors',
    )));

    // Primary Dark Color
    $wp_customize->add_setting( 'theme_primary_dark_color', array(
        'default'           => '#3651D4',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'theme_primary_dark_color', array(
        'label'       => __( 'Primary Dark', 'vahidrajabloo-theme' ),
        'description' => __( 'Hover state color for primary elements.', 'vahidrajabloo-theme' ),
        'section'     => 'vahidrajabloo_brand_colors',
    )));

    // Primary Light Color
    $wp_customize->add_setting( 'theme_primary_light_color', array(
        'default'           => '#6B7FFF',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'theme_primary_light_color', array(
        'label'       => __( 'Primary Light', 'vahidrajabloo-theme' ),
        'description' => __( 'Light accent of primary color.', 'vahidrajabloo-theme' ),
        'section'     => 'vahidrajabloo_brand_colors',
    )));

    // --- ACCENT COLORS SECTION ---
    $wp_customize->add_section( 'vahidrajabloo_accent_colors', array(
        'title'    => __( 'Accent Colors', 'vahidrajabloo-theme' ),
        'panel'    => 'vahidrajabloo_colors_panel',
        'priority' => 20,
    ));

    // Accent Color
    $wp_customize->add_setting( 'theme_accent_color', array(
        'default'           => '#7C3AED',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'theme_accent_color', array(
        'label'       => __( 'Accent Color (Purple)', 'vahidrajabloo-theme' ),
        'description' => __( 'Secondary accent for highlights.', 'vahidrajabloo-theme' ),
        'section'     => 'vahidrajabloo_accent_colors',
    )));

    // Accent Light Color
    $wp_customize->add_setting( 'theme_accent_light_color', array(
        'default'           => '#22D3EE',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'theme_accent_light_color', array(
        'label'       => __( 'Accent Light (Cyan)', 'vahidrajabloo-theme' ),
        'description' => __( 'Used for gradient accents.', 'vahidrajabloo-theme' ),
        'section'     => 'vahidrajabloo_accent_colors',
    )));

    // --- BACKGROUND COLORS SECTION ---
    $wp_customize->add_section( 'vahidrajabloo_background_colors', array(
        'title'    => __( 'Background Colors', 'vahidrajabloo-theme' ),
        'panel'    => 'vahidrajabloo_colors_panel',
        'priority' => 30,
    ));

    // Secondary/Main Background
    $wp_customize->add_setting( 'theme_secondary_color', array(
        'default'           => '#FFFFFF',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'theme_secondary_color', array(
        'label'       => __( 'Page Background', 'vahidrajabloo-theme' ),
        'description' => __( 'Main page background color.', 'vahidrajabloo-theme' ),
        'section'     => 'vahidrajabloo_background_colors',
    )));

    // Background Alt
    $wp_customize->add_setting( 'theme_background_alt_color', array(
        'default'           => '#F8FAFC',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'theme_background_alt_color', array(
        'label'       => __( 'Section Background', 'vahidrajabloo-theme' ),
        'description' => __( 'Alternate background for sections.', 'vahidrajabloo-theme' ),
        'section'     => 'vahidrajabloo_background_colors',
    )));

    // Background Dark
    $wp_customize->add_setting( 'theme_background_dark_color', array(
        'default'           => '#1E293B',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'theme_background_dark_color', array(
        'label'       => __( 'Dark Background', 'vahidrajabloo-theme' ),
        'description' => __( 'Footer and dark sections.', 'vahidrajabloo-theme' ),
        'section'     => 'vahidrajabloo_background_colors',
    )));

    // --- TEXT COLORS SECTION ---
    $wp_customize->add_section( 'vahidrajabloo_text_colors', array(
        'title'    => __( 'Text Colors', 'vahidrajabloo-theme' ),
        'panel'    => 'vahidrajabloo_colors_panel',
        'priority' => 40,
    ));

    // Text Primary (Headings)
    $wp_customize->add_setting( 'theme_text_primary_color', array(
        'default'           => '#0F172A',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'theme_text_primary_color', array(
        'label'       => __( 'Headings Color', 'vahidrajabloo-theme' ),
        'description' => __( 'Color for headings and titles.', 'vahidrajabloo-theme' ),
        'section'     => 'vahidrajabloo_text_colors',
    )));

    // Text Body
    $wp_customize->add_setting( 'theme_text_body_color', array(
        'default'           => '#475569',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'theme_text_body_color', array(
        'label'       => __( 'Body Text Color', 'vahidrajabloo-theme' ),
        'description' => __( 'Main paragraph text color.', 'vahidrajabloo-theme' ),
        'section'     => 'vahidrajabloo_text_colors',
    )));

    // Text Muted
    $wp_customize->add_setting( 'theme_text_muted_color', array(
        'default'           => '#94A3B8',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'theme_text_muted_color', array(
        'label'       => __( 'Muted Text Color', 'vahidrajabloo-theme' ),
        'description' => __( 'Subtle text like captions.', 'vahidrajabloo-theme' ),
        'section'     => 'vahidrajabloo_text_colors',
    )));

    // --- BORDER & MISC SECTION ---
    $wp_customize->add_section( 'vahidrajabloo_misc_colors', array(
        'title'    => __( 'Border & Misc', 'vahidrajabloo-theme' ),
        'panel'    => 'vahidrajabloo_colors_panel',
        'priority' => 50,
    ));

    // Border Color
    $wp_customize->add_setting( 'theme_border_color', array(
        'default'           => '#E2E8F0',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'theme_border_color', array(
        'label'       => __( 'Border Color', 'vahidrajabloo-theme' ),
        'description' => __( 'Default border color.', 'vahidrajabloo-theme' ),
        'section'     => 'vahidrajabloo_misc_colors',
    )));

    // --- DARK MODE COLORS SECTION ---
    $wp_customize->add_section( 'vahidrajabloo_dark_mode_colors', array(
        'title'       => __( 'Dark Mode Colors', 'vahidrajabloo-theme' ),
        'description' => __( 'Colors used when dark mode is enabled.', 'vahidrajabloo-theme' ),
        'panel'       => 'vahidrajabloo_colors_panel',
        'priority'    => 60,
    ));

    // Dark Mode Primary
    $wp_customize->add_setting( 'theme_dark_primary_color', array(
        'default'           => '#5B74F3',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'theme_dark_primary_color', array(
        'label'       => __( 'Primary Color (Dark Mode)', 'vahidrajabloo-theme' ),
        'description' => __( 'Button color in dark mode', 'vahidrajabloo-theme' ),
        'section'     => 'vahidrajabloo_dark_mode_colors',
    )));

    // Dark Mode Primary Dark (Hover)
    $wp_customize->add_setting( 'theme_dark_primary_dark_color', array(
        'default'           => '#4A63E0',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'theme_dark_primary_dark_color', array(
        'label'       => __( 'Primary Dark (Dark Mode)', 'vahidrajabloo-theme' ),
        'description' => __( 'Button hover color in dark mode', 'vahidrajabloo-theme' ),
        'section'     => 'vahidrajabloo_dark_mode_colors',
    )));

    // Dark Mode Background
    $wp_customize->add_setting( 'theme_dark_background_color', array(
        'default'           => '#0F172A',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'theme_dark_background_color', array(
        'label'   => __( 'Background (Dark Mode)', 'vahidrajabloo-theme' ),
        'section' => 'vahidrajabloo_dark_mode_colors',
    )));

    // Dark Mode Background Alt
    $wp_customize->add_setting( 'theme_dark_background_alt_color', array(
        'default'           => '#1E293B',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'theme_dark_background_alt_color', array(
        'label'   => __( 'Section Background (Dark Mode)', 'vahidrajabloo-theme' ),
        'section' => 'vahidrajabloo_dark_mode_colors',
    )));

    // Dark Mode Text Primary
    $wp_customize->add_setting( 'theme_dark_text_primary_color', array(
        'default'           => '#F8FAFC',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'theme_dark_text_primary_color', array(
        'label'   => __( 'Headings (Dark Mode)', 'vahidrajabloo-theme' ),
        'section' => 'vahidrajabloo_dark_mode_colors',
    )));

    // Dark Mode Text Body
    $wp_customize->add_setting( 'theme_dark_text_body_color', array(
        'default'           => '#CBD5E1',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'theme_dark_text_body_color', array(
        'label'   => __( 'Body Text (Dark Mode)', 'vahidrajabloo-theme' ),
        'section' => 'vahidrajabloo_dark_mode_colors',
    )));

    // Dark Mode Border
    $wp_customize->add_setting( 'theme_dark_border_color', array(
        'default'           => '#334155',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'theme_dark_border_color', array(
        'label'   => __( 'Border (Dark Mode)', 'vahidrajabloo-theme' ),
        'section' => 'vahidrajabloo_dark_mode_colors',
    )));
}
add_action( 'customize_register', 'vahidrajabloo_customize_register' );

/**
 * Output Customizer CSS
 */
function vahidrajabloo_customizer_css() {
    // Light Mode Colors
    $primary_color         = get_theme_mod( 'theme_primary_color', '#4361EE' );
    $primary_dark_color    = get_theme_mod( 'theme_primary_dark_color', '#3651D4' );
    $primary_light_color   = get_theme_mod( 'theme_primary_light_color', '#6B7FFF' );
    $secondary_color       = get_theme_mod( 'theme_secondary_color', '#FFFFFF' );
    $accent_color          = get_theme_mod( 'theme_accent_color', '#7C3AED' );
    $accent_light_color    = get_theme_mod( 'theme_accent_light_color', '#22D3EE' );
    $background_alt_color  = get_theme_mod( 'theme_background_alt_color', '#F8FAFC' );
    $background_dark_color = get_theme_mod( 'theme_background_dark_color', '#1E293B' );
    $text_primary_color    = get_theme_mod( 'theme_text_primary_color', '#0F172A' );
    $text_body_color       = get_theme_mod( 'theme_text_body_color', '#475569' );
    $text_muted_color      = get_theme_mod( 'theme_text_muted_color', '#94A3B8' );
    $border_color          = get_theme_mod( 'theme_border_color', '#E2E8F0' );
    
    // Dark Mode Colors
    $dark_primary_color         = get_theme_mod( 'theme_dark_primary_color', '#5B74F3' );
    $dark_primary_dark_color    = get_theme_mod( 'theme_dark_primary_dark_color', '#4A63E0' );
    $dark_background_color      = get_theme_mod( 'theme_dark_background_color', '#0F172A' );
    $dark_background_alt_color  = get_theme_mod( 'theme_dark_background_alt_color', '#1E293B' );
    $dark_text_primary_color    = get_theme_mod( 'theme_dark_text_primary_color', '#F8FAFC' );
    $dark_text_body_color       = get_theme_mod( 'theme_dark_text_body_color', '#CBD5E1' );
    $dark_border_color          = get_theme_mod( 'theme_dark_border_color', '#334155' );
    
    ?>
    <style type="text/css" id="vahidrajabloo-customizer-css">
        :root {
            /* Brand Colors */
            --color-primary: <?php echo esc_attr( $primary_color ); ?>;
            --color-primary-dark: <?php echo esc_attr( $primary_dark_color ); ?>;
            --color-primary-light: <?php echo esc_attr( $primary_light_color ); ?>;
            
            /* Accent Colors */
            --color-accent: <?php echo esc_attr( $accent_color ); ?>;
            --color-accent-light: <?php echo esc_attr( $accent_light_color ); ?>;
            
            /* Background Colors */
            --color-secondary: <?php echo esc_attr( $secondary_color ); ?>;
            --color-background-alt: <?php echo esc_attr( $background_alt_color ); ?>;
            --color-background-dark: <?php echo esc_attr( $background_dark_color ); ?>;
            
            /* Text Colors */
            --color-text-primary: <?php echo esc_attr( $text_primary_color ); ?>;
            --color-text-body: <?php echo esc_attr( $text_body_color ); ?>;
            --color-text-muted: <?php echo esc_attr( $text_muted_color ); ?>;
            
            /* Border */
            --color-border: <?php echo esc_attr( $border_color ); ?>;
            
            /* Gradients - Auto Generated */
            --gradient-primary: linear-gradient(135deg, <?php echo esc_attr( $primary_color ); ?> 0%, <?php echo esc_attr( $accent_color ); ?> 100%);
            --gradient-accent: linear-gradient(135deg, <?php echo esc_attr( $accent_color ); ?> 0%, <?php echo esc_attr( $accent_light_color ); ?> 100%);
        }
        
        /* Dark Mode Override */
        [data-theme="dark"] {
            --color-primary: <?php echo esc_attr( $dark_primary_color ); ?>;
            --color-primary-dark: <?php echo esc_attr( $dark_primary_dark_color ); ?>;
            --color-secondary: <?php echo esc_attr( $dark_background_color ); ?>;
            --color-background: <?php echo esc_attr( $dark_background_color ); ?>;
            --color-background-alt: <?php echo esc_attr( $dark_background_alt_color ); ?>;
            --color-text-primary: <?php echo esc_attr( $dark_text_primary_color ); ?>;
            --color-text-body: <?php echo esc_attr( $dark_text_body_color ); ?>;
            --color-border: <?php echo esc_attr( $dark_border_color ); ?>;
        }
    </style>
    <?php
    
    // Announcement Bar Colors (inline style for dynamic colors)
    $announcement_bar_bg    = get_theme_mod('announcement_bar_bg_color', '#fbbf24');
    $announcement_bar_text  = get_theme_mod('announcement_bar_text_color', '#000000');
    
    if (get_theme_mod('announcement_bar_enable', false)) :
    ?>
    <style type="text/css" id="announcement-bar-styles">
        .announcement-bar {
            background-color: <?php echo esc_attr($announcement_bar_bg); ?>;
            color: <?php echo esc_attr($announcement_bar_text); ?>;
        }
        .announcement-bar__text {
            color: <?php echo esc_attr($announcement_bar_text); ?>;
        }
        .announcement-bar__close {
            color: <?php echo esc_attr($announcement_bar_text); ?>;
        }
    </style>
    <?php endif;
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

/**
 * Add body class for announcement bar
 */
function vahidrajabloo_announcement_bar_body_class( $classes ) {
    if ( get_theme_mod( 'announcement_bar_enable', false ) && get_theme_mod( 'announcement_bar_text', '' ) ) {
        $classes[] = 'has-announcement-bar';
    }
    return $classes;
}
add_filter( 'body_class', 'vahidrajabloo_announcement_bar_body_class' );

/**
 * Configure SMTP for SendGrid
 */
function vahidrajabloo_smtp_config( $phpmailer ) {
    $sendgrid_key = defined('SENDGRID_API_KEY') ? SENDGRID_API_KEY : getenv('SENDGRID_API_KEY');
    
    if ( empty($sendgrid_key) ) {
        return; // Skip if no API key configured
    }
    
    $phpmailer->isSMTP();
    $phpmailer->Host       = 'smtp.sendgrid.net';
    $phpmailer->SMTPAuth   = true;
    $phpmailer->Port       = 587;
    $phpmailer->Username   = 'apikey';
    $phpmailer->Password   = $sendgrid_key;
    $phpmailer->SMTPSecure = 'tls';
    $phpmailer->From       = 'info@vahidrajabloo.com';
    $phpmailer->FromName   = 'Vahid Rajabloo';
}
add_action( 'phpmailer_init', 'vahidrajabloo_smtp_config' );

/**
 * Newsletter Signup - Database Storage with optional SendGrid
 */
function vahidrajabloo_newsletter_signup() {
    // Verify nonce
    if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'newsletter_nonce' ) ) {
        wp_send_json_error( [ 'message' => 'Security check failed.' ] );
    }
    
    $email = sanitize_email( $_POST['email'] ?? '' );
    
    if ( ! is_email( $email ) ) {
        wp_send_json_error( [ 'message' => 'Please enter a valid email address.' ] );
    }
    
    // Get existing subscribers
    $subscribers = get_option( 'vahidrajabloo_newsletter_subscribers', [] );
    
    // Check if already subscribed
    if ( in_array( $email, array_column( $subscribers, 'email' ) ) ) {
        wp_send_json_error( [ 'message' => 'You are already subscribed!' ] );
    }
    
    // Add new subscriber to database
    $subscribers[] = [
        'email'      => $email,
        'date'       => current_time( 'mysql' ),
        'ip'         => $_SERVER['REMOTE_ADDR'] ?? '',
    ];
    update_option( 'vahidrajabloo_newsletter_subscribers', $subscribers );
    
    // Optionally send to SendGrid if configured
    $api_key = defined('SENDGRID_API_KEY') ? SENDGRID_API_KEY : getenv('SENDGRID_API_KEY');
    
    if ( ! empty( $api_key ) ) {
        // SendGrid Marketing Contacts API
        wp_remote_request( 'https://api.sendgrid.com/v3/marketing/contacts', [
            'method'  => 'PUT',
            'headers' => [
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type'  => 'application/json',
            ],
            'body'    => json_encode([
                'contacts' => [
                    [ 'email' => $email ]
                ]
            ]),
            'timeout' => 15,
        ]);
    }
    
    wp_send_json_success( [ 'message' => 'Thank you for subscribing!' ] );
}
add_action( 'wp_ajax_newsletter_signup', 'vahidrajabloo_newsletter_signup' );
add_action( 'wp_ajax_nopriv_newsletter_signup', 'vahidrajabloo_newsletter_signup' );

/**
 * Admin Menu for Newsletter Subscribers
 */
function vahidrajabloo_newsletter_admin_menu() {
    add_menu_page(
        'Newsletter Subscribers',
        'Newsletter',
        'manage_options',
        'newsletter-subscribers',
        'vahidrajabloo_newsletter_admin_page',
        'dashicons-email-alt',
        30
    );
}
add_action( 'admin_menu', 'vahidrajabloo_newsletter_admin_menu' );

/**
 * Newsletter Admin Page
 */
function vahidrajabloo_newsletter_admin_page() {
    $subscribers = get_option( 'vahidrajabloo_newsletter_subscribers', [] );
    ?>
    <div class="wrap">
        <h1>Newsletter Subscribers</h1>
        <p>Total subscribers: <strong><?php echo count( $subscribers ); ?></strong></p>
        
        <?php if ( ! empty( $subscribers ) ) : ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Email</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( array_reverse( $subscribers ) as $i => $sub ) : ?>
                        <tr>
                            <td><?php echo count( $subscribers ) - $i; ?></td>
                            <td><?php echo esc_html( $sub['email'] ); ?></td>
                            <td><?php echo esc_html( $sub['date'] ?? 'N/A' ); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <h3>Export as CSV</h3>
            <form method="post" action="">
                <?php wp_nonce_field( 'export_newsletter', 'newsletter_export_nonce' ); ?>
                <button type="submit" name="export_newsletter_csv" class="button button-primary">Export CSV</button>
            </form>
        <?php else : ?>
            <p>No subscribers yet.</p>
        <?php endif; ?>
    </div>
    <?php
    
    // Handle CSV export
    if ( isset( $_POST['export_newsletter_csv'] ) && wp_verify_nonce( $_POST['newsletter_export_nonce'] ?? '', 'export_newsletter' ) ) {
        vahidrajabloo_export_newsletter_csv( $subscribers );
    }
}

/**
 * Export Newsletter Subscribers as CSV
 */
function vahidrajabloo_export_newsletter_csv( $subscribers ) {
    if ( empty( $subscribers ) ) return;
    
    header( 'Content-Type: text/csv; charset=utf-8' );
    header( 'Content-Disposition: attachment; filename=newsletter-subscribers-' . date('Y-m-d') . '.csv' );
    
    $output = fopen( 'php://output', 'w' );
    fputcsv( $output, [ 'Email', 'Date' ] );
    
    foreach ( $subscribers as $sub ) {
        fputcsv( $output, [ $sub['email'], $sub['date'] ?? '' ] );
    }
    
    fclose( $output );
    exit;
}

