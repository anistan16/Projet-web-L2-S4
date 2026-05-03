<?php
include('../config.php');
$msg      = "";
$msg_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom    = trim($conn->real_escape_string($_POST['nom']));
    $email  = trim($conn->real_escape_string($_POST['email']));
    $sujet  = trim($conn->real_escape_string($_POST['sujet']));
    $message = trim($conn->real_escape_string($_POST['message']));

    if ($nom && $email && $sujet && $message) {
        $sql = "INSERT INTO messages (nom, email, sujet, message) VALUES ('$nom', '$email', '$sujet', '$message')";
        if ($conn->query($sql) === TRUE) {
            $msg      = "Message envoyé ! Notre équipe vous répondra dans les plus brefs délais.";
            $msg_type = "success";
        } else {
            $msg      = "Une erreur est survenue. Veuillez réessayer.";
            $msg_type = "error";
        }
    } else {
        $msg      = "Tous les champs sont obligatoires.";
        $msg_type = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact | AniPriZZA</title>
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
            <a href="panier.php">Panier <span class="cart-badge" id="cart-count">0</span></a>
            <a href="login.php">Mon Compte</a>
            <a href="contact.php" class="active">Contact</a>
        </div>
    </nav>

    <main class="container">
        <p class="section-eyebrow">On vous écoute</p>
        <h1 class="page-title slide-up">Nous contacter</h1>
        <p class="page-subtitle slide-up" data-delay="0.1">Une question, une commande spéciale ou juste envie de nous dire bonjour ?</p>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:40px; max-width:1000px; margin:0 auto; align-items:start;">

            <!-- Formulaire -->
            <form class="form-box slide-up" method="POST" data-delay="0.15">
                <?php if ($msg): ?>
                <div class="alert alert-<?= $msg_type ?>"><?= htmlspecialchars($msg) ?></div>
                <?php endif; ?>

                <h2>Envoyer un message</h2>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom" placeholder="Votre nom" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="votre@email.fr" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="sujet">Sujet</label>
                    <input type="text" id="sujet" name="sujet" placeholder="Comment puis-je vous aider ?" required>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" placeholder="Votre message..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;">
                    Envoyer le message
                </button>
            </form>

            <!-- Infos de contact -->
            <div class="slide-up" data-delay="0.3">
                <div class="form-box" style="margin-bottom:20px;">
                    <h2 style="font-size:1.4rem; margin-bottom:24px;">Nos coordonnées</h2>
                    <div style="display:flex; flex-direction:column; gap:20px;">
                        <div style="display:flex; gap:16px; align-items:flex-start;">
                            <span style="font-size:1.4rem; flex-shrink:0;">📍</span>
                            <div>
                                <div style="font-weight:600; color:var(--cream); margin-bottom:4px;">Adresse</div>
                                <div style="font-size:0.9rem; color:var(--text-muted);">12 Rue de la Pizzeria<br>75011 Paris, France</div>
                            </div>
                        </div>
                        <div style="display:flex; gap:16px; align-items:flex-start;">
                            <span style="font-size:1.4rem; flex-shrink:0;">🕐</span>
                            <div>
                                <div style="font-weight:600; color:var(--cream); margin-bottom:4px;">Horaires</div>
                                <div style="font-size:0.9rem; color:var(--text-muted);">Lun–Ven : 11h30 – 22h00<br>Sam–Dim : 12h00 – 23h00</div>
                            </div>
                        </div>
                        <div style="display:flex; gap:16px; align-items:flex-start;">
                            <span style="font-size:1.4rem; flex-shrink:0;">📞</span>
                            <div>
                                <div style="font-weight:600; color:var(--cream); margin-bottom:4px;">Téléphone</div>
                                <div style="font-size:0.9rem; color:var(--text-muted);">01 23 45 67 89</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <footer>
        <div class="footer-bottom" style="border-top:none; padding-top:0;">
            <span>&copy; 2026 AniPriZZA</span>
            <a href="../index.php" style="color:var(--text-muted);">← Retour à l'accueil</a>
        </div>
    </footer>

    <script src="../js/script.js"></script>

    <style>
        @media (max-width: 768px) {
            main > div[style*="grid-template-columns:1fr 1fr"] {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
</body>
</html>
