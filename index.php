<?php
require 'auth.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogSecure - Accueil</title>
</head>
<body>
<header>
    <h1>BlogSecure</h1>
    <p>Plateforme de partage d'articles</p>
</header>

<nav>
    <a href="index.php">Accueil</a>
    <?php if (!isLoggedIn()): ?>
        <a href="login.php">Connexion</a>
        <a href="register.php">Inscription</a>
    <?php else: ?>
        <a href="create_article.php">Nouvel Article</a>
        <a href="auth.php?logout=1">Déconnexion</a>
    <?php endif; ?>
</nav>

<div class="container">
    <?php if (!empty($_SESSION['message'])): ?>
        <div class="success"><?= e($_SESSION['message']) ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="error"><?= e($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <h2>Articles Récents</h2>

    <?php
    $sql = "SELECT articles.*, users.username FROM articles JOIN users ON articles.user_id = users.id ORDER BY articles.created_at DESC";
    $result = $conn->query($sql);
    ?>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($article = $result->fetch_assoc()): ?>
            <div class="article">
                <h3><?= e($article['title']) ?></h3>
                <div class="meta">Par <?= e($article['username']) ?> - <?= e($article['created_at']) ?></div>
                <p><?= e($article['content']) ?></p>
                <a href="article.php?id=<?= (int)$article['id'] ?>" class="btn">Lire la suite</a>

                <?php if (isLoggedIn() && $_SESSION['user_id'] == $article['user_id']): ?>
                    <form method="post" action="delete_article.php" style="display:inline;">
                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                        <input type="hidden" name="id" value="<?= (int)$article['id'] ?>">
                        <button type="submit" class="btn danger" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Aucun article trouvé.</p>
    <?php endif; ?>
</div>
</body>
</html>
