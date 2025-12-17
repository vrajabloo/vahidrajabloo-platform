<?php
/**
 * Plugin Name: Laravel SSO Auto-Login
 * Description: Handles auto-login from Laravel Admin Panel
 * Version: 1.2.0
 * Author: VahidRajabloo Platform
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Start output buffering VERY early to prevent "headers already sent"
ob_start();

/**
 * Configuration - Dynamic based on environment
 */
function laravel_sso_get_api_url() {
    // Check if we're on local or production
    if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], '.local') !== false) {
        return 'http://app.vahidrajabloo.local/api/wp-validate-token';
    }
    return 'https://app.vahidrajabloo.com/api/wp-validate-token';
}

define('LARAVEL_SSO_WP_ADMIN_USERNAME', 'admin');
define('LARAVEL_SSO_WP_ADMIN_EMAIL', 'vahidrajablou87@gmail.com');

/**
 * Handle auto-login requests as early as possible
 */
add_action('muplugins_loaded', 'laravel_sso_handle_login');

function laravel_sso_handle_login() {
    // Check if this is an SSO request
    if (!isset($_GET['sso']) || $_GET['sso'] != '1') {
        return;
    }
    
    if (!isset($_GET['token']) || empty($_GET['token'])) {
        return;
    }
    
    $token = sanitize_text_field($_GET['token']);
    
    // Validate token length (basic check)
    if (strlen($token) !== 64) {
        wp_die('Invalid token format', 'SSO Error', ['response' => 400]);
    }
    
    // Validate token with Laravel API
    $apiUrl = laravel_sso_get_api_url();
    $response = wp_remote_get($apiUrl . '?token=' . urlencode($token), [
        'timeout' => 10,
        'sslverify' => false, // Allow for local development
    ]);
    
    if (is_wp_error($response)) {
        error_log('SSO Error: ' . $response->get_error_message());
        wp_die('Could not validate token: ' . $response->get_error_message(), 'SSO Error', ['response' => 500]);
    }
    
    $statusCode = wp_remote_retrieve_response_code($response);
    $body = json_decode(wp_remote_retrieve_body($response), true);
    
    if ($statusCode !== 200 || empty($body['valid'])) {
        $error = $body['error'] ?? 'Unknown error';
        wp_die('Invalid or expired token: ' . $error, 'SSO Error', ['response' => 401]);
    }
    
    // Get WordPress user by username
    $wpUsername = $body['wp_username'] ?? LARAVEL_SSO_WP_ADMIN_USERNAME;
    $user = get_user_by('login', $wpUsername);
    
    // If user doesn't exist, try by email
    if (!$user) {
        $user = get_user_by('email', LARAVEL_SSO_WP_ADMIN_EMAIL);
    }
    
    // If still no user, create one (admin only)
    if (!$user) {
        $userId = wp_create_user(
            LARAVEL_SSO_WP_ADMIN_USERNAME,
            wp_generate_password(32),
            LARAVEL_SSO_WP_ADMIN_EMAIL
        );
        
        if (is_wp_error($userId)) {
            error_log('SSO Error: Could not create user - ' . $userId->get_error_message());
            wp_die('Could not create user: ' . $userId->get_error_message(), 'SSO Error', ['response' => 500]);
        }
        
        // Set as administrator
        $user = get_user_by('id', $userId);
        $user->set_role('administrator');
    }
    
    // Clear any output buffer before setting headers
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    // Log the user in
    wp_clear_auth_cookie();
    wp_set_current_user($user->ID);
    wp_set_auth_cookie($user->ID, true);
    
    // Log the SSO login
    error_log('SSO Login: User ' . $user->user_login . ' logged in via Laravel SSO');
    
    // Redirect to WordPress admin using header() directly
    $adminUrl = admin_url();
    header('Location: ' . $adminUrl, true, 302);
    exit;
}
