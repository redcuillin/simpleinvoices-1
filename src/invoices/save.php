<?php
include('./config/config.php');

$conn = mysql_connect( $db_host, $db_user, $db_password);
mysql_select_db( $db_name, $conn);

# Deal with op and add some basic sanity checking

$action = !empty( $_POST['action'] ) ? addslashes( $_POST['action'] ) : NULL;


#insert invoice_total - start
else if ( isset( $_POST['invoice_style'] ) && $_POST['invoice_style'] === 'insert_invoice_total' ) {

	$sql = "INSERT into
			si_invoices (inv_id, inv_biller_id, inv_customer_id, inv_type,
			inv_preference, inv_date, inv_note)
		VALUES
			(
				'',
				'$_POST[sel_id]',
				'$_POST[select_customer]',
				'1',
				'$_POST[select_preferences]',
				now(),
				'$_POST[invoice_total_note]'
			)";

	if (mysql_query($sql)) {
		$display_block =  "Processing invoice, <br> you will be redirected Quick View of this invoice";
	} else {
		$display_block =  "Something went wrong, please try adding the invoice again";
	}

	#get the invoice id from the insert
	$invoice_id = mysql_insert_id();


	#tax percentage query
	$print_tax_percentage = "SELECT * FROM si_tax WHERE tax_id ='$_POST[select_tax]'";
	$result_print_tax_percentage = mysql_query($print_tax_percentage, $conn) or die(mysql_error());


	while ($Array_tax = mysql_fetch_array($result_print_tax_percentage)) {
		$tax_idField = $Array_tax['tax_id'];
		$tax_descriptionField = $Array_tax['tax_description'];
		$tax_percentageField = $Array_tax['tax_percentage'];

	};

	$actual_tax = $tax_percentageField / 100;
	$total_invoice_total_tax = $_POST[inv_it_gross_total] * $actual_tax ;
	$total_invoice_total = $total_invoice_total_tax + $_POST[inv_it_gross_total] ;	
		

	$sql_items = "INSERT into
				si_invoice_items
			VALUES
				(
					'',
					$invoice_id,
					'1',
					'00',
					'00',
					'$_POST[select_tax]',
					$tax_percentageField,
					$total_invoice_total_tax,		
					'$_POST[inv_it_gross_total]',
					'$_POST[i_description]',
					$total_invoice_total
				)
			";


	if (mysql_query($sql_items)) {
		$display_block_items =  "Processing invoice items<br> you will be redirected back to the Quick View of this invoice";
	} else { die(mysql_error());
	}

	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=1;URL=print_quick_view.php?submit=$invoice_id&invoice_style=Total>";

}
#insert invoice_total - end

#EDIT invoice_total
else if ( isset( $_POST['invoice_style'] ) && $_POST['invoice_style'] === 'edit_invoice_total' ) {

	$invoice_id = $_POST[invoice_id];

	#update the si_invoices table with customer etc  stuff - start
	$sql = "UPDATE
			si_invoices
		SET
			inv_biller_id = '$_POST[sel_id]',
			inv_customer_id = '$_POST[select_customer]',
			inv_preference = '$_POST[select_preferences]'
		WHERE
			inv_id = $invoice_id";

	if (mysql_query($sql)) {
		$display_block =  "Processing invoice, <br> you will be redirected Quick View of this invoice";
	} else {
		$display_block =  "Something went wrong, please try adding the invoice again";
	}
	
	#update the si_invoices table with customer etc  stuff - end
	
	#tax percentage query -start
	$print_tax_percentage = "SELECT * FROM si_tax WHERE tax_id ='$_POST[select_tax]'";
	$result_print_tax_percentage = mysql_query($print_tax_percentage, $conn) or die(mysql_error());


	while ($Array_tax = mysql_fetch_array($result_print_tax_percentage)) {
		$tax_idField = $Array_tax['tax_id'];
		$tax_descriptionField = $Array_tax['tax_description'];
		$tax_percentageField = $Array_tax['tax_percentage'];

	};
	#tax info - end	
	
	#calcultate the invoice total - start
	$actual_tax = $tax_percentageField / 100;
	$total_invoice_total_tax = $_POST[inv_it_gross_total] * $actual_tax ;
	$total_invoice_total = $total_invoice_total_tax + $_POST[inv_it_gross_total] ;
	#calcultate the invoice total - end

	#update the si_invoice_items table - which tax,description etc.. - start
	$sql_items = "UPDATE
				si_invoice_items
			SET
				inv_it_tax_id = '$_POST[select_tax]',
				inv_it_tax = $tax_percentageField,
				inv_it_tax_amount = $total_invoice_total_tax,
				inv_it_gross_total = '$_POST[inv_it_gross_total]',
				inv_it_description = '$_POST[i_description]',
				inv_it_total = $total_invoice_total
			WHERE
				inv_it_invoice_id = $invoice_id";


	if (mysql_query($sql_items)) {
		$display_block_items =  "Processing invoice items<br> you will be redirected back to the Quick View of this invoice";
	} else { die(mysql_error());
}

	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=1;URL=print_quick_view.php?submit=$invoice_id&invoice_style=Total>";

}

#EDIT invoce total - end


#insert invoice_itemised

else if ( isset( $_POST['invoice_style'] ) && $_POST['invoice_style'] === 'insert_invoice_itemised' ) {

	$invoice_itemised_note_field = $_POST[invoice_itemised_note];
	$sql = "INSERT into
		si_invoices (inv_id, inv_biller_id, inv_customer_id, inv_type,
		inv_preference, inv_date, inv_note)
		values ('','$_POST[sel_id]','$_POST[select_customer]', 2,'$_POST[select_preferences]',now(),'$invoice_itemised_note_field')";

	if (mysql_query($sql)) {
		$display_block =  "Processing invoice, <br> you will be redirected back to the Quick View of this invoice";
	} else {
		$display_block =  "Something went wrong, please try adding the invoice again";
	}

	#get the invoice id from the insert
	$invoice_id = mysql_insert_id();


	#tax percentage query
	$print_tax_percentage = "SELECT * FROM si_tax WHERE tax_id ='$_POST[select_tax]'";
	$result_print_tax_percentage = mysql_query($print_tax_percentage, $conn) or die(mysql_error());


	while ($Array_tax = mysql_fetch_array($result_print_tax_percentage)) {
		$tax_idField = $Array_tax['tax_id'];
		$tax_descriptionField = $Array_tax['tax_description'];
		$tax_percentageField = $Array_tax['tax_percentage'];

	};
/*
	#product info query
	$print_products_info = "SELECT * FROM si_products WHERE prod_id ='$_POST[select_products]'";
	$result_print_products_info = mysql_query($print_products_info , $conn) or die(mysql_error());


	while ($Array_tax = mysql_fetch_array($result_print_products_info )) {
		$prod_idField = $Array_tax['tax_id'];
		$prod_descriptionField = $Array_tax['prod_description'];
		$prod_unit_priceField = $Array_tax['prod_unit_price'];

	};
*/
	$num = $_POST[max_items];
	$items = 0;
	while ($items < $num) :

	       /* echo "<b>$items</b><br>"; */
		$qty = $_POST["i_quantity$items"];
		$product_line_item = $_POST["select_products$items"];
	       /* echo "Qty: $qty<br> "; */
	       /*  echo "Prod ID: $product_line_item<br> "; */
	
		
		#break out of the while if no QUANTITY
		if (empty($_POST["i_quantity$items"])) {
			/*echo "continue"; */
			break;
		}
			

		$print_products_info = "SELECT * FROM si_products WHERE prod_id =$product_line_item";
		$result_print_products_info = mysql_query($print_products_info , $conn) or die(mysql_error());


		while ($Array_tax = mysql_fetch_array($result_print_products_info )) {
			$prod_idField = $Array_tax['tax_id'];
			$prod_descriptionField = $Array_tax['prod_description'];
			$prod_unit_priceField = $Array_tax['prod_unit_price'];

		};

		$actual_tax = $tax_percentageField  / 100 ;
		$total_invoice_item_tax = $prod_unit_priceField * $actual_tax;
		$total_invoice_tax_amount = $total_invoice_item_tax * $_POST["i_quantity$items"];
		$total_invoice_item = $total_invoice_item_tax + $prod_unit_priceField ;	
		$total_invoice_item_total = $total_invoice_item * $_POST["i_quantity$items"];
		$total_invoice_item_gross = $prod_unit_priceField  * $_POST["i_quantity$items"];
		

		$sql_items = "INSERT into si_invoice_items values ('',$invoice_id,$qty,$product_line_item,$prod_unit_priceField,'$_POST[select_tax]',$tax_percentageField,$total_invoice_tax_amount,$total_invoice_item_gross,'00',$total_invoice_item_total)";
	
		/*
		mysql_query($sql_items);
		*/

		
		if (mysql_query($sql_items)) {
			$display_block_items =  "Processing invoice items<br> you will be redirected back to Quick View of this invoice";
		} else { die(mysql_error());
		}
		
		/* echo "$sql_items <br>";  */
		$items++ ;
	 endwhile;


	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=1;URL=print_quick_view.php?submit=$invoice_id&invoice_style=Itemised>";


}




#EDIT INVOICE ITEMISED - START

else if ( isset( $_POST['invoice_style'] ) && $_POST['invoice_style'] === 'edit_invoice_itemised' ) {

	$invoice_id = $_POST[invoice_id];

	#update the si_invoices table with customer etc  stuff - start
	$sql = "UPDATE
			si_invoices
		SET
			inv_biller_id = '$_POST[sel_id]',
			inv_customer_id = '$_POST[select_customer]',
			inv_preference = '$_POST[select_preferences]',
			inv_note = '$_POST[invoice_itemised_note]'
		WHERE
			inv_id = $invoice_id";

      if (mysql_query($sql)) {
		$display_block =  "Processing invoice, <br> you will be redirected back to the Quick View of this invoice";
	} else {
		$display_block =  "Something went wrong, please try adding the invoice again";
}


	#$display_block .= "step 2 - 1";
	#tax percentage query
	$print_tax_percentage = "SELECT * FROM si_tax WHERE tax_id ='$_POST[select_tax]'";
	$result_print_tax_percentage = mysql_query($print_tax_percentage, $conn) or die(mysql_error());


	while ($Array_tax = mysql_fetch_array($result_print_tax_percentage)) {
		$tax_idField = $Array_tax['tax_id'];
		$tax_descriptionField = $Array_tax['tax_description'];
		$tax_percentageField = $Array_tax['tax_percentage'];

	};
/*
	#product info query
	$print_products_info = "SELECT * FROM si_products WHERE prod_id ='$_POST[select_products]'";
	$result_print_products_info = mysql_query($print_products_info , $conn) or die(mysql_error());


	while ($Array_tax = mysql_fetch_array($result_print_products_info )) {
		$prod_idField = $Array_tax['tax_id'];
		$prod_descriptionField = $Array_tax['prod_description'];
		$prod_unit_priceField = $Array_tax['prod_unit_price'];

	};
*/
	#$display_block .= "step 2 - 2";
	$num = $_POST[max_items];
	$items = 1;
	$product_id_items = 1;	
	while ($items < $num) :	

		$display_block_qty =$_POST["i_quantity$items"];
		#$display_block .= "step 2 - 3  - qty $display_block_qty!! ";
	       /* echo "<b>$items</b><br>"; */
		$qty = $_POST["i_quantity$items"];
		$product_line_item = $_POST["select_products$product_id_items"];
	       /* echo "Qty: $qty<br> "; */
	       /*  echo "Prod ID: $product_line_item<br> "; */
		
		#$display_block .= "step 2 - 4 : qty $qty :: PLI=$product_line_item MAX-- $_POST[max_items];";
		#break out of the while if no QUANTITY
		
		if (empty($_POST["i_quantity$items"])) {
		       /*echo "continue"; */
		       break;
		}
		

		$print_products_info = "SELECT * FROM si_products WHERE prod_id =$product_line_item";
		$result_print_products_info = mysql_query($print_products_info , $conn) or die(mysql_error());
		
		#$display_block .= "step 2 - 5";
		
		while ($Array_tax = mysql_fetch_array($result_print_products_info )) {
			$prod_idField = $Array_tax['tax_id'];
			$prod_descriptionField = $Array_tax['prod_description'];
			$prod_unit_priceField = $Array_tax['prod_unit_price'];

		};

		$actual_tax = $tax_percentageField  / 100 ;
		$total_invoice_item_tax = $prod_unit_priceField * $actual_tax;
		$total_invoice_tax_amount = $total_invoice_item_tax * $_POST["i_quantity$items"];
		$total_invoice_item = $total_invoice_item_tax + $prod_unit_priceField ;
		$total_invoice_item_total = $total_invoice_item * $_POST["i_quantity$items"];
		$total_invoice_item_gross = $prod_unit_priceField  * $_POST["i_quantity$items"];
		

		$invoice_id_item = $_POST["inv_it_id$items"];
		

		$sql_items = "REPLACE into
					si_invoice_items
				VALUES
					(
						$invoice_id_item,
						$invoice_id,
						$qty,
						$product_line_item,
						$prod_unit_priceField,
						'$_POST[select_tax]',
						$tax_percentageField,
						$total_invoice_tax_amount,
						$total_invoice_item_gross,
						'00',
						$total_invoice_item_total
					)";


		if (mysql_query($sql_items)) {
			$display_block_items =  "Processing invoice items<br> you will be redirected back to Quick View of this invoice";
		} else { die(mysql_error());
		}

		/* echo "$sql_items <br>";  */
		$items++ ;
		$product_id_items++;
	 endwhile;



	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=1;URL=print_quick_view.php?submit=$invoice_id&invoice_style=Itemised>";

}


#EDIT Invoice Itemised - End


#Insert - INVOICE CONSULTING


else if ( isset( $_POST['invoice_style'] ) && $_POST['invoice_style'] === 'insert_invoice_consulting' ) {

	$sql = "INSERT into
		si_invoices (inv_id, inv_biller_id, inv_customer_id, inv_type,
		inv_preference, inv_date, inv_note)
		values
			('','$_POST[sel_id]','$_POST[select_customer]', 3,'$_POST[select_preferences]',now(),'$_POST[invoice_consulting_note]')";

	if (mysql_query($sql)) {
		$display_block =  "Processing invoice, <br> you will be redirected back to the Quick View of this invoice";
	} else {
		$display_block =  "Something went wrong, please try adding the invoice again";
}

	#get the invoice id from the insert
	$invoice_id = mysql_insert_id();


	#tax percentage query
	$print_tax_percentage = "SELECT * FROM si_tax WHERE tax_id ='$_POST[select_tax]'";
	$result_print_tax_percentage = mysql_query($print_tax_percentage, $conn) or die(mysql_error());


	while ($Array_tax = mysql_fetch_array($result_print_tax_percentage)) {
		$tax_idField = $Array_tax['tax_id'];
		$tax_descriptionField = $Array_tax['tax_description'];
		$tax_percentageField = $Array_tax['tax_percentage'];

	};
/*
	#product info query
	$print_products_info = "SELECT * FROM si_products WHERE prod_id ='$_POST[select_products]'";
	$result_print_products_info = mysql_query($print_products_info , $conn) or die(mysql_error());


	while ($Array_tax = mysql_fetch_array($result_print_products_info )) {
		$prod_idField = $Array_tax['tax_id'];
		$prod_descriptionField = $Array_tax['prod_description'];
		$prod_unit_priceField = $Array_tax['prod_unit_price'];

	};
*/
	$num = $_GET[num];
	$items = 0;
	while ($items < $num) :

			
	       /* echo "<b>$items</b><br>"; */
		$qty = $_POST["i_quantity$items"];
		$product_line_item = $_POST["select_products$items"];
		$line_item_description = $_POST["line_item_description$items"];
	       /* echo "Qty: $qty<br> "; */
	       /*  echo "Prod ID: $product_line_item<br> "; */
	
		#break out of the while if no QUANTITY
		if (empty($_POST["i_quantity$items"])) {
			/*echo "break"; */
			break;
		}

		$print_products_info = "SELECT * FROM si_products WHERE prod_id =$product_line_item";
       		$result_print_products_info = mysql_query($print_products_info , $conn) or die(mysql_error());


		while ($Array_tax = mysql_fetch_array($result_print_products_info )) {
			$prod_idField = $Array_tax['tax_id'];
			$prod_descriptionField = $Array_tax['prod_description'];
			$prod_unit_priceField = $Array_tax['prod_unit_price'];

		};

		$actual_tax = $tax_percentageField  / 100 ;
		$total_invoice_item_tax = $prod_unit_priceField * $actual_tax;
		$total_invoice_tax_amount = $total_invoice_item_tax * $_POST["i_quantity$items"];
		$total_invoice_item = $total_invoice_item_tax + $prod_unit_priceField ;	
		$total_invoice_item_total = $total_invoice_item * $_POST["i_quantity$items"];
		$total_invoice_item_gross = $prod_unit_priceField  * $_POST["i_quantity$items"];
		

		$sql_items = "INSERT into si_invoice_items values ('',$invoice_id,$qty,$product_line_item,$prod_unit_priceField,'$_POST[select_tax]',$tax_percentageField,$total_invoice_tax_amount,$total_invoice_item_gross,'$line_item_description',$total_invoice_item_total)";
	
		/*
		mysql_query($sql_items);
		*/

		
		if (mysql_query($sql_items)) {
			$display_block_items =  "Processing invoice items<br> you will be redirected back to Quick View of this invoice";
		} else { die(mysql_error());
		}
		
		/* echo "$sql_items <br>";  */
		$items++ ;
	endwhile;


	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=1;URL=print_quick_view.php?submit=$invoice_id&invoice_style=Consulting>";


}


#EDIT INVOICE CONSULTING - START

else if ( isset( $_POST['invoice_style'] ) && $_POST['invoice_style'] === 'edit_invoice_consulting' ) {

	$invoice_id = $_POST[invoice_id];

	#update the si_invoices table with customer etc  stuff - start
	$sql = "UPDATE
			si_invoices
		SET
			inv_biller_id = '$_POST[sel_id]',
			inv_customer_id = '$_POST[select_customer]',
			inv_preference = '$_POST[select_preferences]',
			inv_note = '$_POST[invoice_itemised_note]'
		WHERE
			inv_id = $invoice_id";

	if (mysql_query($sql)) {
		$display_block =  "Processing invoice, <br> you will be redirected back to the Quick View of this invoice";
	} else {
		$display_block =  "Something went wrong, please try adding the invoice again";
}


	#$display_block .= "step 2 - 1";
	#tax percentage query
	$print_tax_percentage = "SELECT * FROM si_tax WHERE tax_id ='$_POST[select_tax]'";
	$result_print_tax_percentage = mysql_query($print_tax_percentage, $conn) or die(mysql_error());

	while ($Array_tax = mysql_fetch_array($result_print_tax_percentage)) {
		$tax_idField = $Array_tax['tax_id'];
		$tax_descriptionField = $Array_tax['tax_description'];
		$tax_percentageField = $Array_tax['tax_percentage'];

	};
/*
	#product info query
	$print_products_info = "SELECT * FROM si_products WHERE prod_id ='$_POST[select_products]'";
	$result_print_products_info = mysql_query($print_products_info , $conn) or die(mysql_error());


	while ($Array_tax = mysql_fetch_array($result_print_products_info )) {
		$prod_idField = $Array_tax['tax_id'];
		$prod_descriptionField = $Array_tax['prod_description'];
		$prod_unit_priceField = $Array_tax['prod_unit_price'];

	};
*/
	#$display_block .= "step 2 - 2";
	$num = $_POST[max_items];
	$items = 1;
	$product_id_items = 1;
	while ($items < $num) :
	

	$consulting_item_note = $_POST["consulting_item_note$items"];
	$display_block_qty =$_POST["i_quantity$items"];
	#$display_block .= "step 2 - 3  - qty $display_block_qty!! ";
	       /* echo "<b>$items</b><br>"; */
		$qty = $_POST["i_quantity$items"];
		$product_line_item = $_POST["select_products$product_id_items"];
				
	       /* echo "Qty: $qty<br> "; */
	       /*  echo "Prod ID: $product_line_item<br> "; */

		#$display_block .= "step 2 - 4 : qty $qty :: PLI=$product_line_item MAX-- $_POST[max_items];";
		#break out of the while if no QUANTITY
		if (empty($_POST["i_quantity$items"])) {
			/*echo "break"; */
		       /* break;*/
		}

		$print_products_info = "SELECT * FROM si_products WHERE prod_id =$product_line_item";
		$result_print_products_info = mysql_query($print_products_info , $conn) or die(mysql_error());

		#$display_block .= "step 2 - 5  <br> $consulting_item_note ";

		while ($Array_tax = mysql_fetch_array($result_print_products_info )) {
			$prod_idField = $Array_tax['tax_id'];
			$prod_descriptionField = $Array_tax['prod_description'];
			$prod_unit_priceField = $Array_tax['prod_unit_price'];

		};

		$actual_tax = $tax_percentageField  / 100 ;
		$total_invoice_item_tax = $prod_unit_priceField * $actual_tax;
		$total_invoice_tax_amount = $total_invoice_item_tax * $_POST["i_quantity$items"];
		$total_invoice_item = $total_invoice_item_tax + $prod_unit_priceField ;
		$total_invoice_item_total = $total_invoice_item * $_POST["i_quantity$items"];
		$total_invoice_item_gross = $prod_unit_priceField  * $_POST["i_quantity$items"];


		$invoice_id_item = $_POST["inv_it_id$items"];

		$sql_items = "REPLACE into
					si_invoice_items
				VALUES
					(
						$invoice_id_item,
						$invoice_id,
						$qty,
						$product_line_item,
						$prod_unit_priceField,
						'$_POST[select_tax]',
						$tax_percentageField,
						$total_invoice_tax_amount,
						$total_invoice_item_gross,
						'$consulting_item_note',
						$total_invoice_item_total
					)";


		if (mysql_query($sql_items)) {
			$display_block_items =  "Processing invoice items<br> you will be redirected back to Quick View of this invoice";
		} else { die(mysql_error());
		}

		/* echo "$sql_items <br>";  */
		$items++ ;
		$product_id_items++;
	 endwhile;

	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=1;URL=print_quick_view.php?submit=$invoice_id&invoice_style=Consulting>";

}

?>

<html>
<head>
<head>
<?php

include('./include/include_main.php');

$refresh_total = isset($refresh_total) ? $refresh_total : '&nbsp';
$display_block_items = isset($display_block_items) ? $display_block_items : '&nbsp;';
echo <<<EOD
{$refresh_total}
<title>{$title}</title>
<link rel="stylesheet" type="text/css" href="themes/{$theme}/tables.css">
</head>

<body>

EOD;
$mid->printMenu('hormenu1');
$mid->printFooter();
echo <<<EOD
<br>
<br>
{$display_block}
<br><br>
{$display_block_items}

EOD;
?>
</body>
</html>