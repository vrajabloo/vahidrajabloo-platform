<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package VahidRajabloo_Theme
 */

get_header();
?>

<section class="error-404 section">
    <div class="container text-center">
        <div class="error-content">
            <h1 class="error-title">404</h1>
            <h2 class="error-subtitle">Page Not Found</h2>
            <p class="error-text">
                Sorry, we couldn't find the page you're looking for. 
                Perhaps you've mistyped the URL? Be sure to check your spelling.
            </p>
            <div class="error-actions flex flex--center flex--gap-md">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--primary">
                    Go Home
                </a>
                <a href="javascript:history.back()" class="btn btn--secondary">
                    Go Back
                </a>
            </div>
        </div>
    </div>
</section>

<?php
get_footer();
