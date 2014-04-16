<?php
/**
 * WooCommerce Customer/Order XML Export Suite
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce Customer/Order XML Export Suite to newer
 * versions in the future. If you wish to customize WooCommerce Customer/Order XML Export Suite for your
 * needs please refer to http://docs.woothemes.com/document/woocommerce-customer-order-xml-export-suite/
 *
 * @package     WC-Customer-Order-XML-Export-Suite/Export-Methods/FTP
 * @author      SkyVerge
 * @copyright   Copyright (c) 2012-2014, SkyVerge, Inc.
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Customer/Order XML Export Suite Method Download
 *
 * Helper class for downloading an XML file via the browser
 *
 * @since 1.1
 */
class WC_Customer_Order_XML_Export_Suite_Method_Download implements WC_Customer_Order_XML_Export_Suite_Method {


	/**
	 * Downloads the XML file via the browser
	 *
	 * @since 1.1
	 * @param string $filename
	 * @param string $xml XML to download
	 */
	public function perform_action( $filename, $xml ) {

		// allow plugins to add additional headers
		do_action( 'wc_customer_order_xml_export_suite_download_after_headers' );

		// clear the output buffer
		@ob_clean();
		
	   $path = ABSPATH.'/nusoap/lib/nusoap.php';
	   require_once($path);

	   //In SOAP user
		
		$vendername = "";  // vendername
		$password = "!";      // password
		
		$WebOrderXML = $xml;//orderxml
		//Parse to xml soap
		$sxml = new SimpleXMLElement($WebOrderXML); 
		
		//Show trace for output
		//echo "<pre>";
		//print_r ($sxml);
		//die();
		$orderidx = $sxml->OrderID;
		// Create the Soap client instance
		
		$client = new nusoap_client('http://your_link_to_the_wsdli/WEB_Service.asmx?wsdl', 'wsdl',
						$proxyhost, $proxyport, $proxyusername, $proxypassword);
		// Check for an error
		$err = $client->getError();
			if ($err) {
				echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
						}
		$person = array('venderName' => $vendername, 'password' => $password ,'webOrderXML' => $WebOrderXML);
		
		$method = isset($_GET['method']) ? $_GET['method'] : 'function';
			
			 
		$result = $client->call('InsertOrder', $person);
			
			// Check for a fault
			if ($client->fault) {						
					} else {
					// Check for errors
						$err = $client->getError();
							if ($err) {
							// Display the error
								echo '<h2>Error</h2><pre>' . $err . '</pre>';
							} else {
									echo '<h2>succes message</h2>';
								}
							}
							exit;
						}


} // end \WC_Customer_Order_XML_Export_Suite_Method_Download
