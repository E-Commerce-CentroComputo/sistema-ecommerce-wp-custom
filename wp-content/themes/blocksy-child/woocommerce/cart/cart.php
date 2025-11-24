<?php
/**
 * Template personalizado de Carrito — blocksy-child
 * Muestra los items en una columna y el resumen en la derecha
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

wc_print_notices();
do_action( 'woocommerce_before_cart' );

?>

<div class="cc-cart-page">
    <div class="cc-cart-main">
        <h1 class="cc-cart-title">Carrito de Compras</h1>
        <p class="cc-cart-sub"><?php echo WC()->cart->get_cart_contents_count(); ?> productos seleccionados</p>

        <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
            <div class="cc-cart-items">
                <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                    $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                    $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                    if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) :
                        $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                ?>
                    <div class="cc-cart-item">
                        <div class="cc-cart-item-thumbnail">
                            <?php
                                $thumb = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'thumbnail' ), $cart_item, $cart_item_key );
                                if ( $product_permalink ) {
                                    echo '<a href="' . esc_url( $product_permalink ) . '">' . $thumb . '</a>';
                                } else {
                                    echo $thumb;
                                }
                            ?>
                        </div>

                        <div class="cc-cart-item-body">
                            <div class="cc-cart-item-title">
                                <?php if ( $product_permalink ) : ?>
                                    <a href="<?php echo esc_url( $product_permalink ); ?>"><?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ); ?></a>
                                <?php else: ?>
                                    <?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ); ?>
                                <?php endif; ?>
                            </div>
                            <div class="cc-cart-item-meta">
                                <?php echo wc_get_product_category_list( $product_id, ', ', '<span class="posted_in">', '</span>' ); ?>
                            </div>

                            <div class="cc-qty-controls">
                                <?php
                                    if ( $_product->is_sold_individually() ) {
                                        echo '<input type="hidden" name="cart[' . $cart_item_key . '][qty]" value="1" />';
                                    } else {
                                        woocommerce_quantity_input( array(
                                            'input_name'  => "cart[{$cart_item_key}][qty]",
                                            'input_value' => $cart_item['quantity'],
                                            'max_value'   => $_product->get_max_purchase_quantity(),
                                            'min_value'   => '0',
                                        ), $_product, false );
                                    }
                                ?>
                            </div>
                        </div>

                        <div class="cc-cart-item-price">
                            <?php echo WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ); ?>
                            <div class="cc-cart-item-remove">
                                <?php
                                    echo sprintf( '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s</a>', esc_url( wc_get_cart_remove_url( $cart_item_key ) ), esc_attr__( 'Remove this item', 'woocommerce' ), esc_attr( $product_id ), esc_attr( $_product->get_sku() ), '🗑' );
                                ?>
                            </div>
                        </div>
                    </div>
                <?php endif; endforeach; ?>
            </div>

            <div style="margin-top:12px;display:flex;gap:12px;align-items:center;">
                <button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Actualizar carrito', 'woocommerce' ); ?></button>
                <?php wp_nonce_field( 'woocommerce-cart' ); ?>
                <?php do_action( 'woocommerce_cart_actions' ); ?>
            </div>
        </form>
    </div>

    <aside class="cc-cart-side">
        <div class="cc-cart-summary">
            <div class="cc-summary-title">Resumen</div>

            <form class="cc-coupon-form" method="post">
                <div class="cc-coupon">
                    <input type="text" name="coupon_code" placeholder="Código de descuento" />
                    <button type="submit" name="apply_coupon">Aplicar</button>
                </div>
            </form>

            <div class="cc-totals">
                <div class="row"><span>Subtotal</span><span><?php echo wc_price( WC()->cart->get_subtotal() ); ?></span></div>
                <?php if ( WC()->cart->get_cart_contents_tax() ) : ?>
                    <div class="row"><span>Impuestos</span><span><?php echo wc_price( WC()->cart->get_cart_contents_tax() ); ?></span></div>
                <?php endif; ?>
                <div class="row"><span>Envío</span><span><?php echo WC()->cart->get_shipping_total() ? wc_price( WC()->cart->get_shipping_total() ) : 'GRATIS'; ?></span></div>
                <div class="row" style="font-size:18px;font-weight:800"><span>Total</span><span><?php echo WC()->cart->get_cart_total(); ?></span></div>
            </div>

            <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="cc-checkout-btn">Proceder al Pago &nbsp;→</a>
            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="button" style="display:block;margin-top:10px;background:#fff;border:1px solid #e5e7eb;color:#000;text-align:center">Seguir Comprando</a>
        </div>
    </aside>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
