<?php
// Página para eliminar productos del carrito

require_once realpath(__DIR__ . '/includes/config.php');


// Obtenemos la clave del producto desde la petición GET
$key = $_GET['key'] ?? null;

// Si la clave existe y el producto está en el carrito, lo eliminamos
if ($key !== null && isset($_SESSION['cart'][$key])) {
    unset($_SESSION['cart'][$key]);
}

// Redirigimos al usuario de nuevo a la página del carrito
header("Location: cart.php");
exit;
