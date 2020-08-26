<?php

use Inc\Claz\DomainId;
use Inc\Claz\Util;

/**
 * Generate link for online payments
 * @param array $params
 * @param object $smarty
 * @noinspection PhpUnusedParameterInspection
 */
function smarty_function_online_payment_link(array $params, object &$smarty): void
{
    $domainId = DomainId::get($params['domain_id']);

    $url = Util::getURL();
    if (in_array("paypal", explode(",", $params['type']))) {

        $link = '<a	href="https://www.paypal.com/xclick/business=' . $params['business'] .
                    '&item_name=' . $params['item_name'] .
                    '&invoice=' . $params['invoice'] .
                    '&amount=' . number_format($params['amount'], 2, '.', '') .
                    '&currency_code=' . $params['currency_code'] .
                    '&notify_url=' . $params['notify_url'] .
                    '&return=' . $params['return_url'] .
                    '&no_shipping=1&no_note=1&custom=domain_id:' . $domainId . ';" >';

        if ($params['include_image'] == "true") {
            $link .= "<img src='{$url}/images/pay_with_paypal.gif' alt=''/>";
        } else {
            $link .= $params['link_wording'];
        }

        $link .= "</a>";

        echo $link;
    }

    if (in_array("eway_shared", explode(",", $params['type']))) {

        $link = '<a	href="https://www.paypal.com/xclick/business=' . $params['business'] .
				   '&item_name=' . $params['item_name'] . '&invoice=' . $params['invoice'] .
				   '&amount=' . number_format($params['amount'], 2, '.', '') .
                   '&currency_code=' . $params['currency_code'] .
				   '&return=http://vcsweb.com.au&no_shipping=1&no_note=1" >';

        if ($params['include_image'] == "true") {
            $link .= "<img src='{$url}/images/pay_with_eway.gif' alt=''/>";
        } else {
            $link .= $params['link_wording'];
        }

        $link .= "</a>";

        echo $link;
    }

}

