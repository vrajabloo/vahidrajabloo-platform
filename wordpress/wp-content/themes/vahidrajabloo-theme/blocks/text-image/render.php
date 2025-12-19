<?php
/**
 * Text + Image Block - Server-side render
 */

$title = $attributes['title'] ?? '';
$description = $attributes['description'] ?? '';
$button_text = $attributes['buttonText'] ?? '';
$button_url = $attributes['buttonUrl'] ?? '#';
$image_url = $attributes['imageUrl'] ?? '';
$image_alt = $attributes['imageAlt'] ?? '';
$image_position = $attributes['imagePosition'] ?? 'right';

$wrapper_attributes = get_block_wrapper_attributes([
    'class' => 'vr-text-image vr-text-image--image-' . esc_attr($image_position),
]);
?>

<section <?php echo $wrapper_attributes; ?>>
    <div class="vr-text-image__container">
        <div class="vr-text-image__content">
            <?php if ($title) : ?>
                <h2 class="vr-text-image__title"><?php echo esc_html($title); ?></h2>
            <?php endif; ?>
            
            <?php if ($description) : ?>
                <div class="vr-text-image__description">
                    <?php echo wp_kses_post($description); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($button_text) : ?>
                <div class="vr-text-image__action">
                    <a href="<?php echo esc_url($button_url); ?>" class="btn btn--primary">
                        <?php echo esc_html($button_text); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if ($image_url) : ?>
            <div class="vr-text-image__image-wrapper">
                <img 
                    src="<?php echo esc_url($image_url); ?>" 
                    alt="<?php echo esc_attr($image_alt); ?>" 
                    class="vr-text-image__image"
                    loading="lazy"
                />
            </div>
        <?php endif; ?>
    </div>
</section>
