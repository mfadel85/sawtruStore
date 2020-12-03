<?php
class ModelCatalogSupply extends Model {

    public function addSupply($product_id,$quantity){
        $userID = $this->session->data['user_id'];
        $userToken = $this->session->data['user_token'];
        $sql = "INSERT INTO `oc_supply` (`supply_id`, `product_id`, `user_id`, `session_id`, `quantity`, `date_added`) VALUES (NULL, '$product_id', '$userID', '$userToken', '$quantity', current_timestamp());";
        $this->db->query($sql);
    }

    public function getSupplyList(){
        $query = $this->db->query("SELECT *,name as product_name FROM `oc_supply` os join oc_product_description opd on os.product_id = opd.product_id");
        return $query->rows;
    }
}