<?php
/**
 * Template Name: Productos - Otros
 * Muestra productos que NO pertenecen a las categorías de cómputo
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

// Whitelist usada para cómputo (la invertiremos)
$computo_slugs = array( 'computo', 'computación', 'computacion', 'computadores', 'ordenadores', 'laptops', 'notebooks', 'pc' );

// Encontrar categorías existentes que coincidan con la whitelist
$existing = get_terms( array(
    'taxonomy' => 'product_cat',
    'hide_empty' => false,
    'slug' => $computo_slugs,
) );
$exclude_slugs = array();
if ( ! is_wp_error( $existing ) && ! empty( $existing ) ) {
    $exclude_slugs = wp_list_pluck( $existing, 'slug' );
}

// Query para productos excluyendo las categorías de cómputo
$paged = max(1, get_query_var('paged') ? get_query_var('paged') : 1);
$args = array(
    'post_type' => 'product',
    'post_status' => 'publish',
    'posts_per_page' => 12,
    'paged' => $paged,
);

if ( ! empty( $exclude_slugs ) ) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $exclude_slugs,
            'operator' => 'NOT IN',
        ),
    );
}

$products = new WP_Query( $args );
?>

<main class="cc-products-page">
    <div class="container">
        <header class="cc-page-header">
            <h1 class="cc-page-title">Productos - Otros</h1>
            <p class="cc-products-count"><?php echo esc_html( $products->found_posts ) . ' encontrados'; ?></p>
        </header>

        <section class="cc-main-content">
            <?php if ( $products->have_posts() ) : ?>
                <div class="cc-products-grid">
                    <?php while ( $products->have_posts() ) : $products->the_post(); global $product; ?>
                        <article class="cc-product-card">
                            <a href="<?php the_permalink(); ?>">
                                <div class="cc-product-image-wrapper">
                                    <?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'medium', array( 'class' => 'cc-product-image' ) ); else: ?>
                                        <img class="cc-product-image" src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" alt="" />
                                    <?php endif; ?>
                                </div>
                                <h3 class="cc-product-name"><?php the_title(); ?></h3>
                            </a>
                            <div class="cc-price"><?php echo wp_kses_post( $product ? $product->get_price_html() : '' ); ?></div>
                            <div class="cc-add-to-cart">
                                <?php if ( function_exists('woocommerce_template_loop_add_to_cart') ) {
                                    woocommerce_template_loop_add_to_cart();
                                } else {
                                    echo '<a class="button" href="' . get_permalink() . '">Ver</a>';
                                } ?>
                            </div>
                        </article>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>

                <div class="cc-pagination">
                    <?php echo paginate_links( array( 'total' => $products->max_num_pages, 'current' => $paged ) ); ?>
                </div>

            <?php else: ?>
                <div class="cc-no-results">No se encontraron productos en esta categoría.</div>
            <?php endif; ?>
        </section>
    </div>
</main>

<?php
get_footer();
