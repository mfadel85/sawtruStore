<?php
class ModelCatalogATM extends Model {
    public function editAtm(){

    }
    public function getAtm(){
        $query = "SELECT * FROM oc_atm_module where id = 1";
        $atm = $this->db->query($query)->row;
        return $atm;
    }
    public function updateAtm($id,$data){
        $name = $data['name'];
        $status = $data['status'];
        if(isset($id) && isset($name) && isset($status)) {
            $query = "UPDATE oc_atm_module set name='$name',active=$status where id=$id;";
            $updated = $this->db->query($query);
        }
        else {
            return false;
        }
            
    }
}