<?php
class ModelCatalogATM extends Model {
    public function editAtm(){

    }
    public function getAtm(){
        $query = "select * from oc_atm_module where id = 1";
        $atm = $this->db->query($query)->row;
        return $atm;
    }
}