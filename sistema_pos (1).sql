-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-05-2025 a las 03:04:09
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_pos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pcs_armadas`
--

CREATE TABLE `pcs_armadas` (
  `id` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario` varchar(100) NOT NULL,
  `gama` varchar(20) NOT NULL,
  `cpu` varchar(100) NOT NULL,
  `ram` varchar(255) NOT NULL,
  `storage` varchar(255) NOT NULL,
  `mb` varchar(100) NOT NULL,
  `gpu` varchar(100) NOT NULL,
  `psu` varchar(100) NOT NULL,
  `case` varchar(100) NOT NULL,
  `observaciones` text DEFAULT NULL,
  `checklist_qa` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pcs_armadas`
--

INSERT INTO `pcs_armadas` (`id`, `fecha`, `usuario`, `gama`, `cpu`, `ram`, `storage`, `mb`, `gpu`, `psu`, `case`, `observaciones`, `checklist_qa`) VALUES
(1, '2025-05-28 19:45:07', 'asd', 'baja', 'Intel Pentium G6400', '8GB DDR3 1600MHz', '120GB SSD', 'MSI A320M-A PRO', 'NVIDIA GT 1030', '400W Genérica', 'Caja Pequeña', '', '[{\"nombre\":\"CPU\",\"ok\":true,\"fail\":false,\"obs\":\"\"},{\"nombre\":\"RAM\",\"ok\":true,\"fail\":false,\"obs\":\"\"},{\"nombre\":\"STORAGE\",\"ok\":true,\"fail\":false,\"obs\":\"\"},{\"nombre\":\"MB\",\"ok\":true,\"fail\":false,\"obs\":\"\"},{\"nombre\":\"GPU\",\"ok\":true,\"fail\":false,\"obs\":\"\"},{\"nombre\":\"PSU\",\"ok\":true,\"fail\":false,\"obs\":\"\"},{\"nombre\":\"CASE\",\"ok\":true,\"fail\":false,\"obs\":\"\"}]');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recepciones`
--

CREATE TABLE `recepciones` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_recibido` time NOT NULL,
  `cliente_nombre` varchar(255) NOT NULL,
  `cliente_telefono` varchar(20) DEFAULT NULL,
  `equipo_tipo` varchar(100) DEFAULT NULL,
  `equipo_password` varchar(100) DEFAULT NULL,
  `diagnostico` varchar(255) DEFAULT NULL,
  `extras` text DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `hora_entrega` time DEFAULT NULL,
  `costo` decimal(10,2) DEFAULT NULL,
  `tipo_moneda` varchar(10) DEFAULT NULL,
  `foto_url` varchar(255) DEFAULT NULL,
  `codigo_unico` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `codigo_generado` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `recepciones`
--

INSERT INTO `recepciones` (`id`, `fecha`, `hora_recibido`, `cliente_nombre`, `cliente_telefono`, `equipo_tipo`, `equipo_password`, `diagnostico`, `extras`, `observaciones`, `fecha_entrega`, `hora_entrega`, `costo`, `tipo_moneda`, `foto_url`, `codigo_unico`, `created_at`, `codigo_generado`) VALUES
(6, '2025-04-30', '20:32:00', 'dasds', 'asdasd', 'asdasd', 'asdasd', 'asdadsasd', '{\"cargador\":true,\"bateria\":true,\"bolsa\":false,\"estuche\":true,\"cable\":false}', 'adsasd', '2025-05-02', '11:38:00', 123.00, 'S/', 'images/foto_681250c97bf002.93106630.png', NULL, '2025-04-30 16:33:17', '00001A'),
(7, '2025-04-30', '18:37:00', 'asdads', 'asdasd', 'asddas', 'asd', 'asdadssad', '{\"cargador\":true,\"bateria\":true,\"bolsa\":false,\"estuche\":true,\"cable\":false}', 'asasdasd', '2025-05-01', '14:40:00', 123.00, 'S/', 'images/foto_681251e6b26855.56361277.png', NULL, '2025-04-30 16:38:00', '00015A'),
(8, '2025-05-27', '15:57:00', '', '', '', '', '', '{\"cargador\":false,\"bateria\":false,\"bolsa\":false,\"estuche\":false,\"cable\":false}', '', '0000-00-00', '00:00:00', 0.00, 'S/', '', NULL, '2025-05-27 20:57:29', '00091A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cliente` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transaction_items`
--

CREATE TABLE `transaction_items` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `descripcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'asd', 'asd@gmail.com', '$2y$10$TB0kB2YbYTN7O87V0slLF.OeI6KpXABTXVNTRSN/WyntBqIXa4hf.', '2025-04-24 17:28:29'),
(2, 'johan', 'johan12@gmail.com', '$2y$10$bYPhA91twLRxIxonRaQFfOc7R0kU6w/udRhVhUuCxmgWJEi3VW5nW', '2025-04-24 23:30:50'),
(3, 'Andersson Almerco', 'jhordan2402@gmail.com', '$2y$10$Sc9dxepCw2qWj2IbZg9QOeZt1wiBk4LvoXYcqSw/T9kv8kkSISghO', '2025-04-25 17:12:38'),
(4, 'andre', 'andre@gmail.com', '$2y$10$C2bZDsJ8eNIE.QchDWedVeV0eKGRQvHuVpF1R54UetHupQ9PQ77C6', '2025-04-25 20:40:12');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `pcs_armadas`
--
ALTER TABLE `pcs_armadas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `recepciones`
--
ALTER TABLE `recepciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `pcs_armadas`
--
ALTER TABLE `pcs_armadas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `recepciones`
--
ALTER TABLE `recepciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `transaction_items`
--
ALTER TABLE `transaction_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD CONSTRAINT `transaction_items_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
