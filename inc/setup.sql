CREATE TABLE `files` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`guid` VARCHAR(36) NULL DEFAULT uuid() COLLATE 'latin1_swedish_ci',
	`dt` DATETIME NULL DEFAULT current_timestamp(),
	`path` VARCHAR(1024) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`notify` TINYINT(4) NOT NULL DEFAULT '1',
	`protect` TINYINT(4) NOT NULL DEFAULT '0',
	`pw` VARCHAR(256) NULL DEFAULT '' COLLATE 'latin1_swedish_ci',
	`expires` DATE NULL DEFAULT NULL,
	`active` TINYINT(4) NULL DEFAULT '1',
	PRIMARY KEY (`id`) USING BTREE
);

CREATE TABLE `download_logs` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`file_id` INT(11) NOT NULL,
	`dt` DATETIME NULL DEFAULT current_timestamp(),
	`ip_address` VARCHAR(25) NOT NULL COLLATE 'latin1_swedish_ci',
	PRIMARY KEY (`id`) USING BTREE,
	INDEX `file_id` (`file_id`) USING BTREE,
	CONSTRAINT `download_logs_ibfk_1` FOREIGN KEY (`file_id`) REFERENCES `files`.`files` (`id`) ON UPDATE RESTRICT ON DELETE RESTRICT
);

CREATE TABLE `files` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`guid` VARCHAR(36) NULL DEFAULT uuid() COLLATE 'latin1_swedish_ci',
	`dt` DATETIME NULL DEFAULT current_timestamp(),
	`path` VARCHAR(1024) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`notify` TINYINT(4) NOT NULL DEFAULT '1',
	`protect` TINYINT(4) NOT NULL DEFAULT '0',
	`pw` VARCHAR(256) NULL DEFAULT '' COLLATE 'latin1_swedish_ci',
	`expires` DATE NULL DEFAULT NULL,
	`active` TINYINT(4) NULL DEFAULT '1',
	PRIMARY KEY (`id`) USING BTREE
)

CREATE TABLE `settings` (
	`username` VARCHAR(255) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`password` VARCHAR(255) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`protocol_type` VARCHAR(10) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`use_bitly` INT(11) NULL DEFAULT NULL,
	`bitly_token` VARCHAR(255) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`use_email` INT(11) NULL DEFAULT NULL,
	`email_notification` VARCHAR(255) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`smtp_username` VARCHAR(255) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`smtp_password` VARCHAR(255) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`smtp_server` VARCHAR(255) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`smtp_port` VARCHAR(255) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`smtp_security` INT(11) NULL DEFAULT NULL,
	`smtp_security_type` INT(11) NULL DEFAULT NULL,
	`smtp_from_address` VARCHAR(255) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci'
);

CREATE VIEW viewalldownloads AS  
SELECT 
	d.dt AS dt,
	d.ip_address AS ip_address,
	f.path AS path 
from download_logs d 
	join files f ON f.id = d.file_id;

CREATE VIEW viewallfiles AS 
select 
	f.id AS id,
	f.guid AS guid,
	f.dt AS dt,
	f.path AS path,
	f.notify AS notify,
	f.protect AS protect,
	f.expires AS expires,
	f.active AS active,
	f.pw AS pw,
	b.bitly_id AS bitly_id,
	b.bitly_url AS bitly_url,
	count(l.id) AS downloads 
from files f 
	left join bitly_links b on f.id = b.file_id
	left join download_logs l on l.file_id = f.id 
group by f.id,f.guid,f.dt,f.path,f.notify,f.protect,f.expires,f.active,b.bitly_id,b.bitly_url;