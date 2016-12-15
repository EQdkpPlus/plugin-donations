<?php
/*	Project:	EQdkp-Plus
 *	Package:	MediaCenter Plugin
 *	Link:		http://eqdkp-plus.eu
 *
 *	Copyright (C) 2006-2015 EQdkp-Plus Developer Team
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU Affero General Public License as published
 *	by the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU Affero General Public License for more details.
 *
 *	You should have received a copy of the GNU Affero General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
if (!defined('EQDKP_INC'))
{
  header('HTTP/1.0 404 Not Found');exit;
}

$donationsSQL = array(

  'uninstall' => array(
    1     => 'DROP TABLE IF EXISTS `__plugin_donations`',
  ),

  'install'   => array(
	1 => "CREATE TABLE `__plugin_donations` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`date` INT(11) UNSIGNED NOT NULL DEFAULT '0',
	`token` VARCHAR(50) NOT NULL DEFAULT '0',
	`user_id` INT(11) NOT NULL DEFAULT '-1',
	`username` VARCHAR(255) NULL DEFAULT NULL,
	`amount` FLOAT(10,3) NOT NULL DEFAULT '0.000',
	`currency` VARCHAR(3) NULL DEFAULT NULL,
	`status` VARCHAR(50) NULL DEFAULT 'start',
	`completed` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	`description` VARCHAR(255) NULL DEFAULT NULL,
	`public` INT(1) NULL DEFAULT '0',
	`method` VARCHAR(50) NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
",
));

?>