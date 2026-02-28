<?php
/**
 * Hero Block - Server-side render
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content.
 * @var WP_Block $block      Block instance.
 */

$title = $attributes['title'] ?? 'Welcome to VahidRajabloo';
$subtitle = $attributes['subtitle'] ?? '';
$primary_btn_text = $attributes['primaryButtonText'] ?? 'Get Started';
$primary_btn_url = $attributes['primaryButtonUrl'] ?? '#';
$secondary_btn_text = $attributes['secondaryButtonText'] ?? 'Learn More';
$secondary_btn_url = $attributes['secondaryButtonUrl'] ?? '#';
$image_url = $attributes['imageUrl'] ?? '';
$image_alt = $attributes['imageAlt'] ?? '';
$alignment = $attributes['alignment'] ?? 'center';
$show_secondary = $attributes['showSecondaryButton'] ?? true;

$wrapper_attributes = get_block_wrapper_attributes([
    'class' => 'vr-hero vr-hero--' . esc_attr($alignment),
]);

$secondary_aria_label = '';
if ($secondary_btn_text) {
    if ($secondary_btn_url === '#features') {
        $secondary_aria_label = __('Learn more about our accessible technology features', 'vahidrajabloo-theme');
    } elseif ($title) {
        $secondary_aria_label = sprintf(
            /* translators: 1: link text, 2: hero title. */
            __('%1$s - %2$s', 'vahidrajabloo-theme'),
            $secondary_btn_text,
            $title
        );
    } else {
        $secondary_aria_label = $secondary_btn_text;
    }
}
?>

<section <?php echo $wrapper_attributes; ?>>
    <div class="vr-hero__container">
        <div class="vr-hero__content">
            <?php if ($title) : ?>
                <h1 class="vr-hero__title"><?php echo esc_html($title); ?></h1>
            <?php endif; ?>
            
            <?php if ($subtitle) : ?>
                <p class="vr-hero__subtitle"><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>
            
            <div class="vr-hero__buttons">
                <?php if ($primary_btn_text) : ?>
                    <a href="<?php echo esc_url($primary_btn_url); ?>" class="btn btn--primary">
                        <?php echo esc_html($primary_btn_text); ?>
                    </a>
                <?php endif; ?>
                
                <?php if ($show_secondary && $secondary_btn_text) : ?>
                    <a href="<?php echo esc_url($secondary_btn_url); ?>" class="btn btn--secondary" aria-label="<?php echo esc_attr($secondary_aria_label); ?>">
                        <?php echo esc_html($secondary_btn_text); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if ($image_url) : ?>
            <div class="vr-hero__image-wrapper">
                <img 
                    src="<?php echo esc_url($image_url); ?>" 
                    alt="<?php echo esc_attr($image_alt); ?>" 
                    class="vr-hero__image"
                    loading="eager"
                />
            </div>
        <?php endif; ?>
    </div>
</section>
