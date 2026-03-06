<?php
require 'auth.php';

$article_id = $_GET['id'] ?? '';
if (!ctype_digit((string)$article_id)) {
    header('Location: index.php');
    exit;
}
$article_id_int = (int)$article_id;

$stmt = $conn->prepare('SELECT articles.*, users.username FROM articles JOIN users ON articles.user_id = users.id WHERE articles.id = ?');
$stmt->bind_param('i', $article_id_int);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows === 0) {
    header('Location: index.php');
    exit;
}

$article = $result->fetch_assoc();

$stmtComments = $conn->prepare(
    'SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE article_id = ? ORDER BY comments.created_at DESC'
);
$stmtComments->bind_param('i', $article_id_int);
$stmtComments->execute();
$comments = $stmtComments->get_result();
?>
<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title><?= e($article['title']) ?> - BlogSecure</title>
</head>
<body>
<header>
    <h1>BlogSecure</h1>
</header>

<nav>
    <a href='index.php'>Accueil</a>
    <?php if (!isLoggedIn()): ?>
        <a href='login.php'>Connexion</a>
    <?php else: ?>
        <a href='create_article.php'>Nouvel Article</a>
        <a href='auth.php?logout=1'>Déconnexion</a>
    <?php endif; ?>
</nav>

<div class='container'>
    <div class='article-content'>
        <h1><?= e($article['title']) ?></h1>
        <div class='meta'>Par <?= e($article['username']) ?> - <?= e($article['created_at']) ?></div>
        <p><?= nl2br(e($article['content'])) ?></p>
    </div>

    <div class='comments'>
        <h2>Commentaires</h2>

        <?php if ($comments && $comments->num_rows > 0): ?>
            <?php while ($comment = $comments->fetch_assoc()): ?>
                <div class='comment'>
                    <div class='comment-author'><?= e($comment['username']) ?></div>
                    <div class='comment-date'><?= e($comment['created_at']) ?></div>
                    <div class='comment-text'><?= nl2br(e($comment['comment'])) ?></div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Aucun commentaire pour le moment.</p>
        <?php endif; ?>

        <?php if (isLoggedIn()): ?>
            <h3>Ajouter un commentaire</h3>
            <form method='POST' action='add_comment.php'>
                <input type='hidden' name='csrf_token' value='<?= e(csrf_token()) ?>'>
                <input type='hidden' name='article_id' value='<?= (int)$article['id'] ?>'>
                <label for='comment'>Votre commentaire:</label>
                <textarea id='comment' name='comment' rows='5' required></textarea>
                <button type='submit'>Publier le commentaire</button>
            </form>
        <?php else: ?>
            <p><a href='login.php'>Connectez-vous</a> pour ajouter un commentaire.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
