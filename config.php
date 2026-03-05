<?php
// Configuration de la base de données - FAILLE 1 : Informations sensibles visibles

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'blogsecure');

// Connexion à la base de données
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// FAILLE 2 : Aucune préparation pour les requêtes SQL
// Le code utilise directement les entrées utilisateur

session_start();
?>
