<?php
/**
 * The template for displaying the front page
 *
 * @package VahidRajabloo_Theme
 */

get_header();

// Check if Elementor is editing this page
if ( defined( 'ELEMENTOR_VERSION' ) && \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
    the_content();
} else {
?>

<!-- Hero Section -->
<section class="hero-section section">
    <div class="container">
        <div class="hero-grid grid grid--2">
            <div class="hero-content">
                <h1 class="hero-title">
                    <?php echo esc_html( get_theme_mod( 'hero_headline', 'Building systems that serve everyone' ) ); ?>
                </h1>
                <p class="hero-text">
                    <?php echo esc_html( get_theme_mod( 'hero_subtext', 'We create digital experiences that make a difference in people\'s lives.' ) ); ?>
                </p>
                <div class="hero-actions flex flex--gap-md">
                    <?php
                    $btn_primary_text = get_theme_mod( 'hero_btn_primary_text', 'Get Started' );
                    $btn_primary_url = get_theme_mod( 'hero_btn_primary_url', '#' );
                    ?>
                    <a href="<?php echo esc_url( $btn_primary_url ); ?>" class="btn btn--primary">
                        <?php echo esc_html( $btn_primary_text ); ?>
                    </a>
                    <a href="#features" class="btn btn--secondary">
                        Learn More
                    </a>
                </div>
            </div>
            <div class="hero-image">
                <?php
                $hero_image = get_theme_mod( 'hero_image', '' );
                if ( $hero_image ) {
                    echo '<img src="' . esc_url( $hero_image ) . '" alt="Hero Image">';
                } else {
                    echo '<div class="hero-placeholder"></div>';
                }
                ?>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section section section--gray" id="features">
    <div class="container">
        <div class="section-header text-center">
            <span class="tagline">
                <?php echo esc_html( get_theme_mod( 'features_tagline', 'Features' ) ); ?>
            </span>
            <h2 class="section-title">
                <?php echo esc_html( get_theme_mod( 'features_title', 'What we build and why it matters' ) ); ?>
            </h2>
        </div>
        
        <div class="features-grid grid grid--4">
            <?php for ( $i = 1; $i <= 4; $i++ ) : ?>
                <div class="feature-card">
                    <?php
                    $icon = get_theme_mod( "feature_{$i}_icon", '' );
                    if ( $icon ) {
                        echo '<img src="' . esc_url( $icon ) . '" alt="" class="feature-card__icon">';
                    } else {
                        echo '<div class="feature-card__icon feature-icon-placeholder"></div>';
                    }
                    ?>
                    <h3 class="feature-card__title">
                        <?php echo esc_html( get_theme_mod( "feature_{$i}_title", "Feature {$i}" ) ); ?>
                    </h3>
                    <p class="feature-card__text">
                        <?php echo esc_html( get_theme_mod( "feature_{$i}_text", 'Feature description goes here.' ) ); ?>
                    </p>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</section>

<!-- Stories/Blog Section -->
<section class="stories-section section" id="stories">
    <div class="container">
        <div class="section-header text-center">
            <span class="tagline">Stories</span>
            <h2 class="section-title">From the field</h2>
        </div>
        
        <div class="stories-grid grid grid--3">
            <?php
            $args = array(
                'post_type'      => 'post',
                'posts_per_page' => 3,
                'post_status'    => 'publish',
            );
            $query = new WP_Query( $args );
            
            if ( $query->have_posts() ) :
                while ( $query->have_posts() ) : $query->the_post();
            ?>
                <article class="card story-card">
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
                        
                        <h3 class="card__title">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        
                        <p class="card__text">
                            <?php echo wp_trim_words( get_the_excerpt(), 15 ); ?>
                        </p>
                        
                        <a href="<?php the_permalink(); ?>" class="btn btn--link">
                            Read More →
                        </a>
                    </div>
                </article>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
            ?>
                <p class="no-posts">No posts found.</p>
            <?php endif; ?>
        </div>
        
        <div class="section-footer text-center mt-xl">
            <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="btn btn--secondary">
                View All Stories
            </a>
        </div>
    </div>
</section>

<?php
}

get_footer();
