<?php
if ( ! defined( 'ABSPATH' ) ) exit;

    add_action('admin_notices', 'hubbed_woo_notice');

    function hubbed_woo_notice(){

        if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
            // Put your plugin code here
            define('Hubbed_Woo_Error', 0);
        }
        else{
        echo '<div class="error"> This Plugin <strong> (Hubbed) </strong> will not work. Please install the <a href="https://wordpress.org/plugins/woocommerce/"> woocommerce </a> plugin </div>';
        define('Hubbed_Woo_Error', 1);
        }

          // Check the mapping of API_Key, Map_key And Store id  hubbed_map_key
$storescheck =array(
                'store_key'=>(get_option('hubbed_api_key')),
                'store_id'=>(get_option('hubbed_store_id')),
                'store_url'=>(get_site_url()),

                );
$checkstore = hubbed_api_call('/checkStore', $storescheck);

if ($checkstore['responseCode'] == 506) 
{
echo '<div class="error" style="font-size:14px;"> We have run in to some trouble activating the <strong>Hubbed Click & Collect</strong> Plugin. Can you deactivate and then try to reactivate? If you are running in to the same issue, please contact customerservice@hubbed.com.au.</div>';
}



    } 


// create custom plugin settings menu
add_action('admin_menu', 'hubbed_create_menu');
function hubbed_create_menu() {
    add_menu_page('HUBBED Click & Collect', 'HUBBED Click & Collect', 'administrator','hubbeddashboard', 'hubbed_settings_page' , '');
    add_submenu_page('hubbeddashboard', 'Settings', 'Setings', 'administrator', 'hubbeddashboard' );
    
    if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
            // Put your plugin code here
           add_submenu_page( 'hubbeddashboard', 'Orders', 'Orders','manage_options', 'hubbed_order_page','hubbed_order_page' , '');
        }
    
}


function hubbed_settings_page() {

if (isset( $_POST['hubbed-submit'] )) 
{
   
if ( ! isset( $_POST['hubbed_setting_saved'] ) || ! wp_verify_nonce( $_POST['hubbed_setting_saved'], 'hubbed_setting_saved' )) 
{
   echo 'Sorry, your nonce did not verify.';
   exit;
} else {
    
 
       $errormsg = $sucmsg = '';
   
        $data['store_id'] = (get_option('hubbed_store_id'));
        $data['store_key'] = sanitize_text_field($_POST['hubbed_api_key']);
        $hubbed_setting_enable = sanitize_text_field($_POST['hubbed_setting_enable']);
        update_option( 'hubbed_setting_enable', $hubbed_setting_enable );
        $body = hubbed_api_call('/storeKeyCheck', $data);
        if ($body['responseCode'] != 200) {
            $errormsg = 'Please enter a valid HUBBED API key.';
            update_option( 'hubbed_api_key', '' );
        
            }else{

                $hubbed_api_key = sanitize_text_field($_POST['hubbed_api_key']);
                $hubbed_key_location = sanitize_text_field($_POST['hubbed_key_location']);
                $hubbed_shipping_fee = sanitize_text_field($_POST['hubbed_shipping_fee']);
                $hubbed_cutoff_price = sanitize_text_field($_POST['hubbed_cutoff_price']);
                $hubbed_lower_price = sanitize_text_field($_POST['hubbed_lower_price']);
                $hubbed_higher_price = sanitize_text_field($_POST['hubbed_higher_price']);
                $hubbed_button_placement = sanitize_text_field($_POST['hubbed_button_placement']);
                $hubbed_button_placement_mini = sanitize_text_field($_POST['hubbed_button_placement_mini']);
                $hubbed_visible_for_checkout = sanitize_text_field($_POST['hubbed_visible_for_checkout']);
                $button_option = sanitize_text_field($_POST['button_option']);
                
                update_option( 'hubbed_button_placement', $hubbed_button_placement );
                update_option('hubbed_button_placement_mini',$hubbed_button_placement_mini);
                update_option('hubbed_visible_for_checkout',$hubbed_visible_for_checkout);
                update_option('button_option',$button_option);
                update_option( 'hubbed_api_key', $hubbed_api_key );
                update_option( 'hubbed_key_location', $hubbed_key_location );  
                update_option( 'hubbed_shipping_fee', $hubbed_shipping_fee );  
                update_option( 'hubbed_cutoff_price', $hubbed_cutoff_price );  
                update_option( 'hubbed_lower_price', $hubbed_lower_price );   
                update_option( 'hubbed_higher_price', $hubbed_higher_price );
                $sucmsg = "Your store's app data is updated successfully."  ;     

            }
   
}

}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>App</title>
</head>

<body>

<div class="hubbed-header-tab">

    <div class="hubbed-nav">
        <ul id="hubbed-tabs-nav">
            <li class="hubbed-tabs-nav-li active"><a class="hubbed-tabs-nav-li-a" redirectid="tab1" href="#tab1">Settings</a></li>
            <?php if(!empty(get_option('hubbed_api_key'))){ ?>

             <li class="hubbed-tabs-nav-li"><a class="hubbed-tabs-nav-li-a" id="hubbed-tabs-subscription" redirectid="tab3" href="#tab3">Plan Details</a></li>
         <?php } ?>
            <li class="hubbed-tabs-nav-li"><a class="hubbed-tabs-nav-li-a" redirectid="tab2" href="#tab2">Installation Guide </a></li>
        </ul>
    </div>
</div>

<div id="hubbed-tabs-content">


    <div id="tab1" class="hubbed-tab-content">
        <div class="white-background key-tab">
            <!--
            <div class="hubbed-logo-key-tab">
                <img src="<?php echo Hubbed_URL;?>assets/logo.png">
            </div>
            -->
            <form method="post" id="adminHubbed" action="">
                <?php wp_nonce_field( 'hubbed_setting_saved','hubbed_setting_saved' ); ?>
            <label id="hubbed-error-label" class="hubbed-error-label"> <?php echo @$errormsg;?> </label>
            <label id="hubbed-suc-label" class="hubbed-suc-label" style="color:green;"> <?php echo @$sucmsg;?> </label>


                <div class="setting-section-row">
                    <p class="hubbed-section-title">Activation  </p>
                    
                    <div class="field-main row">
                        <div class="col-md-4"> 
                           <label class="hubbed-key-label">HUBBED API Key</label>
                        </div>
                        <div class="col-md-8"> 
                            <div class="hubbed-field-width">
                            <input class="hubbed-field" type="text"  id="hubbed_api_key" name="hubbed_api_key" placeholder="Add Key" value="<?php echo esc_attr(get_option('hubbed_api_key')); ?>" required />
                            <span class="settings-notes">If you don't have a HUBBED API key, go to <a href="https://hubbed.com/" target="_blank">https://hubbed.com/</a> to subscribe and get your own API key.</span>
                            </div>
                        </div>

                        
                        <div class="col-md-4"> 
                           <label class="hubbed-key-label">Click & Collect Active</label>
                        </div>

                        <div class="col-md-8"> 
                            <div class="hubbed-field-width">
                            <select class="hubbed-id-enable hubbed-field" name="hubbed_setting_enable">
                            <option value="1" <?php if(get_option('hubbed_setting_enable') == 1){ echo "selected";} ?> > Yes </option>
                            <option value="0" <?php if(get_option('hubbed_setting_enable') == 0){ echo "selected";} ?> > No </option>   
                            </select>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="setting-section-row section-mt">
                    <p class="hubbed-section-title">Button Placement/Option </p>
                    
                    <div class="field-main row">
                        <div class="col-md-4"> 
                           <label class="hubbed-key-label">Button Placement for Cart Page</label>
                        </div>
                        <div class="col-md-8"> 
                            <div class="hubbed-field-width">
                            <select class="hubbed-placment-enable hubbed-field" name="hubbed_button_placement">
                            <option value="woocommerce_after_cart_totals" <?php if(get_option('hubbed_button_placement') == 'woocommerce_after_cart_totals'){ echo "selected";} ?> > Below Checkout Button</option>
                            <option value="woocommerce_proceed_to_checkout" <?php if(get_option('hubbed_button_placement') == 'woocommerce_proceed_to_checkout'){ echo "selected";} ?> > Above Checkout Button </option> 
                            <option value="woocommerce_before_cart_totals" <?php if(get_option('hubbed_button_placement') == 'woocommerce_before_cart_totals'){ echo "selected";} ?> > Below Cart Items Section </option>
                            <option value="woocommerce_before_cart_table" <?php if(get_option('hubbed_button_placement') == 'woocommerce_before_cart_table'){ echo "selected";} ?> > Above Cart Item Section  </option>
                          
                            
                            </select>

                            </div>
                        </div>

                    </div>

                    <div class="field-main row">
                        <div class="col-md-4"> 
                           <label class="hubbed-key-label">Button Placement for Mini-Cart</label>
                        </div>
                        <div class="col-md-8"> 
                            <div class="hubbed-field-width">
                            <select class="hubbed-placment-enable hubbed-field" name="hubbed_button_placement_mini">
                            <option value="none" <?php if(get_option('hubbed_button_placement_mini') == 'none'){ echo "selected";} ?> >Not Required</option>
                            <option value="woocommerce_after_mini_cart" <?php if(get_option('hubbed_button_placement_mini') == 'woocommerce_after_mini_cart'){ echo "selected";} ?> > Below Cart Items Section </option> 
                            <option value="woocommerce_before_mini_cart" <?php if(get_option('hubbed_button_placement_mini') == 'woocommerce_before_mini_cart'){ echo "selected";} ?> > Above Cart Items Section </option>
                          
                            
                            </select>

                            </div>
                        </div>

                    </div>

                    <div class="field-main row">
                        <div class="col-md-4"> 
                           <label class="hubbed-key-label">Visible in Checkout?</label>
                        </div>
                        <div class="col-md-8"> 
                            <div class="hubbed-field-width">
                                <select class="hubbed-placment-enable hubbed-field" name="hubbed_visible_for_checkout">
                                    <option value="no" <?php if(get_option('hubbed_visible_for_checkout') == 'no'){ echo "selected";} ?> >No</option>
                                    <option value="yes" <?php if(get_option('hubbed_visible_for_checkout') == 'yes'){ echo "selected";} ?> >Yes</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="field-main row">
                        <div class="col-md-4"> 
                           <label class="hubbed-key-label">Button option?</label>
                        </div>
                        <div class="col-md-8"> 
                            <div class="hubbed-field-width">
                                <select class="hubbed-placment-enable hubbed-field" name="button_option">
                                    <option value="1" <?php if(get_option('button_option') == '1'){ echo "selected";} ?> >Simple Click & Collect</option>
                                    <option value="2" <?php if(get_option('button_option') == '2'){ echo "selected";} ?> >Click & Collect with the Sustainability icon</option>
                                    <option value="3" <?php if(get_option('button_option') == '3' || get_option('button_option') == ""){ echo "selected";} ?> >Click & Collect with the Hubbed icon</option>
                                    <option value="4" <?php if(get_option('button_option') == '4'){ echo "selected";} ?> >Click & Collect with the Hubbed icon and the Sustainability icon</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>



                <div class="setting-section-row section-mt" >
                    <p class="hubbed-section-title">HUBBED ID</p>
                    <div class="field-main row">
                    <div class="col-md-4">  
                       <label class="hubbed-key-label">HUBBED ID <abbr title="HUBBED ID is a unique identifier that will be appended to Receiver details, so that HUBBED location can accept the parcel by referring the identifier on the shipping label."></abbr></label>
                    </div>

                    <div class="col-md-8"> 
                        <div class="hubbed-field-width">
                            <select class="hubbed-id-location hubbed-field" name="hubbed_key_location">
                            <option value="first" <?php if(get_option('hubbed_key_location') == 'first'){ echo "selected";} ?> > Append to Firstname </option>
                                <option value="last" <?php if(get_option('hubbed_key_location') == 'last'){ echo "selected";} ?> > Append to Lastname </option>
                                <option value="city" <?php if(get_option('hubbed_key_location') == 'city'){ echo "selected";} ?> > Append to City </option>
                                <option value="address" <?php if(get_option('hubbed_key_location') == 'address'){ echo "selected";} ?> > Append to Address </option>
                                
                            </select>
                            <span class="settings-notes">HUBBED ID is a unique identifier that will be appended to Receiver details, so that HUBBED location can accept the parcel by referring the identifier on the shipping label.</span>
                        </div>
                    </div>

                    </div>
                </div>

                <div class="setting-section-row section-mt" >
                    <p class="hubbed-section-title">How to pass on the HUBBED Click & Collect cost</p>

                    <div class="field-main row">
                    <div class="col-md-4">  
                       <label class="hubbed-key-label">Passing on the Click & Collect cost to customer</label>
                    </div>
                    <div class="col-md-8"> 
                        <div class="hubbed-field-width">
                        <select class="hubbed-id-shipping-cost hubbed-field" name="hubbed_shipping_fee">
                            <option value="0" <?php if(get_option('hubbed_shipping_fee') == 0){ echo "selected";} ?> >No</option>
                            <option value="1" <?php if(get_option('hubbed_shipping_fee') == 1){ echo "selected";} ?>>Yes</option>
                        </select>
                        </div>
                    </div>

                    <div class="hubbed_feeyes" style="display: contents;">

                    <div class="col-md-4"> 
                       <label class="hubbed-key-label">Threshold checkout value</label>
                    </div>
                    <div class="col-md-8"> 
                        <div class="hubbed-field-width">
                           <input class="hubbed-field" type="number"  id="hubbed_cutoff_price" name="hubbed_cutoff_price" value="<?php echo esc_attr(get_option('hubbed_cutoff_price')); ?>" />
                        </div>
                    </div>  
                    <div class="col-md-4"> 
                       <label class="hubbed-key-label"> Lower Fee</label>
                    </div>
                    <div class="col-md-8"> 
                        <div class="hubbed-field-width">
                           <input class="hubbed-field" type="number"  id="hubbed_lower_price" name="hubbed_lower_price" value="<?php echo esc_attr(get_option('hubbed_lower_price')); ?>" />
                        </div>
                    </div>

                    <div class="col-md-4"> 
                       <label class="hubbed-key-label"> Highest Fee </label>
                    </div>
                    <div class="col-md-8"> 
                        <div class="hubbed-field-width">
                           <input class="hubbed-field" type="number"  id="hubbed_higher_price" name="hubbed_higher_price" value="<?php echo esc_attr(get_option('hubbed_higher_price')); ?>" />
                        </div>
                    </div>
                </div>

                </div>
                </div>


            <p class="submit"><input type="submit" name="hubbed-submit" id="button" class="button hubbed-submit" value="Save"></p>
                                    

                
                <p id="hubbedAdminErrMsg"></p>
            </form>

        </div>
    </div>


    <div id="tab3" class="hubbed-tab-content hubbed-plan-content "></div>
 

    <div id="tab2" class="hubbed-tab-content">
       <div class="white-background how-to-install">
            <h2 class="hubbed-main-heading">Installation Steps</h2>
            <!-- 1 -->
            <h3 class="usage-title"><b>Important</b><br>You must register for a HUBBED Account at <a href="https://hubbed.com/register/?plan=HUB_STA_MON&ccredirection=price" target="_blank">here</a> and receive a HUBBED Account API Key to use the HUBBED Click &amp; Collect Neto addon</h3>
            <ul>
                <li>
                    <h3>1. Locate your <b>HUBBED Account API Key</b> and keep a record of it.</h3>
                </li>
                <li>
                    <h3>2. Now Login to your WooCommerce account</h3>
                </li>
                <li>
                    <h3>3. Navigate to the WooCommerce plugins menu and click on add new plugin</h3>
                </li>
                <li>
                    <h3>4. Search for the HUBBED plugin and click on Install now</h3>
                </li>
                <li>
                    <h3>5. This will initiate automatic installation of the HUBBED Click & Collect plugin to your WooCommerce store.</h3>
                </li>
                <li>
                    <h3>6. Congratulations. The plugin should now be installed.<br>You should now see HUBBED Click & Collect appear as a new section in your WooCommerce admin menu</h3>
                </li>
                <li>
                    <h3>7. To activate HUBBED Click & Collect in your shopping cart.<br>
                        - Click on the HUBBED Click & Collect menu<br>
                        - Locate the Activation section then enter your unique HUBBED Account API Key in the HUBBED API Key field and click Save.</h3>
                    <img src="<?php echo Hubbed_URL;?>assets/screenshot-1.png" class="hubbed-images">
                </li>
                <li>
                    <h3>8. If HUBBED Click and Collect is activated, you will see the Click and Collect button in your Shopping Cart and Check out pages.</h3>
                    <h3>Cart Page:</h3>
                    <img src="<?php echo Hubbed_URL;?>assets/screenshot-2.png" class="hubbed-images">
                    <h3>Checkout Page:</h3>
                    <img src="<?php echo Hubbed_URL;?>assets/screenshot-3.png" class="hubbed-images">
                </li>
            </ul>
            <h3 class="hubbed-secound-heading">Passing the parcel handling fee to your customer based on cart price threshold</h3>
            <ul>
                <li>
                    <h3>1. Go to Wordpress admin Dashboard</h3>
                </li>
                <li>
                    <h3>2. Click on “Hubbed click and collect ” from left side of menu</h3>
                </li>
                <li>
                    <h3>3. Toggle Passing on the Click & Collect cost to customer from no to yes</h3>
                    <img src="<?php echo Hubbed_URL;?>assets/screenshot-4.png" class="hubbed-images">
                </li>
                <li>
                    <h3>4. Set your Threshold checkout value: This is the total checkout price by which you can set the limit. Example $50</h3>
                </li>
                <li>
                    <h3>5. Add your Lower Fee - This fee is applicable if customers checkout below the Threshold checkout value. E.g. $2</h3>
                </li>
                <li>
                    <h3>6. Add your Higher Fee value - This fee is applicable if customers checkout above the Threshold checkout value. E.g. 0 to make Click & Collect Free</h3>
                </li>
            </ul>
            <p>For example, for purchases over $50, you are making Click & Collect free and you’ll charge $2 if customers checkout below $50</p>
           
        </div>
    </div>
</div>
</body>
</html>
<?php } 


// order list page

function hubbed_order_page()
{

    require_once 'hubbed-order-list.php';
}


add_action('wp_ajax_nopriv_hubbed_plan_detail', 'hubbed_admin_plan_detail');
  add_action('wp_ajax_hubbed_plan_detail', 'hubbed_admin_plan_detail');
function hubbed_admin_plan_detail()
{
       
$url = HUBBED_WEB_URL .'getsubid';
 $api_key = get_option('hubbed_api_key');

if (!empty($api_key)) {

$fields = array(
            'api_key'=> $api_key,    
                );
  $args = array(
    'body' => $fields,
    'timeout' => '5',
    'redirection' => '5',
    'httpversion' => '1.0',
    'blocking' => true,
    'headers' => array(),
    'cookies' => array()
  );

  $response = wp_remote_post($url, $args);
 echo $body = wp_remote_retrieve_body($response);
  //$body_decode = json_decode($body, true);
 if (empty($body)) {
     $body['status_code'] == 201;
 }

  //  echo json_encode($body_decode);
}else{
$body['message'] = "API Key is not abale yo find..";
}
wp_die();
}
?>