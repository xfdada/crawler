/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 5.7.26 : Database - scarp
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`scarp` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `scarp`;

/*Table structure for table `article` */

DROP TABLE IF EXISTS `article`;

CREATE TABLE `article` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `title` varchar(100) DEFAULT NULL COMMENT '标题',
  `origin` varchar(50) DEFAULT NULL COMMENT '来源公众号',
  `preview` varchar(200) DEFAULT NULL COMMENT '预览图',
  `description` varchar(255) DEFAULT NULL COMMENT '简略描述',
  `content` longtext COMMENT '文章内容',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '保存时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

/*Table structure for table `article_cash` */

DROP TABLE IF EXISTS `article_cash`;

CREATE TABLE `article_cash` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章id',
  `title` varchar(100) DEFAULT NULL COMMENT '标题',
  `origin` varchar(50) DEFAULT NULL COMMENT '来源公众号',
  `preview` varchar(200) DEFAULT NULL COMMENT '缩略图',
  `content` longtext COMMENT '内容',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '爬取时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
