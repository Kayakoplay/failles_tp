<?php
// create_article.php - Création d'un nouvel article
require 'auth.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// CSRF helpers (fallback)
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
if (!function_exists('csrf_token')) {
    function csrf_token(): string {
        return $_SESSION['csrf_token'] ?? '';
    }
}
if (!function_exists('require_csrf_post')) {
    function require_csrf_post(): void {
        $token = $_POST['csrf_token'] ?? '';
        if (!is_string($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(403);
            die('CSRF token invalide');
        }
    }
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

    $_SESSION['message'] = "Article créé avec succès";
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Article - BlogSecure</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; }
        header { background-color: #333; color: white; padding: 20px; text-align: center; }
        nav { background-color: #444; padding: 10px; text-align: center; }
        nav a { color: white; margin: 0 15px; text-decoration: none; }
        nav a:hover { text-decoration: underline; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; background: white; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h2 { margin-bottom: 20px; color: #333; }
        .form-group { margin: 15px 0; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-family: Arial; }
        textarea { resize: vertical; }
        button { padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; margin-top: 10px; }
        button:hover { background-color: #218838; }
        .error { color: #dc3545; padding: 10px; background-color: #f8d7da; border-radius: 4px; margin: 10px 0; }
    </style>
</head>
<body>
<header>
    <h1>BlogSecure</h1>
</header>

<nav>
    <a href="index.php">Accueil</a>
    <a href="auth.php?logout=1">Déconnexion</a>
</nav>

<div class="container">
    <h2>Créer un nouvel article</h2>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="error"><?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">

        <div class="form-group">
            <label for="title">Titre:</label>
            <input type="text" id="title" name="title" required>
        </div>

        <div class="form-group">
            <label for="content">Contenu:</label>
            <textarea id="content" name="content" rows="10" required></textarea>
        </div>

        <button type="submit">Publier l'article</button>
    </form>
</div>
</body>
</html>

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
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; }
        header { background-color: #333; color: white; padding: 20px; text-align: center; }
        nav { background-color: #444; padding: 10px; text-align: center; }
        nav a { color: white; margin: 0 15px; text-decoration: none; }
        nav a:hover { text-decoration: underline; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; background: white; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h2 { margin-bottom: 20px; color: #333; }
        .form-group { margin: 15px 0; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-family: Arial; }
        textarea { resize: vertical; }
        button { padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; margin-top: 10px; }
        button:hover { background-color: #218838; }
        .error { color: #dc3545; padding: 10px; background-color: #f8d7da; border-radius: 4px; margin: 10px 0; }
    </style>
</head>
<body>
<header>
    <h1>BlogSecure</h1>
</header>

<nav>
    <a href="index.php">Accueil</a>
    <a href="auth.php?logout=1">Déconnexion</a>
</nav>

<div class="container">
    <h2>Créer un nouvel article</h2>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="error"><?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">

        <div class="form-group">
            <label for="title">Titre:</label>
            <input type="text" id="title" name="title" required>
        </div>

        <div class="form-group">
            <label for="content">Contenu:</label>
            <textarea id="content" name="content" rows="10" required></textarea>
        </div>

        <button type="submit">Publier l'article</button>
    </form>
</div>
</body>
</html>
