-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql107.infinityfree.com
-- Generation Time: Dec 25, 2025 at 04:13 PM
-- Server version: 11.4.7-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_40743557_medac_shoes`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`) VALUES
(1, 'Casual Sneakers', 'casual-sneakers'),
(2, 'Running Shoes', 'running-shoes'),
(3, 'Training Shoes', 'training-shoes');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(150) NOT NULL,
  `customer_email` varchar(150) NOT NULL,
  `customer_phone` varchar(50) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `shipping_method` varchar(50) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `customer_email`, `customer_phone`, `city`, `address`, `postal_code`, `shipping_method`, `payment_method`, `subtotal`, `tax`, `total`, `created_at`) VALUES
(13, 'Test', 'test@gmail.com', '604300200', 'Malaga', 'av. las palmeras. 2', '29600', 'standard', 'card', '480.00', '100.80', '580.80', '2025-12-22 20:18:54');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size` varchar(20) DEFAULT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `size`, `qty`, `price`, `subtotal`, `product_name`, `product_price`) VALUES
(12, 13, 6, '42', 2, '150.00', '300.00', 'adidas Zapatilla Adizero Boston 13', NULL),
(13, 13, 12, '40', 1, '180.00', '180.00', 'adidas Dropset Control', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `photo` varchar(255) DEFAULT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `created_at`, `photo`, `category_id`) VALUES
(3, 'Converse Chuck Taylor All Star Ox', 'Las zapatillas bajas blancas Converse Chuck Taylor All Star Ox son un clásico atemporal, perfecto para el uso diario en primavera y verano.\r\nEsta versión masculina del icónico modelo está fabricada con una parte superior de lona y conserva las características líneas de diseño Converse.\r\n\r\nLas zapatillas cuentan con:\r\n\r\nun perfil bajo para mayor comodidad y ligereza;\r\n\r\nuna mediasuela amortiguada que reduce el impacto al caminar;\r\n\r\ncordones blancos que resaltan las franjas roja y azul características de la suela;\r\n\r\nuna suela de goma con el dibujo clásico del protector y el logotipo Converse All Star en el talón.\r\n\r\n🧩 Composición: parte superior — textil; suela — material sintético.', '190.00', '2025-12-19 02:13:01', 'img_6944b4ada60e0_1.jpg', 1),
(4, 'adidas Originals Stan Smith', 'Recorre las calles con un clásico. El tenista Stan Smith conquistó las pistas de todo el mundo en los 70.\r\nCálzate la zapatilla que lleva su nombre y derrocha estilo urbano.\r\nEsta versión conserva la parte superior de piel y la silueta minimalista del modelo original de 1971.\r\n\r\n• Corte clásico\r\n• Cierre de cordones\r\n• Parte superior de piel\r\n• Zapatilla atemporal de inspiración tenística\r\n• Forro de piel y plantilla OrthoLite®\r\n• Suela de goma cosida', '190.00', '2025-12-22 13:55:17', 'img_69494dc5de6b8_1.jpg', 1),
(5, 'Fred Perry B721', 'Mejora tu estilo crepé con estos tenis B721 para hombre de Fred Perry.\r\nEn una combinación de colores blanco y azul marino, estas zapatillas están confeccionadas con cuero duradero en la parte superior para brindar una sensación suave y una comodidad duradera.\r\nCuentan con un cierre de cordones para sujetarlo, con un cuello acolchado para mayor soporte y un forro textil para un ajuste ceñido.\r\nBajo los pies, se asientan sobre una entresuela acolchada para una, con una suela exterior de goma adherente para una tracción esencial.\r\nTerminado con la marca Fred Perry Laurel Wreath.\r\n\r\nComposición y materiales: Empeine de cuero / suela sintética', '200.00', '2025-12-22 13:55:59', 'img_69494def8deaf_1.jpg', 1),
(6, 'adidas Zapatilla Adizero Boston 13', 'Cuando persigues tu mejor marca personal, cada detalle cuenta.\r\nLa zapatilla Adizero Boston 13 se ha diseñado para los que viven intensamente cada carrera.\r\nCuenta con un empeine de malla extremadamente ligero, ajustado y transpirable que mantiene tus pies frescos y cómodos mientras devoras cada kilómetro.\r\nLas tecnologías Lightstrike y LightstrikePro redefinen el concepto de velocidad con una mediasuela superligera diseñada para aportar dinamismo a todos tus movimientos.\r\nHaz transiciones suaves y fluidas con la suela Lighttraxion y el compuesto de caucho Continental™ en la puntera para minimizar el peso y maximizar el agarre.\r\nPor último, las varillas Energyrods 2.0 de fibra de vidrio aportan un impulso mejorado a tu zancada.\r\nCompite o entrena con esta zapatilla que garantiza un rendimiento excepcional y refleja el compromiso de adidas con la innovación y la excelencia.\r\n\r\n• Horma clásica\r\n• Cordones\r\n• Empeine textil\r\n• Plantilla textil\r\n• Mediasuela LIGHTSTRIKEPRO\r\n• Tecnología LIGHTSTRIKE\r\n• Peso: 260 g\r\n• Drop de la mediasuela: 6 mm (talón: 36 mm / antepié: 30 mm)\r\n\r\nComposición y materiales: Parte superior textil', '150.00', '2025-12-22 13:56:46', 'img_69494e1e15436_1.jpg', 2),
(7, 'HOKA Bondi 9', 'Dale un empujón a tu rendimiento.\r\nEstas zapatillas de running Bondi 9 para hombre de HOKA están confeccionadas con malla ligera y transpirable.\r\nCuentan con un cuello acolchado para mayor sujeción.\r\nUna lengüeta facilita el calzado y el descalzado.\r\nLa estructura Active Foot Frame, centrada en el talón, proporciona estabilidad.\r\nLa suela intermedia de espuma gruesa aporta elasticidad y rebote, y la tecnología MetaRocker garantiza transiciones suaves.\r\nLa suela exterior de goma adherente ofrece una tracción firme.\r\nCon un diseño Outer Orbit, llevan el distintivo de la marca HOKA.\r\n\r\nComposición y materiales: Textile & Synthetic Upper / Synthetic Sole', '125.00', '2025-12-22 13:57:32', 'img_69494e4c79bbb_1.jpg', 2),
(8, 'On Running Cloudhorizon Waterproof', 'Persigue las vistas del amanecer con total comodidad con estas zapatillas de hombre Cloudhorizon Waterproof de On Running.\r\nEn una combinación de colores Niebla y Espino, estas zapatillas están diseñadas para los senderos con una suela exterior de goma Missiongrip que está hecha para terrenos secos y húmedos, mientras que la gruesa capa de amortiguación CloudTec Phase crea zancadas suaves y onduladas.\r\nEn la parte superior, la suave y flexible malla de ingeniería incorpora una membrana impermeable para mantener el pie seco cuando cambia el tiempo.\r\nCon una caída del talón a la punta de 6 mm, están rematadas con trabillas en el talón y el logo característico de On Running.\r\n\r\nComposición y materiales: Textile & Synthetic Upper / Synthetic Sole', '250.00', '2025-12-22 13:58:23', 'img_69494e7f96d68_1.jpg', 2),
(9, 'adidas Terrex Free Hiker 2.0 Low Gore-Tex Hiking', 'Los entusiastas del senderismo saben que el viaje es tan importante como el destino.\r\nExplora la naturaleza con confianza gracias a la adidas TERREX Free Hiker 2.0.\r\nEsta zapatilla combina lo mejor del calzado de trail running y senderismo para ofrecerte ligereza, sujeción y comodidad durante todas tus aventuras.\r\nLa suela con compuesto de caucho Continental™ garantiza una adherencia óptima en cualquier superficie, ya sea rocosa, con raíces, seca o mojada.\r\nLa mediasuela con amortiguación BOOST proporciona un retorno de energía increíble y una pisada más cómoda.\r\nLa membrana impermeable GORE-TEX mantiene los pies secos a pesar de la humedad.\r\nAl elegir materiales reciclados, podemos dar nueva vida a materiales que ya se han utilizado, lo que contribuye a reducir los residuos.\r\nLa utilización de materiales renovables nos ayuda a acabar con nuestra dependencia de los recursos finitos.\r\nNuestros productos fabricados con materiales reciclados y renovables contienen al menos un 20% de estos materiales.\r\n\r\n• Horma clásica\r\n• Cierre de cordones\r\n• Empeine de malla con refuerzos sellados para una mayor resistencia al desgaste\r\n• Membrana GORE-TEX\r\n• Refuerzo exterior de EVA para mayor estabilidad y clip estabilizador en el talón\r\n• Mediasuela BOOST\r\n• Peso: 462 g (talla 42 2/3)\r\n• Drop: 10 mm (talón 30 мм / antepié 20 мм)\r\n• Suela con compuesto de caucho Continental™\r\n• Contiene al menos un 20 % de material reciclado y renovable\r\n\r\nComposición y materiales: Parte superior textil', '200.00', '2025-12-22 13:59:04', 'img_69494ea85f219_1.jpg', 3),
(10, 'adidas Dame X', 'Damian Lillard manipula el juego mejor que nadie.\r\nDame X, la última zapatilla bandera de baloncesto de adidas y Damian Lillard, se diseñó específicamente para el juego característico de Dame.\r\nEl empeine textil ligero aporta transpirabilidad para mejorar el rendimiento, mientras que los detalles estampados tridimensionales ofrecen sujeción en las partes más importantes del calzado.\r\nLa tracción en todas las direcciones de la suela de goma y la mediasuela ultraligera te ayudan a moverte a toda velocidad y a atravesar la cancha con la confianza de una superestrella.\r\nLa distintiva paleta de colores y los logos de Dame rinden homenaje a los grandes momentos en la vida y la carrera de uno de los mejores jugadores de baloncesto.\r\n\r\n• Horma clásica\r\n• Empeine textil y sintético\r\n• Forro textil\r\n• Amortiguación Lightstrike\r\n• Suela de goma\r\n\r\nComposición y materiales: Parte superior textil', '300.00', '2025-12-22 13:59:46', 'img_69494ed291cd2_1.jpg', 3),
(11, 'adidas D.O.N. Issue 7', 'Prepárate para un nuevo Don.\r\nEl D.O.N. Issue #7 es la zapatilla bandera más reciente de adidas Basketball y el increíble Donovan Mitchell.\r\nEsta zapatilla de baloncesto de alto rendimiento, de diseño estilizado y moderno, está hecha para el tipo de jugadas que han convertido a Spida en una de las promesas más dinámicas de la cancha.\r\nEl cuello blando y el refuerzo de TPO aportan más comodidad y sujeción, mientras la mediasuela superligera Lightstrike Pro te ayuda a volar sobre la pista a velocidad imparable.\r\nUn logotipo de Spida y sus colores distintivos aportan el toque final.\r\n\r\n• Corte clásico\r\n• Empeine textil\r\n• Forro textil\r\n• Amortiguación Lightstrike Pro\r\n• Suela de goma\r\n\r\nComposición y materiales: Parte superior textil', '260.00', '2025-12-22 14:00:26', 'img_69494efa0b363_1.jpg', 3),
(16, 'adidas D.O.N. Issue 7', 'Descripción', '400.00', '2025-12-22 20:45:40', 'img_6949adf4908c2_1.jpg', 3);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `is_main` tinyint(1) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `filename`, `is_main`, `sort_order`, `created_at`) VALUES
(6, 3, 'img_6944b4ada60e0_1.jpg', 1, 0, '2025-12-19 02:13:01'),
(7, 3, 'img_6944b4ada60e0_2.jpg', 0, 0, '2025-12-19 02:13:01'),
(8, 3, 'img_6944b4ada60e0_3.jpg', 0, 0, '2025-12-19 02:13:01'),
(9, 3, 'img_6944b4ada60e0_4.jpg', 0, 0, '2025-12-19 02:13:01'),
(10, 3, 'img_6944b4ada60e0_5.jpg', 0, 0, '2025-12-19 02:13:01'),
(11, 3, 'img_6944b4ada60e0_6.jpg', 0, 0, '2025-12-19 02:13:01'),
(12, 4, 'img_69494dc5de6b8_1.jpg', 1, 0, '2025-12-22 13:55:17'),
(13, 4, 'img_69494dc5de6b8_2.jpg', 0, 0, '2025-12-22 13:55:17'),
(14, 4, 'img_69494dc5de6b8_3.jpg', 0, 0, '2025-12-22 13:55:17'),
(15, 4, 'img_69494dc5de6b8_4.jpg', 0, 0, '2025-12-22 13:55:17'),
(16, 4, 'img_69494dc5de6b8_5.jpg', 0, 0, '2025-12-22 13:55:17'),
(17, 5, 'img_69494def8deaf_1.jpg', 1, 0, '2025-12-22 13:55:59'),
(18, 5, 'img_69494def8deaf_2.jpg', 0, 0, '2025-12-22 13:55:59'),
(19, 5, 'img_69494def8deaf_3.jpg', 0, 0, '2025-12-22 13:55:59'),
(20, 5, 'img_69494def8deaf_4.jpg', 0, 0, '2025-12-22 13:55:59'),
(21, 5, 'img_69494def8deaf_5.jpg', 0, 0, '2025-12-22 13:55:59'),
(22, 6, 'img_69494e1e15436_1.jpg', 1, 0, '2025-12-22 13:56:46'),
(23, 6, 'img_69494e1e15436_2.jpg', 0, 0, '2025-12-22 13:56:46'),
(24, 6, 'img_69494e1e15436_3.jpg', 0, 0, '2025-12-22 13:56:46'),
(25, 6, 'img_69494e1e15436_4.jpg', 0, 0, '2025-12-22 13:56:46'),
(26, 7, 'img_69494e4c79bbb_1.jpg', 1, 0, '2025-12-22 13:57:32'),
(27, 7, 'img_69494e4c79bbb_2.jpg', 0, 0, '2025-12-22 13:57:32'),
(28, 7, 'img_69494e4c79bbb_3.jpg', 0, 0, '2025-12-22 13:57:32'),
(29, 7, 'img_69494e4c79bbb_4.jpg', 0, 0, '2025-12-22 13:57:32'),
(30, 7, 'img_69494e4c79bbb_5.jpg', 0, 0, '2025-12-22 13:57:32'),
(31, 8, 'img_69494e7f96d68_1.jpg', 1, 0, '2025-12-22 13:58:23'),
(32, 8, 'img_69494e7f96d68_2.jpg', 0, 0, '2025-12-22 13:58:23'),
(33, 8, 'img_69494e7f96d68_3.jpg', 0, 0, '2025-12-22 13:58:23'),
(34, 8, 'img_69494e7f96d68_4.jpg', 0, 0, '2025-12-22 13:58:23'),
(35, 8, 'img_69494e7f96d68_5.jpg', 0, 0, '2025-12-22 13:58:23'),
(36, 9, 'img_69494ea85f219_1.jpg', 1, 0, '2025-12-22 13:59:04'),
(37, 9, 'img_69494ea85f219_2.jpg', 0, 0, '2025-12-22 13:59:04'),
(38, 9, 'img_69494ea85f219_3.jpg', 0, 0, '2025-12-22 13:59:04'),
(39, 9, 'img_69494ea85f219_4.jpg', 0, 0, '2025-12-22 13:59:04'),
(40, 9, 'img_69494ea85f219_5.jpg', 0, 0, '2025-12-22 13:59:04'),
(41, 10, 'img_69494ed291cd2_1.jpg', 1, 0, '2025-12-22 13:59:46'),
(42, 10, 'img_69494ed291cd2_2.jpg', 0, 0, '2025-12-22 13:59:46'),
(43, 10, 'img_69494ed291cd2_3.jpg', 0, 0, '2025-12-22 13:59:46'),
(44, 10, 'img_69494ed291cd2_4.jpg', 0, 0, '2025-12-22 13:59:46'),
(45, 10, 'img_69494ed291cd2_5.jpg', 0, 0, '2025-12-22 13:59:46'),
(46, 11, 'img_69494efa0b363_1.jpg', 1, 0, '2025-12-22 14:00:26'),
(47, 11, 'img_69494efa0b363_2.jpg', 0, 0, '2025-12-22 14:00:26'),
(48, 11, 'img_69494efa0b363_3.jpg', 0, 0, '2025-12-22 14:00:26'),
(49, 11, 'img_69494efa0b363_4.jpg', 0, 0, '2025-12-22 14:00:26'),
(50, 11, 'img_69494efa0b363_5.jpg', 0, 0, '2025-12-22 14:00:26'),
(51, 11, 'img_69494efa0b363_6.jpg', 0, 0, '2025-12-22 14:00:26'),
(67, 16, 'img_6949adf4908c2_1.jpg', 1, 0, '2025-12-22 20:45:40'),
(68, 16, 'img_6949adf4908c2_2.jpg', 0, 0, '2025-12-22 20:45:40'),
(69, 16, 'img_6949adf4908c2_3.jpg', 0, 0, '2025-12-22 20:45:40'),
(70, 16, 'img_6949adf4908c2_4.jpg', 0, 0, '2025-12-22 20:45:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','client') DEFAULT 'client',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@zapatillas.es', '$2y$12$w8uuR6VsceRD10nbxSwSGuTMx1g2DDQE1yizsoYs0oRoyYikWRJO.', 'admin', '2025-12-18 20:46:43', '2025-12-22 20:31:14'),
(13, 'test', 'test@gmail.com', '$2y$12$7dyPT6fOqQJ9UtAvduW37ePVY7/IGZ0vFjQAzG8ictaeESUzD8iRK', 'admin', '2025-12-21 22:56:08', '2025-12-22 20:30:57'),
(18, 'nombre2', 'nombre@gmail.com', '$2y$12$k3wUMV1s8s7vAT3.2ijjuevs3dL4W7lmmcKCboWJg3x3F/AmIQNMW', 'admin', '2025-12-22 20:30:52', '2025-12-22 20:31:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_products_category` (`category_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_product_images` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `fk_product_images` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
