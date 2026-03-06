<?php
// login.php - Page de connexion
require 'auth.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - BlogSecure</title>
</head>
<body>
<header>
    <h1>BlogSecure</h1>
</header>

<nav>
    <a href="index.php">Accueil</a>
    <a href="register.php">Inscription</a>
</nav>

<div class="container">
    <h2>Connexion</h2>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="error"><?= e($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="POST" action="auth.php">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <input type="hidden" name="action" value="login">

        <div class="form-group">
            <label for="username">Nom d'utilisateur:</label>
            <input type="text" id="username" name="username" required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit">Se connecter</button>
    </form>
</div>
</body>
</html>
