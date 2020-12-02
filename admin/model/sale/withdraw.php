<?php
class ModelSaleWithdraw extends Model {
    public function getWithdraws(){
        $sql = "SELECT * FROM `oc_transaction`";
        $query = $this->db->query($sql);

		return $query->rows;
    }
    public function getTotalWithdraws() {
        $sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "transaction`";
        $query = $this->db->query($sql);

		return $query->row['total'];
    }
}