/*
 Navicat Premium Data Transfer

 Source Server         : anas
 Source Server Type    : MySQL
 Source Server Version : 80030 (8.0.30)
 Source Host           : localhost:3306
 Source Schema         : koperasi-alizzah

 Target Server Type    : MySQL
 Target Server Version : 80030 (8.0.30)
 File Encoding         : 65001

 Date: 06/12/2024 12:47:46
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for ledgers
-- ----------------------------
DROP TABLE IF EXISTS `ledgers`;
CREATE TABLE `ledgers`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `refrence` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `current` decimal(11, 2) NOT NULL,
  `debit` decimal(11, 2) NOT NULL,
  `credit` decimal(11, 2) NOT NULL,
  `final` decimal(11, 2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ledgers
-- ----------------------------
INSERT INTO `ledgers` VALUES (1, 'pemasukan', NULL, 'SALDO', 0.00, 1000000.00, 0.00, 1000000.00, '2024-11-22 09:36:06', '2024-11-22 09:36:06', NULL);
INSERT INTO `ledgers` VALUES (2, 'pengeluaran', NULL, 'PN00001', 1000000.00, 0.00, 0.00, 1000000.00, '2024-11-22 09:37:02', '2024-11-22 09:37:02', NULL);
INSERT INTO `ledgers` VALUES (3, 'pengeluaran', NULL, 'PN00002', 1000000.00, 0.00, 0.00, 1000000.00, '2024-11-22 09:37:35', '2024-11-22 09:37:35', NULL);
INSERT INTO `ledgers` VALUES (4, 'pengeluaran', 'bayar hutang', '1', 1000000.00, 0.00, 500000.00, 500000.00, '2024-11-22 09:39:51', '2024-11-22 09:39:51', NULL);
INSERT INTO `ledgers` VALUES (5, 'pengeluaran', 'bayar hutang', '2', 500000.00, 0.00, 450000.00, 50000.00, '2024-11-22 09:40:09', '2024-11-22 09:40:09', NULL);
INSERT INTO `ledgers` VALUES (6, 'pengeluaran', NULL, 'PN00003', 50000.00, 0.00, 0.00, 50000.00, '2024-11-28 22:08:35', '2024-11-28 22:08:35', NULL);
INSERT INTO `ledgers` VALUES (7, 'pengeluaran', 'bayar hutang', '3', 50000.00, 0.00, 250000.00, -200000.00, '2024-11-28 22:09:17', '2024-11-28 22:09:17', NULL);
INSERT INTO `ledgers` VALUES (8, 'pemasukan', NULL, 'OR00001', -200000.00, 100000.00, 0.00, -100000.00, '2024-11-28 22:12:41', '2024-11-28 22:12:41', NULL);
INSERT INTO `ledgers` VALUES (9, 'pengeluaran', 'piutang anggota', '1', -100000.00, 0.00, 2000000.00, -2100000.00, '2024-12-03 11:37:34', '2024-12-03 11:37:34', NULL);
INSERT INTO `ledgers` VALUES (10, 'pemasukan', 'bayar piutang', '1', -2100000.00, 200000.00, 0.00, -1900000.00, '2024-12-06 10:33:02', '2024-12-06 10:33:02', NULL);

-- ----------------------------
-- Table structure for members
-- ----------------------------
DROP TABLE IF EXISTS `members`;
CREATE TABLE `members`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'teacher, vendor, other',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 33 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of members
-- ----------------------------
INSERT INTO `members` VALUES (1, '1', 'teacher', 'Abdul Rohim, S.PdI', '2024-11-30 10:56:45', '2024-11-30 10:56:45');
INSERT INTO `members` VALUES (2, '2', 'teacher', 'Khoirul Izzah, S.Pd AUD', '2024-11-30 10:56:45', '2024-11-30 10:56:45');
INSERT INTO `members` VALUES (3, '3', 'teacher', 'Miftahul Jannah, S.Pd', '2024-11-30 10:56:45', '2024-11-30 10:56:45');
INSERT INTO `members` VALUES (4, '4', 'teacher', 'Fatimah Zahroh, S.Pd', '2024-11-30 10:56:45', '2024-11-30 10:56:45');
INSERT INTO `members` VALUES (5, '5', 'teacher', 'Umami Faizah, SE, S.Pd', '2024-11-30 10:56:45', '2024-11-30 10:56:45');
INSERT INTO `members` VALUES (6, '6', 'teacher', 'Siti Khomsiyah, S.Pd AUD', '2024-11-30 10:56:45', '2024-11-30 10:56:45');
INSERT INTO `members` VALUES (7, '7', 'teacher', 'Iin Mayasari, S.Pd', '2024-11-30 10:56:45', '2024-11-30 10:56:45');
INSERT INTO `members` VALUES (8, '8', 'teacher', 'Indah Susanti, S.Pd', '2024-11-30 10:56:45', '2024-11-30 10:56:45');
INSERT INTO `members` VALUES (9, '9', 'teacher', 'Sri Wahyudati, S.Pd', '2024-11-30 10:56:45', '2024-11-30 10:56:45');
INSERT INTO `members` VALUES (10, '10', 'teacher', 'Maratul Mufidah, S.Pd', '2024-11-30 10:56:45', '2024-11-30 10:56:45');
INSERT INTO `members` VALUES (11, '11', 'teacher', 'Siti Zulaikhah, S.Pd', '2024-11-30 10:56:45', '2024-11-30 10:56:45');
INSERT INTO `members` VALUES (12, '12', 'teacher', 'Khafidhotul Mushonnifah', '2024-11-30 10:56:45', '2024-11-30 10:56:45');
INSERT INTO `members` VALUES (13, '13', 'teacher', 'Heni Khumaaidah, S.Pd', '2024-11-30 10:56:45', '2024-11-30 10:56:45');
INSERT INTO `members` VALUES (14, '14', 'teacher', 'Choirul Ummah', '2024-11-30 10:56:45', '2024-11-30 10:56:45');
INSERT INTO `members` VALUES (15, '15', 'teacher', 'Elis Masrikhah, S.Pd', '2024-11-30 10:56:45', '2024-11-30 10:56:45');
INSERT INTO `members` VALUES (16, '16', 'teacher', 'Fitriyah Hanim, S.Pd', '2024-11-30 10:56:45', '2024-11-30 10:56:45');
INSERT INTO `members` VALUES (17, '17', 'teacher', 'Nur Fadilah, s.Pd', '2024-11-30 10:56:45', '2024-11-30 10:56:45');
INSERT INTO `members` VALUES (18, '18', 'teacher', 'Dini Mayasusanti, S.Pd', '2024-11-30 10:56:45', '2024-11-30 10:56:45');
INSERT INTO `members` VALUES (19, '19', 'teacher', 'Husnul Khotimah', '2024-11-30 10:56:45', '2024-11-30 10:56:45');
INSERT INTO `members` VALUES (20, '20', 'teacher', 'Triana Septi Anifah', '2024-11-30 10:56:45', '2024-11-30 10:56:45');
INSERT INTO `members` VALUES (21, '21', 'teacher', 'Ifatin Nikmah, S.Pd', '2024-11-30 10:56:45', '2024-11-30 10:56:45');
INSERT INTO `members` VALUES (22, '22', 'teacher', 'Mei Nur Firdaus, S.S', '2024-11-30 10:56:45', '2024-11-30 10:56:45');
INSERT INTO `members` VALUES (23, '23', 'teacher', 'Masruroh', '2024-11-30 10:56:45', '2024-11-30 10:56:45');
INSERT INTO `members` VALUES (24, '24', 'teacher', 'Nur Sa\'diyah', '2024-11-30 10:56:45', '2024-11-30 10:56:45');
INSERT INTO `members` VALUES (25, '25', 'teacher', 'Faizatur Rohmah', '2024-11-30 10:56:45', '2024-11-30 10:56:45');
INSERT INTO `members` VALUES (26, '26', 'teacher', 'Anita Khoirina, S.Pd', '2024-11-30 10:56:45', '2024-11-30 10:56:45');
INSERT INTO `members` VALUES (27, '27', 'teacher', 'Dhiayu Choirun Nisak, S.Pd', '2024-11-30 10:56:45', '2024-11-30 10:56:45');
INSERT INTO `members` VALUES (28, '28', 'teacher', 'Qurrotul Azizah', '2024-11-30 10:56:45', '2024-11-30 10:56:45');
INSERT INTO `members` VALUES (29, '29', 'teacher', 'Ika Nur Istiqomah', '2024-11-30 10:56:45', '2024-11-30 10:56:45');
INSERT INTO `members` VALUES (30, '30', 'teacher', 'Aisya Zuhrufun Nisak, S.Psi', '2024-11-30 10:56:45', '2024-11-30 10:56:45');
INSERT INTO `members` VALUES (31, '31', 'teacher', 'Rizky Nurus Shobah', '2024-11-30 10:56:45', '2024-11-30 10:56:45');
INSERT INTO `members` VALUES (32, '32', 'teacher', 'Nadlifatul Faniyah', '2024-11-30 10:56:45', '2024-11-30 10:56:45');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 32 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (5, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (6, '2014_10_12_100000_create_password_reset_tokens_table', 1);
INSERT INTO `migrations` VALUES (7, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO `migrations` VALUES (8, '2019_12_14_000001_create_personal_access_tokens_table', 1);
INSERT INTO `migrations` VALUES (9, '2024_10_06_060110_create_products_table', 1);
INSERT INTO `migrations` VALUES (10, '2024_10_06_060831_create_product_variants_table', 1);
INSERT INTO `migrations` VALUES (11, '2024_10_06_060849_create_purchases_table', 1);
INSERT INTO `migrations` VALUES (12, '2024_10_06_060900_create_purchase_details_table', 1);
INSERT INTO `migrations` VALUES (13, '2024_10_06_060910_create_orders_table', 1);
INSERT INTO `migrations` VALUES (14, '2024_10_06_060919_create_order_details_table', 1);
INSERT INTO `migrations` VALUES (15, '2024_10_06_064140_add_user_id_to_purchases', 2);
INSERT INTO `migrations` VALUES (16, '2024_10_06_193807_add_name_to_product_variants', 3);
INSERT INTO `migrations` VALUES (17, '2024_10_09_082239_add_vendor_id_total_terbayar_to_purchases', 4);
INSERT INTO `migrations` VALUES (18, '2024_10_09_083444_add_total_terbayar_to_orders', 5);
INSERT INTO `migrations` VALUES (20, '2024_10_10_112439_create_vendors_table', 6);
INSERT INTO `migrations` VALUES (22, '2024_11_12_200411_create_ledgers_table', 7);
INSERT INTO `migrations` VALUES (23, '2024_11_17_171027_create_students_table', 8);
INSERT INTO `migrations` VALUES (24, '2024_11_19_090700_create_purchase_payments_table', 9);
INSERT INTO `migrations` VALUES (25, '2024_11_19_090742_create_order_payments_table', 9);
INSERT INTO `migrations` VALUES (26, '2024_11_19_125008_add_description_to_ledgers', 9);
INSERT INTO `migrations` VALUES (27, '2024_11_23_114334_create_members_table', 10);
INSERT INTO `migrations` VALUES (28, '2024_11_23_114712_create_receivables_members_table', 11);
INSERT INTO `migrations` VALUES (29, '2024_11_23_114750_create_receivables_member_payments_table', 11);
INSERT INTO `migrations` VALUES (30, '2024_11_26_135628_create_teachers_table', 11);
INSERT INTO `migrations` VALUES (31, '2024_12_06_103034_add_paid_at_to_receivables_member_payment', 12);

-- ----------------------------
-- Table structure for order_details
-- ----------------------------
DROP TABLE IF EXISTS `order_details`;
CREATE TABLE `order_details`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_variant_id` bigint NOT NULL,
  `qty` int NOT NULL,
  `subtotal` decimal(11, 2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of order_details
-- ----------------------------
INSERT INTO `order_details` VALUES (1, 'OR00001', 4, 2, 130000.00, '2024-11-28 22:12:41', '2024-11-28 22:12:41', NULL);

-- ----------------------------
-- Table structure for order_payments
-- ----------------------------
DROP TABLE IF EXISTS `order_payments`;
CREATE TABLE `order_payments`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint NOT NULL,
  `amount` decimal(15, 2) NOT NULL,
  `paid_at` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of order_payments
-- ----------------------------

-- ----------------------------
-- Table structure for orders
-- ----------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_id` bigint NULL DEFAULT NULL,
  `total` decimal(11, 2) NOT NULL,
  `terbayar` decimal(11, 2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of orders
-- ----------------------------
INSERT INTO `orders` VALUES (1, 'OR00001', 1, 130000.00, 100000.00, '2024-11-28 22:12:41', '2024-11-28 22:12:41', NULL, 1);

-- ----------------------------
-- Table structure for password_reset_tokens
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of password_reset_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for personal_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `personal_access_tokens_token_unique`(`token` ASC) USING BTREE,
  INDEX `personal_access_tokens_tokenable_type_tokenable_id_index`(`tokenable_type` ASC, `tokenable_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of personal_access_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for product_variants
-- ----------------------------
DROP TABLE IF EXISTS `product_variants`;
CREATE TABLE `product_variants`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `stock` int NOT NULL,
  `price` decimal(8, 2) NULL DEFAULT NULL COMMENT 'harga jual',
  `purchase_price` decimal(8, 2) NULL DEFAULT NULL COMMENT 'harga beli',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of product_variants
-- ----------------------------
INSERT INTO `product_variants` VALUES (1, 1, NULL, 10, 80000.00, 50000.00, '2024-11-22 09:37:02', '2024-11-28 22:11:06', NULL);
INSERT INTO `product_variants` VALUES (2, 2, NULL, 15, 40000.00, 30000.00, '2024-11-22 09:37:35', '2024-11-28 22:10:44', NULL);
INSERT INTO `product_variants` VALUES (4, 4, 'Kayu', 3, 65000.00, 50000.00, '2024-11-28 22:08:35', '2024-11-28 22:12:41', NULL);

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of products
-- ----------------------------
INSERT INTO `products` VALUES (1, 'tas', '2024-11-22 09:37:02', '2024-11-22 09:37:02', NULL);
INSERT INTO `products` VALUES (2, 'buku', '2024-11-22 09:37:35', '2024-11-22 09:37:35', NULL);
INSERT INTO `products` VALUES (4, 'APE', '2024-11-28 22:08:35', '2024-11-28 22:08:35', NULL);

-- ----------------------------
-- Table structure for purchase_details
-- ----------------------------
DROP TABLE IF EXISTS `purchase_details`;
CREATE TABLE `purchase_details`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_variant_id` bigint NOT NULL,
  `purchase_price` decimal(11, 2) NULL DEFAULT NULL COMMENT 'harga beli',
  `qty` int NOT NULL,
  `subtotal` decimal(11, 2) NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of purchase_details
-- ----------------------------
INSERT INTO `purchase_details` VALUES (1, 'PN00001', 1, 50000.00, 10, 500000.00, '2024-11-22 09:37:02', '2024-11-22 09:37:02', NULL);
INSERT INTO `purchase_details` VALUES (2, 'PN00002', 2, 30000.00, 15, 450000.00, '2024-11-22 09:37:35', '2024-11-22 09:37:35', NULL);
INSERT INTO `purchase_details` VALUES (3, 'PN00003', 4, 50000.00, 5, 250000.00, '2024-11-28 22:08:35', '2024-11-28 22:08:35', NULL);

-- ----------------------------
-- Table structure for purchase_payments
-- ----------------------------
DROP TABLE IF EXISTS `purchase_payments`;
CREATE TABLE `purchase_payments`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `purchase_id` bigint NOT NULL,
  `amount` decimal(15, 2) NOT NULL,
  `paid_at` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of purchase_payments
-- ----------------------------
INSERT INTO `purchase_payments` VALUES (1, 1, 500000.00, '1970-01-01', '2024-11-22 09:39:51', '2024-11-22 09:39:51');
INSERT INTO `purchase_payments` VALUES (2, 2, 450000.00, '1970-01-01', '2024-11-22 09:40:09', '2024-11-22 09:40:09');
INSERT INTO `purchase_payments` VALUES (3, 3, 250000.00, '2024-11-28', '2024-11-28 22:09:17', '2024-11-28 22:09:17');

-- ----------------------------
-- Table structure for purchases
-- ----------------------------
DROP TABLE IF EXISTS `purchases`;
CREATE TABLE `purchases`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint NOT NULL,
  `vendor_id` bigint NULL DEFAULT NULL,
  `total` decimal(11, 2) NULL DEFAULT NULL COMMENT 'harga beli',
  `terbayar` decimal(11, 2) NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of purchases
-- ----------------------------
INSERT INTO `purchases` VALUES (1, 'PN00001', 1, 1, 500000.00, 500000.00, '2024-11-22 09:37:02', '2024-11-22 09:39:51', NULL);
INSERT INTO `purchases` VALUES (2, 'PN00002', 1, 2, 450000.00, 450000.00, '2024-11-22 09:37:35', '2024-11-22 09:40:09', NULL);
INSERT INTO `purchases` VALUES (3, 'PN00003', 1, 4, 250000.00, 250000.00, '2024-11-28 22:08:35', '2024-11-28 22:09:17', NULL);

-- ----------------------------
-- Table structure for receivables_member_payments
-- ----------------------------
DROP TABLE IF EXISTS `receivables_member_payments`;
CREATE TABLE `receivables_member_payments`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `receivables_member_id` bigint NOT NULL,
  `amount` decimal(11, 2) NOT NULL,
  `paid_at` date NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of receivables_member_payments
-- ----------------------------
INSERT INTO `receivables_member_payments` VALUES (1, 1, 200000.00, '1970-01-01', '2024-12-06 10:33:02', '2024-12-06 10:33:02');

-- ----------------------------
-- Table structure for receivables_members
-- ----------------------------
DROP TABLE IF EXISTS `receivables_members`;
CREATE TABLE `receivables_members`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_id` bigint NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `total` decimal(11, 2) NOT NULL,
  `terbayar` decimal(11, 2) NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of receivables_members
-- ----------------------------
INSERT INTO `receivables_members` VALUES (1, 2, NULL, 2000000.00, 200000.00, 'BELUM LUNAS', '2024-12-03 11:37:34', '2024-12-06 10:33:02');

-- ----------------------------
-- Table structure for students
-- ----------------------------
DROP TABLE IF EXISTS `students`;
CREATE TABLE `students`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `no_induk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 273 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of students
-- ----------------------------
INSERT INTO `students` VALUES (1, '464', 'AKMALUDIN HAMZAH TRAFANI', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (2, '459', 'ALISYAH ALIFATUZ ZAHRA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (3, '455', 'BINTANG PUTRA WAHYU KURNIA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (4, '457', 'KAYYISAH HAWWA AZZAHRA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (5, '460', 'MAHARDIKA RAESSA ANDANA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (6, '461', 'MICHEL APRILIA AVANI', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (7, '463', 'MUHAMMAD HABLI HUKMA WA\'ILMA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (8, '458', 'MUHAMMAD ZAFRAN AL ARKHAN', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (9, '462', 'RAISSA ALYSSA ERABANI', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (10, '456', 'ULIN NUHA AHSANA TAFSIRO', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (11, '466', 'DWI ARYA MUHFIAN PUTRA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (12, '472', 'ELVANO ALFAREZI REYNDRA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (13, '473', 'ERLANGGA DENIZ PANCASENA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (14, '467', 'GAYUH KEYSA ZAKIA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (15, '470', 'NAURA ZHAFIRA RAMDHANI', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (16, '471', 'PRANAWA PRASETYA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (17, '136', 'PUTRI AYNA AZKAYRA AL GIRI', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (18, '465', 'QUEENSA FINTA ALVARISQI', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (19, '468', 'RAHMAD HANIF ALBIANSYAH', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (20, '474', 'SYAFIRA AULIA AZZAHRA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (21, '469', 'ZAIDAN AL FATIH IRSYAD', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (22, '483', 'AISYAH HABIBILLAH', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (23, '491', 'ARYASATYA ILYAS NURRACHMAN', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (24, '488', 'AYNA AL ALIFIZAHRA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (25, '490', 'GHINA SAYYIDAH DZAKIYAH', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (26, '489', 'KIANO AHMAD ALFARIZ', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (27, '484', 'MUHAMMAD ADZRIL HAIDAR', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (28, '496', 'MUHAMMAD ALTHAF ARBANI', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (29, '487', 'MUHAMMAD ARSA AL HAFIDZ', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (30, '480', 'MUHAMMAD RAFA NIZAM', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (31, '492', 'NADIA PUTRI LESTARI', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (32, '482', 'SALWANABILA CAHYANI PUTRI', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (33, '510', 'ABIDZAR AL AFNI PRINCE R.A', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (34, '504', 'ALVINO ARSYA AL AKBAR', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (35, '503', 'ANANTARA KHAIRAN RIZQI FIRMANSYAH', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (36, '508', 'AZKA ZYAN ASSEGAF', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (37, '501', 'AZZALEA SHAQUEENA ARINDYA LATIF', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (38, '505', 'DELISHA ADINDA SYAFANIA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (39, '502', 'EMIR DZAKY ZAKARIA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (40, '507', 'JIHAN TALITHA AZZAHRA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (41, '509', 'MAULANA HABIBI ABHIMATA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (42, '499', 'MUHAMMAD SULTAN NARESWARA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (43, '506', 'NUR LATIFAH AN NASYA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (44, '518', 'AQILA AZZAHRA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (45, '512', 'AYRA SANSA DEWAN SYA\'RONI', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (46, '514', 'ELLANO SHANKARA PRASETYO', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (47, '515', 'JABBAR FARAS CAKRAWHARDANA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (48, '516', 'M FARHAN RAFISQY ALFAREZI', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (49, '513', 'M FARREL GIBRAN PRATAMA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (50, '517', 'M IKHWAN ADAM MAULANA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (51, '511', 'NELVINO SHAQUILLE KRISDIANTO', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (52, '519', 'RYU SATRIA PUTRA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (53, '530', 'SAYYIDAH SYARIFAH KAMILA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (54, '529', 'SHAKEEL ABHIVANDYA ROFIQI', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (55, '539', 'ALFIIN ZULFIKAR RIZKI', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (56, '535', 'AMEERA MIKAYLA ZAHRA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (57, '541', 'ARSHAKA NATAN ADITYA AKBAR', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (58, '534', 'LINTANG ADREENA SHEZA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (59, '538', 'M ALVANO KENZIE PRATAMA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (60, '531', 'MUHAMMAD AKMAL ALFA RIZQI', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (61, '540', 'MUHAMMAD MIFZAL RAFIF ABQARY', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (62, '537', 'ORION DEWANGGA PUTRA AHMAD', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (63, '532', 'THALIE PIRANTHIE CAHYANING', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (64, '536', 'VIAN ARJUNA AT TSABIT', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (65, '533', 'WIRDA RIZQIANA LAILATUS SURUR', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (66, '427', 'GUSTI ABU BANGKIT ASTAMARUN .S', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (67, '428', 'KEANO DWI ALFARIZI', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (68, '426', 'KEENANDRA MALIK ARDY ALMUZA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (69, '424', 'KHANZA ADZKIYA KHALISA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (70, '434', 'MUHAMMAD ABIL SIDQI ARSALAN', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (71, '421', 'MUHAMMAD ADZRIEL ALFARIZQI', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (72, '422', 'MUHAMMAD AFIF FIRDAUS PRATAMA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (73, '432', 'MUHAMMAD HAZMI ALFARIZI', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (74, '433', 'RIZKI RAMADHAN PUTRA BASUKI', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (75, '431', 'RUMAISHA ARSA GAURI', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (76, '425', 'SAFANIYA GHEA ARISTA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (77, '423', 'SHANUM SHEZAN AZKAYRA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (78, '429', 'SHINTA AYULIA HARIANA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (79, '133', 'ABDULLAH ALKAHFI', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (80, '138', 'ANAGATA LEONORA SHANUM FIRMANSYAH', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (81, '131', 'DAFFA DZUHAIRI EVANO', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (82, '137', 'ERLANGGA NADEO NARENDRA BARRY', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (83, '140', 'GAMILA NADHIFA ZAIDA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (84, '142', 'GRIZELLA QUIENZHA PUTRI ZAKARIA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (85, '134', 'HAFIZH RAFFASYA AL FAHREZI ERDOGAN', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (86, '141', 'MARYAM HANA PRANATA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (87, '132', 'MUHAMMAD ABIZAR ZAYDAN AL FATTIH', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (88, '448', 'MUHAMMAD ARTA NABIL MAUZA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (89, '139', 'MUHAMMAD IRSYAD JAMALUDIN', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (90, '449', 'NAFIS RASENDRIYA FAHRUDDIN', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (91, '135', 'SABRINA AULIA ZAHRA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (92, '450', 'ALVINO NAUVAL MUSTOFA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (93, '153', 'AZIZAH NAYYIRA ALFIDA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (94, '148', 'ELLENA QIANZY RATU PRASETYO', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (95, '143', 'FATHIAN ZIDAN ALFAREZI PRAYOGO', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (96, '151', 'GAISHAN AHMED PRASETYO', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (97, '147', 'MIKAYLA AZIZ AZ ZAHRA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (98, '145', 'MUHAMMAD AQIL ZHAFRAN ASSYARIF', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (99, '146', 'MUHAMMAD ARKA SHAWQI FILARDHA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (100, '149', 'NADHIRA AYUNINDYA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (101, '144', 'NADIRA SHAFANA ALFARIZKY', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (102, '152', 'RAYYI SHABILLA HILYA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (103, '154', 'ZIDNA ILMAN NAFIAH', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (104, '127', 'ABDULLAH DANISH DHIAURRAHMAN', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (105, '125', 'ALEA SHANUM HUMAIRA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (106, '126', 'ANINDYA KIRANA NUR ICHWAN', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (107, '130', 'ARSYILA DELVIA NAURA AHMAD', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (108, '121', 'CIELO VANDARA NAZEEA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (109, '118', 'IZZAN HARITH FAROKH', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (110, '120', 'KAHEESHA RUBY NASYAUQI', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (111, '128', 'KALYLA CHELSEA NUR AINI', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (112, '122', 'MUHAMMAD FALIH AQMAR', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (113, '124', 'MUHAMMAD UWAIS AL FARIDZI', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (114, '123', 'RAFAEZA HADITAMA STYA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (115, '129', 'SAQUEENA RIZQI RAMADHANI', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (116, '119', 'YURFINA ALIFA HERDRIAH', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (117, '103', 'ACHMAD RIZKY ABI ASWIN', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (118, '109', 'ADIBA ATLTHAFUNNISA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (119, '107', 'AFRIN FAIHA CHISSY', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (120, '101', 'ALFARIZI MAHESA PUTRA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (121, '102', 'ARDIAZ MUHAMMAD FAUZI', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (122, '106', 'BERYLLI BRILIAN', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (123, '104', 'BILLQIS FAIHA RIFDA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (124, '105', 'HANUM CALISTA HANANIA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (125, '110', 'KAILASH RAFQI HASAN', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (126, '108', 'KINARA ALZARINA RAMADHANI', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (127, '411', 'MUHAMMAD FAQIHUL HAKIM', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (128, '111', 'SYATIR AZFAR AL FARZANA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (129, '112', 'WILDAN HAFIZH RAFISQI HARJUNO', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (130, '452', 'AISYAH PUTRI', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (131, '155', 'ALFATH SYARIEF', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (132, '165', 'ALISSA UFAIRASAKHI', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (133, '158', 'AZKA ANUGRAH FILARDHA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (134, '162', 'AZKA RANIA ZHAFIRA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (135, '451', 'HAIKAL AZRIL EL RUMI', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (136, '163', 'KHANZA SHAFEA AZZAHRA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (137, '159', 'M SULTAN OCTAVIANDI SUTRISNO', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (138, '166', 'MAZIYAH KAMILAH RAMADHANI', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (139, '157', 'MUHAMMAD ALFATAN LEONARDI', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (140, '156', 'MUHAMMAD ARSAKHA SHIDDIQ ASSHAUQI', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (141, '160', 'MUHAMMAD FAIZZUDIN ZYDAN PASHA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (142, '161', 'RAISYA ZAHRA SALSA BILLA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (143, '001007', 'ADILLA CAHYA KIRANA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (144, '001005', 'AL ZAVAIR YUFA REZVAN', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (145, '114', 'AZLAN RESCHA HIDAYAT', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (146, '001006', 'BENING ILLYA ALMAIS', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (147, '453', 'DEAN MERU DHAFIR', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (148, '113', 'KAIF HAIDAR HASAN', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (149, '001004', 'KAYLA RIZKI MAFAZA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (150, '001012', 'KHANZA NAYLA AZZAHRA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (151, '000991', 'KIREI ZEA SHANUM', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (152, '115', 'KRISNA NARENDRA UTAMA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (153, '116', 'MUHAMMAD ALBI AL FARIZI', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (154, '454', 'NAVAS AHMAD ASKARABIRU', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (155, '000914', 'QIRANIA NUHA AZ ZAHRA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (156, '445', 'ADHWA ARZAQILLAH', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (157, '446', 'ADINDA VIRLI AZZANIA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (158, '441', 'BRIGITA AULIA AZZAHRA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (159, '443', 'DAMAR ATHARAZKA AGASA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (160, '440', 'ESHAL GHANIA NAHDA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (161, '447', 'MUHAMMAD KEENAN DEVAN ARRASYA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (162, '436', 'MUHAMMAD SHOFAUL MUBARACK', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (163, '435', 'MUHAMMAD WAHYU IRSYAD', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (164, '442', 'MUHAMMAD YAHYA PITULUNGAN', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (165, '444', 'MUHAMMAD ZHIAN ALI RIDHO', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (166, '438', 'NALA LAILATUL AFROKHAH', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (167, '439', 'YUSUF HANIF AZZAKI', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (168, '437', 'ZICO ARSYA WIRATAMA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (169, '000901', 'AHMAD ALENDRA OEMAR ASY SYAZANI B.A', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (170, '000990', 'ANNISA INDRA SAPUTRI', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (171, '000993', 'AZKAYRA PUTRI ZASKIYA AZZAHRA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (172, '000995', 'DINARA EL RUNAKO', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (173, '000805', 'EMIL DZIKRI FISABILILLAH', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (174, '000992', 'MOANA AMORA SUBAGYO', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (175, '000899', 'MUBASSYIRA NADZIRA ASSYAIBANIY', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (176, '000917', 'MUHAMMAD BAGUS VAN CAHYA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (177, '177', 'MUHAMMAD FREY AL RASYA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (178, '000997', 'MUHAMMAD MAHER ALI HIMAM', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (179, '420', 'NADHIRA TUNGGA DEWI', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (180, '000994', 'SABIAN ALFARIZA SENARU', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (181, '000996', 'SHILNA SHAFIRA AZZAQIYAH', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (182, '001000', 'ARISHA ALMAHYRA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (183, '172', 'ARSYILA ADZANADYA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (184, '170', 'DIRANDRA RIFQI PRATAMA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (185, '168', 'EL AZKA RIZQI MAULANA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (186, '001010', 'FATHAR YUSUF RAFFASYA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (187, '001013', 'GENDHIS ELMYRA NUGROHO', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (188, '001002', 'JIHAN MAKAILAH FAKHIRA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (189, '171', 'MOCHAMMAD AL FATHAN ABRAHAM', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (190, '001011', 'MUHAMMAD IKMALI ABDILLAH', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (191, '001009', 'MUHAMMAD IRFAN ZIDNY', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (192, '167', 'NALENDRA TISYA DESKA PRATAMA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (193, '001003', 'NARENDRA PANENGGAK KISWORO', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (194, '173', 'SABIRA NUR RIZQI', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (195, '0001006', 'FIORENZA VIRA FELICIA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (196, '0001004', 'IBRAHIM KHALIL ALFATIH', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (197, '0001005', 'INATSA\'ALAINA NASHIRO', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (198, '0001002', 'KHAYLA PUTRI ZAHIRA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (199, '0001009', 'M ARHAB REYHAN ARDHANI', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (200, '0001003', 'MOCH. ZAYAN AHNAF', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (201, '0001001', 'MUHAMMAD AZIZUDDIN ZAHRONI', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (202, '500', 'MUHAMMAD ZAFRAN AL RAFIF', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (203, '0001000', 'MUHAMMAD ZEYHAN SHAUQILANO', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (204, '000905', 'NOUREEN SYIFA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (205, '0001008', 'RORO NAWANG AYU', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (206, '1221', 'TASYA ANINDYA ZHAFIRA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (207, '0001007', 'ZAHRA NUR APRILINA ANDRIANTO', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (208, '000930', 'ADHITAMA ELVAN SYAHREZA YAMAN', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (209, '000929', 'ALDEVARO RAFASYA RAQILLA LATIF', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (210, '000934', 'ALEEYA FRANSTASYA AZKARAYYA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (211, '000931', 'ALI MUSYAFAK AHMAD', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (212, '000925', 'ALIFIA NAUFALYN AZIZAH', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (213, '179', 'ALMEER FAHREZA AHMAD', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (214, '000927', 'ASFIA NISA ALTAFURROHA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (215, '000932', 'ASYIFA KAYYISA SHATAR', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (216, '000933', 'HIKMAH INARA AQILA I', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (217, '000928', 'LUCAS BRAMASTA DANISWARA WIBAWAN', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (218, '000924', 'MUHAMMAD KHALIF RIZQI FIRMANSYAH', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (219, '000923', 'RAJA RUNAKO AL SAUQHI', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (220, '000926', 'SAFFANA NASYA AL MAHYRA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (221, '000908', 'ADZKIYA INARA ARUMIPUTRI', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (222, '000942', 'AHMAD SYARIFUDIN HAMKA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (223, '000936', 'AISYAH FARHANA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (224, '000937', 'AURELLIA NAURA PRATISTA ARIFIYANTI', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (225, '000943', 'AZLAN ZAYDAN HIDAYAT', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (226, '000939', 'KHADIJAH PUTRI MARTHADITA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (227, '1220', 'M RAFI ILHAM KHOMARUDIN', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (228, '000946', 'MAHREEN SYAZANI BANAFSHA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (229, '000938', 'MUHAMMAD ABIYU KEVIN HIBRIZI', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (230, '000941', 'MUHAMMAD IQBAL MAULANA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (231, '000935', 'MUHAMMAD ZAIGHAM ARKHAN KHUSAINI', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (232, '000787', 'NADHIFAH KHADIJAH AZ ZAHRAH', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (233, '000944', 'NAURA HAFIZA RAKHMAN', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (234, '194', 'ADIPATI RESTU BUMI ANDRIYANTO', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (235, '1202', 'ASHALINA YUMNA NALADHIPA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (236, '199', 'AZKA BALYA RAMADHAN', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (237, '1210', 'GUSTI ARDANA MAHARDIKA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (238, '1203', 'KEYZHA NAYAKA KINANTI PUTRI', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (239, '1200', 'KHANZALA NAHWA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (240, '198', 'KHANZIA KHAIRINA ABIDAH', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (241, '1201', 'MUHAMMAD ADAM ALFATIH', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (242, '197', 'MUHAMMAD FADHILLAH MAULANA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (243, '1206', 'MUHAMMAD JAMALUDIN AL HUSAIN', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (244, '1204', 'MUHAMMAD KAFA AL KASYAFANI', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (245, '1205', 'MUHAMMAD NABIL HAZIMI', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (246, '193', 'MUHAMMAD SHOLAHUDIN AL AYYUBI', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (247, '188', 'ABRISAM SHARIQUE AL ZUKHRUF', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (248, '186', 'ACHMAD AL MUBAROQ', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (249, '185', 'AFFAN GIYATSA NUR RAMADHAN', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (250, '190', 'AMANDA ZELINA ZAKEISYHA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (251, '180', 'HUWAIDA SAFFANAH AZZUHRA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (252, '192', 'KINANTI AZKADINA ESMID', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (253, '189', 'MUHAMMAD ILMAN NAJMUDIN AZHARI', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (254, '191', 'NAURA AQILA JAHIDAH', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (255, '184', 'NAWANG WULANDARI', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (256, '182', 'RETHA FARADINA MECCA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (257, '187', 'ULFA ABQORIYYAH', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (258, '181', 'ZULMI IKHWAN KHOIRI ARIF', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (259, '176', 'AGAM KHALIF ALFARIZI', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (260, '000912', 'ANINDIA NAUFALYN AFIFAH', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (261, '000921', 'ANNISA ASYIFATUL ABIDIN', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (262, '174', 'AZKHA DILAN ALKHALIFI', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (263, '175', 'DZAKIR AHSAN MAULANA', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (264, '000915', 'FAZA NUR MUHAMMAD', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (265, '000920', 'KEISYA RIZKI ABQORI', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (266, '000922', 'MUHAMMAD ABRIZAM AL FATIH', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (267, '000918', 'MUHAMMAD LINTANG ALTHAFARIZQI HANSO', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (268, '000911', 'RAIQA AIMASHYRA', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (269, '000919', 'RAKA LUQMAN ARDIANSYAH', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (270, '000913', 'RASYA ELLENO SYAQUILLE', 'L', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (271, '000916', 'ZAHIDA AS SHOFA MARWAH', 'P', '2024-11-11 15:43:02', '2024-11-11 15:43:02', NULL);
INSERT INTO `students` VALUES (272, '0001', 'tes siswa edit', 'P', '2024-11-17 17:35:26', '2024-11-17 17:36:53', '2024-11-17 17:36:53');

-- ----------------------------
-- Table structure for teachers
-- ----------------------------
DROP TABLE IF EXISTS `teachers`;
CREATE TABLE `teachers`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 33 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of teachers
-- ----------------------------
INSERT INTO `teachers` VALUES (1, 'Abdul Rohim, S.PdI', NULL, NULL);
INSERT INTO `teachers` VALUES (2, 'Khoirul Izzah, S.Pd AUD', NULL, NULL);
INSERT INTO `teachers` VALUES (3, 'Miftahul Jannah, S.Pd', NULL, NULL);
INSERT INTO `teachers` VALUES (4, 'Fatimah Zahroh, S.Pd', NULL, NULL);
INSERT INTO `teachers` VALUES (5, 'Umami Faizah, SE, S.Pd', NULL, NULL);
INSERT INTO `teachers` VALUES (6, 'Siti Khomsiyah, S.Pd AUD', NULL, NULL);
INSERT INTO `teachers` VALUES (7, 'Iin Mayasari, S.Pd', NULL, NULL);
INSERT INTO `teachers` VALUES (8, 'Indah Susanti, S.Pd', NULL, NULL);
INSERT INTO `teachers` VALUES (9, 'Sri Wahyudati, S.Pd', NULL, NULL);
INSERT INTO `teachers` VALUES (10, 'Maratul Mufidah, S.Pd', NULL, NULL);
INSERT INTO `teachers` VALUES (11, 'Siti Zulaikhah, S.Pd', NULL, NULL);
INSERT INTO `teachers` VALUES (12, 'Khafidhotul Mushonnifah', NULL, NULL);
INSERT INTO `teachers` VALUES (13, 'Heni Khumaaidah, S.Pd', NULL, NULL);
INSERT INTO `teachers` VALUES (14, 'Choirul Ummah', NULL, NULL);
INSERT INTO `teachers` VALUES (15, 'Elis Masrikhah, S.Pd', NULL, NULL);
INSERT INTO `teachers` VALUES (16, 'Fitriyah Hanim, S.Pd', NULL, NULL);
INSERT INTO `teachers` VALUES (17, 'Nur Fadilah, s.Pd', NULL, NULL);
INSERT INTO `teachers` VALUES (18, 'Dini Mayasusanti, S.Pd', NULL, NULL);
INSERT INTO `teachers` VALUES (19, 'Husnul Khotimah', NULL, NULL);
INSERT INTO `teachers` VALUES (20, 'Triana Septi Anifah', NULL, NULL);
INSERT INTO `teachers` VALUES (21, 'Ifatin Nikmah, S.Pd', NULL, NULL);
INSERT INTO `teachers` VALUES (22, 'Mei Nur Firdaus, S.S', NULL, NULL);
INSERT INTO `teachers` VALUES (23, 'Masruroh', NULL, NULL);
INSERT INTO `teachers` VALUES (24, 'Nur Sa\'diyah', NULL, NULL);
INSERT INTO `teachers` VALUES (25, 'Faizatur Rohmah', NULL, NULL);
INSERT INTO `teachers` VALUES (26, 'Anita Khoirina, S.Pd', NULL, NULL);
INSERT INTO `teachers` VALUES (27, 'Dhiayu Choirun Nisak, S.Pd', NULL, NULL);
INSERT INTO `teachers` VALUES (28, 'Qurrotul Azizah', NULL, NULL);
INSERT INTO `teachers` VALUES (29, 'Ika Nur Istiqomah', NULL, NULL);
INSERT INTO `teachers` VALUES (30, 'Aisya Zuhrufun Nisak, S.Psi', NULL, NULL);
INSERT INTO `teachers` VALUES (31, 'Rizky Nurus Shobah', NULL, NULL);
INSERT INTO `teachers` VALUES (32, 'Nadlifatul Faniyah', NULL, NULL);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_username_unique`(`username` ASC) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'koperasi', 'koperasi', 'test@example.com', '2024-10-06 09:24:18', '$2y$12$E.pAnQAaDp3h5SXCAninEOb5bQ2EGvLmPczeumm.HWkPrDpXhEB.2', '5kqQ0A91blTtJvEE6WpMuLied2v4X0iX3TQoYEUJyVneVnEtbUeDYNKuxkiF', '2024-10-06 09:24:18', '2024-10-06 09:24:18', NULL);

-- ----------------------------
-- Table structure for vendors
-- ----------------------------
DROP TABLE IF EXISTS `vendors`;
CREATE TABLE `vendors`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of vendors
-- ----------------------------
INSERT INTO `vendors` VALUES (1, 'elfa', 'jl. teratai', '083484347', '2024-11-17 17:46:47', '2024-11-17 17:46:47', NULL);
INSERT INTO `vendors` VALUES (2, 'abiofset', 'ngelo', '08384364673', '2024-11-17 17:47:07', '2024-11-17 17:47:27', NULL);
INSERT INTO `vendors` VALUES (4, 'Sophi', 'online3', '949499', '2024-11-17 18:27:45', '2024-11-17 18:27:45', NULL);

SET FOREIGN_KEY_CHECKS = 1;
