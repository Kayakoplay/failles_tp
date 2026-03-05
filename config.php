<?php
// Configuration DB (pour un vrai projet: mettre ces infos dans des variables d'environnement)

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'blogsecure');

session_start();

// Forcer UTF-8 et protéger contre certaines erreurs silencieuses
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$conn->set_charset('utf8mb4');

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
