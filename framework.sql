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

 Date: 12/10/2018 16:54:23
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for store_goods
-- ----------------------------
DROP TABLE IF EXISTS `store_goods`;
CREATE TABLE `store_goods`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '商品标题',
  `logo` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '商品图标',
  `specs` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '商品规格JSON',
  `lists` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '商品列表JSON',
  `image` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '商品图片',
  `content` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '商品内容',
  `number_sales` bigint(20) UNSIGNED NULL DEFAULT 0 COMMENT '销售数量',
  `number_stock` bigint(20) UNSIGNED NULL DEFAULT 0 COMMENT '库库数量',
  `status` tinyint(1) UNSIGNED NULL DEFAULT 1 COMMENT '销售状态',
  `sort` bigint(20) UNSIGNED NULL DEFAULT 0 COMMENT '排序权重',
  `is_deleted` tinyint(1) UNSIGNED NULL DEFAULT 0 COMMENT '删除状态',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商城商品主表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of store_goods
-- ----------------------------
INSERT INTO `store_goods` VALUES (1, '商品名称', '/upload/cf23526f451784ff/137f161b8fe18d5a.png', '[{\"name\":\"规格分组\",\"list\":[{\"name\":\"规格属性1\",\"check\":true},{\"name\":\"规格属性2\",\"check\":true}]}]', '[[{\"name\":\"规格属性1\",\"check\":true,\"group\":\"规格分组\",\"rowspan\":1,\"show\":true,\"key\":\"规格分组::规格属性1\",\"virtual\":100,\"market\":\"100.00\",\"selling\":\"88.00\",\"status\":true}],[{\"name\":\"规格属性2\",\"check\":true,\"group\":\"规格分组\",\"rowspan\":1,\"show\":true,\"key\":\"规格分组::规格属性2\",\"virtual\":200,\"market\":\"102.00\",\"selling\":\"99.00\",\"status\":true}]]', '/upload/f47b8fe06e38ae99/08e8398da45583b9.png|/upload/6085d57b5ee06ac7/f793fa5f0f052cb5.jpg', '<p>234213</p>\r\n', 0, 0, 1, 0, 0, '2018-10-12 15:44:14');

-- ----------------------------
-- Table structure for store_goods_list
-- ----------------------------
DROP TABLE IF EXISTS `store_goods_list`;
CREATE TABLE `store_goods_list`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `goods_id` bigint(20) UNSIGNED NULL DEFAULT 0 COMMENT '商品ID',
  `goods_spec` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '商品规格',
  `price_market` decimal(20, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '商品标价',
  `price_selling` decimal(20, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '商品售价',
  `number_sales` bigint(20) UNSIGNED NULL DEFAULT 0 COMMENT '销售数量',
  `number_stock` bigint(20) UNSIGNED NULL DEFAULT 0 COMMENT '商品库存',
  `number_virtual` bigint(20) UNSIGNED NULL DEFAULT 0 COMMENT '虚拟销量',
  `status` tinyint(1) UNSIGNED NULL DEFAULT 1 COMMENT '商品状态',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '商城商品规格' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of store_goods_list
-- ----------------------------
INSERT INTO `store_goods_list` VALUES (1, 1, '规格分组::规格属性', 199.00, 99.00, 0, 0, 10, 0, '2018-10-12 15:44:14');
INSERT INTO `store_goods_list` VALUES (2, 1, '规格分组::规格属性1', 100.00, 88.00, 0, 0, 100, 1, '2018-10-12 15:46:16');
INSERT INTO `store_goods_list` VALUES (3, 1, '规格分组::规格属性2', 102.00, 99.00, 0, 0, 200, 1, '2018-10-12 15:46:16');

-- ----------------------------
-- Table structure for store_notify_wxpay
-- ----------------------------
DROP TABLE IF EXISTS `store_notify_wxpay`;
CREATE TABLE `store_notify_wxpay`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `appid` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '公众号ID',
  `mch_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '商户号',
  `sub_mch_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '子商户号',
  `device_info` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '终端设备号',
  `openid` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '用户OPENID',
  `order_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '订单号',
  `trade_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '交易号',
  `trade_type` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '交易类型',
  `bank_type` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '银行类型',
  `is_subscribe` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '是否关注公众账号',
  `total_fee` bigint(20) NULL DEFAULT 0 COMMENT '订单总金额(单位为分)',
  `settlement_total_fee` bigint(20) NULL DEFAULT 0 COMMENT '应结订单金额(单位为分)',
  `fee_type` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '货币类型',
  `cash_fee` bigint(20) NULL DEFAULT 0 COMMENT '现金支付金额',
  `cash_fee_type` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '货币类型',
  `coupon_fee` bigint(20) NULL DEFAULT 0 COMMENT '代金券金额(单位为分)',
  `coupon_count` bigint(20) NULL DEFAULT 0 COMMENT '代金券使用数量',
  `attach` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '商家数据包',
  `time_end` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '支付完成时间',
  `result_code` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '业务结果',
  `err_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '错误返回的信息代码',
  `err_code_des` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '错误返回的信息描述',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '微信支付通知' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for system_auth
-- ----------------------------
DROP TABLE IF EXISTS `system_auth`;
CREATE TABLE `system_auth`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '权限名称',
  `status` tinyint(1) UNSIGNED NULL DEFAULT 1 COMMENT '权限状态',
  `sort` bigint(20) UNSIGNED NULL DEFAULT 0 COMMENT '排序权重',
  `desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '备注说明',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `index_system_auth_title`(`title`) USING BTREE,
  INDEX `index_system_auth_status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '系统权限' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for system_auth_node
-- ----------------------------
DROP TABLE IF EXISTS `system_auth_node`;
CREATE TABLE `system_auth_node`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `auth` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '角色',
  `node` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '节点',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `index_system_auth_auth`(`auth`) USING BTREE,
  INDEX `index_system_auth_node`(`node`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '系统授权' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for system_config
-- ----------------------------
DROP TABLE IF EXISTS `system_config`;
CREATE TABLE `system_config`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '配置名',
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
INSERT INTO `system_config` VALUES (5, 'site_icon', '/upload/f47b8fe06e38ae99/08e8398da45583b9.png');
INSERT INTO `system_config` VALUES (7, 'miitbeian', '粤ICP备16006642号-2');
INSERT INTO `system_config` VALUES (8, 'storage_type', 'local');
INSERT INTO `system_config` VALUES (9, 'storage_local_exts', 'png,jpg,rar,doc,icon,mp4');
INSERT INTO `system_config` VALUES (10, 'storage_qiniu_bucket', '用你自己的');
INSERT INTO `system_config` VALUES (11, 'storage_qiniu_domain', '用你自己的');
INSERT INTO `system_config` VALUES (12, 'storage_qiniu_access_key', '用你自己的');
INSERT INTO `system_config` VALUES (13, 'storage_qiniu_secret_key', '用你自己的');
INSERT INTO `system_config` VALUES (14, 'storage_oss_bucket', 'cuci');
INSERT INTO `system_config` VALUES (15, 'storage_oss_endpoint', '用你自己的');
INSERT INTO `system_config` VALUES (16, 'storage_oss_domain', '用你自己的');
INSERT INTO `system_config` VALUES (17, 'storage_oss_keyid', '用你自己的');
INSERT INTO `system_config` VALUES (18, 'storage_oss_secret', '用你自己的');
INSERT INTO `system_config` VALUES (36, 'storage_oss_is_https', 'http');
INSERT INTO `system_config` VALUES (43, 'storage_qiniu_region', '华东');
INSERT INTO `system_config` VALUES (44, 'storage_qiniu_is_https', 'http');

-- ----------------------------
-- Table structure for system_menu
-- ----------------------------
DROP TABLE IF EXISTS `system_menu`;
CREATE TABLE `system_menu`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) UNSIGNED NULL DEFAULT 0 COMMENT '父ID',
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '名称',
  `node` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '节点代码',
  `icon` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '菜单图标',
  `url` varchar(400) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '链接',
  `params` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '链接参数',
  `target` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '_self' COMMENT '打开方式',
  `sort` int(11) UNSIGNED NULL DEFAULT 0 COMMENT '菜单排序',
  `status` tinyint(1) UNSIGNED NULL DEFAULT 1 COMMENT '状态(0:禁用,1:启用)',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `index_system_menu_node`(`node`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '系统菜单' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of system_menu
-- ----------------------------
INSERT INTO `system_menu` VALUES (1, 0, '后台首页', '', '', 'admin/index/main', '', '_self', 100, 1, '2018-09-05 17:59:38');
INSERT INTO `system_menu` VALUES (2, 0, '系统管理', '', '', '#', '', '_self', 300, 1, '2018-09-05 18:04:52');
INSERT INTO `system_menu` VALUES (3, 12, '系统菜单', '', 'layui-icon layui-icon-menu-fill', 'admin/menu/index', '', '_self', 3, 1, '2018-09-05 18:05:26');
INSERT INTO `system_menu` VALUES (4, 2, '系统配置', '', '', '#', '', '_self', 10, 1, '2018-09-05 18:07:17');
INSERT INTO `system_menu` VALUES (5, 12, '用户管理', '', 'far fa-user', 'admin/user/index', '', '_self', 4, 1, '2018-09-06 11:10:42');
INSERT INTO `system_menu` VALUES (6, 12, '节点管理', '', 'fas fa-align-center', 'admin/node/index', '', '_self', 1, 1, '2018-09-06 14:16:13');
INSERT INTO `system_menu` VALUES (7, 12, '权限管理', '', 'layui-icon layui-icon-vercode', 'admin/auth/index', '', '_self', 2, 1, '2018-09-06 15:17:14');
INSERT INTO `system_menu` VALUES (10, 4, '文件存储', '', 'layui-icon layui-icon-template-1', 'admin/config/file', '', '_self', 2, 1, '2018-09-06 16:43:19');
INSERT INTO `system_menu` VALUES (11, 4, '系统参数', '', 'layui-icon layui-icon-set', 'admin/config/info', '', '_self', 1, 1, '2018-09-06 16:43:47');
INSERT INTO `system_menu` VALUES (12, 2, '权限管理', '', '', '#', '', '_self', 20, 1, '2018-09-06 18:01:31');
INSERT INTO `system_menu` VALUES (13, 0, '商城管理', '', '', '#', '', '_self', 200, 1, '2018-10-12 13:56:29');
INSERT INTO `system_menu` VALUES (14, 13, '商品管理', '', '', '#', '', '_self', 0, 1, '2018-10-12 13:56:48');
INSERT INTO `system_menu` VALUES (15, 14, '商品列表', '', 'fab fa-palfed', 'store/goods/index', '', '_self', 0, 1, '2018-10-12 13:57:37');

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
) ENGINE = InnoDB AUTO_INCREMENT = 42 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '系统节点' ROW_FORMAT = Compact;

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
INSERT INTO `system_node` VALUES (28, 'admin/auth/resume', '启用权限', 0, 1, 1, '2018-09-06 15:16:11');
INSERT INTO `system_node` VALUES (29, 'admin/auth/del', '删除权限', 0, 1, 1, '2018-09-06 15:16:11');
INSERT INTO `system_node` VALUES (30, 'admin/config', '参数配置', 0, 1, 1, '2018-09-06 16:41:18');
INSERT INTO `system_node` VALUES (32, 'admin/config/file', '文件存储', 1, 1, 1, '2018-09-06 16:41:19');
INSERT INTO `system_node` VALUES (34, 'admin/config/info', '系统信息', 1, 1, 1, '2018-09-06 16:42:10');
INSERT INTO `system_node` VALUES (36, 'store/goods/index', '商品列表', 1, 1, 1, '2018-10-12 13:54:45');
INSERT INTO `system_node` VALUES (37, 'store/goods/add', '添加商品', 0, 1, 1, '2018-10-12 13:54:45');
INSERT INTO `system_node` VALUES (38, 'store/goods/edit', '编辑商品', 0, 1, 1, '2018-10-12 13:54:46');
INSERT INTO `system_node` VALUES (39, 'store', '商城管理', 0, 1, 1, '2018-10-12 13:54:53');
INSERT INTO `system_node` VALUES (40, 'store/goods', '商品管理', 0, 1, 1, '2018-10-12 13:55:20');
INSERT INTO `system_node` VALUES (41, 'store/goods/forbid', '启用商品', 0, 0, 0, '2018-10-12 16:49:02');

-- ----------------------------
-- Table structure for system_user
-- ----------------------------
DROP TABLE IF EXISTS `system_user`;
CREATE TABLE `system_user`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '用户账号',
  `password` char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '用户密码',
  `qq` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '联系QQ',
  `mail` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '联系邮箱',
  `phone` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '联系手机号',
  `login_at` datetime NULL DEFAULT NULL COMMENT '登录时间',
  `login_ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '登录IP',
  `login_num` bigint(20) UNSIGNED NULL DEFAULT 0 COMMENT '登录次数',
  `authorize` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '权限授权',
  `desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '备注说明',
  `status` tinyint(1) UNSIGNED NULL DEFAULT 1 COMMENT '状态(0:禁用,1:启用)',
  `is_deleted` tinyint(1) UNSIGNED NULL DEFAULT 0 COMMENT '删除(1:删除,0:未删)',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `index_system_user_username`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10001 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '系统用户' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of system_user
-- ----------------------------
INSERT INTO `system_user` VALUES (10000, 'admin', '21232f297a57a5a743894a0e4a801fc3', '22222222', '', '13111111111', '2018-10-12 16:46:32', '127.0.0.1', 80, '3', '', 1, 0, '2015-11-13 15:14:22');

SET FOREIGN_KEY_CHECKS = 1;
