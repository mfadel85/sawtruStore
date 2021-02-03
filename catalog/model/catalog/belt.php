<?php
	define('Pallet_Detph', 57);

class ModelCatalogBelt extends Model {
	public function getNextBeltID($beltID,$i=1){
		// maxCol is the max column we can choose
		$maxCol = 10;
		error_log("Belt id is $beltID, the value of is $i");
		$beltInfo = $this->db->query("SELECT shelf_id,x_position,unit_id from oc_pallet where pallet_id = $beltID");
		$row    = $beltInfo->row["shelf_id"];
		if((int)$beltInfo->row["x_position"]>$maxCol)
			return -1;

		$xPos   = (int)$beltInfo->row["x_position"]+$i;
		$unitID = $beltInfo->row["unit_id"];
		$nextBeltID = $this->db->query("SELECT pallet_id FROM `oc_pallet` where shelf_id= $row and x_position= $xPos and unit_id = $unitID")->row['pallet_id'];
		return $nextBeltID;
    }
   
}