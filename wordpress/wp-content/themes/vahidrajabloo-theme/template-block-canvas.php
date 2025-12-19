<?php
/**
 * Template Name: Block Canvas
 * Template Post Type: page
 *
 * A full-width template for block-based page design
 *
 * @package VahidRajabloo_Theme
 */

get_header();
?>

<main id="main" class="site-main site-main--blocks">
    <?php
    while (have_posts()) :
        the_post();
        the_content();
    endwhile;
    ?>
</main>

<?php
get_footer();
