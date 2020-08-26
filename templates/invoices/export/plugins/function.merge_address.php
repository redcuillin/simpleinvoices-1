<?php
use Inc\Claz\Util;

/**
 * Function: merge_address
 *
 * Merges the city, state, and zip info onto one live and takes into account the commas
 *
 * @param array $params associative array containing the following key/values:
 *  field1  - normally city
 *  field2  - normally state
 *  field3  - normally zip
 *  street1 - street 1 added print the word "Address:" on the first line of the invoice
 *  street2 - street 2 added print the word "Address:" on the first line of the invoice
 *  class1  - the css class for the first td
 *  class2  - the css class for the second td
 *  colspan - the td colspan of the last td
 * @param object $smarty
 * @noinspection PhpUnusedParameterInspection
 */

function smarty_function_merge_address(array $params, object &$smarty) {
    global $LANG;
    $skipSection = false;
    $ma = '';
    // If any among city, state or zip is present with no street at all
    if ((isset($params['field1']) || isset($params['field2']) || isset($params['field3'])) && !isset($params['street1']) && !isset($params['street2'])) {
        $ma .= "<tr>" .
                   "<td class='" . Util::htmlsafe($params['class1']) . "'>{$LANG['address']}:</td>" .
                   "<td class='" . Util::htmlsafe($params['class2']) . "' colspan='" . Util::htmlsafe($params['colspan']) . "'>";
        $skipSection = true;
    }
    // If any among city, state or zip is present with atleast one street value
    if (($params ['field1'] != null || $params ['field2'] != null || $params ['field3'] != null) && !$skipSection) {
        $ma .= "<tr>" .
                    "<td class='" . Util::htmlsafe($params['class1']) . "'></td>" .
                    "<td class='" . Util::htmlsafe($params['class2']) . "' colspan='" . Util::htmlsafe($params['colspan']) . "'>";
    }
    if (isset($params ['field1'])) {
        $ma .= Util::htmlsafe($params['field1']);
    }

    if (isset($params['field1']) && isset($params['field2'])) {
        $ma .= ", ";
    }

    if (isset($params['field2'])) {
        $ma .= Util::htmlsafe($params['field2']);
    }

    if ((isset($params['field1']) || isset($params['field2'])) && $params ['field3'] != null) {
        $ma .= ", ";
    }

    if ($params ['field3'] != null) {
        $ma .= Util::htmlsafe($params['field3']);
    }

    $ma .= "</td></tr>";
    echo $ma;
}
