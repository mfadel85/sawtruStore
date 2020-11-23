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



            $url = "http://192.168.1.51/store/index.php?route=api/order/getOrderDetails&api_token=" . $api_token . "&order_id=$orderID";
            // actually we need a different funtion that gets the order read and we send it using sockets
            $post = array(
            );

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

            print_r("<br> starts<br>");
            $raw_response = curl_exec($curl);
            print_r("<br> another starts<br>");
            $orderDetails = json_decode($raw_response);
            var_dump($orderDetails->order);
            print_r("<br> starts<br>");
            $productCount = count($orderDetails->products);
            print_r("<BR> Product Count is : $productCount<BR>");

            foreach($orderDetails->products as $product){
                print_r("<BR> Product ID is : $product->product_id Quantity is $product->quantity<BR>");
            }

            print_r("<br> ends<br>");
        }
    }
}
