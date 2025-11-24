// BACKUP: current custom.js (auto-created)
document.addEventListener('DOMContentLoaded',()=>{

    // HERO
    class HeroCarousel{
        constructor(rootId, delay=5000){
            this.root=document.getElementById(rootId);
            if(!this.root)return;
            this.slides=[...this.root.querySelectorAll('.hero-slide')];
            this.dots=this.root.querySelector('.hero-dots');
            this.current=0;
            this.delay=delay;
            this.init();
        }
        init(){
            this.createDots();
            this.showSlide(0);
            this.start();
            this.bindControls();
        }
        createDots(){
            if(!this.dots)return;
            this.dots.innerHTML='';
            this.slides.forEach((_,i)=>{
                const btn=document.createElement('button');
                btn.className=i===0?'active':'';
                btn.type='button';
                btn.onclick=()=>this.showSlide(i);
                this.dots.appendChild(btn);
            });
        }
        showSlide(i){
            this.current=i;
            const offset=-i*100;
            this.root.querySelector('.hero-slides').style.transform=`translateX(${offset}%)`;
            this.updateDots();
        }
        updateDots(){
            if(!this.dots)return;
            [...this.dots.children].forEach((d,i)=>d.classList.toggle('active',i===this.current));
        }
        next(){this.showSlide((this.current+1)%this.slides.length)}
        start(){this.stop();this.interval=setInterval(()=>this.next(),this.delay)}
        stop(){if(this.interval){clearInterval(this.interval);this.interval=null}}
        bindControls(){
            this.root.addEventListener('mouseenter',()=>this.stop());
            this.root.addEventListener('mouseleave',()=>this.start());
        }
    }

    window.heroCarousel=new HeroCarousel('heroCarousel');

    // RENDER CATEGORIES
    function renderCategories(){
        const track=document.getElementById('categoriesTrack');
        if(!track)return; track.innerHTML='';
        categories.forEach(cat=>{
            track.innerHTML+=`
                <div class="carousel-item">
                    <div class="category-card">
                        <img src="${cat.image}" alt="${cat.name}">
                        <div class="category-overlay"></div>
                        <div class="category-content">
                            <p class="category-count">${cat.count}</p>
                            <h3 class="category-name">${cat.name}</h3>
                        </div>
                    </div>
                </div>`;
        });
    }

    // RENDER PRODUCTS
    function renderProducts(){
        const track=document.getElementById('bestsellersTrack'); if(!track)return; track.innerHTML='';
        const bestsellers=products.filter(p=>p.reviews>400);
        bestsellers.forEach(p=>{
            track.innerHTML+=`
            <div class="carousel-item">
                <div class="product-card">
                    <div class="product-image-wrapper">
                        <img src="${p.image}" alt="${p.name}" class="product-image">
                        <div class="product-badges">
                            ${p.reviews>400?'<div class="product-badge badge-bestseller">Bestseller</div>':''}
                        </div>
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">${p.name}</h3>
                        <p class="product-category">${p.category}</p>
                        <div class="product-price-wrapper">
                            <span class="product-price">$${p.price.toFixed(2)}</span>
                        </div>
                        <button class="product-btn">Agregar al carrito</button>
                    </div>
                </div>
            </div>`;
        });
    }

    renderCategories();
    renderProducts();
});

/* ===========================
   PRODUCTOS DATA
   =========================== */
const products = [
    {
        id: '1',
        name: 'Laptop Dell XPS 15',
        price: 1899.99,
        image: 'https://images.unsplash.com/photo-1737868131581-6379cdee4ec3?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxsYXB0b3AlMjBjb21wdXRlciUyMHRlY2hub2xvZ3l8ZW58MXx8fHwxNzYwMzQzMDQyfDA&ixlib=rb-4.1.0&q=80&w=1080',
        category: 'Laptops',
        rating: 4.8,
        reviews: 245,
        isNew: true,
        isBestseller: false
    },
    {
        id: '2',
        name: 'Laptop Gaming ROG',
        price: 2499.99,
        image: 'https://images.unsplash.com/photo-1640955014216-75201056c829?w=1080',
        category: 'Laptops',
        rating: 4.9,
        reviews: 189,
        isNew: true,
        isBestseller: false
    },
    {
        id: '3',
        name: 'Teclado Mecánico Pro',
        price: 149.99,
        image: 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=1080',
        category: 'Periféricos',
        rating: 4.7,
        reviews: 512,
        isNew: true,
        isBestseller: true
    },
    {
        id: '4',
        name: 'Mouse Gaming Pro',
        price: 79.99,
        image: 'https://images.unsplash.com/photo-1754820978711-611479056f97?w=1080',
        category: 'Periféricos',
        rating: 4.6,
        reviews: 423,
        isNew: true,
        isBestseller: true
    },
    {
        id: '5',
        name: 'Auriculares Gaming 7.1',
        price: 129.99,
        image: 'https://images.unsplash.com/photo-1672925216556-c995d23aab2e?w=1080',
        category: 'Audio',
        rating: 4.5,
        reviews: 356,
        isNew: true,
        isBestseller: false
    },
    {
        id: '6',
        name: 'Monitor 4K UHD 27"',
        price: 449.99,
        image: 'https://images.unsplash.com/photo-1647657411140-be890523470a?w=1080',
        category: 'Monitores',
        rating: 4.8,
        reviews: 289,
        isNew: false,
        isBestseller: false
    },
    {
        id: '7',
        name: 'SSD NVMe 1TB',
        price: 119.99,
        image: 'https://images.unsplash.com/photo-1591238372408-8b98667c0460?w=1080',
        category: 'Componentes',
        rating: 4.9,
        reviews: 634,
        isNew: false,
        isBestseller: true
    },
    {
        id: '8',
        name: 'Smartphone Pro Max',
        price: 999.99,
        image: 'https://images.unsplash.com/photo-1640936343842-268f9d87e764?w=1080',
        category: 'Móviles',
        rating: 4.7,
        reviews: 1024,
        isNew: false,
        isBestseller: true
    }
];

// Initialize cart and UI helpers
let cart = JSON.parse(localStorage.getItem('cart')) || [];

function updateCartCount() {
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        cartCount.textContent = totalItems;
    }
}

function addToCart(productId) {
    const product = products.find(p => p.id === productId);
    if (!product) return;
    const existingItem = cart.find(item => item.id === productId);
    if (existingItem) existingItem.quantity++;
    else cart.push({ id: product.id, name: product.name, price: product.price, image: product.image, quantity: 1 });
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    showNotification(`${product.name} agregado al carrito`);
}

function showNotification(message) {
    const notification = document.createElement('div');
    notification.style.cssText = `position: fixed; top: 100px; right: 20px; background: #000; color: #fff; padding: 1rem 2rem; z-index: 1000; font-size: 0.875rem; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase; animation: slideIn 0.3s ease-out;`;
    notification.textContent = message;
    document.body.appendChild(notification);
    setTimeout(() => { notification.style.animation = 'slideOut 0.3s ease-out'; setTimeout(() => notification.remove(), 300); }, 3000);
}

const style = document.createElement('style');
style.textContent = `@keyframes slideIn { from { transform: translateX(400px); opacity: 0; } to { transform: translateX(0); opacity: 1; } } @keyframes slideOut { from { transform: translateX(0); opacity: 1; } to { transform: translateX(400px); opacity: 0; } }`;
document.head.appendChild(style);

updateCartCount();

// Categories data used by renderCategories
const categories = [
    { name: 'LAPTOPS', image: 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=800', count: 4 },
    { name: 'PERIFÉRICOS', image: 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=800', count: 4 },
    { name: 'AUDIO', image: 'https://images.unsplash.com/photo-1672925216556-c995d23aab2e?w=800', count: 3 },
    { name: 'MONITORES', image: 'https://images.unsplash.com/photo-1647657411140-be890523470a?w=800', count: 3 },
    { name: 'COMPONENTES', image: 'https://images.unsplash.com/photo-1591238372408-8b98667c0460?w=800', count: 5 },
    { name: 'MÓVILES', image: 'https://images.unsplash.com/photo-1640936343842-268f9d87e764?w=800', count: 2 },
    { name: 'ACCESORIOS', image: 'https://images.unsplash.com/photo-1508898578281-774ac4893a11?w=800', count: 3 },
    { name: 'STREAMING', image: 'https://images.unsplash.com/photo-1509395176047-4a66953fd231?w=800', count: 3 }
];

// Hook up simple scroll controls
document.getElementById('categoriesPrev')?.addEventListener('click', () => document.getElementById('categoriesTrack')?.scrollBy({ left: -300, behavior: 'smooth' }));
document.getElementById('categoriesNext')?.addEventListener('click', () => document.getElementById('categoriesTrack')?.scrollBy({ left: 300, behavior: 'smooth' }));

document.getElementById('heroNext')?.addEventListener('click', () => { window.heroCarousel?.next(); });
document.getElementById('heroPrev')?.addEventListener('click', () => { window.heroCarousel?.showSlide((window.heroCarousel.current - 1 + window.heroCarousel.slides.length) % window.heroCarousel.slides.length); });

console.log('Prototype JS loaded');
