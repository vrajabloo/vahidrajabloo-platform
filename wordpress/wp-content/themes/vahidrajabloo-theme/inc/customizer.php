<?php
/**
 * Theme Customizer Settings
 *
 * @package VahidRajabloo_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add customizer settings
 */
function vahidrajabloo_additional_customizer_settings($wp_customize) {
    
    // Home Page Products Section
    $wp_customize->add_section('vahidrajabloo_home_products', [
        'title'    => __('Home Page - Products Section', 'vahidrajabloo-theme'),
        'priority' => 30,
    ]);

    // Products Section Tagline
    $wp_customize->add_setting('products_tagline', [
        'default'           => 'Products',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('products_tagline', [
        'label'   => __('Section Tagline', 'vahidrajabloo-theme'),
        'section' => 'vahidrajabloo_home_products',
        'type'    => 'text',
    ]);

    // Products Section Title
    $wp_customize->add_setting('products_title', [
        'default'           => 'Our Products & Services',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('products_title', [
        'label'   => __('Section Title', 'vahidrajabloo-theme'),
        'section' => 'vahidrajabloo_home_products',
        'type'    => 'text',
    ]);

    // Contact Info Section
    $wp_customize->add_section('vahidrajabloo_contact_info', [
        'title'    => __('Contact Information', 'vahidrajabloo-theme'),
        'priority' => 35,
    ]);

    // Contact Email
    $wp_customize->add_setting('contact_email', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_email',
    ]);
    $wp_customize->add_control('contact_email', [
        'label'   => __('Email Address', 'vahidrajabloo-theme'),
        'section' => 'vahidrajabloo_contact_info',
        'type'    => 'email',
    ]);

    // Contact Phone
    $wp_customize->add_setting('contact_phone', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('contact_phone', [
        'label'   => __('Phone Number', 'vahidrajabloo-theme'),
        'section' => 'vahidrajabloo_contact_info',
        'type'    => 'text',
    ]);

    // Contact Address
    $wp_customize->add_setting('contact_address', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('contact_address', [
        'label'   => __('Address', 'vahidrajabloo-theme'),
        'section' => 'vahidrajabloo_contact_info',
        'type'    => 'text',
    ]);

    // Contact Info Title
    $wp_customize->add_setting('contact_info_title', [
        'default'           => 'Get in Touch',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('contact_info_title', [
        'label'   => __('Contact Info Title', 'vahidrajabloo-theme'),
        'section' => 'vahidrajabloo_contact_info',
        'type'    => 'text',
    ]);

    // Social Links Section
    $wp_customize->add_section('vahidrajabloo_social_links', [
        'title'    => __('Social Links', 'vahidrajabloo-theme'),
        'priority' => 36,
    ]);

    // LinkedIn
    $wp_customize->add_setting('social_linkedin', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control('social_linkedin', [
        'label'   => __('LinkedIn URL', 'vahidrajabloo-theme'),
        'section' => 'vahidrajabloo_social_links',
        'type'    => 'url',
    ]);

    // Twitter/X
    $wp_customize->add_setting('social_twitter', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control('social_twitter', [
        'label'   => __('Twitter/X URL', 'vahidrajabloo-theme'),
        'section' => 'vahidrajabloo_social_links',
        'type'    => 'url',
    ]);

    // Instagram
    $wp_customize->add_setting('social_instagram', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control('social_instagram', [
        'label'   => __('Instagram URL', 'vahidrajabloo-theme'),
        'section' => 'vahidrajabloo_social_links',
        'type'    => 'url',
    ]);

    // Facebook
    $wp_customize->add_setting('social_facebook', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control('social_facebook', [
        'label'   => __('Facebook URL', 'vahidrajabloo-theme'),
        'section' => 'vahidrajabloo_social_links',
        'type'    => 'url',
    ]);
    // =========================================================================
    // About Page - Links Section
    // =========================================================================
    $wp_customize->add_section('vahidrajabloo_about_links', [
        'title'    => __('About Page - Links', 'vahidrajabloo-theme'),
        'priority' => 37,
    ]);

    // Section Title
    $wp_customize->add_setting('about_links_title', [
        'default'           => 'Explore More',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('about_links_title', [
        'label'   => __('Section Title', 'vahidrajabloo-theme'),
        'section' => 'vahidrajabloo_about_links',
        'type'    => 'text',
    ]);

    // Photo Gallery
    $wp_customize->add_setting('about_gallery_url', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control('about_gallery_url', [
        'label'   => __('Photo Gallery URL', 'vahidrajabloo-theme'),
        'section' => 'vahidrajabloo_about_links',
        'type'    => 'url',
    ]);

    $wp_customize->add_setting('about_gallery_title', [
        'default'           => 'Photo Gallery',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('about_gallery_title', [
        'label'   => __('Gallery Link Title', 'vahidrajabloo-theme'),
        'section' => 'vahidrajabloo_about_links',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('about_gallery_desc', [
        'default'           => 'View my photo collection',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('about_gallery_desc', [
        'label'   => __('Gallery Link Description', 'vahidrajabloo-theme'),
        'section' => 'vahidrajabloo_about_links',
        'type'    => 'text',
    ]);

    // Social Media
    $wp_customize->add_setting('about_social_url', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control('about_social_url', [
        'label'   => __('Social Media URL', 'vahidrajabloo-theme'),
        'section' => 'vahidrajabloo_about_links',
        'type'    => 'url',
    ]);

    $wp_customize->add_setting('about_social_title', [
        'default'           => 'Social Media',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('about_social_title', [
        'label'   => __('Social Link Title', 'vahidrajabloo-theme'),
        'section' => 'vahidrajabloo_about_links',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('about_social_desc', [
        'default'           => 'Connect with me online',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('about_social_desc', [
        'label'   => __('Social Link Description', 'vahidrajabloo-theme'),
        'section' => 'vahidrajabloo_about_links',
        'type'    => 'text',
    ]);

    // Interviews
    $wp_customize->add_setting('about_interviews_url', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control('about_interviews_url', [
        'label'   => __('Interviews URL', 'vahidrajabloo-theme'),
        'section' => 'vahidrajabloo_about_links',
        'type'    => 'url',
    ]);

    $wp_customize->add_setting('about_interviews_title', [
        'default'           => 'Interviews',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('about_interviews_title', [
        'label'   => __('Interviews Link Title', 'vahidrajabloo-theme'),
        'section' => 'vahidrajabloo_about_links',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('about_interviews_desc', [
        'default'           => 'Watch my interviews',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('about_interviews_desc', [
        'label'   => __('Interviews Link Description', 'vahidrajabloo-theme'),
        'section' => 'vahidrajabloo_about_links',
        'type'    => 'text',
    ]);

    // Speeches
    $wp_customize->add_setting('about_speeches_url', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control('about_speeches_url', [
        'label'   => __('Speeches URL', 'vahidrajabloo-theme'),
        'section' => 'vahidrajabloo_about_links',
        'type'    => 'url',
    ]);

    $wp_customize->add_setting('about_speeches_title', [
        'default'           => 'Speeches',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('about_speeches_title', [
        'label'   => __('Speeches Link Title', 'vahidrajabloo-theme'),
        'section' => 'vahidrajabloo_about_links',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('about_speeches_desc', [
        'default'           => 'Listen to my speeches',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('about_speeches_desc', [
        'label'   => __('Speeches Link Description', 'vahidrajabloo-theme'),
        'section' => 'vahidrajabloo_about_links',
        'type'    => 'text',
    ]);
}
add_action('customize_register', 'vahidrajabloo_additional_customizer_settings');
