<?php
/**
 * Product Custom Post Type Registration
 *
 * @package VahidRajabloo_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Product CPT
 */
function vahidrajabloo_register_product_cpt() {
    $labels = [
        'name'                  => _x('Products', 'Post type general name', 'vahidrajabloo-theme'),
        'singular_name'         => _x('Product', 'Post type singular name', 'vahidrajabloo-theme'),
        'menu_name'             => _x('Products', 'Admin Menu text', 'vahidrajabloo-theme'),
        'name_admin_bar'        => _x('Product', 'Add New on Toolbar', 'vahidrajabloo-theme'),
        'add_new'               => __('Add New', 'vahidrajabloo-theme'),
        'add_new_item'          => __('Add New Product', 'vahidrajabloo-theme'),
        'new_item'              => __('New Product', 'vahidrajabloo-theme'),
        'edit_item'             => __('Edit Product', 'vahidrajabloo-theme'),
        'view_item'             => __('View Product', 'vahidrajabloo-theme'),
        'all_items'             => __('All Products', 'vahidrajabloo-theme'),
        'search_items'          => __('Search Products', 'vahidrajabloo-theme'),
        'not_found'             => __('No products found.', 'vahidrajabloo-theme'),
        'not_found_in_trash'    => __('No products found in Trash.', 'vahidrajabloo-theme'),
        'featured_image'        => __('Product Image', 'vahidrajabloo-theme'),
        'set_featured_image'    => __('Set product image', 'vahidrajabloo-theme'),
        'remove_featured_image' => __('Remove product image', 'vahidrajabloo-theme'),
        'use_featured_image'    => __('Use as product image', 'vahidrajabloo-theme'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => ['slug' => 'products', 'with_front' => false],
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-cart',
        'supports'           => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'show_in_rest'       => true, // Enable Gutenberg
    ];

    register_post_type('vr_product', $args);
}
add_action('init', 'vahidrajabloo_register_product_cpt');

/**
 * Register Product Category Taxonomy
 */
function vahidrajabloo_register_product_taxonomy() {
    $labels = [
        'name'              => _x('Product Categories', 'taxonomy general name', 'vahidrajabloo-theme'),
        'singular_name'     => _x('Product Category', 'taxonomy singular name', 'vahidrajabloo-theme'),
        'search_items'      => __('Search Categories', 'vahidrajabloo-theme'),
        'all_items'         => __('All Categories', 'vahidrajabloo-theme'),
        'parent_item'       => __('Parent Category', 'vahidrajabloo-theme'),
        'parent_item_colon' => __('Parent Category:', 'vahidrajabloo-theme'),
        'edit_item'         => __('Edit Category', 'vahidrajabloo-theme'),
        'update_item'       => __('Update Category', 'vahidrajabloo-theme'),
        'add_new_item'      => __('Add New Category', 'vahidrajabloo-theme'),
        'new_item_name'     => __('New Category Name', 'vahidrajabloo-theme'),
        'menu_name'         => __('Categories', 'vahidrajabloo-theme'),
    ];

    $args = [
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'product-category'],
        'show_in_rest'      => true,
    ];

    register_taxonomy('product_category', ['vr_product'], $args);
}
add_action('init', 'vahidrajabloo_register_product_taxonomy');

/**
 * Add Product Meta Box for Price
 */
function vahidrajabloo_add_product_meta_boxes() {
    add_meta_box(
        'vahidrajabloo_product_details',
        __('Product Details', 'vahidrajabloo-theme'),
        'vahidrajabloo_product_details_callback',
        'vr_product',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'vahidrajabloo_add_product_meta_boxes');

/**
 * Product Meta Box Callback
 */
function vahidrajabloo_product_details_callback($post) {
    wp_nonce_field('vahidrajabloo_product_nonce', 'product_nonce');
    
    $price = get_post_meta($post->ID, '_product_price', true);
    $currency = get_post_meta($post->ID, '_product_currency', true) ?: 'تومان';
    $link = get_post_meta($post->ID, '_product_link', true);
    $link_text = get_post_meta($post->ID, '_product_link_text', true) ?: 'Learn More';
    ?>
    <p>
        <label for="product_price"><?php _e('Price', 'vahidrajabloo-theme'); ?></label>
        <input type="text" id="product_price" name="product_price" value="<?php echo esc_attr($price); ?>" class="widefat">
    </p>
    <p>
        <label for="product_currency"><?php _e('Currency', 'vahidrajabloo-theme'); ?></label>
        <input type="text" id="product_currency" name="product_currency" value="<?php echo esc_attr($currency); ?>" class="widefat">
    </p>
    <p>
        <label for="product_link"><?php _e('External Link (optional)', 'vahidrajabloo-theme'); ?></label>
        <input type="url" id="product_link" name="product_link" value="<?php echo esc_url($link); ?>" class="widefat">
    </p>
    <p>
        <label for="product_link_text"><?php _e('Link Text', 'vahidrajabloo-theme'); ?></label>
        <input type="text" id="product_link_text" name="product_link_text" value="<?php echo esc_attr($link_text); ?>" class="widefat">
    </p>
    <?php
}

/**
 * Save Product Meta
 */
function vahidrajabloo_save_product_meta($post_id) {
    if (!isset($_POST['product_nonce']) || !wp_verify_nonce($_POST['product_nonce'], 'vahidrajabloo_product_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['product_price'])) {
        update_post_meta($post_id, '_product_price', sanitize_text_field($_POST['product_price']));
    }
    if (isset($_POST['product_currency'])) {
        update_post_meta($post_id, '_product_currency', sanitize_text_field($_POST['product_currency']));
    }
    if (isset($_POST['product_link'])) {
        update_post_meta($post_id, '_product_link', esc_url_raw($_POST['product_link']));
    }
    if (isset($_POST['product_link_text'])) {
        update_post_meta($post_id, '_product_link_text', sanitize_text_field($_POST['product_link_text']));
    }
}
add_action('save_post_vr_product', 'vahidrajabloo_save_product_meta');
