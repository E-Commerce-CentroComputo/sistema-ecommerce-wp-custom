/**
 * Centro Cómputo - Servicios JavaScript
 * Gestión de servicios técnicos personalizables
 */

// carga verificada: console.info temporal eliminado para producción

const ccServices = (function() {
    'use strict';
    
    // ========================================
    // ESTADO GLOBAL
    // ========================================
    let expandedServiceId = null;
    let selectedOptions = {};
    
    // Datos de servicios (simulados - en producción vendrían del backend)
    const servicesData = {
        1: {
            id: 1,
            name: 'Mantenimiento Preventivo PC',
            description: 'Servicio completo de limpieza y mantenimiento preventivo. Selecciona componentes específicos.',
            basePrice: 299.99,
            options: [
                { id: 1, name: 'Limpieza de ventiladores', description: 'Remoción completa de polvo en ventiladores CPU y GPU', price: 150.00 },
                { id: 2, name: 'Cambio de pasta térmica', description: 'Aplicación de pasta térmica premium', price: 250.00 },
                { id: 3, name: 'Limpieza de teclado', description: 'Limpieza profunda de teclas y switches', price: 120.00 },
                { id: 4, name: 'Limpieza de pantalla', description: 'Limpieza especializada con kit profesional', price: 80.00 },
                { id: 5, name: 'Optimización de sistema', description: 'Limpieza de archivos y desfragmentación', price: 200.00 },
            ]
        },
        2: {
            id: 2,
            name: 'Reparación de Laptop',
            description: 'Servicio de reparación de componentes. Mano de obra especializada.',
            basePrice: 499.99,
            options: [
                { id: 1, name: 'Instalación SSD/HDD', description: 'Instalación y clonado de disco', price: 350.00 },
                { id: 2, name: 'Instalación RAM', description: 'Instalación de módulos RAM', price: 150.00 },
                { id: 3, name: 'Reparación de pantalla', description: 'Diagnóstico y reparación', price: 800.00 },
                { id: 4, name: 'Cambio de batería', description: 'Instalación de batería nueva', price: 300.00 },
                { id: 5, name: 'Diagnóstico placa base', description: 'Con multímetro y microscopio', price: 550.00 },
            ]
        },
        3: {
            id: 3,
            name: 'Instalación de Sistema Operativo',
            description: 'Instalación y configuración completa. Elige lo que necesitas.',
            basePrice: 399.99,
            options: [
                { id: 1, name: 'Windows 11 Pro', description: 'Instalación limpia de Windows 11', price: 450.00 },
                { id: 2, name: 'Windows 10', description: 'Instalación de Windows 10', price: 400.00 },
                { id: 3, name: 'Microsoft Office', description: 'Instalación y activación', price: 250.00 },
                { id: 4, name: 'Drivers completos', description: 'Todos los drivers actualizados', price: 200.00 },
                { id: 5, name: 'Migración de datos', description: 'Transferencia completa de archivos', price: 300.00 },
            ]
        },
        4: {
            id: 4,
            name: 'Recuperación de Datos',
            description: 'Recuperación profesional de archivos perdidos o eliminados.',
            basePrice: 799.99,
            options: [
                { id: 1, name: 'Análisis básico', description: 'Diagnóstico del disco', price: 200.00 },
                { id: 2, name: 'Recuperación estándar', description: 'Hasta 100GB de datos', price: 500.00 },
                { id: 3, name: 'Recuperación avanzada', description: 'Más de 100GB', price: 1000.00 },
                { id: 4, name: 'Disco dañado físicamente', description: 'Reparación en sala limpia', price: 1500.00 },
            ]
        },
        5: {
            id: 5,
            name: 'Configuración de Red',
            description: 'Setup de redes domésticas o empresariales.',
            basePrice: 599.99,
            options: [
                { id: 1, name: 'Router básico', description: 'Configuración WiFi estándar', price: 200.00 },
                { id: 2, name: 'Red mesh', description: 'Sistema mesh completo', price: 400.00 },
                { id: 3, name: 'VPN empresarial', description: 'VPN segura para empresa', price: 800.00 },
                { id: 4, name: 'Optimización de red', description: 'Reducción de latencia', price: 300.00 },
            ]
        },
        6: {
            id: 6,
            name: 'Actualización de Hardware',
            description: 'Instalación de RAM, SSD y otros componentes.',
            basePrice: 199.99,
            options: [
                { id: 1, name: 'Instalación RAM', description: 'Instalación de memoria RAM', price: 100.00 },
                { id: 2, name: 'Instalación SSD', description: 'Con clonado de datos', price: 250.00 },
                { id: 3, name: 'Instalación GPU', description: 'Tarjeta gráfica', price: 200.00 },
                { id: 4, name: 'Upgrade completo', description: 'Múltiples componentes', price: 400.00 },
            ]
        },
        7: {
            id: 7,
            name: 'Eliminación de Virus',
            description: 'Limpieza completa de malware y virus.',
            basePrice: 349.99,
            options: [
                { id: 1, name: 'Escaneo básico', description: 'Antivirus estándar', price: 150.00 },
                { id: 2, name: 'Limpieza profunda', description: 'Eliminación completa', price: 300.00 },
                { id: 3, name: 'Instalación antivirus', description: 'Suite de seguridad', price: 180.00 },
                { id: 4, name: 'Protección avanzada', description: 'Firewall y protección', price: 250.00 },
            ]
        },
        8: {
            id: 8,
            name: 'Consultoría Tecnológica',
            description: 'Asesoría personalizada para proyectos tecnológicos.',
            basePrice: 999.99,
            options: [
                { id: 1, name: 'Consulta 1 hora', description: 'Asesoría básica', price: 500.00 },
                { id: 2, name: 'Consulta 2 horas', description: 'Análisis detallado', price: 900.00 },
                { id: 3, name: 'Plan tecnológico', description: 'Roadmap completo', price: 2000.00 },
                { id: 4, name: 'Implementación', description: 'Implementación guiada', price: 3000.00 },
            ]
        }
    };
    
    // ========================================
    // INICIALIZACIÓN
    // ========================================
    function init() {
        console.log('CC Services initialized');
        // Inicializar observadores de scroll/animaciones
        try {
            setupObservers();
            // micro-interacciones para tarjetas
            setupCardInteractions();
        } catch (e) {
            // fall back silencioso
            console.warn('ccServices: setupObservers fallo', e);
        }
    }
    
    // ========================================
    // TOGGLE SERVICE (Expandir/Contraer)
    // ========================================
    function toggleService(serviceId) {
        const detailsContainer = document.getElementById('cc-service-details');
        
        if (expandedServiceId === serviceId) {
                // Cerrar el servicio actual (modal)
                expandedServiceId = null;
                try { closeService(); } catch (e) {}
            
                // Remover clase active de todas las cards
            document.querySelectorAll('.cc-service-card').forEach(card => {
                card.classList.remove('active');
            });
            
            return;
        }
        
        // Abrir nuevo servicio
        expandedServiceId = serviceId;
        const service = servicesData[serviceId];
        
        if (!service) {
            console.error('Servicio no encontrado:', serviceId);
            return;
        }
        
        // Actualizar clases active
        document.querySelectorAll('.cc-service-card').forEach(card => {
            card.classList.remove('active');
        });
        const currentCard = document.querySelector(`[data-service-id="${serviceId}"]`);
        if (currentCard) {
            currentCard.classList.add('active');
        }
        
        // Renderizar detalles (como modal)
        renderServiceDetails(service);
        // abrir modal
        detailsContainer.classList.add('open');
        document.body.classList.add('cc-modal-open');

        // focus management: focus inner modal after a tick
        setTimeout(() => {
            const inner = detailsContainer.querySelector('.cc-modal-inner');
            if (inner) inner.focus();
        }, 80);
        // attach escape handler
        document.addEventListener('keydown', escHandler);
    }

    function escHandler(e) {
        if (e.key === 'Escape') {
            closeService();
        }
    }

    function closeService() {
        const detailsContainer = document.getElementById('cc-service-details');
        if (!detailsContainer) return;
        detailsContainer.classList.remove('open');
        document.body.classList.remove('cc-modal-open');
        expandedServiceId = null;
        // remover clase active
        document.querySelectorAll('.cc-service-card').forEach(card => card.classList.remove('active'));
        // remove esc handler
        document.removeEventListener('keydown', escHandler);
    }
    
    // ========================================
    // RENDERIZAR DETALLES DEL SERVICIO
    // ========================================
    function renderServiceDetails(service) {
        const detailsContainer = document.getElementById('cc-service-details');
        
        const selectedOpts = selectedOptions[service.id] || [];
        const total = calculateTotal(service.id);
        
        const html = `
            <div class="cc-modal">
                <div class="cc-modal-overlay" tabindex="-1"></div>
                <div class="cc-modal-inner" role="dialog" aria-modal="true" aria-label="Detalles del servicio" tabindex="-1">
                    <button class="cc-modal-close" aria-label="Cerrar servicio">×</button>
                    <div class="cc-details-grid">
                <!-- Left Column -->
                <div class="cc-details-left">
                    
                    <!-- Header -->
                    <div class="cc-details-header">
                        <div class="cc-details-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                ${getServiceIcon(service.id)}
                            </svg>
                        </div>
                        <div class="cc-details-info">
                            <div class="cc-details-badge">SERVICIO SELECCIONADO</div>
                            <h3 class="cc-details-title">${service.name}</h3>
                            <p class="cc-details-description">${service.description}</p>
                            <div class="cc-details-base-price">
                                <span>Cargo base:</span>
                                <span class="price">$${service.basePrice.toFixed(2)}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Service Image -->
                    <div class="cc-details-image">
                        <img src="${getServiceImage(service.id)}" alt="${service.name}" />
                    </div>
                    
                    <!-- Options -->
                    <div>
                        <h4 class="cc-options-header">OPCIONES DISPONIBLES</h4>
                        <div class="cc-options-list">
                            ${service.options.map(option => renderOption(service.id, option)).join('')}
                        </div>
                    </div>
                    
                </div>
                
                <!-- Right Column - Summary -->
                <div class="cc-details-right">
                    <div class="cc-summary-sticky">
                        <div class="cc-summary-card">
                            <h4 class="cc-summary-title">RESUMEN DEL SERVICIO</h4>
                            
                            <div class="cc-summary-content">
                                <div class="cc-summary-row">
                                    <span>Cargo base</span>
                                    <span>$${service.basePrice.toFixed(2)}</span>
                                </div>
                                
                                ${selectedOpts.length > 0 ? `
                                    <div class="cc-summary-options">
                                        <div class="cc-summary-options-title">Opciones (${selectedOpts.length})</div>
                                        ${selectedOpts.map(optId => {
                                            const opt = service.options.find(o => o.id === optId);
                                            return opt ? `
                                                <div class="cc-summary-option-item">
                                                    <span class="cc-summary-option-name">${opt.name}</span>
                                                    <div class="cc-summary-option-price">
                                                        <span>$${opt.price.toFixed(2)}</span>
                                                        <button class="cc-remove-option" onclick="ccServices.toggleOption(${service.id}, ${opt.id})">
                                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                                                <line x1="6" y1="6" x2="18" y2="18"></line>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            ` : '';
                                        }).join('')}
                                    </div>
                                ` : `
                                    <div class="cc-summary-empty">Selecciona opciones</div>
                                `}
                            </div>
                            
                            <div class="cc-summary-total">
                                <div class="cc-summary-total-row">
                                    <span class="cc-summary-total-label">TOTAL</span>
                                    <span class="cc-summary-total-value">$${total.toFixed(2)}</span>
                                </div>
                            </div>
                            
                            <div class="cc-summary-buttons">
                                <button 
                                    class="cc-btn-request" 
                                    onclick="ccServices.requestService(${service.id})"
                                    ${selectedOpts.length === 0 ? 'disabled' : ''}>
                                    SOLICITAR SERVICIO
                                </button>
                                <a href="<?php echo get_permalink(get_page_by_path('contacto')); ?>" class="cc-btn-consult">
                                    CONSULTAR
                                </a>
                            </div>
                        </div>
                        
                        <!-- Benefits -->
                        <div class="cc-summary-benefits">
                            <div class="cc-benefit-row">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Garantía de 90 días</span>
                            </div>
                            <div class="cc-benefit-row">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Técnicos certificados</span>
                            </div>
                            <div class="cc-benefit-row">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Diagnóstico gratuito</span>
                            </div>
                        </div>
                    </div>
                </div>
                    </div>
                </div>
            </div>
        `;

        detailsContainer.innerHTML = html;

        // Attach handlers for overlay and close
        const overlay = detailsContainer.querySelector('.cc-modal-overlay');
        const btnClose = detailsContainer.querySelector('.cc-modal-close');
        if (overlay) overlay.addEventListener('click', closeService);
        if (btnClose) btnClose.addEventListener('click', closeService);
    }
    
    // ========================================
    // RENDERIZAR OPCIÓN
    // ========================================
    function renderOption(serviceId, option) {
        const isSelected = (selectedOptions[serviceId] || []).includes(option.id);
        
        return `
            <div class="cc-option-card ${isSelected ? 'selected' : ''}" 
                 onclick="ccServices.toggleOption(${serviceId}, ${option.id})">
                <div class="cc-option-content">
                    <div class="cc-option-checkbox">
                        <input type="checkbox" ${isSelected ? 'checked' : ''} onclick="event.stopPropagation()" />
                    </div>
                    <div class="cc-option-info">
                        <div class="cc-option-header">
                            <h5 class="cc-option-name">${option.name}</h5>
                            <span class="cc-option-price">$${option.price.toFixed(2)}</span>
                        </div>
                        <p class="cc-option-description">${option.description}</p>
                    </div>
                </div>
            </div>
        `;
    }
    
    // ========================================
    // TOGGLE OPCIÓN (Seleccionar/Deseleccionar)
    // ========================================
    function toggleOption(serviceId, optionId) {
        if (!selectedOptions[serviceId]) {
            selectedOptions[serviceId] = [];
        }
        
        const index = selectedOptions[serviceId].indexOf(optionId);
        if (index > -1) {
            selectedOptions[serviceId].splice(index, 1);
        } else {
            selectedOptions[serviceId].push(optionId);
        }
        
        // Re-renderizar detalles
        const service = servicesData[serviceId];
        if (service) {
            renderServiceDetails(service);
        }
    }
    
    // ========================================
    // CALCULAR TOTAL
    // ========================================
    function calculateTotal(serviceId) {
        const service = servicesData[serviceId];
        if (!service) return 0;
        
        let total = service.basePrice;
        const selected = selectedOptions[serviceId] || [];
        
        selected.forEach(optId => {
            const option = service.options.find(o => o.id === optId);
            if (option) {
                total += option.price;
            }
        });
        
        return total;
    }
    
    // ========================================
    // SOLICITAR SERVICIO
    // ========================================
    function requestService(serviceId) {
        const service = servicesData[serviceId];
        if (!service) return;
        
        const selected = selectedOptions[serviceId] || [];
        
        if (selected.length === 0) {
            showNotification('Por favor selecciona al menos una opción', 'error');
            return;
        }
        
        // Verificar si está logueado
        if (!ccServiciosData.isLoggedIn) {
            showNotification('Debes iniciar sesión para solicitar un servicio', 'error');
            setTimeout(() => {
                window.location.href = '/login';
            }, 1500);
            return;
        }
        
        const total = calculateTotal(serviceId);
        
        // Aquí puedes hacer una llamada AJAX para guardar en la base de datos
        console.log('Solicitando servicio:', {
            serviceId,
            serviceName: service.name,
            options: selected,
            total: total
        });
        
        showNotification(`Solicitud de ${service.name} enviada correctamente`, 'success');
        
        // Limpiar selección
        selectedOptions[serviceId] = [];
        
    // Cerrar detalles (modal)
    expandedServiceId = null;
    try { closeService(); } catch (e) { /* ignore */ }
        
        // Remover clase active
        document.querySelectorAll('.cc-service-card').forEach(card => {
            card.classList.remove('active');
        });
        
        // Redirigir al perfil después de 1.5 segundos
        setTimeout(() => {
            window.location.href = '/perfil';
        }, 1500);
    }
    
    // ========================================
    // HELPERS
    // ========================================
    function getServiceImage(serviceId) {
        const images = {
            1: 'https://images.unsplash.com/photo-1606485940233-76eeff49360c?w=1080',
            2: 'https://images.unsplash.com/photo-1646756089735-487709743361?w=1080',
            3: 'https://images.unsplash.com/photo-1610018556010-6a11691bc905?w=1080',
            4: 'https://images.unsplash.com/photo-1619455052599-4cded9ae462a?w=1080',
            5: 'https://images.unsplash.com/photo-1552820728-8b83bb6b773f?w=1080',
            6: 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=1080',
            7: 'https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?w=1080',
            8: 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=1080',
        };
        return images[serviceId] || images[1];
    }
    
    function getServiceIcon(serviceId) {
        const icons = {
            1: '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>',
            2: '<rect x="2" y="7" width="20" height="15" rx="2" ry="2"></rect><polyline points="17 2 12 7 7 2"></polyline>',
            3: '<polyline points="8 17 12 21 16 17"></polyline><line x1="12" y1="12" x2="12" y2="21"></line><path d="M20.88 18.09A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.29"></path>',
            4: '<ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>',
            5: '<circle cx="12" cy="12" r="2"></circle><path d="M16.24 7.76a6 6 0 0 1 0 8.49m-8.48-.01a6 6 0 0 1 0-8.49m11.31-2.82a10 10 0 0 1 0 14.14m-14.14 0a10 10 0 0 1 0-14.14"></path>',
            6: '<rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect><rect x="9" y="9" width="6" height="6"></rect>',
            7: '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><path d="m9 12 2 2 4-4"></path>',
            8: '<rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>',
        };
        return icons[serviceId] || icons[1];
    }
    
    // ========================================
    // NOTIFICACIONES
    // ========================================
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = 'cc-notification';
        notification.textContent = message;
        
        notification.style.cssText = `
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: ${type === 'error' ? '#dc2626' : '#000'};
            color: #fff;
            padding: 1rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            z-index: 9999;
            animation: slideInUp 0.3s ease-out;
            max-width: 400px;
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOutDown 0.3s ease-out';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }

    // ========================================
    // SCROLL REVEAL / INTERSECTION OBSERVER
    // ========================================
    function setupObservers() {
        const hero = document.querySelector('.cc-hero-inner');
        const benefits = Array.from(document.querySelectorAll('.cc-benefit-item'));
        const cards = Array.from(document.querySelectorAll('.cc-service-card'));

        // Reveal hero with a tiny delay on load
        if (hero) {
            setTimeout(() => hero.classList.add('in-view'), 120);
        }

        if (!('IntersectionObserver' in window)) {
            // fallback: reveal everything
            benefits.forEach(b => b.classList.add('in-view'));
            cards.forEach(c => c.classList.add('in-view'));
            return;
        }

        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                const el = entry.target;

                if (el.classList.contains('cc-service-card')) {
                    // stagger using index
                    const idx = cards.indexOf(el);
                    el.style.transitionDelay = (Math.min(Math.max(idx, 0), 12) * 80) + 'ms';
                }

                el.classList.add('in-view');
                // badge pop animation when card enters view
                if (el.classList.contains('cc-service-card') || el.closest('.cc-service-product-card')) {
                    const cardEl = el.classList.contains('cc-service-card') ? el : el.closest('.cc-service-product-card') || el;
                    const badge = cardEl.querySelector('.cc-price-badge');
                    if (badge) {
                        badge.classList.add('badge-pop');
                        setTimeout(() => badge.classList.remove('badge-pop'), 1200);
                    }
                }
                obs.unobserve(el);
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -8% 0px' });

        benefits.forEach(b => observer.observe(b));
        cards.forEach(c => observer.observe(c));
    }

    // ========================================
    // MICRO-INTERACCIONES PARA TARJETAS
    // ========================================
    function setupCardInteractions() {
        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        const cards = Array.from(document.querySelectorAll('.cc-service-product-card .cc-card'));
        cards.forEach(card => {
            const img = card.querySelector('.cc-card-img');

            card.style.transformStyle = 'preserve-3d';
            card.style.willChange = 'transform';

            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = (e.clientX - rect.left) / rect.width - 0.5; // -0.5 .. 0.5
                const y = (e.clientY - rect.top) / rect.height - 0.5;
                const rx = (y * 6).toFixed(2);
                const ry = (x * -10).toFixed(2);
                card.style.transform = `translateY(-6px) rotateX(${rx}deg) rotateY(${ry}deg)`;
                if (img) img.style.transform = `translate(${(x * 8).toFixed(1)}px, ${(y * 8).toFixed(1)}px) scale(1.04)`;
            });

            card.addEventListener('mouseleave', () => {
                card.style.transform = '';
                if (img) img.style.transform = '';
            });

            // subtle tap feedback for touch
            card.addEventListener('touchstart', () => {
                card.classList.add('touch-active');
            });
            card.addEventListener('touchend', () => {
                card.classList.remove('touch-active');
            });
        });
    }
    
    // ========================================
    // API PÚBLICA
    // ========================================
    return {
        init,
        toggleService,
        toggleOption,
        requestService
    };
    
})();

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    ccServices.init();
});

// Agregar animaciones CSS dinámicas
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInUp {
        from {
            transform: translateY(100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutDown {
        from {
            transform: translateY(0);
            opacity: 1;
        }
        to {
            transform: translateY(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
