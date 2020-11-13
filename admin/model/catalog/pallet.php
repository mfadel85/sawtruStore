<?php

define('PALLET_DEPTH', 57);
define('COLUMN_COUNT', 6);

class ModelCatalogPallet extends Model {
	public function getPallet($palletID){
		$query = $this->db->query("SELECT * FROM `oc_pallet`  WHERE pallet_id = $palletID");
		return $query->row;		
	}

	public function getPalletProduct($palletID,$productID){

		$query = $this->db->query("SELECT count(start_pallet) as Count,product_id,pallet FROM " . DB_PREFIX . "product_to_position WHERE product_id= $productID AND start_pallet= $palletID group by start_pallet");
		return $query->row;
	}
	/*public function verifyProductPallet($palletID,$productID){
		// check if a pallet is assigned or not
		$countQuery = $this->getPalletProduct($palletID,$productID);
		$count = $countQuery['count'];
		error_log("Count $count");
		if($count > 0){
			return "Assigned";
		}
		else {
			$isItAssigned = $this->db->query("SELECT count(start_pallet) as Count,pallet FROM " . DB_PREFIX . "product_to_position WHERE start_pallet= $palletID group by start_pallet");
			if($isItAssigned->row['count']>0)
				return "Assigned to another Product";
			else 
				return "Not Assigned";
		}
	}*/
	public function getProductPositionInfo($palletID,$productID){
		$productData = $this->getPalletProduct($palletID,$productID);
		if(isset($productData) and isset($productData['product_id']) and $productID == $productData['product_id']){
			$pallet = $productData['pallet'];
			$count  = $productData['Count'];
		}
		else 
			return 0;


		$productInfo = $this->db->query("
		select op.*,unit,name from " . DB_PREFIX . "product op
			join " . DB_PREFIX . "length_class_description olcd 
			on op.length_class_id = olcd.length_class_id
			join " . DB_PREFIX . "product_description opd
			on op.product_id = opd.product_id
			where op.product_id = $productID and olcd.language_id = 1");
		//error_log("Product_id is : ".$productID);
		$width     = $productInfo->row['width']; // this factor is for the available positions, we should get classID also, 
		$length    = $productInfo->row['length'];
		$height    = $productInfo->row['height'];
		$name      = $productInfo->row['name'];
		$beltCount = $productInfo->row['bent_count'];
		$max = floor(PALLET_DEPTH / $width);
		$lengthClassID = $productInfo->row['length_class_id'];
		$weight = $productInfo->row['weight'];
		$availableSpace = PALLET_DEPTH - $count*$width;
		$countAvailable = floor($availableSpace / $width);
		$result = [$countAvailable, $name,$beltCount,$pallet,$max];
		return $result;
	}
    public function getMap(){
		$map = array();
		//MFH we have to chnage the way we are showing the map
		$query = $this->db->query("
			SELECT * from oc_pallet op 
			left join oc_pallet_product opp
		    on op.pallet_id = opp.start_pallet_id /*where opp.position < 2*/
		    order by unit_id,shelf_id,x_position");  
		//print_r($query);
		$skipCount = 0;
		$i = 0;
		foreach ($query->rows as $pallet) {
			$i++;
			if($skipCount>0){
				$skipCount--;
				continue;
			}
			$column = $i % COLUMN_COUNT; // 
			if($column == 0)
				$column +=COLUMN_COUNT;
			$row = ($i-$column)/COLUMN_COUNT+1;
			$unitID = $pallet['unit_id'];
			$productID = $pallet['product_id'];
			$shelfID = $pallet['shelf_id'];
			$countQuery = $this->db->query("SELECT count(*) as count ,product_id as productID FROM `oc_product_to_position` WHERE start_pallet = ".$pallet['pallet_id']." and status='Ready'" );
			$beltCount = 1;
			$max = 10;
			$count      = $countQuery->row["count"];
			$productID  = $countQuery->row["productID"] ?? $pallet['product_id'];
			$palletID   = $pallet['pallet_id'];
			$barcode    = $pallet['barcode'];
			$information = array();
			$availableSpace = -1;
			if($productID != null && isset($pallet['position']) && $pallet['position']==1){
				$information    = $this->getProductPositionInfo($palletID,$productID);

				if($information != "0"){
					$max            = $information[4];
					$availableSpace = $max - $count;
					$productName    = $information[1];
					$beltCount      = $information[2];
					$pallet         = $information[3];
				}
				else {
					$nameQuery = $this->db->query("select name,width,bent_count from oc_product_description opd 
					join oc_product op 
					on op.product_id = opd.product_id 
					where opd.product_id=  $productID 
					and language_id=". (int)$this->config->get('config_language_id'));

					$availableSpace = $max = floor(PALLET_DEPTH / $nameQuery->rows[0]["width"]);				
					$beltCount = $pallet['bent_count'];
					$pallet = $pallet['pallet_id'];
					$productName = $nameQuery->rows[0]["name"];	

				}

				$map[$unitID][$shelfID][] = [$palletID , $count,$productID,$availableSpace,$productName,$beltCount ,$max,$column,$row,$barcode,$productID]; /// has to be an array current,produt name,product id ,how many pallets will take]
				$skipCount = $beltCount -1;
				if($skipCount>0){
					for($j=1;$j<$skipCount+1;$j++) 							// $barcode will change
					{
						$nextBeltID = $this->getNextBeltID($palletID,$j);
						$barcode = $this->db->query("select barcode from oc_pallet where pallet_id = $nextBeltID")->rows[0]['barcode'];
						$map[$unitID][$shelfID][] = [$palletID , $count,$productID,$availableSpace,$productName,$beltCount ,$max,$column+$j,$row,$barcode,$productID]; /// has to be an array current,produt name,product id ,how many pallets will take]
					}
						
				}
			}
			else  {
				$productName = "NA";
				$oppQuery = $this->db->query("select * from oc_pallet_product where start_pallet_id =$palletID limit 1");
				$availableSpace = -1;// to be calculated

				if(count($oppQuery->rows)>0){
					$result = $oppQuery->rows[0];
					if(isset($result['product_id'])){
						$productID = $result['product_id'];		
						$nameQuery = $this->db->query("select name,width,bent_count from oc_product_description opd 
							join oc_product op 
							on op.product_id = opd.product_id 
							where opd.product_id=  $productID 
							and language_id=". (int)$this->config->get('config_language_id'));		
						$productName = $nameQuery->rows[0]["name"];	
						$availableSpace = floor(PALLET_DEPTH / $nameQuery->rows[0]["width"]);
						$beltCount = $nameQuery->rows[0]["bent_count"];
						if($beltCount-1>0){
							$skipCount = $beltCount - 1;
							for($j=1;$j<$skipCount+1;$j++)
								$map[$unitID][$shelfID][] = [$palletID , $count,$productID,$availableSpace,$productName,$beltCount ,$max,$column+$j,$row,$barcode,$productID]; /// has to be an array current,produt name,product id ,how many pallets will take]
						}
					}
				}
				$map[$unitID][$shelfID][] = [$palletID , $count,$productID,$availableSpace,$productName,$beltCount,$max ,$column,$row,$barcode]; /// has to be an array current,produt name,product id ,how many pallets will take]
			}
		}
		//print_r($map);
		return $map;
	}
	public function getNextBeltID($beltID,$i){
		$palletInfo = $this->db->query("SELECT shelf_id,x_position,unit_id from oc_pallet where pallet_id = $beltID");
		$row    = $palletInfo->row["shelf_id"];
		if((int)$palletInfo->row["x_position"]>5)
			return -1;

		$xPos   = (int)$palletInfo->row["x_position"]+$i;
		$xPosX = (int)$palletInfo->row["x_position"];
		$unitID = $palletInfo->row["unit_id"];
		$nextBeltID = $this->db->query("SELECT pallet_id FROM `oc_pallet` where shelf_id= $row and x_position= $xPos and unit_id = $unitID")->row['pallet_id'];
		return $nextBeltID;
	}	
	public function getUnits(){
		$units = array();
		$query = $this->db->query("SELECT * FROM `oc_unit` order by unit_id");
		foreach($query->rows as $unit){
			$units[] = $unit;
		}
		return $units;
	}
	public function getShelves(){
		$units = $this->getUnits();
		$map = array();
		$shelves = array();
		$query = $this->db->query("SELECT * FROM `oc_shelf` ORDER BY `oc_shelf`.`unit_id` ASC, shelf_id ASC");
		foreach($query->rows as $shelf){
			$unitID = $shelf['unit_id'];

			$map[$unitID][] = $shelf['shelf_id'];
		}

		//return $map;
	}

}