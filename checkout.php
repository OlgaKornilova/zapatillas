<?php
// Página de finalización de compra (checkout)

require_once realpath(__DIR__ . '/includes/config.php');
require_once $base_path . 'includes/auth_check.php';

// Comprobamos que el usuario esté autenticado
auth_check('user');

// Inicialización de la aplicación
define('APP_INIT', true);

require_once $base_path . 'includes/header.php';

// Obtenemos el carrito desde la sesión o creamos uno vacío
$cart = $_SESSION['cart'] ?? [];
$total = 0; // Total general del carrito

// Si el carrito está vacío, mostramos un mensaje y finalizamos la ejecución
if (!$cart) {
    echo "<div class='container'><h2>El carrito está vacío. Añade productos para realizar el pedido.</h2></div>";
    require_once $base_path . 'includes/footer.php';
    exit;
}
?>

<section class="checkout-page">
    <div class="container">
        <h2 class="checkout-title">Finalizar compra</h2>
        <!-- Formulario de pago y envío -->
        <form action="<?= $base_url ?>order-place.php" method="POST" id="checkoutForm">
            <div class="checkout-layout">
                <!-- Columna izquierda: formulario de datos del cliente -->
                <div class="checkout-form">
                    <div class="card">
                        <h3>Datos de contacto</h3>
                        <div class="form-item">
                            <label>Nombre y apellidos</label>
                            <input type="text" name="name" required>
                        </div>
                        <div class="form-item">
                            <label>Email</label>
                            <input type="email" name="email" required>
                        </div>
                        <div class="form-item">
                            <label>Teléfono</label>
                            <input type="text" name="phone" required>
                        </div>
                    </div>

                    <div class="card">
                        <h3>Dirección de envío</h3>

                        <div class="form-item">
                            <label>Ciudad</label>
                            <input type="text" name="city" required>
                        </div>

                        <div class="form-item">
                            <label>Calle, número, piso</label>
                            <input type="text" name="address" required>
                        </div>

                        <div class="form-item">
                            <label>Código postal</label>
                            <input type="text" name="postal" required>
                        </div>
                    </div>

                    <div class="card">
                        <h3>Método de envío</h3>
                        <label class="radio-row">
                            <input type="radio" name="shipping" value="standard" checked>
                            <span>Envío estándar — Gratis (5–7 días)</span>
                        </label>
                        <label class="radio-row">
                            <input type="radio" name="shipping" value="courier">
                            <span>Mensajería — €4.90 (2–3 días)</span>
                        </label>
                        <label class="radio-row">
                            <input type="radio" name="shipping" value="pickup">
                            <span>Recogida en tienda — Gratis</span>
                        </label>
                    </div>

                    <div class="card">
                        <h3>Método de pago</h3>
                        <label class="radio-row">
                            <input type="radio" name="payment" value="cod" checked>
                            <span>Pago contra reembolso</span>
                        </label>
                        <label class="radio-row">
                            <input type="radio" name="payment" value="card">
                            <span>Tarjeta Visa / MasterCard</span>
                        </label>
                        <label class="radio-row">
                            <input type="radio" name="payment" value="paypal">
                            <span>PayPal</span>
                        </label>
                    </div>
                </div>

                <!-- Columna derecha: resumen del pedido -->
                <div class="checkout-summary">
                    <div class="card">
                        <h3>Tu pedido</h3>
                        <?php foreach ($cart as $key => $item): ?>
                            <?php
                            // Obtenemos los datos del producto desde la base de datos
                            $stmt = $pdo->prepare("SELECT name, price, photo FROM products WHERE id = :id LIMIT 1");
                            $stmt->execute(['id' => $item['product_id']]);
                            $product = $stmt->fetch(PDO::FETCH_ASSOC);
                            // Comprobamos si la imagen del producto existe
                            $photo = $base_url . "uploads/products/{$item['product_id']}/{$product['photo']}";
                            if (!is_file($_SERVER['DOCUMENT_ROOT'] . $photo)) {
                                $photo = $base_url . "assets/images/no-image.jpg";
                            }
                            // Calculamos el subtotal del producto actual
                            $subtotal = $product['price'] * $item['qty'];
                            $total += $subtotal; // Añadimos al total general
                            ?>
                            <div class="summary-item">
                                <img src="<?= $photo ?>" alt="">
                                <div>
                                    <p><?= htmlspecialchars($product['name']) ?></p>
                                    <small>Talla: <?= $item['size'] ?></small><br>
                                    <small>Cantidad: <?= $item['qty'] ?></small>
                                </div>
                                <strong>$<?= number_format($subtotal, 2) ?></strong>
                            </div>
                        <?php endforeach; ?>
                        <div class="summary-total">
                            <div class="row">
                                <span>Subtotal</span>
                                <span><?= number_format($total, 2) ?> €</span>
                            </div>
                            <div class="row">
                                <span>IVA (21%)</span>
                                <td><?= number_format($total * 0.21, 2) ?> €</td>
                            </div>
                            <div class="row total">
                                <span>Total</span>
                                <span><?= number_format($total + ($total * 0.21), 2) ?> €</span>
                            </div>
                        </div>
                        <button class="btn btn-primary checkout-btn" type="submit">Confirmar pedido</button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</section>

<?php require_once $base_path . 'includes/footer.php'; ?>
