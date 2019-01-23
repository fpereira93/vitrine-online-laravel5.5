-- --------------------------------------------------------
-- Servidor:                     localhost
-- Versão do servidor:           5.7.19 - MySQL Community Server (GPL)
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Copiando estrutura do banco de dados para atelie_maria_modas
CREATE DATABASE IF NOT EXISTS `atelie_maria_modas` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;
USE `atelie_maria_modas`;

-- Copiando estrutura para tabela atelie_maria_modas.brand
CREATE TABLE IF NOT EXISTS `brand` (
  `idBrand` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idBrand`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela atelie_maria_modas.brand: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `brand` DISABLE KEYS */;
REPLACE INTO `brand` (`idBrand`, `name`, `description`, `created_at`, `updated_at`) VALUES
	(4, 'AMM (Ateliê Maria Modas)', 'Ateliê Maria Modas', '2018-06-30 01:36:19', '2018-06-30 13:17:33');
/*!40000 ALTER TABLE `brand` ENABLE KEYS */;

-- Copiando estrutura para tabela atelie_maria_modas.category
CREATE TABLE IF NOT EXISTS `category` (
  `idCategory` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idCategory`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela atelie_maria_modas.category: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
REPLACE INTO `category` (`idCategory`, `name`, `description`, `created_at`, `updated_at`) VALUES
	(5, 'Vestidos de Festas', 'Categoria onde se enquadra vestidos de festas', '2018-06-30 01:35:18', '2018-06-30 01:35:18');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;

-- Copiando estrutura para tabela atelie_maria_modas.container
CREATE TABLE IF NOT EXISTS `container` (
  `idContainer` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idContainer`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela atelie_maria_modas.container: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `container` DISABLE KEYS */;
REPLACE INTO `container` (`idContainer`, `name`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'PRINCIPAIS ITENS', 'PRINCIPAIS ITENS', '2018-05-28 00:00:00', NULL),
	(2, 'ITENS RECOMENDADOS', 'ITENS RECOMENDADOS', '2018-05-28 00:00:00', NULL);
/*!40000 ALTER TABLE `container` ENABLE KEYS */;

-- Copiando estrutura para tabela atelie_maria_modas.container_product
CREATE TABLE IF NOT EXISTS `container_product` (
  `idContainerProduct` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `container` int(10) unsigned NOT NULL,
  `product` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idContainerProduct`),
  UNIQUE KEY `containerproduct_container_product_unique` (`container`,`product`),
  KEY `containerproduct_product_foreign` (`product`),
  CONSTRAINT `containerproduct_container_foreign` FOREIGN KEY (`container`) REFERENCES `container` (`idContainer`),
  CONSTRAINT `containerproduct_product_foreign` FOREIGN KEY (`product`) REFERENCES `product` (`idProduct`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela atelie_maria_modas.container_product: ~26 rows (aproximadamente)
/*!40000 ALTER TABLE `container_product` DISABLE KEYS */;
REPLACE INTO `container_product` (`idContainerProduct`, `container`, `product`, `created_at`, `updated_at`) VALUES
	(58, 1, 27, NULL, NULL),
	(59, 2, 27, NULL, NULL),
	(60, 1, 28, NULL, NULL),
	(61, 2, 28, NULL, NULL),
	(62, 1, 29, NULL, NULL),
	(63, 2, 29, NULL, NULL),
	(64, 1, 30, NULL, NULL),
	(65, 2, 30, NULL, NULL),
	(66, 1, 31, NULL, NULL),
	(67, 2, 31, NULL, NULL),
	(68, 1, 32, NULL, NULL),
	(69, 2, 32, NULL, NULL),
	(70, 1, 33, NULL, NULL),
	(71, 2, 33, NULL, NULL),
	(72, 1, 34, NULL, NULL),
	(73, 2, 34, NULL, NULL),
	(74, 1, 35, NULL, NULL),
	(75, 2, 35, NULL, NULL),
	(76, 1, 36, NULL, NULL),
	(77, 2, 36, NULL, NULL),
	(78, 1, 37, NULL, NULL),
	(79, 2, 37, NULL, NULL),
	(80, 1, 38, NULL, NULL),
	(81, 2, 38, NULL, NULL),
	(82, 1, 39, NULL, NULL),
	(83, 2, 39, NULL, NULL),
	(84, 1, 40, NULL, NULL),
	(85, 2, 40, NULL, NULL);
/*!40000 ALTER TABLE `container_product` ENABLE KEYS */;

-- Copiando estrutura para view atelie_maria_modas.count_likes_product
-- Criando tabela temporária para evitar erros de dependência de VIEW
CREATE TABLE `count_likes_product` (
	`idProduct` INT(10) UNSIGNED NOT NULL,
	`likes` BIGINT(21) NOT NULL
) ENGINE=MyISAM;

-- Copiando estrutura para tabela atelie_maria_modas.files
CREATE TABLE IF NOT EXISTS `files` (
  `FileId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `IdentifierModule` int(10) unsigned NOT NULL,
  `ModuleName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `OriginalName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `MimeType` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Size` int(10) unsigned NOT NULL,
  `Description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`FileId`)
) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela atelie_maria_modas.files: ~31 rows (aproximadamente)
/*!40000 ALTER TABLE `files` DISABLE KEYS */;
REPLACE INTO `files` (`FileId`, `IdentifierModule`, `ModuleName`, `Name`, `OriginalName`, `MimeType`, `Size`, `Description`, `created_at`, `updated_at`) VALUES
	(23, 1, 'App\\User', '3abd70edda6e877e5d95af84e2e8a18a.jpg', 'product7.jpg', 'image/png', 56028, NULL, '2018-06-24 20:59:51', '2018-06-24 20:59:51'),
	(85, 27, 'App\\Models\\Product', 'd7204384c201b87b6bb78d108cb9d7f0.jpg', '20171019_172838-1.jpg', 'image/jpeg', 728829, NULL, '2018-06-30 19:56:02', '2018-06-30 19:56:02'),
	(86, 27, 'App\\Models\\Product', 'cc5e04a4d9b3c5b23fbcbb3dd2c2a370.jpg', '20171019_172901-1.jpg', 'image/jpeg', 630241, NULL, '2018-06-30 19:56:02', '2018-06-30 19:56:02'),
	(87, 27, 'App\\Models\\Product', 'd3d659e97516a2395b082bb362d83e46.jpg', '20171019_172931%280%29-1.jpg', 'image/jpeg', 648972, NULL, '2018-06-30 19:56:03', '2018-06-30 19:56:03'),
	(88, 28, 'App\\Models\\Product', '3c2d9168030640213fe1098f88cbc88e.jpg', '20170907_102425-1.jpg', 'image/jpeg', 689834, NULL, '2018-06-30 20:01:27', '2018-06-30 20:01:27'),
	(89, 28, 'App\\Models\\Product', '9a766131cf2dc043b2c70207db9ae974.jpg', '20170907_102247-1.jpg', 'image/jpeg', 846280, NULL, '2018-06-30 20:01:28', '2018-06-30 20:01:28'),
	(90, 29, 'App\\Models\\Product', '87ed5f74ffc3e9d7ab6ac29b45d615ec.jpg', '20171122_153540-1.jpg', 'image/jpeg', 821790, NULL, '2018-06-30 20:04:19', '2018-06-30 20:04:19'),
	(91, 29, 'App\\Models\\Product', '4ac65da456a1aaa66f194c33e84af221.jpg', '20171122_153623-1.jpg', 'image/jpeg', 604467, NULL, '2018-06-30 20:04:21', '2018-06-30 20:04:21'),
	(92, 30, 'App\\Models\\Product', '9aea9a79a634369fd2fb668f74204431.jpg', '20171218_174706-1.jpg', 'image/jpeg', 1203200, NULL, '2018-06-30 20:07:27', '2018-06-30 20:07:27'),
	(93, 30, 'App\\Models\\Product', 'e915a408931f44b601c7a335e733b437.jpg', '20171218_174716-1.jpg', 'image/jpeg', 915544, NULL, '2018-06-30 20:07:28', '2018-06-30 20:07:28'),
	(94, 30, 'App\\Models\\Product', '77d4e1fa911a775376014988271a0e5d.jpg', '20171218_174806-1.jpg', 'image/jpeg', 698085, NULL, '2018-06-30 20:07:29', '2018-06-30 20:07:29'),
	(95, 31, 'App\\Models\\Product', '6bb9668574dc23c15e660d731700d75d.jpg', '20171216_170522.jpg', 'image/jpeg', 611071, NULL, '2018-06-30 20:10:59', '2018-06-30 20:10:59'),
	(96, 31, 'App\\Models\\Product', '722f5daec641adeab357599cc39ec0b6.jpg', '20171216_170549-1.jpg', 'image/jpeg', 608492, NULL, '2018-06-30 20:11:00', '2018-06-30 20:11:00'),
	(97, 31, 'App\\Models\\Product', 'd85dcff01f457dbf1175618c5ddde7d8.jpg', '20171216_170557-1.jpg', 'image/jpeg', 494910, NULL, '2018-06-30 20:11:01', '2018-06-30 20:11:01'),
	(98, 32, 'App\\Models\\Product', '8d587bf03402ddf7a75ef39274af0820.jpg', '20180614_183817%280%29-1-1.jpg', 'image/jpeg', 599508, NULL, '2018-06-30 20:13:25', '2018-06-30 20:13:25'),
	(99, 32, 'App\\Models\\Product', '77bb04ae67895771dad9a26b5a6ae18b.jpg', '20180614_183751-1.jpg', 'image/jpeg', 537840, NULL, '2018-06-30 20:13:26', '2018-06-30 20:13:26'),
	(100, 32, 'App\\Models\\Product', 'e4216f1c47f8684761e622a686f24cd3.jpg', '20180614_183841-1-1.jpg', 'image/jpeg', 328395, NULL, '2018-06-30 20:13:26', '2018-06-30 20:13:26'),
	(101, 33, 'App\\Models\\Product', '89824a49380f1ceaf4191857a44a25ce.jpg', '20171218_095513-1.jpg', 'image/jpeg', 710348, NULL, '2018-06-30 20:15:25', '2018-06-30 20:15:25'),
	(102, 33, 'App\\Models\\Product', 'f17908dbcd3bc32c1a962600d1c8c3e7.jpg', '20171218_095537-1.jpg', 'image/jpeg', 773035, NULL, '2018-06-30 20:15:27', '2018-06-30 20:15:27'),
	(103, 34, 'App\\Models\\Product', '675cc57b457d32ecaba2c50af3c350c2.jpg', '20170118_191107-1.jpg', 'image/jpeg', 331379, NULL, '2018-06-30 20:21:41', '2018-06-30 20:21:41'),
	(104, 34, 'App\\Models\\Product', '4d88bccd19cb4e668d917d166251a659.jpg', '20170118_191052-1.jpg', 'image/jpeg', 444106, NULL, '2018-06-30 20:21:41', '2018-06-30 20:21:41'),
	(105, 35, 'App\\Models\\Product', 'dbbb663f47d584545286dd18671dd33d.jpg', '20170610_115959-1.jpg', 'image/jpeg', 530013, NULL, '2018-06-30 20:28:34', '2018-06-30 20:28:34'),
	(106, 36, 'App\\Models\\Product', '49ed2dae56eeb54229be5845a6c2d89e.jpg', '20171021_105444-1.jpg', 'image/jpeg', 722108, NULL, '2018-06-30 20:30:54', '2018-06-30 20:30:54'),
	(107, 36, 'App\\Models\\Product', '141894e7226a5d1aa1ada36fec486bd7.jpg', '20171021_105452-1.jpg', 'image/jpeg', 1001454, NULL, '2018-06-30 20:30:55', '2018-06-30 20:30:55'),
	(108, 37, 'App\\Models\\Product', '5189c2cab5862578c0c7706f80464bf8.jpg', '20171212_192915%280%29-1.jpg', 'image/jpeg', 708646, NULL, '2018-06-30 20:33:05', '2018-06-30 20:33:05'),
	(109, 37, 'App\\Models\\Product', '1e73a670a471112a79ad326d3863afbe.jpg', '20171212_192913-1.jpg', 'image/jpeg', 724695, NULL, '2018-06-30 20:33:06', '2018-06-30 20:33:06'),
	(110, 38, 'App\\Models\\Product', '9851a336f2d2b4609cc39d2990f478d8.jpg', '20180627_181956-1.jpg', 'image/jpeg', 421448, NULL, '2018-06-30 20:35:53', '2018-06-30 20:35:53'),
	(111, 39, 'App\\Models\\Product', '1e3bbf9c6194e24b37aa2d995f5fff0b.jpg', '20171118_200745-1.jpg', 'image/jpeg', 635470, NULL, '2018-06-30 20:39:01', '2018-06-30 20:39:01'),
	(112, 39, 'App\\Models\\Product', 'bb8a614e4132bb593d0fe20eb2e8305f.jpg', '20171118_200815-1.jpg', 'image/jpeg', 507173, NULL, '2018-06-30 20:39:02', '2018-06-30 20:39:02'),
	(113, 39, 'App\\Models\\Product', '60f0406f1659120005e00876793ead59.jpg', '20171118_200811-1.jpg', 'image/jpeg', 619226, NULL, '2018-06-30 20:39:03', '2018-06-30 20:39:03'),
	(114, 40, 'App\\Models\\Product', '8dabb5e4ae2dfa8dc9ab684690f6d32d.jpg', '20171207_180111-1.jpg', 'image/jpeg', 674432, NULL, '2018-06-30 20:44:18', '2018-06-30 20:44:18'),
	(115, 40, 'App\\Models\\Product', '7fea45dbbbc721a2ff1bd30dd0dbb6b8.jpg', '20171207_180156-1.jpg', 'image/jpeg', 480194, NULL, '2018-06-30 20:44:18', '2018-06-30 20:44:18');
/*!40000 ALTER TABLE `files` ENABLE KEYS */;

-- Copiando estrutura para tabela atelie_maria_modas.like_heart_product
CREATE TABLE IF NOT EXISTS `like_heart_product` (
  `idLikeHeartProduct` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `product` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idLikeHeartProduct`),
  KEY `like_heart_product_product_foreign` (`product`),
  CONSTRAINT `like_heart_product_product_foreign` FOREIGN KEY (`product`) REFERENCES `product` (`idProduct`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela atelie_maria_modas.like_heart_product: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `like_heart_product` DISABLE KEYS */;
REPLACE INTO `like_heart_product` (`idLikeHeartProduct`, `ip_address`, `product`, `created_at`, `updated_at`) VALUES
	(25, '191.209.10.6', 27, '2018-06-30 20:58:57', '2018-06-30 20:58:57');
/*!40000 ALTER TABLE `like_heart_product` ENABLE KEYS */;

-- Copiando estrutura para tabela atelie_maria_modas.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela atelie_maria_modas.migrations: ~16 rows (aproximadamente)
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
REPLACE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_resets_table', 1),
	(3, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
	(4, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
	(5, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
	(6, '2016_06_01_000004_create_oauth_clients_table', 1),
	(7, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
	(8, '2017_12_09_153511_add_users_lasAccess', 1),
	(9, '2017_12_16_130754_create_files', 1),
	(10, '2018_04_30_201600_AJUSTES_REFERENTE_IMAGEM_PERFIL_USUARIO', 1),
	(11, '2018_05_22_150643_create_permission_tables', 1),
	(12, '2018_05_26_123521_table_category', 2),
	(13, '2018_05_26_123755_table_brand', 2),
	(14, '2018_05_26_124631_table_product', 2),
	(15, '2018_05_26_125500_table_container', 2),
	(18, '2018_05_30_192611_table_container_product', 3),
	(19, '2018_06_01_134020_add_column_set_main_image', 4),
	(20, '2018_06_27_203317_create_like_heart_product', 5),
	(21, '2018_06_28_154508_create_view_count_likes_product', 6);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

-- Copiando estrutura para tabela atelie_maria_modas.model_has_permissions
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` int(10) unsigned NOT NULL,
  `model_id` int(10) unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela atelie_maria_modas.model_has_permissions: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;

-- Copiando estrutura para tabela atelie_maria_modas.model_has_roles
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` int(10) unsigned NOT NULL,
  `model_id` int(10) unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela atelie_maria_modas.model_has_roles: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
REPLACE INTO `model_has_roles` (`role_id`, `model_id`, `model_type`) VALUES
	(1, 1, 'App\\User');
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;

-- Copiando estrutura para tabela atelie_maria_modas.oauth_access_tokens
CREATE TABLE IF NOT EXISTS `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `client_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela atelie_maria_modas.oauth_access_tokens: ~45 rows (aproximadamente)
/*!40000 ALTER TABLE `oauth_access_tokens` DISABLE KEYS */;
REPLACE INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
	('065f19e21fe42ed56ca1df27b92dc0f39a56eab3fe9230b3647320e083a8acc10e2508598db86af6', 1, 1, NULL, '[]', 0, '2018-05-23 23:19:31', '2018-05-23 23:19:31', '2019-05-23 23:19:31'),
	('07eccc68061e2a5c17ea96ad3bb0ad87adae58bd6215b0e094213a8c1ca38f27c5d3ebcf73906a4a', 1, 1, NULL, '[]', 0, '2018-06-15 00:22:17', '2018-06-15 00:22:17', '2019-06-15 00:22:17'),
	('085187f628eb51d4fa58a26a97ab66c317a3042949851d661c8ef242d99b1e94d6cf056c5486b84d', 1, 1, NULL, '[]', 0, '2018-06-30 23:01:50', '2018-06-30 23:01:50', '2019-06-30 23:01:50'),
	('0a401fc235e5d9065c1b3a1913109993c874928daf33da00a95a80cec9ff5aa87d4c66207fc02658', 1, 1, NULL, '[]', 0, '2018-06-25 02:06:08', '2018-06-25 02:06:08', '2019-06-25 02:06:08'),
	('0d469a5a301332f10e7147e01bb9138f4680692fe3a992c2aa72384e78e012fd85948526bc774e2f', 1, 1, NULL, '[]', 0, '2018-06-01 21:40:09', '2018-06-01 21:40:09', '2019-06-01 21:40:09'),
	('118e73dd95236f5e7d50807770e5de1dbbd19e0db7eba63311a275ac257382309d22a8b7e335ef09', 1, 1, NULL, '[]', 0, '2018-06-30 01:28:12', '2018-06-30 01:28:12', '2019-06-30 01:28:12'),
	('1f7c7db2e8be0b41beb3aa6522813d3a29b5defab586edd140aa1fd4307297b94a37966bfa1bc181', 1, 1, NULL, '[]', 0, '2018-06-14 00:24:19', '2018-06-14 00:24:19', '2019-06-14 00:24:19'),
	('211003815cdda14a0eaecdeadba7b9fd7228fdf640d454d7b8148210e711642c0ecde688020eeabd', 1, 1, NULL, '[]', 0, '2018-06-20 14:06:57', '2018-06-20 14:06:57', '2019-06-20 14:06:57'),
	('25b6cdef3f032e61a29fbde10976e0abdc74eaad68ea6d55f5896565cd8722ea6c2cd6ad44433b54', 1, 1, NULL, '[]', 0, '2018-06-26 13:54:32', '2018-06-26 13:54:32', '2019-06-26 13:54:32'),
	('28591d55281b3f533bcef31cb19ca0da2b2fe3c16c17ef6703083f3bec2a58bdac68931bec8aa05f', 1, 1, NULL, '[]', 0, '2018-05-30 18:59:35', '2018-05-30 18:59:35', '2019-05-30 18:59:35'),
	('28ccda0d370ca4c00400fa83566464b7ead55cf6e624ed2a4c98e06d5a47672f6bb9681d865162b2', 1, 1, NULL, '[]', 0, '2018-06-27 15:19:32', '2018-06-27 15:19:32', '2019-06-27 15:19:32'),
	('2c07f628b7540e744f45def51fa7e054943f0ed879779018d8eb043af266280e30ff8098b6c2e77f', 1, 1, NULL, '[]', 0, '2018-06-13 00:40:32', '2018-06-13 00:40:32', '2019-06-13 00:40:32'),
	('340d164ac2ce1e5fc087616414a1c24f7f505be456749b5347dec025b79043b5f814a333dd578bb6', 1, 1, NULL, '[]', 0, '2018-06-06 01:02:58', '2018-06-06 01:02:58', '2019-06-06 01:02:58'),
	('34fea41aefce4dcad09d90b23171163cf98711db09bb4d6eb1e09930acb0bf43f8f53cb165fd78be', 1, 1, NULL, '[]', 0, '2018-05-30 18:45:03', '2018-05-30 18:45:03', '2019-05-30 18:45:03'),
	('38238f8ceb7ede4ff48cf2e44e6062ee424f825db85bd0d033828c5c1ecffad2de9fc2928c547cb3', 1, 1, NULL, '[]', 0, '2018-05-22 15:50:16', '2018-05-22 15:50:16', '2019-05-22 15:50:16'),
	('383ed33ca864edebcdbb3ae5619211dcbd597b2c933832e10caad35b3eb6dfe2ecd95d35e7708ac3', 1, 1, NULL, '[]', 0, '2018-06-01 12:57:15', '2018-06-01 12:57:15', '2019-06-01 12:57:15'),
	('4a884d47a26eeda45191eb72c913603ccb090eaa1b68ff9d8e589578ab43c2b5fed45400c2b2a5c6', 1, 1, NULL, '[]', 0, '2018-05-27 14:34:59', '2018-05-27 14:34:59', '2019-05-27 14:34:59'),
	('4ea78ba9c2c5f67a28e09334ce6949bbd7d31ab27b6c559c9139738f8ce10c2b508c2ba184cba325', 1, 1, NULL, '[]', 0, '2018-05-26 12:31:21', '2018-05-26 12:31:21', '2019-05-26 12:31:21'),
	('4f651246b3d282032c35c735ec462eac45cd0986ed45f24a77bee1b8c85fbdc27916feee11d4fa0c', 1, 1, NULL, '[]', 0, '2018-06-24 16:12:10', '2018-06-24 16:12:10', '2019-06-24 16:12:10'),
	('565f8343857d2058a7c29ff139f673926462cc754ad67d11f41e3bf99fee75d4b8ca4143a02074c7', 1, 1, NULL, '[]', 0, '2018-05-22 19:28:42', '2018-05-22 19:28:42', '2019-05-22 19:28:42'),
	('57ef67e18cb232c98a9daf9b275c6433c4dff9c076a235f80ba495e66fe8f71219755cdb377b4bd8', 1, 1, NULL, '[]', 0, '2018-05-28 12:19:24', '2018-05-28 12:19:24', '2019-05-28 12:19:24'),
	('660cb98484f7ebd21479615bb7615b162394d417c1c9b2674faa443aba057e9b7f531e233a9b2098', 1, 1, NULL, '[]', 0, '2018-07-01 15:47:30', '2018-07-01 15:47:30', '2019-07-01 15:47:30'),
	('707b99520eb180b651aadc650c2b5df0b49f3d4de9527db33723145031662dde881547113d39edd3', 1, 1, NULL, '[]', 0, '2018-06-09 16:54:49', '2018-06-09 16:54:49', '2019-06-09 16:54:49'),
	('72fee0396974be83bc8de4051652330855879e224e64d702deb15153ba74d285b4d7b99c762c3c10', 1, 1, NULL, '[]', 0, '2018-06-01 19:16:24', '2018-06-01 19:16:24', '2019-06-01 19:16:24'),
	('73f7b6f3472d7d2d449af4679326907d5bacfd9ebb5b0b15a3b687b1ee6c27bf7adb8f083e808815', 1, 1, NULL, '[]', 0, '2018-05-30 18:20:59', '2018-05-30 18:20:59', '2019-05-30 18:20:59'),
	('7a33f13f4fc3e405cb3f499b42f1b4f326442f5fefc924886cbc95f09ad423069912e239952fbc92', 1, 1, NULL, '[]', 0, '2018-05-22 19:16:08', '2018-05-22 19:16:08', '2019-05-22 19:16:08'),
	('82a004c66ac278f2c871675a06b8408e29e5d4791b0bd681137871f9eb9b8f8e86b35c6b0c24ec2b', 1, 1, NULL, '[]', 0, '2018-05-22 16:19:02', '2018-05-22 16:19:02', '2019-05-22 16:19:02'),
	('8a6f07deaa17b37d47c1e66e1f3dc12750e977a565b1b372a8df54214e510363bd2d3fac36dd14b3', 1, 1, NULL, '[]', 0, '2018-06-04 10:35:17', '2018-06-04 10:35:17', '2019-06-04 10:35:17'),
	('94f5f2dd261a2e5a358af226747e3ef616485d1d0514ab42690b028f29141f702c6115a9bd1b59ef', 1, 1, NULL, '[]', 0, '2018-06-01 21:15:19', '2018-06-01 21:15:19', '2019-06-01 21:15:19'),
	('959653ecc2691c1dc7372845a012253f35b5d81058e0445e4c13f87d4b29d04d480e83d7c1b81452', 1, 1, NULL, '[]', 0, '2018-05-22 19:04:41', '2018-05-22 19:04:41', '2019-05-22 19:04:41'),
	('9fde0e3aa59ac86cd88cd899fdc6a198fd55265c4c040462402200ee50c1eb42cb2caa18ccb74ab0', 1, 1, NULL, '[]', 0, '2018-05-30 16:38:43', '2018-05-30 16:38:43', '2019-05-30 16:38:43'),
	('a1dbec4f4bfdffd3b88f893074e5599e2def388619841842bf116bf9f97373ca97a61b7f8a9b2834', 1, 1, NULL, '[]', 0, '2018-06-25 14:52:12', '2018-06-25 14:52:12', '2019-06-25 14:52:12'),
	('a2711971ac4e7fc693673021a84d1eb9ead4f9f7d261717c922011ac4ccc424456f31d43130672d9', 1, 1, NULL, '[]', 0, '2018-06-25 19:45:01', '2018-06-25 19:45:01', '2019-06-25 19:45:01'),
	('a42e4fe99a57f60c34c389b320ad873b75d092aef8c73ce9a9978628834192d3a636c8738e3963c3', 1, 1, NULL, '[]', 0, '2018-06-02 12:59:28', '2018-06-02 12:59:28', '2019-06-02 12:59:28'),
	('a48c5e857b3b1e20ed0d987172106cda89ed1853c576246fb0fda3958549f2372f9b58d51a690a05', 1, 1, NULL, '[]', 0, '2018-06-16 13:20:31', '2018-06-16 13:20:31', '2019-06-16 13:20:31'),
	('a4a3a9d1540d847f76804fbcd81cf6a36a037b611598f3ca945fe39df23143b16a15d6d7e4709388', 1, 1, NULL, '[]', 0, '2018-06-28 12:56:59', '2018-06-28 12:56:59', '2019-06-28 12:56:59'),
	('a8124e157073414449534677d0a55526ae11fbeca7d8d13d43e819a06d14d675f2d52037f2818c46', 1, 1, NULL, '[]', 0, '2018-06-26 13:54:33', '2018-06-26 13:54:33', '2019-06-26 13:54:33'),
	('b2c173c5875b2c2167e13e533e42c3feef25b55ba2c60a4cef82bdf6c985d84085e92bfb39f9db78', 1, 1, NULL, '[]', 0, '2018-06-13 00:37:49', '2018-06-13 00:37:49', '2019-06-13 00:37:49'),
	('b602fbbea929354f9c9ac48ad5ee59006d6d3fc47733f19ee312bddbc5af8f5041e3ce5cc7e417c2', 1, 1, NULL, '[]', 0, '2018-06-03 15:33:28', '2018-06-03 15:33:28', '2019-06-03 15:33:28'),
	('ba5335b65ac6233debff8b47c4cd317bb6190d5ade357f406a01cd6976a003e368e2c877d0cd4bfe', 1, 1, NULL, '[]', 0, '2018-06-21 13:20:48', '2018-06-21 13:20:48', '2019-06-21 13:20:48'),
	('beb660b0f7c8107cca09867dc51ad64139771887fc6a5aef4b8493d328bdfdbc33a077f833f18055', 1, 1, NULL, '[]', 0, '2018-06-01 21:20:37', '2018-06-01 21:20:37', '2019-06-01 21:20:37'),
	('c9293e36132f4f55ce3ecae171f0ee37375c791ec663f48977f600992796b00ce1e12d530a2e9a07', 1, 1, NULL, '[]', 0, '2018-05-22 16:19:08', '2018-05-22 16:19:08', '2019-05-22 16:19:08'),
	('cbdabe6c7223156db81db71dadfd7878a8e348b6a7a83d5c80897e5a0a5af0f089390474dfa90599', 1, 1, NULL, '[]', 0, '2018-06-04 10:37:19', '2018-06-04 10:37:19', '2019-06-04 10:37:19'),
	('ccf3c872678d6a97873719c75432e291ae3368e4dde87034c37795af1ec1270c4bc90f93bbbf0de3', 1, 1, NULL, '[]', 0, '2018-05-22 19:04:40', '2018-05-22 19:04:40', '2019-05-22 19:04:40'),
	('ce64257514b90ef293dad47f22b9d2372975c8d1b10c3d479eac0005b0a147f1b88db9da1f70aab9', 1, 1, NULL, '[]', 0, '2018-05-22 19:23:54', '2018-05-22 19:23:54', '2019-05-22 19:23:54'),
	('d18eb6486c115a23505d355622f3a6a530a45fc6f8c2624ee6fbb7345e3daf59f9a82732e26159ea', 1, 1, NULL, '[]', 0, '2018-06-01 21:50:46', '2018-06-01 21:50:46', '2019-06-01 21:50:46'),
	('d5b39406fc28c16a01f4361e47e1fa7fb5e0362950a14980a157bf3684e62b76241f4ddf5ec10312', 1, 1, NULL, '[]', 0, '2018-06-30 18:00:22', '2018-06-30 18:00:22', '2019-06-30 18:00:22'),
	('d64bd079f542cbd95f9419a78984c4c966cc71c4fda3340e79ef9dee17859ea57d6b9a01f9e3357b', 1, 1, NULL, '[]', 0, '2018-05-26 17:56:47', '2018-05-26 17:56:47', '2019-05-26 17:56:47'),
	('dbc1ed38d0d2d9785adead6e5d9226dfc04daae975fd5a4dc906dc56aa346e554eca394fdf9054ce', 1, 1, NULL, '[]', 0, '2018-05-29 13:19:00', '2018-05-29 13:19:00', '2019-05-29 13:19:00'),
	('e3bc2f4c242b885fc88018d0eecf8e66585acb021e5a97c4101188af05c19f6d319999ae5b5448d2', 1, 1, NULL, '[]', 0, '2018-06-27 18:51:38', '2018-06-27 18:51:38', '2019-06-27 18:51:38'),
	('e520cf37aae45584a74c30353c2b2b2d8f2399e3658fcff8d367083c399bebdd2e9786a846e02da3', 1, 1, NULL, '[]', 0, '2018-06-30 13:16:46', '2018-06-30 13:16:46', '2019-06-30 13:16:46'),
	('e5edc0c445e3f573ba8bdc9ce504526f353071007e8f2c8d695832c556fed5c7c013fdbec6bf1b08', 1, 1, NULL, '[]', 0, '2018-05-24 12:21:47', '2018-05-24 12:21:47', '2019-05-24 12:21:47'),
	('e67319ff908223b92717fe56e4ea0c985be887f24956bbd078ee9e71a15cbbfb74c41d014d68e7c6', 1, 1, NULL, '[]', 0, '2018-06-30 01:24:38', '2018-06-30 01:24:38', '2019-06-30 01:24:38'),
	('eb6663d9a4bafdb88d62e9d8bf3926567e4e6423e1ef3a29ebe7e8ec4710ff60d7a808a0303a8c0b', 1, 1, NULL, '[]', 0, '2018-06-27 20:36:20', '2018-06-27 20:36:20', '2019-06-27 20:36:20'),
	('ec38318b92b9ab8ba416eb3b0668cda000ec5dc1dcf3dd5953c8220e61af9a80ea56a79cc7f9368c', 1, 1, NULL, '[]', 0, '2018-05-31 13:36:57', '2018-05-31 13:36:57', '2019-05-31 13:36:57'),
	('ff009069fc56b08ee3eeebb6c1aa34e669d080c938475ef3d8ab20c7cc695460f4c01021dab615f7', 1, 1, NULL, '[]', 0, '2018-06-04 22:33:22', '2018-06-04 22:33:22', '2019-06-04 22:33:22');
/*!40000 ALTER TABLE `oauth_access_tokens` ENABLE KEYS */;

-- Copiando estrutura para tabela atelie_maria_modas.oauth_auth_codes
CREATE TABLE IF NOT EXISTS `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `scopes` text COLLATE utf8_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela atelie_maria_modas.oauth_auth_codes: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `oauth_auth_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_auth_codes` ENABLE KEYS */;

-- Copiando estrutura para tabela atelie_maria_modas.oauth_clients
CREATE TABLE IF NOT EXISTS `oauth_clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `redirect` text COLLATE utf8_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela atelie_maria_modas.oauth_clients: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `oauth_clients` DISABLE KEYS */;
REPLACE INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
	(1, NULL, 'Maria Modas Personal Access Client', '7hW2AJHzfWhXpbyyIHrx7lWJ46N07HGmSgVK88cE', 'http://localhost', 1, 0, 0, '2018-05-22 15:49:34', '2018-05-22 15:49:34'),
	(2, NULL, 'Maria Modas Password Grant Client', 'VWdmOKnvRpfS1Q9VhtjmsEXfZSgJ8Bq3RAAPea82', 'http://localhost', 0, 1, 0, '2018-05-22 15:49:34', '2018-05-22 15:49:34');
/*!40000 ALTER TABLE `oauth_clients` ENABLE KEYS */;

-- Copiando estrutura para tabela atelie_maria_modas.oauth_personal_access_clients
CREATE TABLE IF NOT EXISTS `oauth_personal_access_clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_personal_access_clients_client_id_index` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela atelie_maria_modas.oauth_personal_access_clients: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `oauth_personal_access_clients` DISABLE KEYS */;
REPLACE INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
	(1, 1, '2018-05-22 15:49:34', '2018-05-22 15:49:34');
/*!40000 ALTER TABLE `oauth_personal_access_clients` ENABLE KEYS */;

-- Copiando estrutura para tabela atelie_maria_modas.oauth_refresh_tokens
CREATE TABLE IF NOT EXISTS `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela atelie_maria_modas.oauth_refresh_tokens: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `oauth_refresh_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_refresh_tokens` ENABLE KEYS */;

-- Copiando estrutura para tabela atelie_maria_modas.password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela atelie_maria_modas.password_resets: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;

-- Copiando estrutura para tabela atelie_maria_modas.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela atelie_maria_modas.permissions: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;

-- Copiando estrutura para tabela atelie_maria_modas.product
CREATE TABLE IF NOT EXISTS `product` (
  `idProduct` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `stock` int(11) NOT NULL,
  `category` int(10) unsigned NOT NULL,
  `brand` int(10) unsigned NOT NULL,
  `price` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `mainImage` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`idProduct`),
  KEY `product_category_foreign` (`category`),
  KEY `product_brand_foreign` (`brand`),
  KEY `product_mainimage_foreign` (`mainImage`),
  CONSTRAINT `product_brand_foreign` FOREIGN KEY (`brand`) REFERENCES `brand` (`idBrand`),
  CONSTRAINT `product_category_foreign` FOREIGN KEY (`category`) REFERENCES `category` (`idCategory`),
  CONSTRAINT `product_mainimage_foreign` FOREIGN KEY (`mainImage`) REFERENCES `files` (`FileId`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela atelie_maria_modas.product: ~13 rows (aproximadamente)
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
REPLACE INTO `product` (`idProduct`, `name`, `description`, `stock`, `category`, `brand`, `price`, `created_at`, `updated_at`, `mainImage`) VALUES
	(27, 'Vestido de Renda', 'Vestido de Renda Rosa Bordado', 0, 5, 4, 0.00, '2018-06-30 19:56:01', '2018-06-30 19:56:03', 87),
	(28, 'Vestido Azul', 'Vestido Azul com Fenda', 0, 5, 4, 0.00, '2018-06-30 20:01:25', '2018-06-30 20:01:27', 88),
	(29, 'Vestido Azul Escuro', 'Vestido Azul Escuro', 0, 5, 4, 0.00, '2018-06-30 20:04:18', '2018-06-30 20:04:19', 90),
	(30, 'Vestido Rosa  Veludo', 'Vestido Rosa  Veludo', 0, 5, 4, 0.00, '2018-06-30 20:07:24', '2018-06-30 20:07:27', 92),
	(31, 'Vestido Curto', 'Vestido Curto', 0, 5, 4, 0.00, '2018-06-30 20:10:58', '2018-06-30 20:11:11', 96),
	(32, 'Vestido Longo Azul', 'Vestido Longo Azul', 0, 5, 4, 0.00, '2018-06-30 20:13:24', '2018-06-30 20:13:25', 98),
	(33, 'Vestido Amarelo de Renda', 'Vestido Amarelo de Renda', 0, 5, 4, 0.00, '2018-06-30 20:15:24', '2018-06-30 20:15:25', 101),
	(34, 'Vestido Longo', 'Vestido Longo', 0, 5, 4, 0.00, '2018-06-30 20:21:40', '2018-06-30 20:21:41', 103),
	(35, 'Vestido Longo Rosa de Renda', 'Vestido Longo', 0, 5, 4, 0.00, '2018-06-30 20:28:33', '2018-06-30 20:28:34', 105),
	(36, 'Vestido Longo Rosa', 'Vestido Longo Rosa', 0, 5, 4, 0.00, '2018-06-30 20:30:53', '2018-06-30 20:30:54', 106),
	(37, 'Vestido Curto Vermelho', 'Vestido Curto Vermelho', 0, 5, 4, 0.00, '2018-06-30 20:33:04', '2018-06-30 20:33:05', 108),
	(38, 'Vestido Longo Azul Renda', 'Vestido Longo', 0, 5, 4, 0.00, '2018-06-30 20:35:53', '2018-06-30 20:35:53', 110),
	(39, 'Vestido Longo Bege', 'Vestido Longo Bege', 0, 5, 4, 0.00, '2018-06-30 20:39:00', '2018-06-30 20:39:01', 111),
	(40, 'Vestido Longo', 'Vestido Longo', 0, 5, 4, 0.00, '2018-06-30 20:44:15', '2018-06-30 20:44:18', 114);
/*!40000 ALTER TABLE `product` ENABLE KEYS */;

-- Copiando estrutura para tabela atelie_maria_modas.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela atelie_maria_modas.roles: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
REPLACE INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'superadmin', 'web', '2018-05-22 15:49:38', '2018-05-22 15:49:38');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;

-- Copiando estrutura para tabela atelie_maria_modas.role_has_permissions
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela atelie_maria_modas.role_has_permissions: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;

-- Copiando estrutura para tabela atelie_maria_modas.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `lastAccess` datetime DEFAULT NULL,
  `AvatarFileId` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_avatarfileid_foreign` (`AvatarFileId`),
  CONSTRAINT `users_avatarfileid_foreign` FOREIGN KEY (`AvatarFileId`) REFERENCES `files` (`FileId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Copiando dados para a tabela atelie_maria_modas.users: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
REPLACE INTO `users` (`id`, `name`, `email`, `password`, `deleted_at`, `remember_token`, `created_at`, `updated_at`, `lastAccess`, `AvatarFileId`) VALUES
	(1, 'Administrador', 'admin@ateliemariamodas.com', '$2y$10$UOfGMCpJr3x/5l1gsUhmnODTkXB/fiU7MR6bQSkKB3QxFqOAwTOl6', NULL, '63QR1tPsfSIdTa40osMlfNPwWLKGNLI5xONjEOeVUOpGvnpkfHHsT6MrKY1F', '2018-05-22 15:49:38', '2018-07-01 15:47:28', '2018-07-01 15:47:28', 23);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Copiando estrutura para view atelie_maria_modas.count_likes_product
-- Removendo tabela temporária e criando a estrutura VIEW final
DROP TABLE IF EXISTS `count_likes_product`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` VIEW `count_likes_product` AS select 
       product.idProduct,
       count(like_heart.product) as likes
   from
   	 product
       left join like_heart_product like_heart on (like_heart.product = product.idProduct)
   group by product.idProduct ;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
