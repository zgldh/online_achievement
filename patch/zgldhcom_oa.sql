/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50154
Source Host           : localhost:3306
Source Database       : zgldhcom_oa

Target Server Type    : MYSQL
Target Server Version : 50154
File Encoding         : 65001

Date: 2012-09-09 19:33:37
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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='oa_achievement\r\n用于记录表示每一个成就实体。';

-- ----------------------------
-- Records of oa_achievement
-- ----------------------------
INSERT INTO oa_achievement VALUES ('6', '0', '一顿饭吃10个馒头', '7', '5', '1', '不怕撑死', '2', '2012-08-21 17:47:39');
INSERT INTO oa_achievement VALUES ('7', '0', '纸篓投篮达人', '7', '7', '1', '办公室常见游戏', '2', '2012-08-21 17:58:22');
INSERT INTO oa_achievement VALUES ('8', '0', '小浣熊卡牌专家', '7', '8', '1', '我想收集齐小浣熊出的所有三国卡、水浒卡', '2', '2012-08-21 18:07:33');
INSERT INTO oa_achievement VALUES ('9', '0', '上过太空', '7', '9', '1', '你们这群地球人上过太空么？！', '2', '2012-08-21 18:13:31');
INSERT INTO oa_achievement VALUES ('10', '0', '睡神', '7', '11', '1', '眼睛一闭，就睡过去了', '2', '2012-08-23 17:15:49');
INSERT INTO oa_achievement VALUES ('11', '0', '四大名著', '7', '12', '1', '将四大名著都读过一遍，并且分别写出读后感', '2', '2012-08-23 17:21:05');
INSERT INTO oa_achievement VALUES ('12', '0', '拿到驾照', '7', '13', '1', '会开还要拿驾照', '2', '2012-08-23 18:06:46');
INSERT INTO oa_achievement VALUES ('13', '0', '去南极', '7', '14', '1', '到南极吹吹风。。', '2', '2012-08-28 14:17:33');

-- ----------------------------
-- Table structure for `oa_comment`
-- ----------------------------
DROP TABLE IF EXISTS `oa_comment`;
CREATE TABLE `oa_comment` (
  `comment_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `content` varchar(255) NOT NULL,
  `post_date` datetime NOT NULL,
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0: normal; 1: deleted',
  `reply_comment_id` bigint(20) unsigned DEFAULT NULL COMMENT '这条评论是回复的哪条评论id',
  `achievement_id` bigint(20) unsigned DEFAULT NULL COMMENT '这条评论隶属于的成就',
  `track_id` bigint(20) unsigned DEFAULT NULL COMMENT '这条评论隶属于的track',
  PRIMARY KEY (`comment_id`),
  KEY `achievement_id` (`achievement_id`,`post_date`),
  KEY `user_id` (`user_id`,`post_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_comment
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
  `achieve_date` datetime DEFAULT NULL COMMENT '成就实现时间戳',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1: 执行中 2: 已经达成',
  PRIMARY KEY (`intent_id`),
  KEY `user_id` (`user_id`,`achievement_id`),
  KEY `achievement_id` (`achievement_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='oa_achievement_intent\r\n用于记录用户对某成就有意图去达成。记录达成与否';

-- ----------------------------
-- Records of oa_intent
-- ----------------------------
INSERT INTO oa_intent VALUES ('2', '2', '13', '2012-09-02 15:22:24', null, '1');
INSERT INTO oa_intent VALUES ('3', '2', '12', '2012-09-02 16:37:13', null, '1');
INSERT INTO oa_intent VALUES ('4', '7', '12', '2012-09-03 17:43:45', null, '1');
INSERT INTO oa_intent VALUES ('5', '7', '11', '2012-09-07 12:08:42', '2012-09-07 19:31:10', '2');
INSERT INTO oa_intent VALUES ('6', '1', '11', '2012-09-09 19:07:08', null, '1');

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
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 COMMENT='oa_achievement_step\r\n达成成就所必需的步骤列表';

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
INSERT INTO oa_procedure VALUES ('37', '13', '1', null, '游过去');

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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

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
INSERT INTO oa_tags VALUES ('14', '旅行', '2');
INSERT INTO oa_tags VALUES ('15', '睡觉', '1');
INSERT INTO oa_tags VALUES ('16', '读书', '1');
INSERT INTO oa_tags VALUES ('17', '驾驶', '1');
INSERT INTO oa_tags VALUES ('18', '技能', '1');
INSERT INTO oa_tags VALUES ('19', '南极', '1');

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
INSERT INTO oa_tags_achievement VALUES ('13', '14');
INSERT INTO oa_tags_achievement VALUES ('10', '15');
INSERT INTO oa_tags_achievement VALUES ('11', '16');
INSERT INTO oa_tags_achievement VALUES ('12', '17');
INSERT INTO oa_tags_achievement VALUES ('12', '18');
INSERT INTO oa_tags_achievement VALUES ('13', '19');

-- ----------------------------
-- Table structure for `oa_track`
-- ----------------------------
DROP TABLE IF EXISTS `oa_track`;
CREATE TABLE `oa_track` (
  `track_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '成就追踪记录',
  `achievement_id` bigint(20) unsigned NOT NULL,
  `intent_id` bigint(20) unsigned NOT NULL COMMENT '意向id',
  `procedure_id` bigint(20) unsigned NOT NULL COMMENT '这是哪个步骤的track',
  `track_date` datetime NOT NULL COMMENT '本条记录时间戳',
  `content` text COMMENT '记录内容',
  PRIMARY KEY (`track_id`),
  KEY `achievement_id` (`achievement_id`,`track_date`),
  KEY `intent_id` (`intent_id`,`track_date`),
  KEY `procedure_id` (`procedure_id`,`intent_id`,`track_date`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='oa_achievement_track\r\n用于储存用户对一个成就的意向的实施过程中，每个阶段进行的记录。';

-- ----------------------------
-- Records of oa_track
-- ----------------------------
INSERT INTO oa_track VALUES ('1', '12', '4', '32', '2012-09-06 16:33:15', '在郑州兴华驾校报的名');
INSERT INTO oa_track VALUES ('5', '11', '5', '28', '2012-09-07 16:32:21', '满纸荒唐言，一把辛酸泪');
INSERT INTO oa_track VALUES ('6', '11', '5', '29', '2012-09-07 16:32:33', '猴哥，师傅被妖精抓走了');
INSERT INTO oa_track VALUES ('7', '11', '5', '30', '2012-09-07 16:33:10', '话说天下大势分久必合合久必分');
INSERT INTO oa_track VALUES ('9', '11', '5', '31', '2012-09-07 16:46:45', '黑吃黑');
INSERT INTO oa_track VALUES ('10', '11', '6', '28', '2012-09-09 19:07:19', '字数太多了，没有读完');

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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

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
INSERT INTO oa_uploaded VALUES ('14', 'Penguins.jpg', '.jpg', 'uploads/f5/b1/9e/f5b19e90460b166129e50fad2e0eee03/', '5490', 'logo', '7', 'saved', '2012-08-28 14:15:06');
INSERT INTO oa_uploaded VALUES ('15', '010.jpg', '.jpg', 'uploads/0c/6b/b5/0c6bb541c99213ae5c2f304c7c122c2b/', '309439', 'logo', '3', 'processing', '2012-08-29 12:36:16');
INSERT INTO oa_uploaded VALUES ('16', '003.jpg', '.jpg', 'uploads/7b/91/dd/7b91dd45e96d29838222728a1eacf12c/', '858174', 'logo', '3', 'processing', '2012-08-29 12:36:30');
INSERT INTO oa_uploaded VALUES ('17', '009.jpg', '.jpg', 'uploads/50/5b/dc/505bdc293f744075ecb6e0802db5a054/', '600559', 'logo', '3', 'processing', '2012-08-29 12:40:15');
INSERT INTO oa_uploaded VALUES ('18', '010.jpg', '.jpg', 'uploads/88/67/1b/88671b20db06ee51a025ca74811056e8/', '309439', 'logo', '3', 'processing', '2012-08-29 14:04:56');
INSERT INTO oa_uploaded VALUES ('19', '009.jpg', '.jpg', 'uploads/eb/b9/12/ebb912673dc30a24cafe187120b39066/', '600559', 'logo', '3', 'processing', '2012-08-29 14:05:11');
INSERT INTO oa_uploaded VALUES ('20', '011.jpg', '.jpg', 'uploads/6b/c1/6d/6bc16d4f607fd16357b9502b0341e91f/', '1133813', 'logo', '3', 'processing', '2012-08-29 14:05:18');

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
INSERT INTO oa_user VALUES ('1', 'test1', '098f6bcd4621d373cade4e832627b4f6', '', '2012-06-02 23:15:24', '9d16d23fc402ecc7eb12da56b005ec3b1347188825', '2012-10-09 00:00:00');
INSERT INTO oa_user VALUES ('2', 'test2', '098f6bcd4621d373cade4e832627b4f6', '', '2012-06-02 23:17:28', '85e1ccaf67e095cc92ad62ecbd1d744f1347184553', '2012-10-09 00:00:00');
INSERT INTO oa_user VALUES ('3', 'test3', '098f6bcd4621d373cade4e832627b4f6', '', '2012-06-02 23:17:37', 'b222d517b08c2b96afefa98403634c7a1346646219', '2012-10-03 00:00:00');
INSERT INTO oa_user VALUES ('4', 'test4', '098f6bcd4621d373cade4e832627b4f6', '', '2012-06-02 23:17:44', null, null);
INSERT INTO oa_user VALUES ('7', 'test', '098f6bcd4621d373cade4e832627b4f6', 'test@email.com', '2012-08-21 17:45:28', '593dd46503a73be5c2ed3820b67c64151347190261', '2012-10-09 00:00:00');
