<?php
/**
 * Custom Gutenberg Blocks Registration
 *
 * @package VahidRajabloo_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register custom block category
 */
function vahidrajabloo_block_categories($categories) {
    return array_merge(
        [
            [
                'slug'  => 'vahidrajabloo',
                'title' => __('VahidRajabloo Blocks', 'vahidrajabloo-theme'),
                'icon'  => 'star-filled',
            ],
        ],
        $categories
    );
}
add_filter('block_categories_all', 'vahidrajabloo_block_categories', 10, 1);

/**
 * Register blocks
 */
function vahidrajabloo_register_blocks() {
    $blocks = [
        'hero',
        'cta',
        'text-image',
        'features-grid',
    ];

    foreach ($blocks as $block) {
        $block_path = VAHIDRAJABLOO_THEME_PATH . '/blocks/' . $block;
        
        if (file_exists($block_path . '/block.json')) {
            register_block_type($block_path);
        }
    }
}
add_action('init', 'vahidrajabloo_register_blocks');

/**
 * Enqueue block editor assets
 */
function vahidrajabloo_enqueue_block_editor_assets() {
    // Common editor styles
    wp_enqueue_style(
        'vahidrajabloo-block-editor-common',
        VAHIDRAJABLOO_THEME_URL . '/assets/css/block-editor.css',
        [],
        VAHIDRAJABLOO_THEME_VERSION
    );
}
add_action('enqueue_block_editor_assets', 'vahidrajabloo_enqueue_block_editor_assets');
