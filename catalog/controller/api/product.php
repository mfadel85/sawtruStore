<?php
class ControllerApiProduct extends Controller
{
	public function info(){
		print_r("yalan söyleme");
	}
	public function get(){
		//print_r('Yalan söyleme gözlerime bak');
		//$this->load->language('api/order');
		$json = array();		
		if (!isset($_POST['sku']) || $_POST['sku'] == 0) {
			$json['error']=1;
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		} 
		// now we get the SKU, we have to search in the database for it and get the product details and see 

		$sku = $_POST['sku'];
		$this->load->model('catalog/product');
		$json = $this->model_catalog_product->getProductBySKU($sku);
		//print_r($product);
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}