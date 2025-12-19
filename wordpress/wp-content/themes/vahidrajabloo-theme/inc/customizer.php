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
function vahidrajabloo_customize_register($wp_customize) {
    
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
}
add_action('customize_register', 'vahidrajabloo_customize_register');
