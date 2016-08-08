<?php
header ( "Content-type: text/xml" );

$dir   = (isset($_POST['sortorder'])) ? $_POST ['sortorder'] : "DESC";
$sort  = (isset($_POST['sortname']) ) ? $_POST ['sortname']  : "id";
$rp    = (isset($_POST['rp'])       ) ? $_POST ['rp']        : "25";
$page  = (isset($_POST['page'])     ) ? $_POST ['page']      : "1";

$inventory_all   = Inventory::select_all(     '', $sort, $dir, $rp, $page);
$sth_count_rows  = Inventory::select_all('count', $sort, $dir, $rp, $page);

$count = $sth_count_rows;

$xml  = "";
$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";
foreach ( $inventory_all as $row ) {
    $xml .= "<row id='" . $row ['id'] . "'>";
    $xml .= 
      "<cell><![CDATA[
         <a class='index_table' title='$LANG[view] $row[name]'
            href='index.php?module=inventory&view=view&id=$row[id]'>
           <img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' />
         </a>
         <a class='index_table' title='$LANG[edit] $row[name]'
            href='index.php?module=inventory&view=edit&id=$row[id]'>
           <img src='images/common/edit.png' height='16' border='-5px' padding='-4px' valign='bottom' />
         </a>
       ]]></cell>";
    $xml .= "<cell><![CDATA[$row[date]]]></cell>";
    $xml .= "<cell><![CDATA[$row[description]]]></cell>";
    $xml .= "<cell><![CDATA[" . siLocal::number($row['quantity']  ) . "]]></cell>";
    $xml .= "<cell><![CDATA[" . siLocal::number($row['cost']      ) . "]]></cell>";
    $xml .= "<cell><![CDATA[" . siLocal::number($row['total_cost']) . "]]></cell>";
    $xml .= "</row>";
}
$xml .= "</rows>";
echo $xml;
