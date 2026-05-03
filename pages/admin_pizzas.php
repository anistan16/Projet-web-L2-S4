<?php
include('../config.php');

$is_admin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';


$flash      = "";
$flash_type = "";

// ── Suppression 
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($conn->query("DELETE FROM pizzas WHERE id=$id")) {
        $flash      = "Pizza supprimée avec succès.";
        $flash_type = "success";
    }
}

// ── Toggle disponibilité 
if (isset($_GET['toggle'])) {
    $id  = (int)$_GET['toggle'];
    $cur = $conn->query("SELECT disponible FROM pizzas WHERE id=$id")->fetch_assoc();
    $new = $cur ? ($cur['disponible'] ? 0 : 1) : 1;
    $conn->query("UPDATE pizzas SET disponible=$new WHERE id=$id");
    header("Location: admin_pizzas.php"); exit();
}

// ── Ajout 
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add'])) {
    $nom  = $conn->real_escape_string(trim($_POST['nom']));
    $ing  = $conn->real_escape_string(trim($_POST['ing']));
    $prix = (float)$_POST['prix'];
    $img  = $conn->real_escape_string(trim($_POST['img']));
    $cat  = $conn->real_escape_string(trim($_POST['categorie']));

    if ($nom && $ing && $prix > 0) {
        $conn->query("INSERT INTO pizzas (nom, ingredients, prix, image_url, categorie) VALUES ('$nom','$ing',$prix,'$img','$cat')");
        $flash      = "\"$nom\" ajoutée au menu !";
        $flash_type = "success";
    } else {
        $flash      = "Tous les champs sont requis.";
        $flash_type = "error";
    }
}

// ── Modification inline 
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['edit'])) {
    $id   = (int)$_POST['id'];
    $nom  = $conn->real_escape_string(trim($_POST['nom']));
    $ing  = $conn->real_escape_string(trim($_POST['ing']));
    $prix = (float)$_POST['prix'];
    $img  = $conn->real_escape_string(trim($_POST['img']));
    $conn->query("UPDATE pizzas SET nom='$nom', ingredients='$ing', prix=$prix, image_url='$img' WHERE id=$id");
    header("Location: admin_pizzas.php"); exit();
}

// ── Récupération inventaire 
$pizzas = $conn->query("SELECT * FROM pizzas ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | AniPriZZA</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <nav class="navbar admin-navbar">
    <div style="display:flex; align-items:center; gap:16px;">
        <div class="logo">AniPri<span>ZZA</span></div>
        <span class="admin-badge">Admin</span>
    </div>
    <div class="nav-links">
        <a href="admin_pizzas.php" class="active">🍕 Gérer les Pizzas</a>
        <a href="admin_users.php">👥 Gérer les Clients</a>
        <a href="../index.php">Voir le site</a>
    </div>
</nav>

    <main class="container">
        <p class="section-eyebrow">Back-office</p>
        <h1 class="page-title slide-up">Gestion du Menu</h1>

        <?php if ($flash): ?>
        <div class="alert alert-<?= $flash_type ?> slide-up visible"><?= htmlspecialchars($flash) ?></div>
        <?php endif; ?>

        <!-- ── Formulaire d'ajout ── -->
        <div class="form-box slide-up" style="margin-bottom: 50px;">
            <h2>Ajouter une pizza</h2>
            <form method="POST" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:16px; align-items:end;">
                <div class="form-group" style="margin:0;">
                    <label>Nom</label>
                    <input type="text" name="nom" placeholder="ex: Quattro Stagioni" required>
                </div>
                <div class="form-group" style="margin:0; grid-column: span 2;">
                    <label>Ingrédients</label>
                    <input type="text" name="ing" placeholder="ex: Mozzarella, Jambon, Champignons..." required>
                </div>
                <div class="form-group" style="margin:0;">
                    <label>Prix (€)</label>
                    <input type="number" step="0.01" min="1" name="prix" placeholder="12.90" required>
                </div>
                <div class="form-group" style="margin:0;">
                    <label>Catégorie</label>
                    <select name="categorie">
                        <option value="classique">🍕 Classique</option>
                        <option value="spicy">🌶 Spicy</option>
                        <option value="veggie">🥦 Veggie</option>
                        <option value="premium">✨ Premium</option>
                    </select>
                </div>
                <div class="form-group" style="margin:0; grid-column: span 2;">
                    <label>URL de l'image</label>
                    <input type="text" name="img" placeholder="https://images.unsplash.com/..." value="https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=800">
                </div>
                <button type="submit" name="add" class="btn btn-primary" style="grid-column: span 1; padding: 14px;">
                    + Ajouter
                </button>
            </form>
        </div>

        
        <div class="slide-up">
            <h2 style="font-family:var(--font-display); font-size:1.8rem; font-weight:400; color:var(--cream); margin-bottom:24px;">
                Inventaire <span style="color:var(--text-muted); font-size:1.1rem; font-weight:300;">
                    — <?= ($pizzas ? $pizzas->num_rows : 0) ?> pizzas
                </span>
            </h2>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Nom</th>
                            <th>Ingrédients</th>
                            <th>Catégorie</th>
                            <th>Prix</th>
                            <th>Dispo</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($pizzas && $pizzas->num_rows > 0): ?>
                        <?php while ($row = $pizzas->fetch_assoc()): ?>
                        <tr>
                            <td style="color:var(--text-dim);">#<?= $row['id'] ?></td>
                            <td>
                                <img src="<?= htmlspecialchars($row['image_url']) ?>"
                                     alt="<?= htmlspecialchars($row['nom']) ?>"
                                     style="width:52px; height:52px; object-fit:cover; border-radius:8px;">
                            </td>
                            <td style="font-weight:600; color:var(--cream);"><?= htmlspecialchars($row['nom']) ?></td>
                            <td style="color:var(--text-muted); font-size:0.85rem; max-width:280px;">
                                <?= htmlspecialchars($row['ingredients']) ?>
                            </td>
                            <td>
                                <span style="background:rgba(229,100,30,0.12); color:var(--red); padding:4px 10px; border-radius:50px; font-size:0.75rem; font-weight:500;">
                                    <?= htmlspecialchars($row['categorie']) ?>
                                </span>
                            </td>
                            <td style="color:var(--red); font-family:var(--font-display); font-size:1.1rem; font-weight:600;">
                                <?= number_format((float)$row['prix'], 2, ',', '') ?> €
                            </td>
                            <td>
                                <a href="admin_pizzas.php?toggle=<?= $row['id'] ?>"
                                   style="display:inline-block; width:36px; height:20px; border-radius:20px; background:<?= $row['disponible'] ? 'var(--red)' : '#333' ?>; position:relative; transition:0.2s; cursor:pointer;" title="<?= $row['disponible'] ? 'Cliquer pour désactiver' : 'Cliquer pour activer' ?>">
                                    <span style="display:block; width:14px; height:14px; background:white; border-radius:50%; position:absolute; top:3px; <?= $row['disponible'] ? 'right:3px' : 'left:3px' ?>; transition:0.2s;"></span>
                                </a>
                            </td>
                            <td>
                                <a href="admin_pizzas.php?delete=<?= $row['id'] ?>"
                                   onclick="return confirm('Supprimer <?= addslashes(htmlspecialchars($row['nom'])) ?> ?')"
                                   style="color:var(--red); font-size:0.82rem; font-weight:500; padding:6px 14px; border:1px solid rgba(229,100,30,0.3); border-radius:50px; transition:background 0.2s;"
                                   onmouseover="this.style.background='rgba(229,100,30,0.1)'"
                                   onmouseout="this.style.background='transparent'">
                                    Supprimer
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <tr><td colspan="8" style="text-align:center; padding:40px; color:var(--text-muted);">
                            Aucune pizza en base. Commencez par en ajouter une ci-dessus.
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
