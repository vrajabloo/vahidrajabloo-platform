<?php
/**
 * Template Name: About Page
 * Template Post Type: page
 *
 * Custom About page template with links section
 *
 * @package VahidRajabloo_Theme
 */

get_header();
?>

<main id="main" class="site-main">
    <?php while (have_posts()) : the_post(); ?>
    
    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1 class="page-header__title"><?php the_title(); ?></h1>
            <?php if (has_excerpt()) : ?>
                <p class="page-header__subtitle"><?php the_excerpt(); ?></p>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- Block Content -->
    <div class="page-content">
        <?php the_content(); ?>
    </div>
    
    <!-- About Links Section -->
    <section class="about-links">
        <div class="container">
            <h2 class="about-links__title"><?php echo esc_html(get_theme_mod('about_links_title', 'Explore More')); ?></h2>
            <div class="about-links__grid">
                
                <!-- Photo Gallery Link -->
                <?php 
                $gallery_url = get_theme_mod('about_gallery_url', '#');
                $gallery_title = get_theme_mod('about_gallery_title', 'Photo Gallery');
                $gallery_desc = get_theme_mod('about_gallery_desc', 'View my photo collection');
                ?>
                <a href="<?php echo esc_url($gallery_url ?: '#'); ?>" class="about-links__card about-links__card--gallery">
                    <div class="about-links__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <polyline points="21 15 16 10 5 21"></polyline>
                        </svg>
                    </div>
                    <h3 class="about-links__card-title"><?php echo esc_html($gallery_title); ?></h3>
                    <p class="about-links__card-desc"><?php echo esc_html($gallery_desc); ?></p>
                    <span class="about-links__arrow">→</span>
                </a>
                
                <!-- Social Media Link -->
                <?php 
                $social_url = get_theme_mod('about_social_url', '#');
                $social_title = get_theme_mod('about_social_title', 'Social Media');
                $social_desc = get_theme_mod('about_social_desc', 'Connect with me online');
                ?>
                <a href="<?php echo esc_url($social_url ?: '#'); ?>" class="about-links__card about-links__card--social">
                    <div class="about-links__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                        </svg>
                    </div>
                    <h3 class="about-links__card-title"><?php echo esc_html($social_title); ?></h3>
                    <p class="about-links__card-desc"><?php echo esc_html($social_desc); ?></p>
                    <span class="about-links__arrow">→</span>
                </a>
                
                <!-- Interviews Link -->
                <?php 
                $interviews_url = get_theme_mod('about_interviews_url', '#');
                $interviews_title = get_theme_mod('about_interviews_title', 'Interviews');
                $interviews_desc = get_theme_mod('about_interviews_desc', 'Watch my interviews');
                ?>
                <a href="<?php echo esc_url($interviews_url ?: '#'); ?>" class="about-links__card about-links__card--interviews">
                    <div class="about-links__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path>
                            <path d="M19 10v2a7 7 0 0 1-14 0v-2"></path>
                            <line x1="12" y1="19" x2="12" y2="23"></line>
                            <line x1="8" y1="23" x2="16" y2="23"></line>
                        </svg>
                    </div>
                    <h3 class="about-links__card-title"><?php echo esc_html($interviews_title); ?></h3>
                    <p class="about-links__card-desc"><?php echo esc_html($interviews_desc); ?></p>
                    <span class="about-links__arrow">→</span>
                </a>
                
                <!-- Speeches Link -->
                <?php 
                $speeches_url = get_theme_mod('about_speeches_url', '#');
                $speeches_title = get_theme_mod('about_speeches_title', 'Speeches');
                $speeches_desc = get_theme_mod('about_speeches_desc', 'Listen to my speeches');
                ?>
                <a href="<?php echo esc_url($speeches_url ?: '#'); ?>" class="about-links__card about-links__card--speeches">
                    <div class="about-links__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                            <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                        </svg>
                    </div>
                    <h3 class="about-links__card-title"><?php echo esc_html($speeches_title); ?></h3>
                    <p class="about-links__card-desc"><?php echo esc_html($speeches_desc); ?></p>
                    <span class="about-links__arrow">→</span>
                </a>
                
            </div>
        </div>
    </section>
    
    <?php endwhile; ?>
</main>

<?php
get_footer();
?>
