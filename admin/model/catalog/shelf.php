<?php
class ModelCatalogShelf extends Model {

    public function addShelf($data){

        $this->db->query("INSERT INTO " . DB_PREFIX . "shelf SET 
        shelf_physical_row = '" . $this->db->escape($data['physical_row']) . "', 
        barcode = '" . $this->db->escape($data['barcode']) . "',
        unit_id = '" . $this->db->escape($data['unit_name']) . "',
        height = '" . $this->db->escape($data['height']) . "',
        width = '" . $this->db->escape($data['width']) . "'
        ");

        $shelf_id = $this->db->getLastId();
		$this->cache->delete('shelf');
		return $shelf_id;
    }

    public function editShelf($shelf_id,$data){
        print_r("Hello World");
        $this->db->query("UPDATE " . DB_PREFIX . "shelf 
        SET shelf_physical_row = '" . $this->db->escape($data['physical_row'])  . "', 
        barcode = '" . (int)$this->db->escape($data['barcode']) . "',  
        unit_id = '" . $this->db->escape($data['unit_name']) . "',
        height = '" . $this->db->escape($data['height']) . "',
        width = '" . $this->db->escape($data['width']) . "'
        WHERE shelf_id = '" . (int)$shelf_id . "'");
        $this->cache->delete('shelf');
    }

    public function deleteShelf($shelf_id){
        // we have to delete all of its  belts
        $this->db->query("DELETE FROM " . DB_PREFIX . "shelf WHERE shelf_id = '" . (int)$shelf_id . "'");
		$this->cache->delete('shelf_id');
    }
    
    public function getTotalShelves($unit_id=0,$data=array()){
        $sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "shelf";
        $query = $this->db->query($sql);

		return $query->row['total'];
    }
    public function generateBelts($shelf_id,$data){// we need barcodes
        $unit_id = $this->getUnitID($shelf_id);
        $beltCount =  (int)$this->db->escape($data['beltCount']);
        for($i = 0;$i<$beltCount;$i++){
            $j = 1+$i;
            $inputName = 'barcode'.$j;
            $barcodeValue = $this->db->escape($data[$inputName]);
            $query = "INSERT INTO `oc_pallet` 
            (`pallet_id`, `shelf_id`, `x_position`, `barcode`, `unit_id`, `date_modified`, `date_created`) 
            VALUES 
            (NULL, '$shelf_id', ' $j', '$barcodeValue', '$unit_id', current_timestamp(), current_timestamp());";
            $this->db->query($query);
        }
    }
    public function getBelts($shelf_id){
        $beltsResults = $this->db->query("SELECT * from oc_pallet where shelf_id=$shelf_id");
        $belts = array();
        foreach($beltsResults->rows as $belt){
            $belts[] = $belt;
        }
        return $belts;
    }
    public function updateBarcodes($shelf_id,$data){
        /// VIN to be implemented
        $beltCount =  (int)$this->db->escape($data['beltCount']);
        
        for($i = 0;$i<$beltCount;$i++){
            $j = $i+1;
            $inputName = 'barcode'.$j;
        }
    }

    public function getUnitID($shelf_id){
        $unit_id = $this->db->query("SELECT unit_id from oc_shelf where shelf_id=$shelf_id")->rows[0]['unit_id'];
        return $unit_id;
    }

    public function getShelves($unit_id,$data=array()){
        $shelves = array();
        $sql = "SELECT shelf_id,os.barcode,height,width,os.unit_id,name as unit_name,shelf_physical_row as physical_row FROM `oc_shelf` 
        os join oc_unit ou on os.unit_id = ou.unit_id where active = 1 ORDER BY `shelf_id` ASC";
        $query = $this->db->query($sql);
        foreach($query->rows as $shelf){
            $shelf_id= $shelf['shelf_id'];
            $count = $this->db->query("SELECT count(pallet_id) as count  from oc_pallet where shelf_id =$shelf_id")->rows[0]['count'];
            if($count>0)
                $shelf['noBelts'] = false;
            else 
                $shelf['noBelts'] = true;
            $shelves[] = $shelf;
        }
        return $shelves;
    }

	public function emptyShelf($shelfID){
        /// get all belts in this shelf and empty them
        print_r("Belt ID is $shelfID <BR>");
        $belts = $this->db->query("SELECT pallet_id from oc_pallet WHERE shelf_id = $shelfID");
        print_r($belts);
		foreach($belts->rows as $belt){
			$this->emptyBelt($belt['pallet_id']);
		}
    }    

	public function emptyBelt($beltID){
        // get count and which product it has
        $productInfo = $this->db->query("SELECT count(*) as count,product_id FROM `oc_product_to_position` where start_pallet = $beltID");
        $message = "";
        $count = $productInfo->rows[0]["count"];
        if($count > 0){
            $productID = $productInfo->rows[0]["count"];
            $modifyStock = $this->db->query("UPDATE OC_PRODUCT set quantity = quantity-$count where product_id = $productID");/// decrease the stoc
            $emptyProductPosition = $this->db->query("DELETE FROM oc_product_to_position where start_pallet = $beltID ");
            $message =  "The belt has been emptied";
        }
        else  {
            $message =  "It is an empty belt!!";
        }
        $emptyBeltProduct = $this->db->query("DELETE FROM oc_pallet_product where start_pallet_id = $beltID ");
        return $message;

    }    
    public function emptyUnit($unitID){
        // get all shelves from this unit and empty them
        $shelves = $this->db->query("SELECT * FROM `oc_shelf` WHERE unit_id = $unitID");
        print_r($shelves);
        if(count($shelves->rows)>0){
            foreach($shelves->rows as $shelf)
                $this->emptyShelf($shelf['shelf_id']);
        }

    }
    
    public function getShelf($shelfID){
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "shelf   WHERE shelf_id = '" . (int)$shelfID . "' ");
		return $query->row; 
    }
}