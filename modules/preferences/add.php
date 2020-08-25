<?php

use Inc\Claz\Preferences;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

//if valid then do save
if (!empty($_POST['p_description'])) {
	include "modules/preferences/save.php";
}

$smarty->assign('preferences',Preferences::getActivePreferences());
$smarty->assign('defaults', SystemDefaults::loadValues());
$smarty->assign('localelist', Zend_Locale::getLocaleList());

$smarty->assign('pageActive', 'preference');
$smarty->assign('subPageActive', 'preferences_add');
$smarty->assign('active_tab', '#setting');
