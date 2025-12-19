<?php
/**
 * Features Grid Block - Server-side render
 */

$section_title = $attributes['sectionTitle'] ?? '';
$section_subtitle = $attributes['sectionSubtitle'] ?? '';
$features = $attributes['features'] ?? [];
$columns = $attributes['columns'] ?? 3;

$wrapper_attributes = get_block_wrapper_attributes([
    'class' => 'vr-features-grid vr-features-grid--cols-' . esc_attr($columns),
]);
?>

<section <?php echo $wrapper_attributes; ?>>
    <div class="vr-features-grid__container">
        <?php if ($section_title || $section_subtitle) : ?>
            <div class="vr-features-grid__header">
                <?php if ($section_title) : ?>
                    <h2 class="vr-features-grid__title"><?php echo esc_html($section_title); ?></h2>
                <?php endif; ?>
                
                <?php if ($section_subtitle) : ?>
                    <p class="vr-features-grid__subtitle"><?php echo esc_html($section_subtitle); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($features)) : ?>
            <div class="vr-features-grid__grid">
                <?php foreach ($features as $feature) : ?>
                    <div class="vr-features-grid__item">
                        <?php if (!empty($feature['icon'])) : ?>
                            <span class="vr-features-grid__icon"><?php echo esc_html($feature['icon']); ?></span>
                        <?php endif; ?>
                        
                        <?php if (!empty($feature['title'])) : ?>
                            <h3 class="vr-features-grid__item-title"><?php echo esc_html($feature['title']); ?></h3>
                        <?php endif; ?>
                        
                        <?php if (!empty($feature['description'])) : ?>
                            <p class="vr-features-grid__item-description"><?php echo esc_html($feature['description']); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
