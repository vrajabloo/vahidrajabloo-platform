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
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-5.04-6.71l-2.75 3.54-1.96-2.36L6.5 17h11l-3.54-4.71z"/>
                            <circle cx="8" cy="8.5" r="1.5"/>
                        </svg>
                    </div>
                    <h3 class="about-links__card-title"><?php echo esc_html($gallery_title); ?></h3>
                    <p class="about-links__card-desc"><?php echo esc_html($gallery_desc); ?></p>
                    <span class="about-links__arrow">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/>
                        </svg>
                    </span>
                </a>
                
                <!-- Social Media Link -->
                <?php 
                $social_url = get_theme_mod('about_social_url', '#');
                $social_title = get_theme_mod('about_social_title', 'Social Media');
                $social_desc = get_theme_mod('about_social_desc', 'Connect with me online');
                ?>
                <a href="<?php echo esc_url($social_url ?: '#'); ?>" class="about-links__card about-links__card--social">
                    <div class="about-links__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92s2.92-1.31 2.92-2.92-1.31-2.92-2.92-2.92z"/>
                        </svg>
                    </div>
                    <h3 class="about-links__card-title"><?php echo esc_html($social_title); ?></h3>
                    <p class="about-links__card-desc"><?php echo esc_html($social_desc); ?></p>
                    <span class="about-links__arrow">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/>
                        </svg>
                    </span>
                </a>
                
                <!-- Interviews Link -->
                <?php 
                $interviews_url = get_theme_mod('about_interviews_url', '#');
                $interviews_title = get_theme_mod('about_interviews_title', 'Interviews');
                $interviews_desc = get_theme_mod('about_interviews_desc', 'Watch my interviews');
                ?>
                <a href="<?php echo esc_url($interviews_url ?: '#'); ?>" class="about-links__card about-links__card--interviews">
                    <div class="about-links__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3zm5.91-3c-.49 0-.9.36-.98.85C16.52 14.2 14.47 16 12 16s-4.52-1.8-4.93-4.15c-.08-.49-.49-.85-.98-.85-.61 0-1.09.54-1 1.14.49 3 2.89 5.35 5.91 5.78V20c0 .55.45 1 1 1s1-.45 1-1v-2.08c3.02-.43 5.42-2.78 5.91-5.78.1-.6-.39-1.14-1-1.14z"/>
                        </svg>
                    </div>
                    <h3 class="about-links__card-title"><?php echo esc_html($interviews_title); ?></h3>
                    <p class="about-links__card-desc"><?php echo esc_html($interviews_desc); ?></p>
                    <span class="about-links__arrow">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/>
                        </svg>
                    </span>
                </a>
                
                <!-- Speeches Link -->
                <?php 
                $speeches_url = get_theme_mod('about_speeches_url', '#');
                $speeches_title = get_theme_mod('about_speeches_title', 'Speeches');
                $speeches_desc = get_theme_mod('about_speeches_desc', 'Listen to my speeches');
                ?>
                <a href="<?php echo esc_url($speeches_url ?: '#'); ?>" class="about-links__card about-links__card--speeches">
                    <div class="about-links__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>
                        </svg>
                    </div>
                    <h3 class="about-links__card-title"><?php echo esc_html($speeches_title); ?></h3>
                    <p class="about-links__card-desc"><?php echo esc_html($speeches_desc); ?></p>
                    <span class="about-links__arrow">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/>
                        </svg>
                    </span>
                </a>
                
            </div>
        </div>
    </section>
    
    <?php endwhile; ?>
</main>

<?php
get_footer();
?>
