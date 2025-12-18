<?php
/**
 * Template Name: Elementor Full Width
 * Template Post Type: page
 *
 * A full-width page template for Elementor
 *
 * @package VahidRajabloo_Theme
 */

get_header();
?>

<div class="elementor-full-width">
    <?php
    while ( have_posts() ) :
        the_post();
        the_content();
    endwhile;
    ?>
</div>

<?php
get_footer();
