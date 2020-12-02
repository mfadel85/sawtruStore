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
    public function addWithdraw($data){
        $amount = $data['amount'];
        $note = $data['note'];
        $insert = "INSERT INTO `oc_transaction` (`transaction_id`, `amount`, `note`, `date_added`) VALUES (NULL, '$amount', '$note', current_timestamp());";
        $result = $this->db->query($insert);
        return $result;

    }
    public function getMax() {
		$withdraws = $this->db->query("SELECT sum(amount) as sum FROM `oc_transaction`");
		$totalWithdraws = $withdraws->row['sum'];
		$sql = "SELECT sum(total) as total FROM `oc_order` WHERE order_status_id = 5";
		$query = $this->db->query($sql);
		return $query->row['total']-$totalWithdraws;
	}
}