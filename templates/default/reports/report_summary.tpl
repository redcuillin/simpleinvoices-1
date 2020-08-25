
<!--suppress HtmlFormInputWithoutLabel -->
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=reports&amp;view=report_summary">
<table class="center">
    <tr>
        <td>{$LANG.Start_date}
                <input type="text" class="validate[required,custom[date],length[0,10]] date-picker" size="10"
                       name="start_date" id="date1" value='{if isset($start_date)}{$start_date}{/if}' />
         </td>
        <td>
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
        </td>
        <td>{$LANG.end_date}
          <input type="text" class="validate[required,custom[date],length[0,10]] date-picker" size="10"
                 name="end_date" id="date1" value='{if isset($end_date)}{$end_date}{/if}' />
        </td>
    </tr>
</table>
<br />
<table class="center" >
    <tr>
        <td>
            <button type="submit" class="positive" name="submit" value="{$LANG.insert_biller}">
                <img class="button_img" src="../../../images/tick.png" alt="" />
                {$LANG.run_report}
            </button>

        </td>
    </tr>
</table>
</form>
<div id="top">
    <h3>{$LANG.expense} {$LANG.account} {$LANG.summary} {$LANG.for} {$LANG.the} {$LANG.period}
        {if isset($start_date)}{$start_date}{/if} to {if isset($end_date)}{$end_date}{/if}
    </h3>
</div>

<table class="center">
    <tr>
        <td  class="details_screen">
            <b>{$LANG.account_uc}</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            <b>{$LANG.amount_uc}</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            <b>{$LANG.tax}</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            <b>{$LANG.total}</b>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            <b>{$LANG.status}</b>
        </td>
	</tr>
 {foreach item=account from=$accounts}
    <tr>
        <td class="details_screen">
            {$account.account}
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            {$account.expense|siLocal_number}
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            {if $account.tax != ""}
                {$account.tax|siLocal_number}
            {/if}
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
        {if $account.total != ""}
            {$account.total|siLocal_number}
        {else}
            {$account.expense|siLocal_number}
        {/if}
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            {$account.status_wording}
        </td>
	</tr>
 {/foreach}
 </table>

<div id="top"><h3>{$LANG.invoice_uc}/{$LANG.quote_uc} {$LANG.summary} {$LANG.for} {$LANG.the} {$LANG.period}
        {if isset($start_date)}{$start_date}{/if} {$LANG.to} {if isset($end_date)}{$end_date}{/if}</h3></div>

<table class="center">
    <tr>
        <td  class="details_screen">
            <b>{$LANG.id}</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            <b>{$LANG.biller}</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            <b>{$LANG.customer}</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            <b>{$LANG.amount_uc}</b>
        </td>
	</tr>
 {section name=invoice loop=$invoices}
    {if $invoices[invoice].preference != $invoices[invoice][index_prev].preference AND $smarty.section.invoice.index != 0}
        <tr><td><br /></td></tr>
    {/if}
    <tr>
        <td class="details_screen">{if isset($index)}{$index}{/if}
            {$invoices[invoice].preference}
            {$invoices[invoice].index_id}
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            {$invoices[invoice].biller}
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            {$invoices[invoice].customer}
        </td>
        <td>
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            {$invoices[invoice].total|siLocal_number}
        </td>
	</tr>
 {/section}
 </table>


<div id="top">
    <h3>
        {$LANG.payment_uc} {$LANG.summary} {$LANG.for} {$LANG.the} {$LANG.period}
        {if isset($start_date)}{$start_date}{/if} to {if isset($end_date)}{$end_date}{/if}
    </h3>
</div>

<table class="center">
    <tr>
        <td  class="details_screen">
            <b>{$LANG.id}</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            <b>{$LANG.biller}</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            <b>{$LANG.customer}</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            <b>{$LANG.type}</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            <b>{$LANG.amount_uc}</b>
        </td>
	</tr>
 {foreach item=payment from=$payments}
    <tr>
        <td class="details_screen">
            {$payment.id}
        </td>
        <td></td>
        <td class="details_screen">
            {$payment.bname}
        </td>
        <td></td>
        <td class="details_screen">
            {$payment.cname}
        </td>
        <td></td>
        <td class="details_screen">
            {$payment.type}
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td  class="details_screen">
            {$payment.ac_amount|siLocal_number}
        </td>
	</tr>
 {/foreach}
 </table>
    
