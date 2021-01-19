<?php
class ModelCatalogRefill extends Model {
    public function getBelts($barcode){
        /// get produc id based on barcode
        $productID = $this->db->query("select product_id from " . DB_PREFIX . "product where sku=$barcode" )->row['product_id'];

        // get all belts that contain 
        $belts = $this->db->query("SELECT * from " . DB_PREFIX . "pallet WHERE product_id = $productID");
        print_r($belts);
        return $productID;

    }
}