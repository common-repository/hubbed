<?php
if ( ! defined( 'ABSPATH' ) ) exit;
  add_action('wp_ajax_nopriv_searched_list', 'hubbed_searched_list_location');
  add_action('wp_ajax_searched_list', 'hubbed_searched_list_location');
function hubbed_searched_list_location()
{
if (! isset( $_POST['searched_nonce'] ) || ! wp_verify_nonce( $_POST['searched_nonce'], 'search_nonce_postcode' )) 
{
 
  return 'Sorry, your nonce did not verify.';
   exit;
 
} else {
 	$page_no =1;
  $searched_postcode = sanitize_text_field($_POST['searched_postcode']);
   $searched_services = sanitize_text_field($_POST['searched_services']);
   $searched_channel = sanitize_text_field($_POST['searched_channel']);
   $searched_radius = sanitize_text_field($_POST['searched_radius']);
   $page_no = sanitize_text_field($_POST['page_no']);
   
   $channel = $product = $service == "";
   if (!empty($searched_postcode)) 
   {	   
	   
   	$data = array(
   				'store_id'=> (get_option('hubbed_store_id')),
   				'page'=> $page_no,
   				'perPage'=> '',
   				'searchKeyword'=> $searched_postcode,
   				'radius'=> $searched_radius,
   				
   				);
				
   	if (isset( $_POST['searched_channel']) && !empty($_POST['searched_channel']))
   	{
   		$data['channel'] = sanitize_text_field($_POST['searched_channel']);
   	}
   	if (isset( $_POST['searched_services']) && !empty($_POST['$searched_services']))
   	{
   		$data['service'] = sanitize_text_field($_POST['searched_services']);
   	} 
   				
   	$body = hubbed_api_call('/storeResult', $data);

   echo json_encode($body);
 
   }
   
}
die();
}


add_action('wp_ajax_nopriv_hubbed_selected_address', 'hubbed_selected_address_location');
add_action('wp_ajax_hubbed_selected_address', 'hubbed_selected_address_location');
function hubbed_selected_address_location()
{
//Enable the Shiping Address
session_start();
    $_SESSION['hubbed_hubbedlb'] = sanitize_text_field($_POST['hubbedlb']);
    $_SESSION['hubbed_selectaddress'] = sanitize_text_field(isset($_POST['company']) ? $_POST['company'] : '').', '.sanitize_text_field(isset($_POST['address']) ? $_POST['address'] : '').', '.sanitize_text_field(isset($_POST['city']) ? $_POST['city'] : '').','.sanitize_text_field(isset($_POST['state']) ? $_POST['state'] : '').', '.sanitize_text_field(isset($_POST['country']) ? $_POST['country'] : 'AU').', '.sanitize_text_field(isset($_POST['zip']) ? $_POST['zip'] : '');

    $_SESSION['hubbed_company'] = sanitize_text_field(isset($_POST['company']) ? $_POST['company'] : '');
    $_SESSION['hubbed_address2'] = sanitize_text_field(isset($_POST['address2']) ? $_POST['address2'] : '');
    $_SESSION['hubbed_address'] = sanitize_text_field(isset($_POST['address']) ? $_POST['address'] : '');
    $_SESSION['hubbed_city'] = sanitize_text_field(isset($_POST['city']) ? $_POST['city'] : '');
    $_SESSION['hubbed_state'] = sanitize_text_field(isset($_POST['state']) ? $_POST['state'] : '');
    $_SESSION['hubbed_country'] = sanitize_text_field(isset($_POST['country']) ? $_POST['country'] : 'AU');
    $_SESSION['hubbed_zip'] = sanitize_text_field(isset($_POST['zip']) ? $_POST['zip'] : '');
    
    //$_SESSION['hubbed_selectadminaddress'] = sanitize_text_field(isset($_POST['address']) ? $_POST['address'] : '').'|'.sanitize_text_field(isset($_POST['city']) ? $_POST['city'] : '').'|'.sanitize_text_field(isset($_POST['state']) ? $_POST['state'] : '').'|'.sanitize_text_field(isset($_POST['country']) ? $_POST['country'] : 'AU').', '.sanitize_text_field(isset($_POST['zip']) ? $_POST['zip'] : '');

    global $woocommerce;
     $woocommerce->customer->set_shipping_company(sanitize_text_field(isset($_POST['company']) ? $_POST['company'] : ''));
    $woocommerce->customer->set_shipping_address_1( sanitize_text_field(isset($_POST['address']) ? $_POST['address'] : ''));
    $woocommerce->customer->set_shipping_address_2( sanitize_text_field(isset($_POST['address2']) ? $_POST['address2'] : ''));
    $woocommerce->customer->set_shipping_country( sanitize_text_field(isset($_POST['country']) ? $_POST['country'] : 'AU'));
    $woocommerce->customer->set_shipping_state( sanitize_text_field(isset($_POST['state']) ? $_POST['state'] : ''));
    $woocommerce->customer->set_shipping_city( sanitize_text_field(isset($_POST['city']) ? $_POST['city'] : '')); 
    $woocommerce->customer->set_shipping_postcode( sanitize_text_field(isset($_POST['zip']) ? $_POST['zip'] : ''));

    $return = array();
    $return['checkout_url'] = wc_get_checkout_url();
    echo $data = json_encode($return);
    wp_die();
}	


add_action('wp_ajax_nopriv_removed_hubbed_addresss', 'removed_hubbed_address_location');
add_action('wp_ajax_removed_hubbed_address', 'removed_hubbed_address_location');
function removed_hubbed_address_location()
{
		session_start();
 
// Removing session data
		if(isset($_SESSION["hubbed_selectaddress"])){
		    unset($_SESSION["hubbed_selectaddress"]);
		}

		if(isset($_SESSION['hubbed_hubbedlb'])){
		    unset($_SESSION['hubbed_hubbedlb']);
		}
		
		//add_filter( 'woocommerce_ship_to_different_address_checked', '__return_false');

	    global $woocommerce;
/*
        $woocommerce->customer->set_shipping_address_1('');
        $woocommerce->customer->set_shipping_address_2('');
        $woocommerce->customer->set_shipping_country('');
        $woocommerce->customer->set_shipping_state('');
        $woocommerce->customer->set_shipping_city('');
        $woocommerce->customer->set_shipping_postcode('') ;
 */       
    die();

}