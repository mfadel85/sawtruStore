<?php
class ModelCatalogRefill extends Model {
    public function getBelts($barcode){
        /// get produc id based on barcode
        $productID = $this->db->query("select product_id from " . DB_PREFIX . "product where sku=$barcode" )->row['product_id'];

        // get all belts that contain 
        $belts = $this->db->query("SELECT * from " . DB_PREFIX . "pallet WHERE product_id = $productID and position='Start' ");
        $beltsProduct = array();
        foreach ($belts->rows as $belt) {
            $beltID    = $belt['pallet_id'];
            $unitID    = $belt['unit_id'];
            $shelfID   = $belt['shelf_id'];
            $position  = $belt['x_position'];
            $barcode   = $belt['barcode'];
            $unitName  = $this->db->query("SELECT * FROM " . DB_PREFIX . "unit where unit_id=$unitID")->row['name'];
            $direction = $this->db->query("SELECT * FROM " . DB_PREFIX . "unit where unit_id=$unitID")->row['direction'];
            $sortOrder = $this->db->query("SELECT * FROM " . DB_PREFIX . "unit where unit_id=$unitID")->row['sort_order'];
            $shelfNo   = $this->db->query("SELECT * FROM "  . DB_PREFIX . "shelf where shelf_id =$shelfID")->row['shelf_physical_row'];


            $beltsProduct[] = [$beltID,$unitName,$barcode,$direction,$sortOrder,$shelfNo];
        }
        /// get unit id, unit name,shelf no physical, available positions
        return $beltsProduct;

    }
}