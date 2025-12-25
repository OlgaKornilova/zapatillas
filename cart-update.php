<?php
// Página para actualizar la cantidad de productos en el carrito mediante una petición AJAX

require_once realpath(__DIR__ . '/includes/config.php');

// Obtenemos la clave del producto y la cantidad desde la petición POST
$key = $_POST['key'] ?? null; // la clave es el identificador único del producto en el carrito
$qty = (int)($_POST['qty'] ?? 1); // cantidad del producto, por defecto 1

// Comprobamos que la clave existe, que el producto está en el carrito y que la cantidad es mayor que cero
if ($key !== null && isset($_SESSION['cart'][$key]) && $qty > 0) {
    $_SESSION['cart'][$key]['qty'] = $qty; // actualizamos la cantidad del producto en el carrito
}

// Devolvemos una respuesta simple para indicar que todo ha salido correctamente
echo "ok";
