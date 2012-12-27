-- phpMyAdmin SQL Dump
-- version 3.5.4
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2012 年 12 月 28 日 01:36
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
  `type` varchar(20) NOT NULL DEFAULT 'daily' COMMENT '评论的类型，daily:日报，weekly:周报，monthly:月报',
  `object` varchar(50) NOT NULL COMMENT '评论的类型的时间，哪天/周/月',
  `uid` int(11) NOT NULL COMMENT '评论人UID',
  `to_uid` int(11) NOT NULL COMMENT '对谁的评论',
  `content` text NOT NULL COMMENT '评论内容',
  `add_time` int(11) NOT NULL COMMENT '评论时间',
  PRIMARY KEY (`id`),
  KEY `to_uid` (`to_uid`),
  KEY `type` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='日记评论表' AUTO_INCREMENT=45 ;

--
-- 转存表中的数据 `diary_comment`
--

INSERT INTO `diary_comment` (`id`, `type`, `object`, `uid`, `to_uid`, `content`, `add_time`) VALUES
(1, 'daily', '2012-12-23', 1, 5, '的法规的法规', 1356200995),
(2, 'daily', '2012-12-23', 1, 5, '的法规的法规三分三的反思对方的', 1356201017),
(3, 'daily', '2012-12-23', 1, 5, '的法规的反感的法规', 1356201160),
(4, 'daily', '2012-12-23', 1, 7, '的法规的风格的法规的法规', 1356201163),
(5, 'daily', '2012-12-23', 1, 5, '啊撒打扫打扫的', 1356201817),
(6, 'daily', '2012-12-23', 1, 5, '1111111111111111111111', 1356201830),
(7, 'daily', '2012-12-23', 1, 8, '送的反思的反思的反思的反思对方\r\n送的反思的反思对方\r\n送的反思的反思对方\r\n\r\n送的反思的反思对方第三', 1356202351),
(8, 'daily', '2012-12-24', 1, 5, '的法规的法规的反感', 1356202671),
(9, 'daily', '2012-12-24', 1, 5, '的法规的法规的风光', 1356202674),
(10, 'weekly', '2012-51', 1, 2, '送的反思的反思', 1356202758),
(11, 'monthly', '2013-01', 1, 5, '的三分三对方', 1356202787),
(12, 'monthly', '2012-12', 1, 5, '法规和佛光焕发', 1356202910),
(13, 'monthly', '2013-01', 1, 5, '风光好风光好', 1356202914),
(14, 'monthly', '2013-02', 1, 5, '风光好风光焕发', 1356202919),
(15, 'monthly', '2012-11', 1, 3, '送的反思的反思对方', 1356202954),
(16, 'monthly', '2012-11', 1, 5, '送的反思对方', 1356202956),
(17, 'monthly', '2012-10', 1, 5, '送的反思的反思对方', 1356202959),
(18, 'monthly', '2012-09', 1, 5, '送的反思的反思对方', 1356202963),
(19, 'daily', '2012-12-23', 1, 8, '的法规的法规的法规', 1356203927),
(20, 'monthly', '2012-12', 1, 7, '送的反思的反思对方', 1356204324),
(21, 'monthly', '2012-12', 1, 7, '送的反思的反思对方', 1356204327),
(22, 'monthly', '2013-01', 1, 7, '送的反思的反思对方', 1356204333),
(23, 'monthly', '2012-12', 1, 7, '送的反思对方三对方', 1356204338),
(24, 'monthly', '2012-11', 1, 7, '送的反思的反思对方', 1356204343),
(25, 'monthly', '2012-10', 1, 7, '送的反思的反思对方', 1356204347),
(26, 'weekly', '2012-51', 1, 7, '送的反思的反思对方', 1356204358),
(27, 'weekly', '2012-51', 1, 7, '的反思的反思对方', 1356204361),
(28, 'weekly', '2012-50', 1, 7, '的反思对方的反思', 1356204369),
(29, 'weekly', '2012-49', 1, 7, '送的反思的反思对方', 1356204376),
(30, 'daily', '2012-12-22', 1, 5, '送的反思对方', 1356204473),
(31, 'daily', '2012-12-21', 1, 5, '送的反思对方', 1356204477),
(32, 'daily', '2012-12-20', 1, 5, '送的反思对方', 1356204480),
(33, 'daily', '2012-12-19', 1, 5, '送的反思对方', 1356204484),
(34, 'daily', '2012-12-22', 1, 7, '送的反思对方', 1356204503),
(35, 'daily', '2012-12-23', 1, 1, '送的反思对方', 1356204508),
(36, 'daily', '2012-12-23', 1, 4, '对方对方的', 1356204513),
(37, 'daily', '2012-12-22', 1, 1, '送的反思对方', 1356204522),
(38, 'daily', '2012-12-20', 1, 1, '送的反思对方', 1356204527),
(39, 'weekly', '2012-51', 1, 1, '撒打扫的反思对方', 1356204536),
(40, 'monthly', '2012-12', 1, 1, '法规风光好风光好', 1356204540),
(41, 'monthly', '2013-01', 1, 1, '规划机构环境', 1356204543),
(42, 'monthly', '2012-10', 1, 1, '规划机构环境', 1356204548),
(43, 'monthly', '2012-07', 1, 1, '‘风光好风光好风光好\\''ghjghjghj ''', 1356204563),
(44, 'monthly', '2012-07', 1, 1, 'dfgdfgdfgdfg', 1356204569);

-- --------------------------------------------------------

--
-- 表的结构 `diary_daily_tag`
--

CREATE TABLE IF NOT EXISTS `diary_daily_tag` (
  `diary_id` int(11) NOT NULL COMMENT '日记ID',
  `tag_id` int(11) NOT NULL COMMENT '标签ID',
  KEY `diary_id` (`diary_id`,`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='日记标签表';

--
-- 转存表中的数据 `diary_daily_tag`
--

INSERT INTO `diary_daily_tag` (`diary_id`, `tag_id`) VALUES
(5, 2),
(6, 2),
(8, 4),
(13, 2),
(23, 2),
(148, 2);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='日志信息表' AUTO_INCREMENT=151 ;

--
-- 转存表中的数据 `diary_info`
--

INSERT INTO `diary_info` (`id`, `corp_id`, `content`, `uid`, `type`, `show_time`, `report_time`, `fill_time`) VALUES
(27, 1, '11-15号就写好了的', 1, 1, 1355846400, 1355846400, 1355513451),
(28, 1, '11-15号就写好了的', 1, 1, 1355846400, 1355846400, 1355513454),
(29, 1, '也是十五好写的', 5, 1, 1355673600, 1355673600, 1355513726),
(30, 1, '的法规的反感', 1, 1, 1355738400, 1355738400, 1355515354),
(31, 1, '速度顶顶顶顶顶顶顶顶顶', 1, 1, 1355565600, 1355565600, 1355515450),
(32, 1, '今天写的', 1, 1, 1355565600, 1355565600, 1355515462),
(33, 1, '补交的日志', 7, 1, 1355565600, 1355565600, 1355515809),
(34, 1, '补交的日志', 1, 1, 1355565600, 1355565600, 1355515811),
(35, 1, '这才叫补交，你骗人', 1, 1, 1355479200, 1355479200, 1355515842),
(36, 1, '这才叫补交，你骗人', 1, 1, 1355479200, 1355479200, 1355515842),
(37, 1, '今天写的，咋样？', 1, 1, 1355652000, 1355652000, 1355673290),
(38, 1, '今天写的，咋样？', 4, 1, 1355652000, 1355652000, 1355673294),
(39, 1, '法规回复很风光好', 1, 1, 1355673599, 1355673599, 1355673599),
(40, 1, '17号', 1, 1, 1355824800, 1355824800, 1355679998),
(41, 1, '17号', 1, 1, 1355824800, 1355824800, 1355680000),
(42, 1, '17号', 1, 1, 1355680024, 1355680024, 1355680024),
(43, 1, '17号', 8, 1, 1355680026, 1355680026, 1355680026),
(44, 1, '顶顶顶顶顶顶顶顶顶', 1, 1, 1355587200, 1355680570, 1355680570),
(45, 1, 'dfgdfgdf', 1, 1, 1356019200, 1356098335, 1356098335),
(46, 1, 'dfsdfsdfsdfsdf', 1, 1, 1356192000, 1356196122, 1356196122),
(47, 1, 'sdfsdfsdf', 1, 1, 1356192000, 1356196125, 1356196125),
(48, 1, 'sdfsdfdfsdf', 1, 1, 1356105600, 1356196130, 1356196130),
(49, 1, 'sdfsdfdfsdf', 1, 1, 1356105600, 1356196132, 1356196132),
(50, 1, 'dfgdfgdfg', 8, 1, 1356105600, 1356196250, 1356196250),
(51, 1, 'dfgdfgdfgdf', 7, 1, 1356105600, 1356196252, 1356196252),
(52, 1, 'dfgdfgdfg', 3, 1, 1356105600, 1356196255, 1356196255),
(53, 1, 'dfgdfgdfgdfg', 5, 1, 1356105600, 1356196257, 1356196257),
(54, 1, 'dfgdfgdfgdfg', 5, 1, 1356105600, 1356196260, 1356196260),
(55, 1, 'dfgdfgdfgf', 7, 1, 1356105600, 1356196262, 1356196262),
(56, 1, 'dfgdfgdfgdfg', 1, 1, 1356105600, 1356196264, 1356196264),
(57, 1, '的三分三大方', 7, 1, 1356105600, 1356196267, 1356196267),
(58, 1, '送的反思对方送的反思对方', 1, 1, 1356105600, 1356196269, 1356196269),
(59, 1, '送的反思的反思对方的三分', 7, 1, 1356105600, 1356196272, 1356196272),
(60, 1, '送的反思反思的反思对方', 9, 1, 1356105600, 1356196275, 1356196275),
(61, 1, '送的反思对方送的反思地方', 1, 1, 1356105600, 1356196278, 1356196278),
(62, 1, '送的反思对方送的反思对方', 4, 1, 1356192000, 1356196283, 1356196283),
(63, 1, '送的反思的反思的反思对方', 3, 1, 1356192000, 1356196285, 1356196285),
(64, 1, '送的反思的反思的反思对方', 1, 1, 1356192000, 1356196288, 1356196288),
(65, 1, '送的三分三大方的三分三大方地方', 6, 1, 1356192000, 1356196295, 1356196295),
(66, 1, '的反思地方送的反思对方', 2, 1, 1356192000, 1356196299, 1356196299),
(67, 1, '送的反思对方送的反思对方', 7, 1, 1356192000, 1356196302, 1356196302),
(68, 1, '的法规的法规的反感的法规', 8, 1, 1356192000, 1356196305, 1356196305),
(69, 1, '法规或风格很风光好', 9, 1, 1356192000, 1356196307, 1356196307),
(70, 1, '法规或风格很风光好', 2, 1, 1356192000, 1356196309, 1356196309),
(71, 1, '法规或风格很风光好', 4, 1, 1356192000, 1356196312, 1356196312),
(72, 1, '法规或风格和风光好', 9, 1, 1356192000, 1356196314, 1356196314),
(73, 1, '法规和风光好风光好', 9, 1, 1356192000, 1356196318, 1356196318),
(74, 1, '法规或风格和风光好', 9, 1, 1356192000, 1356196320, 1356196320),
(75, 1, '法规和风光好风光好', 3, 1, 1356192000, 1356196322, 1356196322),
(76, 1, '让他有让他有人天涯', 2, 1, 1356192000, 1356196325, 1356196325),
(77, 1, '儿童儿童儿童', 5, 1, 1356192000, 1356196328, 1356196328),
(78, 1, '儿童儿童儿童儿童而又突然有让他有人太阳', 9, 1, 1356192000, 1356196332, 1356196332),
(79, 1, '于i娱i娱i', 6, 1, 1356192000, 1356196335, 1356196335),
(80, 1, '银行股机构环境v法规换个地方', 5, 1, 1356192000, 1356196340, 1356196340),
(81, 1, '自行操作下存在瑕疵', 9, 1, 1356192000, 1356196343, 1356196343),
(82, 1, '自行操作下存在错', 1, 1, 1356192000, 1356196346, 1356196346),
(83, 1, '在下存在瑕疵现在才', 3, 1, 1356278400, 1356196350, 1356196350),
(84, 1, '自行操作下存在瑕疵', 8, 1, 1356278400, 1356196352, 1356196352),
(85, 1, '自行操作下存在错', 4, 1, 1356278400, 1356196354, 1356196354),
(86, 1, '现在才自行操作下存在瑕疵', 3, 1, 1356278400, 1356196356, 1356196356),
(87, 1, '自行操作下存在瑕疵', 2, 1, 1356278400, 1356196359, 1356196359),
(88, 1, '在下存在瑕疵在下存在瑕疵对方撒的反思大方', 9, 1, 1356278400, 1356196363, 1356196363),
(89, 1, '送的反思的反思对方的三分', 4, 1, 1356278400, 1356196365, 1356196365),
(90, 1, '送的反思的反思对方送的反思而玩儿', 6, 1, 1356278400, 1356196368, 1356196368),
(91, 1, '功夫好风光好风光好风光', 2, 1, 1356278400, 1356196371, 1356196371),
(92, 1, '规划机构环境换个机构环境', 5, 1, 1356278400, 1356196374, 1356196374),
(93, 1, '规划机构环境过后机构环境', 9, 1, 1356278400, 1356196376, 1356196376),
(94, 1, '个环境过后机构环境', 3, 1, 1356364800, 1356196381, 1356196381),
(95, 1, '规划机构和机构环境', 9, 1, 1356364800, 1356196384, 1356196384),
(96, 1, '让他用让他有人同意', 7, 1, 1356364800, 1356196386, 1356196386),
(97, 1, '儿童儿童儿童', 6, 1, 1356364800, 1356196389, 1356196389),
(98, 1, '送的反思的反思对方', 9, 1, 1356364800, 1356196391, 1356196391),
(99, 1, '请问额前我哦', 2, 1, 1356364800, 1356196394, 1356196394),
(100, 1, '请问额前我哦', 5, 1, 1356364800, 1356196396, 1356196396),
(101, 1, '请问额前我哦请问额前我哦请问额', 5, 1, 1356364800, 1356196399, 1356196399),
(102, 1, '请问额前我哦请问额的三分三的反思对方', 6, 1, 1356364800, 1356196405, 1356196405),
(103, 1, '送的反思对方的三分', 7, 1, 1356364800, 1356196408, 1356196408),
(104, 1, '送的反思的反思对方送的反思的反思的反思的反思对方的反思对方', 2, 1, 1356364800, 1356196414, 1356196414),
(105, 1, '的三分三的反思的反思对方', 7, 1, 1356364800, 1356196417, 1356196417),
(106, 1, '送的反思的反思的反思对方', 2, 1, 1356364800, 1356196419, 1356196419),
(107, 1, '的反思的反思的反思的', 7, 1, 1356364800, 1356196421, 1356196421),
(108, 1, '的反思的反思的反思对方', 6, 1, 1356364800, 1356196424, 1356196424),
(109, 1, '送的反思对方对方三大方', 4, 1, 1356364800, 1356196427, 1356196427),
(110, 1, '的反思反思对方第三', 3, 1, 1356364800, 1356196429, 1356196429),
(111, 1, '送的反思的反思对方第三', 6, 1, 1357228800, 1356196437, 1356196437),
(112, 1, '送的反思的反思对方', 9, 1, 1357228800, 1356196440, 1356196440),
(113, 1, '送的反思的反思对方', 3, 1, 1357228800, 1356196443, 1356196443),
(114, 1, '送的反思的反思对方', 9, 1, 1357228800, 1356196445, 1356196445),
(115, 1, '送的反思的反思对方', 9, 1, 1357228800, 1356196447, 1356196447),
(116, 1, '送的反思的反思对方', 7, 1, 1357228800, 1356196450, 1356196450),
(117, 1, '的三分三的反思对方', 8, 1, 1357228800, 1356196452, 1356196452),
(118, 1, '的反思的反思对方', 8, 1, 1357228800, 1356196454, 1356196454),
(119, 1, '送的反思的反思对方', 4, 1, 1357228800, 1356196456, 1356196456),
(120, 1, '送的反思的反思的分', 6, 1, 1357228800, 1356196458, 1356196458),
(121, 1, '的三分三对方的三分', 1, 1, 1357228800, 1356196461, 1356196461),
(122, 1, '送的反思的反思对方', 6, 1, 1357228800, 1356196463, 1356196463),
(123, 1, '送的反思的反思的反思的', 5, 1, 1357228800, 1356196466, 1356196466),
(124, 1, '送的反思的反思的反思的', 4, 1, 1357228800, 1356196468, 1356196468),
(125, 1, '的反思的反思对方', 3, 1, 1357315200, 1356196473, 1356196473),
(126, 1, '的反思的反思对方', 4, 1, 1357315200, 1356196476, 1356196476),
(127, 1, '的反思的反思对方', 1, 1, 1357228800, 1356196530, 1356196530),
(128, 1, '送的反思的反思对方的', 1, 1, 1357142400, 1356196533, 1356196533),
(129, 1, '的反思的反思的反思大概功夫好风光好风光好', 1, 1, 1357056000, 1356196538, 1356196538),
(130, 1, '风光好风光好风光好', 1, 1, 1357056000, 1356196540, 1356196540),
(131, 1, 'f规划风光好风光好', 1, 1, 1357056000, 1356196542, 1356196542),
(132, 1, '风光好风光好风光好', 1, 1, 1357056000, 1356196544, 1356196544),
(133, 1, '风光好风光好风光好', 1, 1, 1357056000, 1356196546, 1356196546),
(134, 1, '风光好风光好风光好', 1, 1, 1356969600, 1356196549, 1356196549),
(135, 1, '功夫好风光好功夫', 1, 1, 1356969600, 1356196552, 1356196552),
(136, 1, '风光好风光好风光好', 1, 1, 1356969600, 1356196554, 1356196554),
(137, 1, '送的反思的反思对方', 1, 1, 1356537600, 1356196559, 1356196559),
(138, 1, '送的反思的反思对方', 1, 1, 1356537600, 1356196561, 1356196561),
(139, 1, '送的反思的反思对方', 1, 1, 1356537600, 1356196563, 1356196563),
(140, 1, '的反思反思的反思', 1, 1, 1356537600, 1356196566, 1356196566),
(141, 1, '的反思对方送服', 1, 1, 1356537600, 1356196568, 1356196568),
(142, 1, '送的反思到反思对方', 1, 1, 1356537600, 1356196571, 1356196571),
(143, 1, '送的反思的反思对方', 1, 1, 1356537600, 1356196573, 1356196573),
(144, 1, 'd f g d f g d f g f g h g h g j', 1, 1, 1356537600, 1356196576, 1356196576),
(145, 1, '过很多风光好的风光好', 1, 1, 1356451200, 1356196580, 1356196580),
(146, 1, '的法规和对方光焕发的', 1, 1, 1356451200, 1356196582, 1356196582),
(147, 1, '的法规和地方很多风光好', 1, 1, 1356364800, 1356196585, 1356196585),
(148, 1, '读过很多风光好的风光好', 1, 1, 1355932800, 1356196592, 1356196592),
(149, 1, '送的反思的反思的反思的反思对方送的反思的反思的反思对方的三分三的反思大方的送的反思对方', 1, 1, 1356537600, 1356620418, 1356620418),
(150, 1, '速度加快三分的三分三就堪萨斯的快速地方\n送的反思的反思对方\n送的反思的反思的反思对方的反思的反思的反思的的反思反思对方\n的三分三的反思的反思的反思对方\n的三分三的反思的反思的反思对方送的反思的反思的反思对方对方的三分三的反思的反思对方送的反思发的反思的反思对方的三分东西方对方送\n的三分三的反思对方', 1, 1, 1356537600, 1356620441, 1356620441);

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
-- 表的结构 `diary_report_record`
--

CREATE TABLE IF NOT EXISTS `diary_report_record` (
  `uid` int(11) NOT NULL COMMENT '汇报日志的用户',
  `type` varchar(20) NOT NULL COMMENT '日报类型，daily/weekly/monthly',
  `object` text NOT NULL COMMENT '汇报人员对象',
  `date` varchar(20) NOT NULL COMMENT '汇报日志的时间对象',
  `repay` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为补交，1为补交'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户日志汇报记录表';

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
  `from_uid` int(11) NOT NULL COMMENT '被订阅的用户uid',
  `from_dept` int(11) NOT NULL COMMENT '被订阅的部门ID',
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
(1, 1, 0, 1),
(1, 2, 0, 1),
(1, 3, 0, 1),
(1, 0, 5, 1),
(1, 0, 8, 1),
(1, 0, 1, 1),
(1, 0, 2, 1),
(1, 0, 3, 1),
(1, 0, 4, 1),
(1, 5, 0, 2),
(1, 0, 5, 2),
(1, 0, 8, 2),
(1, 0, 1, 2),
(1, 0, 2, 2),
(1, 0, 3, 2),
(1, 0, 4, 2),
(1, 5, 0, 3),
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- 转存表中的数据 `diary_tag`
--

INSERT INTO `diary_tag` (`id`, `tag`, `uid`, `color_id`) VALUES
(1, '公共', 1, 14),
(2, '方法', 1, 10),
(3, '广告', 1, 2),
(4, '呵呵00', 1, 18),
(5, '一样', 1, 16),
(6, '往往', 1, 16),
(7, '让人', 1, 9),
(9, '得到', 1, 16);

-- --------------------------------------------------------

--
-- 表的结构 `diary_tag_color`
--

CREATE TABLE IF NOT EXISTS `diary_tag_color` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '颜色表ID',
  `color` char(7) NOT NULL COMMENT '标签颜色rgb值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='标签颜色列表' AUTO_INCREMENT=21 ;

--
-- 转存表中的数据 `diary_tag_color`
--

INSERT INTO `diary_tag_color` (`id`, `color`) VALUES
(1, '#F0F8FF'),
(2, '#FAEBD7'),
(3, '#00FFFF'),
(4, '#7FFFD4'),
(5, '#F0FFFF'),
(6, '#F5F5DC'),
(7, '#FFE4C4'),
(8, '#000000'),
(9, '#FFEBCD'),
(10, '#0000FF'),
(11, '#8A2BE2'),
(12, '#A52A2A'),
(13, '#DEB887'),
(14, '#5F9EA0'),
(15, '#7FFF00'),
(16, '#D2691E'),
(17, '#FF7F50'),
(18, '#6495ED'),
(19, '#FFF8DC'),
(20, '#DC143C');

-- --------------------------------------------------------

--
-- 表的结构 `diary_view_record`
--

CREATE TABLE IF NOT EXISTS `diary_view_record` (
  `type` varchar(20) NOT NULL DEFAULT 'daily' COMMENT '查看类型，daily/weekly/monthly',
  `object` varchar(20) NOT NULL COMMENT '日志查看的时间对象',
  `uid` int(11) NOT NULL COMMENT '被查看的用户',
  `view_uid` int(11) NOT NULL COMMENT '查看人uid',
  `view_time` int(11) NOT NULL COMMENT '查看的时间戳',
  KEY `type` (`type`),
  KEY `object` (`object`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='日记查看记录';

--
-- 转存表中的数据 `diary_view_record`
--

INSERT INTO `diary_view_record` (`type`, `object`, `uid`, `view_uid`, `view_time`) VALUES
('monthly', '2012-12', 5, 1, 1356281369),
('monthly', '2013-01', 5, 1, 1356281468),
('monthly', '2013-02', 5, 1, 1356281488),
('daily', '2012-12-24', 5, 1, 1356281549),
('daily', '2012-12-24', 1, 1, 1356281586),
('daily', '2012-12-25', 5, 1, 1356281616),
('weekly', '2012-52', 5, 1, 1356281869),
('weekly', '2012-51', 4, 1, 1356282373),
('daily', '2012-12-24', 4, 1, 1356282412),
('weekly', '2013-02', 5, 1, 1356282486),
('weekly', '2013-02', 4, 1, 1356282697),
('monthly', '2013-03', 5, 1, 1356362867),
('monthly', '2013-04', 5, 1, 1356362867),
('weekly', '2012-51', 5, 1, 1356362870),
('weekly', '2012-50', 5, 1, 1356362870),
('weekly', '2012-49', 5, 1, 1356362871),
('weekly', '2012-48', 5, 1, 1356362871),
('monthly', '2012-11', 5, 1, 1356362962),
('monthly', '2012-10', 5, 1, 1356362962),
('monthly', '2012-09', 5, 1, 1356362963),
('monthly', '2012-08', 5, 1, 1356362966),
('monthly', '2012-07', 5, 1, 1356363304),
('monthly', '2007-01', 5, 1, 1170177778),
('monthly', '2006-12', 5, 1, 1170178074),
('monthly', '2007-02', 5, 1, 1170264566),
('monthly', '2007-02', 0, 1, 1170264572),
('monthly', '2007-01', 0, 1, 1170264611),
('monthly', '2007-02', 2, 1, 1170264734),
('monthly', '2007-01', 2, 1, 1171388620),
('monthly', '2006-12', 2, 1, 1171388889),
('monthly', '2006-11', 2, 1, 1171388890),
('monthly', '2006-10', 2, 1, 1171388890),
('monthly', '2006-09', 2, 1, 1171388891),
('monthly', '2006-08', 2, 1, 1171388892),
('monthly', '2006-07', 2, 1, 1171388892),
('monthly', '2006-06', 2, 1, 1171388893),
('monthly', '2006-05', 2, 1, 1171388894),
('monthly', '2006-04', 2, 1, 1171388894),
('monthly', '2006-03', 2, 1, 1171388894),
('monthly', '2006-02', 2, 1, 1171388895),
('monthly', '2006-01', 2, 1, 1171388895),
('monthly', '2005-12', 2, 1, 1171388896),
('monthly', '2005-11', 2, 1, 1171388896),
('monthly', '2005-10', 2, 1, 1171388896),
('monthly', '2005-09', 2, 1, 1171388896),
('monthly', '2005-08', 2, 1, 1171388897),
('monthly', '2005-07', 2, 1, 1171388898),
('monthly', '2005-06', 2, 1, 1171388898),
('monthly', '2005-05', 2, 1, 1171388899),
('monthly', '2005-04', 2, 1, 1171388899),
('monthly', '2005-03', 2, 1, 1171388899),
('monthly', '2005-02', 2, 1, 1171388900),
('monthly', '2005-01', 2, 1, 1171388900),
('monthly', '2004-12', 2, 1, 1171388901),
('monthly', '2004-11', 2, 1, 1171389188),
('monthly', '2004-10', 2, 1, 1171389190),
('monthly', '2004-09', 2, 1, 1171389190),
('monthly', '2004-08', 2, 1, 1171389191),
('monthly', '2004-07', 2, 1, 1171389192),
('monthly', '2004-06', 2, 1, 1171389193),
('monthly', '2004-05', 2, 1, 1171389193),
('monthly', '2004-04', 2, 1, 1171389194),
('monthly', '2004-03', 2, 1, 1171389194),
('monthly', '2004-02', 2, 1, 1171389195),
('monthly', '2004-01', 2, 1, 1171389195),
('monthly', '2003-12', 2, 1, 1171389195),
('monthly', '2003-11', 2, 1, 1171389196),
('monthly', '2003-10', 2, 1, 1171389196),
('monthly', '2003-09', 2, 1, 1171389197),
('monthly', '2003-08', 2, 1, 1171389198),
('monthly', '2003-07', 2, 1, 1171389199),
('monthly', '2003-06', 2, 1, 1171389199),
('monthly', '2003-05', 2, 1, 1171389200),
('monthly', '2003-04', 2, 1, 1171389201),
('monthly', '2003-03', 2, 1, 1171389201),
('monthly', '2003-02', 2, 1, 1171389202),
('monthly', '2003-01', 2, 1, 1171389202),
('monthly', '2002-12', 2, 1, 1171389203),
('monthly', '2002-11', 2, 1, 1171389204),
('monthly', '2002-10', 2, 1, 1171389204),
('monthly', '2002-09', 2, 1, 1171389204),
('monthly', '2002-08', 2, 1, 1171389205),
('monthly', '2002-07', 2, 1, 1171389205),
('monthly', '2002-06', 2, 1, 1171389205),
('monthly', '2002-05', 2, 1, 1171389206),
('monthly', '2002-04', 2, 1, 1171389207),
('monthly', '2002-03', 2, 1, 1171389208),
('monthly', '2002-02', 2, 1, 1171389209),
('monthly', '2002-01', 2, 1, 1171389210),
('monthly', '2001-12', 2, 1, 1171389210),
('monthly', '2001-11', 2, 1, 1171389211),
('monthly', '2001-10', 2, 1, 1171389212),
('monthly', '2001-09', 2, 1, 1171389213),
('monthly', '2001-08', 2, 1, 1171389213),
('monthly', '2001-07', 2, 1, 1171389214),
('monthly', '2001-06', 2, 1, 1171389214),
('monthly', '2001-05', 2, 1, 1171389215),
('monthly', '2001-04', 2, 1, 1171389215),
('monthly', '2001-03', 2, 1, 1171389216),
('monthly', '2001-02', 2, 1, 1171389217),
('monthly', '2001-01', 2, 1, 1171389217),
('monthly', '2000-12', 2, 1, 1171389218),
('monthly', '2000-11', 2, 1, 1171389218),
('monthly', '2000-10', 2, 1, 1171389219),
('monthly', '2000-09', 2, 1, 1171389219),
('monthly', '2000-08', 2, 1, 1171389220),
('monthly', '2000-07', 2, 1, 1171389220),
('monthly', '2000-06', 2, 1, 1171389221),
('monthly', '2000-05', 2, 1, 1171389221),
('monthly', '2000-04', 2, 1, 1171389222),
('monthly', '2000-03', 2, 1, 1171389223),
('monthly', '2000-02', 2, 1, 1171389223),
('monthly', '2000-01', 2, 1, 1171389224),
('monthly', '1999-12', 2, 1, 1171389224),
('monthly', '1999-11', 2, 1, 1171389225),
('monthly', '1999-10', 2, 1, 1171389225),
('monthly', '1999-09', 2, 1, 1171389226),
('monthly', '1999-08', 2, 1, 1171389226),
('monthly', '1999-07', 2, 1, 1171389227),
('monthly', '1999-06', 2, 1, 1171389227),
('monthly', '1999-05', 2, 1, 1171389228),
('monthly', '1999-04', 2, 1, 1171389228),
('monthly', '1999-03', 2, 1, 1171389228),
('monthly', '1999-02', 2, 1, 1171389229),
('monthly', '1999-01', 2, 1, 1171389229),
('monthly', '1998-12', 2, 1, 1171389230),
('monthly', '1998-11', 2, 1, 1171389230),
('monthly', '1998-10', 2, 1, 1171389230),
('monthly', '1998-09', 2, 1, 1171389231),
('monthly', '1998-08', 2, 1, 1171389231),
('monthly', '1998-07', 2, 1, 1171389231),
('monthly', '1998-06', 2, 1, 1171389235),
('monthly', '1998-05', 2, 1, 1171389236),
('monthly', '1998-04', 2, 1, 1171389236),
('monthly', '1998-03', 2, 1, 1171389237),
('monthly', '1998-02', 2, 1, 1171389238),
('monthly', '1998-01', 2, 1, 1171389239),
('monthly', '1997-12', 2, 1, 1171389240),
('monthly', '1997-11', 2, 1, 1171389426),
('monthly', '1997-10', 2, 1, 1171389434),
('monthly', '1997-09', 2, 1, 1171389435),
('monthly', '1997-08', 2, 1, 1171389437),
('monthly', '1997-07', 2, 1, 1171389439),
('monthly', '1997-06', 2, 1, 1171389441);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
