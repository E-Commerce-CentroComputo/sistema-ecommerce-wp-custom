<?php
/**
 * Front Page Template - Child Theme
 * Usa get_header() / get_footer() y confía en las colas de scripts/styles
 * definidas en `functions.php` para cargar `assets/css/custom.css` y `assets/js/custom.js`.
 */

get_header();

// Resolver URL de la página de tienda / productos
$shop_url = home_url('/productos/');
if ( function_exists('wc_get_page_id') ) {
    $shop_id = wc_get_page_id( 'shop' );
    if ( $shop_id && $shop_id > 0 ) {
        $shop_url = get_permalink( $shop_id );
    }
} else {
    // fallback: buscar páginas por slug
    $page = get_page_by_path('productos');
    if ( $page ) $shop_url = get_permalink($page->ID);
    else {
        $page2 = get_page_by_path('products');
        if ( $page2 ) $shop_url = get_permalink($page2->ID);
    }
}

// Auto-redirect controlable: por seguridad está desactivado por defecto.
// Para probar la redirección, añade ?redirect_products=1 a la URL de home.
$auto_redirect_to_products = false; // poner true si quieres redirigir siempre
if ( isset($_GET['redirect_products']) && $_GET['redirect_products'] == '1' ) {
    wp_safe_redirect( esc_url_raw( $shop_url ) );
    exit;
}
if ( $auto_redirect_to_products ) {
    wp_safe_redirect( esc_url_raw( $shop_url ) );
    exit;
}
?>

<!-- HERO CAROUSEL -->
<section class="hero-carousel" id="heroCarousel" tabindex="0" aria-roledescription="carousel">
    <div class="hero-slides" id="heroSlides">

        <!-- Slide 1 -->
        <div class="hero-slide active">
            <div class="hero-bg">
                <img src="https://images.unsplash.com/photo-1550751827-4bd374c3f58b?w=1920&q=80" alt="CENTRO CÓMPUTO" loading="lazy" decoding="async">
            </div>
            <div class="hero-content">
                <h1>CENTRO CÓMPUTO</h1>
                <p>La tecnología de hoy, en tus manos</p>
                <a class="hero-btn" href="<?php echo esc_url( $shop_url ); ?>">Ver productos</a>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="hero-slide">
            <div class="hero-bg">
                <img src="https://images.unsplash.com/photo-1593640495253-23196b27a87f?w=1920&q=80" alt="Gaming" loading="lazy" decoding="async">
            </div>
            <div class="hero-content">
                <h1>NUEVA COLECCIÓN</h1>
                <p>Gaming de última generación</p>
                <a class="hero-btn" href="<?php echo esc_url( $shop_url ); ?>">Explorar Gaming</a>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="hero-slide">
            <div class="hero-bg">
                <img src="https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=1920&q=80" alt="Ofertas" loading="lazy" decoding="async">
            </div>
            <div class="hero-content">
                <h1>HASTA 40% OFF</h1>
                <p>Ofertas especiales en periféricos</p>
                <a class="hero-btn" href="<?php echo esc_url( $shop_url ); ?>">Ver ofertas</a>
            </div>
        </div>

        <!-- Slide 4 -->
        <div class="hero-slide">
            <div class="hero-bg">
                <img src="https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=1920&q=80" alt="Envío Gratis" loading="lazy" decoding="async">
            </div>
            <div class="hero-content">
                <h1>ENVÍO GRATIS</h1>
                <p>En compras mayores a $100</p>
                <a class="hero-btn" href="<?php echo esc_url( $shop_url ); ?>">Comprar ahora</a>
            </div>
        </div>

    </div>

    <!-- Navigation -->
    <button class="hero-nav prev" onclick="heroCarousel.prev()">
        <svg class="icon" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"></polyline></svg>
    </button>
    <button class="hero-nav next" onclick="heroCarousel.next()">
        <svg class="icon" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"></polyline></svg>
    </button>

    <!-- Dots -->
    <div class="hero-dots" id="heroDots"></div>

    <!-- Scroll Indicator -->
    <div class="scroll-indicator"></div>
</section>

<!-- FEATURES BAR -->
<section class="features-bar">
    <div class="container">
        <div class="features-grid">
            <div class="feature-item">
                <svg class="icon feature-icon" viewBox="0 0 24 24">
                    <rect x="1" y="3" width="15" height="13"></rect>
                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                    <circle cx="5.5" cy="18.5" r="2.5"></circle>
                    <circle cx="18.5" cy="18.5" r="2.5"></circle>
                </svg>
                <p class="feature-text">Envío gratis</p>
            </div>
            <div class="feature-item">
                <svg class="icon feature-icon" viewBox="0 0 24 24">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
                <p class="feature-text">Garantía</p>
            </div>
            <div class="feature-item">
                <svg class="icon feature-icon" viewBox="0 0 24 24">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                    <line x1="1" y1="10" x2="23" y2="10"></line>
                </svg>
                <p class="feature-text">Pago seguro</p>
            </div>
            <div class="feature-item">
                <svg class="icon feature-icon" viewBox="0 0 24 24">
                    <path d="M3 18v-6a9 9 0 0 1 18 0v6"></path>
                    <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path>
                </svg>
                <p class="feature-text">Soporte 24/7</p>
            </div>
        </div>
    </div>
</section>

<!-- CATEGORIES CAROUSEL -->
<section class="section categories-section">
    <div class="container">
        <div class="section-header" style="text-align: center;">
            <p class="section-label">Explora nuestro catálogo</p>
            <h2 class="section-title">Categorías</h2>
        </div>

        <div class="carousel" id="categoriesCarousel">
            <div class="carousel-track" id="categoriesTrack">
                <!-- Categories will be inserted here by JS -->
            </div>
            <button class="carousel-nav prev" onclick="scrollCarousel('categoriesTrack', -1)">
                <svg class="icon" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </button>
            <button class="carousel-nav next" onclick="scrollCarousel('categoriesTrack', 1)">
                <svg class="icon" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>
        </div>
    </div>
</section>

<!-- Aquí se repetiría el mismo patrón para los demás secciones como NEW ARRIVALS, BESTSELLERS, FLASH DEALS, BRANDS y QUICK LINKS -->
<!-- Solo asegurándote de: -->
<!-- 1. Mantener indentación consistente (4 espacios) -->
<!-- 2. Agrupar divs cerrados correctamente -->
<!-- 3. Comentar secciones principales -->

<?php
get_footer();
?>
