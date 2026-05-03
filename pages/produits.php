<?php include('../config.php'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Carte | AniPriZZA</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <nav class="navbar">
        <div class="logo">AniPri<span>ZZA</span></div>
        <button class="nav-toggle" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
        <div class="nav-links">
            <a href="../index.php">Accueil</a>
            <a href="produits.php" class="active">La Carte</a>
            <a href="panier.php">Panier <span class="cart-badge" id="cart-count">0</span></a>
            <a href="login.php">Mon Compte</a>
            <a href="contact.php">Contact</a>
        </div>
    </nav>

    <main class="container">
        <p class="section-eyebrow">Notre Sélection</p>
        <h1 class="page-title slide-up">Le Menu</h1>
        <p class="page-subtitle slide-up" data-delay="0.1">Des pizzas préparées avec des ingrédients choisis avec soin, cuites à la perfection.</p>

        <!-- Filtres par catégorie -->
        <div class="filter-bar slide-up" data-delay="0.2">
            <button class="filter-btn active" data-filter="all">Tout le menu</button>
            <button class="filter-btn" data-filter="classique">🍕 Classiques</button>
            <button class="filter-btn" data-filter="spicy">🌶 Spicy</button>
            <button class="filter-btn" data-filter="veggie">🥦 Végétarien</button>
            <button class="filter-btn" data-filter="premium">✨ Premium</button>
        </div>

        <div class="grid">
            <?php

            $result = $conn->query("SELECT * FROM pizzas WHERE disponible = 1 ORDER BY categorie, nom");
            $delay  = 0;

            if ($result && $result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
                    $nom  = htmlspecialchars($row['nom']);
                    $ing  = htmlspecialchars($row['ingredients']);
                    $prix = number_format((float)$row['prix'], 2, '.', '');
                    $img  = htmlspecialchars($row['image_url']);
                    $cat  = htmlspecialchars($row['categorie']);

                    // Badge lisible
                    $badges = [
                        'classique' => '🍕 Classique',
                        'spicy'     => '🌶 Spicy',
                        'veggie'    => '🥦 Veggie',
                        'premium'   => '✨ Premium',
                    ];
                    $badge = $badges[$cat] ?? ucfirst($cat);

                    $nomJS = addslashes($nom);
            ?>
            <div class="card slide-up" data-cat="<?= $cat ?>" data-delay="<?= $delay ?>">
                <div class="card-img-wrap">
                    <img src="<?= $img ?>" alt="<?= $nom ?>" loading="lazy">
                    <span class="card-badge"><?= $badge ?></span>
                </div>
                <div class="card-body">
                    <h3><?= $nom ?></h3>
                    <p><?= $ing ?></p>
                    <div class="card-footer">
                        <span class="card-price"><?= number_format((float)$row['prix'], 2, ',', '') ?> <small>€</small></span>
                        <button class="btn btn-primary"
                                onclick="addToCart(<?= $row['id'] ?>, '<?= $nomJS ?>', <?= $prix ?>)">
                            Ajouter
                        </button>
                    </div>
                </div>
            </div>
            <?php
                    $delay = round($delay + 0.08, 2);
                endwhile;
            else:
            ?>
            <div style="grid-column:1/-1; text-align:center; padding:80px 40px; color:var(--text-muted);">
                <p style="font-size:3rem; margin-bottom:16px;">🔥</p>
                <h3 style="font-family:var(--font-display); font-size:1.8rem; font-weight:300; color:var(--cream); margin-bottom:8px;">Le four chauffe...</h3>
                <p>Aucune pizza trouvée. Assurez-vous que la base de données est bien configurée.</p>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <div class="footer-bottom" style="border-top:none; padding-top:0;">
            <span>&copy; 2026 AniPriZZA</span>
            <a href="../index.php" style="color:var(--text-muted);">← Retour à l'accueil</a>
        </div>
    </footer>

    <script src="../js/script.js"></script>
</body>
</html>
