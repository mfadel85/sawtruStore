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
    public function getTotalUnits($data = array()){
        $sql = "SELECT COUNT(*) as total FROM oc_unit";
        $query = $this->db->query($sql);

		return $query->row['total'];
    }
    public function getUnits($data= array()){
        $units = array();
        $sql = "SELECT * FROM " . DB_PREFIX . "unit";
        $query = $this->db->query($sql);
        
        foreach($query->rows as $result){
            $units[] = $result;
        }
        return $units;
    }

    public function getUnit($unit_id){
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "unit p  WHERE p.unit_id = '" . (int)$unit_id . "' ");
		return $query->row;        
    }
}