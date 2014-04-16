<?php
/**
 * Plugin Name: MWW API Send Service
 * Plugin URI: http://www.skyverge.com/contact/
 * Description: Customizes the WooCommerce Customer/Order XML Export Suite specifically for MWW API Integration
 * Author: SkyVerge
 * Author URI: http://www.skyverge.com
 * Version: 1.0
 *
 * Copyright: (c) 2013 SkyVerge, Inc. (info@skyverge.com)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @author    SkyVerge
 * @category  Custom
 * @copyright Copyright (c) 2013, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */
 
 
/**
 * Adjust the root-level XML format
 *
 */
$LineNumber = 0;
function wc_sample_xml_order_format( $orders_format, $orders ) {
 
	$orders_format = array(
		'WorkOrder' => array(
		 $orders,
		),
	);
	return $orders_format;
}
add_filter( 'wc_customer_order_xml_export_suite_order_export_format', 'wc_sample_xml_order_format', 10, 2 );
 
 
/**
 * Adjust the individual order format
 *
 */
function wc_sample_xml_order_list_format( $order_format, $order ) {
	return array(
		'OrderID'     => $order->id,
		'OrderType'   => 'Test',
		'ProjectCode'        => "",
		'LineItems' =>array(
		'LineItem'   => wc_sample_xml_get_line_items( $order )),
		'CustomerBillingInfo'       => '',
		'CustomerShippingInfo' => array(
			0 => array(
				'Name'        => $order->shipping_first_name,
				'Address1'    => $order->shipping_address_1,
				'Address2'    => $order->shipping_address_2,
				'City'        => $order->shipping_city,
				'State'       => $order->shipping_state,
				'PostalCode'         => $order->shipping_postcode,
				'Country'     => $order->shipping_country,
				'Phone'     => $order->billing_phone,
				'ShippingMethod'         => 'UPS @R03 3',
				'ShipAccountNum'         => 'V3190X',
				'ShipType'       => 'MTH',
			),
		),
	);
}
add_filter( 'wc_customer_order_xml_export_suite_order_export_order_list_format', 'wc_sample_xml_order_list_format', 10, 2 );
 
 
/**
 * Adjust the individual line item format
 *
 * @since 1.0
 * @param object $order \WC_Order instance
 * @return array
  */
 
  function wc_sample_xml_get_line_items( $order ) {
 
  	//connect
  		Use Wp
	//output the product elements from the order
	foreach( $order->get_items() as $item_id => $item_data ) {
 		 $OrderId = $order->id; 
		  //grab the data we need
	$query = "Request to grab data from bd based on the $OrderId";
		 $sql = $connexion->query($query);	 
		 $curData = array();
     	 $count = 0;

		 //data specific info for each line item

 while($Ca = $sql -> fetch()) {
			 $curData[] = $Ca;
			 $count ++;
			}
			//If the product ned some customizations before generating the xml
 	if ($count >0){
			 //Format the file name and replace spaces by "-"
			 $titlePath = sanitize_title( trim($ItemName) );
			 $title = $order_idmeta.'_order_item_generated';
			 $filePath = content_url('/fancy_products_orders/images/'.$OrderId.'/'.$order_idmeta.'/'.$title.'.png');
			 //If the item's image is not generated
			 $urlLiveSite = 'http://www.yourWebsite.com/';
			 $NofilePath = ($urlTestSite.'/'.$pathElement.'/'.$titlePath);
			 //Check if the file physically exists
			 if (file($filePath)) {
			     $png_url = array('Source'	=> $filePath);
			 } 
			 else {
			     $png_url = array('Source'	=> $NofilePath);
			 }

		$product = $order->get_product_from_item( $item_data );
		//This is the final xml customized 
		$items[] = array(
			'LineNumber'				=> $LineNumber,
			'ProductCode'				=> $ProductCode,
			'ProductUPC'				=> '0004-2478',
			'ItemDescription'			=> $ItemName,
			'Quantity'					=> $item_data['qty'],
			//we need to write a function that grabs the image URL, store the url as a variable and then pass it as the value here
			'FileList'      			=> $png_url,
		);
	  } //end if
	} //end for
}// End of wc_sample_xml_get_line_items()
	//if the product is an standard one
	elseif ($count <=0)
	{
		if($LineNumber ==0) {
			$LineNumber=1;
			} else {
			$LineNumber = $LineNumber+1;
			}
			
			$product = $order->get_product_from_item( $item_data );
 
		$items[] = array(
			'LineNumber'				=> $LineNumber,
			'ProductCode'				=> '------',
			'ProductUPC'				=> '0004-2478',
			'ItemDescription'			=> $product->get_title(),
			'Quantity'					=> $item_data['qty'],
			//we need to write a function that grabs the image URL, store the url as a variable and then pass it as the value here
			'FileList'        => array('Source'	=> set_url_scheme( get_permalink( $product->id ), 'http' )),
		);
	}	
	return $items;
 } 

} //end wc_sample_xml_get_line_items
