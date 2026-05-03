// ── Panier 

function getCart() {
    return JSON.parse(localStorage.getItem('aniCart')) || [];
}

function saveCart(cart) {
    localStorage.setItem('aniCart', JSON.stringify(cart));
}

function updateCartCount() {
    const cart = getCart();
    const count = cart.reduce((s, i) => s + i.qty, 0);
    document.querySelectorAll('.cart-badge').forEach(el => {
        const prev = parseInt(el.textContent) || 0;
        el.textContent = count;
        if (count !== prev) {
            el.classList.add('bump');
            setTimeout(() => el.classList.remove('bump'), 350);
        }
    });
}

function addToCart(id, name, price) {
    const cart = getCart();
    const item = cart.find(i => i.id === id);
    if (item) item.qty++;
    else cart.push({ id, name, price, qty: 1 });
    saveCart(cart);
    updateCartCount();

    const btn = event.currentTarget;
    btn.textContent = '✓ Ajouté';
    btn.disabled = true;
    btn.style.background = '#2e7d32';
    btn.style.boxShadow = '0 8px 20px rgba(46,125,50,0.4)';
    setTimeout(() => {
        btn.textContent = 'Ajouter';
        btn.disabled = false;
        btn.style.background = '';
        btn.style.boxShadow = '';
    }, 1200);
}

function changeQty(index, delta) {
    const cart = getCart();
    cart[index].qty += delta;
    if (cart[index].qty <= 0) cart.splice(index, 1);
    saveCart(cart);
    renderCart();
    updateCartCount();
}

function removeFromCart(index) {
    const cart = getCart();
    cart.splice(index, 1);
    saveCart(cart);
    renderCart();
    updateCartCount();
}

function clearCart() {
    if (!confirm('Vider entièrement le panier ?')) return;
    localStorage.removeItem('aniCart');
    renderCart();
    updateCartCount();
}

function renderCart() {
    const container = document.getElementById('cart-items');
    const totalEl = document.getElementById('cart-total');

    const formEl = document.getElementById('checkout-form');
    const hiddenQty = document.getElementById('hidden_qty');
    const hiddenPrice = document.getElementById('hidden_price');

    if (!container) return;

    const cart = getCart();
    if (cart.length === 0) {
        container.innerHTML = `
            <div class="empty-state slide-up visible">
                <span class="empty-icon">🍕</span>
                <h2>Votre panier est vide</h2>
                <p style="margin-bottom:32px;">Découvrez notre carte et ajoutez vos pizzas préférées.</p>
                <a href="produits.php" class="btn btn-primary">Voir la carte</a>
            </div>`;
        if (totalEl) totalEl.textContent = '0.00';
        if (formEl) formEl.style.display = 'none';
        return;
    }

    if (formEl) {
        formEl.style.display = 'flex';
        formEl.style.opacity = '1';
        formEl.style.transform = 'none';
    }

    let html = '';
    let total = 0;
    let qty = 0;

    cart.forEach((item, idx) => {
        const sub = item.price * item.qty;
        total += sub;
        qty += item.qty;

        html += `
        <div class="cart-item">
            <div>
                <div class="cart-item-name">${item.name}</div>
                <div class="cart-item-meta">${item.price.toFixed(2)} € / unité</div>
            </div>
            <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
                <div class="qty-controls">
                    <button type="button" class="qty-btn" onclick="changeQty(${idx}, -1)">−</button>
                    <span class="qty-value">${item.qty}</span>
                    <button type="button" class="qty-btn" onclick="changeQty(${idx}, +1)">+</button>
                </div>
                <span class="cart-item-price">${sub.toFixed(2)} €</span>
                <button type="button" class="btn-remove" onclick="removeFromCart(${idx})">Retirer</button>
            </div>
        </div>`;
    });

    container.innerHTML = html;
    if (totalEl) totalEl.textContent = total.toFixed(2);

    if (hiddenQty) hiddenQty.value = qty;
    if (hiddenPrice) hiddenPrice.value = total.toFixed(2);
}

// ── Filtrage par catégorie 

function initFilters() {
    const btns = document.querySelectorAll('.filter-btn');
    const cards = document.querySelectorAll('.card[data-cat]');
    if (!btns.length) return;

    btns.forEach(btn => {
        btn.addEventListener('click', () => {
            btns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const cat = btn.dataset.filter;
            cards.forEach(card => {
                const show = cat === 'all' || card.dataset.cat === cat;
                card.style.transition = 'opacity 0.3s, transform 0.3s';
                card.style.opacity = show ? '1' : '0';
                card.style.transform = show ? '' : 'scale(0.96)';
                card.style.pointerEvents = show ? '' : 'none';
                card.style.display = show ? 'flex' : 'none';
            });
            setTimeout(() => {
                cards.forEach(card => {
                    if (card.style.display !== 'none') {
                        card.style.opacity = '1';
                        card.style.transform = '';
                    }
                });
            }, 10);
        });
    });
}

// ── Navbar scrolled 

function initNavbar() {
    const navbar = document.querySelector('.navbar');
    if (!navbar) return;
    const onScroll = () => navbar.classList.toggle('scrolled', window.scrollY > 40);
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
}

// ── Mobile menu 

function initMobileMenu() {
    const toggle = document.querySelector('.nav-toggle');
    const links = document.querySelector('.nav-links');
    if (!toggle || !links) return;
    toggle.addEventListener('click', () => {
        toggle.classList.toggle('open');
        links.classList.toggle('open');
    });
    links.querySelectorAll('a').forEach(a => {
        a.addEventListener('click', () => {
            toggle.classList.remove('open');
            links.classList.remove('open');
        });
    });
}

// ── Scroll animations 

function initScrollAnimations() {
    const observer = new IntersectionObserver(entries => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                const delay = entry.target.dataset.delay || 0;
                setTimeout(() => entry.target.classList.add('visible'), delay * 1000);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.slide-up').forEach(el => observer.observe(el));
}

// ── Hero parallax léger 

function initHeroParallax() {
    const heroBg = document.querySelector('.hero-bg');
    if (!heroBg) return;
    heroBg.classList.add('loaded');
    window.addEventListener('scroll', () => {
        const y = window.scrollY;
        heroBg.style.transform = `scale(1) translateY(${y * 0.3}px)`;
    }, { passive: true });
}

// ── Init 

document.addEventListener('DOMContentLoaded', () => {
    updateCartCount();
    renderCart();
    initNavbar();
    initMobileMenu();
    initScrollAnimations();
    initHeroParallax();
    initFilters();
});