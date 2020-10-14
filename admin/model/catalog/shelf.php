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
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "shelf   WHERE shelf_id = '" . (int)$shelf_id . "' ");
		return $query->row; 
    }
}