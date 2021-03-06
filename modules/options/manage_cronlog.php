<?php

use Inc\Claz\CronLog;
use Inc\Claz\DomainId;
use Inc\Claz\Util;

global $pdoDb, $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$cronlogs = CronLog::getOne($pdoDb, DomainId::get());
$smarty -> assign("cronlogs",$cronlogs);

$smarty -> assign('pageActive', 'options');
$smarty -> assign('active_tab', '#setting');
