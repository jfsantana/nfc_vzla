-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 16-10-2026 a las 22:06:28
-- Versión del servidor: 9.1.0
-- Versión de PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `nfc_landing_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `analytics`
--

DROP TABLE IF EXISTS `analytics`;
CREATE TABLE IF NOT EXISTS `analytics` (
  `id` int NOT NULL AUTO_INCREMENT,
  `card_id` int NOT NULL,
  `scanned_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `user_agent` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `card_id` (`card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cards`
--

DROP TABLE IF EXISTS `cards`;
CREATE TABLE IF NOT EXISTS `cards` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_id` int NOT NULL,
  `landing_type_id` int NOT NULL,
  `user_id` int DEFAULT NULL COMMENT 'El empleado dueño de la tarjeta',
  `short_code` varchar(20) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `short_code` (`short_code`),
  KEY `company_id` (`company_id`),
  KEY `landing_type_id` (`landing_type_id`),
  KEY `fk_cards_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `card_links`
--

DROP TABLE IF EXISTS `card_links`;
CREATE TABLE IF NOT EXISTS `card_links` (
  `id` int NOT NULL AUTO_INCREMENT,
  `card_id` int NOT NULL,
  `type` enum('whatsapp','phone','email','website','pdf_catalog','instagram','linkedin','facebook','custom') NOT NULL,
  `label` varchar(100) NOT NULL,
  `url_value` varchar(255) NOT NULL,
  `position` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `card_id` (`card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `card_profiles`
--

DROP TABLE IF EXISTS `card_profiles`;
CREATE TABLE IF NOT EXISTS `card_profiles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `card_id` int NOT NULL,
  `full_name` varchar(150) DEFAULT NULL,
  `job_title` varchar(100) DEFAULT NULL,
  `bio_description` text,
  `avatar_url` varchar(255) DEFAULT NULL,
  `cover_url` varchar(255) DEFAULT NULL COMMENT 'Fondo específico para este empleado',
  `custom_css` text COMMENT 'CSS específico para esta tarjeta',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `card_id` (`card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `company_name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `rif` varchar(30) DEFAULT NULL,
  `phone_secondary` varchar(50) DEFAULT NULL,
  `address` text,
  `notes` text,
  `status` enum('lead','contacted','qualified','converted','discarded') DEFAULT 'lead',
  `source` varchar(50) DEFAULT 'landing_contact',
  `converted_company_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `status` (`status`),
  KEY `converted_company_id` (`converted_company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `companies`
--

DROP TABLE IF EXISTS `companies`;
CREATE TABLE IF NOT EXISTS `companies` (
  `id` int NOT NULL AUTO_INCREMENT,
  `plan_id` int DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `cover_url` varchar(255) DEFAULT NULL COMMENT 'Imagen de fondo o banner superior',
  `primary_color` varchar(7) DEFAULT '#000000',
  `secondary_color` varchar(7) DEFAULT '#FFFFFF',
  `custom_css` text COMMENT 'Código CSS inyectado para modificar la plantilla',
  `subscription_ends_at` date DEFAULT NULL,
  `status` enum('active','suspended_payment','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `plan_id` (`plan_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `companies`
--

INSERT INTO `companies` (`id`, `plan_id`, `name`, `slug`, `logo_url`, `cover_url`, `primary_color`, `secondary_color`, `custom_css`, `subscription_ends_at`, `status`, `created_at`, `updated_at`) VALUES
(7, 3, 'ADYARCA', 'adyarca', NULL, NULL, '#000000', '#FFFFFF', NULL, '2026-12-16', 'active', '2026-10-16 17:18:47', '2026-10-16 17:18:47'),
(8, 3, 'GH Hoteles', 'gh-hoteles', NULL, NULL, '#000000', '#FFFFFF', NULL, '2026-12-31', 'active', '2026-10-16 17:19:44', '2026-10-16 17:19:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `landing_types`
--

DROP TABLE IF EXISTS `landing_types`;
CREATE TABLE IF NOT EXISTS `landing_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `landing_types`
--

INSERT INTO `landing_types` (`id`, `name`, `description`) VALUES
(1, 'Personal', 'Perfil de empleado o ejecutivo (vCard)'),
(2, 'Publicitaria', 'Perfil institucional o de producto de la empresa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plans`
--

DROP TABLE IF EXISTS `plans`;
CREATE TABLE IF NOT EXISTS `plans` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `max_cards` int NOT NULL,
  `price` decimal(10,2) DEFAULT '0.00',
  `status` enum('active','inactive') DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `plans`
--

INSERT INTO `plans` (`id`, `name`, `max_cards`, `price`, `status`) VALUES
(1, 'Emprendedor', 5, 10.00, 'active'),
(2, 'Pyme', 20, 35.00, 'active'),
(3, 'Corporativo', 100, 100.00, 'active');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `slug` varchar(50) NOT NULL COMMENT 'Identificador interno (ej: superadmin, company_admin)',
  `name` varchar(100) NOT NULL COMMENT 'Nombre visible (ej: Súper Administrador)',
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `slug`, `name`, `description`) VALUES
(1, 'superadmin', 'Súper Administrador', 'Control total del SaaS y facturación'),
(2, 'company_admin', 'Administrador de Empresa', 'Gestión completa de la empresa, tarjetas y empleados'),
(3, 'hr_manager', 'Gerente de RRHH', 'Gestiona empleados y tarjetas, sin acceso a facturación o configuración global'),
(4, 'employee', 'Empleado', 'Solo puede editar su propia información de contacto y tarjeta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_id` int DEFAULT NULL,
  `role_id` int NOT NULL COMMENT 'Llave foránea hacia la tabla roles',
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `company_id` (`company_id`),
  KEY `fk_users_role` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `company_id`, `role_id`, `name`, `email`, `password`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, 'Super Admin', 'superadmin@sistemanfc.com', '$2y$10$ymc9Ju9d2mu.NX6hK5rcDeY/I8kixcXoPzMij0vPSs1Xu7gw./h86', 'active', '2026-10-15 18:35:12', '2026-10-15 18:35:12'),
(8, 7, 2, 'ADYARCA', 'JESUS.CARABOBO@GMAIL.COM', '$2y$10$bmBSYP13nBHoWR6ykTVXDefOIhk3YEjMs.9Sd9kX48TQVXk.ydNmy', 'active', '2026-10-16 17:19:16', '2026-10-16 17:19:16'),
(9, 8, 2, 'gh', 'jsantana@ghhoteles.com.ve', '$2y$10$H0p673PX9XgtDmoDoA3.Guv4mS8FvAyUD6M0/UFSGwrFI8VccT3GO', 'active', '2026-10-16 17:20:40', '2026-10-16 17:20:40'),
(11, NULL, 1, 'Jesus Santana', 'jfsantana77@gmail.com', '$2y$10$l7z.fUQ.pTLeYbbp2PkNbemrN4Opur8ur/iuVuJtB8yNFKjQXqvgm', 'active', '2026-10-16 18:49:34', '2026-10-16 18:49:34');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `analytics`
--
ALTER TABLE `analytics`
  ADD CONSTRAINT `analytics_ibfk_1` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `cards`
--
ALTER TABLE `cards`
  ADD CONSTRAINT `cards_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cards_ibfk_2` FOREIGN KEY (`landing_type_id`) REFERENCES `landing_types` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `fk_cards_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `card_links`
--
ALTER TABLE `card_links`
  ADD CONSTRAINT `card_links_ibfk_1` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `card_profiles`
--
ALTER TABLE `card_profiles`
  ADD CONSTRAINT `card_profiles_ibfk_1` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `fk_clients_converted_company` FOREIGN KEY (`converted_company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `companies_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
