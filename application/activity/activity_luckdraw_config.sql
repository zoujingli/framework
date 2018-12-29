/*
 Navicat Premium Data Transfer

 Source Server         : server.cuci.cc
 Source Server Type    : MySQL
 Source Server Version : 50562
 Source Host           : server.cuci.cc:3306
 Source Schema         : framework

 Target Server Type    : MySQL
 Target Server Version : 50562
 File Encoding         : 65001

 Date: 29/12/2018 16:46:05
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for activity_luckdraw_config
-- ----------------------------
DROP TABLE IF EXISTS `activity_luckdraw_config`;
CREATE TABLE `activity_luckdraw_config`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '活动代码',
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '活动名称',
  `logo` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '活动图标',
  `content` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '活动内容',
  `date_start` datetime NULL DEFAULT NULL COMMENT '开始时间',
  `date_end` datetime NULL DEFAULT NULL COMMENT '结束时间',
  `uncode` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '核销代码',
  `status` tinyint(1) UNSIGNED NULL DEFAULT 1 COMMENT '活动状态',
  `sort` bigint(20) UNSIGNED NULL DEFAULT 0 COMMENT '排序权重',
  `is_deleted` tinyint(1) UNSIGNED NULL DEFAULT 0 COMMENT '删除状态',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '活动-抽奖规则管理' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for activity_luckdraw_config_record
-- ----------------------------
DROP TABLE IF EXISTS `activity_luckdraw_config_record`;
CREATE TABLE `activity_luckdraw_config_record`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cid` bigint(20) UNSIGNED NULL DEFAULT 0 COMMENT '活动ID',
  `prize_id` bigint(20) UNSIGNED NULL DEFAULT 0 COMMENT '奖品ID',
  `prize_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '奖品名称',
  `prize_logo` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '奖品图标',
  `prize_rate` decimal(20, 4) NULL DEFAULT 0.0000 COMMENT '中奖概率',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '活动-抽奖概率管理' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for activity_luckdraw_member
-- ----------------------------
DROP TABLE IF EXISTS `activity_luckdraw_member`;
CREATE TABLE `activity_luckdraw_member`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `openid` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '公众号OPENID',
  `nickname` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '公众号昵称',
  `headimg` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '公众号头像',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '活动-抽奖会员管理' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for activity_luckdraw_member_record
-- ----------------------------
DROP TABLE IF EXISTS `activity_luckdraw_member_record`;
CREATE TABLE `activity_luckdraw_member_record`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `mid` bigint(20) UNSIGNED NULL DEFAULT 0 COMMENT '活动会员ID',
  `prize_id` bigint(20) UNSIGNED NULL DEFAULT 0 COMMENT '奖品ID',
  `prize_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '奖品名称',
  `prize_logo` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '奖品图标',
  `prize_content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '奖品描述',
  `uncode` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '奖品核销码',
  `uncode_state` tinyint(1) UNSIGNED NULL DEFAULT 0 COMMENT '奖品核销状态',
  `uncode_at` datetime NULL DEFAULT NULL COMMENT '奖品核销时间',
  `uncode_uid` bigint(20) UNSIGNED NULL DEFAULT 0 COMMENT '奖品核销用户',
  `status` tinyint(1) UNSIGNED NULL DEFAULT 1 COMMENT '销售状态',
  `sort` bigint(20) UNSIGNED NULL DEFAULT 0 COMMENT '排序权重',
  `is_deleted` tinyint(1) UNSIGNED NULL DEFAULT 0 COMMENT '删除状态',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '活动-抽奖中奖记录' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for activity_luckdraw_prize
-- ----------------------------
DROP TABLE IF EXISTS `activity_luckdraw_prize`;
CREATE TABLE `activity_luckdraw_prize`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '奖品名称',
  `logo` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '奖品图标',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '奖品描述',
  `status` tinyint(1) UNSIGNED NULL DEFAULT 1 COMMENT '奖品状态',
  `sort` bigint(20) UNSIGNED NULL DEFAULT 0 COMMENT '排序权重',
  `is_deleted` tinyint(1) UNSIGNED NULL DEFAULT 0 COMMENT '删除状态',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '活动-抽奖奖品管理' ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
