/*
SQLyog Ultimate v11.24 (32 bit)
MySQL - 10.1.37-MariaDB : Database - wise
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`wise` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `wise`;

/*Table structure for table `booking` */

DROP TABLE IF EXISTS `booking`;

CREATE TABLE `booking` (
  `booking_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '预订ID',
  `booking_number` bigint(19) NOT NULL COMMENT '订单号',
  `booking_number_ext` varchar(64) NOT NULL DEFAULT '' COMMENT '外部订单号',
  `company_id` int(11) NOT NULL COMMENT '品牌 公司ID',
  `channel` enum('Hotel','Meal','Meeting','Shop','Service','Tour','Sport') NOT NULL DEFAULT 'Hotel' COMMENT '频道',
  `channel_id` int(11) NOT NULL,
  `receivable_id` int(11) NOT NULL DEFAULT '0' COMMENT '协议公司/个人等',
  `member_id` int(11) NOT NULL DEFAULT '0' COMMENT '会员0非会员',
  `member_name` varchar(100) NOT NULL COMMENT '会员名字[预订人]',
  `member_mobile` varchar(100) NOT NULL DEFAULT '' COMMENT '会员手机',
  `member_email` varchar(100) NOT NULL DEFAULT '' COMMENT '会员email',
  `booking_status` enum('-9','-8','-7','-6','-5','-4','-3','-2','-1','0','1','2','3','4','5','6','7','8','9') NOT NULL DEFAULT '0' COMMENT 'hotel[0预订][1进行中][-1结束 已完成][-2取消][-3noshow取消][-4挂账退房][-5走结退房]',
  `cash_pledge` float(12,2) NOT NULL COMMENT '押金',
  `employee_id` int(11) NOT NULL COMMENT '预订员工',
  `employee_name` varchar(128) NOT NULL COMMENT '预订员工',
  `check_in` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `in_time` time NOT NULL DEFAULT '00:00:00',
  `check_out` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `out_time` time NOT NULL DEFAULT '00:00:00',
  `business_day` date NOT NULL COMMENT '营业日',
  `sales_id` int(11) NOT NULL DEFAULT '0' COMMENT '销售员',
  `sales_name` varchar(100) NOT NULL DEFAULT '' COMMENT '销售员名字',
  `booking_total_price` float(12,2) NOT NULL COMMENT '总额',
  `client` enum('web','wx','pms') DEFAULT NULL COMMENT '来源',
  `valid` enum('0','1','2') NOT NULL DEFAULT '1' COMMENT '0无效 1有效 2已完结',
  `node` varchar(500) NOT NULL DEFAULT '' COMMENT '客人备注',
  `remarks` varchar(1000) NOT NULL DEFAULT '' COMMENT '内部备注',
  `add_datetime` datetime DEFAULT NULL COMMENT '产生日期',
  `close_datetime` datetime DEFAULT NULL COMMENT '关闭日期',
  PRIMARY KEY (`booking_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

/*Data for the table `booking` */

insert  into `booking`(`booking_id`,`booking_number`,`booking_number_ext`,`company_id`,`channel`,`channel_id`,`receivable_id`,`member_id`,`member_name`,`member_mobile`,`member_email`,`booking_status`,`cash_pledge`,`employee_id`,`employee_name`,`check_in`,`in_time`,`check_out`,`out_time`,`business_day`,`sales_id`,`sales_name`,`booking_total_price`,`client`,`valid`,`node`,`remarks`,`add_datetime`,`close_datetime`) values (1,1908036779701,'',1,'Hotel',1,0,0,'张珊','','kefu@yelove.cn','-1',0.00,1,'有个员工','2019-08-03 00:00:00','14:00:00','2019-08-04 00:00:00','12:00:00','2019-08-03',0,'',0.00,'pms','1','','','2019-08-03 10:33:00',NULL),(2,1908039214402,'',1,'Hotel',1,0,0,'张珊','','kefu@yelove.cn','-1',0.00,1,'有个员工','2019-08-03 00:00:00','14:00:00','2019-08-04 00:00:00','12:00:00','2019-08-03',0,'',0.00,'pms','1','','','2019-08-03 10:33:03',NULL),(4,1908037882704,'',1,'Hotel',1,0,0,'张珊','','kefu@yelove.cn','-1',0.00,1,'有个员工','2019-08-04 00:00:00','14:00:00','2019-08-05 00:00:00','12:00:00','2019-08-03',0,'',0.00,'pms','1','','','2019-08-03 10:33:23',NULL),(5,1908035842305,'',1,'Hotel',1,0,0,'张珊','','kefu@yelove.cn','-1',0.00,1,'有个员工','2019-08-04 00:00:00','14:00:00','2019-08-05 00:00:00','12:00:00','2019-08-03',0,'',0.00,'pms','1','','','2019-08-03 10:34:17',NULL),(7,1908058128807,'',1,'Hotel',1,0,0,'张珊珊','','kefu@yelove.cn','-1',0.00,1,'有个员工','2019-08-05 00:00:00','14:00:00','2019-08-06 00:00:00','12:00:00','2019-08-05',0,'',0.00,'pms','1','','','2019-08-05 16:39:52',NULL),(11,1908057262011,'',1,'Hotel',1,0,0,'张珊','','kefu@yelove.cn','-1',0.00,1,'有个员工','2019-08-06 00:00:00','14:00:00','2019-08-07 00:00:00','12:00:00','2019-08-05',0,'',0.00,'pms','1','','','2019-08-05 16:48:13',NULL),(12,1908052899012,'',1,'Hotel',1,0,0,'张珊','','kefu@yelove.cn','-1',0.00,1,'有个员工','2019-08-07 00:00:00','14:00:00','2019-08-08 00:00:00','12:00:00','2019-08-05',0,'',0.00,'pms','1','','','2019-08-05 20:52:53',NULL),(13,1908095111013,'',1,'Hotel',1,0,0,'张珊','','kefu@yelove.cn','-1',0.00,1,'有个员工','2019-08-09 00:00:00','14:00:00','2019-08-10 00:00:00','12:00:00','2019-08-09',0,'',0.00,'pms','1','','','2019-08-09 09:20:48',NULL),(14,1908092465014,'',1,'Hotel',1,0,0,'张珊','','kefu@yelove.cn','-1',0.00,1,'有个员工','2019-08-09 00:00:00','14:00:00','2019-08-14 00:00:00','12:00:00','2019-08-09',0,'',0.00,'pms','1','','','2019-08-09 17:54:11',NULL),(15,1908102892015,'',1,'Hotel',1,0,0,'张珊珊','','kefu@yelove.cn','-1',0.00,1,'有个员工','2019-08-13 00:00:00','14:00:00','2019-08-15 00:00:00','12:00:00','2019-08-10',0,'',0.00,'pms','1','','','2019-08-10 18:20:06',NULL),(16,1908108824016,'',1,'Hotel',1,0,0,'张珊','18500353881','','-1',0.00,1,'有个员工','2019-08-10 00:00:00','14:00:00','2019-08-13 00:00:00','12:00:00','2019-08-10',0,'',0.00,'pms','1','','','2019-08-10 19:12:52',NULL),(17,1908111185017,'',1,'Hotel',1,0,1,'张珊','','kefu@yelove.cn','-1',0.00,1,'有个员工','2019-08-10 00:00:00','14:00:00','2019-08-12 00:00:00','12:00:00','2019-08-10',0,'',0.00,'pms','1','','','2019-08-11 10:02:12',NULL),(18,1908149558018,'',1,'Hotel',1,0,0,'李希','','kefu@yelove.cn','-1',0.00,1,'有个员工','2019-08-15 00:00:00','14:00:00','2019-08-17 00:00:00','12:00:00','2019-08-12',0,'',0.00,'pms','1','','','2019-08-14 16:52:25',NULL),(19,1908214963019,'',1,'Hotel',1,0,0,'张珊','','kefu@yelove.cn','-1',0.00,1,'有个员工','2019-08-12 00:00:00','14:00:00','2019-08-14 00:00:00','12:00:00','2019-08-12',0,'',0.00,'pms','1','','','2019-08-21 15:36:26',NULL),(20,1908223931020,'',1,'Hotel',1,0,0,'张珊','','kefu@yelove.cn','-1',0.00,1,'有个员工','2019-08-12 00:00:00','14:00:00','2019-08-13 00:00:00','12:00:00','2019-08-12',0,'',0.00,'pms','1','','','2019-08-22 09:06:29',NULL);

/*Table structure for table `booking_accounts` */

DROP TABLE IF EXISTS `booking_accounts`;

CREATE TABLE `booking_accounts` (
  `accounts_id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_detail_id` int(11) NOT NULL DEFAULT '0',
  `booking_number` bigint(19) NOT NULL DEFAULT '0',
  `booking_number_ext` varchar(64) NOT NULL DEFAULT '',
  `company_id` int(11) NOT NULL,
  `channel` enum('Hotel','Meal','Meeting','Shop','Service','Tour','Sport') NOT NULL,
  `booking_type` varchar(64) NOT NULL DEFAULT 'room_rate',
  `channel_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL COMMENT '会员ID',
  `item_id` int(11) NOT NULL,
  `item_name` varchar(128) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `payment_name` varchar(100) NOT NULL,
  `payment_father_id` int(11) NOT NULL,
  `receivable_id` int(11) NOT NULL DEFAULT '0' COMMENT '挂账的应收账款ID',
  `money` float(12,2) NOT NULL COMMENT '钱',
  `credit_authorized_number` varchar(64) NOT NULL DEFAULT '' COMMENT '授权号',
  `credit_card_number` varchar(64) NOT NULL DEFAULT '' COMMENT '卡号',
  `credit_authorized_days` datetime DEFAULT NULL COMMENT '交易日期',
  `credit_authorized_money` float(8,2) NOT NULL DEFAULT '0.00' COMMENT '原始授权',
  `accounts_type` enum('receipts','refund','hanging','pre-authorization') NOT NULL COMMENT '收/退 款 pre-authorization预授权',
  `account_hanging_money` float(12,2) NOT NULL DEFAULT '0.00' COMMENT '挂账销账',
  `account_cash_return` enum('0','1') NOT NULL COMMENT '挂账回款0 没有 1已回款',
  `accounts_status` enum('-9','-8','-7','-6','-5','-4','-3','-2','-1','0','1','2','3','4','5','6','7','8','9') NOT NULL DEFAULT '0' COMMENT '状态[-1结束 已完成] hotel[0预订][1入住][2noshow入住][-1退房][-2取消][-3noshow取消][-4挂账退房][-5走结退房]',
  `employee_id` int(11) NOT NULL COMMENT '收款人',
  `employee_name` varchar(64) NOT NULL DEFAULT '',
  `business_day` date NOT NULL COMMENT '营业日',
  `valid` enum('0','1') NOT NULL DEFAULT '1' COMMENT '是否有效',
  `add_datetime` datetime NOT NULL,
  PRIMARY KEY (`accounts_id`,`business_day`,`valid`,`add_datetime`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

/*Data for the table `booking_accounts` */

insert  into `booking_accounts`(`accounts_id`,`booking_detail_id`,`booking_number`,`booking_number_ext`,`company_id`,`channel`,`booking_type`,`channel_id`,`member_id`,`item_id`,`item_name`,`payment_id`,`payment_name`,`payment_father_id`,`receivable_id`,`money`,`credit_authorized_number`,`credit_card_number`,`credit_authorized_days`,`credit_authorized_money`,`accounts_type`,`account_hanging_money`,`account_cash_return`,`accounts_status`,`employee_id`,`employee_name`,`business_day`,`valid`,`add_datetime`) values (1,1,1908036779701,'',1,'Hotel','room_day',1,0,3,'201',2,'现金支付',1,0,63.00,'','',NULL,0.00,'receipts',0.00,'0','0',1,'有个员工','2019-08-05','1','2019-08-05 21:15:43'),(2,2,1908039214402,'',1,'Hotel','room_day',1,0,3,'201',2,'现金支付',1,0,1.00,'','',NULL,0.00,'receipts',0.00,'0','0',1,'有个员工','2019-08-05','1','2019-08-05 22:50:39'),(3,8,1908058128807,'',1,'Hotel','room_day',1,0,2,'102',2,'现金支付',1,0,1.00,'','',NULL,0.00,'receipts',0.00,'0','0',1,'有个员工','2019-08-09','1','2019-08-09 09:15:46'),(4,4,1908037882704,'',1,'Hotel','room_day',1,0,2,'102',2,'现金支付',1,0,888.00,'','',NULL,0.00,'receipts',0.00,'0','0',1,'有个员工','2019-08-09','1','2019-08-09 09:17:20'),(5,5,1908035842305,'',1,'Hotel','room_day',1,0,2,'102',2,'现金支付',1,0,888.00,'','',NULL,0.00,'receipts',0.00,'0','0',1,'有个员工','2019-08-09','1','2019-08-09 09:18:23'),(6,12,1908057262011,'',1,'Hotel','room_day',1,0,2,'102',2,'现金支付',1,0,2.00,'','',NULL,0.00,'receipts',0.00,'0','0',1,'有个员工','2019-08-09','1','2019-08-09 09:19:26'),(7,14,1908052899012,'',1,'Hotel','room_day',1,0,3,'201',2,'现金支付',1,0,2.00,'','',NULL,0.00,'receipts',0.00,'0','0',1,'有个员工','2019-08-09','1','2019-08-09 09:20:11'),(8,16,1908095111013,'',1,'Hotel','room_day',1,0,3,'201',4,'支付宝',3,0,66.00,'','',NULL,0.00,'receipts',0.00,'0','0',1,'有个员工','2019-08-10','1','2019-08-10 23:11:25'),(9,17,1908092465014,'',1,'Hotel','room_day',1,0,26,'401',2,'现金支付',1,0,5.00,'','',NULL,0.00,'receipts',0.00,'0','0',1,'有个员工','2019-08-12','1','2019-08-18 14:03:21'),(10,19,1908108824016,'',1,'Hotel','room_day',1,0,3,'201',2,'现金支付',1,0,1.00,'','',NULL,0.00,'receipts',0.00,'0','0',1,'有个员工','2019-08-12','0','2019-08-20 20:14:04'),(11,19,1908108824016,'',1,'Hotel','room_day',1,0,3,'201',2,'现金支付',1,0,1.00,'','',NULL,0.00,'refund',0.00,'0','0',1,'有个员工','2019-08-12','1','2019-08-20 20:15:24'),(12,20,1908111185017,'',1,'Hotel','room_rate',1,1,1,'103',2,'现金支付',1,0,4.00,'','',NULL,0.00,'refund',0.00,'0','0',1,'有个员工','2019-08-12','1','2019-08-20 22:32:17'),(13,19,1908108824016,'',1,'Hotel','room_day',1,0,3,'201',2,'现金支付',1,0,3.00,'','',NULL,0.00,'receipts',0.00,'0','0',1,'有个员工','2019-08-12','1','2019-08-21 02:07:50'),(14,23,1908214963019,'',1,'Hotel','room_day',1,0,1,'103',2,'现金支付',1,0,1.00,'','',NULL,0.00,'receipts',0.00,'0','0',1,'有个员工','2019-08-12','1','2019-08-21 16:29:59'),(15,23,1908214963019,'',1,'Hotel','room_rate',1,0,1,'103',2,'现金支付',1,0,3.00,'','',NULL,0.00,'receipts',0.00,'0','0',1,'有个员工','2019-08-12','1','2019-08-21 22:25:39'),(16,24,1908214963019,'',1,'Hotel','room_rate',1,0,3,'201',2,'现金支付',1,0,3.00,'','',NULL,0.00,'receipts',0.00,'0','0',1,'有个员工','2019-08-12','1','2019-08-21 23:53:39'),(17,25,1908223931020,'',1,'Hotel','room_rate',1,0,3,'201',2,'现金支付',1,0,1.00,'','',NULL,0.00,'receipts',0.00,'0','0',1,'有个员工','2019-08-12','1','2019-08-22 09:55:13'),(18,26,1908223931020,'',1,'Hotel','room_day',1,0,25,'301',2,'现金支付',1,0,11.00,'','',NULL,0.00,'receipts',0.00,'0','0',1,'有个员工','2019-08-12','1','2019-08-23 12:25:20'),(19,26,1908223931020,'',1,'Hotel','room_rate',1,0,25,'301',2,'现金支付',1,0,8.00,'','',NULL,0.00,'refund',0.00,'0','0',1,'有个员工','2019-08-12','1','2019-09-16 10:15:24'),(20,18,1908102892015,'',1,'Hotel','room_rate',1,0,3,'201',2,'现金支付',1,0,2.00,'','',NULL,0.00,'receipts',0.00,'0','0',1,'有个员工','2019-08-14','1','2019-09-16 10:16:40'),(21,21,1908149558018,'',1,'Hotel','room_rate',1,0,3,'201',2,'现金支付',1,0,2.00,'','',NULL,0.00,'receipts',0.00,'0','0',1,'有个员工','2019-09-02','1','2019-09-16 10:19:13');

/*Table structure for table `booking_borrowing` */

DROP TABLE IF EXISTS `booking_borrowing`;

CREATE TABLE `booking_borrowing` (
  `booking_borrowing_id` int(11) NOT NULL AUTO_INCREMENT,
  `channel` varchar(64) NOT NULL,
  `company_id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `booking_detail_id` int(11) NOT NULL,
  `booking_number` bigint(19) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_name` varchar(128) NOT NULL,
  `borrowing_id` int(11) NOT NULL,
  `borrowing_name` varchar(128) NOT NULL,
  `borrowing_num` int(11) NOT NULL DEFAULT '1' COMMENT '数量',
  `borrowing_return_num` int(11) NOT NULL DEFAULT '0' COMMENT '归还',
  `borrowing_return` enum('0','1') DEFAULT '0' COMMENT '1已归还',
  `accounts_id` int(11) NOT NULL DEFAULT '0' COMMENT '已收押金对应的accounts_id',
  `cash_pledge` float(8,2) NOT NULL COMMENT '已收押金',
  `payment_id` int(11) NOT NULL,
  `payment_name` varchar(64) NOT NULL DEFAULT '',
  `employee_id` int(11) NOT NULL,
  `employee_name` varchar(64) NOT NULL DEFAULT '',
  `valid` enum('0','1') NOT NULL DEFAULT '1' COMMENT '有效无效',
  `add_datetime` datetime NOT NULL,
  PRIMARY KEY (`booking_borrowing_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `booking_borrowing` */

insert  into `booking_borrowing`(`booking_borrowing_id`,`channel`,`company_id`,`channel_id`,`booking_detail_id`,`booking_number`,`item_id`,`item_name`,`borrowing_id`,`borrowing_name`,`borrowing_num`,`borrowing_return_num`,`borrowing_return`,`accounts_id`,`cash_pledge`,`payment_id`,`payment_name`,`employee_id`,`employee_name`,`valid`,`add_datetime`) values (1,'1',1,1,19,1908108824016,3,'201',1,'天堂伞小号',1,1,'1',0,20.00,2,'现金支付',1,'有个员工','1','2019-08-20 22:34:46'),(2,'1',1,1,23,1908214963019,1,'103',1,'天堂伞小号',1,0,'0',0,20.00,2,'现金支付',1,'有个员工','1','2019-08-21 18:32:32'),(3,'1',1,1,26,1908223931020,25,'301',1,'天堂伞小号',1,0,'0',0,20.00,2,'现金支付',1,'有个员工','1','2019-08-22 15:42:05');

/*Table structure for table `booking_consume` */

DROP TABLE IF EXISTS `booking_consume`;

CREATE TABLE `booking_consume` (
  `consume_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '消费ID',
  `booking_detail_id` int(11) NOT NULL DEFAULT '0',
  `booking_number` bigint(19) NOT NULL,
  `booking_number_ext` varchar(64) NOT NULL DEFAULT '',
  `company_id` int(11) NOT NULL COMMENT '品牌 公司ID',
  `channel` enum('Hotel','Meal','Meeting','Shop','Service','Tour','Sport') NOT NULL COMMENT '频道',
  `channel_consume_father_id` int(11) NOT NULL DEFAULT '0',
  `channel_consume_id` int(11) NOT NULL DEFAULT '0',
  `booking_type` varchar(64) NOT NULL DEFAULT 'room_rate' COMMENT '消费类型',
  `channel_id` int(11) NOT NULL,
  `receivable_id` int(11) NOT NULL DEFAULT '0',
  `member_id` int(11) NOT NULL COMMENT '会员',
  `market_father_id` int(11) NOT NULL COMMENT '市场大类',
  `market_id` int(11) NOT NULL COMMENT '市场名称',
  `market_name` varchar(64) NOT NULL COMMENT '市场名称',
  `item_id` int(11) NOT NULL COMMENT '项目',
  `item_name` varchar(128) NOT NULL COMMENT '名称',
  `item_amount` int(11) NOT NULL DEFAULT '1' COMMENT '数量',
  `item_category_id` int(11) NOT NULL COMMENT '大类',
  `item_category_name` varchar(128) NOT NULL COMMENT '大类名称',
  `sales_id` int(11) NOT NULL DEFAULT '0',
  `sales_name` varchar(64) NOT NULL DEFAULT '',
  `discount_type` enum('0','1','2','3','4','5','6') NOT NULL COMMENT '是否折扣及类型 0无折扣',
  `price_system_id` int(11) NOT NULL DEFAULT '0' COMMENT '价格体系',
  `price_system_name` varchar(64) NOT NULL DEFAULT '' COMMENT '价格体系',
  `original_price` float(12,2) NOT NULL COMMENT '原价',
  `consume_title` varchar(64) NOT NULL DEFAULT '',
  `consume_price` float(12,2) NOT NULL COMMENT '消费价格',
  `consume_price_total` float(12,2) NOT NULL COMMENT '折后总价',
  `consume_profit` float(12,2) NOT NULL COMMENT '利润 除掉佣金的利润',
  `commision_type` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT '佣金类型 1底价收款 2后付佣金',
  `commision_form` enum('none','percent','night','manually') NOT NULL DEFAULT 'none' COMMENT '佣金形式 百分比 间夜 手动',
  `commision_form_value` int(11) NOT NULL DEFAULT '0' COMMENT '佣金形式 值',
  `consume_status` enum('-9','-8','-7','-6','-5','-4','-3','-2','-1','0','1','2','3','4','5','6') NOT NULL DEFAULT '0' COMMENT '状态[-1结束 已完成] hotel[0预订][1入住][2noshow入住][-1退房][-2取消][-3noshow取消][-4挂账退房][-5走结退房]',
  `employee_id` int(11) NOT NULL,
  `employee_name` varchar(64) NOT NULL DEFAULT '',
  `business_day` date NOT NULL,
  `confirm` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT '夜审确认1手动确认2自动确认',
  `confirm_employee_id` int(11) NOT NULL DEFAULT '0',
  `confirm_employee_name` varchar(64) NOT NULL DEFAULT '',
  `confirm_datetime` datetime DEFAULT NULL,
  `valid` enum('0','1') NOT NULL DEFAULT '1',
  `add_datetime` datetime NOT NULL,
  PRIMARY KEY (`consume_id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;

/*Data for the table `booking_consume` */

insert  into `booking_consume`(`consume_id`,`booking_detail_id`,`booking_number`,`booking_number_ext`,`company_id`,`channel`,`channel_consume_father_id`,`channel_consume_id`,`booking_type`,`channel_id`,`receivable_id`,`member_id`,`market_father_id`,`market_id`,`market_name`,`item_id`,`item_name`,`item_amount`,`item_category_id`,`item_category_name`,`sales_id`,`sales_name`,`discount_type`,`price_system_id`,`price_system_name`,`original_price`,`consume_title`,`consume_price`,`consume_price_total`,`consume_profit`,`commision_type`,`commision_form`,`commision_form_value`,`consume_status`,`employee_id`,`employee_name`,`business_day`,`confirm`,`confirm_employee_id`,`confirm_employee_name`,`confirm_datetime`,`valid`,`add_datetime`) values (1,1,1908036779701,'',1,'Hotel',0,0,'room_rate',1,0,0,1,2,'散客步入',3,'201',1,4,'标准间',0,'','0',3,'散客单早',1.00,'房费',1.00,1.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-03','0',0,'',NULL,'1','2019-08-03 10:33:00'),(2,2,1908039214402,'',1,'Hotel',0,0,'room_rate',1,0,0,1,2,'散客步入',3,'201',1,4,'标准间',0,'','0',3,'散客单早',1.00,'房费',1.00,1.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-03','0',0,'',NULL,'1','2019-08-03 10:33:03'),(4,4,1908037882704,'',1,'Hotel',0,0,'room_rate',1,0,0,1,2,'散客步入',2,'102',1,4,'标准间',0,'','0',3,'散客单早',888.00,'房费',888.00,888.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-04','1',1,'有个员工','2019-08-08 13:01:17','1','2019-08-03 10:33:23'),(5,5,1908035842305,'',1,'Hotel',0,0,'room_rate',1,0,0,1,2,'散客步入',2,'102',1,4,'标准间',0,'','0',3,'散客单早',888.00,'房费',888.00,888.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-04','1',1,'有个员工','2019-08-08 13:01:17','1','2019-08-03 10:34:17'),(6,1,1908036779701,'',1,'Hotel',1,2,'room_rate',1,0,0,1,2,'散客步入',3,'201',1,4,'标准间',0,'','0',3,'散客单早',52.00,'房费',52.00,52.00,5.00,'0','none',0,'0',0,'','2019-08-04','1',1,'有个员工','2019-08-08 13:01:18','1','2019-08-04 20:46:04'),(7,1,1908036779701,'',1,'Hotel',1,2,'room_rate',1,0,0,1,2,'散客步入',3,'201',1,4,'标准间',0,'','0',3,'散客单早',5.00,'房费',5.00,5.00,5.00,'0','none',0,'0',0,'','2019-08-05','1',1,'有个员工','2019-08-08 12:50:36','1','2019-08-04 20:46:04'),(10,8,1908058128807,'',1,'Hotel',0,0,'room_rate',1,0,0,1,2,'散客步入',2,'102',1,4,'标准间',0,'','0',3,'散客单早',1.00,'房费',1.00,1.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-05','1',1,'有个员工','2019-08-08 12:50:33','1','2019-08-05 16:39:52'),(14,12,1908057262011,'',1,'Hotel',0,0,'room_rate',1,0,0,1,2,'散客步入',2,'102',1,4,'标准间',0,'','0',3,'散客单早',1.00,'房费',1.00,1.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-06','1',1,'有个员工','2019-08-08 12:11:20','1','2019-08-05 16:48:13'),(15,13,1908057262011,'',1,'Hotel',0,0,'room_rate',1,0,0,1,2,'散客步入',3,'201',1,4,'标准间',0,'','0',3,'散客单早',1.00,'房费',1.00,1.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-06','1',1,'有个员工','2019-08-08 12:14:50','1','2019-08-05 16:48:13'),(16,1,1908036779701,'',1,'Hotel',1,2,'room_rate',1,0,0,1,2,'散客步入',3,'201',1,4,'标准间',0,'','0',3,'散客单早',5.00,'房费',5.00,5.00,5.00,'0','none',0,'0',0,'','2019-08-06','1',1,'有个员工','2019-08-08 12:50:18','1','2019-08-05 17:30:30'),(27,14,1908052899012,'',1,'Hotel',0,0,'room_rate',1,0,0,1,2,'散客步入',3,'201',1,4,'标准间',0,'','0',3,'散客单早',1.00,'房费',1.00,1.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-07','1',1,'有个员工','2019-08-08 12:40:59','1','2019-08-05 20:52:53'),(28,15,1908052899012,'',1,'Hotel',0,0,'room_rate',1,0,0,1,2,'散客步入',2,'102',1,4,'标准间',0,'','0',3,'散客单早',1.00,'房费',1.00,1.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-07','1',1,'有个员工','2019-08-08 12:12:49','1','2019-08-05 20:52:53'),(29,16,1908095111013,'',1,'Hotel',0,0,'room_rate',1,0,0,1,2,'散客步入',3,'201',1,4,'标准间',0,'','0',3,'散客单早',66.00,'房费',66.00,66.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-09','0',0,'',NULL,'1','2019-08-09 09:20:48'),(30,17,1908092465014,'',1,'Hotel',0,0,'room_rate',1,0,0,1,2,'散客步入',26,'401',1,4,'标准间',0,'','0',3,'散客单早',1.00,'房费',1.00,1.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-09','0',0,'',NULL,'1','2019-08-09 17:54:11'),(31,17,1908092465014,'',1,'Hotel',0,0,'room_rate',1,0,0,1,2,'散客步入',26,'401',1,4,'标准间',0,'','0',3,'散客单早',1.00,'房费',1.00,1.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-10','0',0,'',NULL,'1','2019-08-09 17:54:11'),(32,17,1908092465014,'',1,'Hotel',0,0,'room_rate',1,0,0,1,2,'散客步入',26,'401',1,4,'标准间',0,'','0',3,'散客单早',1.00,'房费',1.00,1.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-11','0',0,'',NULL,'1','2019-08-09 17:54:11'),(33,17,1908092465014,'',1,'Hotel',0,0,'room_rate',1,0,0,1,2,'散客步入',26,'401',1,4,'标准间',0,'','0',3,'散客单早',1.00,'房费',1.00,1.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-12','0',0,'',NULL,'1','2019-08-09 17:54:11'),(34,17,1908092465014,'',1,'Hotel',0,0,'room_rate',1,0,0,1,2,'散客步入',26,'401',1,4,'标准间',0,'','0',3,'散客单早',1.00,'房费',1.00,1.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-13','0',0,'',NULL,'1','2019-08-09 17:54:11'),(35,18,1908102892015,'',1,'Hotel',0,0,'room_rate',1,0,0,1,2,'散客步入',3,'201',1,4,'标准间',0,'','0',3,'散客单早',1.00,'房费',1.00,1.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-13','0',0,'',NULL,'1','2019-08-10 18:20:06'),(36,18,1908102892015,'',1,'Hotel',0,0,'room_rate',1,0,0,1,2,'散客步入',3,'201',1,4,'标准间',0,'','0',3,'散客单早',1.00,'房费',1.00,1.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-14','0',0,'',NULL,'1','2019-08-10 18:20:06'),(37,19,1908108824016,'',1,'Hotel',0,0,'room_rate',1,0,0,1,2,'散客步入',3,'201',1,4,'标准间',0,'','0',3,'散客单早',1.00,'房费',1.00,1.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-10','0',0,'',NULL,'0','2019-08-10 19:12:52'),(38,19,1908108824016,'',1,'Hotel',0,0,'room_rate',1,0,0,1,2,'散客步入',3,'201',1,4,'标准间',0,'','0',3,'散客单早',1.00,'房费',1.00,1.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-11','0',0,'',NULL,'0','2019-08-10 19:12:52'),(39,19,1908108824016,'',1,'Hotel',0,0,'room_rate',1,0,0,1,2,'散客步入',3,'201',1,4,'标准间',0,'','0',3,'散客单早',1.00,'房费',1.00,1.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-12','0',0,'',NULL,'1','2019-08-10 19:12:52'),(40,20,1908111185017,'',1,'Hotel',0,0,'room_rate',1,0,1,4,19,'金卡会员',1,'103',1,8,'双人间',0,'','0',1,'金卡会员无早',2.00,'房费',2.00,2.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-10','0',0,'',NULL,'1','2019-08-11 10:02:12'),(41,20,1908111185017,'',1,'Hotel',0,0,'room_rate',1,0,1,4,19,'金卡会员',1,'103',1,8,'双人间',0,'','0',1,'金卡会员无早',2.00,'房费',2.00,2.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-11','0',0,'',NULL,'1','2019-08-11 10:02:12'),(42,21,1908149558018,'',1,'Hotel',0,0,'room_rate',1,0,0,1,2,'散客步入',3,'201',1,4,'标准间',0,'','0',3,'散客单早',1.00,'房费',1.00,1.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-15','0',0,'',NULL,'1','2019-08-14 16:52:25'),(43,21,1908149558018,'',1,'Hotel',0,0,'room_rate',1,0,0,1,2,'散客步入',3,'201',1,4,'标准间',0,'','0',3,'散客单早',1.00,'房费',1.00,1.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-16','0',0,'',NULL,'1','2019-08-14 16:52:25'),(44,22,1908108824016,'',1,'Hotel',0,0,'room_rate',1,0,0,1,2,'散客步入',25,'301',1,4,'标准间',0,'','0',3,'散客单早',1.00,'房费',1.00,1.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-12','0',0,'',NULL,'1','2019-08-21 13:56:27'),(45,23,1908214963019,'',1,'Hotel',0,0,'room_rate',1,0,0,1,2,'散客步入',1,'103',1,8,'双人间',0,'','0',3,'散客单早',2.00,'房费',2.00,2.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-12','0',0,'',NULL,'1','2019-08-21 15:36:26'),(46,23,1908214963019,'',1,'Hotel',0,0,'room_rate',1,0,0,1,2,'散客步入',1,'103',1,8,'双人间',0,'','0',3,'散客单早',2.00,'房费',2.00,2.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-13','0',0,'',NULL,'1','2019-08-21 15:36:26'),(47,24,1908214963019,'',1,'Hotel',0,0,'room_rate',1,0,0,1,2,'散客步入',3,'201',1,4,'标准间',0,'','0',3,'散客单早',1.00,'房费',1.00,1.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-12','0',0,'',NULL,'1','2019-08-21 15:37:07'),(48,24,1908214963019,'',1,'Hotel',0,0,'room_rate',1,0,0,1,2,'散客步入',3,'201',1,4,'标准间',0,'','0',3,'散客单早',1.00,'房费',1.00,1.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-13','0',0,'',NULL,'1','2019-08-21 15:37:07'),(49,24,1908214963019,'',1,'Hotel',0,0,'room_rate',1,0,0,1,2,'散客步入',3,'201',1,4,'标准间',0,'','0',3,'散客单早',1.00,'房费',1.00,1.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-14','0',0,'',NULL,'1','2019-08-21 15:37:07'),(50,25,1908223931020,'',1,'Hotel',0,0,'room_rate',1,0,0,1,2,'散客步入',3,'201',1,4,'标准间',0,'','0',3,'散客单早',1.00,'房费',1.00,1.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-12','0',0,'',NULL,'1','2019-08-22 09:06:29'),(51,26,1908223931020,'',1,'Hotel',0,0,'room_rate',1,0,0,1,2,'散客步入',25,'301',1,4,'标准间',0,'','0',3,'散客单早',2.00,'房费',2.00,2.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-12','0',0,'',NULL,'1','2019-08-22 09:54:54'),(52,27,1908223931020,'',1,'Hotel',0,0,'room_rate',1,0,0,1,2,'散客步入',-66449007,'',1,4,'标准间',0,'','0',3,'散客单早',1.00,'房费',1.00,1.00,0.00,'0','none',0,'0',1,'有个员工','2019-08-12','0',0,'',NULL,'1','2019-08-22 12:43:26');

/*Table structure for table `booking_detail` */

DROP TABLE IF EXISTS `booking_detail`;

CREATE TABLE `booking_detail` (
  `booking_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_number` bigint(19) NOT NULL COMMENT '订单号',
  `booking_number_ext` varchar(64) NOT NULL DEFAULT '' COMMENT 'ota订单\\外部订单号',
  `company_id` int(11) NOT NULL COMMENT '品牌 公司ID',
  `channel` enum('Hotel','Meal','Meeting','Shop','Service','Tour','Sport') NOT NULL DEFAULT 'Hotel' COMMENT '频道',
  `booking_type` enum('room_hour','room_day','goods') NOT NULL DEFAULT 'room_day' COMMENT 'room_day 全日房 room_hour 小时房',
  `channel_id` int(11) NOT NULL,
  `receivable_id` int(11) NOT NULL DEFAULT '0' COMMENT '协议公司/个人等',
  `member_id` int(11) NOT NULL DEFAULT '0' COMMENT '会员',
  `member_name` varchar(50) NOT NULL DEFAULT '',
  `member_mobile` varchar(50) NOT NULL DEFAULT '',
  `member_email` varchar(100) NOT NULL DEFAULT '',
  `market_father_id` int(11) NOT NULL COMMENT '客源市场父类',
  `market_id` int(11) NOT NULL COMMENT '客源市场',
  `market_name` varchar(100) NOT NULL COMMENT '客源市场名称',
  `item_id` bigint(19) NOT NULL DEFAULT '0' COMMENT '[房间][商品][消费][菜式]item id',
  `item_name` varchar(100) NOT NULL DEFAULT '' COMMENT 'item名称',
  `item_category_id` int(11) NOT NULL COMMENT '[商品类别][房型][消费类别...]item_id category',
  `item_category_name` varchar(128) NOT NULL COMMENT '[商品类别][房型][消费类别...]item_id category',
  `check_in` date NOT NULL DEFAULT '0000-00-00' COMMENT '预抵时间',
  `in_time` time NOT NULL DEFAULT '00:00:00',
  `check_out` date NOT NULL DEFAULT '0000-00-00' COMMENT '预离时间',
  `out_time` time NOT NULL DEFAULT '00:00:00',
  `actual_check_in` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '实际入住',
  `actual_check_out` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '实际退房',
  `booking_detail_status` enum('-9','-8','-7','-6','-5','-4','-3','-2','-1','0','1','2','3','4','5','6','7','8','9') NOT NULL DEFAULT '0' COMMENT 'hotel[0预订][1入住][2noshow入住][-1退房][-2取消][-3noshow取消][-4挂账退房][-5走结退房]',
  `employee_id` int(11) NOT NULL COMMENT '预订员工',
  `employee_name` varchar(100) NOT NULL COMMENT '预订员工',
  `business_day` date NOT NULL COMMENT '营业日',
  `sales_id` int(11) NOT NULL DEFAULT '0' COMMENT '销售员',
  `sales_name` varchar(100) NOT NULL DEFAULT '' COMMENT '销售员名字',
  `discount_type` enum('0','1','2','3','4','5') NOT NULL DEFAULT '0' COMMENT '0无折扣',
  `price_system_id` int(11) NOT NULL COMMENT '价格体系',
  `price_system_name` varchar(64) NOT NULL DEFAULT '' COMMENT '价格体系',
  `source_price` float(12,2) DEFAULT NULL,
  `total_price` float(12,2) NOT NULL COMMENT '实际金额',
  `client` enum('web','wx','pms','CTRIP','ELONG','QUNAR','EASYTRIP','LY') DEFAULT NULL COMMENT '网站 微信 PMS 携程 Booking',
  `valid` enum('0','1','2') NOT NULL DEFAULT '1' COMMENT '0无效 1有效',
  `add_datetime` datetime DEFAULT NULL COMMENT '产生日期',
  `close_datetime` datetime DEFAULT NULL COMMENT '关闭日期',
  PRIMARY KEY (`booking_detail_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

/*Data for the table `booking_detail` */

insert  into `booking_detail`(`booking_detail_id`,`booking_number`,`booking_number_ext`,`company_id`,`channel`,`booking_type`,`channel_id`,`receivable_id`,`member_id`,`member_name`,`member_mobile`,`member_email`,`market_father_id`,`market_id`,`market_name`,`item_id`,`item_name`,`item_category_id`,`item_category_name`,`check_in`,`in_time`,`check_out`,`out_time`,`actual_check_in`,`actual_check_out`,`booking_detail_status`,`employee_id`,`employee_name`,`business_day`,`sales_id`,`sales_name`,`discount_type`,`price_system_id`,`price_system_name`,`source_price`,`total_price`,`client`,`valid`,`add_datetime`,`close_datetime`) values (1,1908036779701,'',1,'Hotel','room_day',1,0,0,'张珊','','kefu@yelove.cn',1,2,'散客步入',3,'201',4,'标准间','2019-08-03','14:00:00','2019-08-07','12:00:00','2019-08-03 23:40:25','0000-00-00 00:00:00','-1',1,'有个员工','2019-08-03',0,'','0',3,'散客单早',0.00,0.00,'pms','1','2019-08-03 10:33:00',NULL),(2,1908039214402,'',1,'Hotel','room_day',1,0,0,'张珊','','kefu@yelove.cn',1,2,'散客步入',3,'201',4,'标准间','2019-08-03','14:00:00','2019-08-04','12:00:00','2019-08-05 22:50:07','0000-00-00 00:00:00','-1',1,'有个员工','2019-08-03',0,'','0',3,'散客单早',0.00,0.00,'pms','1','2019-08-03 10:33:03',NULL),(4,1908037882704,'',1,'Hotel','room_day',1,0,0,'张珊','','kefu@yelove.cn',1,2,'散客步入',2,'102',4,'标准间','2019-08-04','14:00:00','2019-08-05','12:00:00','2019-08-05 22:51:03','0000-00-00 00:00:00','-1',1,'有个员工','2019-08-03',0,'','0',3,'散客单早',0.00,0.00,'pms','1','2019-08-03 10:33:23',NULL),(5,1908035842305,'',1,'Hotel','room_day',1,0,0,'张珊','','kefu@yelove.cn',1,2,'散客步入',2,'102',4,'标准间','2019-08-04','14:00:00','2019-08-05','12:00:00','2019-08-05 22:54:42','0000-00-00 00:00:00','-1',1,'有个员工','2019-08-03',0,'','0',3,'散客单早',0.00,0.00,'pms','1','2019-08-03 10:34:17',NULL),(8,1908058128807,'',1,'Hotel','room_day',1,0,0,'张珊珊','','kefu@yelove.cn',1,2,'散客步入',2,'102',4,'标准间','2019-08-05','14:00:00','2019-08-06','12:00:00','2019-08-05 22:41:24','0000-00-00 00:00:00','-1',1,'有个员工','2019-08-05',0,'','0',3,'散客单早',0.00,0.00,'pms','1','2019-08-05 16:39:52',NULL),(12,1908057262011,'',1,'Hotel','room_day',1,0,0,'张珊','','kefu@yelove.cn',1,2,'散客步入',2,'102',4,'标准间','2019-08-06','14:00:00','2019-08-07','12:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','-1',1,'有个员工','2019-08-05',0,'','0',3,'散客单早',0.00,0.00,'pms','1','2019-08-05 16:48:13',NULL),(13,1908057262011,'',1,'Hotel','room_day',1,0,0,'张珊','','kefu@yelove.cn',1,2,'散客步入',3,'201',4,'标准间','2019-08-06','14:00:00','2019-08-07','12:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','-1',1,'有个员工','2019-08-05',0,'','0',3,'散客单早',0.00,0.00,'pms','1','2019-08-05 16:48:13',NULL),(14,1908052899012,'',1,'Hotel','room_day',1,0,0,'张珊','','kefu@yelove.cn',1,2,'散客步入',3,'201',4,'标准间','2019-08-07','14:00:00','2019-08-08','12:00:00','2019-08-08 13:02:38','0000-00-00 00:00:00','-1',1,'有个员工','2019-08-05',0,'','0',3,'散客单早',0.00,0.00,'pms','1','2019-08-05 20:52:53',NULL),(15,1908052899012,'',1,'Hotel','room_day',1,0,0,'张珊','','kefu@yelove.cn',1,2,'散客步入',2,'102',4,'标准间','2019-08-07','14:00:00','2019-08-08','12:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','-1',1,'有个员工','2019-08-05',0,'','0',3,'散客单早',0.00,0.00,'pms','1','2019-08-05 20:52:53',NULL),(16,1908095111013,'',1,'Hotel','room_day',1,0,0,'张珊','','kefu@yelove.cn',1,2,'散客步入',3,'201',4,'标准间','2019-08-09','14:00:00','2019-08-10','12:00:00','2019-08-09 14:38:15','0000-00-00 00:00:00','-1',1,'有个员工','2019-08-09',0,'','0',3,'散客单早',0.00,0.00,'pms','1','2019-08-09 09:20:48',NULL),(17,1908092465014,'',1,'Hotel','room_day',1,0,0,'张珊','','kefu@yelove.cn',1,2,'散客步入',26,'401',4,'标准间','2019-08-09','14:00:00','2019-08-14','12:00:00','2019-08-10 10:14:00','0000-00-00 00:00:00','-1',1,'有个员工','2019-08-09',0,'','0',3,'散客单早',0.00,0.00,'pms','1','2019-08-09 17:54:11',NULL),(18,1908102892015,'',1,'Hotel','room_day',1,0,0,'张珊珊','','kefu@yelove.cn',1,2,'散客步入',3,'201',4,'标准间','2019-08-13','14:00:00','2019-08-15','12:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','-1',1,'有个员工','2019-08-10',0,'','0',3,'散客单早',0.00,0.00,'pms','1','2019-08-10 18:20:06',NULL),(19,1908108824016,'',1,'Hotel','room_day',1,0,0,'张珊','18500353881','',1,2,'散客步入',3,'201',4,'标准间','2019-08-10','14:00:00','2019-08-13','12:00:00','2019-08-11 11:03:27','0000-00-00 00:00:00','-1',1,'有个员工','2019-08-10',0,'','0',3,'散客单早',0.00,0.00,'pms','1','2019-08-10 19:12:52',NULL),(20,1908111185017,'',1,'Hotel','room_day',1,0,1,'张珊','','kefu@yelove.cn',4,19,'金卡会员',1,'103',8,'双人间','2019-08-10','14:00:00','2019-08-12','12:00:00','2019-08-11 23:01:02','0000-00-00 00:00:00','-1',1,'有个员工','2019-08-10',0,'','0',1,'金卡会员无早',0.00,0.00,'pms','1','2019-08-11 10:02:12',NULL),(21,1908149558018,'',1,'Hotel','room_day',1,0,0,'李希','','kefu@yelove.cn',1,2,'散客步入',3,'201',4,'标准间','2019-08-15','14:00:00','2019-08-17','12:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','-1',1,'有个员工','2019-08-12',0,'','0',3,'散客单早',0.00,0.00,'pms','1','2019-08-14 16:52:25',NULL),(22,1908108824016,'',1,'Hotel','room_day',1,0,0,'张珊','','kefu@yelove.cn',1,2,'散客步入',25,'301',4,'标准间','2019-08-12','14:00:00','2019-08-13','12:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','-1',1,'有个员工','2019-08-12',0,'','0',3,'散客单早',0.00,0.00,'pms','1','2019-08-21 13:56:27',NULL),(23,1908214963019,'',1,'Hotel','room_day',1,0,0,'张珊','','kefu@yelove.cn',1,2,'散客步入',1,'103',8,'双人间','2019-08-12','14:00:00','2019-08-14','12:00:00','2019-08-21 15:37:24','0000-00-00 00:00:00','-1',1,'有个员工','2019-08-12',0,'','0',3,'散客单早',0.00,0.00,'pms','1','2019-08-21 15:36:26',NULL),(24,1908214963019,'',1,'Hotel','room_day',1,0,0,'张珊','','kefu@yelove.cn',1,2,'散客步入',3,'201',4,'标准间','2019-08-12','14:00:00','2019-08-15','12:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','-1',1,'有个员工','2019-08-12',0,'','0',3,'散客单早',0.00,0.00,'pms','1','2019-08-21 15:37:07',NULL),(25,1908223931020,'',1,'Hotel','room_day',1,0,0,'张珊','','kefu@yelove.cn',1,2,'散客步入',3,'201',4,'标准间','2019-08-12','14:00:00','2019-08-13','12:00:00','2019-08-22 09:06:57','0000-00-00 00:00:00','-1',1,'有个员工','2019-08-12',0,'','0',3,'散客单早',0.00,0.00,'pms','1','2019-08-22 09:06:29',NULL),(26,1908223931020,'',1,'Hotel','room_day',1,0,0,'张珊','','kefu@yelove.cn',1,2,'散客步入',25,'301',4,'标准间','2019-08-12','14:00:00','2019-08-13','12:00:00','2019-08-22 12:43:05','0000-00-00 00:00:00','-1',1,'有个员工','2019-08-12',0,'','0',3,'散客单早',0.00,0.00,'pms','1','2019-08-22 09:54:54',NULL),(27,1908223931020,'',1,'Hotel','room_day',1,0,0,'张珊珊','','kefu@yelove.cn',1,2,'散客步入',-66449007,'',4,'标准间','2019-08-12','14:00:00','2019-08-13','12:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','-1',1,'有个员工','2019-08-12',0,'','0',3,'散客单早',0.00,0.00,'pms','1','2019-08-22 12:43:26',NULL);

/*Table structure for table `booking_discount` */

DROP TABLE IF EXISTS `booking_discount`;

CREATE TABLE `booking_discount` (
  `booking_discount_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '打折',
  `booking_detail_id` int(11) NOT NULL COMMENT '预订明细ID',
  `consume_id` int(11) NOT NULL DEFAULT '0',
  `coupons_id` int(11) NOT NULL DEFAULT '0' COMMENT '优惠卷ID',
  `discount_type` enum('1','2','4') NOT NULL COMMENT '1打折 2直减 3积分 4现金卷',
  `discount_card_number` varchar(64) NOT NULL DEFAULT '' COMMENT '优惠卡|现金卷',
  `discount_card_password` varchar(64) NOT NULL DEFAULT '' COMMENT '优惠卡密',
  `discount` float(12,2) NOT NULL COMMENT '折扣',
  `arefavorable_money` float(12,2) NOT NULL COMMENT '优惠的金额',
  `business_day` date NOT NULL,
  `valid` enum('0','1') NOT NULL DEFAULT '1',
  `add_datetime` datetime NOT NULL,
  PRIMARY KEY (`booking_discount_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `booking_discount` */

/*Table structure for table `booking_even` */

DROP TABLE IF EXISTS `booking_even`;

CREATE TABLE `booking_even` (
  `booking_even_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '维修、锁房',
  `booking_even_type` enum('lock','repair') NOT NULL,
  `company_id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `begin_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `even_node` varchar(1000) NOT NULL DEFAULT '' COMMENT '事由',
  `employee_id` int(11) NOT NULL,
  `employee_name` varchar(100) NOT NULL,
  `valid` enum('0','1','2') NOT NULL DEFAULT '1' COMMENT '0无效  1有效 2已解除',
  `add_datetime` datetime NOT NULL,
  PRIMARY KEY (`booking_even_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `booking_even` */

/*Table structure for table `booking_live_in` */

DROP TABLE IF EXISTS `booking_live_in`;

CREATE TABLE `booking_live_in` (
  `live_in_id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `booking_detail_id` int(11) NOT NULL,
  `booking_number` varchar(64) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_name` varchar(50) NOT NULL DEFAULT '',
  `member_id` int(11) NOT NULL,
  `member_name` varchar(50) NOT NULL,
  `member_mobile` varchar(100) NOT NULL DEFAULT '',
  `member_email` varchar(100) NOT NULL DEFAULT '',
  `member_sex` enum('0','1') NOT NULL DEFAULT '1',
  `member_idcard_type` enum('id_card','passport','hk_permit','tw_permit','officer_permit','other_permit') NOT NULL DEFAULT 'id_card',
  `member_idcard_number` varchar(100) NOT NULL DEFAULT '',
  `live_in_datetime` datetime DEFAULT NULL,
  `live_out_datetime` datetime DEFAULT NULL,
  `employee_id` int(11) NOT NULL,
  `employee_name` varchar(100) NOT NULL,
  `door_card` enum('0','1') NOT NULL DEFAULT '0' COMMENT '是否做门卡',
  `valid` enum('0','1','2') DEFAULT NULL,
  `add_datetime` datetime NOT NULL,
  PRIMARY KEY (`live_in_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `booking_live_in` */

insert  into `booking_live_in`(`live_in_id`,`company_id`,`channel_id`,`booking_detail_id`,`booking_number`,`item_id`,`item_name`,`member_id`,`member_name`,`member_mobile`,`member_email`,`member_sex`,`member_idcard_type`,`member_idcard_number`,`live_in_datetime`,`live_out_datetime`,`employee_id`,`employee_name`,`door_card`,`valid`,`add_datetime`) values (1,1,1,1,'1908036779701',3,'201',0,'张珊','15899656878','','1','id_card','364568196805021244','2019-08-03 23:40:25',NULL,1,'有个员工','0','1','2019-08-03 23:40:25'),(2,1,1,16,'1908095111013',3,'102',0,'张珊','15899656878','','1','id_card','364568196805021244','2019-08-09 14:38:15',NULL,1,'有个员工','0','1','2019-08-09 14:38:15'),(3,1,1,17,'1908092465014',26,'401',0,'张珊','15899656878','','1','id_card','364568196805021244','2019-08-10 10:13:59',NULL,1,'有个员工','0','1','2019-08-10 10:14:00'),(4,1,1,19,'1908108824016',3,'201',0,'张珊珊','15899656878','','1','id_card','364568196805021244','2019-08-11 11:03:27',NULL,1,'有个员工','0','1','2019-08-11 11:03:27'),(5,1,1,20,'1908111185017',1,'103',0,'张山峰','15899656878','','1','id_card','364568196805021244','2019-08-11 23:01:02',NULL,1,'有个员工','0','1','2019-08-11 23:01:02'),(6,1,1,23,'1908214963019',1,'103',0,'张珊','15899656878','','1','id_card','364568196805021244','2019-08-21 15:37:24',NULL,1,'有个员工','0','1','2019-08-21 15:37:24'),(7,1,1,25,'1908223931020',3,'201',0,'张珊','15899656878','','1','id_card','364568196805021244','2019-08-22 09:06:57',NULL,1,'有个员工','0','1','2019-08-22 09:06:57'),(8,1,1,26,'1908223931020',25,'301',0,'张珊珊','15899656878','','1','id_card','364568196805021244','2019-08-22 10:14:43',NULL,1,'有个员工','0','1','2019-08-22 10:14:43');

/*Table structure for table `booking_operation` */

DROP TABLE IF EXISTS `booking_operation`;

CREATE TABLE `booking_operation` (
  `operation_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '操作日志',
  `booking_number` bigint(19) NOT NULL,
  `company_id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `module_name` varchar(128) NOT NULL COMMENT '操作模块',
  `method` varchar(128) DEFAULT NULL,
  `request` longtext NOT NULL,
  `operation_title` varchar(300) NOT NULL COMMENT '操作标题',
  `operation_content` varchar(1000) NOT NULL DEFAULT '' COMMENT '操作内容',
  `business_day` date NOT NULL,
  `employee_id` int(11) NOT NULL,
  `employee_name` varchar(256) NOT NULL,
  `add_datetime` datetime NOT NULL,
  PRIMARY KEY (`operation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

/*Data for the table `booking_operation` */

insert  into `booking_operation`(`operation_id`,`booking_number`,`company_id`,`channel_id`,`module_id`,`module_name`,`method`,`request`,`operation_title`,`operation_content`,`business_day`,`employee_id`,`employee_name`,`add_datetime`) values (1,1908036779701,1,1,12,'客房预订','bookingRoom','{\"channel\":\"WzECYg\",\"view\":\"RoomOrder\",\"market_id\":\"2\",\"method\":\"bookingRoom\",\"channel_id\":\"1\"}{\"isBookRoom\":\"0\",\"mobile_email\":\"kefu@yelove.cn\",\"set_prices\":\"\",\"check_in\":\"2019-08-03\",\"check_out\":\"2019-08-04\",\"in_time\":\"14:00:00\",\"out_time\":\"12:00:00\",\"channel_id\":\"1\",\"channel_father_id\":\"1\",\"member_name\":\"\\u5f20\\u73ca\",\"market_name\":\"\\u6563\\u5ba2\\u6b65\\u5165\",\"market_id\":\"2\",\"market_father_id\":\"1\",\"booking_data\":{\"2019-08-03|2019-08-04\":{\"booking_room\":{\"1\":{\"4\":{\"3\":{\"room_info\":{\"item_name\":\"\",\"item_category_name\":\"\\u6807\\u51c6\\u95f4\",\"price_system_name\":\"\\u6563\\u5ba2\\u5355\\u65e9\"},\"value\":\"1\"}}}},\"booking_price\":{\"1\":{\"4\":{\"3\":{\"2019-08-03\":\"1.0\"}}}}}},\"id\":\"VDE\"}null','预订成功','预订成功','2019-08-03',1,'有个员工','2019-08-03 10:33:00'),(2,1908039214402,1,1,12,'客房预订','bookingRoom','{\"channel\":\"WzECYg\",\"view\":\"RoomOrder\",\"market_id\":\"2\",\"method\":\"bookingRoom\",\"channel_id\":\"1\"}{\"isBookRoom\":\"0\",\"mobile_email\":\"kefu@yelove.cn\",\"set_prices\":\"\",\"check_in\":\"2019-08-03\",\"check_out\":\"2019-08-04\",\"in_time\":\"14:00:00\",\"out_time\":\"12:00:00\",\"channel_id\":\"1\",\"channel_father_id\":\"1\",\"member_name\":\"\\u5f20\\u73ca\",\"market_name\":\"\\u6563\\u5ba2\\u6b65\\u5165\",\"market_id\":\"2\",\"market_father_id\":\"1\",\"booking_data\":{\"2019-08-03|2019-08-04\":{\"booking_room\":{\"1\":{\"4\":{\"3\":{\"room_info\":{\"item_name\":\"\",\"item_category_name\":\"\\u6807\\u51c6\\u95f4\",\"price_system_name\":\"\\u6563\\u5ba2\\u5355\\u65e9\"},\"value\":\"1\"}}}},\"booking_price\":{\"1\":{\"4\":{\"3\":{\"2019-08-03\":\"1.0\"}}}}}},\"id\":\"VDE\"}null','预订成功','预订成功','2019-08-03',1,'有个员工','2019-08-03 10:33:03'),(3,1908037882704,1,1,12,'客房预订','bookingRoom','{\"channel\":\"WzECYg\",\"view\":\"RoomOrder\",\"market_id\":\"2\",\"method\":\"bookingRoom\",\"channel_id\":\"1\"}{\"isBookRoom\":\"0\",\"mobile_email\":\"kefu@yelove.cn\",\"set_prices\":\"1\",\"check_in\":\"2019-08-04\",\"check_out\":\"2019-08-05\",\"in_time\":\"14:00\",\"out_time\":\"12:00\",\"channel_id\":\"1\",\"channel_father_id\":\"1\",\"member_name\":\"\\u5f20\\u73ca\",\"market_name\":\"\\u6563\\u5ba2\\u6b65\\u5165\",\"market_id\":\"2\",\"market_father_id\":\"1\",\"booking_data\":{\"2019-08-04|2019-08-05\":{\"booking_room\":{\"1\":{\"4\":{\"3\":{\"room_info\":{\"item_name\":\"\",\"item_category_name\":\"\\u6807\\u51c6\\u95f4\",\"price_system_name\":\"\\u6563\\u5ba2\\u5355\\u65e9\"},\"value\":\"1\"}}}},\"booking_price\":{\"1\":{\"4\":{\"3\":{\"2019-08-04\":\"888\"}}}}}},\"id\":\"VDE\"}null','预订成功','预订成功','2019-08-03',1,'有个员工','2019-08-03 10:33:23'),(4,1908035842305,1,1,12,'客房预订','bookingRoom','{\"channel\":\"WzECYg\",\"view\":\"RoomOrder\",\"market_id\":\"2\",\"method\":\"bookingRoom\",\"channel_id\":\"1\"}{\"isBookRoom\":\"0\",\"mobile_email\":\"kefu@yelove.cn\",\"set_prices\":\"1\",\"check_in\":\"2019-08-04\",\"check_out\":\"2019-08-05\",\"in_time\":\"14:00\",\"out_time\":\"12:00\",\"channel_id\":\"1\",\"channel_father_id\":\"1\",\"member_name\":\"\\u5f20\\u73ca\",\"market_name\":\"\\u6563\\u5ba2\\u6b65\\u5165\",\"market_id\":\"2\",\"market_father_id\":\"1\",\"booking_data\":{\"2019-08-04|2019-08-05\":{\"booking_room\":{\"1\":{\"4\":{\"3\":{\"room_info\":{\"item_name\":\"\",\"item_category_name\":\"\\u6807\\u51c6\\u95f4\",\"price_system_name\":\"\\u6563\\u5ba2\\u5355\\u65e9\"},\"value\":\"1\"}}}},\"booking_price\":{\"1\":{\"4\":{\"3\":{\"2019-08-04\":\"888\"}}}}}},\"id\":\"VDE\"}null','预订成功','预订成功','2019-08-03',1,'有个员工','2019-08-03 10:34:17'),(5,1908058128807,1,1,12,'客房预订','bookingRoom','{\"channel\":\"AWVQaw\",\"view\":\"RoomOrder\",\"market_id\":\"2\",\"method\":\"bookingRoom\",\"channel_id\":\"1\"}{\"isBookRoom\":\"0\",\"mobile_email\":\"kefu@yelove.cn\",\"set_prices\":\"\",\"check_in\":\"2019-08-05\",\"check_out\":\"2019-08-06\",\"in_time\":\"14:00:00\",\"out_time\":\"12:00:00\",\"channel_id\":\"1\",\"channel_father_id\":\"1\",\"member_name\":\"\\u5f20\\u73ca\\u73ca\",\"market_name\":\"\\u6563\\u5ba2\\u6b65\\u5165\",\"market_id\":\"2\",\"market_father_id\":\"1\",\"booking_data\":{\"2019-08-05|2019-08-06\":{\"booking_room\":{\"1\":{\"4\":{\"3\":{\"room_info\":{\"item_name\":\"\",\"item_category_name\":\"\\u6807\\u51c6\\u95f4\",\"price_system_name\":\"\\u6563\\u5ba2\\u5355\\u65e9\"},\"value\":\"1\"}}}},\"booking_price\":{\"1\":{\"4\":{\"3\":{\"2019-08-05\":\"1.0\"}}}}}},\"id\":\"Czs\"}null','预订成功','预订成功','2019-08-05',1,'有个员工','2019-08-05 16:39:52'),(6,1908057262011,1,1,12,'客房预订','bookingRoom','{\"channel\":\"AWVQaw\",\"view\":\"RoomOrder\",\"market_id\":\"2\",\"method\":\"bookingRoom\",\"channel_id\":\"1\"}{\"isBookRoom\":\"0\",\"mobile_email\":\"kefu@yelove.cn\",\"set_prices\":\"\",\"check_in\":\"2019-08-06\",\"check_out\":\"2019-08-07\",\"in_time\":\"14:00\",\"out_time\":\"12:00\",\"channel_id\":\"1\",\"channel_father_id\":\"1\",\"member_name\":\"\\u5f20\\u73ca\",\"market_name\":\"\\u6563\\u5ba2\\u6b65\\u5165\",\"market_id\":\"2\",\"market_father_id\":\"1\",\"booking_data\":{\"2019-08-06|2019-08-07\":{\"booking_room\":{\"1\":{\"4\":{\"3\":{\"room_info\":{\"item_name\":\"\",\"item_category_name\":\"\\u6807\\u51c6\\u95f4\",\"price_system_name\":\"\\u6563\\u5ba2\\u5355\\u65e9\"},\"value\":\"2\"}}}},\"booking_price\":{\"1\":{\"4\":{\"3\":{\"2019-08-06\":\"1.0\"}}}}}},\"id\":\"Czs\"}null','预订成功','预订成功','2019-08-05',1,'有个员工','2019-08-05 16:48:13'),(7,1908052899012,1,1,12,'客房预订','bookingRoom','{\"channel\":\"AWVQaw\",\"view\":\"RoomOrder\",\"market_id\":\"2\",\"method\":\"bookingRoom\",\"channel_id\":\"1\"}{\"isBookRoom\":\"0\",\"mobile_email\":\"kefu@yelove.cn\",\"set_prices\":\"\",\"check_in\":\"2019-08-07\",\"check_out\":\"2019-08-08\",\"in_time\":\"14:00\",\"out_time\":\"12:00\",\"channel_id\":\"1\",\"channel_father_id\":\"1\",\"member_name\":\"\\u5f20\\u73ca\",\"market_name\":\"\\u6563\\u5ba2\\u6b65\\u5165\",\"market_id\":\"2\",\"market_father_id\":\"1\",\"booking_data\":{\"2019-08-07|2019-08-08\":{\"booking_room\":{\"1\":{\"4\":{\"3\":{\"room_info\":{\"item_name\":\"\",\"item_category_name\":\"\\u6807\\u51c6\\u95f4\",\"price_system_name\":\"\\u6563\\u5ba2\\u5355\\u65e9\"},\"value\":\"2\"}}}},\"booking_price\":{\"1\":{\"4\":{\"3\":{\"2019-08-07\":\"1.0\"}}}}}},\"id\":\"Czs\"}null','预订成功','预订成功','2019-08-05',1,'有个员工','2019-08-05 20:52:53'),(8,1908095111013,1,1,12,'客房预订','bookingRoom','{\"channel\":\"UjYENA\",\"view\":\"RoomOrder\",\"market_id\":\"2\",\"method\":\"bookingRoom\",\"channel_id\":\"1\"}{\"isBookRoom\":\"0\",\"mobile_email\":\"kefu@yelove.cn\",\"set_prices\":\"1\",\"check_in\":\"2019-08-09\",\"check_out\":\"2019-08-10\",\"in_time\":\"14:00:00\",\"out_time\":\"12:00:00\",\"channel_id\":\"1\",\"channel_father_id\":\"1\",\"member_name\":\"\\u5f20\\u73ca\",\"market_name\":\"\\u6563\\u5ba2\\u6b65\\u5165\",\"market_id\":\"2\",\"market_father_id\":\"1\",\"booking_data\":{\"2019-08-09|2019-08-10\":{\"booking_room\":{\"1\":{\"4\":{\"3\":{\"room_info\":{\"item_name\":\"\",\"item_category_name\":\"\\u6807\\u51c6\\u95f4\",\"price_system_name\":\"\\u6563\\u5ba2\\u5355\\u65e9\"},\"value\":\"1\"}}}},\"booking_price\":{\"1\":{\"4\":{\"3\":{\"2019-08-09\":\"66\"}}}}}},\"id\":\"Uzk\"}null','预订成功','预订成功','2019-08-09',1,'有个员工','2019-08-09 09:20:48'),(9,1908092465014,1,1,12,'客房预订','bookingRoom','{\"channel\":\"UjYENA\",\"view\":\"RoomOrder\",\"market_id\":\"2\",\"method\":\"bookingRoom\",\"channel_id\":\"1\"}{\"isBookRoom\":\"0\",\"mobile_email\":\"kefu@yelove.cn\",\"set_prices\":\"\",\"check_in\":\"2019-08-09\",\"check_out\":\"2019-08-14\",\"in_time\":\"14:00\",\"out_time\":\"12:00\",\"channel_id\":\"1\",\"channel_father_id\":\"1\",\"member_name\":\"\\u5f20\\u73ca\",\"market_name\":\"\\u6563\\u5ba2\\u6b65\\u5165\",\"market_id\":\"2\",\"market_father_id\":\"1\",\"booking_data\":{\"2019-08-09|2019-08-14\":{\"booking_room\":{\"1\":{\"4\":{\"3\":{\"room_info\":{\"item_name\":\"\",\"item_category_name\":\"\\u6807\\u51c6\\u95f4\",\"price_system_name\":\"\\u6563\\u5ba2\\u5355\\u65e9\"},\"value\":\"1\"}}}},\"booking_price\":{\"1\":{\"4\":{\"3\":{\"2019-08-09\":\"1.0\",\"2019-08-10\":\"1.0\",\"2019-08-11\":\"1.0\",\"2019-08-12\":\"1.0\",\"2019-08-13\":\"1.0\"}}}}}},\"id\":\"Uzk\"}null','预订成功','预订成功','2019-08-09',1,'有个员工','2019-08-09 17:54:11'),(10,1908102892015,1,1,12,'客房预订','bookingRoom','{\"channel\":\"VGVSMw\",\"view\":\"RoomOrder\",\"market_id\":\"2\",\"method\":\"bookingRoom\",\"channel_id\":\"1\"}{\"isBookRoom\":\"0\",\"mobile_email\":\"kefu@yelove.cn\",\"set_prices\":\"\",\"check_in\":\"2019-08-13\",\"check_out\":\"2019-08-15\",\"in_time\":\"14:00\",\"out_time\":\"12:00\",\"channel_id\":\"1\",\"channel_father_id\":\"1\",\"member_name\":\"\\u5f20\\u73ca\\u73ca\",\"market_name\":\"\\u6563\\u5ba2\\u6b65\\u5165\",\"market_id\":\"2\",\"market_father_id\":\"1\",\"booking_data\":{\"2019-08-13|2019-08-15\":{\"booking_room\":{\"1\":{\"4\":{\"3\":{\"room_info\":{\"item_name\":\"\",\"item_category_name\":\"\\u6807\\u51c6\\u95f4\",\"price_system_name\":\"\\u6563\\u5ba2\\u5355\\u65e9\"},\"value\":\"1\"}}}},\"booking_price\":{\"1\":{\"4\":{\"3\":{\"2019-08-13\":\"1.0\",\"2019-08-14\":\"1.0\"}}}}}},\"id\":\"BjM\"}null','预订成功','预订成功','2019-08-10',1,'有个员工','2019-08-10 18:20:06'),(11,1908108824016,1,1,12,'客房预订','bookingRoom','{\"channel\":\"VGVSMw\",\"view\":\"RoomOrder\",\"market_id\":\"2\",\"method\":\"bookingRoom\",\"channel_id\":\"1\"}{\"isBookRoom\":\"0\",\"mobile_email\":\"18500353881\",\"set_prices\":\"\",\"check_in\":\"2019-08-10\",\"check_out\":\"2019-08-13\",\"in_time\":\"14:00\",\"out_time\":\"12:00\",\"channel_id\":\"1\",\"channel_father_id\":\"1\",\"member_name\":\"\\u5f20\\u73ca\",\"market_name\":\"\\u6563\\u5ba2\\u6b65\\u5165\",\"market_id\":\"2\",\"market_father_id\":\"1\",\"booking_data\":{\"2019-08-10|2019-08-13\":{\"booking_room\":{\"1\":{\"4\":{\"3\":{\"room_info\":{\"item_name\":\"\",\"item_category_name\":\"\\u6807\\u51c6\\u95f4\",\"price_system_name\":\"\\u6563\\u5ba2\\u5355\\u65e9\"},\"value\":\"1\"}}}},\"booking_price\":{\"1\":{\"4\":{\"3\":{\"2019-08-10\":\"1.0\",\"2019-08-11\":\"1.0\",\"2019-08-12\":\"1.0\"}}}}}},\"id\":\"BjM\"}null','预订成功','预订成功','2019-08-10',1,'有个员工','2019-08-10 19:12:52'),(12,1908111185017,1,1,12,'客房预订','bookingRoom','{\"channel\":\"VGlUNw\",\"view\":\"RoomOrder\",\"market_id\":\"2\",\"method\":\"bookingRoom\",\"channel_id\":\"1\"}{\"isBookRoom\":\"0\",\"mobile_email\":\"kefu@yelove.cn\",\"set_prices\":\"\",\"check_in\":\"2019-08-10\",\"check_out\":\"2019-08-12\",\"in_time\":\"14:00\",\"out_time\":\"12:00\",\"channel_id\":\"1\",\"channel_father_id\":\"1\",\"member_name\":\"\\u5f20\\u73ca\",\"member_id\":\"1\",\"market_name\":\"\\u91d1\\u5361\\u4f1a\\u5458\",\"market_id\":\"19\",\"market_father_id\":\"4\",\"booking_data\":{\"2019-08-10|2019-08-12\":{\"booking_room\":{\"1\":{\"8\":{\"1\":{\"room_info\":{\"item_name\":\"\",\"item_category_name\":\"\\u53cc\\u4eba\\u95f4\",\"price_system_name\":\"\\u91d1\\u5361\\u4f1a\\u5458\\u65e0\\u65e9\"},\"value\":\"1\"}}}},\"booking_price\":{\"1\":{\"8\":{\"1\":{\"2019-08-10\":\"2\",\"2019-08-11\":\"2\"}}}}}},\"id\":\"ADE\"}null','预订成功','预订成功','2019-08-10',1,'有个员工','2019-08-11 10:02:12'),(13,1908149558018,1,1,12,'客房预订','bookingRoom','{\"channel\":\"CzBXNA\",\"view\":\"RoomOrder\",\"market_id\":\"2\",\"method\":\"bookingRoom\",\"channel_id\":\"1\"}{\"isBookRoom\":\"0\",\"mobile_email\":\"kefu@yelove.cn\",\"set_prices\":\"\",\"check_in\":\"2019-08-15\",\"check_out\":\"2019-08-17\",\"in_time\":\"14:00\",\"out_time\":\"12:00\",\"channel_id\":\"1\",\"channel_father_id\":\"1\",\"member_name\":\"\\u674e\\u5e0c\",\"market_name\":\"\\u6563\\u5ba2\\u6b65\\u5165\",\"market_id\":\"2\",\"market_father_id\":\"1\",\"booking_data\":{\"2019-08-15|2019-08-17\":{\"booking_room\":{\"1\":{\"4\":{\"3\":{\"room_info\":{\"item_name\":\"\",\"item_category_name\":\"\\u6807\\u51c6\\u95f4\",\"price_system_name\":\"\\u6563\\u5ba2\\u5355\\u65e9\"},\"value\":\"1\"}}}},\"booking_price\":{\"1\":{\"4\":{\"3\":{\"2019-08-15\":\"1.0\",\"2019-08-16\":\"1.0\"}}}}}},\"id\":\"B2I\"}null','预订成功','预订成功','2019-08-12',1,'有个员工','2019-08-14 16:52:25'),(14,1908108824016,1,1,13,'客房房态','revokesOperations','{\"channel\":\"CT0GMg\",\"view\":\"RoomStatus\",\"method\":\"revokesOperations\",\"channel_id\":1}{\"c_id\":\"AGVWPg\",\"revokes\":\"consume\",\"consume_title\":\"\\u623f\\u8d39\",\"item_name\":\"201\",\"business_day\":\"2019-08-11\",\"booking_number\":\"1908108824016\"}null','取消消费','取消房号:201 营业日:2019-08-11 的房费','2019-08-12',1,'有个员工','2019-08-21 09:46:55'),(15,1908108824016,1,1,13,'客房房态','revokesOperations','{\"channel\":\"CT0GMg\",\"view\":\"RoomStatus\",\"method\":\"revokesOperations\",\"channel_id\":1}{\"ba_id\":\"BmEEZA\",\"payment_name\":\"\\u73b0\\u91d1\\u652f\\u4ed8\",\"accounts_type\":\"receipts\",\"revokes\":\"account\",\"item_name\":\"201\",\"business_day\":\"2019-08-12\",\"booking_number\":\"1908108824016\"}null','取消账务','取消房号:201 营业日:2019-08-12 的现金支付 , 类型:收款','2019-08-12',1,'有个员工','2019-08-21 10:01:23'),(16,1908108824016,1,1,13,'客房房态','revokesOperations','{\"channel\":\"CT0GMg\",\"view\":\"RoomStatus\",\"method\":\"revokesOperations\",\"channel_id\":1}{\"bb_id\":\"AWY\",\"borrowing_name\":\"\\u5929\\u5802\\u4f1e\\u5c0f\\u53f7\",\"borrowing_num\":\"1\",\"revokes\":\"borrow\",\"item_name\":\"201\",\"booking_number\":\"1908108824016\"}null','归还借物','房号:201 营业日:，归还借物天堂伞小号 ， 数量:1，押金','2019-08-12',1,'有个员工','2019-08-21 10:21:42'),(17,1908108824016,1,1,12,'客房预订','bookingRoom','{\"channel\":\"AzcCNw\",\"view\":\"RoomStatus\",\"market_id\":\"2\",\"method\":\"bookingRoom\",\"channel_id\":\"1\"}{\"isBookRoom\":\"0\",\"mobile_email\":\"kefu@yelove.cn\",\"set_prices\":\"\",\"check_in\":\"2019-08-12\",\"check_out\":\"2019-08-13\",\"in_time\":\"14:00\",\"out_time\":\"12:00\",\"channel_id\":\"1\",\"channel_father_id\":\"1\",\"member_name\":\"\\u5f20\\u73ca\",\"market_name\":\"\\u6563\\u5ba2\\u6b65\\u5165\",\"market_id\":\"2\",\"market_father_id\":\"1\",\"booking_data\":{\"2019-08-12|2019-08-13\":{\"booking_room\":{\"1\":{\"4\":{\"3\":{\"room_info\":{\"item_name\":\"\",\"item_category_name\":\"\\u6807\\u51c6\\u95f4\",\"price_system_name\":\"\\u6563\\u5ba2\\u5355\\u65e9\"},\"value\":\"1\"}}}},\"booking_price\":{\"1\":{\"4\":{\"3\":{\"2019-08-12\":\"1.0\"}}}}}},\"id\":\"DDc\",\"booking_number\":\"1908108824016\",\"book_id\":\"UTYCawZtX2AFYlJhDDZUbFZjU2EDMlRqA2M\"}null','预订成功','预订成功','2019-08-12',1,'有个员工','2019-08-21 13:56:27'),(18,1908214963019,1,1,12,'客房预订','bookingRoom','{\"channel\":\"AzcCNw\",\"view\":\"RoomStatus\",\"market_id\":\"2\",\"method\":\"bookingRoom\",\"channel_id\":\"1\"}{\"isBookRoom\":\"1\",\"mobile_email\":\"kefu@yelove.cn\",\"set_prices\":\"\",\"check_in\":\"2019-08-12\",\"check_out\":\"2019-08-14\",\"in_time\":\"14:00\",\"out_time\":\"12:00\",\"channel_id\":\"1\",\"channel_father_id\":\"1\",\"member_name\":\"\\u5f20\\u73ca\",\"market_name\":\"\\u6563\\u5ba2\\u6b65\\u5165\",\"market_id\":\"2\",\"market_father_id\":\"1\",\"booking_data\":{\"2019-08-12|2019-08-14\":{\"booking_room\":{\"1\":{\"8\":{\"3\":{\"value\":\"103\",\"room_info\":{\"item_name\":\"103\",\"item_id\":\"1\",\"item_category_name\":\"\\u53cc\\u4eba\\u95f4\",\"price_system_name\":\"\\u6563\\u5ba2\\u5355\\u65e9\"}}}}},\"booking_price\":{\"1\":{\"8\":{\"3\":{\"2019-08-12\":\"2.0\",\"2019-08-13\":\"2.0\"}}}}}},\"id\":\"DDc\"}null','预订成功','预订成功','2019-08-12',1,'有个员工','2019-08-21 15:36:26'),(19,1908214963019,1,1,12,'客房预订','bookingRoom','{\"channel\":\"AzcCNw\",\"view\":\"RoomStatus\",\"market_id\":\"2\",\"method\":\"bookingRoom\",\"channel_id\":\"1\"}{\"isBookRoom\":\"0\",\"mobile_email\":\"kefu@yelove.cn\",\"set_prices\":\"\",\"check_in\":\"2019-08-12\",\"check_out\":\"2019-08-15\",\"in_time\":\"14:00\",\"out_time\":\"12:00\",\"channel_id\":\"1\",\"channel_father_id\":\"1\",\"member_name\":\"\\u5f20\\u73ca\",\"market_name\":\"\\u6563\\u5ba2\\u6b65\\u5165\",\"market_id\":\"2\",\"market_father_id\":\"1\",\"booking_data\":{\"2019-08-12|2019-08-15\":{\"booking_room\":{\"1\":{\"4\":{\"3\":{\"room_info\":{\"item_name\":\"\",\"item_category_name\":\"\\u6807\\u51c6\\u95f4\",\"price_system_name\":\"\\u6563\\u5ba2\\u5355\\u65e9\"},\"value\":\"1\"}}}},\"booking_price\":{\"1\":{\"4\":{\"3\":{\"2019-08-12\":\"1.0\",\"2019-08-13\":\"1.0\",\"2019-08-14\":\"1.0\"}}}}}},\"id\":\"DDc\",\"booking_number\":\"1908214963019\",\"book_id\":\"D2gDaloxVWoDZ1VnAzVWb1RlVGEENQ8xWzQ\"}null','预订成功','预订成功','2019-08-12',1,'有个员工','2019-08-21 15:37:07'),(20,1908223931020,1,1,12,'客房预订','bookingRoom','{\"channel\":\"A2VcMQ\",\"view\":\"RoomStatus\",\"market_id\":\"2\",\"method\":\"bookingRoom\",\"channel_id\":\"1\"}{\"isBookRoom\":\"1\",\"mobile_email\":\"kefu@yelove.cn\",\"set_prices\":\"\",\"check_in\":\"2019-08-12\",\"check_out\":\"2019-08-13\",\"in_time\":\"14:00\",\"out_time\":\"12:00\",\"channel_id\":\"1\",\"channel_father_id\":\"1\",\"member_name\":\"\\u5f20\\u73ca\",\"market_name\":\"\\u6563\\u5ba2\\u6b65\\u5165\",\"market_id\":\"2\",\"market_father_id\":\"1\",\"booking_data\":{\"2019-08-12|2019-08-13\":{\"booking_room\":{\"1\":{\"4\":{\"3\":{\"value\":\"201\",\"room_info\":{\"item_name\":\"201\",\"item_id\":\"3\",\"item_category_name\":\"\\u6807\\u51c6\\u95f4\",\"price_system_name\":\"\\u6563\\u5ba2\\u5355\\u65e9\"}}}}},\"booking_price\":{\"1\":{\"4\":{\"3\":{\"2019-08-12\":\"1.0\"}}}}}},\"id\":\"A2Q\"}null','预订成功','预订成功','2019-08-12',1,'有个员工','2019-08-22 09:06:29'),(21,1908223931020,1,1,12,'客房预订','bookingRoom','{\"channel\":\"A2VcMQ\",\"view\":\"RoomStatus\",\"market_id\":\"2\",\"method\":\"bookingRoom\",\"channel_id\":\"1\"}{\"isBookRoom\":\"0\",\"mobile_email\":\"kefu@yelove.cn\",\"set_prices\":\"\",\"check_in\":\"2019-08-12\",\"check_out\":\"2019-08-13\",\"in_time\":\"14:00\",\"out_time\":\"12:00\",\"channel_id\":\"1\",\"channel_father_id\":\"1\",\"member_name\":\"\\u5f20\\u73ca\",\"market_name\":\"\\u6563\\u5ba2\\u6b65\\u5165\",\"market_id\":\"2\",\"market_father_id\":\"1\",\"booking_data\":{\"2019-08-12|2019-08-13\":{\"booking_room\":{\"1\":{\"4\":{\"3\":{\"room_info\":{\"item_name\":\"\",\"item_category_name\":\"\\u6807\\u51c6\\u95f4\",\"price_system_name\":\"\\u6563\\u5ba2\\u5355\\u65e9\"},\"value\":\"1\"}}}},\"booking_price\":{\"1\":{\"4\":{\"3\":{\"2019-08-12\":\"1.0\"}}}}}},\"id\":\"A2Q\",\"booking_number\":\"1908223931020\",\"book_id\":\"UGcGbAFnBmQEMwE0DToCbFEyAGYGYQY8UDc\"}null','预订成功','预订成功','2019-08-12',1,'有个员工','2019-08-22 09:54:54'),(22,1908223931020,1,1,12,'客房预订','bookingRoom','{\"channel\":\"A2VcMQ\",\"view\":\"RoomStatus\",\"market_id\":\"2\",\"method\":\"bookingRoom\",\"channel_id\":\"1\"}{\"isBookRoom\":\"0\",\"mobile_email\":\"kefu@yelove.cn\",\"set_prices\":\"\",\"check_in\":\"2019-08-12\",\"check_out\":\"2019-08-13\",\"in_time\":\"14:00\",\"out_time\":\"12:00\",\"channel_id\":\"1\",\"channel_father_id\":\"1\",\"member_name\":\"\\u5f20\\u73ca\\u73ca\",\"market_name\":\"\\u6563\\u5ba2\\u6b65\\u5165\",\"market_id\":\"2\",\"market_father_id\":\"1\",\"booking_data\":{\"2019-08-12|2019-08-13\":{\"booking_room\":{\"1\":{\"4\":{\"3\":{\"room_info\":{\"item_name\":\"\",\"item_category_name\":\"\\u6807\\u51c6\\u95f4\",\"price_system_name\":\"\\u6563\\u5ba2\\u5355\\u65e9\"},\"value\":\"1\"}}}},\"booking_price\":{\"1\":{\"4\":{\"3\":{\"2019-08-12\":\"1.0\"}}}}}},\"id\":\"A2Q\",\"booking_number\":\"1908223931020\",\"book_id\":\"UWZVPwRiUTNVYgI3VmFSPFIxA2VXMAkzC2w\"}null','预订成功','预订成功','2019-08-12',1,'有个员工','2019-08-22 12:43:26');

/*Table structure for table `booking_receivable` */

DROP TABLE IF EXISTS `booking_receivable`;

CREATE TABLE `booking_receivable` (
  `booking_receivable_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '回款ID',
  `receivable_id` int(11) NOT NULL COMMENT '应收账户ID',
  `receivable_payback` float(8,2) NOT NULL COMMENT '回款金额',
  `payment_id` int(11) NOT NULL COMMENT '支付方式',
  `payment_name` varchar(128) NOT NULL,
  `payment_father_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL COMMENT '操作员',
  `employee_name` varchar(128) NOT NULL,
  `valid` enum('0','1') NOT NULL DEFAULT '1' COMMENT '是否有效',
  `add_datetime` datetime NOT NULL,
  PRIMARY KEY (`booking_receivable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `booking_receivable` */

/*Table structure for table `channel` */

DROP TABLE IF EXISTS `channel`;

CREATE TABLE `channel` (
  `channel_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '酒店、餐厅等ID',
  `channel` enum('Hotel','Meal','Meeting','Shop','Service','Tour','Sport') NOT NULL COMMENT '频道',
  `channel_father_id` int(11) NOT NULL DEFAULT '0' COMMENT '隶属于ID',
  `company_chairman` enum('0','1') NOT NULL DEFAULT '0' COMMENT '集团主席/总部',
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `channel_name` varchar(100) NOT NULL COMMENT 'channel名称',
  `channel_en_name` varchar(200) NOT NULL DEFAULT '' COMMENT '英文名称',
  `channel_short_name` varchar(200) NOT NULL DEFAULT '' COMMENT '简称',
  `channel_type` varchar(100) NOT NULL DEFAULT '' COMMENT '类型',
  `business_day` enum('0') NOT NULL DEFAULT '0' COMMENT '是否啟用夜審制度',
  `phone` varchar(20) NOT NULL DEFAULT '' COMMENT '电话',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '移动电话',
  `email` varchar(20) NOT NULL DEFAULT '' COMMENT 'email',
  `fax` varchar(20) NOT NULL DEFAULT '' COMMENT 'fax',
  `longitude` varchar(50) NOT NULL DEFAULT '' COMMENT '经度',
  `latitude` varchar(50) NOT NULL DEFAULT '' COMMENT '纬度',
  `country` varchar(50) NOT NULL DEFAULT '' COMMENT '国家',
  `province` varchar(50) NOT NULL DEFAULT '' COMMENT '省',
  `city` varchar(50) NOT NULL DEFAULT '' COMMENT '市、县',
  `town` varchar(50) NOT NULL DEFAULT '' COMMENT '城镇',
  `address` varchar(200) NOT NULL DEFAULT '' COMMENT '地址',
  `address_en` varchar(255) NOT NULL DEFAULT '' COMMENT '地址英文',
  `images` varchar(1000) NOT NULL DEFAULT '' COMMENT '图片',
  `star` tinyint(3) NOT NULL DEFAULT '0' COMMENT '星级',
  `wifi` enum('0','1') NOT NULL DEFAULT '1' COMMENT 'wifi',
  `web` varchar(255) NOT NULL DEFAULT '' COMMENT 'web',
  `valid` enum('0','1') NOT NULL DEFAULT '1' COMMENT '是否有效',
  `add_datetime` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`channel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `channel` */

insert  into `channel`(`channel_id`,`channel`,`channel_father_id`,`company_chairman`,`company_id`,`channel_name`,`channel_en_name`,`channel_short_name`,`channel_type`,`business_day`,`phone`,`mobile`,`email`,`fax`,`longitude`,`latitude`,`country`,`province`,`city`,`town`,`address`,`address_en`,`images`,`star`,`wifi`,`web`,`valid`,`add_datetime`) values (1,'Hotel',1,'0',1,'有间酒店叫酒店','Have A Hotel','有间酒店','','0','989-12344556','18733659865','kefu@yelove.cn','7486378876','116.357612','40.035605','86','110000','110100','110108','北京市海淀区清景园南门-公交车站','','',0,'1','www.myhotel.om','1','2018-04-01 18:43:57'),(2,'Meal',1,'0',1,'有间餐馆吧','Have A Bar','有间餐馆','','0','989-12344556','18733659865','kefu@yelove.cn','7486378876','121.48716','31.233188','86','110000','110100','110108','上海市黄浦区哦加哦网络科技(上海)有限公司','','',6,'1','www.myBar.com','1','2018-04-01 21:30:19'),(3,'Sport',1,'0',1,'健身娱乐吧','Have A sport','健身娱乐','','0','','','','','116.4122','40.078522','86','110000','110100','110108','北京市昌平区迈高国际运动工厂(天通苑店)','','',0,'1','','1','2018-05-08 17:35:00'),(4,'Hotel',1,'0',1,'还有1间酒店','and the hotel','还有酒店','','0','','','','','116.30403','39.97399','86','110000','110100','110108','北京市海淀区北京海淀区国家税务局','','',2,'1','www.andthehotel.com','1','2018-05-30 15:31:26'),(5,'Hotel',1,'0',1,'集团酒店','jitian hotel','jitian hotel','','0','','','','','121.44657','31.236072','86','310000','310100','310110','上海市静安区东京屋(武定路店)','','',0,'1','','1','2018-06-01 17:38:43'),(6,'Shop',1,'0',1,'有间商店','have a shop','有间酒店','','0','','','','','112.584182','37.85327','86','140000','140100','140105','太原市迎泽区山西省人民医院','','',0,'1','','1','2018-09-17 00:42:30');

/*Table structure for table `channel_attribute` */

DROP TABLE IF EXISTS `channel_attribute`;

CREATE TABLE `channel_attribute` (
  `attribute_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '属性ID',
  `channel_config` enum('self','layout','room','cuisine','table','delivery') NOT NULL COMMENT '属性类型',
  `company_id` int(11) NOT NULL DEFAULT '0' COMMENT '0通用 公司/品牌',
  `channel_id` int(11) NOT NULL DEFAULT '0' COMMENT '频道ID',
  `item_id` int(11) NOT NULL DEFAULT '0' COMMENT '属于ITEM',
  `attribute_father_id` int(11) NOT NULL DEFAULT '0' COMMENT '父类',
  `attribute_name` varchar(100) NOT NULL COMMENT '属性名称',
  `attribute_en_name` varchar(100) NOT NULL DEFAULT '' COMMENT '属性英文名称',
  `input_type` enum('text','redio','boolean','checkbox','select','number','extend_text') NOT NULL DEFAULT 'text' COMMENT '输入类型',
  `valid` enum('0','1') NOT NULL DEFAULT '1' COMMENT '是否有效',
  `add_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`attribute_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Data for the table `channel_attribute` */

insert  into `channel_attribute`(`attribute_id`,`channel_config`,`company_id`,`channel_id`,`item_id`,`attribute_father_id`,`attribute_name`,`attribute_en_name`,`input_type`,`valid`,`add_datetime`) values (1,'layout',0,0,0,0,'房型类型','','checkbox','1','0000-00-00 00:00:00'),(2,'layout',0,0,0,0,'基础配置','','text','1','2018-04-04 13:42:25'),(3,'layout',0,0,0,2,'床型','','checkbox','1','2018-04-04 14:16:42'),(4,'layout',0,0,0,3,'宽度','','checkbox','1','0000-00-00 00:00:00'),(5,'layout',0,0,0,3,'数量','','number','1','0000-00-00 00:00:00'),(6,'layout',0,0,0,2,'空调','','checkbox','1','0000-00-00 00:00:00'),(7,'layout',0,0,0,2,'WiFi','','boolean','1','0000-00-00 00:00:00'),(8,'layout',0,0,0,2,'卫生间','','checkbox','1','0000-00-00 00:00:00'),(9,'layout',0,0,0,2,'窗口','','checkbox','1','0000-00-00 00:00:00'),(10,'layout',0,0,0,2,'特色配置','','extend_text','1','0000-00-00 00:00:00');

/*Table structure for table `channel_borrowing` */

DROP TABLE IF EXISTS `channel_borrowing`;

CREATE TABLE `channel_borrowing` (
  `borrowing_id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `channel` enum('Hotel','Meal') NOT NULL,
  `channel_id` int(11) NOT NULL,
  `borrowing_name` varchar(128) NOT NULL COMMENT '借物名称',
  `borrowing_en_name` varchar(128) NOT NULL DEFAULT '' COMMENT '英文名称',
  `borrowing_price` float(8,2) NOT NULL COMMENT '借物(价值)押金',
  `borrowing_tag` varchar(64) NOT NULL DEFAULT '',
  `borrowing_stock` int(11) NOT NULL COMMENT '库存',
  `borrowing_have` int(11) NOT NULL DEFAULT '0' COMMENT '已借',
  `borrowing_describe` varchar(500) NOT NULL DEFAULT '',
  `valid` enum('0','1') NOT NULL DEFAULT '1' COMMENT '有效无效',
  `add_datetime` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`borrowing_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `channel_borrowing` */

insert  into `channel_borrowing`(`borrowing_id`,`company_id`,`channel`,`channel_id`,`borrowing_name`,`borrowing_en_name`,`borrowing_price`,`borrowing_tag`,`borrowing_stock`,`borrowing_have`,`borrowing_describe`,`valid`,`add_datetime`) values (1,1,'Hotel',1,'天堂伞小号','umbrella',20.00,'雨伞',9,0,'雨伞','1','2019-06-09 11:47:33'),(2,1,'Hotel',1,'天堂伞大号','umbrella',30.00,'雨伞',5,0,'天堂伞大号','1','2019-06-09 13:53:52');

/*Table structure for table `channel_business_day` */

DROP TABLE IF EXISTS `channel_business_day`;

CREATE TABLE `channel_business_day` (
  `business_day_id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `business_day` date NOT NULL,
  `add_datetime` datetime NOT NULL,
  PRIMARY KEY (`business_day_id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

/*Data for the table `channel_business_day` */

insert  into `channel_business_day`(`business_day_id`,`company_id`,`channel_id`,`business_day`,`add_datetime`) values (1,1,1,'2019-08-08','2019-08-08 14:55:28'),(2,1,1,'2019-08-09','2019-08-09 00:34:01'),(3,1,1,'2019-08-10','2019-08-10 17:13:10'),(4,1,1,'2019-08-11','2019-08-11 23:35:02'),(5,1,1,'2019-08-12','2019-08-12 10:27:27'),(6,1,1,'2019-08-13','2019-09-16 10:15:35'),(7,1,1,'2019-08-14','2019-09-16 10:15:48'),(8,1,1,'2019-08-15','2019-09-16 10:17:29'),(9,1,1,'2019-08-16','2019-09-16 10:17:31'),(10,1,1,'2019-08-17','2019-09-16 10:17:36'),(11,1,1,'2019-08-18','2019-09-16 10:17:39'),(12,1,1,'2019-08-19','2019-09-16 10:17:41'),(13,1,1,'2019-08-20','2019-09-16 10:17:42'),(14,1,1,'2019-08-21','2019-09-16 10:17:44'),(15,1,1,'2019-08-22','2019-09-16 10:17:47'),(16,1,1,'2019-08-23','2019-09-16 10:17:49'),(17,1,1,'2019-08-24','2019-09-16 10:17:52'),(18,1,1,'2019-08-25','2019-09-16 10:17:57'),(19,1,1,'2019-08-26','2019-09-16 10:18:02'),(20,1,1,'2019-08-27','2019-09-16 10:18:06'),(21,1,1,'2019-08-28','2019-09-16 10:18:10'),(22,1,1,'2019-08-29','2019-09-16 10:18:12'),(23,1,1,'2019-08-30','2019-09-16 10:18:13'),(24,1,1,'2019-08-31','2019-09-16 10:18:14'),(25,1,1,'2019-09-01','2019-09-16 10:18:16'),(26,1,1,'2019-09-02','2019-09-16 10:18:17'),(27,1,1,'2019-09-03','2019-09-16 10:19:20'),(28,1,1,'2019-09-04','2019-09-16 10:19:22'),(29,1,1,'2019-09-05','2019-09-16 10:19:23'),(30,1,1,'2019-09-06','2019-09-16 10:19:25'),(31,1,1,'2019-09-07','2019-09-16 10:19:26'),(32,1,1,'2019-09-08','2019-09-16 10:19:33'),(33,1,1,'2019-09-09','2019-09-16 10:19:34'),(34,1,1,'2019-09-10','2019-09-16 10:19:36'),(35,1,1,'2019-09-11','2019-09-16 10:19:37'),(36,1,1,'2019-09-12','2019-09-16 10:19:39'),(37,1,1,'2019-09-13','2019-09-16 10:19:40'),(38,1,1,'2019-09-14','2019-09-16 10:19:41'),(39,1,1,'2019-09-15','2019-09-16 10:19:43'),(40,1,1,'2019-09-16','2019-09-16 10:19:50'),(41,1,1,'2019-09-17','2019-09-17 00:02:02'),(42,1,1,'2019-09-18','2019-09-29 08:05:29'),(43,1,1,'2019-09-19','2019-09-29 08:05:32'),(44,1,1,'2019-09-20','2019-09-29 08:05:34'),(45,1,1,'2019-09-21','2019-09-29 08:05:35'),(46,1,1,'2019-09-22','2019-09-29 08:05:37'),(47,1,1,'2019-09-23','2019-09-29 08:05:39'),(48,1,1,'2019-09-24','2019-09-29 08:05:41'),(49,1,1,'2019-09-25','2019-09-29 08:05:44'),(50,1,1,'2019-09-26','2019-09-29 08:05:48');

/*Table structure for table `channel_cancellation_policy` */

DROP TABLE IF EXISTS `channel_cancellation_policy`;

CREATE TABLE `channel_cancellation_policy` (
  `policy_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `policy_name` varchar(100) NOT NULL COMMENT '取消政策名称',
  `policy_en_name` varchar(100) NOT NULL DEFAULT '' COMMENT '英文名称',
  `channel` enum('Hotel','Meal','Meeting','Shop','Service','Tour','Sport') NOT NULL DEFAULT 'Hotel',
  `company_id` int(11) NOT NULL COMMENT '公司/品牌ID',
  `channel_id` int(11) NOT NULL DEFAULT '0' COMMENT '频道ID',
  `rules` enum('night','percent','cash') NOT NULL COMMENT '规则',
  `rules_value` int(11) NOT NULL DEFAULT '0' COMMENT '规则',
  `rules_days` int(11) DEFAULT NULL COMMENT '提前几天',
  `rules_time` time DEFAULT NULL COMMENT '几点前生效',
  `begin_datetime` varchar(20) DEFAULT NULL COMMENT '生效时间',
  `end_datetime` varchar(20) DEFAULT NULL COMMENT '结束时间',
  `policy_type` enum('layout') NOT NULL DEFAULT 'layout' COMMENT '规则类型',
  `valid` enum('0','1') NOT NULL DEFAULT '1' COMMENT '是否生效',
  `add_datetime` datetime NOT NULL,
  PRIMARY KEY (`policy_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `channel_cancellation_policy` */

insert  into `channel_cancellation_policy`(`policy_id`,`policy_name`,`policy_en_name`,`channel`,`company_id`,`channel_id`,`rules`,`rules_value`,`rules_days`,`rules_time`,`begin_datetime`,`end_datetime`,`policy_type`,`valid`,`add_datetime`) values (1,'不可取消','Cannot be canceled','Hotel',1,0,'percent',100,NULL,NULL,NULL,NULL,'layout','1','2018-06-04 15:36:36'),(2,'18点前免费取消','Free cancellation before 18 o\'clock','Hotel',1,0,'night',1,NULL,'18:00:00',NULL,NULL,'layout','1','2018-06-04 15:40:48');

/*Table structure for table `channel_commision` */

DROP TABLE IF EXISTS `channel_commision`;

CREATE TABLE `channel_commision` (
  `company_id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `market_id` int(11) NOT NULL COMMENT '市场ID',
  `price_system_id` int(11) NOT NULL,
  `commision_type` enum('0','1','2','3') NOT NULL COMMENT '佣金类型 1底价收款 2后付佣金',
  `commision_form` enum('percent','night','manually') NOT NULL COMMENT '佣金形式 百分比 间夜 手动',
  `commision_form_value` float(8,2) DEFAULT NULL COMMENT '佣金形式 值',
  `valid` enum('0','1') NOT NULL DEFAULT '1',
  `add_datetime` datetime NOT NULL,
  PRIMARY KEY (`company_id`,`channel_id`,`market_id`,`price_system_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `channel_commision` */

insert  into `channel_commision`(`company_id`,`channel_id`,`market_id`,`price_system_id`,`commision_type`,`commision_form`,`commision_form_value`,`valid`,`add_datetime`) values (1,1,2,3,'1','percent',5.00,'1','2019-07-30 16:33:09'),(1,1,3,5,'2','night',1.00,'1','2019-08-08 01:17:39');

/*Table structure for table `channel_consume` */

DROP TABLE IF EXISTS `channel_consume`;

CREATE TABLE `channel_consume` (
  `channel_consume_id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_consume_father_id` int(11) NOT NULL DEFAULT '0',
  `channel` enum('commom','Hotel','Meal','Meeting','Shop','Service','Tour','Sport') NOT NULL DEFAULT 'Hotel',
  `company_id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `consume_title` varchar(100) NOT NULL COMMENT '消费标题',
  `consume_title_en` varchar(100) NOT NULL DEFAULT '',
  `consume_code` varchar(50) NOT NULL COMMENT '消费代码 消费类型 对应booking_type',
  `consume_price` float(8,2) NOT NULL DEFAULT '0.00' COMMENT '价格 0为免费 -1自定义 -99父类',
  `consume_unit` varchar(50) NOT NULL DEFAULT '' COMMENT '单位',
  `valid` enum('-1','0','1') NOT NULL DEFAULT '1' COMMENT '有效无效 -1删除',
  `add_datetime` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`channel_consume_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Data for the table `channel_consume` */

insert  into `channel_consume`(`channel_consume_id`,`channel_consume_father_id`,`channel`,`company_id`,`channel_id`,`consume_title`,`consume_title_en`,`consume_code`,`consume_price`,`consume_unit`,`valid`,`add_datetime`) values (0,0,'commom',0,0,'新类别','New type','new ',0.00,'元','-1','0000-00-00 00:00:00'),(1,1,'Hotel',0,0,'房费','Room rate','room_rate',-1.00,'元','1','0000-00-00 00:00:00'),(2,1,'Hotel',0,0,'房费','Charge rate','room_rate',-1.00,'元','1','0000-00-00 00:00:00'),(3,3,'commom',0,0,'能源费','Energy cost','energy_cost',-1.00,'元','1','0000-00-00 00:00:00'),(4,3,'commom',0,0,'水费','Water rate','energy_cost',-1.00,'元','1','0000-00-00 00:00:00'),(5,3,'commom',0,0,'电费','Electric charge','energy_cost',-1.00,'元','1','0000-00-00 00:00:00'),(6,1,'Hotel',0,0,'加收房费','Charge rate','room_rate',-1.00,'元','1','0000-00-00 00:00:00'),(7,7,'Hotel',0,0,'服务费','Service charge','service_charge',-1.00,'元','1','0000-00-00 00:00:00'),(8,8,'Hotel',0,0,'场地费','Water Front Hire','water_front_hire',-1.00,'元','1','0000-00-00 00:00:00'),(9,1,'Hotel',0,0,'小时房费','Hour rate','room_rate',-1.00,'元','1','0000-00-00 00:00:00'),(11,1,'Hotel',1,1,'我的房费','fangfei','room_rate',0.00,'','1','2019-06-03 23:11:18');

/*Table structure for table `channel_coupons` */

DROP TABLE IF EXISTS `channel_coupons`;

CREATE TABLE `channel_coupons` (
  `coupons_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '优惠劵',
  PRIMARY KEY (`coupons_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `channel_coupons` */

/*Table structure for table `channel_item` */

DROP TABLE IF EXISTS `channel_item`;

CREATE TABLE `channel_item` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'item_id',
  `channel_config` enum('self','layout','room','cuisine','table','delivery') NOT NULL COMMENT '类型配置',
  `item_father_id` int(11) NOT NULL DEFAULT '0' COMMENT '父类',
  `item_type` enum('item','category') NOT NULL DEFAULT 'item' COMMENT '类别[item,category]',
  `company_id` int(11) NOT NULL DEFAULT '0' COMMENT '0通用 公司/品牌',
  `channel_id` int(11) NOT NULL DEFAULT '0' COMMENT '频道ID',
  `item_name` varchar(100) NOT NULL COMMENT '中文[房间名称/编号]',
  `item_en_name` varchar(100) NOT NULL DEFAULT '' COMMENT '英文名称',
  `item_unit` varchar(50) NOT NULL DEFAULT '' COMMENT '[room:锁号][单位]',
  `item_number` varchar(50) NOT NULL DEFAULT '' COMMENT '[room:房号][goods:编号][dining:编号]',
  `item_attr1_value` varchar(50) NOT NULL DEFAULT '' COMMENT '[item默认属性1][room:楼层][good:货架]',
  `item_attr2_value` varchar(50) NOT NULL DEFAULT '' COMMENT '[item默认属性2][room:楼栋][goods:价格][dining:价格]',
  `item_attr3_value` varchar(50) NOT NULL DEFAULT '' COMMENT '[item默认属性3]加床]',
  `item_attr4_value` varchar(50) NOT NULL DEFAULT '' COMMENT '[item默认属性4][room:面积]',
  `item_attr5_value` varchar(100) NOT NULL DEFAULT '' COMMENT '[item默认属性5][room:朝向]',
  `booking_number` varchar(64) NOT NULL DEFAULT '' COMMENT '关联ID',
  `number_max` int(11) NOT NULL DEFAULT '1' COMMENT '[room:最多住几人][good:数量 库存]',
  `number_min` int(11) NOT NULL DEFAULT '0' COMMENT '[room:最多住小朋友]',
  `describe` varchar(100) NOT NULL DEFAULT '' COMMENT '[描述][room:房间类型]',
  `describe_en` varchar(100) NOT NULL DEFAULT '' COMMENT '[描述英文名称]',
  `image_src` varchar(255) NOT NULL DEFAULT '' COMMENT '主图地址',
  `status` enum('0','live_in') NOT NULL DEFAULT '0' COMMENT '0正常[room:live_in在住]',
  `clean` enum('0','dirty') NOT NULL DEFAULT '0' COMMENT '0正常[room:dirty脏的]',
  `lock` enum('0','lock','repair') NOT NULL DEFAULT '0' COMMENT '0正常[room:lock锁上 repair维修][dining: sell沽清]',
  `begin_datetime` datetime DEFAULT NULL COMMENT '锁房 维修房时间',
  `end_datetime` datetime DEFAULT NULL COMMENT '锁房 维修房时间',
  `valid` enum('0','1') NOT NULL DEFAULT '1' COMMENT '是否有效',
  `add_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`item_id`),
  UNIQUE KEY `item_name` (`item_name`,`channel_id`,`company_id`,`item_type`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

/*Data for the table `channel_item` */

insert  into `channel_item`(`item_id`,`channel_config`,`item_father_id`,`item_type`,`company_id`,`channel_id`,`item_name`,`item_en_name`,`item_unit`,`item_number`,`item_attr1_value`,`item_attr2_value`,`item_attr3_value`,`item_attr4_value`,`item_attr5_value`,`booking_number`,`number_max`,`number_min`,`describe`,`describe_en`,`image_src`,`status`,`clean`,`lock`,`begin_datetime`,`end_datetime`,`valid`,`add_datetime`) values (1,'room',0,'item',1,1,'103','','','22','1','1','3','4','东南','',1,2,'朝街房,城景房,园景房','','','0','0','0','2019-07-11 00:00:00','2019-07-12 00:00:00','1','0000-00-00 00:00:00'),(2,'room',0,'item',1,1,'102','','','22','1','1','1','1','南','',1,1,'朝街房,背街房','','','0','0','0','2019-05-12 00:00:00','2019-05-12 00:00:00','1','2018-04-29 13:41:08'),(3,'room',0,'item',1,1,'201','','','22','2','1','1','1','西','',1,1,'园景房,海景房,湖景房','','','0','0','0','2019-05-12 00:00:00','2019-05-13 00:00:00','1','2018-04-30 01:35:20'),(4,'layout',0,'category',1,1,'标准间','7777','','','','','1','','','',1,1,'','','/data/images/2018/0418/20180418003841_88090.png','0','0','0',NULL,NULL,'1','2018-05-08 09:06:10'),(8,'layout',0,'category',1,1,'双人间','ewqeqweqw','','','','','0','','','',2,3,'erqr33','wrqwrqwrqwer','/data/images/2018/0429/20180429140906_41456.jpg','0','0','0',NULL,NULL,'1','2018-05-08 14:17:24'),(17,'layout',0,'category',1,1,'园景房','SFQERFWEQR','','','','','0','','','',2,2,'ADFASDF','ASDFASDFAS','/data/images/2018/0501/20180501224018_82795.jpg','0','0','0',NULL,NULL,'1','2018-05-08 21:32:09'),(18,'layout',0,'category',1,1,'背街房','7777','','','','','0','','','',1,0,'ADFASDF','4se5rd6ft7gy8hjopk[l]','/data/images/2018/0429/20180429140906_41456.jpg','0','0','0',NULL,NULL,'1','2018-05-13 22:05:06'),(21,'layout',0,'category',1,1,'豪华间','7777','','','','','0','','','',1,0,'暗室逢灯','','/data/images/2018/0429/20180429140906_41456.jpg','0','0','0',NULL,NULL,'1','2018-05-13 23:22:23'),(22,'layout',0,'category',1,5,'经济单人间','jijidanrenjian','','','','','1','','','',2,2,'','','/resource/images/a10.jpg','0','0','0',NULL,NULL,'1','2018-06-07 13:22:38'),(23,'layout',0,'category',1,4,'海景房','haijingfang','','','','','1','','','',1,1,'','','/resource/images/a10.jpg','0','0','0',NULL,NULL,'1','2018-06-07 15:24:53'),(24,'room',0,'item',1,4,'1001','','','1','1','1','1','2','南','',1,1,'朝街房,背街房,城景房','','','0','0','0',NULL,NULL,'1','2019-03-05 15:16:42'),(25,'room',0,'item',1,1,'301','','','1','1','1','1','1','东','',1,1,'背街房,城景房,山景房','','','0','0','0',NULL,NULL,'1','2019-08-09 16:33:53'),(26,'room',0,'item',1,1,'401','','','1','1','1','1','1','南','',1,1,'背街房,城景房','','','0','0','0',NULL,NULL,'1','2019-08-09 16:34:11');

/*Table structure for table `channel_item_attribute_value` */

DROP TABLE IF EXISTS `channel_item_attribute_value`;

CREATE TABLE `channel_item_attribute_value` (
  `company_id` int(11) NOT NULL COMMENT '公司/品牌',
  `channel_id` int(11) NOT NULL COMMENT '频道ID',
  `item_category_id` int(11) NOT NULL DEFAULT '0' COMMENT 'item_category_id',
  `item_id` int(11) NOT NULL COMMENT 'item_id[房间ID]',
  `item_images_src` varchar(255) NOT NULL DEFAULT '' COMMENT '图片地址',
  `attribute_id` int(11) NOT NULL DEFAULT '0' COMMENT '属性ID',
  `attr_value` varchar(200) NOT NULL DEFAULT '' COMMENT '属性值[图片名称]',
  `attr_en_value` varchar(255) NOT NULL DEFAULT '' COMMENT '属性值英文[图片名称]',
  `attr_type` enum('images','attr_value','multipe_room') NOT NULL COMMENT 'multipe_room 混合房间',
  PRIMARY KEY (`attribute_id`,`company_id`,`channel_id`,`item_category_id`,`item_id`,`item_images_src`,`attr_value`,`attr_en_value`,`attr_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `channel_item_attribute_value` */

insert  into `channel_item_attribute_value`(`company_id`,`channel_id`,`item_category_id`,`item_id`,`item_images_src`,`attribute_id`,`attr_value`,`attr_en_value`,`attr_type`) values (1,1,4,1,'',0,'','','multipe_room'),(1,1,4,2,'',0,'','','multipe_room'),(1,1,4,3,'',0,'','','multipe_room'),(1,1,4,25,'',0,'','','multipe_room'),(1,1,4,26,'',0,'','','multipe_room'),(1,1,8,1,'',0,'','','multipe_room'),(1,1,8,8,'/data/images/2018/0429/20180429140906_41456.jpg',0,'','','images'),(1,1,17,17,'/data/images/2018/0429/20180429140906_41456.jpg',0,'','','images'),(1,1,18,18,'/data/images/2018/0501/20180501224018_82795.jpg',0,'匹配','','images'),(1,4,23,24,'',0,'','','multipe_room'),(1,1,8,8,'',1,'单人间','','attr_value'),(1,1,8,8,'',1,'山景房','','attr_value'),(1,1,8,8,'',1,'朝街房','','attr_value'),(1,1,8,8,'',1,'经济间','','attr_value'),(1,1,17,17,'',1,'单人间','','attr_value'),(1,1,17,17,'',1,'朝街房','','attr_value'),(1,1,17,17,'',1,'经济间','','attr_value'),(1,1,18,18,'',1,'双人间','','attr_value'),(1,1,18,18,'',1,'普通间','','attr_value'),(1,1,18,18,'',1,'背街房','','attr_value'),(1,1,21,21,'',1,'三人间','','attr_value'),(1,1,21,21,'',1,'四人间','','attr_value'),(1,1,21,21,'',1,'园景房','','attr_value'),(1,1,21,21,'',1,'豪华间','','attr_value'),(1,4,23,23,'',1,'海景房','','attr_value'),(1,5,22,22,'',1,'单人间','','attr_value'),(1,5,22,22,'',1,'双人间','','attr_value'),(1,5,22,22,'',1,'城景房','','attr_value'),(1,5,22,22,'',1,'大床间','','attr_value'),(1,5,22,22,'',1,'背街房','','attr_value'),(1,5,22,22,'',1,'高级间','','attr_value'),(1,1,4,4,'',3,'圆床','','attr_value'),(1,1,4,4,'',3,'情调床','','attr_value'),(1,1,4,4,'',3,'标准床','','attr_value'),(1,1,8,8,'',3,'圆床','','attr_value'),(1,1,8,8,'',3,'标准床','','attr_value'),(1,1,21,21,'',3,'标准床','','attr_value'),(1,1,4,4,'',4,'1.2米','','attr_value'),(1,1,4,4,'',4,'1.5米','','attr_value'),(1,1,4,4,'',5,'1','','attr_value'),(1,1,21,21,'',5,'1','','attr_value'),(1,1,8,8,'',6,'000','','attr_value'),(1,1,8,8,'',6,'中央空调','','attr_value'),(1,1,8,8,'',6,'分体空调','','attr_value'),(1,1,21,21,'',6,'中央空调','','attr_value'),(1,1,4,4,'',7,'1','','attr_value'),(1,1,8,8,'',7,'1','','attr_value'),(1,1,17,17,'',7,'1','','attr_value'),(1,1,18,18,'',7,'1','','attr_value'),(1,1,21,21,'',7,'1','','attr_value'),(1,4,23,23,'',7,'1','','attr_value'),(1,5,22,22,'',7,'1','','attr_value'),(1,1,8,8,'',8,'独立卫生间','','attr_value'),(1,1,21,21,'',8,'独立卫生间','','attr_value'),(1,1,8,8,'',9,'落地窗','','attr_value'),(1,1,8,8,'',10,'111','','attr_value'),(1,1,8,8,'',10,'222','','attr_value'),(1,1,8,8,'',10,'333','','attr_value');

/*Table structure for table `channel_layout_price` */

DROP TABLE IF EXISTS `channel_layout_price`;

CREATE TABLE `channel_layout_price` (
  `price_system_id` int(11) NOT NULL COMMENT '价格体系',
  `layout_price_date` date NOT NULL COMMENT '时间',
  `item_id` int(11) NOT NULL COMMENT '房型ID',
  `company_id` int(11) NOT NULL COMMENT '品牌/公司ID',
  `channel_id` int(11) NOT NULL COMMENT '酒店ID',
  `day_01` float(9,2) DEFAULT NULL,
  `day_02` float(9,2) DEFAULT NULL,
  `day_03` float(9,2) DEFAULT NULL,
  `day_04` float(9,2) DEFAULT NULL,
  `day_05` float(9,2) DEFAULT NULL,
  `day_06` float(9,2) DEFAULT NULL,
  `day_07` float(9,2) DEFAULT NULL,
  `day_08` float(9,2) DEFAULT NULL,
  `day_09` float(9,2) DEFAULT NULL,
  `day_10` float(9,2) DEFAULT NULL,
  `day_11` float(9,2) DEFAULT NULL,
  `day_12` float(9,2) DEFAULT NULL,
  `day_13` float(9,2) DEFAULT NULL,
  `day_14` float(9,2) DEFAULT NULL,
  `day_15` float(9,2) DEFAULT NULL,
  `day_16` float(9,2) DEFAULT NULL,
  `day_17` float(9,2) DEFAULT NULL,
  `day_18` float(9,2) DEFAULT NULL,
  `day_19` float(9,2) DEFAULT NULL,
  `day_20` float(9,2) DEFAULT NULL,
  `day_21` float(9,2) DEFAULT NULL,
  `day_22` float(9,2) DEFAULT NULL,
  `day_23` float(9,2) DEFAULT NULL,
  `day_24` float(9,2) DEFAULT NULL,
  `day_25` float(9,2) DEFAULT NULL,
  `day_26` float(9,2) DEFAULT NULL,
  `day_27` float(9,2) DEFAULT NULL,
  `day_28` float(9,2) DEFAULT NULL,
  `day_29` float(9,2) DEFAULT NULL,
  `day_30` float(9,2) DEFAULT NULL,
  `day_31` float(9,2) DEFAULT NULL,
  `add_datetime` datetime NOT NULL,
  PRIMARY KEY (`price_system_id`,`layout_price_date`,`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `channel_layout_price` */

insert  into `channel_layout_price`(`price_system_id`,`layout_price_date`,`item_id`,`company_id`,`channel_id`,`day_01`,`day_02`,`day_03`,`day_04`,`day_05`,`day_06`,`day_07`,`day_08`,`day_09`,`day_10`,`day_11`,`day_12`,`day_13`,`day_14`,`day_15`,`day_16`,`day_17`,`day_18`,`day_19`,`day_20`,`day_21`,`day_22`,`day_23`,`day_24`,`day_25`,`day_26`,`day_27`,`day_28`,`day_29`,`day_30`,`day_31`,`add_datetime`) values (1,'2018-06-01',4,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.01,0.01,0.01,NULL,'2018-06-28 20:18:23'),(1,'2018-06-01',18,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.02,0.02,0.02,NULL,'2018-06-28 20:18:23'),(1,'2018-06-01',23,1,4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.03,0.03,0.03,NULL,'2018-06-28 20:18:23'),(1,'2018-07-01',4,1,1,0.01,0.01,0.01,0.01,0.01,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1.00,1.00,1.00,'2018-06-28 20:18:23'),(1,'2018-07-01',8,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2.00,2.00,2.00,'2018-07-29 09:31:44'),(1,'2018-07-01',17,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3.00,3.00,3.00,'2018-07-29 09:31:44'),(1,'2018-07-01',18,1,1,0.02,0.02,0.02,0.02,0.02,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2018-06-28 20:18:23'),(1,'2018-07-01',23,1,4,0.03,0.03,0.03,0.03,0.03,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2018-06-28 20:18:23'),(1,'2018-08-01',4,1,1,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,1.00,1.00,1.00,1.00,1.00,'2018-07-29 09:31:44'),(1,'2018-08-01',8,1,1,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,2.00,2.00,2.00,2.00,2.00,'2018-07-29 09:31:44'),(1,'2018-08-01',17,1,1,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,3.00,3.00,3.00,3.00,3.00,'2018-07-29 09:31:44'),(1,'2018-09-01',4,1,1,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,NULL,'2018-08-01 21:43:22'),(1,'2018-09-01',8,1,1,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,NULL,'2018-08-01 21:43:22'),(1,'2018-09-01',17,1,1,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,NULL,'2018-08-01 21:43:22'),(1,'2018-10-01',4,1,1,NULL,NULL,NULL,NULL,NULL,NULL,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,'2018-10-07 18:11:51'),(1,'2018-10-01',8,1,1,NULL,NULL,NULL,NULL,NULL,NULL,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,'2018-10-07 18:11:51'),(1,'2018-10-01',17,1,1,NULL,NULL,NULL,NULL,NULL,NULL,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,'2018-10-07 18:11:51'),(1,'2018-10-01',18,1,1,NULL,NULL,NULL,NULL,NULL,NULL,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,'2018-10-07 18:11:51'),(1,'2018-10-01',21,1,1,NULL,NULL,NULL,NULL,NULL,NULL,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,'2018-10-07 18:11:51'),(1,'2018-10-01',23,1,4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,8.00,8.00,8.00,8.00,'2018-10-28 18:47:53'),(1,'2018-11-01',4,1,1,NULL,NULL,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,NULL,'2018-11-03 11:32:22'),(1,'2018-11-01',8,1,1,NULL,NULL,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,NULL,'2018-11-03 11:32:22'),(1,'2018-11-01',17,1,1,NULL,NULL,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,NULL,'2018-11-03 11:32:22'),(1,'2018-11-01',18,1,1,NULL,NULL,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,NULL,'2018-11-03 11:32:22'),(1,'2018-11-01',21,1,1,NULL,NULL,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,NULL,'2018-11-03 11:32:22'),(1,'2018-11-01',23,1,4,8.00,8.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,NULL,'2018-10-28 18:47:53'),(1,'2018-12-01',4,1,1,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,'2018-11-03 11:32:22'),(1,'2018-12-01',8,1,1,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,'2018-11-03 11:32:22'),(1,'2018-12-01',17,1,1,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,'2018-11-03 11:32:22'),(1,'2018-12-01',18,1,1,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,'2018-11-03 11:32:22'),(1,'2018-12-01',21,1,1,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,'2018-11-03 11:32:22'),(1,'2018-12-01',23,1,4,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,'2018-11-03 11:32:22'),(1,'2019-01-01',4,1,1,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,'2018-11-03 11:32:22'),(1,'2019-01-01',8,1,1,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,'2018-11-03 11:32:22'),(1,'2019-01-01',17,1,1,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,'2018-11-03 11:32:22'),(1,'2019-01-01',18,1,1,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,'2018-11-03 11:32:22'),(1,'2019-01-01',21,1,1,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,'2018-11-03 11:32:22'),(1,'2019-01-01',23,1,4,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,'2018-11-03 11:32:22'),(1,'2019-02-01',4,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,NULL,NULL,NULL,'2019-02-16 12:37:41'),(1,'2019-02-01',8,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,NULL,NULL,NULL,'2019-02-16 12:37:41'),(1,'2019-02-01',17,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,NULL,NULL,NULL,'2019-02-16 12:37:41'),(1,'2019-02-01',18,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,NULL,NULL,NULL,'2019-02-16 12:37:41'),(1,'2019-02-01',21,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,NULL,NULL,NULL,'2019-02-16 12:37:41'),(1,'2019-02-01',23,1,4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,NULL,NULL,NULL,'2019-02-16 12:37:41'),(1,'2019-03-01',4,1,1,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,'2019-02-16 12:37:41'),(1,'2019-03-01',8,1,1,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,'2019-02-16 12:37:41'),(1,'2019-03-01',17,1,1,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,'2019-02-16 12:37:41'),(1,'2019-03-01',18,1,1,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,'2019-02-16 12:37:41'),(1,'2019-03-01',21,1,1,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,'2019-02-16 12:37:41'),(1,'2019-03-01',23,1,4,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,'2019-02-16 12:37:41'),(1,'2019-04-01',4,1,1,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,NULL,'2019-02-16 12:37:41'),(1,'2019-04-01',8,1,1,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,NULL,'2019-02-16 12:37:41'),(1,'2019-04-01',17,1,1,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,NULL,'2019-02-16 12:37:41'),(1,'2019-04-01',18,1,1,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,NULL,'2019-02-16 12:37:41'),(1,'2019-04-01',21,1,1,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,NULL,'2019-02-16 12:37:41'),(1,'2019-04-01',23,1,4,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,6.00,NULL,'2019-02-16 12:37:41'),(1,'2019-05-01',4,1,1,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2019-05-01 11:03:48'),(1,'2019-05-01',8,1,1,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,'2019-05-01 11:06:12'),(1,'2019-05-01',17,1,1,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2019-05-01 11:03:48'),(1,'2019-05-01',18,1,1,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2019-05-01 11:03:48'),(1,'2019-05-01',21,1,1,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2019-05-01 11:03:48'),(1,'2019-06-01',4,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1.00,1.00,NULL,'2019-06-29 12:09:48'),(1,'2019-06-01',8,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2.00,2.00,NULL,'2019-06-29 12:09:48'),(1,'2019-06-01',17,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3.00,3.00,NULL,'2019-06-29 12:09:48'),(1,'2019-06-01',18,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4.00,4.00,NULL,'2019-06-29 12:09:48'),(1,'2019-06-01',21,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5.00,5.00,NULL,'2019-06-29 12:09:48'),(1,'2019-07-01',4,1,1,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,'2019-06-29 12:09:48'),(1,'2019-07-01',8,1,1,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,'2019-06-29 12:09:48'),(1,'2019-07-01',17,1,1,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,'2019-06-29 12:09:48'),(1,'2019-07-01',18,1,1,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,'2019-06-29 12:09:48'),(1,'2019-07-01',21,1,1,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,'2019-06-29 12:09:48'),(1,'2019-08-01',4,1,1,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,1.00,'2019-06-29 12:09:48'),(1,'2019-08-01',8,1,1,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,2.00,'2019-06-29 12:09:48'),(1,'2019-08-01',17,1,1,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,3.00,'2019-06-29 12:09:48'),(1,'2019-08-01',18,1,1,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,4.00,'2019-06-29 12:09:48'),(1,'2019-08-01',21,1,1,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,5.00,'2019-06-29 12:09:48');

/*Table structure for table `channel_layout_price_system` */

DROP TABLE IF EXISTS `channel_layout_price_system`;

CREATE TABLE `channel_layout_price_system` (
  `price_system_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `price_system_father_id` int(11) NOT NULL DEFAULT '0' COMMENT '父ID',
  `company_id` int(11) NOT NULL COMMENT '公司、品牌',
  `channel_father_id` int(11) NOT NULL DEFAULT '0' COMMENT '隶属于集团ID',
  `channel_ids` varchar(1000) NOT NULL COMMENT '适用酒店',
  `price_system_name` varchar(100) NOT NULL COMMENT '名称',
  `price_system_en_name` varchar(100) NOT NULL DEFAULT '' COMMENT '英文名称',
  `market_ids` varchar(2000) NOT NULL COMMENT '适用客源市场 json数据',
  `layout_item` varchar(2000) NOT NULL COMMENT '适用房型ID json数据',
  `book_min_day` int(11) NOT NULL DEFAULT '1' COMMENT '最小预订天数',
  `cancellation_policy` varchar(200) NOT NULL DEFAULT '' COMMENT '取消政策',
  `price_system_type` enum('formula','direct') NOT NULL DEFAULT 'direct' COMMENT 'direct手输价格 formula公式价格',
  `formula` text COMMENT '公式 (formula formula_value)(formula_second formula_second_value)',
  `valid` enum('0','1') NOT NULL DEFAULT '1' COMMENT '有效',
  `add_datetime` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`price_system_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `channel_layout_price_system` */

insert  into `channel_layout_price_system`(`price_system_id`,`price_system_father_id`,`company_id`,`channel_father_id`,`channel_ids`,`price_system_name`,`price_system_en_name`,`market_ids`,`layout_item`,`book_min_day`,`cancellation_policy`,`price_system_type`,`formula`,`valid`,`add_datetime`) values (1,0,1,0,'{\"1\":\"1\",\"4\":\"4\"}','金卡会员无早','ti-eraser','{\"19\":\"19\"}','{\"1\":{\"4\":\"4\",\"8\":\"8\",\"17\":\"17\",\"18\":\"18\",\"21\":\"21\"},\"4\":{\"23\":\"23\"}}',1,'2','direct',NULL,'1','2018-06-08 13:15:33'),(2,1,1,0,'{\"1\":\"1\"}','携程现付双早','xiechengtejia','{\"11\":\"11\",\"13\":\"13\",\"24\":\"24\"}','{\"1\":{\"8\":\"8\"}}',1,'2','formula','{\"1-8\":{\"formula\":\"+\",\"formula_value\":\"2\",\"formula_second\":\"+\",\"formula_second_value\":\"2\"}}','1','2018-06-11 17:28:53'),(3,1,1,0,'{\"1\":\"1\"}','散客单早','xiechengtejia','{\"2\":\"2\"}','{\"1\":{\"4\":\"4\",\"8\":\"8\",\"17\":\"17\"}}',1,'1','formula','{\"1-4\":{\"formula\":\"+\",\"formula_value\":\"0.01\",\"formula_second\":\"+\",\"formula_second_value\":\"\"},\"1-8\":{\"formula\":\"+\",\"formula_value\":\"0.01\",\"formula_second\":\"+\",\"formula_second_value\":\"\"},\"1-17\":{\"formula\":\"+\",\"formula_value\":\"0.01\",\"formula_second\":\"+\",\"formula_second_value\":\"\"},\"1-18\":{\"formula\":\"+\",\"formula_value\":\"0.01\",\"formula_second\":\"+\",\"formula_second_value\":\"\"}}','1','2018-09-18 20:33:34'),(4,1,1,0,'{\"1\":\"1\"}','散客双早','xiechengtejia','{\"2\":\"2\"}','{\"1\":{\"4\":\"4\",\"8\":\"8\",\"17\":\"17\",\"18\":\"18\"}}',1,'','formula','{\"1-4\":{\"formula\":\"+\",\"formula_value\":\"\",\"formula_second\":\"+\",\"formula_second_value\":\"\"},\"1-8\":{\"formula\":\"+\",\"formula_value\":\"\",\"formula_second\":\"+\",\"formula_second_value\":\"\"},\"1-17\":{\"formula\":\"+\",\"formula_value\":\"\",\"formula_second\":\"+\",\"formula_second_value\":\"\"},\"1-18\":{\"formula\":\"+\",\"formula_value\":\"\",\"formula_second\":\"+\",\"formula_second_value\":\"\"}}','1','2018-09-18 21:32:29'),(5,1,1,0,'{\"1\":\"1\"}','散客三早','san','{\"2\":\"2\",\"3\":\"3\"}','{\"1\":{\"4\":\"4\",\"17\":\"17\",\"18\":\"18\"}}',1,'1','formula','{\"1-4\":{\"formula\":\"+\",\"formula_value\":\"\",\"formula_second\":\"+\",\"formula_second_value\":\"\"},\"1-17\":{\"formula\":\"+\",\"formula_value\":\"\",\"formula_second\":\"+\",\"formula_second_value\":\"\"},\"1-18\":{\"formula\":\"+\",\"formula_value\":\"\",\"formula_second\":\"+\",\"formula_second_value\":\"\"}}','1','2018-09-18 22:00:58'),(6,1,1,0,'{\"1\":\"1\"}','散客四早','si','{\"2\":\"2\"}','{\"1\":{\"4\":\"4\",\"17\":\"17\",\"18\":\"18\"}}',1,'1','formula','{\"1-4\":{\"formula\":\"+\",\"formula_value\":\"\",\"formula_second\":\"+\",\"formula_second_value\":\"\"},\"1-17\":{\"formula\":\"+\",\"formula_value\":\"\",\"formula_second\":\"+\",\"formula_second_value\":\"\"},\"1-18\":{\"formula\":\"+\",\"formula_value\":\"\",\"formula_second\":\"+\",\"formula_second_value\":\"\"}}','1','2018-09-18 22:02:06');

/*Table structure for table `channel_layout_price_system_layout` */

DROP TABLE IF EXISTS `channel_layout_price_system_layout`;

CREATE TABLE `channel_layout_price_system_layout` (
  `price_system_id` int(11) NOT NULL,
  `price_system_father_id` int(11) NOT NULL DEFAULT '0',
  `channel_id` int(11) NOT NULL,
  `market_id` int(11) NOT NULL,
  `item_category_id` int(11) NOT NULL COMMENT '房型ID',
  PRIMARY KEY (`price_system_id`,`channel_id`,`market_id`,`item_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `channel_layout_price_system_layout` */

insert  into `channel_layout_price_system_layout`(`price_system_id`,`price_system_father_id`,`channel_id`,`market_id`,`item_category_id`) values (1,0,1,19,4),(1,0,1,19,8),(1,0,1,19,17),(1,0,1,19,18),(1,0,1,19,21),(1,0,1,19,23),(1,0,4,19,4),(1,0,4,19,8),(1,0,4,19,17),(1,0,4,19,18),(1,0,4,19,21),(1,0,4,19,23),(2,1,1,11,8),(2,1,1,13,8),(2,1,1,24,8),(3,1,1,2,4),(3,1,1,2,8),(3,1,1,2,17),(4,1,1,2,4),(4,1,1,2,8),(4,1,1,2,17),(4,1,1,2,18),(5,1,1,2,4),(5,1,1,2,17),(5,1,1,2,18),(5,1,1,3,4),(5,1,1,3,17),(5,1,1,3,18),(6,1,1,2,4),(6,1,1,2,17),(6,1,1,2,18);

/*Table structure for table `channel_receivable` */

DROP TABLE IF EXISTS `channel_receivable`;

CREATE TABLE `channel_receivable` (
  `receivable_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '应收账款ID',
  `company_id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `receivable_name` varchar(128) NOT NULL COMMENT '应收账款公司名称/个人名字',
  `receivable_type` enum('personal','company','OTA') NOT NULL COMMENT '类型',
  `receivable_address` varchar(255) NOT NULL,
  `receivable_credit_code` varchar(64) DEFAULT NULL COMMENT '公司信用代码/个人身份证',
  `market_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL DEFAULT '0' COMMENT '会员ID',
  `contact_name` varchar(64) DEFAULT NULL COMMENT '联系人',
  `contact_mobile` varchar(64) DEFAULT NULL COMMENT '电话',
  `contact_email` varchar(64) DEFAULT NULL COMMENT '联系人email',
  `bank` varchar(64) DEFAULT NULL COMMENT '开户行',
  `bank_account` varchar(64) DEFAULT NULL COMMENT '银行帐号',
  `credit` float(11,2) NOT NULL COMMENT '信用额度',
  `credit_used` float(11,2) NOT NULL COMMENT '已用额度',
  `the_cumulative` float(11,2) NOT NULL COMMENT '累计消费',
  `the_cumulative_payback` float(11,2) NOT NULL COMMENT '累计回款',
  `valid_date` date NOT NULL DEFAULT '0000-00-00' COMMENT '有效期',
  `employee_id` int(11) NOT NULL,
  `employee_name` varchar(64) NOT NULL,
  `add_datetime` datetime NOT NULL,
  `valid` enum('0','1') NOT NULL DEFAULT '1' COMMENT '是否有效',
  PRIMARY KEY (`receivable_id`),
  FULLTEXT KEY `receivable_name` (`receivable_name`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

/*Data for the table `channel_receivable` */

insert  into `channel_receivable`(`receivable_id`,`company_id`,`channel_id`,`receivable_name`,`receivable_type`,`receivable_address`,`receivable_credit_code`,`market_id`,`member_id`,`contact_name`,`contact_mobile`,`contact_email`,`bank`,`bank_account`,`credit`,`credit_used`,`the_cumulative`,`the_cumulative_payback`,`valid_date`,`employee_id`,`employee_name`,`add_datetime`,`valid`) values (1,1,1,'上海友谊公司','personal','华东路39好','77qqq',23,0,'77','77','111','77','77',7789.00,0.00,0.00,0.00,'2019-08-06',1,'有个员工','2019-08-06 21:00:00','1'),(2,1,0,'北京友谊公司','company','上海北京路','67890567890',23,0,'李先生','567890','7890','上海银行','567890',90.00,0.00,0.00,0.00,'2022-07-14',1,'有个员工','2019-08-07 23:08:17','1'),(3,1,0,'广州友谊公司','personal','567890','7890',23,0,'李先生','567890','7890','上海银行','567890',56.00,0.00,0.00,0.00,'2022-07-14',1,'有个员工','2019-08-07 23:10:29','1'),(4,1,0,'天津友谊公司','company','77','77',23,0,'李先生','567890','7890','上海银行','44',3.00,0.00,0.00,0.00,'2026-12-03',1,'有个员工','2019-08-07 23:11:12','1'),(5,1,0,'河北友谊公司','company','77','77',23,0,'李先生','567890','7890','上海银行','44',3.00,0.00,0.00,0.00,'2026-12-03',1,'有个员工','2019-08-07 23:11:14','1'),(6,1,0,'山东友谊公司','company','77','77',23,0,'李先生','567890','7890','上海银行','44',3.00,0.00,0.00,0.00,'2026-12-03',1,'有个员工','2019-08-07 23:11:14','1'),(7,1,0,'大连友谊公司','company','77','77',23,0,'李先生','567890','7890','上海银行','44',3.00,0.00,0.00,0.00,'2026-12-03',1,'有个员工','2019-08-07 23:11:15','1'),(8,1,0,'深圳友谊公司','company','77','77',23,0,'李先生','567890','7890','上海银行','44',3.00,0.00,0.00,0.00,'2026-12-03',1,'有个员工','2019-08-07 23:11:15','1'),(9,1,0,'南宁友谊公司','company','77','77',23,0,'李先生','567890','7890','上海银行','44',3.00,0.00,0.00,0.00,'2026-12-03',1,'有个员工','2019-08-07 23:11:16','1'),(10,1,0,'广西友谊公司','company','77','77',23,0,'李先生','567890','7890','上海银行','44',3.00,0.00,0.00,0.00,'2026-12-03',1,'有个员工','2019-08-07 23:11:16','1'),(11,1,0,'深海友谊公司','company','77','77',23,0,'李先生','567890','7890','上海银行','44',3.00,0.00,0.00,0.00,'2026-12-03',1,'有个员工','2019-08-07 23:11:17','1'),(12,1,0,'张傲友谊公司','company','77','77',23,0,'李先生','567890','7890','上海银行','44',3.00,0.00,0.00,0.00,'2026-12-03',1,'有个员工','2019-08-07 23:11:17','1'),(13,1,0,'西藏友谊公司','company','77','77',23,0,'李先生','567890','7890','上海银行','44',3.00,0.00,0.00,0.00,'2026-12-03',1,'有个员工','2019-08-07 23:11:23','1'),(14,1,0,'新疆友谊公司','company','77','77',23,0,'李先生','567890','7890','上海银行','44',3.00,0.00,0.00,0.00,'2026-12-03',1,'有个员工','2019-08-07 23:11:23','1'),(15,1,0,'北平友谊公司','company','77','77',23,0,'李先生','567890','7890','上海银行','44',3.00,0.00,0.00,0.00,'2026-12-03',1,'有个员工','2019-08-07 23:11:24','1'),(16,1,0,'秦岭友谊公司','company','77','77',23,0,'李先生','567890','7890','上海银行','44',3.00,0.00,0.00,0.00,'2026-12-03',1,'有个员工','2019-08-07 23:11:24','1'),(17,1,0,'山海关友谊公司','company','77','77',23,0,'李先生','567890','7890','上海银行','44',3.00,0.00,0.00,0.00,'2026-12-03',1,'有个员工','2019-08-07 23:11:25','1'),(18,1,0,'凤岭友谊公司','company','77','77',23,0,'李先生','567890','7890','上海银行','44',3.00,0.00,0.00,0.00,'2026-12-03',1,'有个员工','2019-08-07 23:11:25','1'),(19,1,0,'友谊关友谊公司','company','77','77',23,0,'李先生','567890','7890','上海银行','44',3.00,0.00,0.00,0.00,'2026-12-03',1,'有个员工','2019-08-07 23:11:26','1'),(20,1,0,'海口友谊公司','company','77','77',23,0,'李先生','567890','7890','上海银行','44',3.00,0.00,0.00,0.00,'2026-12-03',1,'有个员工','2019-08-07 23:11:27','1'),(21,1,0,'海南友谊公司','company','77','77',23,0,'李先生','567890','7890','上海银行','44',3.00,0.00,0.00,0.00,'2026-12-03',1,'有个员工','2019-08-07 23:11:28','1'),(22,1,0,'东南友谊公司','company','77','77',23,0,'李先生','567890','7890','上海银行','44',3.00,0.00,0.00,0.00,'2026-12-03',1,'有个员工','2019-08-07 23:11:28','1'),(23,1,0,'西方友谊公司','company','77','77',23,0,'李先生','567890','7890','上海银行','44',3.00,0.00,0.00,0.00,'2026-12-03',1,'有个员工','2019-08-07 23:11:29','1'),(24,1,0,'东方友谊公司','company','77','77',23,0,'李先生','567890','7890','上海银行','44',3.00,0.00,0.00,0.00,'2026-12-03',1,'有个员工','2019-08-07 23:11:30','1'),(25,1,0,'南方友谊公司','company','77','77',23,0,'李先生','567890','7890','上海银行','44',3.00,0.00,0.00,0.00,'2026-12-03',1,'有个员工','2019-08-07 23:11:30','1'),(26,1,0,'北方友谊公司','company','77','77',23,0,'李先生','567890','7890','上海银行','44',3.00,0.00,0.00,0.00,'2026-12-03',1,'有个员工','2019-08-07 23:11:31','1'),(27,1,0,'东北友谊公司','company','77','77',23,0,'李先生','567890','7890','上海银行','44',3.00,0.00,0.00,0.00,'2026-12-03',1,'有个员工','2019-08-07 23:11:32','1');

/*Table structure for table `channel_setting` */

DROP TABLE IF EXISTS `channel_setting`;

CREATE TABLE `channel_setting` (
  `channel_id` int(11) NOT NULL COMMENT '酒店、餐厅等ID',
  `channel` enum('Hotel','Meal','Meeting','Shop','Service','Tour','Sport') NOT NULL COMMENT '频道',
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `is_business_day` enum('0','1') NOT NULL DEFAULT '0' COMMENT '1营业日工作制 0记账式工作制',
  `check_in_time` time NOT NULL DEFAULT '14:00:00' COMMENT '正常入住',
  `check_out_time` time NOT NULL DEFAULT '12:00:00' COMMENT '正常退房',
  `half_price_time` time NOT NULL DEFAULT '18:00:00' COMMENT '14点后18点前半价 18点后全价',
  `plus_price_time` time NOT NULL DEFAULT '06:00:00' COMMENT '凌晨6点前入住加价1天',
  `decimal_price` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT '价格保留小数点默认不保留',
  PRIMARY KEY (`channel_id`,`channel`,`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `channel_setting` */

insert  into `channel_setting`(`channel_id`,`channel`,`company_id`,`is_business_day`,`check_in_time`,`check_out_time`,`half_price_time`,`plus_price_time`,`decimal_price`) values (1,'Hotel',1,'1','14:00:00','14:00:00','18:00:00','06:00:00','1'),(2,'Meal',1,'0','14:00:00','14:00:00','18:00:00','06:00:00','0'),(3,'Sport',1,'0','14:00:00','14:00:00','18:00:00','06:00:00','0');

/*Table structure for table `channel_upload_images` */

DROP TABLE IF EXISTS `channel_upload_images`;

CREATE TABLE `channel_upload_images` (
  `images_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '图片ID',
  `company_id` int(11) NOT NULL COMMENT '公司/品牌',
  `item_id` int(11) NOT NULL COMMENT 'item_id',
  `channel_id` int(11) NOT NULL COMMENT '频道ID',
  `images_name` varchar(100) NOT NULL DEFAULT '' COMMENT '图片名称',
  `images_src` varchar(255) NOT NULL COMMENT '图片路径',
  `images_size` int(11) NOT NULL DEFAULT '0' COMMENT '图片大小',
  `images_channel_type` enum('layout','room','cuisine','table','delivery','meeting','tour','employee','personal') NOT NULL DEFAULT 'layout' COMMENT '图片类型',
  `images_add_datetime` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`images_id`),
  UNIQUE KEY `item_id` (`item_id`,`channel_id`,`images_src`,`images_channel_type`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `channel_upload_images` */

insert  into `channel_upload_images`(`images_id`,`company_id`,`item_id`,`channel_id`,`images_name`,`images_src`,`images_size`,`images_channel_type`,`images_add_datetime`) values (1,1,0,0,'hotelbrain_logo单独','2018/0418/20180418003841_88090.png',1946,'layout','2018-04-18 00:38:41'),(2,1,0,0,'a6','2018/0429/20180429140906_41456.jpg',3965,'layout','2018-04-29 14:09:06'),(3,1,0,0,'a4','2018/0501/20180501224018_82795.jpg',5454,'layout','2018-05-01 22:40:18'),(4,1,0,0,'a0','2019/0917/20190917114640_62722.jpg',17837,'employee','2019-09-17 11:46:40');

/*Table structure for table `company` */

DROP TABLE IF EXISTS `company`;

CREATE TABLE `company` (
  `company_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '公司/品牌ID',
  `company_group` enum('0','1') NOT NULL DEFAULT '0' COMMENT '是否集团',
  `group_unified_settings` enum('0','1') NOT NULL DEFAULT '0' COMMENT '是否集团统一配置',
  `company_name` varchar(100) NOT NULL COMMENT '公司/品牌名称',
  `valid` enum('0','1') NOT NULL DEFAULT '1' COMMENT '是否有效',
  PRIMARY KEY (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `company` */

insert  into `company`(`company_id`,`company_group`,`group_unified_settings`,`company_name`,`valid`) values (1,'1','0','有间公司','1');

/*Table structure for table `company_channel_sector` */

DROP TABLE IF EXISTS `company_channel_sector`;

CREATE TABLE `company_channel_sector` (
  `sector_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '公司部门\\职位ID',
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `channel_id` int(11) NOT NULL COMMENT '隶属于',
  `sector_father_id` int(11) NOT NULL COMMENT '父ID',
  `sector_name` varchar(100) NOT NULL COMMENT '名称',
  `sector_order` bigint(13) NOT NULL DEFAULT '0' COMMENT '排序',
  `sector_type` enum('sector','position') DEFAULT NULL COMMENT 'position 职位，sector 部门',
  `valid` enum('1','0') DEFAULT '1' COMMENT '是否有效',
  `is_delete` varchar(64) NOT NULL DEFAULT '0' COMMENT '是否删除',
  PRIMARY KEY (`sector_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/*Data for the table `company_channel_sector` */

insert  into `company_channel_sector`(`sector_id`,`company_id`,`channel_id`,`sector_father_id`,`sector_name`,`sector_order`,`sector_type`,`valid`,`is_delete`) values (1,1,1,1,'部门A',1000000000,'sector','1','0'),(2,1,1,1,'部门A 2级部门1',1002000000,'sector','1','0'),(3,1,1,2,'部门A 2级部门1职位1',1002003000,'position','1','0'),(4,1,1,4,'部门B',4000000000,'sector','1','0'),(5,1,1,4,'部门B 2级部门1',4000005000,'sector','1','0'),(6,1,1,1,'部门A 2级部门2',1006000000,'sector','1','0'),(7,1,1,2,'部门A 2级部门1职位2',1002007000,'position','1','0'),(8,1,1,1,'部门A 职位A',1000000008,'position','1','0'),(9,1,1,5,'66666',0,'position','1','0'),(10,1,1,4,'7777',0,'sector','1','0'),(11,1,1,4,'88888',0,'sector','1','0'),(12,1,1,11,'555444',0,'position','0','20191002042025222561');

/*Table structure for table `customer_market` */

DROP TABLE IF EXISTS `customer_market`;

CREATE TABLE `customer_market` (
  `market_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '客源市场ID',
  `company_id` int(11) NOT NULL DEFAULT '0' COMMENT '公司/品牌 0表示通用',
  `market_father_id` int(11) NOT NULL DEFAULT '0' COMMENT '父ID',
  `market_name` varchar(50) NOT NULL COMMENT '客源市场名称',
  `market_en_name` varchar(50) NOT NULL DEFAULT '',
  `marketing` enum('0','1','2') NOT NULL DEFAULT '1' COMMENT '1直销 2分销 3OTA 0其它',
  `market_level` enum('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15') NOT NULL DEFAULT '0' COMMENT '0無級別 1級最高',
  `brokerage_category` enum('manual','fixed') DEFAULT NULL COMMENT '手動 佣金 固定佣金',
  `brokerage_type` enum('manual','pay_later','base_price') DEFAULT NULL COMMENT '后付佣金 底價收款',
  `brokerage_constitute` enum('percent','night','manually') DEFAULT NULL COMMENT '间夜 百分比',
  `brokerage` float(8,2) NOT NULL DEFAULT '0.00' COMMENT '佣金',
  `valid` enum('0','1') NOT NULL DEFAULT '1' COMMENT '1有效',
  PRIMARY KEY (`market_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

/*Data for the table `customer_market` */

insert  into `customer_market`(`market_id`,`company_id`,`market_father_id`,`market_name`,`market_en_name`,`marketing`,`market_level`,`brokerage_category`,`brokerage_type`,`brokerage_constitute`,`brokerage`,`valid`) values (1,0,0,'散客','','1','0',NULL,NULL,NULL,0.00,'1'),(2,0,1,'散客步入','','1','0',NULL,NULL,NULL,0.00,'1'),(3,0,1,'散客团体','','1','0',NULL,NULL,NULL,0.00,'1'),(4,0,0,'会员','','1','0',NULL,NULL,NULL,0.00,'1'),(5,0,0,'联盟','','1','0',NULL,NULL,NULL,0.00,'1'),(6,1,0,'协议客源','','1','0',NULL,NULL,NULL,0.00,'1'),(7,0,0,'国内OTA','','2','0',NULL,NULL,NULL,0.00,'1'),(8,0,0,'国外OTA','','2','0',NULL,NULL,NULL,0.00,'1'),(9,0,7,'飞猪','','2','0',NULL,NULL,NULL,0.00,'1'),(10,0,8,'Booking','','2','0',NULL,NULL,NULL,0.00,'1'),(11,0,7,'携程现付','','2','0',NULL,NULL,NULL,0.00,'1'),(12,0,7,'携程预付','','2','0',NULL,NULL,NULL,0.00,'1'),(13,0,7,'艺龙','','2','0',NULL,NULL,NULL,0.00,'1'),(14,0,7,'美团','','2','0',NULL,NULL,NULL,0.00,'1'),(15,0,0,'其它','','0','0',NULL,NULL,NULL,0.00,'1'),(16,0,8,'TripAdvisor','TripAdvisor','2','0',NULL,NULL,NULL,0.00,'1'),(17,0,8,'Expedia','Expedia','2','0',NULL,NULL,NULL,0.00,'1'),(18,0,8,'Agoda','Agoda','2','0',NULL,NULL,NULL,0.00,'1'),(19,1,4,'金卡会员','Gold','1','0',NULL,NULL,NULL,0.00,'1'),(20,1,5,'联盟成员','Union','1','0',NULL,NULL,NULL,0.00,'1'),(21,1,4,'银卡会员','Silver','1','0',NULL,NULL,NULL,0.00,'0'),(22,1,4,'铜卡会员','Copper','1','0',NULL,NULL,NULL,0.00,'0'),(23,1,6,'A级协议公司','A Company','1','0',NULL,NULL,NULL,0.00,'1'),(24,0,7,'携程商旅','','2','0',NULL,NULL,NULL,0.00,'1'),(25,0,7,'去哪儿','','2','0',NULL,NULL,NULL,0.00,'1');

/*Table structure for table `employee` */

DROP TABLE IF EXISTS `employee`;

CREATE TABLE `employee` (
  `employee_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '员工ID',
  `company_id` int(11) NOT NULL COMMENT '默认公司ID',
  `employee_name` varchar(50) NOT NULL COMMENT '名字',
  `sex` enum('0','1') NOT NULL COMMENT '性别',
  `birthday` date DEFAULT NULL COMMENT '出生日期',
  `photo` varchar(200) NOT NULL DEFAULT '' COMMENT '头像',
  `mobile` bigint(11) NOT NULL COMMENT '移动电话',
  `email` varchar(200) NOT NULL COMMENT 'Email',
  `password` varchar(50) NOT NULL COMMENT 'password',
  `password_salt` varchar(50) NOT NULL COMMENT 'password_salt',
  `id_card` varchar(64) NOT NULL COMMENT '身份证',
  `weixin` varchar(200) DEFAULT NULL COMMENT '微信号',
  `wx_unionid` varchar(128) NOT NULL DEFAULT '' COMMENT '微信扫码登陆',
  `default_channel_id` int(11) NOT NULL DEFAULT '0' COMMENT '默认切换的channel',
  `dimission` enum('0','1') NOT NULL DEFAULT '0' COMMENT '是否离职',
  `valid` enum('1','0') NOT NULL DEFAULT '1' COMMENT '是否禁用 0禁用',
  `add_datetime` date NOT NULL COMMENT '添加日期',
  `is_system` enum('0','1') NOT NULL DEFAULT '0' COMMENT '是否是系统人员',
  PRIMARY KEY (`employee_id`),
  UNIQUE KEY `hotel_mobile` (`company_id`,`mobile`),
  UNIQUE KEY `hotel_email` (`company_id`,`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `employee` */

insert  into `employee`(`employee_id`,`company_id`,`employee_name`,`sex`,`birthday`,`photo`,`mobile`,`email`,`password`,`password_salt`,`id_card`,`weixin`,`wx_unionid`,`default_channel_id`,`dimission`,`valid`,`add_datetime`,`is_system`) values (1,1,'有个员工','0','2018-01-06','',18500353881,'kefu@yelove.cn','75cc0db8ac6117d158272682cadd90e0','5483116858d36bd6d1f6c','',NULL,'',1,'0','1','2018-01-06','0'),(2,1,'员工2','0',NULL,'',0,'','','','',NULL,'',1,'0','1','0000-00-00','0');

/*Table structure for table `employee_sector` */

DROP TABLE IF EXISTS `employee_sector`;

CREATE TABLE `employee_sector` (
  `company_id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `channel_father_id` int(11) NOT NULL,
  `sector_father_id` int(11) NOT NULL COMMENT '部门ID',
  `sector_id` int(11) NOT NULL COMMENT '职位ID',
  `is_default` enum('0','1') NOT NULL DEFAULT '0' COMMENT '默认值',
  `employee_id` int(11) NOT NULL COMMENT '员工ID',
  `employee_name` varchar(128) NOT NULL COMMENT '员工姓名',
  `email` varchar(255) DEFAULT NULL,
  `weixin` varchar(64) DEFAULT NULL,
  `birthday` date NOT NULL COMMENT '生日',
  `photo` varchar(255) DEFAULT NULL COMMENT '头像',
  `id_card` varchar(50) DEFAULT NULL COMMENT '身份证',
  `address` varchar(255) DEFAULT NULL COMMENT '家庭地址',
  `sex` enum('0','1') NOT NULL COMMENT '男女',
  `mobile` bigint(11) NOT NULL,
  `present_address` varchar(255) DEFAULT NULL COMMENT '现住址',
  `positive_id_card` varchar(255) DEFAULT NULL COMMENT '身份证正面',
  `back_id_card` varchar(255) DEFAULT NULL COMMENT '身份证背面',
  `full_time` enum('0','1') NOT NULL DEFAULT '1' COMMENT '全职兼职',
  `entry_date` date DEFAULT NULL COMMENT '入职时间',
  `probation_date` date DEFAULT NULL COMMENT '试用期结束时间',
  `photo_labor` text COMMENT '劳动合同照片',
  `employee_number` varchar(255) DEFAULT NULL COMMENT '员工工号',
  `emergency_contact` varchar(50) DEFAULT NULL COMMENT '紧急联系人',
  `emergency_relation` varchar(50) DEFAULT NULL COMMENT '紧急联系人关系',
  `emergency_mobile` varchar(50) DEFAULT NULL COMMENT '紧急联系人电话',
  `dimission` enum('0','1') NOT NULL DEFAULT '0' COMMENT '是否离职',
  `dimission_date` date DEFAULT NULL COMMENT '离职时间',
  `valid` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0禁用',
  `add_datetime` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`employee_id`,`company_id`,`channel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `employee_sector` */

insert  into `employee_sector`(`company_id`,`channel_id`,`channel_father_id`,`sector_father_id`,`sector_id`,`is_default`,`employee_id`,`employee_name`,`email`,`weixin`,`birthday`,`photo`,`id_card`,`address`,`sex`,`mobile`,`present_address`,`positive_id_card`,`back_id_card`,`full_time`,`entry_date`,`probation_date`,`photo_labor`,`employee_number`,`emergency_contact`,`emergency_relation`,`emergency_mobile`,`dimission`,`dimission_date`,`valid`,`add_datetime`) values (1,1,1,1,8,'1',1,'有个员工','kefu@yelove.cn',NULL,'0000-00-00',NULL,NULL,NULL,'0',18500353881,NULL,NULL,NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0',NULL,'1','0000-00-00 00:00:00'),(1,1,1,2,3,'1',2,'员工2',NULL,NULL,'0000-00-00',NULL,NULL,NULL,'0',0,NULL,NULL,NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0',NULL,'1','0000-00-00 00:00:00');

/*Table structure for table `module` */

DROP TABLE IF EXISTS `module`;

CREATE TABLE `module` (
  `module_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '模块ID',
  `module_channel` enum('Booking','Management','Channel','Setting','Marketing') NOT NULL COMMENT '频道 Marketing营销管理',
  `module_name` varchar(100) NOT NULL COMMENT '模块名称',
  `module_father_id` int(11) NOT NULL COMMENT '父ID',
  `module` varchar(100) NOT NULL COMMENT '模块',
  `url` varchar(100) NOT NULL DEFAULT '' COMMENT '模块url',
  `module_view` varchar(50) NOT NULL DEFAULT '' COMMENT '模块VIEW',
  `module_order` int(11) NOT NULL DEFAULT '0' COMMENT '模块排序',
  `action` varchar(100) NOT NULL DEFAULT '' COMMENT '模块的action',
  `action_order` int(11) NOT NULL DEFAULT '0' COMMENT 'action排序',
  `submenu_father_id` int(11) NOT NULL DEFAULT '0' COMMENT 'method是否是submenu',
  `is_menu` enum('0','1') NOT NULL DEFAULT '1' COMMENT '是否在菜单显示',
  `is_recommend` enum('0','1') NOT NULL DEFAULT '0' COMMENT '是否是新推荐',
  `ico` varchar(100) NOT NULL DEFAULT '' COMMENT '图标',
  `is_release` enum('0','1') NOT NULL DEFAULT '1' COMMENT '是否对外发布使用',
  PRIMARY KEY (`module_id`),
  UNIQUE KEY `module-action` (`module`,`action`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;

/*Data for the table `module` */

insert  into `module`(`module_id`,`module_channel`,`module_name`,`module_father_id`,`module`,`url`,`module_view`,`module_order`,`action`,`action_order`,`submenu_father_id`,`is_menu`,`is_recommend`,`ico`,`is_release`) values (1,'Booking','预订模块',-1,'Booking','','',0,'',0,0,'1','0','fab fa-first-order-alt','1'),(2,'Booking','酒店预订',0,'HotelOrder','','Hotel',0,'',0,0,'1','0','fa fa-bed','1'),(3,'Booking','餐饮服务',0,'MealOrder','','Meal',0,'',0,0,'1','0','glyphicon glyphicon-cutlery','1'),(4,'Booking','商务会议',0,'MeetingBooking','','Meeting',0,'',0,0,'1','0','ti-comments-smiley','1'),(5,'Booking','健身娱乐',0,'SportBooking','','Sport',0,'',0,0,'1','0','ti-dribbble','1'),(6,'Booking','商品购买',0,'Shoping','','Shop',0,'',0,0,'1','0','ti-shopping-cart','1'),(7,'Booking','商务服务',0,'Services','','Services',0,'',0,0,'1','0','ti-tablet','1'),(8,'Booking','旅行路线',0,'Tour','','Tour',0,'',0,0,'1','0','fa fa-sun','1'),(9,'Management','公司管理',-1,'Management','','',0,'',0,0,'1','0','fas fa-chalkboard-teacher','1'),(10,'Management','员工管理',0,'Employee','','',0,'',0,0,'1','0','fas fa-user','1'),(11,'Management','文件管理',0,'File','','',0,'',0,0,'1','0','fa fa-file-word','1'),(12,'Booking','客房预订',2,'HotelOrder','','RoomOrder',0,'RoomOrder',0,0,'1','0','','1'),(13,'Booking','客房房态',2,'HotelOrder','','RoomStatus',0,'RoomStatus',0,0,'1','0','','1'),(14,'Setting','配置模块',-1,'Setting','','',0,'',0,0,'1','0','fas fa-puzzle-piece','1'),(15,'Setting','企业配置',0,'Channel','','',0,'',0,0,'1','0','ti-settings','1'),(16,'Setting','新增/编辑企业',15,'Channel','','',0,'AddEdit',0,0,'1','0','','1'),(17,'Setting','禁用企业',15,'Channel','','',0,'Delete',0,0,'0','0','','1'),(18,'Setting','配置企业',15,'Channel','','',0,'Config',0,0,'0','0','','1'),(19,'Setting','配置属性',18,'ChannelConfig','','',0,'',0,0,'0','0','','1'),(20,'Setting','房间配置',18,'ChannelConfig','','',0,'room',0,0,'0','0','','1'),(21,'Setting','上传文件',14,'Upload','','',0,'',0,0,'0','0','','1'),(22,'Setting','上传图片',14,'Upload','','',0,'images',0,0,'0','0','','1'),(23,'Setting','管理图片',14,'Upload','','',0,'manager',0,0,'0','0','','1'),(24,'Setting','房型配置',18,'ChannelConfig','','',0,'layout',0,0,'0','0','','1'),(25,'Setting','支付配置',0,'ChannelSetting','','PaymentType',0,'payment',0,0,'1','0','fa fa-credit-card','1'),(26,'Setting','新增/编辑支付',25,'ChannelSetting','','PaymentTypeAddEdit',0,'paymentAddEdit',0,0,'0','0','','1'),(27,'Setting','客源市场',0,'ChannelSetting','','',0,'',0,0,'1','0','fa fa-users','1'),(28,'Setting','新增/编辑客源市场',27,'ChannelSetting','','CustomerMarketAddEdit',0,'marketAddEdit',0,0,'0','0','','1'),(29,'Setting','价格设置',0,'PriceSetting','','',0,'',0,0,'1','0','ti-server','1'),(30,'Setting','客房价格',29,'PriceSetting','','RoomPriceList',0,'RoomPriceList',0,0,'1','0','','1'),(31,'Setting','酒店取消政策',37,'CancellationPolicy','','Policy',0,'doDefault',0,0,'1','0','','1'),(32,'Setting','新增/编辑取消政策',37,'CancellationPolicy','','PolicyAddEdit',0,'PolicyAddEdit',0,0,'0','0','','1'),(33,'Setting','价格类型',29,'PriceSetting','','RoomPriceSystem',0,'RoomPriceSystem',0,0,'0','0','','1'),(34,'Setting','添加价格类型',29,'PriceSetting','','RoomPriceSystemAddEdit',0,'RoomPriceSystemAddEdit',0,0,'0','0','','1'),(35,'Setting','添加客房价格',29,'PriceSetting','','RoomPriceAddEdit',0,'RoomPriceAddEdit',0,0,'0','0','','1'),(36,'Setting','企业管理',15,'Channel','','',0,'Manager',0,0,'1','0','','1'),(37,'Setting','取消政策',0,'CancellationPolicy','','',0,' ',0,0,'1','0','ti-receipt','1'),(38,'Setting','消费配置',0,'Consume','','',0,'',0,0,'1','0','fas fa-comment-dollar','1'),(39,'Setting','酒店消费类型',38,'Consume','','HotelConsume',0,'HotelConsume',0,0,'1','0','','1'),(40,'Setting','借物设置',38,'Consume','','Borrowing',0,'Borrowing',0,0,'1','0','','1'),(42,'Management','部门职位',10,'Employee','','SectorPosition',0,'SectorPosition',2,0,'1','0','','1'),(43,'Management','所有员工',10,'Employee','','Employee',0,'Employee',0,0,'1','0','','1'),(44,'Management','所有部门',10,'Employee','','Position',0,'Position',1,0,'0','0','','1'),(45,'Setting','客源市场',27,'ChannelSetting','','CustomerMarket',0,'market',0,0,'1','0','','1'),(46,'Setting','市场佣金',27,'ChannelSetting','','MarketCommission',0,'MarketCommission',0,0,'1','0','','1'),(47,'Marketing','营销管理',-1,'Marketing','','',0,'',0,0,'1','0','fab fa-salesforce','1'),(48,'Marketing','销售管理',0,'Marketing','','MarketingSales',0,'MarketingSales',0,0,'1','0','fab fa-cloudsmith','1'),(49,'Marketing','运营报表',0,'OperatingReports','','OperatingReports',0,'OperatingReports',0,0,'1','0','fas fa-chart-line','1'),(50,'Marketing','交班报表',49,'LogStatements','','ShiftStatements',0,'ShiftStatements',0,0,'1','0','','1'),(51,'Marketing','经理报表',49,'LogStatements','','ManagerReports',0,'ManagerReports',0,0,'1','0','','1'),(52,'Management','员工权限',10,'Employee','','Role',0,'Role',0,0,'1','0','','1');

/*Table structure for table `module_channel` */

DROP TABLE IF EXISTS `module_channel`;

CREATE TABLE `module_channel` (
  `module_id` int(11) NOT NULL COMMENT '模块ID',
  `channel_id` int(11) NOT NULL DEFAULT '1' COMMENT '公司/品牌',
  PRIMARY KEY (`module_id`,`channel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `module_channel` */

insert  into `module_channel`(`module_id`,`channel_id`) values (1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),(13,1),(14,1),(15,1),(16,1),(17,1),(18,1),(19,1),(20,1),(21,1),(22,1),(23,1),(24,1),(25,1),(26,1),(27,1),(28,1),(29,1),(30,1),(31,1),(32,1),(33,1),(34,1),(35,1),(36,1),(37,1),(38,1),(39,1),(40,1),(41,1),(42,1),(43,1),(44,1),(45,1),(46,1),(47,1),(48,1),(49,1),(50,1),(51,1),(52,1),(53,1),(54,1),(55,1),(56,1),(57,1),(58,1),(59,1),(60,1),(61,1),(62,1),(63,1),(64,1),(65,1),(66,1),(67,1),(68,1),(69,1),(70,1),(71,1),(72,1),(73,1),(74,1),(75,1),(76,1),(77,1),(78,1),(79,1),(80,1),(81,1),(82,1),(83,1),(84,1),(85,1),(86,1),(87,1),(88,1),(89,1),(90,1),(91,1),(92,1),(93,1),(94,1),(95,1),(96,1),(97,1),(98,1),(99,1),(100,1);

/*Table structure for table `module_company` */

DROP TABLE IF EXISTS `module_company`;

CREATE TABLE `module_company` (
  `module_id` int(11) NOT NULL COMMENT '模块ID',
  `company_id` int(11) NOT NULL DEFAULT '1' COMMENT '公司/品牌',
  PRIMARY KEY (`module_id`,`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `module_company` */

insert  into `module_company`(`module_id`,`company_id`) values (1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),(13,1),(14,1),(15,1),(16,1),(17,1),(18,1),(19,1),(20,1),(21,1),(22,1),(23,1),(24,1),(25,1),(26,1),(27,1),(28,1),(29,1),(30,1),(31,1),(32,1),(33,1),(34,1),(35,1),(36,1),(37,1),(38,1),(39,1),(40,1),(41,1),(42,1),(43,1),(44,1),(45,1),(46,1),(47,1),(48,1),(49,1),(50,1),(51,1),(52,1),(53,1),(54,1),(55,1),(56,1),(57,1),(58,1),(59,1),(60,1),(61,1),(62,1),(63,1),(64,1),(65,1),(66,1),(67,1),(68,1),(69,1),(70,1),(71,1),(72,1),(73,1),(74,1),(75,1),(76,1),(77,1),(78,1),(79,1),(80,1),(81,1),(82,1),(83,1),(84,1),(85,1),(86,1),(87,1),(88,1),(89,1),(90,1),(91,1),(92,1),(93,1),(94,1),(95,1),(96,1),(97,1),(98,1),(99,1),(100,1);

/*Table structure for table `payment_type` */

DROP TABLE IF EXISTS `payment_type`;

CREATE TABLE `payment_type` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL DEFAULT '0' COMMENT '0 通用支付方式',
  `payment_father_id` int(11) NOT NULL COMMENT '父ID',
  `payment_name` varchar(50) NOT NULL COMMENT '支付方式名称',
  `payment_en_name` varchar(50) NOT NULL DEFAULT '' COMMENT '英文名称',
  `valid` enum('0','1') NOT NULL DEFAULT '1' COMMENT '有效',
  PRIMARY KEY (`payment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

/*Data for the table `payment_type` */

insert  into `payment_type`(`payment_id`,`company_id`,`payment_father_id`,`payment_name`,`payment_en_name`,`valid`) values (1,0,0,'现金','','1'),(2,0,1,'现金支付','','1'),(3,0,0,'移动支付','','1'),(4,0,3,'支付宝','AliPay','1'),(5,0,3,'微信','','1'),(6,0,3,'Paypal','Paypal','1'),(7,0,0,'银行卡支付','','1'),(8,0,7,'银行卡','','1'),(9,0,0,'信用卡支付','','1'),(10,0,9,'信用卡','','1'),(11,0,9,'信用卡预授权','','1'),(12,0,0,'支票','','1'),(13,0,12,'现金支票','','1'),(14,0,12,'转帐支票','','1'),(15,0,0,'汇款','','1'),(16,0,15,'银行汇款','','1'),(17,0,0,'积分红包','','1'),(18,0,17,'积分抵扣','','1'),(19,0,17,'优惠卷','','1'),(20,0,17,'现金红包','','1'),(21,1,9,'我的支付','MyPay','1');

/*Table structure for table `role` */

DROP TABLE IF EXISTS `role`;

CREATE TABLE `role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '角色权限ID',
  `company_id` int(11) NOT NULL DEFAULT '0' COMMENT '公司ID',
  `channel_id` int(11) NOT NULL COMMENT '部门ID',
  `sector_id` int(11) NOT NULL DEFAULT '0' COMMENT '职位ID',
  `role_name` varchar(100) NOT NULL COMMENT '权限名称',
  `tag` varchar(128) DEFAULT NULL COMMENT '标签',
  `notes` varchar(200) NOT NULL DEFAULT '' COMMENT '备注',
  `is_system` enum('0','1') NOT NULL DEFAULT '0' COMMENT '是否是系统权限',
  `valid` enum('0','1') NOT NULL DEFAULT '1' COMMENT '是否有效',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `role` */

insert  into `role`(`role_id`,`company_id`,`channel_id`,`sector_id`,`role_name`,`tag`,`notes`,`is_system`,`valid`) values (1,1,1,2,'有个权限','财务权限','','0','1'),(2,1,1,0,'有个权限3','财务权限','','0','1');

/*Table structure for table `role_employee` */

DROP TABLE IF EXISTS `role_employee`;

CREATE TABLE `role_employee` (
  `company_id` int(11) NOT NULL COMMENT '公司ID',
  `role_id` int(11) NOT NULL COMMENT '角色权限',
  `employee_id` int(11) NOT NULL COMMENT '部门ID',
  PRIMARY KEY (`company_id`,`role_id`,`employee_id`),
  KEY `hotel_id` (`company_id`,`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `role_employee` */

insert  into `role_employee`(`company_id`,`role_id`,`employee_id`) values (1,1,1);

/*Table structure for table `role_module` */

DROP TABLE IF EXISTS `role_module`;

CREATE TABLE `role_module` (
  `role_id` int(11) NOT NULL DEFAULT '1' COMMENT '角色权限',
  `module_id` int(11) NOT NULL COMMENT '模块ID',
  PRIMARY KEY (`role_id`,`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `role_module` */

insert  into `role_module`(`role_id`,`module_id`) values (1,1),(1,2),(1,3),(1,4),(1,5),(1,6),(1,7),(1,8),(1,9),(1,10),(1,11),(1,12),(1,13),(1,14),(1,15),(1,16),(1,17),(1,18),(1,19),(1,20),(1,21),(1,22),(1,23),(1,24),(1,25),(1,26),(1,27),(1,28),(1,29),(1,30),(1,31),(1,32),(1,33),(1,34),(1,35),(1,36),(1,37),(1,38),(1,39),(1,40),(1,41),(1,42),(1,43),(1,44),(1,45),(1,46),(1,47),(1,48),(1,49),(1,50),(1,51),(1,52),(1,53),(1,54),(1,55),(1,56),(1,57),(1,58),(1,59),(1,60),(1,61),(1,62),(1,63),(1,64),(1,65),(1,66),(1,67),(1,68),(1,69),(1,70),(1,71),(1,72),(1,73),(1,74),(1,75),(1,76),(1,77),(1,78),(1,79),(1,80),(1,81),(1,82),(1,83),(1,84),(1,85),(1,86),(1,87),(1,88),(1,89),(1,90),(1,91),(1,92),(1,93),(1,94),(1,95),(1,96),(1,97),(1,98),(1,99),(1,100),(2,1),(2,2),(2,9),(2,10),(2,11),(2,12),(2,14),(2,15),(2,16),(2,18),(2,26),(2,27),(2,28),(2,37),(2,42),(2,44);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
