<?php
/**
 * The template for displaying archive pages
 *
 * @package VahidRajabloo_Theme
 */

get_header();
?>

<section class="archive-section section">
    <div class="container">
        <header class="page-header text-center">
            <span class="tagline">
                <?php
                if ( is_category() ) {
                    echo 'Category';
                } elseif ( is_tag() ) {
                    echo 'Tag';
                } elseif ( is_author() ) {
                    echo 'Author';
                } elseif ( is_date() ) {
                    echo 'Archive';
                } else {
                    echo 'Posts';
                }
                ?>
            </span>
            <h1 class="page-title"><?php the_archive_title(); ?></h1>
            <?php the_archive_description( '<p class="archive-description">', '</p>' ); ?>
        </header>

        <?php if ( have_posts() ) : ?>
            <div class="posts-grid grid grid--3">
                <?php while ( have_posts() ) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'card' ); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail( 'large', array( 'class' => 'card__image' ) ); ?>
                            </a>
                        <?php endif; ?>
                        
                        <div class="card__content">
                            <h2 class="card__title">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                            <p class="card__text"><?php echo wp_trim_words( get_the_excerpt(), 20 ); ?></p>
                            <a
                                href="<?php the_permalink(); ?>"
                                class="btn btn--link"
                                aria-label="<?php echo esc_attr( sprintf( __( 'Read more - %s', 'vahidrajabloo-theme' ), get_the_title() ) ); ?>"
                            >Read More →</a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <nav class="pagination mt-xl">
                <?php the_posts_pagination( array( 'mid_size' => 2 ) ); ?>
            </nav>
        <?php else : ?>
            <p class="no-posts text-center">No posts found.</p>
        <?php endif; ?>
    </div>
</section>

<?php
get_footer();
