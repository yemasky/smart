SET PASSWORD FOR root@localhost = PASSWORD('root'); 
ALTER USER 'root'@'localhost' IDENTIFIED BY 'mySql22582!'
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'mySql22582!';
create user rpl_user@'%' identified WITH mysql_native_password by 'rpl_Pass123!';

ALTER TABLE `channel_consume` AUTO_INCREMENT=12;
SHOW PROCESSLIST;

STATUS 
EXPLAIN SELECT t1.*, t2.name AS projectName, t3.title AS storyTitle, t3.status AS storyStatus, t3.version AS latestStoryVersion, t4.name AS taskName, t5.title AS planName 
FROM `bug` AS t1  LEFT JOIN `project` AS t2  ON t1.project = t2.id  
LEFT JOIN `story` AS t3  ON t1.story = t3.id  
LEFT JOIN `task` AS t4  ON t1.task = t4.id  
LEFT JOIN `productplan` AS t5  ON t1.plan = t5.id  WHERE t1.id  = '964'

GRANT SELECT,INSERT ON `q_pms_log`.* TO qa_db@127.0.0.1 IDENTIFIED BY 'qa_db_976567jyyui';
GRANT SELECT,INSERT,UPDATE,DELETE ON `marketing`.* TO marketing@127.0.0.1 IDENTIFIED BY '`marketing`123xsda3423s';
GRANT SELECT,INSERT,UPDATE,DELETE ON `release_marketing`.* TO r_marketing@127.0.0.1 IDENTIFIED BY '`release_marketing`as2123';

GRANT SELECT,INSERT,UPDATE,DELETE ON `q_marketing`.* TO qa_db@127.0.0.1 IDENTIFIED BY 'qa_db_976567jyyui';
GRANT SELECT,INSERT,UPDATE,DELETE ON `q_finance`.* TO qa_db@127.0.0.1 IDENTIFIED BY 'qa_db_976567jyyui';

GRANT SELECT,INSERT,UPDATE,DELETE ON `test`.* TO test@127.0.0.1 IDENTIFIED BY 'test';


GRANT SELECT,INSERT,UPDATE,DELETE,TRUNCATE ON `hotel_finance_beta`.* TO weixin_book_beta@127.0.0.1 IDENTIFIED BY '`weixin_book`_5678987654';

GRANT SELECT,INSERT,UPDATE ON `hotel_finance`.* TO r_marketing@127.0.0.1 IDENTIFIED BY '`release_marketing`as2123';
GRANT SELECT,INSERT,UPDATE ON `releasel_finance`.* TO r_marketing@127.0.0.1 IDENTIFIED BY '`release_marketing`as2123';

#aot
GRANT SELECT,UPDATE ON `wecenter_test`.* TO ota_direct@127.0.0.1 IDENTIFIED BY 'ota_direct567890';
GRANT SELECT,UPDATE ON `release_pms`.* TO ota_direct@127.0.0.1 IDENTIFIED BY 'ota_direct567890';
GRANT SELECT,INSERT ON `release_log`.* TO ota_direct@127.0.0.1 IDENTIFIED BY 'ota_direct567890';


GRANT SELECT,INSERT,UPDATE ON `ota_direct`.* TO ota_direct@127.0.0.1 IDENTIFIED BY 'ota_direct567890';

GRANT SELECT,INSERT,UPDATE ON `wecenter_beta`.* TO dbBeta@127.0.0.1 IDENTIFIED BY 'dbBeta567890';

CREATE USER dumper_01@'127.0.0.1' IDENTIFIED BY 'dumper_01^&sadfafsd1212';
GRANT SELECT,SHOW VIEW,LOCK TABLES,TRIGGER ON `wecenter_test`.* TO dumper_01@'127.0.0.1';
GRANT SELECT,SHOW VIEW,LOCK TABLES,TRIGGER ON `wecenter_work_start`.* TO dumper_01@'127.0.0.1';
GRANT SELECT,SHOW VIEW,LOCK TABLES,TRIGGER ON `weixin_book`.* TO dumper_01@'127.0.0.1';

GRANT SELECT,SHOW VIEW,LOCK TABLES,TRIGGER ON `release_pms`.* TO dumper_01@'127.0.0.1';
GRANT SELECT,SHOW VIEW,LOCK TABLES,TRIGGER ON `release_work`.* TO dumper_01@'127.0.0.1';
GRANT SELECT,SHOW VIEW,LOCK TABLES,TRIGGER ON `release_book`.* TO dumper_01@'127.0.0.1';
GRANT SELECT,SHOW VIEW,LOCK TABLES,TRIGGER ON `release_marketing`.* TO dumper_01@'127.0.0.1';






#grant show view on tempdb.* to dumper@'127.0.0.1';
#grant lock tables on tempdb.* to dumper@'127.0.0.1';
#grant trigger on tempdb.* to dumper@'127.0.0.1';

SHOW VARIABLES LIKE '%iso%';

SET GLOBAL tx_isolation='READ-COMMITTED'; 


INSERT INTO 
`room_layout_price_system_name` (
room_layout_price_system_id,room_layout_price_system_father_id,hotel_id,
policy_id,room_layout_price_system_name,room_layout_price_system_type,
room_layout_price_system_valid )
SELECT 
room_layout_price_system_id,room_layout_price_system_father_id,hotel_id,
policy_id,room_layout_price_system_name,room_layout_price_system_type,
room_layout_price_system_valid 
FROM `room_layout_price_system`

SELECT * FROM `hotel_modules` WHERE hotel_id = 4
SELECT * FROM `hotel_modules` WHERE modules_id = 55
SELECT * FROM `modules` WHERE modules_id = 55
DELETE FROM `hotel_modules` WHERE hotel_id = 4

UPDATE  `modules` m,  `hotel_modules` hm
SET m.modules_father_id=hm.hotel_modules_father_id, m.modules_name=hm.hotel_modules_name
WHERE m.modules_id=hm.modules_id AND  hm.hotel_id=4 AND hm.hotel_modules_name != ''、

SELECT * FROM `room_layout_price_system` WHERE hotel_id = 3

SELECT * FROM `employee` WHERE hotel_id = 8


SELECT m.member_name,m.member_nick_name,mfv.fans_weixin,mfv.fans_wx_openid,mfv.add_datetime FROM `member` m LEFT JOIN `media_fans_visits` mfv ON m.member_id = mfv.member_id








