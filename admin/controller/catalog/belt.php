<?php
class ControllerCatalogBelt extends Controller {
	public function stuff(){
		$json="Shelves";
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

public function index(){
		$this->load->language('catalog/pallet');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->model('catalog/pallet');
		$this->getList();
	}

	// pallet has to be changed with Belt word later
	public function getPallet($palletID){
		$this->model('catalog/pallet');
		$palletInfo = $this->model_catalog_pallet->getPallet($palletID);
	}

	public function emptyBelt($beltID){
		$this->model('catalog/pallet');
		$this->model_catalog_pallet->emptyBelt($beltID);
	}
	public function emptyShelf($shelfID){
		$this->model('catalog/pallet');
		$this->model_catalog_pallet->emptyShelf($shelfID);
	}	
	
	public function assignBeltProduct(){
		print_r("serefsize bak ya");
		$json = array();
		if(!isset($_POST['barcode']) || !isset($_POST['productID']) || $_POST['bentCount']==''){
			$json['error']=1;
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
		$this->load->model('catalog/pallet');
		$valid = $this->model_catalog_pallet->verifyShelfProduct($_POST['barcode'],$_POST['productID']);
		error_log("$valid is valid");
		if(!$valid){
			$json['error']=1;
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));

		}
		else {
			$barcode  = $_POST['barcode'];
			$productID = $_POST['productID'];
			$beltCount = $_POST['bentCount'];
			$update    = $_POST['update'];
			error_log("Operation is like this: $barcode $productID  $beltCount $update");
			/// check if it can't be assigned, or updated???
			//$this->model_catalog_pallet->getPalletStatus($barcode,$productID,$bentCount,$update);

			$this->model_catalog_pallet->assignBeltProduct($barcode,$productID,$beltCount,$update);
		}

	}	
	
}