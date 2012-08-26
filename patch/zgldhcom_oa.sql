/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50154
Source Host           : localhost:3306
Source Database       : zgldhcom_oa

Target Server Type    : MYSQL
Target Server Version : 50154
File Encoding         : 65001

Date: 2012-08-26 19:43:52
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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='oa_achievement\r\n用于记录表示每一个成就实体。';

-- ----------------------------
-- Records of oa_achievement
-- ----------------------------
INSERT INTO oa_achievement VALUES ('6', '0', '一顿饭吃10个馒头', '7', '5', '1', '不怕撑死', '2', '2012-08-21 17:47:39');
INSERT INTO oa_achievement VALUES ('7', '0', '纸篓投篮达人', '7', '7', '1', '办公室常见游戏', '2', '2012-08-21 17:58:22');
INSERT INTO oa_achievement VALUES ('8', '0', '小浣熊卡牌专家', '7', '8', '1', '我想收集齐小浣熊出的所有三国卡、水浒卡', '2', '2012-08-21 18:07:33');
INSERT INTO oa_achievement VALUES ('9', '0', '上过太空', '3', '9', '1', '你们这群地球人上过太空么？！', '2', '2012-08-21 18:13:31');
INSERT INTO oa_achievement VALUES ('10', '0', '睡神', '7', '11', '1', '眼睛一闭，就睡过去了', '2', '2012-08-23 17:15:49');
INSERT INTO oa_achievement VALUES ('11', '0', '四大名著', '7', '12', '1', '将四大名著都读过一遍，并且分别写出读后感', '2', '2012-08-23 17:21:05');
INSERT INTO oa_achievement VALUES ('12', '0', '拿到驾照', '7', '13', '1', '会开还要拿驾照', '2', '2012-08-23 18:06:46');

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
  PRIMARY KEY (`procedure_id`),
  KEY `achievement_id` (`achievement_id`,`step_num`),
  KEY `upper_id` (`upper_id`,`step_num`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8 COMMENT='oa_achievement_step\r\n达成成就所必需的步骤列表';

-- ----------------------------
-- Records of oa_procedure
-- ----------------------------
INSERT INTO oa_procedure VALUES ('9', '6', '1', null, '做出10个馒头');
INSERT INTO oa_procedure VALUES ('10', '6', '2', null, '全吃下去');
INSERT INTO oa_procedure VALUES ('11', '7', '1', null, '1米外用废纸投篮命中');
INSERT INTO oa_procedure VALUES ('12', '7', '2', null, '2米外投篮命中');
INSERT INTO oa_procedure VALUES ('13', '7', '3', null, '5米外投篮命中');
INSERT INTO oa_procedure VALUES ('14', '7', '4', null, '10米外投篮命中');
INSERT INTO oa_procedure VALUES ('15', '8', '1', null, '去买干脆面');
INSERT INTO oa_procedure VALUES ('16', '8', '2', null, '把卡拿出来，把面扔掉');
INSERT INTO oa_procedure VALUES ('17', '8', '3', null, '收集到所有水浒卡');
INSERT INTO oa_procedure VALUES ('18', '8', '4', null, '收集到所有三国卡');
INSERT INTO oa_procedure VALUES ('19', '8', '5', null, '');
INSERT INTO oa_procedure VALUES ('20', '9', '1', null, '在中国，还是先去当战斗机飞行员吧');
INSERT INTO oa_procedure VALUES ('21', '9', '2', null, '宇航员选拔的时候一定要被选上');
INSERT INTO oa_procedure VALUES ('22', '9', '3', null, '争取参加载人航天任务');
INSERT INTO oa_procedure VALUES ('23', '9', '4', null, '上去了。来张照片留念');
INSERT INTO oa_procedure VALUES ('24', '10', '1', null, '一觉睡上12小时。这才刚入门');
INSERT INTO oa_procedure VALUES ('25', '10', '2', null, '吃过晚饭后，直接睡到第二天晚饭');
INSERT INTO oa_procedure VALUES ('26', '10', '3', null, '连续睡三天不睁眼');
INSERT INTO oa_procedure VALUES ('27', '10', '4', null, '连续睡一星期（这是昏迷了吧？）');
INSERT INTO oa_procedure VALUES ('28', '11', '1', null, '读《红楼梦》并且写读后感');
INSERT INTO oa_procedure VALUES ('29', '11', '2', null, '读《西游记》并且写读后感');
INSERT INTO oa_procedure VALUES ('30', '11', '3', null, '读《三国演义》并且写读后感');
INSERT INTO oa_procedure VALUES ('31', '11', '4', null, '读《水浒传》并且写读后感');
INSERT INTO oa_procedure VALUES ('32', '12', '1', null, '到当地车管所报名， 或到一个驾校报名');
INSERT INTO oa_procedure VALUES ('33', '12', '2', null, '科目一理论考试，要考到90分以上');
INSERT INTO oa_procedure VALUES ('34', '12', '3', null, '科目二桩考');
INSERT INTO oa_procedure VALUES ('35', '12', '4', null, '科目三路考');
INSERT INTO oa_procedure VALUES ('36', '12', '5', null, '领证吧');

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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_tags
-- ----------------------------
INSERT INTO oa_tags VALUES ('5', '吃货', '1');
INSERT INTO oa_tags VALUES ('6', '馒头', '1');
INSERT INTO oa_tags VALUES ('7', '投掷', '1');
INSERT INTO oa_tags VALUES ('8', '运动', '1');
INSERT INTO oa_tags VALUES ('9', '办公室', '1');
INSERT INTO oa_tags VALUES ('10', '小浣熊干脆面', '1');
INSERT INTO oa_tags VALUES ('11', '卡牌', '1');
INSERT INTO oa_tags VALUES ('12', '收集', '1');
INSERT INTO oa_tags VALUES ('13', '太空', '1');
INSERT INTO oa_tags VALUES ('14', '旅行', '1');
INSERT INTO oa_tags VALUES ('15', '睡觉', '1');
INSERT INTO oa_tags VALUES ('16', '读书', '1');
INSERT INTO oa_tags VALUES ('17', '驾驶', '1');
INSERT INTO oa_tags VALUES ('18', '技能', '1');

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
INSERT INTO oa_tags_achievement VALUES ('6', '5');
INSERT INTO oa_tags_achievement VALUES ('6', '6');
INSERT INTO oa_tags_achievement VALUES ('7', '7');
INSERT INTO oa_tags_achievement VALUES ('7', '8');
INSERT INTO oa_tags_achievement VALUES ('7', '9');
INSERT INTO oa_tags_achievement VALUES ('8', '10');
INSERT INTO oa_tags_achievement VALUES ('8', '11');
INSERT INTO oa_tags_achievement VALUES ('8', '12');
INSERT INTO oa_tags_achievement VALUES ('9', '13');
INSERT INTO oa_tags_achievement VALUES ('9', '14');
INSERT INTO oa_tags_achievement VALUES ('10', '15');
INSERT INTO oa_tags_achievement VALUES ('11', '16');
INSERT INTO oa_tags_achievement VALUES ('12', '17');
INSERT INTO oa_tags_achievement VALUES ('12', '18');

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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_uploaded
-- ----------------------------
INSERT INTO oa_uploaded VALUES ('5', '002.jpg', '.jpg', 'uploads/43/08/0f/43080fc27b948b8c55031fb3d65f1cce/', '4840', 'logo', '7', 'saved', '2012-08-21 17:47:12');
INSERT INTO oa_uploaded VALUES ('7', 'u1029571090,3652895485fm52gp0.jpg', '.jpg', 'uploads/41/d5/16/41d5161c7066dca8a6f8f01ea6e8db3b/', '3095', 'logo', '7', 'saved', '2012-08-21 17:57:13');
INSERT INTO oa_uploaded VALUES ('8', '20090105.raccoon-sticker04_.jpg', '.jpg', 'uploads/5b/69/8e/5b698e0c248248596580b7a2cdfca2be/', '8745', 'logo', '7', 'saved', '2012-08-21 18:06:31');
INSERT INTO oa_uploaded VALUES ('9', '200911292439802_2.jpg', '.jpg', 'uploads/11/23/9e/11239e163d298bb2eb25999fc083570d/', '4155', 'logo', '7', 'saved', '2012-08-21 18:11:50');
INSERT INTO oa_uploaded VALUES ('11', 'Sleep.jpg', '.jpg', 'uploads/0d/92/34/0d923432f560abdf4887b277a2f4bc06/', '7268', 'logo', '7', 'saved', '2012-08-23 17:13:59');
INSERT INTO oa_uploaded VALUES ('12', 'd231428.jpg', '.jpg', 'uploads/01/ea/e3/01eae39f648aba5aa1a19cc5b133d14a/', '7309', 'logo', '7', 'saved', '2012-08-23 17:20:27');
INSERT INTO oa_uploaded VALUES ('13', '20110322011353843daib.jpg', '.jpg', 'uploads/a8/23/c8/a823c86b5a519b9bc5134ab1f42328ec/', '5188', 'logo', '7', 'saved', '2012-08-23 18:05:12');

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='oa_user\r\n用户表';

-- ----------------------------
-- Records of oa_user
-- ----------------------------
INSERT INTO oa_user VALUES ('1', 'test1', '098f6bcd4621d373cade4e832627b4f6', '', '2012-06-02 23:15:24', null, null);
INSERT INTO oa_user VALUES ('2', 'test2', '098f6bcd4621d373cade4e832627b4f6', '', '2012-06-02 23:17:28', 'e3d796e1b7d1c8a2a536a0df5a135f0d1345972944', '2012-09-25 00:00:00');
INSERT INTO oa_user VALUES ('3', 'test3', '098f6bcd4621d373cade4e832627b4f6', '', '2012-06-02 23:17:37', null, null);
INSERT INTO oa_user VALUES ('4', 'test4', '098f6bcd4621d373cade4e832627b4f6', '', '2012-06-02 23:17:44', null, null);
INSERT INTO oa_user VALUES ('7', 'test', '098f6bcd4621d373cade4e832627b4f6', 'test@email.com', '2012-08-21 17:45:28', '5fad6024b501fa53b6397fc9e356d24a1345712287', '2012-09-22 00:00:00');
