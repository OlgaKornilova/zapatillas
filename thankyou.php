<?php
// Página de agradecimiento tras realizar un pedido

require_once realpath(__DIR__ . '/includes/config.php');
require_once $base_path . 'includes/auth_check.php';

// Comprobamos que el usuario esté autenticado
auth_check('user');

// Inicialización de la aplicación
define('APP_INIT', true);

require_once $base_path . 'includes/header.php';

// Obtenemos el ID del pedido desde el parámetro de la URL
$order_id = $_GET['order_id'] ?? 0;
?>

<section class="thankyou-page">
    <div class="container">
        <div class="thankyou-box">
            <!-- Mensaje de confirmación de pedido -->
            <h1>¡Gracias por tu pedido!</h1>
            <p>Tu pedido <strong>#<?= htmlspecialchars($order_id) ?></strong> se ha realizado correctamente.</p>
            <p>Te enviaremos un correo electrónico con los detalles de tu compra.</p>

            <!-- Botón para volver a la tienda -->
            <a href="<?= $base_url ?>" class="btn btn-primary">Volver a la tienda</a>
        </div>
    </div>
</section>

<?php require_once $base_path . 'includes/footer.php'; ?>
