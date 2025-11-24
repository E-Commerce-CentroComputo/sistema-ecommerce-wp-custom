<?php
/**
 * My Account login form override — blocksy-child
 * Este archivo sustituye la plantilla por defecto de WooCommerce para ajustar
 * la maquetación al diseño solicitado (split hero + tarjeta de formulario)
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$registration_enabled = 'yes' === get_option( 'woocommerce_enable_myaccount_registration' );
?>

<div class="cc-login-split">
    <div class="cc-login-hero">
        <div class="cc-hero-inner">
            <h1 class="cc-hero-title">CENTRO CÓMPUTO</h1>
            <p class="cc-hero-sub">LA TECNOLOGÍA DE HOY</p>
        </div>
    </div>

    <div class="cc-login-card-wrap">
        <div class="cc-login-card">
            <a class="cc-login-back" href="<?php echo esc_url( wp_get_referer() ? wp_get_referer() : home_url() ); ?>">← Volver</a>

            <div class="cc-login-tabs" role="tablist">
                <div class="cc-login-tab" data-tab="login" role="tab">INICIAR SESIÓN</div>
                <?php if ( $registration_enabled ) : ?>
                <div class="cc-login-tab" data-tab="register" role="tab">CREAR CUENTA</div>
                <?php endif; ?>
            </div>

            <div class="cc-login-form">
                <div class="cc-panel-login">
                    <?php do_action( 'woocommerce_before_customer_login_form' ); ?>

                    <form class="woocommerce-form woocommerce-form-login login" method="post">
                        <?php do_action( 'woocommerce_login_form_start' ); ?>

                        <p class="cc-form-row">
                            <label for="username">CORREO ELECTRÓNICO&nbsp;<span class="required">*</span></label>
                            <input type="text" class="input-text" name="username" id="username" autocomplete="email" />
                        </p>

                        <p class="cc-form-row">
                            <label for="password">CONTRASEÑA&nbsp;<span class="required">*</span></label>
                            <input class="input-text" type="password" name="password" id="password" autocomplete="current-password" />
                        </p>

                        <?php do_action( 'woocommerce_login_form' ); ?>

                        <div class="cc-login-submit">
                            <p class="form-row">
                                <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
                                <button type="submit" class="cc-btn-black" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>">INICIAR SESIÓN &nbsp;→</button>
                            </p>

                            <p class="cc-link-muted"><label><input name="rememberme" type="checkbox" id="rememberme" value="forever" /> Recordarme</label></p>
                        </div>

                        <p class="lost-password"><a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="cc-link-muted">¿Olvidaste tu contraseña?</a></p>

                        <?php do_action( 'woocommerce_login_form_end' ); ?>
                    </form>

                    <?php do_action( 'woocommerce_after_customer_login_form' ); ?>
                </div>

                <?php if ( $registration_enabled ) : ?>
                <div class="cc-panel-register cc-hidden">
                    <?php // Registration form (copiado y simplificado) ?>
                    <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?>>
                        <?php do_action( 'woocommerce_register_form_start' ); ?>

                        <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
                            <p class="cc-form-row">
                                <label for="reg_username">Usuario&nbsp;<span class="required">*</span></label>
                                <input type="text" class="input-text" name="username" id="reg_username" autocomplete="username" />
                            </p>
                        <?php endif; ?>

                        <p class="cc-form-row">
                            <label for="reg_email">CORREO ELECTRÓNICO&nbsp;<span class="required">*</span></label>
                            <input type="email" class="input-text" name="email" id="reg_email" autocomplete="email" />
                        </p>

                        <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
                            <p class="cc-form-row">
                                <label for="reg_password">CONTRASEÑA&nbsp;<span class="required">*</span></label>
                                <input type="password" class="input-text" name="password" id="reg_password" autocomplete="new-password" />
                            </p>
                        <?php endif; ?>

                        <?php do_action( 'woocommerce_register_form' ); ?>

                        <p class="form-row">
                            <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
                            <button type="submit" class="cc-btn-black" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>">CREAR CUENTA</button>
                        </p>

                        <?php do_action( 'woocommerce_register_form_end' ); ?>
                    </form>
                </div>
                <?php endif; ?>

            </div>

        </div>
    </div>
</div>
