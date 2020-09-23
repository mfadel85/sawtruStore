<?php
	define('Pallet_Detph', 57);

class ModelCatalogPallet extends Model {



	public function getPallet($palletID){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "pallet  WHERE barcode = $palletID");
		return $query->row;		
	}

	public function getPalletProduct($palletID,$productID){
		$query = $this->db->query("SELECT count(start_pallet) as Count,product_id FROM " . DB_PREFIX . "product_to_position 
		WHERE product_id=$productID AND start_pallet= $palletID group by start_pallet");
		return $query->row;
	}

    public function getMap(){
		$map = array();
		$query = $this->db->query("SELECT * from oc_pallet order by unit_id,shelf_id,x_position");
		foreach ($query->rows as $result) {
			$map[] = $result;
		}
		return $map;

	}
	public function productAssignedToPallet($palletID,$productID){
		$count= $this->db->query("SELECT count(*) FROM `oc_pallet_product` where product_id = $productID and start_pallet_id = $palletID");
		if(count($count->row)> 0)
			return true;
		else 
			return false;
	}
	public function getAvailablePositionsCount($palletID,$productID){
		$width = $this->db->query("SELECT width FROM " . DB_PREFIX . "product  WHERE product_id = $productID");
		$width = $width->rows[0]["width"];
		$productData = $this->getPalletProduct($palletID,$productID);


		if(isset($productData) and isset($productData['product_id']) and $productID == $productData['product_id']){
			$count     =  $productData['Count'];
			error_log("Count: ".$count);

		}
		else {
			$countAvailable = floor(Pallet_Detph / $width);
			error_log("Count available: ".$countAvailable);
			return $countAvailable; // return max not zero
		}
			
		// what is hapenning here? we have to explore
		// now get product dimensions 
		$productInfo = $this->db->query("select op.*,unit from " . DB_PREFIX . "product op
			join " . DB_PREFIX . "length_class_description olcd 
			on op.length_class_id = olcd.length_class_id
			where product_id = $productID and language_id = 1");

		$width  = $productInfo->row['width']; // this factor is for the available positions, we should get classID also, 
		$length = $productInfo->row['length'];
		$height = $productInfo->row['height'];
		$lengthClassID = $productInfo->row['length_class_id'];
		$weight = $productInfo->row['weight'];
		$availableSpace = Pallet_Detph - $count*$width;
		$countAvailable = floor($availableSpace / $width);

		return $countAvailable;
	}
	public function verifyProductPallet($palletID,$productID){
		// check if a pallet is assigned or not
		$countQuery = $this->getPalletProduct($palletID,$productID);
		//var_dump($countQuery);
		$count = -1;
		if(isset($countQuery['Count'])){
			$count = $countQuery['Count'];
			error_log("Count $count");
			if($count > 0)
				return "Assigned";
		}

		else {
			$isItAssigned = $this->db->query("SELECT count(start_pallet) as Count,pallet FROM " . DB_PREFIX . "product_to_position WHERE start_pallet= $palletID group by start_pallet");
			if( $isItAssigned->num_rows > 0)
				return "Assigned to another Product";
			else 
				return "Not Assigned";
		}
	}
	public function updateStock($palletID,$productID){
		$countAvailable = $this->getAvailablePositionsCount($palletID,$productID);
		if($countAvailable < 1){

			return -1; 
		}
			
		
		$update = $this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = quantity+1 WHERE product_id = $productID");
		if($update){
			$unitIDQuery  = $this->db->query("SELECT unit_id,shelf_id FROM `oc_pallet` WHERE pallet_id = $palletID");
			$unitID = $unitIDQuery->rows[0]['unit_id'];
			$shelfID = $unitIDQuery->rows[0]['shelf_id'];
			$update = $this->db->query("INSERT INTO `oc_product_to_position` (`position_id`, `product_id`, `shelf_id`, `unit_id`, `start_pallet`, `expiry_date`, `date_added`) 
				VALUES (NULL, '$productID', '$shelfID', '$unitID', '$palletID', '2022-04-30', CURRENT_TIMESTAMP);");
			if($update)
				return 1;

		}
		

	}

	public function assignPalletProduct($palletID,$productID,$bentCount,$update){
		if(!$update)
			$assigned = $this->db->query("
				INSERT INTO `oc_pallet_product` (`pallet_product_id`, `start_pallet_id`, `product_id`, `bent_count`, `time_created`, `time_modified`, `expiration_date`) 
				VALUES (NULL,$palletID, $productID, $bentCount, current_timestamp(), current_timestamp(), NULL);
			");
		else 
			$updated = $this->db->query("
				UPDATE `oc_pallet_product` set product_id = $productID,bent_count=$bentCount where start_pallet_id = $palletID");
		

	}
}