<?php
/**
 * The template for displaying all single posts
 *
 * @package VahidRajabloo_Theme
 */

get_header();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post' ); ?>>
    <?php while ( have_posts() ) : the_post(); ?>
        
        <!-- Post Header -->
        <header class="post-header section">
            <div class="container container--narrow">
                <?php
                $categories = get_the_category();
                if ( $categories ) :
                ?>
                    <span class="tagline">
                        <?php echo esc_html( $categories[0]->name ); ?>
                    </span>
                <?php endif; ?>
                
                <h1 class="post-title"><?php the_title(); ?></h1>
                
                <div class="post-meta flex flex--gap-lg">
                    <span class="post-author">
                        By <?php the_author(); ?>
                    </span>
                    <span class="post-date">
                        <?php echo get_the_date(); ?>
                    </span>
                    <span class="post-reading-time">
                        <?php
                        $content = get_the_content();
                        $word_count = str_word_count( strip_tags( $content ) );
                        $reading_time = ceil( $word_count / 200 );
                        echo $reading_time . ' min read';
                        ?>
                    </span>
                </div>
            </div>
        </header>

        <!-- Featured Image -->
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="post-featured-image">
                <div class="container">
                    <?php the_post_thumbnail( 'full', array( 'class' => 'featured-image' ) ); ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Post Content -->
        <div class="post-content section">
            <div class="container container--narrow">
                <?php the_content(); ?>
            </div>
        </div>

        <!-- Post Footer -->
        <footer class="post-footer section--gray">
            <div class="container container--narrow">
                <!-- Tags -->
                <?php
                $tags = get_the_tags();
                if ( $tags ) :
                ?>
                    <div class="post-tags flex flex--gap-sm mb-lg">
                        <?php foreach ( $tags as $tag ) : ?>
                            <a href="<?php echo get_tag_link( $tag->term_id ); ?>" class="tag">
                                #<?php echo esc_html( $tag->name ); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Share -->
                <div class="post-share flex flex--between">
                    <span>Share this article:</span>
                    <div class="share-links flex flex--gap-md">
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( get_permalink() ); ?>&text=<?php echo urlencode( get_the_title() ); ?>" target="_blank" rel="noopener" class="share-link">
                            Twitter
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode( get_permalink() ); ?>" target="_blank" rel="noopener" class="share-link">
                            LinkedIn
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>" target="_blank" rel="noopener" class="share-link">
                            Facebook
                        </a>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Author Bio -->
        <section class="author-bio section">
            <div class="container container--narrow">
                <div class="author-card flex flex--gap-lg">
                    <div class="author-avatar">
                        <?php
                        echo get_avatar(
                            get_the_author_meta( 'ID' ),
                            80,
                            '',
                            sprintf(
                                /* translators: %s: Author display name. */
                                __( 'Avatar of %s', 'vahidrajabloo-theme' ),
                                get_the_author()
                            )
                        );
                        ?>
                    </div>
                    <div class="author-info">
                        <h4 class="author-name"><?php the_author(); ?></h4>
                        <p class="author-description"><?php echo get_the_author_meta( 'description' ); ?></p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Post Navigation -->
        <nav class="post-navigation section--gray">
            <div class="container">
                <div class="nav-links flex flex--between">
                    <?php
                    $prev_post = get_previous_post();
                    $next_post = get_next_post();
                    ?>
                    <?php if ( $prev_post ) : ?>
                        <a href="<?php echo get_permalink( $prev_post ); ?>" class="nav-link nav-link--prev">
                            <span class="nav-label">← Previous</span>
                            <span class="nav-title"><?php echo get_the_title( $prev_post ); ?></span>
                        </a>
                    <?php endif; ?>
                    <?php if ( $next_post ) : ?>
                        <a href="<?php echo get_permalink( $next_post ); ?>" class="nav-link nav-link--next">
                            <span class="nav-label">Next →</span>
                            <span class="nav-title"><?php echo get_the_title( $next_post ); ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>

    <?php endwhile; ?>
</article>

<?php
get_footer();
