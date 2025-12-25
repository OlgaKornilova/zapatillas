<?php
// Página para añadir productos al carrito mediante una petición AJAX

require_once realpath(__DIR__ . '/includes/config.php');

// Obtenemos los datos enviados mediante POST
$id   = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$size = isset($_POST['size']) ? trim($_POST['size']) : '';
$qty  = isset($_POST['qty']) ? (int) $_POST['qty'] : 1;

// Validamos la corrección de los datos recibidos
if ($id <= 0 || $qty <= 0 || $size === '') {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Datos incorrectos'
    ]);
    exit;
}

// Lógica para añadir el producto al carrito
// Creamos una clave única para identificar el producto en el carrito
$item_key = $id . '_' . $size;

// Inicializamos el carrito si aún no existe
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Añadimos el producto al carrito o actualizamos la cantidad si ya existe
if (!isset($_SESSION['cart'][$item_key])) {
    $_SESSION['cart'][$item_key] = [
        'product_id' => $id,
        'size'       => $size,
        'qty'        => $qty
    ];
} else {
    $_SESSION['cart'][$item_key]['qty'] += $qty;
}

// Devolvemos una respuesta de éxito
echo json_encode(['status' => 'success']);
exit;
