<?php
// Página para cerrar la sesión del usuario

require_once dirname(__DIR__) . '/includes/config.php';

// Iniciamos la sesión
session_start();

// Destruimos la sesión (cerrar sesión del usuario)
session_destroy();

// Redirigimos al usuario a la página principal
header('Location: ' . $base_url . 'index.php');
exit;
?>