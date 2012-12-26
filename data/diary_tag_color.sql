-- phpMyAdmin SQL Dump
-- version 3.5.4
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2012 年 12 月 27 日 01:18
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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
