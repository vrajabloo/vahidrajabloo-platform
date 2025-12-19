<?php
/**
 * CTA Block - Server-side render
 */

$title = $attributes['title'] ?? 'Ready to get started?';
$description = $attributes['description'] ?? '';
$button_text = $attributes['buttonText'] ?? 'Get Started';
$button_url = $attributes['buttonUrl'] ?? '#';
$variant = $attributes['variant'] ?? 'default';

$wrapper_attributes = get_block_wrapper_attributes([
    'class' => 'vr-cta vr-cta--' . esc_attr($variant),
]);
?>

<section <?php echo $wrapper_attributes; ?>>
    <div class="vr-cta__container">
        <div class="vr-cta__content">
            <?php if ($title) : ?>
                <h2 class="vr-cta__title"><?php echo esc_html($title); ?></h2>
            <?php endif; ?>
            
            <?php if ($description) : ?>
                <p class="vr-cta__description"><?php echo esc_html($description); ?></p>
            <?php endif; ?>
        </div>
        
        <?php if ($button_text) : ?>
            <div class="vr-cta__action">
                <a href="<?php echo esc_url($button_url); ?>" class="btn btn--primary">
                    <?php echo esc_html($button_text); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>
