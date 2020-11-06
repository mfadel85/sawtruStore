<?php
class ControllerApiPallet extends Controller
{
	public function info(){
		print_r("yalan söyleme");
	}

	public function getPallet(){
		$json = array();		
		if (!isset($_POST['palletID']) ) {
			$json['error']=1;
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		} 

		$palletID = $_POST['palletID'];
		$this->load->model('catalog/pallet');
		$json = $this->model_catalog_pallet->getPallet($palletID);
		//print_r($product);
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function getPalletContent(){
		$json = array();
		if (!isset($_POST['palletID']) ) {
			$json['error']=1;
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
		$palletID = $_POST['palletID'];
		$productID = $_POST['productID'];
		$this->load->model('catalog/pallet');
		$json = $this->model_catalog_pallet->getPalletProduct($palletID,$productID);
		/*echo "<pre>";
		var_dump($json);
		echo "</pre>";*/
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));		 		
	}
	public function assignPalletProduct(){

		$json = array();
		if(!isset($_POST['palletID']) || !isset($_POST['productID']) || $_POST['bentCount']==''){
			$json['error']=1;
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
		$this->load->model('catalog/pallet');
		$valid = $this->model_catalog_pallet->verifyShelfProduct($_POST['palletID'],$_POST['productID']);
		error_log("$valid is valid");
		if(!$valid){
			$json['error']=1;
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));

		}
		else {
			$palletID  = $_POST['palletID'];
			$productID = $_POST['productID'];
			$bentCount = $_POST['bentCount'];
			$update    = $_POST['update'];
			error_log("$palletID $productID  $bentCount $update");
			/// check if it can't be assigned, or updated???
			$this->model_catalog_pallet->assignPalletProduct($palletID,$productID,$bentCount,$update);
		}

	}

	public function getAvailableSpace(){
		// check if this pallet can contain this product ?? here or not here?

		$json = array();
		//isset and not empty we need to understand what is happening here :D
		if (!isset($_POST['palletID']) || !isset($_POST['productID']) || $_POST['productID']=='' || $_POST['palletID']=='' ) {
			error_log("got here");

			$json['error']=1;
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}	
		$palletID  = $_POST['palletID'];
		$productID = $_POST['productID'];
		$this->load->model('catalog/pallet');	
		error_log("palletID : $palletID, productID: $productID");
		$assigned = $this->model_catalog_pallet->productAssignedToPallet($palletID,$productID);
		error_log("assigned $assigned" );

		if($assigned)
			$json = $this->model_catalog_pallet->getAvailablePositionsCount($palletID,$productID);
		else 
			$json = -1;
		error_log("json  $json");
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
    }
    /**
     * Describes getMap() function
     *
     * @return map of all elements that are in the pallets
     **/
    public function getMap()
    {
		$map = array();
		$this->load->model('catalog/pallet');
		$map = $this->model_catalog_pallet->getMap();
		print_r($map[0]);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($map));
        /// suppose to get everything in the pallet a map of the whole stock
	}



    /**
     * Describes updateStock()
     * it updates the stock of pallet using product_id, pallet_id to add 1 to stock
     * 
     * @return void
     **/
    public function updateStock()
    {
		$beltBarcode  = $_POST['palletID'];
		$productID = $_POST['productID'];
		error_log("BeltBarcode is  $beltBarcode, Product id i productID");

		// verifiy if this product assigned to this pallet
		$this->load->model('catalog/pallet');
		$assigned = $this->model_catalog_pallet->verifyProductPallet($beltBarcode,$productID);
		error_log("Assigned $assigned");
		// check if allowerd 
		$availablePositions = $this->model_catalog_pallet->getAvailablePositionsCount($beltBarcode,$productID);
		if($assigned == "Assigned to another Product" || $assigned == "Not Allowed Operation" || $availablePositions < 1)
			$json = "Not Allowed Operation";
		else {
			$json = array();
			if (!isset($_POST['palletID']) && !isset($_POST['productID']) ) {
				$json['error']=1;
				$this->response->addHeader('Content-Type: application/json');
				$this->response->setOutput(json_encode($json));
			}		
			$json = $this->model_catalog_pallet->updateStock($beltBarcode,$productID);
		}


		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));	


	}

}