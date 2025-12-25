<?php
// Página para procesar el registro de nuevos usuarios

require_once dirname(__DIR__) . '/includes/config.php';

// Verificamos si la sesión está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si el formulario fue enviado mediante POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Obtenemos los datos del formulario
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    // Comprobamos si todos los campos están completos
    if ($username === '' || $email === '' || $password === '' || $confirm === '') {
        $_SESSION['flash_message'] = "¡Por favor, completa todos los campos!";
        $_SESSION['flash_type'] = "error";
        header('Location: ' . $base_url . 'account.php?register');
        exit;
    }

    // Validamos el formato del correo electrónico
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['flash_message'] = "Correo electrónico no válido.";
        $_SESSION['flash_type'] = "error";
        header('Location: ' . $base_url . 'account.php?register');
        exit;
    }

    // Verificamos si las contraseñas coinciden
    if ($password !== $confirm) {
        $_SESSION['flash_message'] = "¡Las contraseñas no coinciden!";
        $_SESSION['flash_type'] = "error";
        header('Location: ' . $base_url . 'account.php?register');
        exit;
    }

    // Comprobamos si ya existe un usuario con ese email o nombre
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
    $stmt->execute([$email, $username]);
    if ($stmt->fetch()) {
        $_SESSION['flash_message'] = "¡El usuario o correo ya existen!";
        $_SESSION['flash_type'] = "error";
        header('Location: ' . $base_url . 'account.php?register');
        exit;
    }

    // Encriptamos la contraseña antes de guardarla
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Guardamos el nuevo usuario en la base de datos
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'client')");
    $stmt->execute([$username, $email, $hash]);

    // Autenticamos al usuario después del registro
    $_SESSION['username'] = $username;
    $_SESSION['role'] = 'client';

    // Redirigimos a la página principal
    header('Location: ' . $base_url . 'index.php');
    exit;
}

// Si la solicitud no es POST, redirigimos a la página de registro
header('Location: ' . $base_url . 'account.php?register');
exit;
?>
