document.addEventListener('DOMContentLoaded', function(){
    const form = document.getElementById('cc-service-options');
    const totalEl = document.querySelector('.cc-summary-total');
    const selectedEl = document.querySelector('.cc-summary-selected');
    const baseEl = document.querySelector('.cc-summary-base');
    const basePrice = baseEl && baseEl.dataset && baseEl.dataset.basePrice ? parseFloat(baseEl.dataset.basePrice) || 0 : 0;

    function formatMoney(n){
        try {
            return new Intl.NumberFormat('es-PE', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(n);
        } catch(e){
            return (typeof n === 'number' ? n.toFixed(2) : parseFloat(n||0).toFixed(2)).replace('.',',');
        }
    }

    function updateSummary(){
        const checked = Array.from(form.querySelectorAll('input[type="checkbox"]:checked'));
    const sum = checked.reduce((s, c) => s + parseFloat(c.dataset.price || 0), 0) + basePrice;
    totalEl.textContent = 'S/. ' + formatMoney(sum);
        if (checked.length) {
            selectedEl.textContent = checked.map(c => c.closest('.cc-option-card').querySelector('.cc-option-title').textContent).join(', ');
        } else {
            selectedEl.textContent = 'Selecciona opciones';
        }
    }

    if (form){
        form.addEventListener('change', updateSummary);
        updateSummary();
    }

    // Debug logs removed for production

    // Add-to-cart for selected subservices
    const requestBtn = document.getElementById('cc-request-service');
    if (requestBtn){
        requestBtn.addEventListener('click', async function(e){
            e.preventDefault();
            const checked = Array.from(form.querySelectorAll('input[type="checkbox"]:checked'));
            if (!checked.length) {
                // If no options selected, add the main product instead
                // Redirect to standard add-to-cart for main product
                const mainAdd = requestBtn.dataset && requestBtn.dataset.ajaxUrl ? null : null;
            }
            // collect product IDs to add (subservices)
            let ids = checked.map(c => parseInt(c.dataset.productId || '0')).filter(n => n > 0);
            // Always include main product (base service) first
            const mainId = parseInt(requestBtn.dataset.mainProductId || '0');
            if (mainId > 0) {
                // if not already present, insert at the start
                if (ids.indexOf(mainId) === -1) ids.unshift(mainId);
            } else {
                // no main id available: fallback - just redirect to cart
                window.location.href = requestBtn.dataset.cartUrl || '/cart';
                return;
            }

            // disable button and show working state
            const origText = requestBtn.textContent;
            requestBtn.disabled = true;
            requestBtn.textContent = 'Agregando…';

            // Send one AJAX request to server to add main product + subservices atomically
            try {
                const ajaxUrl = requestBtn.dataset.ajaxUrl || (window.location.origin + '/wp-admin/admin-ajax.php');
                const fd = new FormData();
                fd.append('action', 'cc_add_service_to_cart');
                fd.append('nonce', requestBtn.dataset.nonce || '');
                fd.append('main_id', mainId);
                // append sub ids as repeated field
                ids.slice(1).forEach(sid => fd.append('subs[]', sid));
                // Collect selected options that don't map to a product (product_id == 0)
                const extras = checked.map(c => ({
                    product_id: parseInt(c.dataset.productId || '0') || 0,
                    label: c.closest('.cc-option-card').querySelector('.cc-option-title').textContent.trim(),
                    price: parseFloat(c.dataset.price || '0') || 0
                })).filter(it => it.product_id === 0 && it.price > 0);
                if ( extras.length ) {
                    fd.append('subs_extra', JSON.stringify(extras));
                }

                const resp = await fetch(ajaxUrl, { method: 'POST', body: fd, credentials: 'same-origin' });
                const json = await resp.json();
                if ( json && json.success ) {
                    // If server returned fragments, dispatch event so mini-cart updates instantly
                    try {
                        if ( json.data && json.data.fragments ) {
                            const ev = new CustomEvent('cc_cart_updated', { detail: json.data.fragments });
                            document.dispatchEvent(ev);
                        }
                        // Also dispatch a generic event some themes listen to
                        try { document.body.dispatchEvent(new Event('added_to_cart')); } catch(e){}
                    } catch(e){}
                    // redirect to cart
                    window.location.href = requestBtn.dataset.cartUrl || '/cart';
                    return;
                } else {
                    requestBtn.disabled = false;
                    requestBtn.textContent = origText;
                    // Try to provide helpful feedback: include failed items if present
                    let msg = (json && json.data && json.data.message) ? json.data.message : 'No se pudo agregar al carrito. Intenta de nuevo.';
                    if ( json && json.data && json.data.failed ) {
                        try {
                            const failed = json.data.failed;
                            const parts = failed.map(f => (typeof f === 'object' ? (f.id + ' (' + (f.reason||'error') + ')') : String(f)) );
                            msg += '\n\nElementos no añadidos: ' + parts.join(', ');
                        } catch(e){ /* ignore formatting errors */ }
                    }
                    alert( msg );
                    return;
                }
            } catch (err) {
                requestBtn.disabled = false;
                requestBtn.textContent = origText;
                alert('Error de red al intentar agregar al carrito.');
                return;
            }
        });
    }
});
