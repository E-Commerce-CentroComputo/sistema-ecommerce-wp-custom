<?php
/**
 * WooCommerce - Archive Product Override
 * Custom productos view focused on productos de cómputo
 * Location: wp-content/themes/blocksy-child/woocommerce/archive-product.php
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

// Prepara variables de consulta
$paged = max( 1, ( get_query_var('paged') ) ? get_query_var('paged') : 1 );
$per_page = apply_filters( 'cc_products_per_page', 12 );

// Whitelist de slugs/categorías que consideramos "cómputo"
$computo_slugs = array( 'computo', 'computación', 'computacion', 'computadores', 'ordenadores', 'laptops', 'notebooks', 'pc' );

// Parámetros GET (básicos)
$search = isset($_GET['s']) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
$price_min = isset($_GET['price_min']) ? floatval($_GET['price_min']) : 0;
$price_max = isset($_GET['price_max']) ? floatval($_GET['price_max']) : 0;
$cat_filter = isset($_GET['categoria']) ? sanitize_text_field(wp_unslash($_GET['categoria'])) : '';

// Construir tax_query para limitar a categorías de cómputo si ninguna categoría explícita es solicitada
$tax_query = array();
if ( ! empty( $cat_filter ) && $cat_filter !== 'all' ) {
    $tax_query[] = array(
        'taxonomy' => 'product_cat',
        'field'    => 'slug',
        'terms'    => array( $cat_filter ),
    );
} else {
    // Intentar encontrar categorías válidas existentes en el sitio
    $existing = get_terms( array(
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
        'slug' => $computo_slugs,
    ) );
    if ( ! is_wp_error( $existing ) && ! empty( $existing ) ) {
        $slugs = wp_list_pluck( $existing, 'slug' );
        $tax_query[] = array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $slugs,
        );
    }
}

// Meta query para rango de precio (meta _price)
$meta_query = array();
if ( $price_min > 0 ) {
    $meta_query[] = array(
        'key' => '_price',
        'value' => $price_min,
        'compare' => '>=',
        'type' => 'NUMERIC',
    );
}
if ( $price_max > 0 ) {
    $meta_query[] = array(
        'key' => '_price',
        'value' => $price_max,
        'compare' => '<=',
        'type' => 'NUMERIC',
    );
}

$args = array(
    'post_type' => 'product',
    'post_status' => 'publish',
    'posts_per_page' => $per_page,
    'paged' => $paged,
);

if ( ! empty( $search ) ) {
    $args['s'] = $search;
}
if ( ! empty( $tax_query ) ) {
    $args['tax_query'] = $tax_query;
}
if ( ! empty( $meta_query ) ) {
    $args['meta_query'] = $meta_query;
}

$products = new WP_Query( $args );

?>

<main class="cc-products-page">
    <div class="container">
        <div class="cc-breadcrumbs">
            <?php if ( function_exists('woocommerce_breadcrumb') ) { woocommerce_breadcrumb(); } ?>
        </div>

        <header class="cc-page-header">
            <h1 class="cc-page-title"><?php echo esc_html( apply_filters( 'woocommerce_page_title', 'Productos' ) ); ?></h1>
            <div class="cc-products-count">
                <?php printf( '%s productos', number_format_i18n( $products->found_posts ) ); ?>
            </div>
        </header>

        <div class="cc-productos-layout">
            <aside class="cc-sidebar">
                <div class="cc-filters">
                    <h4>Categorías</h4>
                    <div class="cc-categories-list">
                        <button class="cc-category-btn <?php echo $cat_filter === 'all' || empty($cat_filter) ? 'active' : ''; ?>" data-category="all">Todas</button>
                        <?php
                        // Lista preferida y ordenada para los filtros solicitados
                        $preferred = array(
                            'accessories' => array('label' => 'Accessories', 'candidates' => array('accessories','accessory')),
                            'jackets'     => array('label' => 'Jackets',     'candidates' => array('jackets','jacket')),
                            'sneakers'    => array('label' => 'Sneakers',    'candidates' => array('sneakers','sneaker')),
                            't-shirts'    => array('label' => 'T-Shirts',    'candidates' => array('t-shirts','t_shirts','tshirts')),
                        );

                        $rendered = 0;
                        foreach ( $preferred as $key => $meta ) {
                            $term = false;
                            foreach ( $meta['candidates'] as $candidate ) {
                                $t = get_term_by('slug', $candidate, 'product_cat');
                                if ( $t && ! is_wp_error( $t ) ) { $term = $t; break; }
                            }
                            if ( $term ) {
                                $active = ( $cat_filter === $term->slug ) ? 'active' : '';
                                echo '<button class="cc-category-btn ' . esc_attr($active) . '" data-category="' . esc_attr($term->slug) . '">' . esc_html($meta['label']) . '</button>';
                                $rendered++;
                            }
                        }

                        // Si no encontramos las categorías preferidas, mostramos las existentes como fallback
                        if ( $rendered === 0 ) {
                            $terms = get_terms( array('taxonomy' => 'product_cat', 'hide_empty' => true) );
                            if ( ! is_wp_error( $terms ) ) {
                                foreach ( $terms as $t ) {
                                    $active = ( $cat_filter === $t->slug ) ? 'active' : '';
                                    echo '<button class="cc-category-btn ' . esc_attr($active) . '" data-category="' . esc_attr($t->slug) . '">' . esc_html($t->name) . '</button>';
                                }
                            }
                        }
                        ?>
                    </div>

                    <h4>Precio</h4>
                    <div class="cc-price-filter">
                        <input id="cc-price-min" type="number" placeholder="Min" value="<?php echo esc_attr( $price_min ); ?>" />
                        <input id="cc-price-max" type="number" placeholder="Max" value="<?php echo esc_attr( $price_max ); ?>" />
                        <div style="margin-top:.5rem">
                            <button id="cc-apply-price">Aplicar</button>
                            <button id="cc-clear-price">Limpiar</button>
                        </div>
                    </div>
                </div>
            </aside>

            <section class="cc-main-content">
                <?php if ( $products->have_posts() ): ?>
                    <div class="cc-products-grid">
                        <?php while ( $products->have_posts() ): $products->the_post(); global $product; ?>
                            <article class="cc-product-card">
                                <a href="<?php the_permalink(); ?>" class="cc-product-link">
                                    <div class="cc-product-image-wrapper">
                                        <?php if ( has_post_thumbnail() ): the_post_thumbnail('medium', array('class'=>'cc-product-image')); else: ?>
                                            <img class="cc-product-image" src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" alt="" />
                                        <?php endif; ?>
                                    </div>
                                    <h3 class="cc-product-name"><?php the_title(); ?></h3>
                                </a>
                                <div class="cc-product-meta">
                                    <div class="cc-price"><?php echo wp_kses_post( $product ? $product->get_price_html() : '' ); ?></div>
                                    <div class="cc-add-to-cart">
                                        <?php if ( function_exists('woocommerce_template_loop_add_to_cart') ) {
                                            // muestra el botón/add-to-cart estándar de WooCommerce
                                            woocommerce_template_loop_add_to_cart();
                                        } else {
                                            // fallback: enlace simple al detalle
                                            echo '<a class="button" href="' . get_permalink() . '">Ver</a>';
                                        } ?>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>

                    <div class="cc-pagination">
                        <?php
                        echo paginate_links( array(
                            'total' => $products->max_num_pages,
                            'current' => $paged,
                        ) );
                        ?>
                    </div>

                <?php else: ?>
                    <div class="cc-no-results">No se encontraron productos.</div>
                <?php endif; ?>
            </section>
        </div>
    </div>
</main>

<?php
get_footer();
