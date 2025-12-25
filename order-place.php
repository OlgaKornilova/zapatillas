<?php
// Página para procesar la colocación de un pedido

require_once realpath(__DIR__ . '/includes/config.php');
require_once $base_path . 'includes/auth_check.php';

// Comprobamos que el usuario esté autenticado
auth_check('user');

// Obtenemos el carrito desde la sesión
$cart = $_SESSION['cart'] ?? [];

// Si el carrito está vacío, redirigimos a la página del carrito
if (!$cart) {
    header("Location: cart.php");
    exit;
}

// Obtenemos los datos enviados desde el formulario
$name     = trim($_POST['name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$city     = trim($_POST['city'] ?? '');
$address  = trim($_POST['address'] ?? '');
$postal   = trim($_POST['postal'] ?? '');
$shipping = trim($_POST['shipping'] ?? 'standard');
$payment  = trim($_POST['payment'] ?? 'cod');

// Cálculo del total y preparación de los datos de los productos
$subtotal = 0; // Total sin impuestos
$items_data = []; // Array para almacenar los datos de los productos

foreach ($cart as $item) {
    // Obtenemos los datos del producto desde la base de datos
    $productQuery = $pdo->prepare("SELECT name, price FROM products WHERE id = ?");
    $productQuery->execute([$item['product_id']]);
    $product = $productQuery->fetch(PDO::FETCH_ASSOC);

    if (!$product) continue; // Saltamos si el producto no existe

    $line_total = $product['price'] * $item['qty']; // Calculamos el precio total del producto
    $subtotal += $line_total; // Sumamos al subtotal general

    // Añadimos los datos del producto al array
    $items_data[] = [
        'product_id'    => $item['product_id'],
        'product_name'  => $product['name'],
        'price'         => $product['price'],
        'qty'           => $item['qty'],
        'size'          => $item['size'],
        'subtotal'      => $line_total
    ];
}

$tax = $subtotal * 0.21; // Calculamos el IVA (21%)
$total = $subtotal + $tax; // Total con impuestos incluidos

// Guardamos el pedido en la tabla "orders"
$stmt = $pdo->prepare("
    INSERT INTO orders 
    (customer_name, customer_email, customer_phone, city, address, postal_code,
     shipping_method, payment_method, subtotal, tax, total)
    VALUES 
    (:name, :email, :phone, :city, :address, :postal,
     :shipping, :payment, :subtotal, :tax, :total)
");

$stmt->execute([
    'name'     => $name,
    'email'    => $email,
    'phone'    => $phone,
    'city'     => $city,
    'address'  => $address,
    'postal'   => $postal,
    'shipping' => $shipping,
    'payment'  => $payment,
    'subtotal' => $subtotal,
    'tax'      => $tax,
    'total'    => $total
]);

$order_id = $pdo->lastInsertId(); // Obtenemos el ID del nuevo pedido

// Insertamos los productos del pedido en la tabla "order_items"
$stmt_item = $pdo->prepare("
    INSERT INTO order_items 
    (order_id, product_id, product_name, price, qty, size, subtotal)
    VALUES 
    (:order_id, :product_id, :product_name, :price, :qty, :size, :subtotal)
");

foreach ($items_data as $item) {
    // Añadimos cada producto a la tabla "order_items"
    $stmt_item->execute([
        'order_id'      => $order_id,
        'product_id'    => $item['product_id'],
        'product_name'  => $item['product_name'],
        'price'         => $item['price'],
        'qty'           => $item['qty'],
        'size'          => $item['size'],
        'subtotal'      => $item['subtotal']
    ]);
}

// Vaciamos el carrito y redirigimos a la página de agradecimiento
unset($_SESSION['cart']); // Eliminamos el contenido del carrito
header("Location: {$base_url}thankyou.php?order_id={$order_id}"); // Redirigimos a la página "Gracias"
exit;
