<?php
// Página para procesar el inicio de sesión de usuarios

require_once dirname(__DIR__) . '/includes/config.php';

// Verificamos si se ha enviado el formulario de inicio de sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Obtenemos los datos del formulario
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $redirect = $_POST['redirect'] ?? '';

    // Validación de campos
    if ($username === '' || $password === '') {
        $_SESSION['flash_message'] = 'Por favor, completa todos los campos.';
        $_SESSION['flash_type'] = 'error';
        header('Location: ' . $base_url . 'account.php?login');
        exit;
    }

    // Obtenemos el usuario desde la base de datos
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificamos la contraseña
    if (!$user || !password_verify($password, $user['password'])) {
        $_SESSION['flash_message'] = 'Nombre de usuario o contraseña incorrectos';
        $_SESSION['flash_type'] = 'error';
        header('Location: ' . $base_url . 'account.php?login');
        exit;
    }

    // Autenticación
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    // Redirección si existe parámetro redirect
    if (!empty($redirect)) {
        if (str_starts_with($redirect, '/')) {
            // Redirigimos al usuario a la página original
            header('Location: ' . $redirect);
        } else {
            // Si redirect no es válido, enviamos al usuario a la página principal
            header('Location: ' . $base_url);
        }
        exit;
    }

    // Redirección sin parámetro redirect
    if ($user['role'] === 'admin') {
        // Redirigir al panel de administrador
        header('Location: ' . $base_url . 'admin/index.php');
    } else {
        // Redirigir al usuario normal a la página principal
        header('Location: ' . $base_url . 'index.php');
    }

    exit;
}
?>
