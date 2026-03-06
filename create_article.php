<?php
// create_article.php - Création d'un nouvel article
require 'auth.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf_post();

    $title = trim((string)($_POST['title'] ?? ''));
    $content = trim((string)($_POST['content'] ?? ''));
    $user_id = (int)($_SESSION['user_id'] ?? 0);

    if ($title === '' || $content === '' || $user_id <= 0) {
        $_SESSION['error'] = 'Tous les champs sont obligatoires.';
        header('Location: create_article.php');
        exit;
    }

    $stmt = $conn->prepare('INSERT INTO articles (user_id, title, content) VALUES (?, ?, ?)');
    $stmt->bind_param('iss', $user_id, $title, $content);
    $stmt->execute();

    $_SESSION['message'] = 'Article créé avec succès';
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Article - BlogSecure</title>
</head>
<body>
<header>
    <h1>BlogSecure</h1>
</header>

<nav>
    <a href="index.php">Accueil</a> |
    <a href="auth.php?logout=1">Déconnexion</a>
</nav>

<div>
    <h2>Créer un nouvel article</h2>

    <?php if (!empty($_SESSION['error'])): ?>
        <div><?php echo e($_SESSION['error']); ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">

        <div>
            <label for="title">Titre:</label>
            <input type="text" id="title" name="title" required>
        </div>

        <div>
            <label for="content">Contenu:</label>
            <textarea id="content" name="content" rows="8" required></textarea>
        </div>

        <button type="submit">Publier l'article</button>
    </form>
</div>
</body>
</html>
