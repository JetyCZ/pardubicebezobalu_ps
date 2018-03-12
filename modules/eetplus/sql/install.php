<?php
/**
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

$sql = array();

  
 

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'eetplus` (
      `id_eetplus` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_order` int(10) unsigned NOT NULL DEFAULT "0",
  `fik` varchar(39) DEFAULT NULL,
  `response` text,
  `input` varchar(250) DEFAULT NULL,
  `ceny` text  DEFAULT NULL,
  `action` varchar(8) DEFAULT "trzba",
  `date_akc` varchar(30) DEFAULT NULL,
  `castka` float(12,2) DEFAULT NULL,
  `date_trzby` varchar(30) DEFAULT NULL,
  `codes` text,
  `email` varchar(150) DEFAULT NULL,
    PRIMARY KEY  (`id_eetplus`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';


$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'eetplus_sandbox` (
     `id_eetplus` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_order` int(10) unsigned NOT NULL DEFAULT "0",
  `fik` varchar(39) DEFAULT NULL,
  `response` text,
  `input` varchar(250) DEFAULT NULL,
  `ceny` text,
  `action` varchar(8) DEFAULT "trzba",
  `date_akc` varchar(30) DEFAULT NULL,
  `castka` float(12,2) DEFAULT NULL,
  `date_trzby` varchar(30) DEFAULT NULL,
  `codes` text,
  `email` varchar(150) DEFAULT NULL,
    PRIMARY KEY  (`id_eetplus`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

 
$sql[] = 'CREATE TABLE  IF NOT EXISTS `'._DB_PREFIX_.'eetplus_error` (
  `id_eetplus_error` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_eetplus` int(10) unsigned DEFAULT NULL,
  `error` varchar(250) DEFAULT NULL,
  `sandbox` tinyint(3) unsigned DEFAULT "0",
  PRIMARY KEY (`id_eetplus_error`),
  KEY `id_eetplus` (`id_eetplus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
