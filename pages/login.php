<?php
include('../config.php');
$msg = "";
$msg_type = "";
$is_logged = false;
$logged_user = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // ── Inscription
    if (isset($_POST['register'])) {
        $nom   = trim($conn->real_escape_string($_POST['nom']));
        $email = trim($conn->real_escape_string($_POST['email']));
        $pwd   = $_POST['password'] ?? '';

        if (strlen($pwd) < 6) {
            $msg      = "Le mot de passe doit contenir au moins 6 caractères.";
            $msg_type = "error";
        } else {
            $hash  = password_hash($pwd, PASSWORD_BCRYPT);
            $check = $conn->query("SELECT id FROM users WHERE email='$email'");

            if ($check && $check->num_rows > 0) {
                $msg      = "Cette adresse e-mail est déjà utilisée.";
                $msg_type = "error";
            } else {
                $conn->query("INSERT INTO users (nom, email, password, points_fidelite) VALUES ('$nom', '$email', '$hash', 50)");
                $msg      = "Compte créé ! Vous gagnez 50 points de bienvenue. Connectez-vous.";
                $msg_type = "success";
            }
        }
    }

    // ── Connexion
    elseif (isset($_POST['login'])) {
        $email = trim($conn->real_escape_string($_POST['email']));
        $pwd   = $_POST['password'] ?? '';
        $res   = $conn->query("SELECT * FROM users WHERE email='$email'");

        if ($res && $res->num_rows > 0) {
            $user = $res->fetch_assoc();
            if (password_verify($pwd, $user['password'])) {
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['nom'];
                $_SESSION['user_role'] = $user['role'];
                $is_logged   = true;
                $logged_user = $user;
                $msg      = "Ravi de vous revoir, " . htmlspecialchars($user['nom']) . " !";
                $msg_type = "success";
            } else {
                $msg      = "Mot de passe incorrect.";
                $msg_type = "error";
            }
        } else {
            $msg      = "Aucun compte trouvé pour cet e-mail.";
            $msg_type = "error";
        }
    }

    // ── Mise à jour du profil
    elseif (isset($_POST['update_profile']) && isset($_SESSION['user_id'])) {
        $tel = trim($conn->real_escape_string($_POST['telephone']));
        $adr = trim($conn->real_escape_string($_POST['adresse']));
        $uid = (int)$_SESSION['user_id'];
        
        $conn->query("UPDATE users SET telephone='$tel', adresse='$adr' WHERE id=$uid");
        $msg      = "Vos coordonnées ont été mises à jour avec succès.";
        $msg_type = "success";
    }

    // ── Suppression du compte
    elseif (isset($_POST['delete_account']) && isset($_SESSION['user_id'])) {
        $uid = (int)$_SESSION['user_id'];
        $conn->query("DELETE FROM users WHERE id=$uid");
        session_destroy();
        header("Location: ../index.php");
        exit();
    }

    // ── Déconnexion
    elseif (isset($_POST['logout'])) {
        session_destroy();
        header("Location: login.php");
        exit();
    }
}

// Reprise de session existante
if (isset($_SESSION['user_id'])) {
    $id   = (int)$_SESSION['user_id'];
    $res  = $conn->query("SELECT * FROM users WHERE id=$id");
    if ($res && $res->num_rows > 0) {
        $is_logged   = true;
        $logged_user = $res->fetch_assoc();
    } else {
        session_destroy();
        $is_logged = false;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte | AniPriZZA</title>
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
            <a href="login.php" class="active">Mon Compte</a>
            <a href="contact.php">Contact</a>
        </div>
    </nav>

    <main class="container">
        <p class="section-eyebrow">Espace personnel</p>
        <h1 class="page-title slide-up">Mon Compte</h1>

        <?php if ($msg): ?>
        <div class="alert alert-<?= $msg_type ?> slide-up visible"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>

        <?php if ($is_logged): ?>
        <div class="slide-up visible" style="max-width:900px; margin:0 auto; padding:20px 0;">
            
            <div style="text-align:center; margin-bottom: 40px;">
                <div style="font-size:4rem; margin-bottom:10px;">👤</div>
                <h2 style="font-family:var(--font-display); font-size:2.2rem; font-weight:300; color:var(--cream); margin-bottom:4px;">
                    <?= htmlspecialchars($logged_user['nom']) ?>
                </h2>
                <p style="color:var(--text-muted); margin-bottom:16px;"><?= htmlspecialchars($logged_user['email']) ?></p>
                
                <div style="display:inline-block; background:rgba(201, 169, 110, 0.15); border:1px solid var(--gold); padding:10px 24px; border-radius:50px; color:var(--gold);">
                    <span style="font-size:1.2rem; font-weight:600;">⭐ <?= (int)$logged_user['points_fidelite'] ?></span> points de fidélité
                </div>
            </div>

            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap:30px;">
                
                <form class="form-box" method="POST">
                    <h3 style="margin-bottom:20px; font-family:var(--font-display); font-size:1.6rem; color:var(--cream);">Mes Coordonnées</h3>
                    
                    <div class="form-group">
                        <label for="tel">Numéro de téléphone</label>
                        <input type="tel" id="tel" name="telephone" placeholder="Ex: 06 12 34 56 78" value="<?= htmlspecialchars($logged_user['telephone'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="adr">Adresse de livraison</label>
                        <textarea id="adr" name="adresse" rows="3" placeholder="123 rue de la Pizza, 75000 Paris"><?= htmlspecialchars($logged_user['adresse'] ?? '') ?></textarea>
                    </div>
                    
                    <button type="submit" name="update_profile" class="btn btn-outline" style="width:100%;">Enregistrer les modifications</button>
                </form>

                <div class="form-box" style="display:flex; flex-direction:column; gap:16px;">
                    <h3 style="margin-bottom:10px; font-family:var(--font-display); font-size:1.6rem; color:var(--cream);">Mes Actions</h3>

                    <?php if ($logged_user['role'] === 'admin'): ?>
                    <a href="admin_pizzas.php" class="btn btn-primary" style="width:100%;">🛠 Panneau Admin</a>
                    <?php endif; ?>
                    
                    <a href="produits.php" class="btn btn-outline" style="width:100%; text-align:center;">Voir la carte</a>
                    
                    <form method="POST" style="width:100%;">
                        <button type="submit" name="logout" class="btn btn-ghost" style="width:100%; background:var(--surface-2);">Se déconnecter</button>
                    </form>

                    <hr style="border:0; border-top:1px solid var(--border); margin:16px 0;">

                    <div>
                        <p style="font-size:0.8rem; color:var(--text-muted); margin-bottom:12px; text-align:center;">Attention, cette action est irréversible.</p>
                        <form method="POST" style="width:100%;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement votre compte et perdre vos points de fidélité ?');">
                            <button type="submit" name="delete_account" class="btn" style="width:100%; background:transparent; border:1px solid rgba(229,100,30,0.3); color:var(--red);">Supprimer mon compte</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <?php else: ?>
        <div style="display:flex; gap:32px; flex-wrap:wrap; max-width:1000px; margin:0 auto;">

            <form class="form-box slide-up" method="POST" style="flex:1; min-width:300px;">
                <h2>Se connecter</h2>
                <div class="form-group">
                    <label for="login-email">Adresse e-mail</label>
                    <input type="email" id="login-email" name="email" placeholder="votre@email.fr" required>
                </div>
                <div class="form-group">
                    <label for="login-pwd">Mot de passe</label>
                    <input type="password" id="login-pwd" name="password" placeholder="••••••••" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary" style="width:100%;">
                    Me connecter
                </button>
            </form>

            <form class="form-box slide-up" method="POST" style="flex:1; min-width:300px;" data-delay="0.15">
                <h2 style="color:var(--text-muted);">Créer un compte</h2>
                <div class="form-group">
                    <label for="reg-nom">Nom ou pseudo</label>
                    <input type="text" id="reg-nom" name="nom" placeholder="Votre nom" required>
                </div>
                <div class="form-group">
                    <label for="reg-email">Adresse e-mail</label>
                    <input type="email" id="reg-email" name="email" placeholder="votre@email.fr" required>
                </div>
                <div class="form-group">
                    <label for="reg-pwd">Mot de passe <span style="color:var(--text-dim)">(min. 6 car.)</span></label>
                    <input type="password" id="reg-pwd" name="password" placeholder="••••••••" required minlength="6">
                </div>
                <button type="submit" name="register" class="btn btn-ghost" style="width:100%; background:var(--surface-2); color:var(--text);">
                    Créer mon compte
                </button>
            </form>

        </div>
        <?php endif; ?>

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