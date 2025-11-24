<?php
/**
 * Custom single product view for services (category: servicios)
 * This template renders a large hero and an options list with a summary box
 */
defined( 'ABSPATH' ) || exit;
global $product;
if ( ! isset( $product ) ) $product = wc_get_product( get_the_ID() );

$product_id = $product ? $product->get_id() : 0;
$title = $product ? $product->get_name() : '';
$excerpt = $product ? $product->get_short_description() : '';
$price = $product ? wc_price( $product->get_price() ) : '';
$price_raw = 0;
if ( $product ) {
    // Use WooCommerce helper to get the display price (respects taxes/pricing filters)
    $display_price = wc_get_price_to_display( $product );
    $price_raw = floatval( $display_price );
}
$image_url = wp_get_attachment_image_url( $product->get_image_id(), 'full' );

// Map product slugs to subservices (editable). If you want to add/modify options,
// update the $services_map array below or move this structure to post meta for each product.
$slug = $product ? $product->get_slug() : '';

$services_map = array(
    // Example mapping. Update slugs and items to match your products.
    'mantenimiento-pc' => array(
        array('label' => 'Limpieza de ventiladores', 'desc' => 'Remoción completa de polvo en CPU y GPU', 'price' => 15),
        array('label' => 'Cambio de pasta térmica', 'desc' => 'Aplicación de pasta térmica premium (Arctic MX-6)', 'price' => 25),
        array('label' => 'Limpieza de teclado', 'desc' => 'Limpieza profunda de teclas y switches', 'price' => 12),
        array('label' => 'Limpieza de pantalla', 'desc' => 'Limpieza especializada LCD/OLED', 'price' => 8),
    ),
    'formateo' => array(
        array('label' => 'Formateo computadora escritorio', 'desc' => '', 'price'=>40),
        array('label' => 'Formateo laptop', 'desc' => '', 'price'=>45),
        array('label' => 'Formateo escritorio MAC', 'desc' => '', 'price'=>85),
        array('label' => 'Formateo laptop MAC', 'desc' => '', 'price'=>90),
        array('label' => 'Recuperado de archivos (backup)', 'desc' => '', 'price'=>30),
        array('label' => 'Recuperado de disco / recovery', 'desc' => '', 'price'=>60),
        array('label' => 'Recuperado disco dañado / bloqueado', 'desc' => '', 'price'=>420),
        array('label' => 'Configuración correo corporativo', 'desc' => '', 'price'=>25),
    ),
);

$options = array();

// First try: find subservice products that explicitly reference this product via meta 'parent_service_id'
if ( $product_id ) {
    // Fetch only IDs to reduce memory usage when site has many products.
    $q = new WP_Query(array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'fields' => 'ids',
        'meta_query' => array(
            array(
                'key' => 'parent_service_id',
                'value' => $product_id,
                'compare' => '='
            )
        )
    ));
    if ( $q->have_posts() ) {
        foreach ( $q->posts as $pid ) {
            $p = wc_get_product( $pid );
            if ( ! $p ) continue;
            $options[] = array(
                'label' => $p->get_name(),
                'desc' => $p->get_short_description(),
                'price' => floatval( $p->get_price() ),
                'product_id' => $p->get_id(),
            );
        }
        wp_reset_postdata();
    }
}

// Fallback: if no subproducts found via meta, try to map by slug using the $services_map defined above
if ( empty( $options ) ) {
    $map = isset( $services_map[ $slug ] ) ? $services_map[ $slug ] : ( isset( $services_map['mantenimiento-pc'] ) ? $services_map['mantenimiento-pc'] : array() );
    foreach ( $map as $opt ) {
        // attempt to find a product by exact title matching the label
        $found = get_page_by_title( $opt['label'], OBJECT, 'product' );
        if ( $found ) {
            $p = wc_get_product( $found->ID );
            if ( $p ) {
                $options[] = array(
                    'label' => $p->get_name(),
                    'desc' => $p->get_short_description() ?: $opt['desc'],
                    'price' => floatval( $p->get_price() ?: $opt['price'] ),
                    'product_id' => $p->get_id(),
                );
                continue;
            }
        }

        // If exact title lookup fails, try a loose search by term (partial match).
        // This helps when product titles differ slightly from the mapping labels.
        $search = get_posts( array(
            'post_type' => 'product',
            's' => $opt['label'],
            'posts_per_page' => 1,
            'post_status' => 'publish',
            'fields' => 'ids',
        ) );
        if ( ! empty( $search ) && ! is_wp_error( $search ) ) {
            $pid = intval( $search[0] );
            $p = wc_get_product( $pid );
            if ( $p ) {
                $options[] = array(
                    'label' => $p->get_name(),
                    'desc' => $p->get_short_description() ?: $opt['desc'],
                    'price' => floatval( $p->get_price() ?: $opt['price'] ),
                    'product_id' => $p->get_id(),
                );
                continue;
            }
        }
        // fallback to static map entry — keep price and set product_id 0 (will be sent as extra)
        $options[] = array(
            'label' => $opt['label'],
            'desc' => isset($opt['desc']) ? $opt['desc'] : '',
            'price' => isset($opt['price']) ? floatval($opt['price']) : 0,
            'product_id' => 0,
        );
    }
}

get_header();
?>
<?php $css_path = get_stylesheet_directory() . '/assets/css/service-single.css'; $css_ver = file_exists($css_path) ? filemtime($css_path) : ''; ?>
<link rel="stylesheet" href="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/css/service-single.css' ); ?>?ver=<?php echo esc_attr( $css_ver ); ?>">
<main class="cc-service-single">
    <section class="cc-service-hero" style="background-image:url('<?php echo esc_url( $image_url ); ?>')">
        <div class="cc-service-hero-overlay"></div>
        <div class="cc-service-hero-inner">
            <a class="cc-back" href="<?php echo esc_url( get_permalink( wc_get_page_id('shop') ) ); ?>">&lt; VOLVER</a>
            <div class="cc-hero-badge">SERVICIO TÉCNICO</div>
            <h1 class="cc-hero-title"><?php echo esc_html( $title ); ?></h1>
            <p class="cc-hero-sub"><?php echo esc_html( $excerpt ); ?></p>
            <div class="cc-hero-cta">
                <div class="cc-hero-price">Desde <span class="cc-price"><?php echo $price; ?></span></div>
                <a class="cc-hero-cta-btn" href="#opciones">VER OPCIONES</a>
            </div>
        </div>
    </section>

    <section class="cc-service-content">
        <div class="cc-service-grid">
            <div class="cc-service-left">
                <h2>OPCIONES DISPONIBLES</h2>
                <p class="cc-lead">Selecciona las opciones que necesitas para personalizar tu servicio</p>
                <form id="cc-service-options">
                    <?php foreach ( $options as $i => $opt ): ?>
                        <label class="cc-option-card">
                            <input type="checkbox" name="opt[]" value="<?php echo esc_attr( $i ); ?>" data-price="<?php echo esc_attr( floatval( $opt['price'] ) ); ?>" data-product-id="<?php echo esc_attr( isset($opt['product_id']) ? $opt['product_id'] : 0 ); ?>">
                            <div class="cc-option-body">
                                <div class="cc-option-title"><?php echo esc_html( $opt['label'] ); ?></div>
                                <?php if ( ! empty( $opt['desc'] ) ): ?><div class="cc-option-desc"><?php echo esc_html( $opt['desc'] ); ?></div><?php endif; ?>
                            </div>
                            <div class="cc-option-price">S/. <?php echo number_format_i18n( $opt['price'], 2 ); ?></div>
                        </label>
                    <?php endforeach; ?>
                </form>
            </div>

            <aside class="cc-service-right">
                <div class="cc-summary-card">
                    <h3>RESUMEN DEL SERVICIO</h3>
                    <div class="cc-summary-line"><span>Cargo base</span><span class="cc-summary-base" data-base-price="<?php echo esc_attr( $price_raw ); ?>">S/. <?php echo number_format_i18n( $price_raw, 2 ); ?></span></div>
                    <div class="cc-summary-selected">Selecciona opciones</div>
                    <hr>
                    <div class="cc-summary-total-row"><div>TOTAL</div><div class="cc-summary-total">S/. <?php echo number_format_i18n( $price_raw, 2 ); ?></div></div>
                    <?php $cc_nonce = wp_create_nonce( 'cc_add_service' ); ?>
                    <button id="cc-request-service" class="cc-btn-primary" data-cart-url="<?php echo esc_url( wc_get_cart_url() ); ?>" data-ajax-url="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" data-main-product-id="<?php echo esc_attr( $product_id ); ?>" data-nonce="<?php echo esc_attr( $cc_nonce ); ?>">SOLICITAR SERVICIO</button>
                    <a href="#" class="cc-btn-ghost">CONSULTAR</a>
                </div>
                <!-- DEBUG removed: production-ready template -->
                <ul class="cc-service-bullets">
                    <li>Garantía de 90 días</li>
                    <li>Técnicos certificados</li>
                    <li>Diagnóstico gratuito</li>
                </ul>
            </aside>
        </div>
    </section>
</main>

<?php $js_path = get_stylesheet_directory() . '/assets/js/service-single.js'; $js_ver = file_exists($js_path) ? filemtime($js_path) : ''; ?>
<script src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/js/service-single.js' ); ?>?ver=<?php echo esc_attr( $js_ver ); ?>" defer></script>

<?php
get_footer();
