<?php

use Inc\Claz\ProductAttributeType;
use Inc\Claz\Util;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();
error_log("_POST: " . print_r($_POST, true));
//if valid then do save
if (!empty($_POST['name'])) {
    include "modules/product_attribute/save.php";
} else {
    $smarty->assign("types", ProductAttributeType::getAll());

    $smarty->assign('pageActive', 'product_attribute_add');
    $smarty->assign('activeTab', '#product');
}
