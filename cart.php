<?php
// Página del carrito de compras

require_once realpath(__DIR__ . '/includes/config.php');

// Inicialización de la aplicación
define('APP_INIT', true);

require_once $base_path . 'includes/header.php';

// Obtenemos el carrito desde la sesión o creamos uno vacío
$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>

<section class="cart-page">
    <div class="container">

        <h2 class="cart-title">Carrito de compras</h2>

        <?php if (empty($cart)): ?>
            <p class="cart-empty">Tu carrito está vacío.</p>
        <?php else: ?>

        <table class="cart-table">
            <tr>
                <th>Producto</th>
                <th>Talla</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th></th>
            </tr>

            <?php foreach ($cart as $key => $item): ?>
                <?php
                // Consulta de producto
                $stmt = $pdo->prepare("SELECT name, price, photo FROM products WHERE id = :id LIMIT 1");
                $stmt->execute(['id' => $item['product_id']]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);

                // Imagen
                $photo = $base_url . "uploads/products/{$item['product_id']}/" . $product['photo'];
                if (!is_file($_SERVER['DOCUMENT_ROOT'] . $photo)) {
                    $photo = $base_url . "assets/images/no-image.jpg";
                }

                // Subtotal
                $subtotal = $product['price'] * $item['qty'];
                $total += $subtotal;
                ?>

                <tr>
                    <td>
                        <div class="cart-info">
                            <img src="<?= $photo ?>" alt="">
                            <div>
                                <p><?= htmlspecialchars($product['name']) ?></p>
                                <small>Precio: $<?= number_format($product['price'], 2) ?></small><br>
                            </div>
                        </div>
                    </td>

                    <td><?= htmlspecialchars($item['size']) ?></td>

                    <td>
                        <input 
                            type="number" 
                            value="<?= $item['qty'] ?>" 
                            min="1"
                            class="cart-qty"
                            data-key="<?= $key ?>"
                        >
                    </td>

                    <td>$<?= number_format($subtotal, 2) ?></td>

                    <td>
                        <a href="<?= $base_url ?>cart-remove.php?key=<?= $key ?>" class="remove-btn">Eliminar</a>
                    </td>
                </tr>

            <?php endforeach; ?>

        </table>

        <div class="total-price">
            <table>
                <tr>
                    <td>Subtotal</td>
                    <td><?= number_format($total, 2) ?> €</td>
                </tr>
                <tr>
                    <td>IVA (21%)</td>
                    <td><?= number_format($total * 0.21, 2) ?> €</td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td><?= number_format($total + ($total * 0.21), 2) ?> €</td>
                </tr>
            </table>
        </div>

        <!-- Mostrar dinámicamente el botón de pago en función del estado de inicio de sesión del usuario. -->
        <div class="checkout-continue">
            <?php if (!empty($_SESSION['username'])): ?>
                <a href="<?= $base_url ?>checkout.php" class="btn btn-primary">Finalizar compra</a>
            <?php else: ?>
                <button onclick="MicroModal.show('modal-1')" class="btn btn-primary">Inicia sesión para continuar</button>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    </div>
</section>

<script>
// Actualización de cantidades
document.querySelectorAll(".cart-qty").forEach(input => {
    input.addEventListener("change", () => {
        const key = input.dataset.key;
        const qty = input.value;

        fetch("<?= $base_url ?>cart-update.php", {
            method: "POST",
            headers: {"Content-Type": "application/x-www-form-urlencoded"},
            body: `key=${key}&qty=${qty}`
        }).then(() => location.reload());
    });
});
</script>

<?php require_once $base_path . 'includes/footer.php'; ?>
