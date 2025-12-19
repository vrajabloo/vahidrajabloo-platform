<?php
/**
 * The template for displaying product archive
 *
 * @package VahidRajabloo_Theme
 */

get_header();
?>

<section class="products-archive section">
    <div class="container">
        <!-- Archive Header -->
        <header class="page-header text-center">
            <span class="tagline">Products</span>
            <h1 class="page-title">Our Products</h1>
            <p class="page-description">Explore our range of products and services</p>
        </header>

        <!-- Category Filter -->
        <?php
        $categories = get_terms([
            'taxonomy'   => 'product_category',
            'hide_empty' => true,
        ]);
        
        if (!empty($categories) && !is_wp_error($categories)) :
        ?>
        <nav class="filter-nav mb-xl">
            <a href="<?php echo esc_url(get_post_type_archive_link('product')); ?>" class="filter-nav__item <?php echo !is_tax('product_category') ? 'is-active' : ''; ?>">
                All
            </a>
            <?php foreach ($categories as $cat) : ?>
                <a href="<?php echo esc_url(get_term_link($cat)); ?>" class="filter-nav__item <?php echo is_tax('product_category', $cat->term_id) ? 'is-active' : ''; ?>">
                    <?php echo esc_html($cat->name); ?>
                </a>
            <?php endforeach; ?>
        </nav>
        <?php endif; ?>

        <!-- Products Grid -->
        <?php if (have_posts()) : ?>
            <div class="products-grid grid grid--3">
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('product-card card'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium_large', ['class' => 'card__image']); ?>
                            </a>
                        <?php else : ?>
                            <div class="card__image card__image--placeholder"></div>
                        <?php endif; ?>
                        
                        <div class="card__content">
                            <?php
                            $cats = get_the_terms(get_the_ID(), 'product_category');
                            if ($cats && !is_wp_error($cats)) :
                            ?>
                                <span class="card__category"><?php echo esc_html($cats[0]->name); ?></span>
                            <?php endif; ?>
                            
                            <h2 class="card__title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            
                            <?php
                            $price = get_post_meta(get_the_ID(), '_product_price', true);
                            $currency = get_post_meta(get_the_ID(), '_product_currency', true) ?: 'تومان';
                            if ($price) :
                            ?>
                                <span class="product-card__price"><?php echo esc_html($price . ' ' . $currency); ?></span>
                            <?php endif; ?>
                            
                            <?php if (has_excerpt()) : ?>
                                <p class="card__text"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
                            <?php endif; ?>
                            
                            <a href="<?php the_permalink(); ?>" class="btn btn--link">View Details →</a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <nav class="pagination mt-xl">
                <?php the_posts_pagination(['mid_size' => 2]); ?>
            </nav>
        <?php else : ?>
            <p class="no-posts text-center">No products found.</p>
        <?php endif; ?>
    </div>
</section>

<?php
get_footer();
