<?php
/**
 * Template Name: Contact Page
 * Template Post Type: page
 *
 * Contact page template with Gravity Forms support
 *
 * @package VahidRajabloo_Theme
 */

get_header();
?>

<main id="main" class="site-main">
    <?php while (have_posts()) : the_post(); ?>
    
    <!-- Page Header -->
    <section class="page-header page-header--contact">
        <div class="container">
            <h1 class="page-header__title"><?php the_title(); ?></h1>
            <?php if (has_excerpt()) : ?>
                <p class="page-header__subtitle"><?php the_excerpt(); ?></p>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- Contact Content -->
    <section class="contact-section section">
        <div class="container">
            <div class="contact-grid">
                <!-- Contact Info -->
                <div class="contact-info">
                    <h2 class="contact-info__title"><?php echo esc_html(get_theme_mod('contact_info_title', 'Get in Touch')); ?></h2>
                    
                    <?php if (get_theme_mod('contact_email')) : ?>
                    <div class="contact-info__item">
                        <span class="contact-info__icon">📧</span>
                        <div>
                            <strong>Email</strong>
                            <a href="mailto:<?php echo esc_attr(get_theme_mod('contact_email')); ?>">
                                <?php echo esc_html(get_theme_mod('contact_email')); ?>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (get_theme_mod('contact_phone')) : ?>
                    <div class="contact-info__item">
                        <span class="contact-info__icon">📱</span>
                        <div>
                            <strong>Phone</strong>
                            <a href="tel:<?php echo esc_attr(get_theme_mod('contact_phone')); ?>">
                                <?php echo esc_html(get_theme_mod('contact_phone')); ?>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (get_theme_mod('contact_address')) : ?>
                    <div class="contact-info__item">
                        <span class="contact-info__icon">📍</span>
                        <div>
                            <strong>Address</strong>
                            <span><?php echo esc_html(get_theme_mod('contact_address')); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Social Links -->
                    <?php if (get_theme_mod('social_linkedin') || get_theme_mod('social_twitter') || get_theme_mod('social_instagram')) : ?>
                    <div class="contact-info__social">
                        <?php if (get_theme_mod('social_linkedin')) : ?>
                            <a href="<?php echo esc_url(get_theme_mod('social_linkedin')); ?>" target="_blank" rel="noopener">LinkedIn</a>
                        <?php endif; ?>
                        <?php if (get_theme_mod('social_twitter')) : ?>
                            <a href="<?php echo esc_url(get_theme_mod('social_twitter')); ?>" target="_blank" rel="noopener">Twitter</a>
                        <?php endif; ?>
                        <?php if (get_theme_mod('social_instagram')) : ?>
                            <a href="<?php echo esc_url(get_theme_mod('social_instagram')); ?>" target="_blank" rel="noopener">Instagram</a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Contact Form (Gravity Forms via content) -->
                <div class="contact-form">
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
    </section>
    
    <?php endwhile; ?>
</main>

<?php
get_footer();
