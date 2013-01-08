-- phpMyAdmin SQL Dump
-- version 3.5.4
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2013 年 01 月 09 日 01:08
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
(1, '#B54143'),
(2, '#4AA900'),
(3, '#DBBA0F'),
(4, '#37964C'),
(5, '#DE7BA8'),
(6, '#E5ACAD'),
(7, '#AFE09C'),
(8, '#F4D2A3'),
(9, '#A6DAED'),
(10, '#F4C9E1'),
(11, '#46BC94'),
(12, '#C7AE00'),
(13, '#CE68EA'),
(14, '#9D9D9D'),
(15, '#373737'),
(16, '#ACE7D8'),
(17, '#ECE09D'),
(18, '#EEBFFA'),
(19, '#D9D9D9'),
(20, '#F1F1F1');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
