<?php
class ModelCatalogUnit extends Model {
    public function addUnit(){

    }
    public function editUnit(){

    }
    public function getTotalUnits($data = array()){
        $sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "unit";
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
}