<?php
// Página de cuenta de usuario con formularios de inicio de sesión y registro

require_once realpath(__DIR__ . '/includes/config.php');

// Obtenemos el mensaje de error o éxito, si existe
$message = $_SESSION['flash_message'] ?? '';
$type = $_SESSION['flash_type'] ?? 'info';

// Eliminamos el mensaje después de mostrarlo
unset($_SESSION['flash_message'], $_SESSION['flash_type']);

// Determinamos qué pestaña debe estar activa (login o registro)
$show_tab = isset($_GET['register']) ? 'register' : 'login';

// Si el registro fue exitoso, mostramos un mensaje informativo
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $message = "¡Registro exitoso! Ahora puedes iniciar sesión.";
}
?>

<?php include $base_path . 'includes/header.php'; ?>

<section class="account-page">
    <div class="container">
        <div class="account-wrapper">

            <div class="account-image">
                <!-- Imagen decorativa de la página de cuenta -->
                <img src="<?= $base_url ?>assets/images/account_img.jpg" alt="Cuenta">
            </div>

            <div class="account-form">
                <!-- Pestañas para alternar entre formularios -->
                <div class="tabs">
                    <button class="tab <?= $show_tab === 'login' ? 'active' : '' ?>" data-tab="login">Iniciar sesión</button>
                    <button class="tab <?= $show_tab === 'register' ? 'active' : '' ?>" data-tab="register">Registrarse</button>
                </div>

                <!-- Mensaje de error o éxito -->
                <?php if (!empty($message)): ?>
                <div class="alert alert-<?= htmlspecialchars($type) ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
                <?php endif; ?>

                <div class="form-area">
                    <!-- Formulario de inicio de sesión -->
                    <form id="login-form" class="form <?= $show_tab === 'login' ? 'active' : '' ?>"
                          action="<?= $base_url ?>auth/login.php" method="POST">

                        <!-- Redirección después del login -->
                        <input type="hidden" name="redirect" value="<?= htmlspecialchars($_GET['redirect'] ?? '') ?>">

                        <div class="field">
                            <!-- Campo de nombre de usuario o correo -->
                            <input type="text" name="username" placeholder="Nombre de usuario o correo electrónico" required>
                        </div>

                        <div class="field">
                            <!-- Campo de contraseña -->
                            <input type="password" name="password" placeholder="Contraseña" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Iniciar sesión</button>
                    </form>

                    <!-- Formulario de registro -->
                    <form id="register-form" class="form <?= $show_tab === 'register' ? 'active' : '' ?>"
                          action="<?= $base_url ?>auth/register.php" method="POST">

                        <div class="field">
                            <!-- Nombre de usuario -->
                            <input type="text" name="username" placeholder="Nombre de usuario" required>
                        </div>

                        <div class="field">
                            <!-- Correo electrónico -->
                            <input type="email" name="email" placeholder="Correo electrónico" required>
                        </div>

                        <div class="field">
                            <!-- Contraseña -->
                            <input type="password" name="password" placeholder="Contraseña" required>
                        </div>

                        <div class="field">
                            <!-- Confirmación de contraseña -->
                            <input type="password" name="confirm_password" placeholder="Repetir contraseña" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Registrarse</button>
                    </form>
                </div>

            </div>

        </div>
    </div>
</section>

<?php include $base_path . 'includes/footer.php'; ?>
