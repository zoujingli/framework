/*
 Navicat Premium Data Transfer

 Source Server         : server.cuci.cc
 Source Server Type    : MySQL
 Source Server Version : 50561
 Source Host           : server.cuci.cc:3306
 Source Schema         : framework

 Target Server Type    : MySQL
 Target Server Version : 50561
 File Encoding         : 65001

 Date: 19/09/2018 17:19:34
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for system_auth
-- ----------------------------
DROP TABLE IF EXISTS `system_auth`;
CREATE TABLE `system_auth`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '权限名称',
  `status` tinyint(1) UNSIGNED NULL DEFAULT 1 COMMENT '状态(1:禁用,2:启用)',
  `sort` smallint(6) UNSIGNED NULL DEFAULT 0 COMMENT '排序权重',
  `desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注说明',
  `create_by` bigint(11) UNSIGNED NULL DEFAULT 0 COMMENT '创建人',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `index_system_auth_title`(`title`) USING BTREE,
  INDEX `index_system_auth_status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '系统权限' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of system_auth
-- ----------------------------
INSERT INTO `system_auth` VALUES (3, 'test', 1, 0, 'test', 0, '2018-09-07 11:08:05');

-- ----------------------------
-- Table structure for system_auth_node
-- ----------------------------
DROP TABLE IF EXISTS `system_auth_node`;
CREATE TABLE `system_auth_node`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `auth` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '角色ID',
  `node` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '节点路径',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `index_system_auth_auth`(`auth`) USING BTREE,
  INDEX `index_system_auth_node`(`node`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 167 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '系统授权' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of system_auth_node
-- ----------------------------
INSERT INTO `system_auth_node` VALUES (136, 3, 'admin');
INSERT INTO `system_auth_node` VALUES (137, 3, 'admin/auth');
INSERT INTO `system_auth_node` VALUES (138, 3, 'admin/auth/index');
INSERT INTO `system_auth_node` VALUES (139, 3, 'admin/auth/apply');
INSERT INTO `system_auth_node` VALUES (140, 3, 'admin/auth/add');
INSERT INTO `system_auth_node` VALUES (141, 3, 'admin/auth/edit');
INSERT INTO `system_auth_node` VALUES (142, 3, 'admin/auth/forbid');
INSERT INTO `system_auth_node` VALUES (143, 3, 'admin/auth/resume');
INSERT INTO `system_auth_node` VALUES (144, 3, 'admin/auth/del');
INSERT INTO `system_auth_node` VALUES (145, 3, 'admin/config');
INSERT INTO `system_auth_node` VALUES (146, 3, 'admin/config/info');
INSERT INTO `system_auth_node` VALUES (147, 3, 'admin/menu');
INSERT INTO `system_auth_node` VALUES (148, 3, 'admin/menu/index');
INSERT INTO `system_auth_node` VALUES (149, 3, 'admin/menu/edit');
INSERT INTO `system_auth_node` VALUES (150, 3, 'admin/menu/add');
INSERT INTO `system_auth_node` VALUES (151, 3, 'admin/menu/resume');
INSERT INTO `system_auth_node` VALUES (152, 3, 'admin/menu/forbid');
INSERT INTO `system_auth_node` VALUES (153, 3, 'admin/menu/del');
INSERT INTO `system_auth_node` VALUES (154, 3, 'admin/node');
INSERT INTO `system_auth_node` VALUES (155, 3, 'admin/node/index');
INSERT INTO `system_auth_node` VALUES (156, 3, 'admin/node/clear');
INSERT INTO `system_auth_node` VALUES (157, 3, 'admin/node/save');
INSERT INTO `system_auth_node` VALUES (158, 3, 'admin/user');
INSERT INTO `system_auth_node` VALUES (159, 3, 'admin/user/index');
INSERT INTO `system_auth_node` VALUES (160, 3, 'admin/user/auth');
INSERT INTO `system_auth_node` VALUES (161, 3, 'admin/user/add');
INSERT INTO `system_auth_node` VALUES (162, 3, 'admin/user/edit');
INSERT INTO `system_auth_node` VALUES (163, 3, 'admin/user/pass');
INSERT INTO `system_auth_node` VALUES (164, 3, 'admin/user/del');
INSERT INTO `system_auth_node` VALUES (165, 3, 'admin/user/forbid');
INSERT INTO `system_auth_node` VALUES (166, 3, 'admin/user/resume');

-- ----------------------------
-- Table structure for system_config
-- ----------------------------
DROP TABLE IF EXISTS `system_config`;
CREATE TABLE `system_config`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '配置编码',
  `value` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '配置值',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `index_system_config_name`(`name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 45 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '系统配置' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of system_config
-- ----------------------------
INSERT INTO `system_config` VALUES (1, 'app_name', 'Framework');
INSERT INTO `system_config` VALUES (2, 'site_name', 'Framework');
INSERT INTO `system_config` VALUES (3, 'app_version', 'V1.0');
INSERT INTO `system_config` VALUES (4, 'site_copy', '©版权所有 2014-2018 楚才科技');
INSERT INTO `system_config` VALUES (5, 'browser_icon', '/upload/f47b8fe06e38ae99/08e8398da45583b9.png');
INSERT INTO `system_config` VALUES (6, 'tongji_baidu_key', '');
INSERT INTO `system_config` VALUES (7, 'miitbeian', '粤ICP备16006642号-2');
INSERT INTO `system_config` VALUES (8, 'storage_type', 'local');
INSERT INTO `system_config` VALUES (9, 'storage_local_exts', 'png,jpg,rar,doc,icon,mp4');
INSERT INTO `system_config` VALUES (10, 'storage_qiniu_bucket', '用你自己的');
INSERT INTO `system_config` VALUES (11, 'storage_qiniu_domain', '用你自己的');
INSERT INTO `system_config` VALUES (12, 'storage_qiniu_access_key', '用你自己的');
INSERT INTO `system_config` VALUES (13, 'storage_qiniu_secret_key', '用你自己的');
INSERT INTO `system_config` VALUES (14, 'storage_oss_bucket', 'cuci');
INSERT INTO `system_config` VALUES (15, 'storage_oss_endpoint', 'oss-cn-beijing.aliyuncs.com');
INSERT INTO `system_config` VALUES (16, 'storage_oss_domain', 'cuci.oss-cn-beijing.aliyuncs.com');
INSERT INTO `system_config` VALUES (17, 'storage_oss_keyid', '用你自己的吧');
INSERT INTO `system_config` VALUES (18, 'storage_oss_secret', '用你自己的吧');
INSERT INTO `system_config` VALUES (34, 'wechat_appid', 'wx60a43dd8161666d4');
INSERT INTO `system_config` VALUES (35, 'wechat_appkey', '9890a0d7c91801a609d151099e95b61a');
INSERT INTO `system_config` VALUES (36, 'storage_oss_is_https', 'http');
INSERT INTO `system_config` VALUES (37, 'wechat_type', 'thr');
INSERT INTO `system_config` VALUES (38, 'wechat_token', 'test');
INSERT INTO `system_config` VALUES (39, 'wechat_appsecret', 'a041bec98ed015d52b99acea5c6a16ef');
INSERT INTO `system_config` VALUES (40, 'wechat_encodingaeskey', 'BJIUzE0gqlWy0GxfPp4J1oPTBmOrNDIGPNav1YFH5Z5');
INSERT INTO `system_config` VALUES (41, 'wechat_thr_appid', 'wx60a43dd8161666d4');
INSERT INTO `system_config` VALUES (42, 'wechat_thr_appkey', '05db2aa335382c66ab56d69b1a9ad0ee');
INSERT INTO `system_config` VALUES (43, 'storage_qiniu_region', '华东');
INSERT INTO `system_config` VALUES (44, 'storage_qiniu_is_https', 'http');

-- ----------------------------
-- Table structure for system_menu
-- ----------------------------
DROP TABLE IF EXISTS `system_menu`;
CREATE TABLE `system_menu`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父id',
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '名称',
  `node` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '节点代码',
  `icon` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '菜单图标',
  `url` varchar(400) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '链接',
  `params` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '链接参数',
  `target` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '_self' COMMENT '链接打开方式',
  `sort` int(11) UNSIGNED NULL DEFAULT 0 COMMENT '菜单排序',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态(0:禁用,1:启用)',
  `create_by` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建人',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `index_system_menu_node`(`node`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '系统菜单' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of system_menu
-- ----------------------------
INSERT INTO `system_menu` VALUES (1, 0, '后台首页', '', '', 'admin/index/main', '', '_self', 100, 1, 0, '2018-09-05 17:59:38');
INSERT INTO `system_menu` VALUES (2, 0, '系统管理', '', '', '#', '', '_self', 300, 1, 0, '2018-09-05 18:04:52');
INSERT INTO `system_menu` VALUES (3, 12, '系统菜单', '', 'layui-icon layui-icon-menu-fill', 'admin/menu/index', '', '_self', 3, 1, 0, '2018-09-05 18:05:26');
INSERT INTO `system_menu` VALUES (4, 2, '系统配置', '', '', '#', '', '_self', 10, 1, 0, '2018-09-05 18:07:17');
INSERT INTO `system_menu` VALUES (5, 12, '用户管理', '', 'far fa-user', 'admin/user/index', '', '_self', 4, 1, 0, '2018-09-06 11:10:42');
INSERT INTO `system_menu` VALUES (6, 12, '节点管理', '', 'fas fa-align-center', 'admin/node/index', '', '_self', 1, 1, 0, '2018-09-06 14:16:13');
INSERT INTO `system_menu` VALUES (7, 12, '权限管理', '', 'layui-icon layui-icon-vercode', 'admin/auth/index', '', '_self', 2, 1, 0, '2018-09-06 15:17:14');
INSERT INTO `system_menu` VALUES (10, 4, '文件存储', '', 'layui-icon layui-icon-template-1', 'admin/config/file', '', '_self', 2, 1, 0, '2018-09-06 16:43:19');
INSERT INTO `system_menu` VALUES (11, 4, '系统参数', '', 'layui-icon layui-icon-set', 'admin/config/info', '', '_self', 1, 1, 0, '2018-09-06 16:43:47');
INSERT INTO `system_menu` VALUES (12, 2, '权限管理', '', '', '#', '', '_self', 20, 1, 0, '2018-09-06 18:01:31');

-- ----------------------------
-- Table structure for system_node
-- ----------------------------
DROP TABLE IF EXISTS `system_node`;
CREATE TABLE `system_node`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `node` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '节点代码',
  `title` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '节点标题',
  `is_menu` tinyint(1) UNSIGNED NULL DEFAULT 0 COMMENT '是否可设置为菜单',
  `is_auth` tinyint(1) UNSIGNED NULL DEFAULT 1 COMMENT '是否启动RBAC权限控制',
  `is_login` tinyint(1) UNSIGNED NULL DEFAULT 1 COMMENT '是否启动登录控制',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `index_system_node_node`(`node`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 36 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '系统节点' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of system_node
-- ----------------------------
INSERT INTO `system_node` VALUES (1, 'admin', '系统管理', 0, 1, 1, '2018-09-06 14:20:42');
INSERT INTO `system_node` VALUES (2, 'admin/menu', '菜单管理', 0, 1, 1, '2018-09-06 14:23:01');
INSERT INTO `system_node` VALUES (3, 'admin/menu/index', '菜单列表', 1, 1, 1, '2018-09-06 14:23:01');
INSERT INTO `system_node` VALUES (4, 'admin/menu/edit', '编辑菜单', 0, 1, 1, '2018-09-06 14:23:01');
INSERT INTO `system_node` VALUES (5, 'admin/menu/add', '添加菜单', 0, 1, 1, '2018-09-06 14:23:01');
INSERT INTO `system_node` VALUES (6, 'admin/menu/resume', '启用菜单', 0, 1, 1, '2018-09-06 14:23:01');
INSERT INTO `system_node` VALUES (7, 'admin/menu/forbid', '禁用菜单', 0, 1, 1, '2018-09-06 14:23:02');
INSERT INTO `system_node` VALUES (8, 'admin/menu/del', '删除菜单', 0, 1, 1, '2018-09-06 14:23:02');
INSERT INTO `system_node` VALUES (9, 'admin/node/index', '节点列表', 1, 1, 1, '2018-09-06 14:24:20');
INSERT INTO `system_node` VALUES (10, 'admin/node/clear', '清理节点', 0, 1, 1, '2018-09-06 14:24:20');
INSERT INTO `system_node` VALUES (11, 'admin/node/save', '更新节点', 0, 1, 1, '2018-09-06 14:24:20');
INSERT INTO `system_node` VALUES (12, 'admin/user/index', '用户列表', 1, 1, 1, '2018-09-06 14:24:21');
INSERT INTO `system_node` VALUES (13, 'admin/user/auth', '用户授权', 0, 1, 1, '2018-09-06 14:24:21');
INSERT INTO `system_node` VALUES (14, 'admin/user/add', '添加用户', 0, 1, 1, '2018-09-06 14:24:21');
INSERT INTO `system_node` VALUES (15, 'admin/user/edit', '编辑用户', 0, 1, 1, '2018-09-06 14:24:21');
INSERT INTO `system_node` VALUES (16, 'admin/user/pass', '修改密码', 0, 1, 1, '2018-09-06 14:24:22');
INSERT INTO `system_node` VALUES (17, 'admin/user/del', '删除用户', 0, 1, 1, '2018-09-06 14:24:22');
INSERT INTO `system_node` VALUES (18, 'admin/user/forbid', '禁用用户', 0, 1, 1, '2018-09-06 14:24:22');
INSERT INTO `system_node` VALUES (19, 'admin/user/resume', '启用用户', 0, 1, 1, '2018-09-06 14:24:22');
INSERT INTO `system_node` VALUES (20, 'admin/node', '节点管理', 0, 1, 1, '2018-09-06 14:35:36');
INSERT INTO `system_node` VALUES (21, 'admin/user', '用户管理', 0, 1, 1, '2018-09-06 14:36:09');
INSERT INTO `system_node` VALUES (22, 'admin/auth', '权限管理', 0, 1, 1, '2018-09-06 15:16:10');
INSERT INTO `system_node` VALUES (23, 'admin/auth/index', '权限列表', 1, 1, 1, '2018-09-06 15:16:10');
INSERT INTO `system_node` VALUES (24, 'admin/auth/apply', '节点授权', 0, 1, 1, '2018-09-06 15:16:10');
INSERT INTO `system_node` VALUES (25, 'admin/auth/add', '添加授权', 0, 1, 1, '2018-09-06 15:16:10');
INSERT INTO `system_node` VALUES (26, 'admin/auth/edit', '编辑权限', 0, 1, 1, '2018-09-06 15:16:10');
INSERT INTO `system_node` VALUES (27, 'admin/auth/forbid', '禁用权限', 0, 1, 1, '2018-09-06 15:16:11');
INSERT INTO `system_node` VALUES (28, 'admin/auth/resume', '启用权限', 0, 0, 1, '2018-09-06 15:16:11');
INSERT INTO `system_node` VALUES (29, 'admin/auth/del', '删除权限', 0, 0, 0, '2018-09-06 15:16:11');
INSERT INTO `system_node` VALUES (30, 'admin/config', '参数配置', 0, 1, 1, '2018-09-06 16:41:18');
INSERT INTO `system_node` VALUES (32, 'admin/config/file', '文件存储', 1, 1, 1, '2018-09-06 16:41:19');
INSERT INTO `system_node` VALUES (34, 'admin/config/info', '系统信息', 1, 1, 1, '2018-09-06 16:42:10');

-- ----------------------------
-- Table structure for system_user
-- ----------------------------
DROP TABLE IF EXISTS `system_user`;
CREATE TABLE `system_user`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户登录名',
  `password` char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户登录密码',
  `qq` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '联系QQ',
  `mail` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '联系邮箱',
  `phone` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '联系手机号',
  `desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '备注说明',
  `login_num` bigint(20) UNSIGNED NULL DEFAULT 0 COMMENT '登录次数',
  `login_at` datetime NULL DEFAULT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态(0:禁用,1:启用)',
  `authorize` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `is_deleted` tinyint(1) UNSIGNED NULL DEFAULT 0 COMMENT '删除状态(1:删除,0:未删)',
  `create_by` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '创建人',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `index_system_user_username`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10003 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '系统用户' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of system_user
-- ----------------------------
INSERT INTO `system_user` VALUES (10000, 'admin', '21232f297a57a5a743894a0e4a801fc3', '22222222', '', '13111111111', '', 34, '2018-09-19 11:09:43', 1, '3', 0, NULL, '2015-11-13 15:14:22');
INSERT INTO `system_user` VALUES (10001, 'test', '098f6bcd4621d373cade4e832627b4f6', NULL, '', '', '', 2, '2018-09-10 11:28:37', 1, '3', 0, NULL, '2018-09-10 11:19:53');
INSERT INTO `system_user` VALUES (10002, 'ceshi', '059d38a8c888d5109fa33a9815866013', NULL, '123@11.com', '15888888888', '', 1, '2018-09-19 11:19:17', 1, '', 0, NULL, '2018-09-19 11:12:37');

SET FOREIGN_KEY_CHECKS = 1;
