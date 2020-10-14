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
	public function getProductHeight($productID){
		$height = $this->db->query("SELECT height FROM " . DB_PREFIX . "product  WHERE product_id = $productID");
		$height = $height->rows[0]["height"];
		return $height;
	}
	public function getShelfHeight($beltID){
		$shelfID = $this->db->query("select shelf_id from oc_pallet where pallet_id=$beltID")->rows[0]['shelf_id'];
		$shelfHeight = $this->db->query("SELECT height FROM `oc_shelf` where shelf_id =$shelfID")->rows[0]['height'];
		return $shelfHeight;
	}
	public function verifyShelfProduct($beltID,$productID){
		$shelfHeight = $this->getShelfHeight($beltID);
		$productHeight = $this->getProductHeight($productID);
		error_log(" shelfheigt $shelfHeight Product height $productHeight");
		if((int)$shelfHeight >= (int)$productHeight){
			error_log(" kill you?");

			return true;
		}
		else {
			error_log(" kill them?");

			return false;

		}
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
			print_r(" PP is $palletID, prdct is $productID");

			if(!$this->verifyShelfProduct($palletID,$productID)){
				//print_r("got you");
				return "Not Allowed Operation";
			}
				
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
	public function getPalletStatus($palletID){
		$state = $this->db->query("SELECT * FROM `oc_pallet_product` where start_pallet_id = 13");
		if(count($state->row)==1){
			$status = "Assigned Empty";
			$isitFilled = $this->db->query("SELECT count(*) as Count FROM `oc_product_to_position` WHERE start_pallet = 13");			
			if(isset($isitFilled->row['count']) && $isitFilled->row['count']>0)
				$status = "Assigned Not Empty";			
		}
		else 
			$status = "Not Assigned Empty";
		return $status;

	}
	public function getNextPalletID($palletID,$i){
		$palletInfo = $this->db->query("SELECT shelf_id,x_position,unit_id from oc_pallet where pallet_id = $palletID");
		$row    = $palletInfo->row["shelf_id"];
		if((int)$palletInfo->row["x_position"]>5)
			return -1;

		$xPos   = (int)$palletInfo->row["x_position"]+$i;
		$xPosX = (int)$palletInfo->row["x_position"];
		error_log("before $xPosX, after xPos is $xPos ");
		$unitID = $palletInfo->row["unit_id"];
		$nextPalletID = $this->db->query("SELECT pallet_id FROM `oc_pallet` where shelf_id= $row and x_position= $xPos and unit_id = $unitID")->row['pallet_id'];
		return $nextPalletID;
	}
	public function assignPalletProduct($beltID,$productID,$beltCount,$update){
		error_log("Z $beltID,$productID,$beltCount,$update");
		// check before inserting
		$assignable = false;
		if($beltCount > 1 ){
			for($i=0;$i<$beltCount-1;$i++){
				/// get next bent id
				$nextBeltID = $this->getNextPalletID($beltID,$i);
				$palletStats = $this->getPalletStatus($nextBeltID);
				if($palletStats == "Empty" || $palletStats == "Assigned Empty")
				{
					error_log("We are here Z: $palletStats, Belt ID $beltID, next belt id is $nextBeltID");
					// if assigned empty and the cells before it is also assigned empty
					// to delete the record of $nextPalletID
					if($palletStats == "Assigned Empty") {
						$this->db->query("DELETE from `oc_pallet_product` where start_pallet_id = $nextBeltID");
					}
					$assignable = true;
					continue;
				}
				else if($palletStats == "Assigned Not Empty"){
					$assignable = false;
					break;
				}
					
			}
			$assignable = true;
		}
		else {
			$assignable = true;
		}
		error_log("We are here 0: $update,$assignable,$beltCount");

		if($update == "false" && $assignable){
			error_log("We are here to live");
			// all the cells to be written
			for($i=0;$i< $beltCount;$i++){
				if($i>0){ // comment
					$beltID = $this->getNextPalletID($beltID,1); 
					error_log("beltID is $beltID i is $i");
				}
				$assigned = $this->db->query("
					INSERT INTO `oc_pallet_product` (`pallet_product_id`, `start_pallet_id`, `product_id`, `bent_count`, `position`, `time_created`, `time_modified`, `expiration_date`) 
					VALUES (NULL,$beltID, $productID, $beltCount,$i+1, current_timestamp(), current_timestamp(), NULL);");
				print_r($assigned);
				error_log("Assigned $assigned");
			}
				
		}	
		else {
			for($i=1;$i<=$beltCount;$i++){
				if($i>1){ // comment
					$beltID = $this->getNextPalletID($beltID,$i); 
				}
				error_log("We are here to live in another way");
				$updated = $this->db->query("
				UPDATE `oc_pallet_product` set product_id = $productID,bent_count=$beltCount,position=$i where start_pallet_id = $beltID");
			}
		
		}

	}
}