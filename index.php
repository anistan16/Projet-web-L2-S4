<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="AniPriZZA — Pizzas artisanales cuites au feu de bois. Ingrédients d'exception importés d'Italie.">
    <title>AniPriZZA | L'Excellence Italienne</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <nav class="navbar">
        <div class="logo">AniPri<span>ZZA</span></div>
        <button class="nav-toggle" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
        <div class="nav-links">
            <a href="index.php" class="active">Accueil</a>
            <a href="pages/produits.php">La Carte</a>
            <a href="pages/panier.php">Panier <span class="cart-badge" id="cart-count">0</span></a>
            <a href="pages/login.php">Mon Compte</a>
            <a href="pages/contact.php">Contact</a>
        </div>
    </nav>

    <header class="hero">
        <div class="hero-bg"></div>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <p class="hero-eyebrow">Napoli · Depuis 2022</p>
            <h1>L'Art de la<br><em>Vraie Pizza</em></h1>
            <p class="hero-desc">Cuisson au feu de bois à 450°C, ingrédients d'exception importés d'Italie. Une expérience culinaire unique imaginée par Judeken &amp; Peter.</p>
            <div class="hero-actions">
                <a href="pages/produits.php" class="btn btn-primary">Découvrir la carte</a>
                <a href="pages/contact.php" class="btn btn-outline">Nous trouver</a>
            </div>
        </div>
        <div class="hero-stats">
            <div class="stat">
                <span class="stat-num">450°</span>
                <span class="stat-label">Feu de bois</span>
            </div>
            <div class="stat">
                <span class="stat-num">90"</span>
                <span class="stat-label">Cuisson</span>
            </div>
            <div class="stat">
                <span class="stat-num">100%</span>
                <span class="stat-label">Artisanal</span>
            </div>
        </div>
    </header>

    <section class="features-strip">
        <div class="feature-item slide-up">
            <div class="feature-icon">🔥</div>
            <div>
                <h3>Four à bois 450°C</h3>
                <p>Cuisson traditionnelle en 90 secondes pour une pâte aérienne et une croûte parfaitement dorée.</p>
            </div>
        </div>
        <div class="feature-item slide-up" data-delay="0.15">
            <div class="feature-icon">🍅</div>
            <div>
                <h3>Tomates San Marzano</h3>
                <p>Mûries au soleil sur les pentes du Vésuve et importées directement de Campanie.</p>
            </div>
        </div>
        <div class="feature-item slide-up" data-delay="0.3">
            <div class="feature-icon">🛵</div>
            <div>
                <h3>Livraison express</h3>
                <p>Votre pizza arrive chaude et croustillante chez vous en moins de 30 minutes.</p>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="section-header">
            <div>
                <p class="section-eyebrow">Nos Incontournables</p>
                <h2 class="section-title">Les Classiques<br>de la Maison</h2>
            </div>
            <a href="pages/produits.php" class="btn btn-outline">Voir tout le menu</a>
        </div>

        <div class="grid">
            <div class="card slide-up" data-delay="0">
                <div class="card-img-wrap">
                    <img src="https://images.unsplash.com/photo-1574071318508-1cdbab80d002?q=80&w=800" alt="Margherita" loading="lazy">
                    <span class="card-badge">Classique</span>
                </div>
                <div class="card-body">
                    <h3>Margherita</h3>
                    <p>Sauce tomate San Marzano, Fior di Latte, Basilic frais, Huile d'olive extra-vierge.</p>
                    <div class="card-footer">
                        <span class="card-price">11,90 <small>€</small></span>
                        <a href="pages/produits.php" class="btn btn-primary">Commander</a>
                    </div>
                </div>
            </div>
            <div class="card slide-up" data-delay="0.1">
                <div class="card-img-wrap">
                    <img src="https://images.unsplash.com/photo-1628840042765-356cda07504e?q=80&w=800" alt="Diavola" loading="lazy">
                    <span class="card-badge">🌶 Spicy</span>
                </div>
                <div class="card-body">
                    <h3>Diavola</h3>
                    <p>Sauce tomate, Mozzarella, Salami piquant, Piment rouge calabrais, Origan séché.</p>
                    <div class="card-footer">
                        <span class="card-price">13,90 <small>€</small></span>
                        <a href="pages/produits.php" class="btn btn-primary">Commander</a>
                    </div>
                </div>
            </div>
            <div class="card slide-up" data-delay="0.2">
                <div class="card-img-wrap">
                    <img src="https://images.unsplash.com/photo-1600628421055-4d30de868b8f?q=80&w=800" alt="Truffe" loading="lazy">
                    <span class="card-badge">✨ Premium</span>
                </div>
                <div class="card-body">
                    <h3>Truffe &amp; Roquette</h3>
                    <p>Crème de truffe noire, Mozzarella, Jambon de Parme 18 mois, Roquette, Copeaux de Parmesan.</p>
                    <div class="card-footer">
                        <span class="card-price">18,90 <small>€</small></span>
                        <a href="pages/produits.php" class="btn btn-primary">Commander</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-grid">
            <div class="footer-brand">
                <div class="logo">AniPri<span>ZZA</span></div>
                <p>L'excellence de la pizza napolitaine, cuisinée avec passion depuis 2022 par Judeken &amp; Peter.</p>
            </div>
            <div class="footer-col">
                <h4>Navigation</h4>
                <a href="index.php">Accueil</a>
                <a href="pages/produits.php">La Carte</a>
                <a href="pages/panier.php">Panier</a>
                <a href="pages/contact.php">Contact</a>
            </div>
            <div class="footer-col">
                <h4>Horaires</h4>
                <a>Lun – Ven : 11h30 – 22h</a>
                <a>Sam – Dim : 12h00 – 23h</a>
                <a>Livraison dès 17h</a>
            </div>
        </div>
        <div class="footer-bottom">
            <span>&copy; 2026 AniPriZZA. Conçu avec passion.</span>
            <span>Projet L2 Informatique · Judeken &amp; Peter</span>
        </div>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
