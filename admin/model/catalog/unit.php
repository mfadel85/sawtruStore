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
    public function getBeltCount($unitID){
        $shelfID = $this->db->query("select shelf_id from oc_shelf where unit_id=$unitID limit 0,1")->rows[0]['shelf_id'];
        $query = "select count(*) as count from oc_pallet where shelf_id =$shelfID";
        $count = $this->db->query($query)->row['count'];
        return $count;
    }
    public function getUnitDetails($unitID){
        $unit = array();
        // get all rows in that unit
        $shelves = $this->db->query("select shelf_id from oc_shelf where unit_id = $unitID");
        foreach($shelves->rows  as $shelf)
        {
            $shelfID = $shelf['shelf_id'];
            $shelf = array('id' => $shelfID,'contents'=> array() );
            $query = "select * from oc_pallet where shelf_id =$shelfID";
            $results = $this->db->query($query);
            foreach($results->rows as $belt){
                $shelf['contents'][] = array($belt['pallet_id'],$belt['product_id'],$belt['quantity']);
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