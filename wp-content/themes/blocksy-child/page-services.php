<?php
/**
 * Template Name: Servicios - Centro Cómputo
 * Description: Página de servicios técnicos con opciones personalizables
 */

// Encolar assets aquí explícitamente para asegurar que se impriman en la vista
if ( function_exists('wp_enqueue_style') ) {
    wp_enqueue_style('servicios-css', get_stylesheet_directory_uri() . '/assets/css/servicios.css', array(), '1.0.0');
}
if ( function_exists('wp_enqueue_script') ) {
    wp_enqueue_script('servicios-js', get_stylesheet_directory_uri() . '/assets/js/servicios.js', array(), '1.0.0', true);
}

// La encolación de CSS/JS de la página Servicios se realiza desde functions.php
// para garantizar que los assets estén registrados antes de wp_head.

get_header();

// Obtener servicios desde la base de datos
global $wpdb;
$tabla_servicios = $wpdb->prefix . 'servicios_tecnicos';

$servicios = $wpdb->get_results("
    SELECT *
    FROM $tabla_servicios
    WHERE activo = 1
    ORDER BY id ASC
");

// Imágenes de servicios (puedes guardarlas en la base de datos o usar estas por defecto)
$service_images = array(
    'Mantenimiento Preventivo PC' => 'https://images.unsplash.com/photo-1606485940233-76eeff49360c?w=1080',
    'Reparación de Laptop' => 'https://images.unsplash.com/photo-1646756089735-487709743361?w=1080',
    'Instalación de Sistema Operativo' => 'https://images.unsplash.com/photo-1610018556010-6a11691bc905?w=1080',
    'Recuperación de Datos' => 'https://images.unsplash.com/photo-1619455052599-4cded9ae462a?w=1080',
    'Configuración de Red' => 'https://images.unsplash.com/photo-1552820728-8b83bb6b773f?w=1080',
    'Actualización de Hardware' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=1080',
    'Eliminación de Virus' => 'https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?w=1080',
    'Consultoría Tecnológica' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=1080',
);

// Pasar la lista de servicios a JavaScript para que el script use los datos reales
// Construimos un array simple con los campos principales
$js_services = array();
foreach ($servicios as $s) {
    $js_services[] = array(
        'id' => intval($s->id),
        'name' => isset($s->nombre) ? $s->nombre : (isset($s->name) ? $s->name : ''),
        'description' => isset($s->descripcion) ? $s->descripcion : '',
        'price' => isset($s->precio) ? floatval($s->precio) : 0,
        'time' => isset($s->tiempo_estimado) ? $s->tiempo_estimado : '',
        'icon' => isset($s->icono) ? $s->icono : ''
    );
}

wp_localize_script('servicios-js', 'ccServiciosData', array(
    'ajaxurl' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('cc_servicios_nonce'),
    'isLoggedIn' => is_user_logged_in(),
    'services' => $js_services,
));
// Hero background
$hero_image = 'https://images.unsplash.com/photo-1646756089735-487709743361?w=1080';
?>

<!-- Hero Section -->
<section class="cc-services-hero">
    <div class="cc-hero-bg" style="background-image: url('<?php echo esc_url($hero_image); ?>');"></div>
    <div class="cc-hero-overlay"></div>
    
    <div class="container cc-hero-content">
        <div class="cc-hero-inner">
            <div class="cc-hero-badge">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                </svg>
                <span>SERVICIOS TÉCNICOS</span>
            </div>
            
            <h1 class="cc-hero-title">SERVICIOS</h1>
            
            <p class="cc-hero-description">
                Selecciona el servicio que necesitas y personaliza las opciones
            </p>
            
            <div class="cc-hero-buttons">
                <a href="<?php echo get_permalink(get_page_by_path('contacto')); ?>" class="cc-btn-primary">
                    CONTACTAR
                </a>
                <a href="tel:+525512345678" class="cc-btn-secondary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                    LLAMAR
                </a>
            </div>
        </div>
    </div>
</section>

<?php
// (Moved) product display will be rendered below the services header
?>

<!-- Benefits Bar -->
<section class="cc-benefits-bar">
    <div class="container">
        <div class="cc-benefits-grid">
            <div class="cc-benefit-item">
                <div class="cc-benefit-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                </div>
                <h3>GARANTÍA 90 DÍAS</h3>
                <p>En todos los servicios</p>
            </div>
            <div class="cc-benefit-item">
                <div class="cc-benefit-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
                <h3>24-48 HORAS</h3>
                <p>Servicio express</p>
            </div>
            <div class="cc-benefit-item">
                <div class="cc-benefit-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <h3>CERTIFICADOS</h3>
                <p>Técnicos profesionales</p>
            </div>
            <div class="cc-benefit-item">
                <div class="cc-benefit-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                </div>
                <h3>ORIGINALES</h3>
                <p>Repuestos de calidad</p>
            </div>
        </div>
    </div>
</section>

<!-- Services Grid -->
<section class="cc-services-section">
    <div class="container">
        
        <!-- Section Header -->
        <div class="cc-section-header">
            <p class="cc-section-label">NUESTROS SERVICIOS</p>
            <h2 class="cc-section-title">¿QUÉ NECESITAS?</h2>
            <p class="cc-section-description">
                Haz click en el servicio para ver opciones y precios
            </p>
        </div>

        <!-- INLINE-TEST removido: cambios aplicados en producción -->

        <?php
        // Nota: la tarjeta compacta destacada fue eliminada por petición del usuario.
        // La rejilla de servicios se muestra más abajo y seguirá mostrando todos los servicios
        // tanto desde la tabla custom `servicios_tecnicos` como desde productos WooCommerce en la
        // categoría 'servicios'.
        ?>
        
        <!-- Services Grid -->
        <div class="cc-services-grid" id="cc-services-grid">
            <?php
            // Añadir también productos de WooCommerce de la categoría 'servicios' si existen.
            // Esto permite que los servicios creados como "Productos" aparezcan aquí.
            if ( function_exists('wc_get_product') ) {
                $cat = get_term_by( 'slug', 'servicios', 'product_cat' );
                if ( $cat && ! is_wp_error( $cat ) ) {
                    $pq = new WP_Query( array(
                        'post_type' => 'product',
                        'posts_per_page' => -1,
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'product_cat',
                                'field' => 'term_id',
                                'terms' => array( $cat->term_id ),
                            ),
                        ),
                    ) );

                    if ( $pq->have_posts() ) {
                        while ( $pq->have_posts() ) {
                            $pq->the_post();
                            $prod_id = get_the_ID();
                            $product = wc_get_product( $prod_id );
                            if ( $product ) {
                                $obj = new stdClass();
                                $obj->id = $prod_id;
                                $obj->nombre = $product->get_name();
                                $short = $product->get_short_description();
                                if ( empty( trim( $short ) ) ) {
                                    $short = wp_strip_all_tags( get_the_excerpt() );
                                }
                                $obj->descripcion = $short;
                                $obj->precio = floatval( $product->get_price() );
                                $obj->tiempo_estimado = get_post_meta( $prod_id, 'tiempo_estimado', true );
                                $obj->icono = 'cpu';
                                // Datos específicos para productos: enlace, miniatura y HTML de precio
                                $obj->is_product = true;
                                $obj->permalink = get_permalink( $prod_id );
                                $obj->thumbnail = get_the_post_thumbnail_url( $prod_id, 'medium' );
                                $obj->price_html = $product->get_price_html();
                                // Añadir al array de servicios para que el loop que viene abajo los muestre
                                $servicios[] = $obj;
                            }
                        }
                        wp_reset_postdata();
                    }
                }
            }

            foreach ( $servicios as $servicio ):
                // Imagen por defecto o desde el array de imágenes
                $imagen = isset($service_images[$servicio->nombre]) ?
                         $service_images[$servicio->nombre] :
                         'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=1080';

                // Si el servicio proviene de un producto WooCommerce, usar su miniatura y permalink
                $permalink = '';
                if ( isset( $servicio->thumbnail ) && ! empty( $servicio->thumbnail ) ) {
                    $imagen = $servicio->thumbnail;
                }
                if ( isset( $servicio->permalink ) && ! empty( $servicio->permalink ) ) {
                    $permalink = $servicio->permalink;
                }

                $icono = cc_get_service_icon( isset($servicio->icono) ? $servicio->icono : '' );
                ?>

                <div class="cc-service-card"
                     data-service-id="<?php echo esc_attr( $servicio->id ); ?>"
                     id="service-<?php echo esc_attr( $servicio->id ); ?>">

                    <!-- Service Image -->
                    <div class="cc-service-image">
                        <?php if ( $permalink ): ?><a href="<?php echo esc_url( $permalink ); ?>"><?php endif; ?>
                            <img src="<?php echo esc_url( $imagen ); ?>"
                                 alt="<?php echo esc_attr( $servicio->nombre ); ?>"
                                 loading="lazy" />
                        <?php if ( $permalink ): ?></a><?php endif; ?>
                        <div class="cc-service-overlay"></div>

                        <!-- Icon Badge -->
                        <div class="cc-service-icon-badge">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <?php echo $icono; ?>
                            </svg>
                        </div>

                        <!-- Price Badge -->
                        <div class="cc-service-price-badge">
                            <?php
                            if ( isset( $servicio->price_html ) && ! empty( $servicio->price_html ) ) {
                                echo wp_kses_post( $servicio->price_html );
                            } else {
                                echo 'Desde S/ ' . number_format( isset( $servicio->precio ) ? $servicio->precio : 0, 2 );
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Service Info -->
                    <div class="cc-service-info">
                        <h3 class="cc-service-name">
                            <?php if ( $permalink ): ?>
                                <a class="cc-service-link" href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $servicio->nombre ); ?></a>
                            <?php else: ?>
                                <?php echo esc_html( $servicio->nombre ); ?>
                            <?php endif; ?>
                        </h3>
                        <p class="cc-service-description">
                            <?php echo esc_html( wp_trim_words( $servicio->descripcion, 12 ) ); ?>
                        </p>

                        <div class="cc-service-footer">
                            <span class="cc-service-duration">
                                <?php echo esc_html( isset( $servicio->tiempo_estimado ) ? $servicio->tiempo_estimado : '' ); ?>
                            </span>
                            <?php if ( $permalink ): ?>
                                <a class="cc-link-more" href="<?php echo esc_url( $permalink ); ?>">VER MÁS <span class="arrow">→</span></a>
                            <?php else: ?>
                                <button class="cc-service-toggle" onclick="ccServices.toggleService(<?php echo esc_attr( $servicio->id ); ?>)">
                                    <span class="toggle-text">VER MÁS</span>
                                    <svg class="toggle-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Expanded Service Details (Se llena dinámicamente con JS) -->
        <div id="cc-service-details" class="cc-service-details">
            <!-- Contenido dinámico -->
        </div>
        
    </div>
</section>

<!-- Info Section -->
<section class="cc-info-section">
    <div class="container">
        <div class="cc-info-grid">
            
            <!-- Left Column -->
            <div class="cc-info-content">
                <h2 class="cc-info-title">PRECIOS TRANSPARENTES</h2>
                <p class="cc-info-text">
                    Sin costos ocultos. Presupuesto detallado antes de iniciar. Diagnóstico gratuito.
                </p>
                
                <ul class="cc-info-list">
                    <li>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        <span>Diagnóstico gratuito en todos los servicios</span>
                    </li>
                    <li>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        <span>Presupuesto sin compromiso</span>
                    </li>
                    <li>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        <span>Garantía de 90 días</span>
                    </li>
                    <li>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        <span>Pago después de completar</span>
                    </li>
                </ul>
            </div>
            
            <!-- Right Column -->
            <div class="cc-info-cards">
                <div class="cc-info-card">
                    <div class="cc-card-label">SERVICIO EXPRESS</div>
                    <div class="cc-card-value">24-48 HORAS</div>
                    <div class="cc-card-text">Reparaciones urgentes (+30%)</div>
                </div>
                <div class="cc-info-card">
                    <div class="cc-card-label">A DOMICILIO</div>
                    <div class="cc-card-value">DISPONIBLE</div>
                    <div class="cc-card-text">Recogida y entrega gratis</div>
                </div>
            </div>
            
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cc-cta-section">
    <div class="container">
        <div class="cc-cta-content">
            <h2 class="cc-cta-title">¿NECESITAS AYUDA?</h2>
            <p class="cc-cta-text">Lunes a sábado de 9:00 a 20:00</p>
            
            <div class="cc-cta-buttons">
                <a href="<?php echo get_permalink(get_page_by_path('contacto')); ?>" class="cc-cta-btn-primary">
                    <span>CONTACTAR</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
                <a href="tel:+525512345678" class="cc-cta-btn-secondary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                    <span>(55) 1234-5678</span>
                </a>
            </div>
        </div>
    </div>
</section>

<?php
/**
 * Función auxiliar para obtener SVG de iconos de servicios
 */
function cc_get_service_icon($icon_name) {
    $icons = array(
        'wrench' => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>',
        'cpu' => '<rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect><rect x="9" y="9" width="6" height="6"></rect><line x1="9" y1="1" x2="9" y2="4"></line><line x1="15" y1="1" x2="15" y2="4"></line><line x1="9" y1="20" x2="9" y2="23"></line><line x1="15" y1="20" x2="15" y2="23"></line><line x1="20" y1="9" x2="23" y2="9"></line><line x1="20" y1="14" x2="23" y2="14"></line><line x1="1" y1="9" x2="4" y2="9"></line><line x1="1" y1="14" x2="4" y2="14"></line>',
        'download' => '<polyline points="8 17 12 21 16 17"></polyline><line x1="12" y1="12" x2="12" y2="21"></line><path d="M20.88 18.09A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.29"></path>',
        'settings' => '<circle cx="12" cy="12" r="3"></circle><path d="M12 1v6m0 6v6m8.66-14.66l-4.24 4.24m-4.24 4.24l-4.24 4.24M23 12h-6m-6 0H1m18.66 8.66l-4.24-4.24m-4.24-4.24l-4.24-4.24"></path>',
        'gamepad' => '<line x1="6" y1="12" x2="10" y2="12"></line><line x1="8" y1="10" x2="8" y2="14"></line><line x1="15" y1="13" x2="15.01" y2="13"></line><line x1="18" y1="11" x2="18.01" y2="11"></line><rect x="2" y="6" width="20" height="12" rx="2"></rect>',
        'briefcase' => '<rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>',
        'shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>',
        'database' => '<ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>',
    );
    
    return isset($icons[strtolower($icon_name)]) ? $icons[strtolower($icon_name)] : $icons['wrench'];
}

get_footer();
?>
