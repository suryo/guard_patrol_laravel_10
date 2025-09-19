/*
 Navicat Premium Data Transfer

 Source Server         : local
 Source Server Type    : MySQL
 Source Server Version : 80030
 Source Host           : localhost:3306
 Source Schema         : db_patrol

 Target Server Type    : MySQL
 Target Server Version : 80030
 File Encoding         : 65001

 Date: 19/09/2025 09:39:35
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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 36 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '2014_10_12_100000_create_password_reset_tokens_table', 1);
INSERT INTO `migrations` VALUES (3, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO `migrations` VALUES (4, '2019_12_14_000001_create_personal_access_tokens_table', 1);
INSERT INTO `migrations` VALUES (5, '2025_08_30_041149_create_tb_checkpoint_table', 1);
INSERT INTO `migrations` VALUES (6, '2025_08_30_041149_create_tb_person_table', 1);
INSERT INTO `migrations` VALUES (7, '2025_08_30_041149_create_tb_users_table', 1);
INSERT INTO `migrations` VALUES (8, '2025_08_30_041150_create_tb_activity_table', 1);
INSERT INTO `migrations` VALUES (9, '2025_08_30_041150_create_tb_report_asli_table', 1);
INSERT INTO `migrations` VALUES (10, '2025_08_30_041150_create_tb_report_table', 1);
INSERT INTO `migrations` VALUES (11, '2025_08_30_041150_create_tb_schedule_table', 1);
INSERT INTO `migrations` VALUES (12, '2025_08_30_041151_create_tb_schedule_template_table', 1);
INSERT INTO `migrations` VALUES (13, '2025_08_30_041151_create_tb_task_table', 1);
INSERT INTO `migrations` VALUES (14, '2025_08_30_041151_create_tb_task_template_table', 1);
INSERT INTO `migrations` VALUES (15, '2025_08_30_041152_create_tb_logs_table', 1);
INSERT INTO `migrations` VALUES (16, '2025_08_30_041152_create_tb_person_mapping_table', 1);
INSERT INTO `migrations` VALUES (17, '2025_08_30_041152_create_tb_phase_table', 1);
INSERT INTO `migrations` VALUES (18, '2025_08_30_041152_create_tb_task_list_table', 1);
INSERT INTO `migrations` VALUES (19, '2025_08_30_041153_create_laporan_view', 1);
INSERT INTO `migrations` VALUES (20, '2025_09_10_021517_create_tb_groups_table', 1);
INSERT INTO `migrations` VALUES (21, '2025_09_10_021607_refactor_tb_schedule_to_tb_schedules', 1);
INSERT INTO `migrations` VALUES (22, '2025_09_10_021647_create_tb_schedule_group_table', 1);
INSERT INTO `migrations` VALUES (23, '2025_09_10_021702_refactor_tb_phase_to_tb_phases', 1);
INSERT INTO `migrations` VALUES (24, '2025_09_10_021710_refactor_tb_activity_to_tb_activities', 1);
INSERT INTO `migrations` VALUES (25, '2025_09_10_021734_create_tb_activity_task_table', 1);
INSERT INTO `migrations` VALUES (26, '2025_09_11_025209_create_tb_schedule_group_phase_table', 1);
INSERT INTO `migrations` VALUES (27, '2025_09_11_070407_create_tb_task_group_table', 1);
INSERT INTO `migrations` VALUES (28, '2025_09_11_070414_create_tb_task_group_detail_table', 1);
INSERT INTO `migrations` VALUES (29, '2025_09_12_022910_alter_tb_schedule_template', 1);
INSERT INTO `migrations` VALUES (30, '2025_09_12_023052_create_tb_schedule_template_detail', 1);
INSERT INTO `migrations` VALUES (31, '2025_09_12_025342_add_unique_index_to_template_id_on_tb_schedule_template', 1);
INSERT INTO `migrations` VALUES (32, '2025_09_12_025651_alter_template_id_length_on_tb_schedule_template', 1);
INSERT INTO `migrations` VALUES (33, '2025_09_12_074728_add_fks_to_tb_schedule_template_detail', 1);
INSERT INTO `migrations` VALUES (34, '2025_09_13_045109_create_tb_schedule_group_phase_activity', 1);
INSERT INTO `migrations` VALUES (35, '2025_09_13_053841_add_fks_to_tb_schedule_group_phase_activity', 1);
INSERT INTO `migrations` VALUES (36, '2025_09_16_064905_add_schedule_group_phase_uid_to_tb_schedule_group_phase_activity', 2);

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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of personal_access_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for tb_activities
-- ----------------------------
DROP TABLE IF EXISTS `tb_activities`;
CREATE TABLE `tb_activities`  (
  `uid` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `activityId` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phase_uid` int UNSIGNED NULL DEFAULT NULL,
  `personId` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `scheduleId` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `checkpointStart` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `checkpointEnd` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `activityStart` datetime NULL DEFAULT NULL,
  `activityEnd` datetime NULL DEFAULT NULL,
  `activityStatus` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `lastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`) USING BTREE,
  UNIQUE INDEX `tb_activity_activityid_unique`(`activityId` ASC) USING BTREE,
  INDEX `tb_activity_personid_scheduleid_index`(`personId` ASC, `scheduleId` ASC) USING BTREE,
  INDEX `tb_activities_phase_uid_activitystart_index`(`phase_uid` ASC, `activityStart` ASC) USING BTREE,
  CONSTRAINT `tb_activities_phase_uid_foreign` FOREIGN KEY (`phase_uid`) REFERENCES `tb_phases` (`uid`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_activities
-- ----------------------------

-- ----------------------------
-- Table structure for tb_activity_task
-- ----------------------------
DROP TABLE IF EXISTS `tb_activity_task`;
CREATE TABLE `tb_activity_task`  (
  `uid` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `activity_uid` int UNSIGNED NOT NULL,
  `task_uid` int UNSIGNED NOT NULL,
  `is_done` tinyint(1) NOT NULL DEFAULT 0,
  `checked_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`uid`) USING BTREE,
  UNIQUE INDEX `tb_activity_task_activity_uid_task_uid_unique`(`activity_uid` ASC, `task_uid` ASC) USING BTREE,
  INDEX `tb_activity_task_task_uid_foreign`(`task_uid` ASC) USING BTREE,
  CONSTRAINT `tb_activity_task_activity_uid_foreign` FOREIGN KEY (`activity_uid`) REFERENCES `tb_activities` (`uid`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `tb_activity_task_task_uid_foreign` FOREIGN KEY (`task_uid`) REFERENCES `tb_task` (`uid`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_activity_task
-- ----------------------------

-- ----------------------------
-- Table structure for tb_checkpoint
-- ----------------------------
DROP TABLE IF EXISTS `tb_checkpoint`;
CREATE TABLE `tb_checkpoint`  (
  `uid` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `checkpointId` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `checkpointName` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` decimal(10, 6) NULL DEFAULT NULL,
  `longitude` decimal(10, 6) NULL DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `lastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`) USING BTREE,
  UNIQUE INDEX `tb_checkpoint_checkpointid_unique`(`checkpointId` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_checkpoint
-- ----------------------------

-- ----------------------------
-- Table structure for tb_groups
-- ----------------------------
DROP TABLE IF EXISTS `tb_groups`;
CREATE TABLE `tb_groups`  (
  `uid` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `groupId` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `groupName` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `timeStart` time NULL DEFAULT NULL,
  `timeEnd` time NULL DEFAULT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `lastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`) USING BTREE,
  UNIQUE INDEX `tb_groups_groupid_unique`(`groupId` ASC) USING BTREE,
  INDEX `tb_groups_timestart_timeend_index`(`timeStart` ASC, `timeEnd` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 25 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_groups
-- ----------------------------
INSERT INTO `tb_groups` VALUES (1, 'GRP-00', 'Group [00:00 - 00:59]', '00:00:00', '00:59:00', NULL, '2025-09-15 03:54:31');
INSERT INTO `tb_groups` VALUES (2, 'GRP-01', 'Group [01:00 - 01:59]', '01:00:00', '01:59:00', NULL, '2025-09-15 03:54:31');
INSERT INTO `tb_groups` VALUES (3, 'GRP-02', 'Group [02:00 - 02:59]', '02:00:00', '02:59:00', NULL, '2025-09-15 03:54:31');
INSERT INTO `tb_groups` VALUES (4, 'GRP-03', 'Group [03:00 - 03:59]', '03:00:00', '03:59:00', NULL, '2025-09-15 03:54:31');
INSERT INTO `tb_groups` VALUES (5, 'GRP-04', 'Group [04:00 - 04:59]', '04:00:00', '04:59:00', NULL, '2025-09-15 03:54:31');
INSERT INTO `tb_groups` VALUES (6, 'GRP-05', 'Group [05:00 - 05:59]', '05:00:00', '05:59:00', NULL, '2025-09-15 03:54:31');
INSERT INTO `tb_groups` VALUES (7, 'GRP-06', 'Group [06:00 - 06:59]', '06:00:00', '06:59:00', NULL, '2025-09-15 03:54:31');
INSERT INTO `tb_groups` VALUES (8, 'GRP-07', 'Group [07:00 - 07:59]', '07:00:00', '07:59:00', NULL, '2025-09-15 03:54:31');
INSERT INTO `tb_groups` VALUES (9, 'GRP-08', 'Group [08:00 - 08:59]', '08:00:00', '08:59:00', NULL, '2025-09-15 03:54:31');
INSERT INTO `tb_groups` VALUES (10, 'GRP-09', 'Group [09:00 - 09:59]', '09:00:00', '09:59:00', NULL, '2025-09-15 03:54:31');
INSERT INTO `tb_groups` VALUES (11, 'GRP-10', 'Group [10:00 - 10:59]', '10:00:00', '10:59:00', NULL, '2025-09-15 03:54:31');
INSERT INTO `tb_groups` VALUES (12, 'GRP-11', 'Group [11:00 - 11:59]', '11:00:00', '11:59:00', NULL, '2025-09-15 03:54:31');
INSERT INTO `tb_groups` VALUES (13, 'GRP-12', 'Group [12:00 - 12:59]', '12:00:00', '12:59:00', NULL, '2025-09-15 03:54:31');
INSERT INTO `tb_groups` VALUES (14, 'GRP-13', 'Group [13:00 - 13:59]', '13:00:00', '13:59:00', NULL, '2025-09-15 03:54:31');
INSERT INTO `tb_groups` VALUES (15, 'GRP-14', 'Group [14:00 - 14:59]', '14:00:00', '14:59:00', NULL, '2025-09-15 03:54:31');
INSERT INTO `tb_groups` VALUES (16, 'GRP-15', 'Group [15:00 - 15:59]', '15:00:00', '15:59:00', NULL, '2025-09-15 03:54:31');
INSERT INTO `tb_groups` VALUES (17, 'GRP-16', 'Group [16:00 - 16:59]', '16:00:00', '16:59:00', NULL, '2025-09-15 03:54:31');
INSERT INTO `tb_groups` VALUES (18, 'GRP-17', 'Group [17:00 - 17:59]', '17:00:00', '17:59:00', NULL, '2025-09-15 03:54:31');
INSERT INTO `tb_groups` VALUES (19, 'GRP-18', 'Group [18:00 - 18:59]', '18:00:00', '18:59:00', NULL, '2025-09-15 03:54:31');
INSERT INTO `tb_groups` VALUES (20, 'GRP-19', 'Group [19:00 - 19:59]', '19:00:00', '19:59:00', NULL, '2025-09-15 03:54:31');
INSERT INTO `tb_groups` VALUES (21, 'GRP-20', 'Group [20:00 - 20:59]', '20:00:00', '20:59:00', NULL, '2025-09-15 03:54:31');
INSERT INTO `tb_groups` VALUES (22, 'GRP-21', 'Group [21:00 - 21:59]', '21:00:00', '21:59:00', NULL, '2025-09-15 03:54:31');
INSERT INTO `tb_groups` VALUES (23, 'GRP-22', 'Group [22:00 - 22:59]', '22:00:00', '22:59:00', NULL, '2025-09-15 03:54:31');
INSERT INTO `tb_groups` VALUES (24, 'GRP-23', 'Group [23:00 - 23:59]', '23:00:00', '23:59:00', NULL, '2025-09-15 03:54:31');

-- ----------------------------
-- Table structure for tb_logs
-- ----------------------------
DROP TABLE IF EXISTS `tb_logs`;
CREATE TABLE `tb_logs`  (
  `uid` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `activity` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `category` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `userName` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `lastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_logs
-- ----------------------------

-- ----------------------------
-- Table structure for tb_person
-- ----------------------------
DROP TABLE IF EXISTS `tb_person`;
CREATE TABLE `tb_person`  (
  `uid` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `personId` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `personName` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `userName` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `isDeleted` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `lastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`) USING BTREE,
  UNIQUE INDEX `tb_person_personid_unique`(`personId` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_person
-- ----------------------------

-- ----------------------------
-- Table structure for tb_person_mapping
-- ----------------------------
DROP TABLE IF EXISTS `tb_person_mapping`;
CREATE TABLE `tb_person_mapping`  (
  `uid` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `mappingId` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mappingTag` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `mappingName` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `userName` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `lastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`) USING BTREE,
  UNIQUE INDEX `tb_person_mapping_mappingid_unique`(`mappingId` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_person_mapping
-- ----------------------------

-- ----------------------------
-- Table structure for tb_phases
-- ----------------------------
DROP TABLE IF EXISTS `tb_phases`;
CREATE TABLE `tb_phases`  (
  `uid` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `phaseId` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `schedule_group_uid` bigint UNSIGNED NULL DEFAULT NULL,
  `phaseDate` date NULL DEFAULT NULL,
  `lastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `phaseName` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `phaseOrder` smallint UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`uid`) USING BTREE,
  UNIQUE INDEX `tb_phase_phaseid_unique`(`phaseId` ASC) USING BTREE,
  INDEX `tb_phases_schedule_group_uid_phasedate_index`(`schedule_group_uid` ASC, `phaseDate` ASC) USING BTREE,
  CONSTRAINT `tb_phases_schedule_group_uid_foreign` FOREIGN KEY (`schedule_group_uid`) REFERENCES `tb_schedule_group` (`uid`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_phases
-- ----------------------------
INSERT INTO `tb_phases` VALUES (11, 'PHASE-01', NULL, '2025-09-15', '2025-09-15 04:00:04', 'Phase 1', 1);
INSERT INTO `tb_phases` VALUES (12, 'PHASE-02', NULL, '2025-09-15', '2025-09-15 04:00:04', 'Phase 2', 2);
INSERT INTO `tb_phases` VALUES (13, 'PHASE-03', NULL, '2025-09-15', '2025-09-15 04:00:04', 'Phase 3', 3);
INSERT INTO `tb_phases` VALUES (14, 'PHASE-04', NULL, '2025-09-15', '2025-09-15 04:00:04', 'Phase 4', 4);
INSERT INTO `tb_phases` VALUES (15, 'PHASE-05', NULL, '2025-09-15', '2025-09-15 04:00:04', 'Phase 5', 5);
INSERT INTO `tb_phases` VALUES (16, 'PHASE-06', NULL, '2025-09-15', '2025-09-15 04:00:04', 'Phase 6', 6);
INSERT INTO `tb_phases` VALUES (17, 'PHASE-07', NULL, '2025-09-15', '2025-09-15 04:00:04', 'Phase 7', 7);
INSERT INTO `tb_phases` VALUES (18, 'PHASE-08', NULL, '2025-09-15', '2025-09-15 04:00:04', 'Phase 8', 8);
INSERT INTO `tb_phases` VALUES (19, 'PHASE-09', NULL, '2025-09-15', '2025-09-15 04:00:04', 'Phase 9', 9);
INSERT INTO `tb_phases` VALUES (20, 'PHASE-10', NULL, '2025-09-15', '2025-09-15 04:00:04', 'Phase 10', 10);

-- ----------------------------
-- Table structure for tb_report
-- ----------------------------
DROP TABLE IF EXISTS `tb_report`;
CREATE TABLE `tb_report`  (
  `uid` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `reportId` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reportLatitude` decimal(10, 6) NULL DEFAULT NULL,
  `reportLongitude` decimal(10, 6) NULL DEFAULT NULL,
  `activityId` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `personId` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `checkpointName` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `reportNote` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `reportDate` date NULL DEFAULT NULL,
  `reportTime` time NULL DEFAULT NULL,
  `lastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`) USING BTREE,
  UNIQUE INDEX `tb_report_reportid_unique`(`reportId` ASC) USING BTREE,
  INDEX `tb_report_personid_reportdate_index`(`personId` ASC, `reportDate` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_report
-- ----------------------------

-- ----------------------------
-- Table structure for tb_report_asli
-- ----------------------------
DROP TABLE IF EXISTS `tb_report_asli`;
CREATE TABLE `tb_report_asli`  (
  `uid` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `reportId` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reportLatitude` decimal(10, 6) NULL DEFAULT NULL,
  `reportLongitude` decimal(10, 6) NULL DEFAULT NULL,
  `activityId` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `personId` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `checkpointName` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `reportNote` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `reportDate` date NULL DEFAULT NULL,
  `reportTime` time NULL DEFAULT NULL,
  `lastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`) USING BTREE,
  UNIQUE INDEX `tb_report_asli_reportid_unique`(`reportId` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_report_asli
-- ----------------------------

-- ----------------------------
-- Table structure for tb_schedule_group
-- ----------------------------
DROP TABLE IF EXISTS `tb_schedule_group`;
CREATE TABLE `tb_schedule_group`  (
  `uid` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `schedule_uid` int UNSIGNED NOT NULL,
  `group_uid` bigint UNSIGNED NOT NULL,
  `timeStart` time NULL DEFAULT NULL,
  `timeEnd` time NULL DEFAULT NULL,
  `sortOrder` smallint UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`uid`) USING BTREE,
  UNIQUE INDEX `tb_schedule_group_schedule_uid_group_uid_unique`(`schedule_uid` ASC, `group_uid` ASC) USING BTREE,
  INDEX `tb_schedule_group_group_uid_foreign`(`group_uid` ASC) USING BTREE,
  CONSTRAINT `tb_schedule_group_group_uid_foreign` FOREIGN KEY (`group_uid`) REFERENCES `tb_groups` (`uid`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `tb_schedule_group_schedule_uid_foreign` FOREIGN KEY (`schedule_uid`) REFERENCES `tb_schedules` (`uid`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_schedule_group
-- ----------------------------
INSERT INTO `tb_schedule_group` VALUES (1, 1, 1, '00:00:00', '00:59:00', 1, '2025-09-15 04:02:22', '2025-09-15 04:02:22');
INSERT INTO `tb_schedule_group` VALUES (2, 1, 2, '01:00:00', '01:59:00', 2, '2025-09-15 04:02:22', '2025-09-15 04:02:22');
INSERT INTO `tb_schedule_group` VALUES (3, 2, 1, '00:00:00', '00:59:00', 1, '2025-09-16 01:57:06', '2025-09-16 01:57:06');
INSERT INTO `tb_schedule_group` VALUES (4, 2, 2, '01:00:00', '01:59:00', 2, '2025-09-16 01:57:06', '2025-09-16 01:57:06');
INSERT INTO `tb_schedule_group` VALUES (5, 2, 3, '02:00:00', '02:59:00', 3, '2025-09-16 01:57:07', '2025-09-16 01:57:07');
INSERT INTO `tb_schedule_group` VALUES (6, 3, 1, '00:00:00', '00:59:00', 1, '2025-09-19 01:50:50', '2025-09-19 01:50:50');
INSERT INTO `tb_schedule_group` VALUES (7, 3, 2, '01:00:00', '01:59:00', 2, '2025-09-19 01:50:50', '2025-09-19 01:50:50');

-- ----------------------------
-- Table structure for tb_schedule_group_phase
-- ----------------------------
DROP TABLE IF EXISTS `tb_schedule_group_phase`;
CREATE TABLE `tb_schedule_group_phase`  (
  `uid` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `schedule_group_uid` bigint NOT NULL,
  `phase_uid` int NOT NULL,
  `phaseDate` date NOT NULL,
  `sortOrder` smallint NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`uid`) USING BTREE,
  INDEX `tb_schedule_group_phase_schedule_group_uid_index`(`schedule_group_uid` ASC) USING BTREE,
  INDEX `tb_schedule_group_phase_phase_uid_index`(`phase_uid` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_schedule_group_phase
-- ----------------------------
INSERT INTO `tb_schedule_group_phase` VALUES (1, 1, 11, '2025-09-15', 1, '2025-09-15 04:02:31', '2025-09-15 04:02:31');
INSERT INTO `tb_schedule_group_phase` VALUES (2, 1, 12, '2025-09-15', 2, '2025-09-15 04:02:38', '2025-09-15 04:02:38');
INSERT INTO `tb_schedule_group_phase` VALUES (3, 3, 11, '2025-09-16', 1, '2025-09-16 01:57:16', '2025-09-16 01:57:16');
INSERT INTO `tb_schedule_group_phase` VALUES (4, 4, 11, '2025-09-16', 1, '2025-09-16 01:57:25', '2025-09-16 01:57:25');
INSERT INTO `tb_schedule_group_phase` VALUES (5, 5, 11, '2025-09-16', 1, '2025-09-16 01:57:32', '2025-09-16 01:57:32');

-- ----------------------------
-- Table structure for tb_schedule_group_phase_activity
-- ----------------------------
DROP TABLE IF EXISTS `tb_schedule_group_phase_activity`;
CREATE TABLE `tb_schedule_group_phase_activity`  (
  `uid` int NOT NULL AUTO_INCREMENT,
  `schedule_group_phase_uid` bigint UNSIGNED NULL DEFAULT NULL,
  `phase_uid` int UNSIGNED NOT NULL,
  `task_group_uid` int UNSIGNED NOT NULL,
  `task_group_detail_uid` int UNSIGNED NOT NULL,
  `task_uid` int UNSIGNED NULL DEFAULT NULL,
  `sortOrder` smallint NULL DEFAULT NULL,
  `activityNote` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `userName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`uid`) USING BTREE,
  UNIQUE INDEX `uniq_phase_detail`(`schedule_group_phase_uid` ASC, `phase_uid` ASC, `task_group_uid` ASC, `task_group_detail_uid` ASC) USING BTREE,
  INDEX `tb_schedule_group_phase_activity_phase_uid_index`(`phase_uid` ASC) USING BTREE,
  INDEX `tb_schedule_group_phase_activity_task_group_uid_index`(`task_group_uid` ASC) USING BTREE,
  INDEX `tb_schedule_group_phase_activity_task_group_detail_uid_index`(`task_group_detail_uid` ASC) USING BTREE,
  INDEX `tb_schedule_group_phase_activity_task_uid_index`(`task_uid` ASC) USING BTREE,
  INDEX `idx_phase_uid`(`phase_uid` ASC) USING BTREE,
  INDEX `idx_group_uid`(`task_group_uid` ASC) USING BTREE,
  INDEX `idx_detail_uid`(`task_group_detail_uid` ASC) USING BTREE,
  INDEX `idx_task_uid`(`task_uid` ASC) USING BTREE,
  CONSTRAINT `fk_phase_act_group` FOREIGN KEY (`task_group_uid`) REFERENCES `tb_task_group` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_phase_act_group_detail` FOREIGN KEY (`task_group_detail_uid`) REFERENCES `tb_task_group_detail` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_phase_act_phase` FOREIGN KEY (`phase_uid`) REFERENCES `tb_phases` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_phase_act_task` FOREIGN KEY (`task_uid`) REFERENCES `tb_task` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 56 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_schedule_group_phase_activity
-- ----------------------------
INSERT INTO `tb_schedule_group_phase_activity` VALUES (45, 3, 11, 1, 1, 2, 1, NULL, 'system', '2025-09-17 03:11:38', '2025-09-17 03:11:38');
INSERT INTO `tb_schedule_group_phase_activity` VALUES (46, 3, 11, 1, 2, 1, 2, NULL, 'system', '2025-09-17 03:11:38', '2025-09-17 03:11:38');
INSERT INTO `tb_schedule_group_phase_activity` VALUES (49, 4, 11, 1, 1, 2, 1, NULL, 'system', '2025-09-17 03:12:11', '2025-09-17 03:12:11');
INSERT INTO `tb_schedule_group_phase_activity` VALUES (50, 4, 11, 1, 2, 1, 2, NULL, 'system', '2025-09-17 03:12:11', '2025-09-17 03:12:11');
INSERT INTO `tb_schedule_group_phase_activity` VALUES (51, 5, 11, 1, 1, 2, 1, NULL, 'system', '2025-09-17 03:12:21', '2025-09-17 03:12:21');
INSERT INTO `tb_schedule_group_phase_activity` VALUES (52, 5, 11, 1, 2, 1, 2, NULL, 'system', '2025-09-17 03:12:21', '2025-09-17 03:12:21');
INSERT INTO `tb_schedule_group_phase_activity` VALUES (55, 5, 11, 2, 3, 1, 1, NULL, 'system', '2025-09-17 04:33:14', '2025-09-17 04:33:14');

-- ----------------------------
-- Table structure for tb_schedule_template
-- ----------------------------
DROP TABLE IF EXISTS `tb_schedule_template`;
CREATE TABLE `tb_schedule_template`  (
  `uid` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `templateId` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `templateName` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `personId` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `timeStart` datetime NULL DEFAULT NULL,
  `timeEnd` datetime NULL DEFAULT NULL,
  `userName` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `lastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`) USING BTREE,
  UNIQUE INDEX `tb_schedule_template_templateid_unique`(`templateId` ASC) USING BTREE,
  UNIQUE INDEX `uq_tb_schedule_template_templateId`(`templateId` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_schedule_template
-- ----------------------------

-- ----------------------------
-- Table structure for tb_schedule_template_detail
-- ----------------------------
DROP TABLE IF EXISTS `tb_schedule_template_detail`;
CREATE TABLE `tb_schedule_template_detail`  (
  `uid` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `template_uid` int UNSIGNED NOT NULL,
  `task_group_detail_uid` int UNSIGNED NOT NULL,
  `sortOrder` smallint NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`uid`) USING BTREE,
  UNIQUE INDEX `uq_template_taskdetail`(`template_uid` ASC, `task_group_detail_uid` ASC) USING BTREE,
  INDEX `tb_schedule_template_detail_template_uid_index`(`template_uid` ASC) USING BTREE,
  INDEX `tb_schedule_template_detail_task_group_detail_uid_index`(`task_group_detail_uid` ASC) USING BTREE,
  INDEX `idx_tpl_uid`(`template_uid` ASC) USING BTREE,
  INDEX `idx_task_detail_uid`(`task_group_detail_uid` ASC) USING BTREE,
  CONSTRAINT `fk_tpl_detail_taskdetail` FOREIGN KEY (`task_group_detail_uid`) REFERENCES `tb_task_group_detail` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_tpl_detail_template` FOREIGN KEY (`template_uid`) REFERENCES `tb_schedule_template` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_schedule_template_detail
-- ----------------------------

-- ----------------------------
-- Table structure for tb_schedules
-- ----------------------------
DROP TABLE IF EXISTS `tb_schedules`;
CREATE TABLE `tb_schedules`  (
  `uid` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `scheduleId` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `personId` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `scheduleName` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `scheduleStart` datetime NULL DEFAULT NULL,
  `scheduleEnd` datetime NULL DEFAULT NULL,
  `scheduleDate` date NULL DEFAULT NULL,
  `userName` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `lastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`) USING BTREE,
  UNIQUE INDEX `tb_schedule_scheduleid_unique`(`scheduleId` ASC) USING BTREE,
  INDEX `tb_schedule_personid_scheduledate_index`(`personId` ASC, `scheduleDate` ASC) USING BTREE,
  INDEX `tb_schedules_scheduledate_index`(`scheduleDate` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_schedules
-- ----------------------------
INSERT INTO `tb_schedules` VALUES (1, 'SCH-2025-09-15-MD2B', '00', NULL, '2025-09-15 00:00:00', '2025-09-15 23:59:00', '2025-09-15', 'system', '2025-09-15 04:02:22');
INSERT INTO `tb_schedules` VALUES (2, 'SCH-2025-09-16-GBGG', '00', NULL, '2025-09-16 00:00:00', '2025-09-16 23:59:00', '2025-09-16', 'system', '2025-09-16 01:57:07');
INSERT INTO `tb_schedules` VALUES (3, 'SCH-2025-09-17-HIXK', '00', NULL, '2025-09-17 00:00:00', '2025-09-17 23:59:00', '2025-09-17', 'system', '2025-09-19 01:50:50');

-- ----------------------------
-- Table structure for tb_task
-- ----------------------------
DROP TABLE IF EXISTS `tb_task`;
CREATE TABLE `tb_task`  (
  `uid` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `taskId` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `taskName` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `taskNote` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `userName` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `lastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`) USING BTREE,
  UNIQUE INDEX `tb_task_taskid_unique`(`taskId` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_task
-- ----------------------------
INSERT INTO `tb_task` VALUES (1, '1', 'mematikan keran air taman', NULL, NULL, '2025-09-16 01:58:09');
INSERT INTO `tb_task` VALUES (2, '2', 'mematikan lampu', NULL, NULL, '2025-09-16 01:58:23');

-- ----------------------------
-- Table structure for tb_task_group
-- ----------------------------
DROP TABLE IF EXISTS `tb_task_group`;
CREATE TABLE `tb_task_group`  (
  `uid` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `groupId` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `groupName` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `groupNote` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `userName` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `lastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`) USING BTREE,
  UNIQUE INDEX `tb_task_group_groupid_unique`(`groupId` ASC) USING BTREE,
  INDEX `tb_task_group_groupname_index`(`groupName` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_task_group
-- ----------------------------
INSERT INTO `tb_task_group` VALUES (1, 'tg-01', 'Pos 1', NULL, NULL, '2025-09-16 01:59:25');
INSERT INTO `tb_task_group` VALUES (2, 'tg-02', 'Pos 2', NULL, NULL, '2025-09-16 01:59:37');

-- ----------------------------
-- Table structure for tb_task_group_detail
-- ----------------------------
DROP TABLE IF EXISTS `tb_task_group_detail`;
CREATE TABLE `tb_task_group_detail`  (
  `uid` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `group_uid` int UNSIGNED NOT NULL,
  `task_uid` int UNSIGNED NOT NULL,
  `sortOrder` smallint NOT NULL DEFAULT 0,
  `userName` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `lastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`) USING BTREE,
  UNIQUE INDEX `tb_task_group_detail_group_uid_task_uid_unique`(`group_uid` ASC, `task_uid` ASC) USING BTREE,
  INDEX `tb_task_group_detail_group_uid_sortorder_index`(`group_uid` ASC, `sortOrder` ASC) USING BTREE,
  INDEX `tb_task_group_detail_task_uid_index`(`task_uid` ASC) USING BTREE,
  CONSTRAINT `tb_task_group_detail_group_uid_foreign` FOREIGN KEY (`group_uid`) REFERENCES `tb_task_group` (`uid`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `tb_task_group_detail_task_uid_foreign` FOREIGN KEY (`task_uid`) REFERENCES `tb_task` (`uid`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_task_group_detail
-- ----------------------------
INSERT INTO `tb_task_group_detail` VALUES (1, 1, 2, 1, NULL, '2025-09-16 01:59:25');
INSERT INTO `tb_task_group_detail` VALUES (2, 1, 1, 2, NULL, '2025-09-16 01:59:25');
INSERT INTO `tb_task_group_detail` VALUES (3, 2, 1, 1, NULL, '2025-09-16 01:59:34');

-- ----------------------------
-- Table structure for tb_task_list
-- ----------------------------
DROP TABLE IF EXISTS `tb_task_list`;
CREATE TABLE `tb_task_list`  (
  `uid` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `taskId` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `scheduleId` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phaseId` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `taskStatus` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `userName` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `lastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`) USING BTREE,
  INDEX `tb_task_list_taskid_scheduleid_index`(`taskId` ASC, `scheduleId` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_task_list
-- ----------------------------

-- ----------------------------
-- Table structure for tb_task_template
-- ----------------------------
DROP TABLE IF EXISTS `tb_task_template`;
CREATE TABLE `tb_task_template`  (
  `uid` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `taskId` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `taskName` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `taskNote` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `lastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`) USING BTREE,
  INDEX `tb_task_template_taskid_index`(`taskId` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_task_template
-- ----------------------------

-- ----------------------------
-- Table structure for tb_users
-- ----------------------------
DROP TABLE IF EXISTS `tb_users`;
CREATE TABLE `tb_users`  (
  `uid` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `userId` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `userName` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `userPassword` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `userLevel` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashMobile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `hashWeb` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `userEmail` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `lastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`) USING BTREE,
  UNIQUE INDEX `tb_users_userid_unique`(`userId` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_users
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------

-- ----------------------------
-- View structure for laporan
-- ----------------------------
DROP VIEW IF EXISTS `laporan`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `laporan` AS select `r`.`reportDate` AS `reportDate`,`r`.`lastUpdated` AS `lastUpdated`,`p`.`personName` AS `personName`,`s`.`scheduleId` AS `scheduleId`,`s`.`checkpointName` AS `checkpointName`,`s`.`scheduleDate` AS `scheduleDate`,`s`.`scheduleStart` AS `scheduleStart`,`s`.`scheduleEnd` AS `scheduleEnd`,`a`.`checkpointStart` AS `checkpointStart`,`a`.`checkpointEnd` AS `checkpointEnd`,`a`.`activityStart` AS `activityStart`,`a`.`activityEnd` AS `activityEnd` from (((`tb_report` `r` left join `tb_person` `p` on((`p`.`personId` = `r`.`personId`))) left join `tb_activity` `a` on((`a`.`activityId` = `r`.`activityId`))) left join `tb_schedule` `s` on((`s`.`scheduleId` = `a`.`scheduleId`)));

SET FOREIGN_KEY_CHECKS = 1;
