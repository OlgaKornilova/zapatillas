<?php  // Header del sitio web ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Zapatillas.es - MEDAC </title>
    <!-- Usamos time(); para no almacenar los archivos en caché durante el desarrollo. En un proyecto comercial, eliminar.-->
    <link rel="stylesheet" href="<?= $base_url ?>assets/style.css?v=<?php echo time(); ?>" />
    <!-- Conexión de una fuente externa -->
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Conexión e inicialización del script externo JavaScript - MicroModal.js para las ventanas modales -->
    <script src="https://unpkg.com/micromodal/dist/micromodal.min.js"></script>
    <script>
        MicroModal.init();
    </script>

    <!-- Conexión del archivo JavaScript interno - main.js -->
    <script src="<?= $base_url ?>assets/js/main.js?v=<?php echo time(); ?>"></script>

    <!-- Estilos de pagina del administrador , visible solo en las páginas dentro de /admin/ -->
    <?php if (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false): ?>
    <link rel="stylesheet" href="<?= $base_url ?>assets/admin.css?v=<?= time(); ?>">
    <?php endif; ?>
</head>
<body>
    <nav class="navbar">
        <div class="container nav-inner">
            <div class="logo">
                <a href="<?= $base_url ?>index.php"><img src="<?= $base_url ?>assets/images/logo.png" alt="logo"></a>
            </div>
            
            <div class="nav-menu">
                <ul id="MenuItems" class="nav-links">
                    <li><a href="<?= $base_url ?>index.php">Inicio</a></li>
                    <li><a href="<?= $base_url ?>products.php">Productos</a></li>
                </ul>
            </div>

            <div class="nav-icons">
                <?php
                if (session_status() === PHP_SESSION_NONE) session_start();
                $cart_count = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'qty')) : 0;
                ?>
                <!-- Icon cart svg -->
                <a href="<?= $base_url ?>cart.php" class="nav-icon js-call-minicart">
                    <svg viewBox="0 0 298 399" xmlns="http://www.w3.org/2000/svg">
                        <g transform="matrix(1.3333 0 0 -1.3333 0 398.67)">
                            <g transform="scale(0.1)">
                                <path d="M2233.36 2432.71H0V0h2233.36v2432.71zm-220-220V220H220v1992.71h1793.36"
                                    fill="currentColor" />
                                <path d="m1116.68 2990c-361.22 0-654.04-292.82-654.04-654.04V2216.92H1770.7v119.04c0 361.22-292.82 
                                    654.04-654.02 654.04zm0-220c204.58 0 376.55-142.29 422.19-333.08H694.49c45.62 190.79 
                                    217.61 333.08 422.19 333.08" fill="currentColor" />
                                <path d="M1554.82 1888.17H678.54v169.54h876.28v-169.54" fill="currentColor" />
                            </g>
                        </g>
                    </svg>
                                    <?php if ($cart_count > 0): ?>
                    <span class="cart-count"><?= $cart_count ?></span>
                <?php endif; ?>
                </a>

                <!-- Icon user svg -->
                <div class="user-menu-wrapper">
                    <?php if (isset($_SESSION['username'])): ?>
                        <div class="user-toggle">
                            <svg viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg" class="user-icon">
                                <g transform="matrix(1.3333 0 0 -1.3333 0 400)">
                                    <g transform="scale(0.1)">
                                        <path d="M1506.87 2587.11c-225.04 0-408.14-183.08-408.14-408.11 0-225.06 183.1-408.13 
                                            408.14-408.13 225.02 0 408.13 183.07 408.13 408.13 0 225.03-183.11 408.11-408.13 
                                            408.11zm0-1038.56c-347.64 0-630.43 282.79-630.43 630.45s282.79 630.43 
                                            630.43 630.43 630.42-282.8 630.42-630.43-282.79-630.45-630.42-630.45"
                                            fill="currentColor" />
                                        <path d="M399.65 361.79h2214.42c-25.06 261.53-139.49 503.46-327.47 689.83-124.25 123.14-300.78 
                                            193.96-483.86 193.96h-591.76c-183.61 0-359.6-70.82-483.86-193.96C539.15 865.25 
                                            424.72 623.32 399.65 361.79zm2331.04-222.33H283.03c-61.56 0-111.16 49.59-111.16 
                                            111.16 0 363.44 141.68 704 398.32 959.02 165.66 164.55 399.41 258.82 640.78 
                                            258.82h591.76c241.94 0 475.14-94.27 640.8-258.82 256.63-255.02 
                                            398.31-595.58 398.31-959.02 0-61.57-49.59-111.16-111.16-111.16"
                                            fill="currentColor" />
                                    </g>
                                </g>
                            </svg>
                            <span class="user-name">Hola, <?= htmlspecialchars($_SESSION['username']) ?></span>
                        </div>

                        <!-- Menu desplegable -->
                        <div class="user-dropdown">
                            <!-- Si tienes derechos de administrador, te mostraremos un enlace al panel de administración. -->
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <a href="<?= $base_url ?>admin/index.php" class="dropdown-link">Admin</a>
                            <?php endif; ?>
                            <a href="<?= $base_url ?>profile.php" class="dropdown-link">Perfil</a>
                            <a href="<?= $base_url ?>auth/logout.php" class="dropdown-link">Salir</a>
                        </div>

                    <?php else: ?>
                        
                        <button onclick="MicroModal.show('modal-1')" class="nav-icon js-call-account">
                            <svg viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
                                <g transform="matrix(1.3333 0 0 -1.3333 0 400)">
                                    <g transform="scale(0.1)">
                                        <path d="M1506.87 2587.11c-225.04 0-408.14-183.08-408.14-408.11 0-225.06 183.1-408.13 
                                            408.14-408.13 225.02 0 408.13 183.07 408.13 408.13 0 225.03-183.11 408.11-408.13 
                                            408.11zm0-1038.56c-347.64 0-630.43 282.79-630.43 630.45s282.79 630.43 
                                            630.43 630.43 630.42-282.8 630.42-630.43-282.79-630.45-630.42-630.45"
                                            fill="currentColor" />
                                        <path d="M399.65 361.79h2214.42c-25.06 261.53-139.49 503.46-327.47 689.83-124.25 123.14-300.78 
                                            193.96-483.86 193.96h-591.76c-183.61 0-359.6-70.82-483.86-193.96C539.15 865.25 
                                            424.72 623.32 399.65 361.79zm2331.04-222.33H283.03c-61.56 0-111.16 49.59-111.16 
                                            111.16 0 363.44 141.68 704 398.32 959.02 165.66 164.55 399.41 258.82 640.78 
                                            258.82h591.76c241.94 0 475.14-94.27 640.8-258.82 256.63-255.02 
                                            398.31-595.58 398.31-959.02 0-61.57-49.59-111.16-111.16-111.16"
                                            fill="currentColor" />
                                    </g>
                                </g>
                            </svg>
                        </button>
                    <?php endif; ?>
                </div>
                <!-- Mobile menu placeholder -->
                <a href="javascript:void(0)" class="nav-icon menuleft" onclick="menutoggle()">
                    <svg viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
                        <path d="M904 160H120v80h784v-80zm0 624H120v80h784v-80zm0-312H120v80h784v-80z" fill="currentColor"/>
                    </svg>
                </a>

            </div>
        </div>
    </nav>

    <!-- Menú de administrador, visible solo en las páginas dentro de /admin/ -->
    <?php if (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false): ?>
        <div class="admin-header">
            <div class="container admin-nav">
                <h1>Panel de Administración</h1>
                <nav class="admin-menu">
                    <a href="<?= $base_url ?>admin/index.php">Inicio</a>
                    <a href="<?= $base_url ?>admin/users.php">Usuarios</a>
                    <a href="<?= $base_url ?>admin/products.php">Productos</a>
                    <a href="<?= $base_url ?>admin/orders.php">Pedidos</a>
                </nav>
            </div>
        </div>
    <?php endif; ?>
