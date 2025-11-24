<?php
/**
 * Child-theme override: single product loader.
 * If product is in category 'servicios' we load a custom service view,
 * otherwise fall back to the default WooCommerce single product template.
 */
defined( 'ABSPATH' ) || exit;

global $product, $post;

// Ensure we have a WC product object. Try several fallbacks.
if ( empty( $product ) || ! is_object( $product ) ) {
    if ( isset( $post ) && is_object( $post ) ) {
        $product = wc_get_product( $post->ID );
    } else {
        $product = wc_get_product( get_the_ID() );
    }
}

// Determine product ID safely
$product_id = 0;
if ( is_object( $product ) ) {
    if ( method_exists( $product, 'get_id' ) ) {
        $product_id = (int) $product->get_id();
    } else {
        // If the object doesn't expose get_id(), try to resolve a WC_Product instance
        $maybe_product = wc_get_product( $product );
        if ( $maybe_product && method_exists( $maybe_product, 'get_id' ) ) {
            $product_id = (int) $maybe_product->get_id();
        }
    }
} elseif ( is_numeric( $product ) ) {
    $product_id = (int) $product;
}

if ( $product_id && has_term( 'servicios', 'product_cat', $product_id ) ) {
    // Load custom service single template located in the child theme
    wc_get_template( 'single-product-service.php', array(), '', get_stylesheet_directory() . '/woocommerce/' );
} else {
    // Default behaviour
    wc_get_template_part( 'content', 'single-product' );
}
