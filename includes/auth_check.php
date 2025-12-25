<?php
// Validación de acceso (autenticación / rol).

/*
 * auth_check()
 * -----------------------
 * auth_check('user')  → acceso solo para usuarios autenticados
 * auth_check('admin') → acceso solo para administradores
 *
 * Si el usuario no está autenticado:
 *   → redirect = regresar a la página original después del inicio de sesión
 */

function auth_check(string $mode = 'user'): void {
    global $base_url;

    // 1. Verificar si el usuario está autenticado
    if (empty($_SESSION['username']) || empty($_SESSION['role'])) {

        // Guardamos la URL actual para regresar después del login
        $redirect = urlencode($_SERVER['REQUEST_URI']);

        // Redirigimos a la página de inicio de sesión
        header('Location: ' . $base_url . 'account.php?login&redirect=' . $redirect);
        exit;
    }

    // 2. Verificar acceso solo para administradores
    if ($mode === 'admin' && ($_SESSION['role'] !== 'admin')) {
        header('Location: ' . $base_url . 'index.php');
        exit;
    }
 
}
