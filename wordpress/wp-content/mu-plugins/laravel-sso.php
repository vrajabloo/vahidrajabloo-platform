<?php
/**
 * Plugin Name: Laravel SSO Auto-Login
 * Description: Handles auto-login from Laravel Admin Panel
 * Version: 1.0.0
 * Author: VahidRajabloo Platform
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Configuration
 */
define('LARAVEL_API_URL', 'https://app.vahidrajabloo.com/api/wp-validate-token');
define('WP_ADMIN_USERNAME', 'admin');
define('WP_ADMIN_EMAIL', 'vahidrajablou87@gmail.com');

/**
 * Handle auto-login requests
 */
add_action('init', function() {
    // Only handle requests to wp-auto-login.php or with token parameter
    if (!isset($_GET['token']) || empty($_GET['token'])) {
        return;
    }
    
    // Check if this is the auto-login request
    $requestUri = $_SERVER['REQUEST_URI'] ?? '';
    if (strpos($requestUri, 'wp-auto-login') === false && !isset($_GET['sso'])) {
        return;
    }
    
    $token = sanitize_text_field($_GET['token']);
    
    // Validate token length (basic check)
    if (strlen($token) !== 64) {
        wp_die('Invalid token format', 'SSO Error', ['response' => 400]);
    }
    
    // Validate token with Laravel API
    $response = wp_remote_get(LARAVEL_API_URL . '?token=' . urlencode($token), [
        'timeout' => 10,
        'sslverify' => true,
    ]);
    
    if (is_wp_error($response)) {
        error_log('SSO Error: ' . $response->get_error_message());
        wp_die('Could not validate token', 'SSO Error', ['response' => 500]);
    }
    
    $statusCode = wp_remote_retrieve_response_code($response);
    $body = json_decode(wp_remote_retrieve_body($response), true);
    
    if ($statusCode !== 200 || empty($body['valid'])) {
        wp_die('Invalid or expired token', 'SSO Error', ['response' => 401]);
    }
    
    // Get WordPress user by username
    $wpUsername = $body['wp_username'] ?? WP_ADMIN_USERNAME;
    $user = get_user_by('login', $wpUsername);
    
    // If user doesn't exist, try by email
    if (!$user) {
        $user = get_user_by('email', WP_ADMIN_EMAIL);
    }
    
    // If still no user, create one (admin only)
    if (!$user) {
        $userId = wp_create_user(
            WP_ADMIN_USERNAME,
            wp_generate_password(32),
            WP_ADMIN_EMAIL
        );
        
        if (is_wp_error($userId)) {
            error_log('SSO Error: Could not create user - ' . $userId->get_error_message());
            wp_die('Could not create user', 'SSO Error', ['response' => 500]);
        }
        
        // Set as administrator
        $user = get_user_by('id', $userId);
        $user->set_role('administrator');
    }
    
    // Log the user in
    wp_clear_auth_cookie();
    wp_set_current_user($user->ID);
    wp_set_auth_cookie($user->ID, true);
    
    // Log the SSO login
    error_log('SSO Login: User ' . $user->user_login . ' logged in via Laravel SSO');
    
    // Redirect to WordPress admin
    wp_safe_redirect(admin_url());
    exit;
});

/**
 * Add rewrite rule for clean URL
 */
add_action('init', function() {
    add_rewrite_rule(
        '^wp-auto-login/?$',
        'index.php?sso=1',
        'top'
    );
});

add_filter('query_vars', function($vars) {
    $vars[] = 'sso';
    return $vars;
});

/**
 * Handle the clean URL
 */
add_action('template_redirect', function() {
    if (get_query_var('sso') && isset($_GET['token'])) {
        // The init hook above will handle this
    }
});
