<?php
// Configuración general — conexión a la base de datos, sesión, rutas.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ruta absoluta a la raíz del proyecto
$base_path = realpath(__DIR__ . '/..') . DIRECTORY_SEPARATOR;

// Ruta relativa desde el dominio
$base_url = '/';



// Database configuration
$host = 'localhost';
$db   = 'if0_40743557_medac_shoes';
$user = '';
$pass = '';
$charset = 'utf8mb4';

// Data Source Name
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Opciones PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Mostrar errores 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // Devolver matrices asociativas
    PDO::ATTR_EMULATE_PREPARES   => false,                   // Utilizar sentencias preparadas reales
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die('Ошибка подключения к базе данных: ' . $e->getMessage());
}

// DEBUG
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);