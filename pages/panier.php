<?php
include('../config.php');
$msg = "";
$msg_type = "";
$clear_cart = false;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['checkout'])) {
    
    if (!isset($_SESSION['user_id'])) {
        $msg = "Vous devez être connecté pour passer une commande.";
        $msg_type = "error";
    } else {
        $uid = (int)$_SESSION['user_id'];
        $total_qty = (int)$_POST['total_qty'];
        $total_price = (float)$_POST['total_price'];

        if ($total_qty > 0) {
            $user_query = $conn->query("SELECT points_fidelite FROM users WHERE id=$uid");
            $points = (int)$user_query->fetch_assoc()['points_fidelite'];

            $discount = 0;
            
            if (isset($_POST['use_points']) && $points >= 100) {
                $discount = 12.90;
                $conn->query("UPDATE users SET points_fidelite = points_fidelite - 100 WHERE id=$uid");
            }

            $final_price = $total_price - $discount;
            if ($final_price < 0) $final_price = 0;

            $points_earned = $total_qty * 2;
            $conn->query("UPDATE users SET points_fidelite = points_fidelite + $points_earned WHERE id=$uid");

            $msg = "Commande validée ! Total payé : " . number_format($final_price, 2) . " €. Vous avez gagné ⭐ $points_earned points.";
            $msg_type = "success";
            $clear_cart = true;
        } else {
            $msg = "Votre panier est vide.";
            $msg_type = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier | AniPriZZA</title>
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
            <a href="produits.php">La Carte</a>
            <a href="panier.php" class="active">Panier <span class="cart-badge" id="cart-count">0</span></a>
            <a href="login.php">Mon Compte</a>
            <a href="contact.php">Contact</a>
        </div>
    </nav>

    <main class="container">
        <p class="section-eyebrow">Récapitulatif</p>
        <h1 class="page-title slide-up">Votre Commande</h1>

        <div style="max-width: 780px; margin: 0 auto;">

            <?php if ($msg): ?>
            <div class="alert alert-<?= $msg_type ?> slide-up visible" style="margin-bottom: 30px;">
                <?= htmlspecialchars($msg) ?>
            </div>
            <?php endif; ?>

            <?php if (!$clear_cart): ?>
            <div id="cart-items"></div>

            <form method="POST" id="checkout-form" class="cart-total-box" style="display:none;">
                
                <input type="hidden" name="total_qty" id="hidden_qty" value="0">
                <input type="hidden" name="total_price" id="hidden_price" value="0">

                <div style="flex:1;">
                    <div class="cart-total-label">Total estimé</div>
                    <div class="cart-total-value">
                        <span id="cart-total">0.00</span> <span>€</span>
                    </div>
                    <p style="font-size:0.8rem; color:var(--text-muted); margin-top:8px;">Livraison incluse dans un rayon de 5 km</p>
                    
                    <div style="margin-top:24px; padding-top:24px; border-top:1px solid var(--border);">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <?php
                                $uid_fid = (int)$_SESSION['user_id'];
                                $pts_query = $conn->query("SELECT points_fidelite FROM users WHERE id=$uid_fid");
                                $pts = $pts_query ? (int)$pts_query->fetch_assoc()['points_fidelite'] : 0;
                            ?>
                            <h4 style="color:var(--gold); margin-bottom:12px; font-family:var(--font-display); font-size:1.4rem;">⭐ Fidélité AniPriZZA</h4>
                            
                            <?php if ($pts >= 100): ?>
                                <label style="display:flex; align-items:center; gap:12px; cursor:pointer; background:rgba(201, 169, 110, 0.1); padding:12px 16px; border-radius:8px; border:1px solid var(--gold);">
                                    <input type="checkbox" name="use_points" value="1" style="width:20px; height:20px; accent-color:var(--gold);">
                                    <span style="color:var(--cream); font-weight:500;">Utiliser 100 points pour une pizza offerte (-12.90 €)</span>
                                </label>
                                <p style="font-size:0.85rem; color:var(--text-muted); margin-top:8px;">Votre solde actuel : <?= $pts ?> points</p>
                            <?php else: ?>
                                <p style="font-size:0.9rem; color:var(--text-muted);">Vous avez ⭐ <?= $pts ?> points. Encore <?= 100 - $pts ?> points pour avoir une pizza gratuite !</p>
                            <?php endif; ?>
                            
                        <?php else: ?>
                            <p style="font-size:0.9rem; color:var(--gold);">
                                <a href="login.php" style="color:var(--gold); text-decoration:underline; font-weight:600;">Connectez-vous</a> pour cumuler des points de fidélité à chaque pizza !
                            </p>
                        <?php endif; ?>
                    </div>
                </div>

                <div style="display:flex; gap:12px; flex-wrap:wrap; justify-content:flex-end; align-items:flex-end;">
                    <button type="button" class="btn btn-ghost" onclick="clearCart()">Vider le panier</button>
                    <button type="submit" name="checkout" class="btn btn-primary">
                        🍕 &nbsp;Passer commande
                    </button>
                </div>
            </form>
            <?php else: ?>
                <div style="text-align:center; padding: 40px 0;">
                    <a href="produits.php" class="btn btn-outline">Recommander d'autres pizzas</a>
                </div>
            <?php endif; ?>

        </div>
    </main>

    <footer>
        <div class="footer-bottom" style="border-top:none; padding-top:0;">
            <span>&copy; 2026 AniPriZZA</span>
            <a href="produits.php" style="color:var(--text-muted);">← Continuer mes achats</a>
        </div>
    </footer>

    <script src="../js/script.js"></script>
    
    <?php if ($clear_cart): ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            localStorage.removeItem('aniCart');
            updateCartCount();
        });
    </script>
    <?php endif; ?>
</body>
</html>