<?php
if (! defined('WP_DEBUG')) {
    die('Direct access forbidden.');
}

// Cargar los estilos del tema padre
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
});

/**
 * Get asset version using transient cache to avoid frequent disk filemtime calls.
 * Caches the filemtime for a short TTL (seconds).
 *
 * @param string $file_path Absolute path to file
 * @param int $ttl Seconds to cache the filemtime
 * @return int|string
 */
function cc_get_asset_version( $file_path, $ttl = 60 ) {
    $key = 'cc_av_' . md5( $file_path );
    $ver = get_transient( $key );
    if ( $ver !== false ) {
        return $ver;
    }
    $ver = file_exists( $file_path ) ? filemtime( $file_path ) : '1.0';
    set_transient( $key, $ver, intval( $ttl ) );
    return $ver;
}

// ===========================================================
// 🔹 CARGAR ARCHIVOS PERSONALIZADOS (CSS y JS)
// ===========================================================
/**
 * Enqueue parent and child theme scripts/styles.
 * Enqueues base custom CSS/JS for the child theme.
 */
function blocksy_child_enqueue_scripts() {

    // 🎨 CSS personalizado
    $css_file = get_stylesheet_directory() . '/assets/css/custom.css';
    $css_ver = cc_get_asset_version( $css_file );
    wp_enqueue_style(
        'blocksy-child-custom-css',
        get_stylesheet_directory_uri() . '/assets/css/custom.css',
        array(),
        $css_ver,
        'all'
    );

    // ⚙️ JS personalizado (sin dependencia de jQuery: nuestro custom.js usa vanilla JS)
    $js_file = get_stylesheet_directory() . '/assets/js/custom.js';
    $js_ver = cc_get_asset_version( $js_file );
    wp_enqueue_script(
        'blocksy-child-custom-js',
        get_stylesheet_directory_uri() . '/assets/js/custom.js',
        array(),
        $js_ver,
        true
    );
    // marcar como defer para reducir bloqueo de parsing
    if ( function_exists('wp_script_add_data') ) {
        wp_script_add_data( 'blocksy-child-custom-js', 'defer', true );
    }

    // No inline debug scripts in production. Use proper logging only when needed.
}

add_action('wp_enqueue_scripts', 'blocksy_child_enqueue_scripts');

// Encolar assets específicos para la página de servicios
/**
 * Enqueue assets used by the Services pages.
 * Loads `servicios.css` and `servicios.js` when viewing the services page or related templates.
 */
function cc_enqueue_servicios_assets() {
    // Queremos encolar los assets de servicios en cualquiera de estos casos:
    // - la página usa explícitamente la plantilla `page-services.php`
    // - la página tiene slug 'servicios' o 'services'
    // - (futuro) se puede ampliar con IDs o condicionales adicionales
    $should_enqueue = false;

    if ( function_exists('is_page_template') && is_page_template('page-services.php') ) {
        $should_enqueue = true;
    }
    if ( function_exists('is_page') ) {
        if ( is_page( array( 'servicios', 'services', 'mantenimiento-pc', 'mantenimiento' ) ) ) {
            $should_enqueue = true;
        }
    }

    if ( $should_enqueue ) {
    $svc_css = get_stylesheet_directory() . '/assets/css/servicios.css';
    $svc_js  = get_stylesheet_directory() . '/assets/js/servicios.js';
    // Versionado por cached filemtime
    $svc_css_ver = cc_get_asset_version( $svc_css );
    $svc_js_ver  = cc_get_asset_version( $svc_js );

    wp_enqueue_style('servicios-css', get_stylesheet_directory_uri() . '/assets/css/servicios.css', array(), $svc_css_ver);
    wp_enqueue_script('servicios-js', get_stylesheet_directory_uri() . '/assets/js/servicios.js', array(), $svc_js_ver, true);
        if ( function_exists('wp_script_add_data') ) wp_script_add_data( 'servicios-js', 'defer', true );
        // No inline debug styles — styles should live in assets/css/servicios.css
    }
}
add_action('wp_enqueue_scripts', 'cc_enqueue_servicios_assets');

// Encolar assets específicos para la página de productos
/**
 * Enqueue assets used by product listing pages (shop, product archives).
 */
function cc_enqueue_productos_assets() {
    // Encolar para plantilla personalizada, página por slug 'products'/'productos' o páginas de tienda de WooCommerce
    $should_enqueue = false;

    if ( function_exists('is_page_template') && is_page_template('page-products.php') ) {
        $should_enqueue = true;
    }
    if ( function_exists('is_page_template') && is_page_template('page-productos-clean.php') ) {
        $should_enqueue = true;
    }

    // Si la página actual es un archive/shop de WooCommerce
    if ( function_exists('is_post_type_archive') && is_post_type_archive('product') ) {
        $should_enqueue = true;
    }
    if ( function_exists('is_shop') && is_shop() ) {
        $should_enqueue = true;
    }

    // También encolar si la página actual tiene slug 'products' o 'productos'
    if ( function_exists('is_page') ) {
        if ( is_page('products') || is_page('productos') ) {
            $should_enqueue = true;
        }
    }

    if ( $should_enqueue ) {
        $prod_css_file = get_stylesheet_directory() . '/assets/css/productos.css';
        $prod_js_file  = get_stylesheet_directory() . '/assets/js/productos.js';
        $prod_css_ver = cc_get_asset_version( $prod_css_file );
        $prod_js_ver  = cc_get_asset_version( $prod_js_file );

        wp_enqueue_style('productos-css', get_stylesheet_directory_uri() . '/assets/css/productos.css', array(), $prod_css_ver);
        wp_enqueue_script('productos-js', get_stylesheet_directory_uri() . '/assets/js/productos.js', array(), $prod_js_ver, true);
        if ( function_exists('wp_script_add_data') ) wp_script_add_data( 'productos-js', 'defer', true );
    }
}
add_action('wp_enqueue_scripts', 'cc_enqueue_productos_assets');

// Encolar estilos y scripts para las páginas de Login / Mi cuenta
/**
 * Enqueue login / account page assets for consistent styling of auth forms.
 */
function cc_enqueue_login_assets() {
    // Encolamos los assets de login en todas las páginas frontend.
    // Esto asegura que modales que muestran el formulario (desde el header o plugins)
    // también reciban los estilos/JS independientemente del estado de sesión.
    $should = true;
    if ( function_exists('is_account_page') && is_account_page() ) $should = true;
    if ( function_exists('is_page') && is_page( array( 'login', 'mi-cuenta', 'mi_cuenta' ) ) ) $should = true;

    if ( $should ) {
        $css = get_stylesheet_directory() . '/assets/css/login.css';
        $js  = get_stylesheet_directory() . '/assets/js/login.js';
        $css_ver = cc_get_asset_version( $css );
        $js_ver  = cc_get_asset_version( $js );
        wp_enqueue_style('cc-login-css', get_stylesheet_directory_uri() . '/assets/css/login.css', array(), $css_ver );
        wp_enqueue_script('cc-login-js', get_stylesheet_directory_uri() . '/assets/js/login.js', array(), $js_ver, true );
        if ( function_exists('wp_script_add_data') ) wp_script_add_data( 'cc-login-js', 'defer', true );
    }
}
add_action('wp_enqueue_scripts', 'cc_enqueue_login_assets');

// También encolar en el login de WP (wp-login.php) para mantener estilo consistente
/**
 * Enqueue styles and scripts for the WordPress login screen (wp-login.php).
 */
function cc_enqueue_login_wp_login() {
    $css = get_stylesheet_directory() . '/assets/css/login.css';
    $js  = get_stylesheet_directory() . '/assets/js/login.js';
    $css_ver = file_exists($css) ? filemtime($css) : '1.0';
    $js_ver  = file_exists($js) ? filemtime($js) : '1.0';
    wp_enqueue_style('cc-login-css-wp', get_stylesheet_directory_uri() . '/assets/css/login.css', array(), $css_ver );
    wp_enqueue_script('cc-login-js-wp', get_stylesheet_directory_uri() . '/assets/js/login.js', array(), $js_ver, true );
}
add_action('login_enqueue_scripts', 'cc_enqueue_login_wp_login');

// Encolar CSS para la página de Carrito
/**
 * Enqueue cart-related assets including the mini-cart script and styles.
 */
function cc_enqueue_cart_assets() {
    // Encolamos estilos del carrito también para el mini-cart (dropdown) en el header
    $css_file = get_stylesheet_directory() . '/assets/css/cart.css';
    $css_ver = cc_get_asset_version( $css_file );
    wp_enqueue_style('cc-cart-css', get_stylesheet_directory_uri() . '/assets/css/cart.css', array(), $css_ver );

    // Encolar el script del mini-cart para mejorar la interacción (hover)
    $js_file = get_stylesheet_directory() . '/assets/js/cart-mini.js';
    $js_ver = cc_get_asset_version( $js_file );
    wp_enqueue_script('cc-cart-mini-js', get_stylesheet_directory_uri() . '/assets/js/cart-mini.js', array(), $js_ver, true );
    if ( function_exists('wp_script_add_data') ) wp_script_add_data( 'cc-cart-mini-js', 'defer', true );
}
// Nota: desactivado temporalmente el enqueue del mini-cart del child theme
// para volver a la implementación nativa del theme (Blocksy). Si quieres
// reactivar los estilos/scripts personalizados del mini-cart, descomenta
// la siguiente línea.
// Reactivar el mini-cart del child theme para usar nuestro dropdown y evitar conflictos
add_action('wp_enqueue_scripts', 'cc_enqueue_cart_assets');

// Encolar assets específicos para la vista de servicio (single product en cat 'servicios')
/**
 * Enqueue assets specifically for the single product view when product is in category 'servicios'.
 */
function cc_enqueue_service_single_assets() {
    if ( function_exists('is_product') && is_product() ) {
        global $post;
        $product_id = isset( $post->ID ) ? $post->ID : 0;
        if ( $product_id ) {
            $terms = wp_get_post_terms( $product_id, 'product_cat', array( 'fields' => 'slugs' ) );
            if ( ! is_wp_error( $terms ) && in_array( 'servicios', $terms, true ) ) {
                $css = get_stylesheet_directory() . '/assets/css/service-single.css';
                $js  = get_stylesheet_directory() . '/assets/js/service-single.js';
                $css_ver = cc_get_asset_version( $css );
                $js_ver  = cc_get_asset_version( $js );
                wp_enqueue_style('cc-service-single-css', get_stylesheet_directory_uri() . '/assets/css/service-single.css', array(), $css_ver );
                wp_enqueue_script('cc-service-single-js', get_stylesheet_directory_uri() . '/assets/js/service-single.js', array(), $js_ver, true );
                if ( function_exists('wp_script_add_data') ) wp_script_add_data( 'cc-service-single-js', 'defer', true );
            }
        }
    }
}
add_action('wp_enqueue_scripts', 'cc_enqueue_service_single_assets');

// footer debug eliminado (se usó solo para desarrollo)

/**
 * Al guardar un producto, si pertenece a la categoría 'servicios', asegurar metadatos por defecto
 * para que la página de Servicios muestre la miniatura, descripción corta, tiempo estimado e icono.
 * Esto facilita que al crear un nuevo producto en WooCommerce y marcarlo en la categoría 'servicios'
 * se muestre automáticamente con el estilo esperado en la página de Servicios.
 */
/**
 * Ensure default metadata for products in the 'servicios' category when saved.
 * - Sets a default short description, estimated time and icon if missing.
 */
function cc_set_service_defaults_on_save( $post_id, $post, $update ) {
    // Evitar autosaves y revisiones
    if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
        return;
    }

    if ( ! isset( $post->post_type ) || 'product' !== $post->post_type ) {
        return;
    }

    // Comprobar si el producto está en la categoría 'servicios'
    $terms = wp_get_post_terms( $post_id, 'product_cat', array( 'fields' => 'slugs' ) );
    if ( is_wp_error( $terms ) || empty( $terms ) ) {
        return;
    }

    if ( ! in_array( 'servicios', $terms, true ) ) {
        return;
    }

    // Evitar reentradas al actualizar el post desde aquí
    static $running = false;
    if ( $running ) {
        return;
    }
    $running = true;

    // 1) Short description (excerpt) por defecto
    $excerpt = isset( $post->post_excerpt ) ? trim( $post->post_excerpt ) : '';
    if ( empty( $excerpt ) ) {
        // Solo actualizar si está vacío
        wp_update_post( array(
            'ID' => $post_id,
            'post_excerpt' => 'Limpieza y optimización profesional'
        ) );
    }

    // 2) Meta 'tiempo_estimado' por defecto (si no existe)
    $tiempo = get_post_meta( $post_id, 'tiempo_estimado', true );
    if ( empty( $tiempo ) ) {
        update_post_meta( $post_id, 'tiempo_estimado', '1 hora' );
    }

    // 3) Meta 'icono' por defecto
    $icono = get_post_meta( $post_id, 'icono', true );
    if ( empty( $icono ) ) {
        update_post_meta( $post_id, 'icono', 'cpu' );
    }

    // 4) Si no tiene imagen destacada, no intentamos subir una (requiere archivo). Solo dejamos nota.
    // (Opcional) podríamos asignar una imagen por URL si se dispone de una en la plantilla.

    $running = false;
}
add_action( 'save_post_product', 'cc_set_service_defaults_on_save', 20, 3 );

/**
 * Exclude products in product_cat 'servicios' from shop and product listings
 * but keep them visible on the dedicated /servicios page or its category archive.
 * Also, on the /servicios page we exclude any product that is a subservice
 * (has meta key 'parent_service_id') so only top-level services appear.
 */
/**
 * Exclude 'servicios' products from general shop listings while keeping them
 * visible on the dedicated /servicios page. Also hide subservices (products
 * that have meta 'parent_service_id') from the servicios listing.
 */
function cc_exclude_servicios_from_shop( $query ) {
    // Only modify main frontend queries
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    // Determine if current request is the dedicated servicios listing (page or category archive)
    $is_servicios_page = ( method_exists( $query, 'is_page' ) && $query->is_page( 'servicios' ) ) || ( method_exists( $query, 'is_tax' ) && $query->is_tax( 'product_cat', 'servicios' ) );

    // Get servicios term id
    $term = get_term_by( 'slug', 'servicios', 'product_cat' );
    $servicios_term_id = $term && ! is_wp_error( $term ) ? intval( $term->term_id ) : 0;

    if ( $is_servicios_page ) {
        // On the servicios listing, exclude any product that is a subservice (has parent_service_id)
        $meta_query = $query->get( 'meta_query', array() );
        $meta_query[] = array(
            'key' => 'parent_service_id',
            'compare' => 'NOT EXISTS',
        );
        $query->set( 'meta_query', $meta_query );
        return;
    }

    // On shop / product archives, exclude the servicios category entirely
    $is_shop_archive = ( method_exists( $query, 'is_shop' ) && $query->is_shop() ) || ( method_exists( $query, 'is_post_type_archive' ) && $query->is_post_type_archive( 'product' ) ) || ( method_exists( $query, 'is_tax' ) && $query->is_tax( 'product_cat' ) );

    if ( $servicios_term_id && $is_shop_archive ) {
        $tax_query = $query->get( 'tax_query', array() );
        $tax_query[] = array(
            'taxonomy' => 'product_cat',
            'field' => 'term_id',
            'terms' => array( $servicios_term_id ),
            'operator' => 'NOT IN',
        );
        $query->set( 'tax_query', $tax_query );
    }
}
add_action( 'pre_get_posts', 'cc_exclude_servicios_from_shop', 20 );

// AJAX handler: add main product and selected subservices to cart
/**
 * AJAX handler to add a main service product and selected subservice products
 * to the WooCommerce cart in a single request. Expects:
 * - nonce: security nonce generated in template
 * - main_id: product ID of the base service
 * - subs[]: array of product IDs for subservices
 */
function cc_add_service_to_cart_ajax() {
    // Verify nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'cc_add_service' ) ) {
        wp_send_json_error( array( 'message' => 'Nonce inválido' ), 403 );
    }

    if ( ! class_exists( 'WooCommerce' ) ) {
        wp_send_json_error( array( 'message' => 'WooCommerce no está activo' ), 500 );
    }

    $main_id = isset( $_POST['main_id'] ) ? intval( $_POST['main_id'] ) : 0;
    $subs_raw = isset( $_POST['subs'] ) && is_array( $_POST['subs'] ) ? $_POST['subs'] : array();
    // sanitize and normalize subs to unique positive integers
    $subs = array();
    foreach ( $subs_raw as $s ) {
        $n = intval( $s );
        if ( $n > 0 ) $subs[] = $n;
    }
    $subs = array_values( array_unique( $subs ) );

    if ( $main_id <= 0 ) {
        wp_send_json_error( array( 'message' => 'Producto principal inválido' ), 400 );
    }

    // Ensure cart is available
    if ( ! WC()->cart ) {
        wc_load_cart();
    }

    $added = array();
    $failed = array();

    // Ensure cart is available
    if ( ! WC()->cart ) {
        if ( function_exists( 'wc_load_cart' ) ) {
            wc_load_cart();
        }
    }

    // Helper to check if product already in cart
    $is_in_cart = function( $pid ) {
        foreach ( WC()->cart->get_cart() as $cart_item ) {
            if ( isset( $cart_item['product_id'] ) && intval( $cart_item['product_id'] ) === intval( $pid ) ) return true;
        }
        return false;
    };

    // Add main product if needed
    if ( $main_id > 0 ) {
        if ( ! $is_in_cart( $main_id ) ) {
            $prod_main = wc_get_product( $main_id );
            if ( ! $prod_main ) {
                wp_send_json_error( array( 'message' => 'Producto principal no encontrado', 'main_id' => $main_id ), 404 );
            }
            // if product is variable, request explicit variation from frontend (not supported here)
            if ( $prod_main->is_type( 'variable' ) ) {
                wp_send_json_error( array( 'message' => 'Producto principal es variable. Seleccione una variación en la ficha.' ), 400 );
            }
            $res_main = WC()->cart->add_to_cart( $main_id );
            if ( $res_main ) $added[] = $main_id; else $failed[] = $main_id;
        }
    }

    // Add subservices: validate type and availability
    foreach ( $subs as $sid ) {
        if ( $sid <= 0 ) continue;
        if ( $is_in_cart( $sid ) ) continue; // skip already present
        $p = wc_get_product( $sid );
        if ( ! $p ) {
            $failed[] = array( 'id' => $sid, 'reason' => 'no_exist' );
            continue;
        }
        // Only simple or virtual/simple purchasable products supported by this flow
        if ( $p->is_type( 'variable' ) ) {
            $failed[] = array( 'id' => $sid, 'reason' => 'variable_not_supported' );
            continue;
        }
        if ( ! $p->is_purchasable() ) {
            $failed[] = array( 'id' => $sid, 'reason' => 'not_purchasable' );
            continue;
        }

        $r = WC()->cart->add_to_cart( $sid );
        if ( $r ) $added[] = $sid; else $failed[] = array( 'id' => $sid, 'reason' => 'add_failed' );
    }

    // Process extras (price-only options) sent from frontend
    $subs_extra = array();
    if ( isset( $_POST['subs_extra'] ) ) {
        $raw = wp_unslash( $_POST['subs_extra'] );
        $decoded = json_decode( $raw, true );
        if ( is_array( $decoded ) ) {
            foreach ( $decoded as $ex ) {
                $label = isset( $ex['label'] ) ? sanitize_text_field( $ex['label'] ) : '';
                $price = isset( $ex['price'] ) ? floatval( $ex['price'] ) : 0;
                if ( $price > 0 && $label ) $subs_extra[] = array( 'label' => $label, 'price' => $price );
            }
        }
    }

    if ( ! empty( $subs_extra ) ) {
        // Remove existing fees with same names to avoid duplicates
        if ( WC()->cart && method_exists( WC()->cart, 'get_fees' ) ) {
            $existing = WC()->cart->get_fees();
            if ( is_array( $existing ) ) {
                foreach ( $existing as $key => $fee ) {
                    // $fee could be an object with name property
                    $fname = is_object( $fee ) && isset( $fee->name ) ? $fee->name : ( is_array( $fee ) && isset( $fee['name'] ) ? $fee['name'] : '' );
                    foreach ( $subs_extra as $se ) {
                        if ( $fname && $fname === trim( 'Opción: ' . $se['label'] ) ) {
                            // attempt to remove by unsetting from cart->fees if available
                            if ( isset( WC()->cart->fees ) && is_array( WC()->cart->fees ) ) {
                                foreach ( WC()->cart->fees as $k => $f ) {
                                    $fn = is_object( $f ) && isset( $f->name ) ? $f->name : ( is_array( $f ) && isset( $f['name'] ) ? $f['name'] : '' );
                                    if ( $fn === $fname ) unset( WC()->cart->fees[ $k ] );
                                }
                            }
                        }
                    }
                }
            }
        }

        // Add fees for each extra option
        foreach ( $subs_extra as $se ) {
            $fee_name = 'Opción: ' . $se['label'];
            // add_fee expects amount and optional taxable flag (false)
            try {
                WC()->cart->add_fee( $fee_name, floatval( $se['price'] ), false );
            } catch ( Exception $e ) {
                $failed[] = array( 'label' => $se['label'], 'reason' => 'fee_failed' );
            }
        }
        // recalc totals after adding fees
        if ( method_exists( WC()->cart, 'calculate_totals' ) ) WC()->cart->calculate_totals();
    }

    // Recalculate totals
    if ( method_exists( WC()->cart, 'calculate_totals' ) ) {
        WC()->cart->calculate_totals();
    }

    if ( empty( $added ) && ! empty( $failed ) ) {
        wp_send_json_error( array( 'message' => 'Algunos productos no pudieron añadirse', 'failed' => $failed ), 500 );
    }

    // Build compact cart items list for debugging/frontend convenience
    $cart_items = array();
    foreach ( WC()->cart->get_cart() as $ci ) {
        $cart_items[] = array(
            'product_id' => isset( $ci['product_id'] ) ? intval( $ci['product_id'] ) : 0,
            'quantity' => isset( $ci['quantity'] ) ? intval( $ci['quantity'] ) : 0,
            'line_total' => isset( $ci['line_total'] ) ? floatval( $ci['line_total'] ) : 0,
        );
    }

    // Return cart counts and total (both raw and formatted)
    $count = WC()->cart->get_cart_contents_count();
    $total_raw = WC()->cart->get_cart_contents_total();
    $total_html = wc_price( $total_raw );

    // Build WooCommerce-like fragments payload so frontend can update mini-cart quickly
    $fragments = array();
    try {
        ob_start();
        // Render the mini cart template content
        if ( function_exists( 'woocommerce_mini_cart' ) ) {
            woocommerce_mini_cart();
        } else {
            // Fallback: try to load template directly
            wc_get_template( 'cart/mini-cart.php' );
        }
        $mini = ob_get_clean();
        if ( $mini ) {
            $fragments['div.widget_shopping_cart_content'] = '<div class="widget_shopping_cart_content">' . $mini . '</div>';
        }
        // cart hash (some themes/plugins use it)
        if ( WC()->cart && method_exists( WC()->cart, 'get_cart_hash' ) ) {
            $fragments['cart_hash'] = WC()->cart->get_cart_hash();
        }
    } catch ( Exception $e ) {
        $fragments = array();
    }

    wp_send_json_success( array( 'count' => $count, 'total_raw' => $total_raw, 'total_html' => $total_html, 'added' => $added, 'failed' => $failed, 'cart_items' => $cart_items, 'fragments' => $fragments ) );
}
add_action( 'wp_ajax_cc_add_service_to_cart', 'cc_add_service_to_cart_ajax' );
add_action( 'wp_ajax_nopriv_cc_add_service_to_cart', 'cc_add_service_to_cart_ajax' );
