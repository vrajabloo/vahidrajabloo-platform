<?php
/**
 * The template for displaying all pages
 *
 * @package VahidRajabloo_Theme
 */

get_header();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
    <?php while ( have_posts() ) : the_post(); ?>
        
        <?php
        // Check if page is built with Elementor
        if ( defined( 'ELEMENTOR_VERSION' ) && \Elementor\Plugin::$instance->documents->get( get_the_ID() )->is_built_with_elementor() ) {
            // Elementor handles the content
            the_content();
        } else {
        ?>
            <!-- Page Header -->
            <header class="page-header section">
                <div class="container container--narrow text-center">
                    <h1 class="page-title"><?php the_title(); ?></h1>
                </div>
            </header>

            <!-- Page Content -->
            <div class="page-body section">
                <div class="container container--narrow">
                    <?php the_content(); ?>
                </div>
            </div>
        <?php } ?>

    <?php endwhile; ?>
</article>

<?php
get_footer();
