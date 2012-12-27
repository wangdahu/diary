-- phpMyAdmin SQL Dump
-- version 3.5.4
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2012 年 12 月 27 日 10:01
-- 服务器版本: 5.5.24-0ubuntu0.12.04.1
-- PHP 版本: 5.3.10-1ubuntu3.4

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
-- 表的结构 `diary_report_record`
--

CREATE TABLE IF NOT EXISTS `diary_report_record` (
  `uid` int(11) NOT NULL COMMENT '汇报日志的用户',
  `type` varchar(20) NOT NULL COMMENT '日报类型，daily/weekly/monthly',
  `object` text NOT NULL COMMENT '汇报人员对象',
  `date` varchar(20) NOT NULL COMMENT '汇报日志的时间对象',
  `repay` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为补交，1为补交'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户日志汇报记录表';

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
