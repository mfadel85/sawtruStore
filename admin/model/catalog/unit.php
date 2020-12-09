<?php
class ModelCatalogUnit extends Model {
    public function addUnit($data){
        
        $this->db->query("INSERT INTO " . DB_PREFIX . "unit SET name = '" . $this->db->escape($data['name']) . "',
         direction  = '" . $this->db->escape($data['unit_direction']) . "',
         sort_order  = '" . $this->db->escape($data['sort_order']) . "',
         barcode = '" . $this->db->escape($data['barcode']) . "'");

        $unit_id = $this->db->getLastId();
		$this->cache->delete('unit');

		return $unit_id;
    }
    public function editUnit($unit_id, $data){
        $this->db->query("UPDATE " . DB_PREFIX . "unit SET name = '" . $this->db->escape($data['name'])  . "',
        direction = '" . $this->db->escape($data['unit_direction'])  . "',
        sort_order = '" . $this->db->escape($data['sort_order'])  . "',

         barcode = '" . (int)$this->db->escape($data['barcode']) . "'  WHERE unit_id = '" . (int)$unit_id . "'");
        $this->cache->delete('unit');
    }
    public function deleteUnit($unit_id){
        // we have to delete all of its shelves and belts
        $this->db->query("DELETE FROM " . DB_PREFIX . "unit WHERE unit_id = '" . (int)$unit_id . "'");
		$this->cache->delete('unit_id');
    }
    public function checkStatus($unitID){
        //SELECT * FROM `oc_pallet` WHERE unit_id = $unitID
        $status = " Empty";
        $fullCount = 0;
        $emptyCount = 0;
        $allBelts = $this->db->query("SELECT * from oc_pallet where unit_id=$unitID");
        foreach($allBelts->rows as $belt){
            if($belt['quantity'] > 0){
                $status = " Partially";
                $fullCount++;
            }
            else {
                $emptyCount++;
            }
        }
        if($fullCount == 0){
            $status = " Empty";
        }

        return $status;

    }
    public function getTotalUnits($data = array()){
        $sql = "SELECT COUNT(*) as total FROM oc_unit";
        $query = $this->db->query($sql);

		return $query->row['total'];
    }
    public function getUnits($data= array()){
        $units = array();
        $sql = "SELECT * FROM " . DB_PREFIX . "unit order by direction";
        $query = $this->db->query($sql);
        
        foreach($query->rows as $result){
            $units[] = $result;
        }
        return $units;
    }
    public function getBeltProductInfo($beltID,$productID){
		$productInfo = $this->db->query("
		select op.*,unit,name from " . DB_PREFIX . "product op
			join " . DB_PREFIX . "length_class_description olcd 
			on op.length_class_id = olcd.length_class_id
			join " . DB_PREFIX . "product_description opd
			on op.product_id = opd.product_id
            where op.product_id = $productID and olcd.language_id = 1");
            
		$width     = $productInfo->row['width']; // this factor is for the available positions, we should get classID also, 
		$length    = $productInfo->row['length'];
		$height    = $productInfo->row['height'];
		$name      = $productInfo->row['name'];
		$beltCount = $productInfo->row['bent_count'];
        $weight    = $productInfo->row['weight'];
        $max       = floor(PALLET_DEPTH / $width);

		$availableSpace = PALLET_DEPTH - $count*$width;
		$countAvailable = floor($availableSpace / $width);
		$result = [$countAvailable, $name,$beltCount,$pallet,$max];
		return $result;
    }
    public function getBeltCount($unitID){
        $shelfID = $this->db->query("SELECT shelf_id from oc_shelf where unit_id=$unitID limit 0,1")->rows[0]['shelf_id'];
        $query = "select count(*) as count from oc_pallet where shelf_id =$shelfID ";
        $count = $this->db->query($query)->row['count'];
        return $count;
    }
    public function getUnitDetails($unitID){
        $unit = array();
        // get all rows in that unit
        $shelves = $this->db->query("SELECT shelf_id,shelf_physical_row from oc_shelf where unit_id = $unitID");
        foreach($shelves->rows  as $shelf)
        {
            $shelfID     = $shelf['shelf_id'];
            $physicalRow = $shelf['shelf_physical_row'];
            $shelf = array('id' => $shelfID,'physicalRow'=> $physicalRow,'contents'=> array() );
            $query = "SELECT * from oc_pallet where shelf_id =$shelfID ";
            $results = $this->db->query($query);

            foreach($results->rows as $belt){
                $productID   = $belt['product_id'];
                $productName = '';
                if(isset($productID) && $belt['status']){
		            $count =  $belt['quantity'];
                    $productInfo = $this->db->query("
                    SELECT name,width from oc_product_description opd 
                    join oc_product  op on opd.product_id = op.product_id
                    where opd.product_id =$productID");
                    $productName = $productInfo->row['name'];
                    $width = $productInfo->row['width'];
                    $full = false;
                    $max       = floor(57 / $width);
                    $availableSpace = 57 - $count*$width;
                    $countAvailable = floor($availableSpace / $width);    
                    $shelf['contents'][] = array(
                        $belt['pallet_id'],
                        $belt['product_id'],
                        $productName,
                        $belt['quantity'],
                        $full,
                        $max,
                        $countAvailable,
                        $belt['barcode'],
                        1, /* active or inactive*/
                        ''
                    );                
                }
                else {
                    if(!$belt['status'])
                        $shelf['contents'][] = array('',0,'',0,'','',0,$belt['barcode'],0,'Disabled Belt');                
                    else
                        $shelf['contents'][] = array('',0,'',0,'','',0,$belt['barcode'],1,'');                
                }
            }
            $unit[]=$shelf;
        }
        //print_r($unit);
        return $unit;
    }
    public function getUnit($unit_id){
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "unit p  WHERE p.unit_id = '" . (int)$unit_id . "' ");
		return $query->row;        
    }
}