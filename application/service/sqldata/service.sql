SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for wechat_service_config
-- ----------------------------
DROP TABLE IF EXISTS `wechat_service_config`;
CREATE TABLE `wechat_service_config`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `authorizer_appid` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '公众号APPID',
  `authorizer_access_token` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '公众号Token',
  `authorizer_refresh_token` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '公众号刷新Token',
  `func_info` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '公众号集权',
  `nick_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '公众号昵称',
  `head_img` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '公众号头像',
  `expires_in` bigint(20) NULL DEFAULT NULL COMMENT 'Token有效时间',
  `service_type` tinyint(2) NULL DEFAULT NULL COMMENT '微信类型(0代表订阅号, 2代表服务号, 3代表小程序)',
  `service_type_info` tinyint(2) NULL DEFAULT NULL COMMENT '公众号实际类型',
  `verify_type` tinyint(2) NULL DEFAULT NULL COMMENT '公众号认证类型(-1代表未认证, 0代表微信认证)',
  `verify_type_info` tinyint(2) NULL DEFAULT NULL COMMENT '公众号实际认证类型',
  `user_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '众众号原始账号',
  `alias` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '公众号别名',
  `qrcode_url` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '公众号二维码地址',
  `business_info` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `principal_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '公司名称',
  `miniprograminfo` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '小程序信息JSON',
  `idc` tinyint(1) UNSIGNED NULL DEFAULT NULL,
  `signature` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '小程序的描述',
  `total` bigint(20) UNSIGNED NULL DEFAULT 0 COMMENT '统计调用次数',
  `appkey` char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '接口KEY',
  `appuri` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '响应接口APP',
  `status` tinyint(1) UNSIGNED NULL DEFAULT 1 COMMENT '状态(1正常授权,0取消授权)',
  `is_deleted` tinyint(1) UNSIGNED NULL DEFAULT 0 COMMENT '删除状态(0未删除,1已删除)',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `index_wechat_service_config_authorizer_appid`(`authorizer_appid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 227 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '公众号授权参数' ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
