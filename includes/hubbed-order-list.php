  <?php 
  if ( ! defined( 'ABSPATH' ) ) exit;
  ?>
  <table id="hubbed_order_list_table">
		<thead>
			<tr>
				<th>Order ID.</th>
				<th>User Name</th> 
				<th>Receiver Address</th>
				<th>Order Date</th>
				<th>Total Amount</th>
				<th>Order Status</th>
				<th>Click&Collect</th>
			</tr>
		</thead>
		<tbody>
<?php

    
$args = array(
    'numberposts' => -1,
    'post_type' => 'shop_order',
    'post_status'    => 'any',
    'meta_key' =>  'hubbed_enable_address',
    'meta_value' => 1,

);

$results = get_posts($args);
	foreach ($results as $result)
	{

		$order = wc_get_order( $result->ID );

		echo '<tr>';
		echo '<td> <a href="'.get_edit_post_link($result->ID).'">'.$result->ID.'</a></td>';
		echo '<td>'.get_user_meta($result->post_author, 'first_name', true).' '.get_user_meta($result->post_author, 'last_name', true).'</td>';
		$address = get_post_meta($result->ID,'hubbed_checkout_address',true);
		$addresses = explode('|', $address);

		echo '<td>';
		foreach ($addresses as $hubbed_addres) {
          
          echo $hubbed_addres.'<br/>';
        }
        echo '</td>';
        $date = $order->get_date_created();
		echo '<td>'.date("m-d-Y h:i ", strtotime($date)).'</td>';
		echo '<td>'.$order->get_currency().' '.$order->get_total().'</td>';
		echo '<td>'.$order->get_status().'</td>';
		echo '<td>';
		$hubbed_consignment_no = get_post_meta( $result->ID, 'hubbed_consignment_no', true );
		$hubbed_consignment_status = get_post_meta( $result->ID, 'hubbed_consignment_status',true);
		if (empty($hubbed_consignment_status)) {
			echo '<span style="color:orange;">Pending</span>';
		}else{ 
				if ($hubbed_consignment_status == 'error') {
					echo '<span style="color:red;">Error</span>';
				}else{
			echo '<span style="color:green;">Success</span>';
					}
		}
		echo '</td>';
		echo '</tr>';

	}

?>


		</tbody>
	</table>