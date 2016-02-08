<?php

defined('DS') ? null : define('DS',DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT',dirname(dirname(dirname(dirname(__FILE__)))).DS."vsm");
defined('LIB_PATH') ? null : define('LIB_PATH',SITE_ROOT.DS."includes".DS."lib");
defined('CONFIG_PATH') ? null : define('CONFIG_PATH',SITE_ROOT.DS."includes".DS."config");
defined("LOG_PATH") ? null : define("LOG_PATH",SITE_ROOT.DS."logs");

require_once(CONFIG_PATH.DS."database.config.php");
require_once(CONFIG_PATH.DS."constants.config.php");
//Decide The Order of the Classes properly
require_once(LIB_PATH.DS.'Log.class.php');
require_once(LIB_PATH.DS.'Database.class.php');
require_once(LIB_PATH.DS.'User.class.php');
require_once(LIB_PATH.DS.'Session.class.php');
require_once(LIB_PATH.DS.'StockHolder.class.php');
/*
	Settings for StockHolder class
*/
	StockHolder::$initial_amount = 10000;

require_once(LIB_PATH.DS.'StockCompany.class.php');
require_once(LIB_PATH.DS.'Transaction.class.php');
require_once(LIB_PATH.DS.'Stock.class.php');
require_once(LIB_PATH.DS.'HolderStocks.class.php');
require_once(LIB_PATH.DS.'News.class.php');
require_once(LIB_PATH.DS.'Redeem.class.php');
require_once(LIB_PATH.DS.'StockControl.class.php');
require_once(LIB_PATH.DS.'StockHistory.class.php');

/* 
 *Lazy Loading
 */

function autoload($class){
	include_once(LIB_PATH.DS.$class.'.class.php');
}

spl_autoload_register('autoload');

date_default_timezone_set("Asia/Calcutta");

?>