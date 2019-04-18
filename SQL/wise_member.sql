/*
SQLyog Ultimate v11.24 (32 bit)
MySQL - 10.1.19-MariaDB : Database - wise_member
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`wise_member` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `wise_member`;

/*Table structure for table `member` */

DROP TABLE IF EXISTS `member`;

CREATE TABLE `member` (
  `member_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_nickname` varchar(64) NOT NULL DEFAULT '',
  `member_name` varchar(64) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `member_mobile` varchar(64) NOT NULL,
  `member_email` varchar(64) NOT NULL DEFAULT '',
  `id_type` enum('id_card','passport','hk_permit','tw_permit','officer_permit','other_permit') NOT NULL DEFAULT 'id_card' COMMENT '证件类型 hk_permit 港澳通行证 tw_permit 台胞证 officer_permit军官证',
  `id_number` varchar(64) NOT NULL DEFAULT '' COMMENT '证件号码',
  `member_password` varchar(64) NOT NULL,
  `member_password_salt` varchar(64) NOT NULL,
  `add_datetime` datetime NOT NULL,
  PRIMARY KEY (`member_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `member` */

insert  into `member`(`member_id`,`member_nickname`,`member_name`,`member_mobile`,`member_email`,`id_type`,`id_number`,`member_password`,`member_password_salt`,`add_datetime`) values (1,'me','me','18500353881','kef@yelove.cn','id_card','','111','111','2018-08-26 10:20:00');

/*Table structure for table `member_level` */

DROP TABLE IF EXISTS `member_level`;

CREATE TABLE `member_level` (
  `member_id` int(11) NOT NULL,
  `channel_father_id` int(11) NOT NULL,
  `market_id` int(11) NOT NULL,
  PRIMARY KEY (`member_id`,`channel_father_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `member_level` */

insert  into `member_level`(`member_id`,`channel_father_id`,`market_id`) values (1,1,19);

/*Table structure for table `member_occupant` */

DROP TABLE IF EXISTS `member_occupant`;

CREATE TABLE `member_occupant` (
  `occupant_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '常用联系人',
  `member_id` int(11) NOT NULL COMMENT '会员ID',
  `occupant_name` varchar(64) NOT NULL COMMENT '常用联系人姓名',
  `occupant_sex` enum('0','1') NOT NULL DEFAULT '1',
  `occupant_mobile` varchar(64) NOT NULL,
  `occupant_email` varchar(64) NOT NULL DEFAULT '',
  `id_type` enum('id_card','passport','hk_permit','tw_permit','officer_permit','other_permit') NOT NULL,
  `id_number` varchar(64) NOT NULL,
  `add_datetime` datetime NOT NULL,
  PRIMARY KEY (`occupant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `member_occupant` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
