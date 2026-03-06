<?php
require 'auth.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

require_csrf_post();

$id = $_POST['id'] ?? '';
if (!ctype_digit((string)$id)) {
    $_SESSION['error'] = "Article invalide.";
    header('Location: index.php');
    exit;
}

$article_id = (int)$id;
$user_id = (int)($_SESSION['user_id'] ?? 0);

$stmt = $conn->prepare('DELETE FROM articles WHERE id = ? AND user_id = ?');
$stmt->bind_param('ii', $article_id, $user_id);
$stmt->execute();

$_SESSION['message'] = 'Article supprimé.';
header('Location: index.php');
exit;
