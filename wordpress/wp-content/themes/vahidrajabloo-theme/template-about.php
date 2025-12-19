<?php
/**
 * Template Name: About Page
 * Template Post Type: page
 *
 * Custom About page template
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
    
    <?php endwhile; ?>
</main>

<?php
get_footer();
