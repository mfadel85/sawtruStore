<?php
define('Pallet_Detph', 57);

class ModelCatalogRefill extends Model {

    
	private function getBeltProduct($beltID,$productID){
		$query = $this->db->query("SELECT count(start_pallet) as Count,product_id FROM " . DB_PREFIX . "product_to_position 
		WHERE product_id=$productID AND start_pallet= $beltID and status != 'Sold' group by start_pallet");
		return $query->row;
    }
    
    private function getAvailablePositionsCount($beltID,$productID){

		$width = $this->db->query("SELECT width FROM " . DB_PREFIX . "product  WHERE product_id = $productID");
		$width = $width->rows[0]["width"];
		$productData = $this->getBeltProduct($beltID,$productID);


		if(isset($productData) and isset($productData['product_id']) and $productID == $productData['product_id']){
			$max = floor(Pallet_Detph / $width);
            
			$count = $productData['Count'];
			// it has to be Max-$count
			return $max-$count;
		}
		else {
			$countAvailable = floor(Pallet_Detph / $width);
			error_log("Count available: ".$countAvailable);
			return $countAvailable; // return max not zero
		}
			
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
    public function getBelts($barcode,$quantity){
        /// get produc id based on barcode
        $productInfo   = $this->db->query("select * from " . DB_PREFIX . "product where sku=$barcode" );
        $productID = $productInfo->row['product_id'];
        $beltCount = $productInfo->row['bent_count'];
        $length = $productInfo->row['length'];


        // get all belts that contain 
        $belts = $this->db->query("SELECT * from " . DB_PREFIX . "pallet WHERE product_id = $productID and position='Start' ");
        $beltsProduct = array();
        foreach ($belts->rows as $belt) {
            $beltID    = $belt['pallet_id'];
            $unitID    = $belt['unit_id'];
            $shelfID   = $belt['shelf_id'];
            $position  = $belt['x_position'];
            $barcode   = $belt['barcode'];
            $unitName  = $this->db->query("SELECT * FROM " . DB_PREFIX . "unit where unit_id=$unitID")->row['name'];
            $direction = $this->db->query("SELECT * FROM " . DB_PREFIX . "unit where unit_id=$unitID")->row['direction'];
            $sortOrder = $this->db->query("SELECT * FROM " . DB_PREFIX . "unit where unit_id=$unitID")->row['sort_order'];
            $shelfNo   = $this->db->query("SELECT * FROM "  . DB_PREFIX . "shelf where shelf_id =$shelfID")->row['shelf_physical_row'];
            /// available positions in this belt
            $countAvailable = $this->getAvailablePositionsCount($beltID,$productID);
            if($countAvailable >= $quantity)
                $beltsProduct[] = array(
                    "beltID"         => $beltID,
                    "unitName"       => $unitName,
                    "barcode"        => $barcode,
                    "direction"      => $direction,
                    "sortOrder"      => $sortOrder,
                    "shelfNo"        => $shelfNo,
                    "countAvailable" => $countAvailable,
                    "position"       => $position,
                    "beltCount"      => $beltCount,
                    "length"         => $length
                );

        }
        return $beltsProduct;
    }
    private function updateTillEnd($beltID,$quantity){
        // get status of the beltif single nothing to be done, if start till the end
        $this->load->model("catalog/pallet");

        $position = $this->db->query("SELECT position FROM `oc_pallet` where pallet_id = $beltID")->row['position'];
        if($position == "Single")
            return 1;
        else 
            while ($position != "End"){
                /// update next cell with the quantity
                $nextBeltID = $this->model_catalog_pallet->getNextBeltID($beltID,1);
                $beltID =$nextBeltID;
                $this->db->query("UPDATE OC_PALLET set quantity = $quantity where pallet_id = $nextBeltID");
                $position = $this->db->query("SELECT position FROM `oc_pallet` where pallet_id = $nextBeltID")->row['position'];
            }

    }
    public function refill($beltID,$quantity){
        print_r("Belt ID is  $beltID");
        $this->load->model("catalog/pallet");
        // add to oc_pallet, oc_product_pallet we need the product _id
        $beltInfo = $this->db->query("SELECT product_id,quantity from oc_pallet where pallet_id=$beltID");
        $productID = $beltInfo->row['product_id'];
        $update = $this->db->query("update oc_product set quantity = quantity+$quantity where product_id=$productID");

        $origQuantity = $beltInfo->row['quantity'];
        $newQuantity = intval($origQuantity) + intval($quantity);
        $this->db->query("UPDATE OC_PALLET set quantity = $newQuantity where pallet_id = $beltID");
        // UPDATE OC_PRODUCT ALSO
        // get 
        $this->updateTillEnd($beltID,$newQuantity);
        // if multibelt product then all of the belts included in this update 
        // till gets to the end?
        $unitIDQuery = $this->db->query("SELECT unit_id,shelf_id FROM `oc_pallet` WHERE pallet_id = $beltID");
        $unitID = $unitIDQuery->rows[0]['unit_id'];
        $shelfID = $unitIDQuery->rows[0]['shelf_id'];

        for($i=0;$i<$quantity;$i++){
            $update = $this->db->query("INSERT INTO `oc_product_to_position` (`position_id`, `product_id`, `shelf_id`, `unit_id`, `start_pallet`, `expiry_date`, `date_added`)
				VALUES (NULL, '$productID', '$shelfID', '$unitID', '$beltID', '2022-04-30', CURRENT_TIMESTAMP);");
        }
    }

}