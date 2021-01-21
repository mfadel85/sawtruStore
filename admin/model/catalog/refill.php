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
    public function getBelts($barcode){
        /// get produc id based on barcode
        $productID = $this->db->query("select product_id from " . DB_PREFIX . "product where sku=$barcode" )->row['product_id'];

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
            $beltsProduct[] = [$beltID,$unitName,$barcode,$direction,$sortOrder,$shelfNo,$countAvailable,$position];
        }
        return $beltsProduct;
    }
    public function refill($barcode,$quantity){
        print_r("Iloveyou!!!");
    }

}