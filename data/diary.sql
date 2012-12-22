-- phpMyAdmin SQL Dump
-- version 3.5.4
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2012 年 12 月 23 日 01:02
-- 服务器版本: 5.5.28-0ubuntu0.12.10.2
-- PHP 版本: 5.4.6-1ubuntu1.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `diary`
--

-- --------------------------------------------------------

--
-- 表的结构 `diary_comment`
--

CREATE TABLE IF NOT EXISTS `diary_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '表ID',
  `diary_id` int(11) NOT NULL COMMENT '日记ID',
  `uid` int(11) NOT NULL COMMENT '评论人UID',
  `content` text NOT NULL COMMENT '评论内容',
  `add_time` int(11) NOT NULL COMMENT '评论时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '评论是否已读，默认0未读，1已读',
  PRIMARY KEY (`id`),
  KEY `diary_id` (`diary_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='日记评论表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `diary_diary_tag`
--

CREATE TABLE IF NOT EXISTS `diary_diary_tag` (
  `diary_id` int(11) NOT NULL COMMENT '日记ID',
  `tag_id` int(11) NOT NULL COMMENT '标签ID',
  KEY `diary_id` (`diary_id`,`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='日记标签表';

-- --------------------------------------------------------

--
-- 表的结构 `diary_info`
--

CREATE TABLE IF NOT EXISTS `diary_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '表ID',
  `corp_id` int(11) NOT NULL COMMENT '企业ID',
  `content` text NOT NULL COMMENT '内容',
  `uid` int(11) NOT NULL COMMENT '用户UID',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:日报，2:周报，3:月报',
  `show_time` int(11) NOT NULL COMMENT '应显示时间',
  `report_time` int(11) NOT NULL COMMENT '汇报时间',
  `fill_time` int(11) NOT NULL COMMENT '填写时间',
  PRIMARY KEY (`id`),
  KEY `corp_id` (`corp_id`,`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='日志信息表' AUTO_INCREMENT=46 ;

--
-- 转存表中的数据 `diary_info`
--

INSERT INTO `diary_info` (`id`, `corp_id`, `content`, `uid`, `type`, `show_time`, `report_time`, `fill_time`) VALUES
(27, 1, '11-15号就写好了的', 1, 1, 1355846400, 1355846400, 1355513451),
(28, 1, '11-15号就写好了的', 1, 1, 1355846400, 1355846400, 1355513454),
(29, 1, '也是十五好写的', 1, 1, 1355673600, 1355673600, 1355513726),
(30, 1, '的法规的反感', 1, 1, 1355738400, 1355738400, 1355515354),
(31, 1, '速度顶顶顶顶顶顶顶顶顶', 1, 1, 1355565600, 1355565600, 1355515450),
(32, 1, '今天写的', 1, 1, 1355565600, 1355565600, 1355515462),
(33, 1, '补交的日志', 1, 1, 1355565600, 1355565600, 1355515809),
(34, 1, '补交的日志', 1, 1, 1355565600, 1355565600, 1355515811),
(35, 1, '这才叫补交，你骗人', 1, 1, 1355479200, 1355479200, 1355515842),
(36, 1, '这才叫补交，你骗人', 1, 1, 1355479200, 1355479200, 1355515842),
(37, 1, '今天写的，咋样？', 1, 1, 1355652000, 1355652000, 1355673290),
(38, 1, '今天写的，咋样？', 1, 1, 1355652000, 1355652000, 1355673294),
(39, 1, '法规回复很风光好', 1, 1, 1355673599, 1355673599, 1355673599),
(40, 1, '17号', 1, 1, 1355824800, 1355824800, 1355679998),
(41, 1, '17号', 1, 1, 1355824800, 1355824800, 1355680000),
(42, 1, '17号', 1, 1, 1355680024, 1355680024, 1355680024),
(43, 1, '17号', 8, 1, 1355680026, 1355680026, 1355680026),
(44, 1, '顶顶顶顶顶顶顶顶顶', 1, 1, 1355587200, 1355680570, 1355680570),
(45, 1, 'dfgdfgdf', 1, 1, 1356019200, 1356098335, 1356098335);

-- --------------------------------------------------------

--
-- 表的结构 `diary_remind_set`
--

CREATE TABLE IF NOT EXISTS `diary_remind_set` (
  `uid` int(11) NOT NULL COMMENT '用户uid',
  `daily` varchar(255) NOT NULL COMMENT '日报提醒日期和时间和提醒方式',
  `weekly` varchar(255) NOT NULL COMMENT '周报提醒日期和时间和提醒方式',
  `monthly` varchar(255) NOT NULL COMMENT '月报提醒日期和时间和提醒方式',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户日记提醒设置';

--
-- 转存表中的数据 `diary_remind_set`
--

INSERT INTO `diary_remind_set` (`uid`, `daily`, `weekly`, `monthly`) VALUES
(1, '{"hour":"9","minute":"30","way":["sms","remind"]}', '{"w":"5","hour":"5","minute":"30","way":["sms","remind"]}', '{"date":"20","hour":"17","minute":"30","way":["email","sms","mms","remind"]}');

-- --------------------------------------------------------

--
-- 表的结构 `diary_report_object`
--

CREATE TABLE IF NOT EXISTS `diary_report_object` (
  `uid` int(11) NOT NULL COMMENT '汇报者',
  `to_uid` int(11) NOT NULL COMMENT '汇报给的用户uid',
  `to_dept` int(11) NOT NULL COMMENT '汇报给的部门',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:日报，2:周报，3:月报',
  KEY `uid` (`uid`),
  KEY `to_uid` (`to_uid`),
  KEY `to_dept` (`to_dept`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='日记汇报对象列表';

--
-- 转存表中的数据 `diary_report_object`
--

INSERT INTO `diary_report_object` (`uid`, `to_uid`, `to_dept`, `type`) VALUES
(1, 5, 0, 1),
(1, 8, 0, 1),
(1, 6, 0, 1),
(1, 7, 0, 1),
(1, 1, 0, 1),
(1, 2, 0, 1),
(1, 3, 0, 1),
(1, 4, 0, 1),
(1, 0, 5, 1),
(1, 0, 8, 1),
(1, 0, 6, 1),
(1, 0, 7, 1),
(1, 0, 1, 1),
(1, 0, 2, 1),
(1, 0, 3, 1),
(1, 0, 4, 1),
(1, 5, 0, 2),
(1, 8, 0, 2),
(1, 6, 0, 2),
(1, 7, 0, 2),
(1, 1, 0, 2),
(1, 2, 0, 2),
(1, 3, 0, 2),
(1, 4, 0, 2),
(1, 0, 5, 2),
(1, 0, 8, 2),
(1, 0, 6, 2),
(1, 0, 7, 2),
(1, 0, 1, 2),
(1, 0, 2, 2),
(1, 0, 3, 2),
(1, 0, 4, 2),
(1, 5, 0, 3),
(1, 8, 0, 3),
(1, 6, 0, 3),
(1, 7, 0, 3),
(1, 1, 0, 3),
(1, 2, 0, 3),
(1, 3, 0, 3),
(1, 4, 0, 3),
(1, 0, 5, 3),
(1, 0, 8, 3),
(1, 0, 6, 3),
(1, 0, 7, 3),
(1, 0, 1, 3),
(1, 0, 2, 3),
(1, 0, 3, 3),
(1, 0, 4, 3);

-- --------------------------------------------------------

--
-- 表的结构 `diary_report_set`
--

CREATE TABLE IF NOT EXISTS `diary_report_set` (
  `uid` int(11) NOT NULL COMMENT '用户UID',
  `daily` varchar(255) NOT NULL COMMENT '日报发送时期，时间，方式',
  `weekly` varchar(255) NOT NULL COMMENT '周报发送时期，时间，方式',
  `monthly` varchar(255) NOT NULL COMMENT '月报发送时期，时间，方式',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='日记汇报设置';

--
-- 转存表中的数据 `diary_report_set`
--

INSERT INTO `diary_report_set` (`uid`, `daily`, `weekly`, `monthly`) VALUES
(1, '{"hour":"4","minute":"25","way":["email","sms","mms","remind"]}', '{"w":"7","hour":"17","minute":"45","way":["email","sms","mms","remind"]}', '{"date":"18","hour":"19","minute":"50","way":["email","sms","mms","remind"]}');

-- --------------------------------------------------------

--
-- 表的结构 `diary_set`
--

CREATE TABLE IF NOT EXISTS `diary_set` (
  `uid` int(11) NOT NULL COMMENT '用户UID',
  `working_time` varchar(255) NOT NULL COMMENT '工作时间设置',
  `allow_underling` tinyint(1) NOT NULL DEFAULT '0' COMMENT '默认0不允许下属查看，1为允许下属查看',
  UNIQUE KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='日志设置';

--
-- 转存表中的数据 `diary_set`
--

INSERT INTO `diary_set` (`uid`, `working_time`, `allow_underling`) VALUES
(1, '["1","2","3","4","5"]', 0);

-- --------------------------------------------------------

--
-- 表的结构 `diary_subscribe_object`
--

CREATE TABLE IF NOT EXISTS `diary_subscribe_object` (
  `uid` int(11) NOT NULL COMMENT '订阅者',
  `from_uid` int(11) NOT NULL COMMENT '订阅的用户uid',
  `from_dept` int(11) NOT NULL COMMENT '订阅的部门ID',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:日报，2:周报，3:月报',
  KEY `uid` (`uid`),
  KEY `from_uid` (`from_uid`),
  KEY `from_dept` (`from_dept`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='日记订阅对象列表';

--
-- 转存表中的数据 `diary_subscribe_object`
--

INSERT INTO `diary_subscribe_object` (`uid`, `from_uid`, `from_dept`, `type`) VALUES
(1, 5, 0, 1),
(1, 8, 0, 1),
(1, 1, 0, 1),
(1, 2, 0, 1),
(1, 3, 0, 1),
(1, 4, 0, 1),
(1, 0, 5, 1),
(1, 0, 8, 1),
(1, 0, 1, 1),
(1, 0, 2, 1),
(1, 0, 3, 1),
(1, 0, 4, 1),
(1, 5, 0, 2),
(1, 8, 0, 2),
(1, 1, 0, 2),
(1, 2, 0, 2),
(1, 3, 0, 2),
(1, 4, 0, 2),
(1, 0, 5, 2),
(1, 0, 8, 2),
(1, 0, 1, 2),
(1, 0, 2, 2),
(1, 0, 3, 2),
(1, 0, 4, 2),
(1, 5, 0, 3),
(1, 8, 0, 3),
(1, 1, 0, 3),
(1, 2, 0, 3),
(1, 3, 0, 3),
(1, 4, 0, 3),
(1, 0, 5, 3),
(1, 0, 8, 3),
(1, 0, 1, 3),
(1, 0, 2, 3),
(1, 0, 3, 3),
(1, 0, 4, 3);

-- --------------------------------------------------------

--
-- 表的结构 `diary_tag`
--

CREATE TABLE IF NOT EXISTS `diary_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '表ID',
  `tag` varchar(50) NOT NULL COMMENT 'tag值',
  `uid` int(11) NOT NULL COMMENT '所属用户',
  `color_id` int(11) NOT NULL COMMENT '标签颜色ID',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag` (`tag`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `diary_tag_color`
--

CREATE TABLE IF NOT EXISTS `diary_tag_color` (
  `id` int(11) NOT NULL COMMENT '颜色表ID',
  `color` char(7) NOT NULL COMMENT '标签颜色rgb值'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='标签颜色列表';

-- --------------------------------------------------------

--
-- 表的结构 `diary_view_record`
--

CREATE TABLE IF NOT EXISTS `diary_view_record` (
  `diary_id` int(11) NOT NULL COMMENT '日记ID',
  `uid` int(11) NOT NULL COMMENT '汇报对象UID',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '默认0未读，1已读',
  KEY `diary_id` (`diary_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='日记查看记录';

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
