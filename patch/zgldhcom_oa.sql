/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50519
Source Host           : localhost:3306
Source Database       : zgldhcom_oa

Target Server Type    : MYSQL
Target Server Version : 50519
File Encoding         : 65001

Date: 2012-08-18 12:37:21
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `oa_achievement`
-- ----------------------------
DROP TABLE IF EXISTS `oa_achievement`;
CREATE TABLE `oa_achievement` (
  `achievement_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '成就id',
  `super_achievement_id` bigint(20) unsigned DEFAULT NULL COMMENT '本条成就继承自哪个成就',
  `name` varchar(255) NOT NULL COMMENT '成就名字',
  `creater_id` bigint(20) unsigned NOT NULL COMMENT '创作者id=>oa_user.user_id',
  `logo_id` bigint(20) unsigned DEFAULT NULL,
  `track_type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1: 普通 2: 阶段式 3: 无限式',
  `requirement` text NOT NULL COMMENT '达成条件，达成需求',
  `status` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '0: 刚创建，编辑中； 1. 创建完成，待审核； 2. 审核通过； 3. 已屏蔽',
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`achievement_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COMMENT='oa_achievement\r\n用于记录表示每一个成就实体。';

-- ----------------------------
-- Records of oa_achievement
-- ----------------------------

-- ----------------------------
-- Table structure for `oa_grradation`
-- ----------------------------
DROP TABLE IF EXISTS `oa_grradation`;
CREATE TABLE `oa_grradation` (
  `achievement_relation_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '成就关系id',
  `upper_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '上级成就id',
  `lower_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '下级成就id',
  PRIMARY KEY (`achievement_relation_id`),
  KEY `upper_id` (`upper_id`),
  KEY `lower_id` (`lower_id`),
  CONSTRAINT `oa_grradation_ibfk_1` FOREIGN KEY (`upper_id`) REFERENCES `oa_achievement` (`achievement_id`) ON DELETE CASCADE,
  CONSTRAINT `oa_grradation_ibfk_2` FOREIGN KEY (`lower_id`) REFERENCES `oa_achievement` (`achievement_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='oa_achievement_relation\r\n用于记录成就实体之间的关系。\r\n上级成就是开始实现下级成就的必要条件。';

-- ----------------------------
-- Records of oa_grradation
-- ----------------------------

-- ----------------------------
-- Table structure for `oa_intent`
-- ----------------------------
DROP TABLE IF EXISTS `oa_intent`;
CREATE TABLE `oa_intent` (
  `intent_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `achievement_id` bigint(20) unsigned NOT NULL,
  `intent_date` datetime NOT NULL COMMENT '该意图建立的时间戳',
  `achieve_date` datetime NOT NULL COMMENT '成就实现时间戳',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1: 执行中 2: 已经达成',
  PRIMARY KEY (`intent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='oa_achievement_intent\r\n用于记录用户对某成就有意图去达成。记录达成与否';

-- ----------------------------
-- Records of oa_intent
-- ----------------------------

-- ----------------------------
-- Table structure for `oa_procedure`
-- ----------------------------
DROP TABLE IF EXISTS `oa_procedure`;
CREATE TABLE `oa_procedure` (
  `procedure_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `achievement_id` bigint(20) unsigned NOT NULL COMMENT '成就id',
  `step_num` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '步骤顺序，小序在前',
  `upper_id` bigint(20) unsigned DEFAULT NULL COMMENT '上一级步骤ID',
  `description` text NOT NULL COMMENT '步骤描述',
  PRIMARY KEY (`procedure_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='oa_achievement_step\r\n达成成就所必需的步骤列表';

-- ----------------------------
-- Records of oa_procedure
-- ----------------------------

-- ----------------------------
-- Table structure for `oa_tags`
-- ----------------------------
DROP TABLE IF EXISTS `oa_tags`;
CREATE TABLE `oa_tags` (
  `tag_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(255) NOT NULL,
  `tag_count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`tag_id`),
  KEY `tag_name` (`tag_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_tags
-- ----------------------------

-- ----------------------------
-- Table structure for `oa_tags_achievement`
-- ----------------------------
DROP TABLE IF EXISTS `oa_tags_achievement`;
CREATE TABLE `oa_tags_achievement` (
  `achievement_id` bigint(20) unsigned NOT NULL,
  `tag_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`achievement_id`,`tag_id`),
  KEY `tag_id` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_tags_achievement
-- ----------------------------

-- ----------------------------
-- Table structure for `oa_track`
-- ----------------------------
DROP TABLE IF EXISTS `oa_track`;
CREATE TABLE `oa_track` (
  `track_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '成就追踪记录',
  `intent_id` bigint(20) unsigned NOT NULL COMMENT '意向id',
  `track_date` datetime NOT NULL COMMENT '本条记录时间戳',
  `content` text COMMENT '记录内容',
  PRIMARY KEY (`track_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='oa_achievement_track\r\n用于储存用户对一个成就的意向的实施过程中，每个阶段进行的记录。';

-- ----------------------------
-- Records of oa_track
-- ----------------------------

-- ----------------------------
-- Table structure for `oa_uploaded`
-- ----------------------------
DROP TABLE IF EXISTS `oa_uploaded`;
CREATE TABLE `oa_uploaded` (
  `uploaded_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  `file_ext` varchar(10) DEFAULT NULL COMMENT '扩展名',
  `relative_path` varchar(255) NOT NULL,
  `size` int(10) unsigned NOT NULL DEFAULT '0',
  `file_type` varchar(255) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `statues` varchar(32) NOT NULL DEFAULT 'processing',
  `upload_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`uploaded_id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_uploaded
-- ----------------------------

-- ----------------------------
-- Table structure for `oa_user`
-- ----------------------------
DROP TABLE IF EXISTS `oa_user`;
CREATE TABLE `oa_user` (
  `user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '用户名',
  `password` varchar(32) NOT NULL COMMENT '经过md5加密的密码',
  `email` varchar(255) NOT NULL,
  `reg_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '注册时间戳',
  `auto_login_token` varchar(255) DEFAULT NULL,
  `auto_login_expire` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='oa_user\r\n用户表';

-- ----------------------------
-- Records of oa_user
-- ----------------------------
INSERT INTO oa_user VALUES ('1', 'test1', '098f6bcd4621d373cade4e832627b4f6', '', '2012-06-02 23:15:24', null, null);
INSERT INTO oa_user VALUES ('2', 'test2', '098f6bcd4621d373cade4e832627b4f6', '', '2012-06-02 23:17:28', 'b929c12306ea7c3f6a01fca0a0ae64981345259073', '2012-09-17 00:00:00');
INSERT INTO oa_user VALUES ('3', 'test3', '098f6bcd4621d373cade4e832627b4f6', '', '2012-06-02 23:17:37', null, null);
INSERT INTO oa_user VALUES ('4', 'test4', '098f6bcd4621d373cade4e832627b4f6', '', '2012-06-02 23:17:44', null, null);
INSERT INTO oa_user VALUES ('6', 'test', '098f6bcd4621d373cade4e832627b4f6', 'test@email.com', '2012-08-06 18:59:01', '9e747c1b6ef02167ce93a6d87afd2e291344927520', '2012-09-13 00:00:00');
