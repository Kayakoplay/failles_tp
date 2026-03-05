<?php
require 'config.php';

// CSRF token generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function requirePostCsrf(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        die('CSRF token invalide');
    }
}

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function logout(): void {
    $_SESSION = [];
    session_destroy();
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    requirePostCsrf();

    if ($_POST['action'] === 'register') {
        $username = trim((string)($_POST['username'] ?? ''));
        $email    = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        if ($username === '' || $email === '' || $password === '') {
            $_SESSION['error'] = 'Tous les champs sont obligatoires.';
            header('Location: register.php');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Email invalide.';
            header('Location: register.php');
            exit;
        }

        if (strlen($password) < 8) {
            $_SESSION['error'] = 'Mot de passe trop court (8 caracteres min).';
            header('Location: register.php');
            exit;
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
        if (!$stmt) {
            $_SESSION['error'] = 'Erreur serveur.';
            header('Location: register.php');
            exit;
        }
        $stmt->bind_param('sss', $username, $email, $hashed);

        if ($stmt->execute()) {
            $_SESSION['message'] = 'Inscription reussie. Vous pouvez vous connecter.';
            header('Location: login.php');
            exit;
        }

        $_SESSION['error'] = 'Inscription impossible (username/email deja utilise ?).';
        header('Location: register.php');
        exit;
    }

    if ($_POST['action'] === 'login') {
        $username = trim((string)($_POST['username'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        if ($username === '' || $password === '') {
            $_SESSION['error'] = 'Veuillez renseigner username et mot de passe.';
            header('Location: login.php');
            exit;
        }

        $stmt = $conn->prepare('SELECT id, username, password FROM users WHERE username = ? LIMIT 1');
        if (!$stmt) {
            $_SESSION['error'] = 'Erreur serveur.';
            header('Location: login.php');
            exit;
        }
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result ? $result->fetch_assoc() : null;

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['username'] = $user['username'];

            header('Location: index.php');
            exit;
        }

        $_SESSION['error'] = 'Identifiants incorrects';
        header('Location: login.php');
        exit;
    }
}
?>
