<?php

function isValidBarcode($barcode) {
	//checks validity of: GTIN-8, GTIN-12, GTIN-13, GTIN-14, GSIN, SSCC
	//see: http://www.gs1.org/how-calculate-check-digit-manually
	$barcode = (string) $barcode;
	//we accept only digits
	if (!preg_match("/^[0-9]+$/", $barcode)) {
		return false;
	}
	//check valid lengths:
	$l = strlen($barcode);
	if(!in_array($l, [8,12,13,14,17,18]))
		return false;
	//get check digit
	$check = substr($barcode, -1);
	$barcode = substr($barcode, 0, -1);
	$sum_even = $sum_odd = 0;
	$even = true;
	while(strlen($barcode)>0) {
		$digit = substr($barcode, -1);
		if($even)
			$sum_even += 3 * $digit;
		else 
			$sum_odd += $digit;
		$even = !$even;
		$barcode = substr($barcode, 0, -1);
	}
	$sum = $sum_even + $sum_odd;
	$sum_rounded_up = ceil($sum/10) * 10;
	return ($check == ($sum_rounded_up - $sum));
}
function token($length = 32) {
	// Create random token
	$string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	
	$max = strlen($string) - 1;
	
	$token = '';
	
	for ($i = 0; $i < $length; $i++) {
		$token .= $string[mt_rand(0, $max)];
	}	
	
	return $token;
}

/**
 * Backwards support for timing safe hash string comparisons
 * 
 * http://php.net/manual/en/function.hash-equals.php
 */

if(!function_exists('hash_equals')) {
	function hash_equals($known_string, $user_string) {
		$known_string = (string)$known_string;
		$user_string = (string)$user_string;

		if(strlen($known_string) != strlen($user_string)) {
			return false;
		} else {
			$res = $known_string ^ $user_string;
			$ret = 0;

			for($i = strlen($res) - 1; $i >= 0; $i--) $ret |= ord($res[$i]);

			return !$ret;
		}
	}
}