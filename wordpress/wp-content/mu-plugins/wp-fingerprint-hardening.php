<?php
/**
 * Plugin Name: WP Fingerprint Hardening
 * Description: Reduces publicly exposed WordPress fingerprints for automated scanners.
 * Version: 1.0.0
 * Author: VahidRajabloo Platform
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Remove discovery/version traces from frontend output.
 */
add_action('init', static function (): void {
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'wp_shortlink_wp_head', 10);
    remove_action('template_redirect', 'wp_shortlink_header', 11);
    remove_action('wp_head', 'rest_output_link_wp_head', 10);
    remove_action('template_redirect', 'rest_output_link_header', 11);
    remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
    remove_action('wp_head', 'wp_oembed_add_host_js');
    remove_action('wp_head', 'feed_links_extra', 3);
}, 1);

add_filter('the_generator', '__return_empty_string');
add_filter('xmlrpc_enabled', '__return_false');

/**
 * Disable Elementor generator meta tag if Elementor is installed.
 */
add_action('init', static function (): void {
    if (!function_exists('get_option') || !function_exists('update_option')) {
        return;
    }

    if (get_option('elementor_meta_generator_tag') !== '1') {
        update_option('elementor_meta_generator_tag', '1', false);
    }
}, 5);

/**
 * Keep REST API private: only authenticated users can access it.
 */
add_filter('rest_authentication_errors', static function ($result) {
    if (!empty($result)) {
        return $result;
    }

    if (is_user_logged_in()) {
        return $result;
    }

    return new WP_Error('not_found', 'Not Found', ['status' => 404]);
}, 99);

/**
 * Hide pingback and REST relation headers that expose WordPress internals.
 */
add_filter('wp_headers', static function (array $headers): array {
    unset($headers['X-Pingback'], $headers['x-pingback'], $headers['Link']);
    return $headers;
});

/**
 * Remove `?ver=` query strings from asset URLs.
 */
add_filter('style_loader_src', 'vrj_remove_wp_asset_version', 9999);
add_filter('script_loader_src', 'vrj_remove_wp_asset_version', 9999);

function vrj_remove_wp_asset_version(string $src): string {
    if ($src === '') {
        return $src;
    }

    $parts = wp_parse_url($src);
    if (!isset($parts['query'])) {
        return $src;
    }

    parse_str($parts['query'], $query);
    if (!isset($query['ver'])) {
        return $src;
    }

    unset($query['ver']);

    $base = strtok($src, '?');
    if (!is_string($base)) {
        return $src;
    }

    return empty($query) ? $base : $base . '?' . http_build_query($query);
}

/**
 * Remove default emoji scripts/styles that commonly leak WP defaults.
 */
add_action('init', static function (): void {
    remove_action('wp_enqueue_scripts', 'wp_enqueue_emoji_styles');
    remove_action('admin_enqueue_scripts', 'wp_enqueue_emoji_styles');
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}, 20);

/**
 * Obfuscate public HTML output to avoid exposing default WordPress paths.
 */
add_action('template_redirect', static function (): void {
    if (is_admin()) {
        return;
    }

    if (defined('REST_REQUEST') && REST_REQUEST) {
        return;
    }

    ob_start('vrj_obfuscate_wp_fingerprint_output');
}, 0);

function vrj_obfuscate_wp_fingerprint_output(string $buffer): string {
    if ($buffer === '') {
        return $buffer;
    }

    $buffer = preg_replace('~<meta[^>]+name=[\'"]generator[\'"][^>]*>\s*~i', '', $buffer) ?? $buffer;

    return str_replace(
        [
            '/wp-content/',
            '/wp-includes/',
            '/wp-admin/admin-ajax.php',
            '/wp-admin/admin.php',
            '"/wp-admin/*"',
            'wp-content/',
            'wp-includes/',
        ],
        [
            '/assets/',
            '/core/',
            '/ajax-endpoint',
            '/admin',
            '"/admin/*"',
            'assets/',
            'core/',
        ],
        $buffer
    );
}
