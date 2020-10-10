{include file=$path|cat:"library/reportTitle.tpl" title=$title}
{include file=$path|cat:"library/exportButtons.tpl"
		 params=[
			 'customerId' => $customerId|urlencode,
			 'endDate' => $endDate|urlencode,
			 'fileName' => "reportProductsSoldByCustomer",
			 'startDate' => $startDate|urlencode,
		 	 'title' => $title|urlencode
		 ]
}
{if $menu}
	<form name="frmpost" method="POST" id="frmpost"
		  action="index.php?module=reports&amp;view=reportProductsSoldByCustomer">
		<table class="center">
			{include file=$path|cat:"library/dateRangePrompt.tpl"}
			{include file="templates/default/reports/library/customerSelectList.tpl"}
		</table>
		<br/>
		{include file=$path|cat:"library/runReportButton.tpl" value="reportProductsSoldByCustomer" label=$LANG.runReport}
		<br/>
	</form>
{/if}
{if isset($smarty.post.submit) || $view == "export"}
	{include file=$path|cat:"reportProductsSoldByCustomerBody.tpl"}
{/if}
