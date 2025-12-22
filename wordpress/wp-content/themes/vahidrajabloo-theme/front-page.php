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
<section class="hero-section section section--mesh">
    <div class="container">
        <div class="hero-grid grid grid--2">
            <div class="hero-content animate-fade-in-up">
                <h1 class="hero-title text-gradient">
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
                    <a href="<?php echo esc_url( $btn_primary_url ); ?>" class="btn btn--premium">
                        <?php echo esc_html( $btn_primary_text ); ?>
                    </a>
                    <a href="#features" class="btn btn--outline-animated">
                        Learn More
                    </a>
                </div>
            </div>
            <div class="hero-image animate-fade-in-right delay-200">
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
            <?php for ( $i = 1; $i <= 4; $i++ ) : 
                $feature_link = get_theme_mod( "feature_{$i}_link", '' );
                $has_link = ! empty( $feature_link );
            ?>
                <?php if ( $has_link ) : ?>
                    <a href="<?php echo esc_url( $feature_link ); ?>" class="feature-card feature-card--premium feature-card--linked hover-lift">
                <?php else : ?>
                    <div class="feature-card feature-card--premium hover-lift">
                <?php endif; ?>
                    
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
                    
                <?php if ( $has_link ) : ?>
                    </a>
                <?php else : ?>
                    </div>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
    </div>
</section>

<!-- Products Section -->
<section class="products-showcase section" id="products">
    <div class="container">
        <div class="section-header text-center">
            <span class="tagline">
                <?php echo esc_html( get_theme_mod( 'products_tagline', 'Products' ) ); ?>
            </span>
            <h2 class="section-title">
                <?php echo esc_html( get_theme_mod( 'products_title', 'Our Products & Services' ) ); ?>
            </h2>
        </div>
        
        <div class="products-grid grid grid--3">
            <?php
            $products_query = new WP_Query([
                'post_type'      => 'vr_product',
                'posts_per_page' => 6,
                'post_status'    => 'publish',
                'orderby'        => 'menu_order date',
                'order'          => 'ASC',
            ]);
            
            if ( $products_query->have_posts() ) :
                while ( $products_query->have_posts() ) : $products_query->the_post();
                    $price = get_post_meta( get_the_ID(), '_product_price', true );
                    $currency = get_post_meta( get_the_ID(), '_product_currency', true ) ?: 'تومان';
                    $link = get_post_meta( get_the_ID(), '_product_link', true );
            ?>
                <article class="product-card card">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail( 'medium_large', [ 'class' => 'card__image' ] ); ?>
                        </a>
                    <?php else : ?>
                        <div class="card__image card__image--placeholder"></div>
                    <?php endif; ?>
                    
                    <div class="card__content">
                        <?php
                        $categories = get_the_terms( get_the_ID(), 'product_category' );
                        if ( $categories && ! is_wp_error( $categories ) ) :
                        ?>
                            <span class="card__category"><?php echo esc_html( $categories[0]->name ); ?></span>
                        <?php endif; ?>
                        
                        <h3 class="card__title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        
                        <?php if ( $price ) : ?>
                            <span class="product-card__price"><?php echo esc_html( $price . ' ' . $currency ); ?></span>
                        <?php endif; ?>
                        
                        <?php if ( has_excerpt() ) : ?>
                            <p class="card__text"><?php echo wp_trim_words( get_the_excerpt(), 12 ); ?></p>
                        <?php endif; ?>
                        
                        <a href="<?php the_permalink(); ?>" class="btn btn--link">View Details →</a>
                    </div>
                </article>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
            ?>
                <p class="no-posts">No products found. <a href="<?php echo admin_url('post-new.php?post_type=vr_product'); ?>">Add your first product</a></p>
            <?php endif; ?>
        </div>
        
        <div class="section-footer text-center mt-xl">
            <a href="<?php echo esc_url( get_post_type_archive_link( 'vr_product' ) ); ?>" class="btn btn--secondary">
                View All Products
            </a>
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
