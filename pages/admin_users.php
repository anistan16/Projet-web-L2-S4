<?php
include('../config.php');

// ── Vérification accès admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') { 
    header("Location: login.php"); 
    exit(); 
}

$flash      = "";
$flash_type = "";

// ── Suppression d'un client
if (isset($_GET['delete'])) {
    $id_to_delete = (int)$_GET['delete'];
    
    // Sécurité : Empêcher l'admin de se supprimer lui-même
    if ($id_to_delete === (int)$_SESSION['user_id']) {
        $flash      = "Action refusée : Vous ne pouvez pas supprimer votre propre compte.";
        $flash_type = "error";
    } else {
        if ($conn->query("DELETE FROM users WHERE id=$id_to_delete")) {
            $flash      = "Utilisateur supprimé avec succès.";
            $flash_type = "success";
        }
    }
}

// ── Ajout d'un client
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_user'])) {
    $nom   = $conn->real_escape_string(trim($_POST['nom']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $pwd   = trim($_POST['password']);
    $role  = $conn->real_escape_string($_POST['role']);

    if ($nom && $email && strlen($pwd) >= 6) {
        $check = $conn->query("SELECT id FROM users WHERE email='$email'");
        if ($check && $check->num_rows > 0) {
            $flash      = "Cet e-mail est déjà utilisé par un autre compte.";
            $flash_type = "error";
        } else {
            $hash = password_hash($pwd, PASSWORD_BCRYPT);
            $conn->query("INSERT INTO users (nom, email, password, role, points_fidelite) VALUES ('$nom', '$email', '$hash', '$role', 50)");
            $flash      = "Compte '$nom' créé avec succès !";
            $flash_type = "success";
        }
    } else {
        $flash      = "Tous les champs sont requis (mot de passe : 6 car. min).";
        $flash_type = "error";
    }
}

// ── Récupération de tous les utilisateurs
$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Clients | Admin AniPriZZA</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <nav class="navbar admin-navbar">
        <div style="display:flex; align-items:center; gap:16px;">
            <div class="logo">AniPri<span>ZZA</span></div>
            <span class="admin-badge">Admin</span>
        </div>
        <div class="nav-links">
            <a href="admin_pizzas.php">🍕 Gérer les Pizzas</a>
            <a href="admin_users.php" class="active">👥 Gérer les Clients</a>
            <a href="../index.php">Voir le site</a>
        </div>
    </nav>

    <main class="container">
        <p class="section-eyebrow">Back-office</p>
        <h1 class="page-title slide-up">Base de données Clients</h1>

        <?php if ($flash): ?>
        <div class="alert alert-<?= $flash_type ?> slide-up visible"><?= htmlspecialchars($flash) ?></div>
        <?php endif; ?>

        <div class="form-box slide-up" style="margin-bottom: 50px;">
            <h2>Créer un compte manuellement</h2>
            <form method="POST" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:16px; align-items:end;">
                <div class="form-group" style="margin:0;">
                    <label>Nom ou Pseudo</label>
                    <input type="text" name="nom" placeholder="Ex: Jean Dupont" required>
                </div>
                <div class="form-group" style="margin:0;">
                    <label>Adresse E-mail</label>
                    <input type="email" name="email" placeholder="client@email.fr" required>
                </div>
                <div class="form-group" style="margin:0;">
                    <label>Mot de passe temporaire</label>
                    <input type="text" name="password" placeholder="Min. 6 caractères" required minlength="6">
                </div>
                <div class="form-group" style="margin:0;">
                    <label>Rôle</label>
                    <select name="role">
                        <option value="user">👤 Client</option>
                        <option value="admin">🛡️ Administrateur</option>
                    </select>
                </div>
                <button type="submit" name="add_user" class="btn btn-primary" style="grid-column: span 1; padding: 14px;">
                    + Ajouter le compte
                </button>
            </form>
        </div>

        <div class="slide-up">
            <h2 style="font-family:var(--font-display); font-size:1.8rem; font-weight:400; color:var(--cream); margin-bottom:24px;">
                Liste des inscrits <span style="color:var(--text-muted); font-size:1.1rem; font-weight:300;">
                    — <?= ($users ? $users->num_rows : 0) ?> comptes
                </span>
            </h2>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>E-mail</th>
                            <th>Rôle</th>
                            <th>Points</th>
                            <th>Date d'inscription</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($users && $users->num_rows > 0): ?>
                        <?php while ($row = $users->fetch_assoc()): ?>
                        <tr>
                            <td style="color:var(--text-dim);">#<?= $row['id'] ?></td>
                            <td style="font-weight:600; color:var(--cream);"><?= htmlspecialchars($row['nom']) ?></td>
                            <td style="color:var(--text-muted);"><?= htmlspecialchars($row['email']) ?></td>
                            <td>
                                <?php if ($row['role'] === 'admin'): ?>
                                    <span style="background:rgba(201, 169, 110, 0.15); color:var(--gold); padding:4px 10px; border-radius:50px; font-size:0.75rem; font-weight:600;">Admin</span>
                                <?php else: ?>
                                    <span style="background:rgba(255, 255, 255, 0.1); color:var(--text-muted); padding:4px 10px; border-radius:50px; font-size:0.75rem;">Client</span>
                                <?php endif; ?>
                            </td>
                            <td style="color:var(--gold); font-weight:600;">⭐ <?= (int)$row['points_fidelite'] ?></td>
                            <td style="font-size:0.85rem; color:var(--text-muted);">
                                <?= date('d/m/Y', strtotime($row['created_at'])) ?>
                            </td>
                            <td>
                                <?php if ((int)$row['id'] !== (int)$_SESSION['user_id']): ?>
                                <a href="admin_users.php?delete=<?= $row['id'] ?>"
                                   onclick="return confirm('Supprimer le compte de <?= addslashes(htmlspecialchars($row['nom'])) ?> ? Cette action est irréversible.')"
                                   style="color:var(--red); font-size:0.82rem; font-weight:500; padding:6px 14px; border:1px solid rgba(229,100,30,0.3); border-radius:50px; transition:background 0.2s;"
                                   onmouseover="this.style.background='rgba(229,100,30,0.1)'"
                                   onmouseout="this.style.background='transparent'">
                                    Supprimer
                                </a>
                                <?php else: ?>
                                    <span style="color:var(--text-dim); font-size:0.8rem; font-style:italic;">Vous (En ligne)</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <tr><td colspan="7" style="text-align:center; padding:40px; color:var(--text-muted);">
                            Aucun utilisateur trouvé.
                        </td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <footer>
        <div class="footer-bottom" style="border-top:none; padding-top:0;">
            <span>&copy; 2026 AniPriZZA — Admin Dashboard</span>
        </div>
    </footer>

    <script src="../js/script.js"></script>

</body>
</html>

