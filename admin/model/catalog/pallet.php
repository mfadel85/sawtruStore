<?php
/*define('Pallet_Detph', 57);*/

class ModelCatalogPallet extends Model
{

    public function getPallet($beltID)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "pallet  WHERE barcode = $beltID");
        return $query->row;
    }

    public function getBeltProduct($beltID, $productID)
    {
        $query = $this->db->query("SELECT count(start_pallet) as Count,product_id FROM " . DB_PREFIX . "product_to_position
		WHERE product_id=$productID AND start_pallet= $beltID and status != 'Sold' group by start_pallet");
        return $query->row;
    }

    public function getMap()
    {
        $map = array();
        $query = $this->db->query("SELECT * from oc_pallet order by unit_id,shelf_id,x_position");
        foreach ($query->rows as $result) {
            $map[] = $result;
        }
        return $map;
    }

    public function productAssignedToPallet($palletID, $productID)
    {
        $count = $this->db->query("SELECT count(*) FROM `oc_pallet_product` where product_id = $productID and start_pallet_id = $palletID");
        if (count($count->row) > 0) {
            return true;
        } else {
            return false;
        }

    }
    public function disableBelt($beltID)
    {
        $isItActive = "SELECT status,product_id,quantity  from oc_pallet where pallet_id=$beltID";
        $active = $this->db->query($isItActive);
        if ($active->row['status']) {
            $productID = $active->row['product_id'];
            $quantity = $active->row['quantity'];
            if ($quantity > 0) {
                $this->db->query("UPDATE OC_PRODUCT set quantity = quantity-$quantity where product_id=$productID");
            }
            $query = "UPDATE oc_pallet set status = 0 where pallet_id=$beltID";
            $this->db->query($query);

        }
    }
    public function getBelts($shelfID)
    {
        $belts = $this->db->query("SELECT * from oc_pallet where shelf_id=$shelfID")->rows;
        return $belts;
    }
    public function getAvailablePositionsCount($beltBarcode, $productID)
    {
        $beltID = $this->getBeltID("$beltBarcode");
        error_log("Belt Barcode is : $beltBarcode, Product ID is $productID");
        $width = $this->db->query("SELECT width FROM " . DB_PREFIX . "product  WHERE product_id = $productID");
        $width = $width->rows[0]["width"];
        $productData = $this->getBeltProduct($beltID, $productID);

        if (isset($productData) and isset($productData['product_id']) and $productID == $productData['product_id']) {
            $max = floor(Pallet_Detph / $width);

            $count = $productData['Count'];
            // it has to be Max-$count
            return $max - $count;
        } else {
            $countAvailable = floor(Pallet_Detph / $width);
            error_log("Count available: " . $countAvailable);
            return $countAvailable; // return max not zero
        }

        $productInfo = $this->db->query("select op.*,unit from " . DB_PREFIX . "product op
			join " . DB_PREFIX . "length_class_description olcd
			on op.length_class_id = olcd.length_class_id
			where product_id = $productID and language_id = 1");

        $width = $productInfo->row['width']; // this factor is for the available positions, we should get classID also,
        $length = $productInfo->row['length'];
        $height = $productInfo->row['height'];
        $lengthClassID = $productInfo->row['length_class_id'];
        $weight = $productInfo->row['weight'];
        $availableSpace = Pallet_Detph - $count * $width;
        $countAvailable = floor($availableSpace / $width);

        return $countAvailable;
    }
    public function getProductHeight($productID)
    {
        $height = $this->db->query("SELECT height FROM " . DB_PREFIX . "product  WHERE product_id = $productID");
        $height = $height->rows[0]["height"];
        return $height;
    }
    public function getShelfHeight($beltID)
    {
        $shelfIDResult = $this->db->query("select shelf_id from oc_pallet where pallet_id=$beltID");
        $shelfID = $shelfIDResult->rows[0]['shelf_id'];
        $shelfHeight = $this->db->query("SELECT height FROM `oc_shelf` where shelf_id =$shelfID")->rows[0]['height'];
        return $shelfHeight;
    }
    private function getBeltID($barcode)
    {
        $barcodeResult = $this->db->query("SELECT pallet_id from oc_pallet where barcode = '$barcode'");
        return $barcodeResult->row['pallet_id'];
    }
    public function verifyShelfProduct($beltBarcode, $productID)
    {
        $beltID = $this->getBeltID("$beltBarcode");
        $shelfHeight = $this->getShelfHeight($beltID);
        $productHeight = $this->getProductHeight($productID);
        $result = (int) $shelfHeight >= (int) $productHeight ? true : false;
        return $result;
	}

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

    public function verifyProductPallet($beltBarcode, $productID)
    {
        // check if a pallet is assigned or not
        // I need barcode hre :D
        $beltID = $this->getBeltID($beltBarcode);

        $countQuery = $this->getBeltProduct($beltID, $productID);
        //var_dump($countQuery);
        $count = -1;
        if (isset($countQuery['Count'])) {
            $count = $countQuery['Count'];
            error_log("Count $count");
            if ($count > 0) {
                return "Assigned";
            }

        } else {
            error_log(" beltBarcode is $beltBarcode, prdct is $productID");

            if (!$this->verifyShelfProduct($beltBarcode, $productID)) {
                return "Not Allowed Operation";
            }
            $isItAssigned = $this->db->query("SELECT count(start_pallet) as Count,pallet FROM " . DB_PREFIX . "product_to_position WHERE start_pallet= $beltID group by start_pallet");
            if ($isItAssigned->num_rows > 0) {
                return "Assigned to another Product";
            } else {
                return "Not Assigned";
            }

        }
    }
    public function updateStock($barcode, $productID)
    {

        $beltID = $this->getBeltID($barcode);
        error_log("Step 0 Barcode D:  $barcode,$productID");
        $countAvailable = $this->getAvailablePositionsCount($barcode, $productID);
        error_log("Step 1 $countAvailable");

        if ($countAvailable < 1) {
            return -1;
        }
        $update = $this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = quantity+1 WHERE product_id = $productID");
        if ($update) {
            $unitIDQuery = $this->db->query("SELECT unit_id,shelf_id FROM `oc_pallet` WHERE pallet_id = $beltID");
            $unitID = $unitIDQuery->rows[0]['unit_id'];
            $shelfID = $unitIDQuery->rows[0]['shelf_id'];
            $update = $this->db->query("UPDATE `oc_pallet` SET `quantity` = quantity+1 WHERE `oc_pallet`.`pallet_id` = $beltID;");
            //  get beltCount for this product ,
            // get next pallet and also updates its stock
            $beltCount = $this->db->query("SELECT bent_count from oc_product where product_id = $productID")->row['bent_count'];
            for ($i = 1; $i < $beltCount; $i++) {
                $nextBeltID = $this->getNextPalletID($beltID, $i);
                $update = $this->db->query("UPDATE `oc_pallet` SET `quantity` = quantity+1 WHERE `oc_pallet`.`pallet_id` = $nextBeltID;");
            }
            $update = $this->db->query("INSERT INTO `oc_product_to_position` (`position_id`, `product_id`, `shelf_id`, `unit_id`, `start_pallet`, `expiry_date`, `date_added`)
				VALUES (NULL, '$productID', '$shelfID', '$unitID', '$beltID', '2022-04-30', CURRENT_TIMESTAMP);");
            if ($update) {
                return 1;
            }

        }

    }
    public function getBeltAssigned($belID)
    {
        $state = $this->db->query("SELECT * FROM `oc_pallet_product` where start_pallet_id = $belID");
        if (count($state->row) == 1) {
            $status = "Assigned Empty";
            $isitFilled = $this->db->query("SELECT count(*) as Count FROM `oc_product_to_position` WHERE start_pallet = $belID");
            if (isset($isitFilled->row['count']) && $isitFilled->row['count'] > 0) {
                $status = "Assigned Not Empty";
            }

        } else {
            $status = "Not Assigned Empty";
        }

        return $status;

    }

    public function getPrevBeltID($beltID)
    {
        // maxCol is the max column we can choose
        $maxCol = 10;
        $beltInfo = $this->db->query("SELECT shelf_id,x_position,unit_id from oc_pallet where pallet_id = $beltID");
        $row = $beltInfo->row["shelf_id"];
        if ((int) $beltInfo->row["x_position"] < 2) {
            return -1;
        }

        $xPos = (int) $beltInfo->row["x_position"] - 1;
        $unitID = $beltInfo->row["unit_id"];
        $prevBeltID = $this->db->query("SELECT pallet_id FROM `oc_pallet` where shelf_id= $row and x_position= $xPos and unit_id = $unitID")->row['pallet_id'];
        return $prevBeltID;
    }
    private function updateTillEnd($beltID)
    {
        // to check this max execution time
        $nextBeltID = $this->getNextPalletID($beltID, 1);
        $beltStatus = $this->db->query("SELECT position from oc_pallet where pallet_id=$nextBeltID")->row['position'];
        $this->db->query("Update `oc_pallet` set product_id = NULL,position='Single' where pallet_id = $nextBeltID");
        $this->db->query("DELETE from `oc_pallet_product` where start_pallet_id = $nextBeltID");
        error_log("Belt Status is : $beltStatus");
        return $beltStatus;
    }
    private function updateTillFirst($beltID)
    {
        $nextBeltID = $this->getPrevBeltID($beltID, 1);
        error_log("Task 1: started here0 current Belt ID is $beltID while the next is  $nextBeltID");
        $beltStatus = $this->db->query("SELECT position from oc_pallet where pallet_id=$nextBeltID")->row['position'];
        $this->db->query("UPDATE `oc_pallet` set product_id = NULL,position='Single' WHERE pallet_id = $nextBeltID");
        $this->db->query("DELETE from `oc_pallet_product` where start_pallet_id = $nextBeltID");
        return $beltStatus;
    }
    private function updateAdjacentCells($beltID)
    {
        $status = $this->db->query("SELECT position from oc_pallet where pallet_id=$beltID")->row['position'];
        $beltStatus = $status;

        switch ($status) {
            case "Single":
                break;
            case "Start": /// update till the end
                while ($beltStatus != "End") {
                    $beltStatus = $this->updateTillEnd($beltID);
                }
                break;
            case "Middle": /// update the ones before till first and the ones after till the end
                while ($beltStatus != "End") {
                    $beltStatus = $this->updateTillEnd($beltID);
                }

                $beltStatus = $status;
                while ($beltStatus != "Start") {
                    $beltStatus = $this->updateTillFirst($beltID);
                }

                break;
            case "End": // update the ones before till first
                while ($beltStatus != "Start") {
                    $beltStatus = $this->updateTillFirst($beltID);
                }

                break;
        }
    }

	public function getNextPalletID($beltID,$i=1){
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

    public function assignBeltProduct($beltBarcode, $productID, $beltCount, $update)
    {
        error_log("Task 1: started here0 $beltCount update is $update");
        $beltID = $this->getBeltID($beltBarcode);
        $this->updateAdjacentCells($beltID);

        if ($update == 'false') {
            error_log("Task 1 not update: started here 1");
            for ($i = 0; $i < $beltCount; $i++) {
                error_log("Task 1: started here 3");

                $cellPosition = "Single";
                if ($beltCount > 1) {
                    $cellPosition = "Start";
                }

                if ($i > 0) {
                    $beltID = $this->getNextBeltID($beltID, 1);
                    $cellPosition = 1 == $beltCount - $i ? "End" : "Middle";
                }

                $assigned = $this->db->query(
                    "INSERT INTO `oc_pallet_product`
					(`pallet_product_id`, `start_pallet_id`, `product_id`, `bent_count`,
					`position`, `time_created`, `time_modified`, `expiration_date`)
					VALUES (NULL,$beltID, $productID, $beltCount,$i+1, current_timestamp(), current_timestamp(), NULL);"
                );
                $assigned = $this->db->query("Update `oc_pallet` set product_id = $productID,position='$cellPosition' where pallet_id = $beltID");
            }
        } else {
            $prevInfo = $this->db->query("SELECT position,bent_count from oc_pallet_product where start_pallet_id =$beltID");
            $prevPosition = $prevInfo->rows[0]['position'];
            $prevBeltCount = $prevInfo->rows[0]['bent_count'];
            $cellPosition = "Single"; // single or Start or middle or End

            $cellPosition = $beltCount > 0 ? "Start" : "Single";

            $updated = $this->db->query("
							UPDATE `oc_pallet_product` set product_id = $productID,bent_count=$beltCount,position=1
							where start_pallet_id = $beltID
						");
            $updated = $this->db->query("Update `oc_pallet` set product_id = $productID,start = 0 ,position='$cellPosition' where pallet_id = $beltID");
            $tobeDeletedPrevCount = $prevPosition - 1;

            for ($j = 1; $j <= $tobeDeletedPrevCount; $j++) {
                $prevBeltID = $this->getNextPalletID($beltID, $j * -1);

                $deleted = $this->db->query("DELETE FROM oc_pallet_product WHERE start_pallet_id = $prevBeltID");
            }
            $tobeDeletedNextCount = $prevBeltCount - $prevPosition;
            for ($j = 1; $j <= $tobeDeletedNextCount; $j++) {
                $nextBeltID = $this->getNextPalletID($beltID, $j);

                $deleted = $this->db->query("DELETE FROM oc_pallet_product WHERE start_pallet_id = $nextBeltID");
            }

            /// updated cells here
            for ($i = 1; $i < $beltCount; $i++) {

                $cellPosition = "Single"; // single or Start or middle or End
                if ($beltCount > 0) {
                    $cellPosition = "Start";
                }

                if ($i > 0) {
                    $beltID = $this->getNextPalletID($beltID, 1);
                    $cellPosition = 1 == $beltCount - $i ? "End" : "Middle";
                }
                $updated = $this->db->query("
							UPDATE `oc_pallet_product` set product_id = $productID,bent_count=$beltCount,position=$i
							where start_pallet_id = $beltID");
                $updated = $this->db->query("Update `oc_pallet` set product_id = $productID,start = 0 ,position='$cellPosition' where pallet_id = $beltID");
            }

        }

    }
    // no need to be deleted later
    public function assignPalletProduct($beltBarcode, $productID, $beltCount, $update)
    {

        $beltID = $this->getBeltID($beltBarcode);
        $assignable = true;
        if (!$update) {

            for ($i = 0; $i < $beltCount; $i++) {

                $cellPosition = "Single"; // single or Start or middle or End
                if ($beltCount > 0) {
                    $cellPosition = "Start";
                }

                if ($i > 0) {
                    $beltID = $this->getNextPalletID($beltID, 1);
                    $cellPosition = "Middle";
                    if ($i == $beltCount - 1) {
                        $cellPosition = "End";
                    }

                }

                // what about the prev state of the belt/cell in the next line we handle it
                //$this->updateAdjacentCells($beltID);
                $assigned = $this->db->query(
                    "INSERT INTO `oc_pallet_product`
					(`pallet_product_id`, `start_pallet_id`, `product_id`, `bent_count`,
					`position`, `time_created`, `time_modified`, `expiration_date`)
					VALUES (NULL,$beltID, $productID, $beltCount,$i+1, current_timestamp(), current_timestamp(), NULL);"
                );
                $assigned = $this->db->query("Update `oc_pallet` set product_id = $productID,position='$cellPosition' where pallet_id = $beltID");
            }
        } else {

        }

        if ($beltCount > 1) {
            $assignable = false;
            error_log("Here we passed");
            for ($i = 0; $i < $beltCount - 1; $i++) {
                $nextBeltID = $this->getNextPalletID($beltID, $i);
                error_log("Belt ID is $beltID, i is $i, next belt id is $nextBeltID");
                $beltStatus = $this->getBeltAssigned($nextBeltID);
                if ($beltStatus == "Empty" || $beltStatus == "Assigned Empty") {
                    if ($beltStatus == "Assigned Empty") {
                        $this->db->query("DELETE from `oc_pallet_product` where start_pallet_id = $nextBeltID");
                        $this->db->query("Update `oc_pallet` set product_id = $productID where pallet_id = $beltID");
                    }
                    $assignable = true;
                    continue;
                } else if ($beltStatus == "Assigned Not Empty") {
                    $assignable = false;
                    break;
                }
            }
            $assignable = true;
        }
        if ($update == "false" && $assignable) {
            ////// single\ start  middle \\\ end how to define???

            for ($i = 0; $i < $beltCount; $i++) {
                if ($i > 0) { // comment
                    $beltID = $this->getNextPalletID($beltID, 1);
                }
                $assigned = $this->db->query("
					INSERT INTO `oc_pallet_product` (`pallet_product_id`, `start_pallet_id`, `product_id`, `bent_count`, `position`, `time_created`, `time_modified`, `expiration_date`)
					VALUES (NULL,$beltID, $productID, $beltCount,$i+1, current_timestamp(), current_timestamp(), NULL);");
                $assigned = $this->db->query("Update `oc_pallet` set product_id = $productID where pallet_id = $beltID");
            }
        } else {
            $prevInfo = $this->db->query("SELECT position,bent_count from oc_pallet_product where start_pallet_id =$beltID");
            $prevPosition = $prevInfo->rows[0]['position'];
            $prevBeltCount = $prevInfo->rows[0]['bent_count'];
            $updated = $this->db->query("
				UPDATE `oc_pallet_product` set product_id = $productID,bent_count=$beltCount,position=1
				where start_pallet_id = $beltID
			");
            $updated = $this->db->query("Update `oc_pallet` set product_id = $productID,start = 1 where pallet_id = $beltID");
            $tobeDeletedPrevCount = $prevPosition - 1;

            for ($j = 1; $j <= $tobeDeletedPrevCount; $j++) {
                $prevBeltID = $this->getNextPalletID($beltID, $j * -1);

                $deleted = $this->db->query("DELETE FROM oc_pallet_product WHERE start_pallet_id = $prevBeltID");
            }
            $tobeDeletedNextCount = $prevBeltCount - $prevPosition;
            for ($j = 1; $j <= $tobeDeletedNextCount; $j++) {
                $nextBeltID = $this->getNextPalletID($beltID, $j);

                $deleted = $this->db->query("DELETE FROM oc_pallet_product WHERE start_pallet_id = $nextBeltID");
            }
            /// updated cells here
            for ($i = 1; $i < $beltCount; $i++) {

                $beltID = $this->getNextPalletID($beltID, $i);

                error_log("We are here to live in another way: update next cells");
                $updated = $this->db->query("
				UPDATE `oc_pallet_product` set product_id = $productID,bent_count=$beltCount,position=$i
				where start_pallet_id = $beltID");
                $updated = $this->db->query("Update `oc_pallet` set product_id = $productID,start = 0 where pallet_id = $beltID");

                // if update gave no results then create
            }

        }

    }
}
