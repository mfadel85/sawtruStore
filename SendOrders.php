<?php
$handle = curl_init();
 
$url = "http://192.168.1.51/store/index.php?route=common/send";
 
// Set the url
curl_setopt($handle, CURLOPT_URL, $url);
// Set the result output to be a string.
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
 
$output = curl_exec($handle);
 
curl_close($handle);
 
echo $output;
