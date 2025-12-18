<?php
/**
 * The main template file
 *
 * @package VahidRajabloo_Theme
 */

get_header();
?>

<section class="archive-section section">
    <div class="container">
        <?php if ( is_home() && ! is_front_page() ) : ?>
            <header class="page-header text-center">
                <span class="tagline">Blog</span>
                <h1 class="page-title"><?php single_post_title(); ?></h1>
            </header>
        <?php endif; ?>

        <?php if ( have_posts() ) : ?>
            <div class="posts-grid grid grid--3">
                <?php while ( have_posts() ) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'card' ); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail( 'large', array( 'class' => 'card__image' ) ); ?>
                            </a>
                        <?php else : ?>
                            <div class="card__image card__image--placeholder"></div>
                        <?php endif; ?>
                        
                        <div class="card__content">
                            <?php
                            $categories = get_the_category();
                            if ( $categories ) :
                            ?>
                                <span class="card__category">
                                    <?php echo esc_html( $categories[0]->name ); ?>
                                </span>
                            <?php endif; ?>
                            
                            <h2 class="card__title">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                            
                            <p class="card__text">
                                <?php echo wp_trim_words( get_the_excerpt(), 20 ); ?>
                            </p>
                            
                            <a href="<?php the_permalink(); ?>" class="btn btn--link">
                                Read More →
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <nav class="pagination mt-xl">
                <?php
                the_posts_pagination( array(
                    'mid_size'  => 2,
                    'prev_text' => '← Previous',
                    'next_text' => 'Next →',
                ));
                ?>
            </nav>
        <?php else : ?>
            <div class="no-posts text-center">
                <h2>No posts found</h2>
                <p>Sorry, no posts matched your criteria.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php
get_footer();
