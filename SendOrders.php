<?php

$apiKey = "LA6g3ogGx7lgceCO2uiFZJ4QCwfe93SY54OYi2Pvjnrnxr55sFygOMT1sATi0b7y439oTRZPlM2s9ZY9Qt6tLOYqyDcoVXmhNAChHV2wL3ptKSlaWxMtO5XHhsokshxVyCGiKgMMU775z4IVy549FxY4rTRYb8UVlGNHJBcDIQgkRXdWziUpkzJP6ybm1gUPIIVn5ehCXxQTiRXvqXc6dd0zz4MddwWnQdRMMbdS5wF2IszhxPunqKAYx2If6YZA"; //Whatever you put in System -> Users -> API

$url = "http://192.168.1.51/store/index.php?route=api/login&api_token=0";

$curl = curl_init($url);

$post = array(
    'username' => 'Default',
    'key' => $apiKey,
);

curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => $post,
));

$raw_response = curl_exec($curl);
//var_dump($raw_response);
$response = json_decode($raw_response);
curl_close($curl);
var_dump($response);

$api_token = $response->api_token;
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "http://localhost/store/index.php?route=api/order/getUnsentOrders",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
    ),
));

$response = curl_exec($curl);

$err = curl_error($curl);

curl_close($curl);
if ($err) {
    echo "cURL Error #:" . $err;
} else {

    $orders = json_decode($response);
    //var_dump($orders);
    if (count($orders) > 0) {
        foreach ($orders as $order) {
            $orderID = $order->order_id;
            echo "<BR><BR>The order ID is $orderID:<BR>";
            print_r($order);
            echo "End<BR><BR>";



            $url = "http://192.168.1.51/store/index.php?route=api/order/sendOrders&api_token=" . $api_token . "&order_id=$orderID";
            // actually we need a different funtion that gets the order read and we send it using sockets
            $post = array(
            );

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

            print_r("<br> starts<br>");
            $raw_response = curl_exec($curl);
            print_r("<br> another starts<br>");
            $orderDetails = json_decode($raw_response);
            var_dump($orderDetails);
            print_r("<br> starts<br>");
            $productCount = count($orderDetails->products);
            print_r("<BR> Product Count is : $productCount<BR>");

            /*foreach($orderDetails->products as $product){
                print_r("<BR> Product ID is : $product->product_id Quantity is $product->quantity<BR>");
                $positionQueryString = "
				SELECT 
					shelf_physical_row,
					optp.product_id,
					optp.shelf_id,
					optp.unit_id as unitID,
					ocu.direction as direction,
					optp.start_pallet,
					op.x_position as xPos ,
					os.shelf_physical_row as yPos FROM `oc_product_to_position` optp 
					join oc_pallet op on optp.start_pallet = op.pallet_id 
					join oc_shelf os on os.shelf_id = op.shelf_id 
					join oc_unit ocu on ocu.unit_id = os.unit_id
					WHERE product_id = " . (int)$product->product_id . " and optp.status='Ready' limit 0,".$product->quantity ;
					//error_log($positionQueryString);
					//die();
				$position_query = $this->db->query($positionQueryString);

				//print_r($position_query);
				if($cart['quantity'] == 1){
					$xPos = $position_query->row['xPos'];
					$yPos = $position_query->row['shelf_physical_row'];
					$direction =  $position_query->row['direction'];
				}

				else if($cart['quantity']> 1){

					$xPos = array();
					$yPos = array();
					foreach($position_query->rows as $product){
						$xPos[] = $product['xPos'];/// Null ??
						$yPos[] = $product['yPos'];/// Null ??
						$direction[] =  $product['direction'];/// Null ??

					}
				}

				//print_r("<br>our Query<br>");
				//print_r($cart['product_id']);
			
				//print_r("<br>end of our Query<br>");
				//// if product out of stock handle
				/// MFH 
				$product_data[] = array(
					'cart_id'         => $cart['cart_id'],
					'bent_count'      => $product_query->row['bent_count'],
					'xPos'            => $xPos,//// maybe we have multiple xPos
					'yPos'            => $yPos,/// maybe we have multiple yPos
					'unit_id'         => $position_query->row['unitID'],
					'direction'       => $direction,
					'product_id'      => $product_query->row['product_id'],
					'name'            => $product_query->row['name'],
					'model'           => $product_query->row['model'],
					'shipping'        => $product_query->row['shipping'],
					'image'           => $product_query->row['image'],
					'option'          => $option_data,
					'download'        => $download_data,
					'quantity'        => $cart['quantity'],
					'minimum'         => $product_query->row['minimum'],
					'subtract'        => $product_query->row['subtract'],
					'stock'           => $stock,
					'price'           => ($price + $option_price),
					'total'           => ($price + $option_price) * $cart['quantity'],
					'reward'          => $reward * $cart['quantity'],
					'points'          => ($product_query->row['points'] ? ($product_query->row['points'] + $option_points) * $cart['quantity'] : 0),
					'tax_class_id'    => $product_query->row['tax_class_id'],
					'weight'          => ($product_query->row['weight'] + $option_weight) * $cart['quantity'],
					'weight_class_id' => $product_query->row['weight_class_id'],
					'length'          => $product_query->row['length'],
					'width'           => $product_query->row['width'],
					'height'          => $product_query->row['height'],
					'length_class_id' => $product_query->row['length_class_id'],
					'recurring'       => $recurring
				);
            }*/

            print_r("<br> ends<br>");
        }
    }
}
