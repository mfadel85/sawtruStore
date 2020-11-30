<?php
class ModelExtensionDashboardOnline extends Model {
	public function getTotalOnline($data = array()) {
		// to be calculated
		//return "500";
		$sql = "SELECT sum(total) as total FROM `oc_order` WHERE order_status_id = 5";


		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}