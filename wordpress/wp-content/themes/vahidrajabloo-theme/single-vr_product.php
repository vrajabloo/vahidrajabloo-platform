<?php
/**
 * The template for displaying single product posts
 *
 * @package VahidRajabloo_Theme
 */

get_header();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('single-product'); ?>>
    <?php while (have_posts()) : the_post(); ?>
        
        <!-- Product Header -->
        <header class="product-header section">
            <div class="container">
                <div class="product-header__grid">
                    <!-- Product Image -->
                    <div class="product-image">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('large', ['class' => 'product-image__img']); ?>
                        <?php else : ?>
                            <div class="product-image__placeholder"></div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Product Info -->
                    <div class="product-info">
                        <?php
                        $categories = get_the_terms(get_the_ID(), 'product_category');
                        if ($categories && !is_wp_error($categories)) :
                        ?>
                            <span class="tagline"><?php echo esc_html($categories[0]->name); ?></span>
                        <?php endif; ?>
                        
                        <h1 class="product-title"><?php the_title(); ?></h1>
                        
                        <?php if (has_excerpt()) : ?>
                            <p class="product-excerpt"><?php the_excerpt(); ?></p>
                        <?php endif; ?>
                        
                        <?php
                        $price = get_post_meta(get_the_ID(), '_product_price', true);
                        $currency = get_post_meta(get_the_ID(), '_product_currency', true) ?: 'تومان';
                        if ($price) :
                        ?>
                            <div class="product-price">
                                <span class="product-price__amount"><?php echo esc_html($price); ?></span>
                                <span class="product-price__currency"><?php echo esc_html($currency); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php
                        $link = get_post_meta(get_the_ID(), '_product_link', true);
                        $link_text = get_post_meta(get_the_ID(), '_product_link_text', true) ?: 'Learn More';
                        ?>
                        <div class="product-actions">
                            <?php if ($link) : ?>
                                <a href="<?php echo esc_url($link); ?>" class="btn btn--primary" target="_blank" rel="noopener">
                                    <?php echo esc_html($link_text); ?>
                                </a>
                            <?php endif; ?>
                            <a href="<?php echo esc_url(get_post_type_archive_link('vr_product')); ?>" class="btn btn--secondary">
                                ← All Products
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Product Content -->
        <?php if (get_the_content()) : ?>
        <div class="product-content section">
            <div class="container container--narrow">
                <?php the_content(); ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Related Products -->
        <?php
        $related_args = [
            'post_type'      => 'vr_product',
            'posts_per_page' => 3,
            'post__not_in'   => [get_the_ID()],
            'orderby'        => 'rand',
        ];
        
        if ($categories && !is_wp_error($categories)) {
            $related_args['tax_query'] = [
                [
                    'taxonomy' => 'product_category',
                    'field'    => 'term_id',
                    'terms'    => $categories[0]->term_id,
                ],
            ];
        }
        
        $related_query = new WP_Query($related_args);
        
        if ($related_query->have_posts()) :
        ?>
        <section class="related-products section section--gray">
            <div class="container">
                <h2 class="section-title text-center">Related Products</h2>
                
                <div class="products-grid grid grid--3">
                    <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                        <article class="product-card card">
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium', ['class' => 'card__image']); ?>
                                </a>
                            <?php endif; ?>
                            
                            <div class="card__content">
                                <h3 class="card__title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                
                                <?php
                                $card_price = get_post_meta(get_the_ID(), '_product_price', true);
                                $card_currency = get_post_meta(get_the_ID(), '_product_currency', true) ?: 'تومان';
                                if ($card_price) :
                                ?>
                                    <span class="product-card__price"><?php echo esc_html($card_price . ' ' . $card_currency); ?></span>
                                <?php endif; ?>
                                
                                <a href="<?php the_permalink(); ?>" class="btn btn--link">View Details →</a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
        <?php
        wp_reset_postdata();
        endif;
        ?>

    <?php endwhile; ?>
</article>

<?php
get_footer();
