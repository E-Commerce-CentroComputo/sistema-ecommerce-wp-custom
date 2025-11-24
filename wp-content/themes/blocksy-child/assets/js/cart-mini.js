/* Mini cart dropdown on hover — safe progressive enhancement
   - Busca en el header un elemento que enlace al carrito y, si existe, añade un dropdown
   - Recupera contenido del fragmento de WooCommerce vía wc-ajax=get_refreshed_fragments
   - Muestra la lista de productos en un dropdown minimalista al pasar el mouse
*/
(function(){
    'use strict';

    function qs(selector, root=document){ return root.querySelector(selector); }
    function qsa(selector, root=document){ return Array.from(root.querySelectorAll(selector)); }

    // Attempt to find cart trigger in header
    function findCartTrigger(){
        const candidates = [
            'a[href*="/cart" i]',
            'a[href*="/carrito" i]',
            '[aria-label*="cart" i]',
            '[aria-label*="carrito" i]',
            '.site-header-cart',
            '.header-cart',
            '.blocksy-cart',
            '.woocommerce-cart',
            '.widget_shopping_cart'
        ];

        for (let sel of candidates){
            const el = qs(sel);
            if (el) return el;
        }
        return null;
    }

    // Build dropdown container and attach to body near trigger
    function buildDropdown(){
        const container = document.createElement('div');
        container.className = 'cc-mini-cart';
        container.setAttribute('aria-hidden','true');
        container.innerHTML = '<div class="cc-mini-cart-inner"><div class="cc-mini-cart-loading">Cargando...</div></div>';
        document.body.appendChild(container);
        return container;
    }

    // Position dropdown under trigger
    function positionDropdown(trigger, dropdown){
        const rect = trigger.getBoundingClientRect();
        // Use fixed positioning so the dropdown stays relative to the viewport
        // and we can clamp it inside the visible area.
    const viewportW = document.documentElement.clientWidth || window.innerWidth;
    // Aumentar un poco la anchura máxima y el mínimo para tarjetas más claras
    const desiredWidth = Math.min(520, Math.max(320, rect.width * 2));
        let left = Math.round(rect.left);

        // If the dropdown would overflow to the right, clamp it
        if (left + desiredWidth > viewportW - 8) {
            left = Math.max(8, viewportW - 8 - desiredWidth);
        }
        // Make sure left is not negative or too close to edge
        left = Math.max(8, left);

        dropdown.style.position = 'fixed';
        dropdown.style.left = left + 'px';
        // place it just below the trigger within viewport coordinates
        dropdown.style.top = (rect.bottom + 8) + 'px';
        dropdown.style.zIndex = 99999;
        dropdown.style.minWidth = desiredWidth + 'px';
        dropdown.style.maxWidth = Math.min(desiredWidth, viewportW - 16) + 'px';
    }

    // Small helper to clamp numeric values
    function clamp(v, a, b){ return Math.max(a, Math.min(b, v)); }

    // Fetch fragments from WooCommerce (standard endpoint)
    // Add an in-memory cache (short TTL) to avoid repeated requests on frequent hovers
    // and allow manual update via custom event 'cc_cart_updated'.
    const __cc_frag_cache = { ts: 0, data: null, inflight: null };
    function fetchFragments(){
        const now = Date.now();
        const TTL = 10000; // 10s cache to reduce perceived slowness on hover
        if ( __cc_frag_cache.data && (now - __cc_frag_cache.ts) < TTL ) {
            return Promise.resolve(__cc_frag_cache.data);
        }
        if ( __cc_frag_cache.inflight ) return __cc_frag_cache.inflight;

        const url = window.location.origin + window.location.pathname + '?wc-ajax=get_refreshed_fragments';
        __cc_frag_cache.inflight = fetch(url, {
            credentials: 'same-origin',
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).then(r => r.json()).then(json => {
            __cc_frag_cache.ts = Date.now();
            __cc_frag_cache.data = json;
            __cc_frag_cache.inflight = null;
            return json;
        }).catch((e)=>{ __cc_frag_cache.inflight = null; return null; });

        return __cc_frag_cache.inflight;
    }

    // Refresh cache immediately (useful after adding to cart)
    function refreshFragmentsNow(){
        // force fetch and replace cache
        __cc_frag_cache.ts = 0;
        return fetchFragments();
    }

    // Accept direct fragments payload from other scripts: update cache and optionally re-render
    document.addEventListener('cc_cart_updated', function(e){
        try {
            const payload = e && e.detail ? e.detail : null;
            if (!payload) return;
            __cc_frag_cache.data = payload;
            __cc_frag_cache.ts = Date.now();
            // if dropdown is open, update UI
            const dd = document.querySelector('.cc-mini-cart.open, .cc-mini-cart[aria-hidden="false"]');
            if ( dd ) {
                const html = extractMiniCartHTML(payload) || payload?.fragments?.['div.widget_shopping_cart_content'] || null;
                renderDropdown(dd, html);
            }
        } catch(err){ /* ignore */ }
    });

    // Extract mini cart HTML from fragments
    function extractMiniCartHTML(fragments){
        if (!fragments) return null;
        // Try common keys
        const keys = Object.keys(fragments || {});
        for (let k of keys){
            if (k.indexOf('widget_shopping_cart') !== -1 || k.indexOf('woocommerce_cart') !== -1) {
                return fragments[k];
            }
        }
        // try fragments['fragments'] nested
        if (fragments.fragments){
            for (let k in fragments.fragments){
                if (k.indexOf('widget_shopping_cart') !== -1) return fragments.fragments[k];
            }
        }
        return null;
    }

    // Render fetched HTML into dropdown
    function renderDropdown(dropdown, html){
        const inner = dropdown.querySelector('.cc-mini-cart-inner');
        if (!inner) return;
        inner.innerHTML = html || '<div class="cc-mini-cart-empty">No hay productos en el carrito</div>';
    }

    // Attach hover handlers
    function attachHandlers(trigger, dropdown){
    let openTimer = null, closeTimer = null, pointerCloseTimer = null;
    const OPEN_DELAY = 50;   // ms
    const CLOSE_DELAY = 80; // ms (shorter so it closes reliably when pointer leaves)

        function open(){
            clearTimeout(closeTimer);
            clearTimeout(pointerCloseTimer);
            dropdown.setAttribute('aria-hidden','false');
            dropdown.classList.add('open');
            positionDropdown(trigger, dropdown);
        }
        function close(){
            clearTimeout(openTimer);
            clearTimeout(pointerCloseTimer);
            clearTimeout(closeTimer);
            dropdown.setAttribute('aria-hidden','true');
            dropdown.classList.remove('open');
        }

        // Use pointer events for more consistent behavior across devices
        trigger.addEventListener('pointerenter', function(){
            clearTimeout(closeTimer);
            openTimer = setTimeout(function(){
                open();
                // refresh content when opening (and use cache)
                    // first try cached data for instant render
                    const cached = __cc_frag_cache.data;
                    if ( cached ) {
                        const htmlCached = extractMiniCartHTML(cached) || cached?.fragments?.['div.widget_shopping_cart_content'] || null;
                        renderDropdown(dropdown, htmlCached);
                        // then refresh in background for freshness
                        fetchFragments().then(data => {
                            const html = extractMiniCartHTML(data) || data?.fragments?.['div.widget_shopping_cart_content'] || null;
                            renderDropdown(dropdown, html);
                        });
                    } else {
                        // no cache, fetch and render
                        fetchFragments().then(data => {
                            const html = extractMiniCartHTML(data) || data?.fragments?.['div.widget_shopping_cart_content'] || null;
                            renderDropdown(dropdown, html);
                        });
                    }
            }, OPEN_DELAY);
        });
        trigger.addEventListener('pointerleave', function(){
            clearTimeout(openTimer);
            closeTimer = setTimeout(close, CLOSE_DELAY);
        });

        // keep dropdown open when hovered
        dropdown.addEventListener('pointerenter', function(){ clearTimeout(closeTimer); clearTimeout(pointerCloseTimer); dropdown.classList.add('open'); dropdown.setAttribute('aria-hidden','false'); });
        dropdown.addEventListener('pointerleave', function(){ pointerCloseTimer = setTimeout(close, CLOSE_DELAY); });

        // Fallback: if pointer moves far away from both trigger and dropdown, close quickly
        function onPointerMove(e){
            if (!dropdown.classList.contains('open')) return;
            const p = { x: e.clientX, y: e.clientY };
            const r1 = trigger.getBoundingClientRect();
            const r2 = dropdown.getBoundingClientRect();
            // expand rects slightly to allow natural diagonal motion
            const MARGIN = 12;
            const expand = r => ({ left: r.left - MARGIN, top: r.top - MARGIN, right: r.right + MARGIN, bottom: r.bottom + MARGIN });
            const er1 = expand(r1), er2 = expand(r2);
            const inRect = function(r){ return p.x >= r.left && p.x <= r.right && p.y >= r.top && p.y <= r.bottom; };
            if (!inRect(er1) && !inRect(er2)){
                // not inside either expanded rect: schedule an immediate close
                clearTimeout(pointerCloseTimer);
                pointerCloseTimer = setTimeout(close, 60);
            } else {
                clearTimeout(pointerCloseTimer);
            }
        }

        // Close when clicking/tapping outside the trigger/dropdown
        function onPointerDown(e){
            try {
                if (!dropdown.classList.contains('open')) return;
                const path = e.composedPath ? e.composedPath() : (e.path || []);
                if (path.length) {
                    if (path.indexOf(trigger) === -1 && path.indexOf(dropdown) === -1) close();
                } else {
                    if (!trigger.contains(e.target) && !dropdown.contains(e.target)) close();
                }
            } catch(err){}
        }

        // Close on Escape key
        function onKeyDown(e){ if (e.key === 'Escape' || e.key === 'Esc') close(); }

        document.addEventListener('pointerdown', onPointerDown);
        document.addEventListener('keydown', onKeyDown);
        document.addEventListener('pointermove', onPointerMove);
    }

    // init
    document.addEventListener('DOMContentLoaded', function(){
        try {
            // If the theme already provides a mini-cart panel (Blocksy), try to reuse it when it has visible content.
            // If the theme panel exists but is hidden/empty (for example because child CSS hid it), fall back to creating our own dropdown.
            const themePanel = document.querySelector('.ct-header-cart .ct-cart-content, #woo-cart-panel, .ct-cart-content');
            if (themePanel) {
                try { themePanel.classList.add('cc-themed-mini'); } catch(e){}

                // helper to determine if element is visible and not display:none
                function isVisible(el){
                    if (!el) return false;
                    const cs = window.getComputedStyle(el);
                    if (cs.display === 'none' || cs.visibility === 'hidden' || cs.opacity === '0') return false;
                    if (el.offsetWidth || el.offsetHeight) return true;
                    return el.getClientRects && el.getClientRects().length > 0;
                }

                // Check if the theme panel contains typical mini-cart content
                const hasContent = !!themePanel.querySelector('.widget_shopping_cart_content, .woocommerce-mini-cart, .cart_list, .cart_item');

                if (isVisible(themePanel) && hasContent) {
                    // reuse theme panel as our dropdown and attach handlers
                    let dropdown = themePanel;
                    try { dropdown.classList.add('cc-mini-cart'); } catch(e){}
                    if (!dropdown.querySelector('.cc-mini-cart-inner')) {
                        const inner = document.createElement('div');
                        inner.className = 'cc-mini-cart-inner';
                        while (dropdown.firstChild) inner.appendChild(dropdown.firstChild);
                        dropdown.appendChild(inner);
                    }
                    const trigger = findCartTrigger();
                    if (trigger) attachHandlers(trigger, dropdown);
                    return;
                }
                // otherwise the theme panel exists but is hidden/empty — continue initialization and build our own dropdown
            }

            const trigger = findCartTrigger();
            if (!trigger) return;

            // avoid double-init
            if (trigger.dataset && trigger.dataset.ccMiniInit) return;
            if (trigger.dataset) trigger.dataset.ccMiniInit = '1';

            // Try to reuse existing mini-cart element provided by the theme
            let dropdown = null;
            try {
                const header = trigger.closest('header, .site-header, .main-header');
                // collect candidates globally but prefer those inside header
                const candidates = [];
                if (header) candidates.push(...Array.from(header.querySelectorAll('.widget_shopping_cart, .woocommerce-mini-cart')));
                candidates.push(...Array.from(document.querySelectorAll('.widget_shopping_cart, .woocommerce-mini-cart')));

                // remove duplicates: keep the one closest to trigger (if any), otherwise first
                if (candidates.length > 0) {
                    // sort by distance to trigger
                    candidates.sort((a,b) => {
                        const ra = a.getBoundingClientRect();
                        const rb = b.getBoundingClientRect();
                        const da = Math.abs(ra.left - trigger.getBoundingClientRect().left) + Math.abs(ra.top - trigger.getBoundingClientRect().top);
                        const db = Math.abs(rb.left - trigger.getBoundingClientRect().left) + Math.abs(rb.top - trigger.getBoundingClientRect().top);
                        return da - db;
                    });

                    const primary = candidates[0];
                    // remove or hide other duplicates
                    for (let i = 1; i < candidates.length; i++) {
                        try { candidates[i].parentNode && candidates[i].parentNode.removeChild(candidates[i]); } catch(e) { try { candidates[i].style.display = 'none'; } catch(e2){} }
                    }

                    // use primary as dropdown
                    dropdown = primary;
                }
            } catch (e) {
                // ignore and fallback
            }

            if (dropdown) {
                // Ensure dropdown has our class and inner wrapper
                dropdown.classList.add('cc-mini-cart');
                if (!dropdown.querySelector('.cc-mini-cart-inner')) {
                    const inner = document.createElement('div');
                    inner.className = 'cc-mini-cart-inner';
                    while (dropdown.firstChild) inner.appendChild(dropdown.firstChild);
                    dropdown.appendChild(inner);
                }
            } else {
                dropdown = buildDropdown();
            }
            attachHandlers(trigger, dropdown);

            // Improve freshness: refresh fragments when product is added to cart
            // listen to common WooCommerce events (both jQuery and native events)
            try {
                // native event used by WC updates
                document.body.addEventListener('wc_fragments_refreshed', function(){ refreshFragmentsNow(); });
                // older event triggered after add to cart
                document.body.addEventListener('added_to_cart', function(){ refreshFragmentsNow(); });
                // also listen for global custom event that some themes/plugins trigger
                document.addEventListener('wc_fragments_refreshed', function(){ refreshFragmentsNow(); });
            } catch(e){}
        } catch (e){ console.warn('cc-mini-cart init failed', e); }
    });

})();
