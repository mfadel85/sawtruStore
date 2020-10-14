<?php
class ModelCatalogShelf extends Model {
    public function addShelf($data){

    }

    public function editShelf($shelf_id,$data){

    }

    public function deleteShelf($row_id){

    }
    
    public function getTotalShelves($unit_id=0,$data=array()){
        $sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "shelf";
        $query = $this->db->query($sql);

		return $query->row['total'];
    }

    public function getShelves($unit_id,$data=array()){
        $shelves = array();
        $sql = "SELECT shelf_id,os.barcode,height,width,os.unit_id,name as unit_name,shelf_physical_row as physical_row FROM `oc_shelf` 
        os join oc_unit ou on os.unit_id = ou.unit_id where active = 1 ORDER BY `shelf_id` ASC";
        $query = $this->db->query($sql);
        foreach($query->rows as $shelf)
            $shelves[] = $shelf;
        return $shelves;


    }
    public function getShelf($shelf_id){

    }
}