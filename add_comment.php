<?php
require 'auth.php';

if (!isLoggedIn() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

require_csrf_post();

$article_id = $_POST['article_id'] ?? '';
$comment = trim((string)($_POST['comment'] ?? ''));
$user_id = (int)($_SESSION['user_id'] ?? 0);

if (!ctype_digit((string)$article_id) || $comment === '' || $user_id <= 0) {
    $_SESSION['error'] = 'Commentaire invalide.';
    header('Location: index.php');
    exit;
}

$article_id_int = (int)$article_id;

$stmt = $conn->prepare('INSERT INTO comments (article_id, user_id, comment) VALUES (?, ?, ?)');
$stmt->bind_param('iis', $article_id_int, $user_id, $comment);
$stmt->execute();

$_SESSION['message'] = 'Commentaire ajouté.';
header('Location: article.php?id=' . $article_id_int);
exit;
