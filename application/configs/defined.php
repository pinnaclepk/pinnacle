<?php

$translate = Zend_Registry::get("translate");

if (!defined('TEMPLATE_PATH'))
    define('TEMPLATE_PATH', '/template/');

### SITE CONFIGURATION ###
if (!defined('SITE_TITLE'))
    define('SITE_TITLE', $translate->_('PINNACLE Admin'));
if (!defined('SITE_NAME'))
    define('SITE_NAME', $translate->_('PINNACLE Admin'));

if (!defined('SITE_ALIAS'))
    define('SITE_ALIAS', '');
if (!defined('SITE_PATH'))
    define('SITE_PATH', 'http://' . $_SERVER['HTTP_HOST'] . SITE_ALIAS);
if (!defined('SITE_URL'))
    define('SITE_URL', SITE_PATH . "/index.php");


if (!defined('MODULE_USER'))
    define('MODULE_USER', 1000);  //User
if (!defined('MODULE_COIN'))
    define('MODULE_COIN', 1001);
if (!defined('MODULE_PLAN'))
    define('MODULE_PLAN', 1002);
if (!defined('MODULE_PACKAGE'))
    define('MODULE_PACKAGE', 1003);
?>