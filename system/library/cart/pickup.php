<?php
namespace Cart;
/**
 * This class is for picking products up algorithm from the shelves int
 * the robot.
 * 
 */
class Pickup {
    private $data = array();
    private $cells = array();
    private $timing;
    private $fillingPercentage;
	private $nextPatch  = array();
	private $beltCount = 5;
	private $rowCount  = 22;
	private $cellDepth = 2.5;
	private $order = array();

	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->customer = $registry->get('customer');
		$this->session = $registry->get('session');
		$this->db = $registry->get('db');
		$this->tax = $registry->get('tax');
		$this->weight = $registry->get('weight');
		$this->initCells();
		// Remove all the expired carts with no customer ID
		$this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE (api_id > '0' OR customer_id = '0') AND date_added < DATE_SUB(NOW(), INTERVAL 1 HOUR)");

		if ($this->customer->getId()) {
			// We want to change the session ID on all the old items in the customers cart
			$this->db->query("UPDATE " . DB_PREFIX . "cart SET session_id = '" . $this->db->escape($this->session->getId()) . "' WHERE api_id = '0' AND customer_id = '" . (int)$this->customer->getId() . "'");

			// Once the customer is logged in we want to update the customers cart
			$cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE api_id = '0' AND customer_id = '0' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");

			foreach ($cart_query->rows as $cart) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE cart_id = '" . (int)$cart['cart_id'] . "'");

				// The advantage of using $this->add is that it will check if the products already exist and increaser the quantity if necessary.
				$this->add($cart['product_id'], $cart['quantity'], json_decode($cart['option']), $cart['recurring_id']);
			}
		}
	}
	private function initCells(){
		for($i=0; $i < $this->rowCount; $i++){
			$this->cells[] = [];
			for($j=0; $j< $this->beltCount; $j++)
			   $this->cells[$i][] = '';
		}
	}
    public function start(){
		$order = $this->getOrder();
		print_r("<BR> Order Contents:<BR>");
		foreach($order as $product){
			print_r("<BR> PRODUCT<BR>");
			print_r($product);
			print_r("<br>");
		}
		print_r("Order Contents finished<br>");
		usort($order,array($this,"sorterMain"));
		print_r("<BR> Order Contents Sorted:<BR>");
		foreach($order as $product){
			print_r("<BR> PRODUCT<BR>");
			print_r($product);
			print_r("<br>");
		}
		print_r("Order Contents Sorted finished<br>");

		$this->order = $order;
		$time = $this->calculatTime($order);
		$this->fillShelf($order);// fillshelf is no a good name :D
		print_r("Timing is $time.<br>.");
		foreach($this->cells as $line){
		print_r("<br>");
		print_r($line);
		}
    }

    private function algorithm1(){

    }

    private function algorithm2(){

    }

   private  function sorterMain($a,$b){
	   /// what about shelf sort order? should be included here I guess yes
        if(($a["belt_count"] < 4 && $b["belt_count"] < 4) || $a["belt_count"] != $b["belt_count"]){
            return $a["unit_sort_order"] - $b["unit_sort_order"];
        } else {
            return $a["belt_count"]-$b["belt_count"];
        }
	}
	
    private function sortOrderSec($order){
	} 

	private function fillShelf($order){
		$unPickedProducts = [];
		$index = 0;
		foreach ($order as $product) {
			$index = $this->pickProduct($product,$index); // check the output of this function!!
			print_r("this $index<BR>");

			if($index == -1){
				// update timing and 
				// add this product to the not added products
				// 
				$unPickedProducts[] = $product;
			}
		}
		print_r("<BR>UNPICKED PRODUCTS<BR>");
		print_r($unPickedProducts);
		print_r("<BR>UNPICKED PRODUCTS Finished<BR>");
	}

	private function getStartIndex($index,$product){
		$cellCount = ceil((float)$product['width']/2.5);
		print_r("Cell Count is $cellCount<BR> ");
		switch ($product['belt_count']) {
			case '4':
			case '5':
				return 0;
			break;
			case '3':
				return 2;
			break;
			case '2':
				return $this->getTwoBeltIndex($index,$cellCount);
			break;
			case '1':
				return $this->getOneBeltIndex($index,$cellCount);
			break;
			default:
				print_r("O lan var ya");
			break;
		}

	}

	private function getBeltDepth($n){
		$index = 0;
		for($i = 21;$i>=0;$i--){

			if($this->cells[$i][$n] != '')
				$index = $i +1;
			break;
		}
		return $index;
	}

	private function nBeltProductDepth($n){
		$depth = 0;
		foreach($this->order as $product){
			if($product['belt_count'] == $n)
				$depth += ceil($product['width']/$this->cellDepth);
		}
		return $depth;
	}

	private function getOneBeltIndex($cellCount){
		$firstBeltIndex  =  $this->getBeltDepth(0);
		$secondBeltIndex = $this->getBeltDepth(1);
		$fifthBeltIndex   = $this->getBeltDepth(4);
		$threeDepth = $this->nBeltProductDepth(3);
		$fourDepth  = $this->nBeltProductDepth(4);
		$arrayValues = array($firstBeltIndex, $secondBeltIndex, 1000, 1000, $fifthBeltIndex);
		$index = array_search(min($arrayValues), $arrayValues);
		if($fifthBeltIndex + $fourDepth + $threeDepth + $cellCount <= 22)
			return 4;
		return $index;
	}
	private function getTwoBeltIndex($index,$cellCount){
		$firstBeltIndex  =  $this->getBeltDepth(0);
		$thirdBeltIndex   = $this->getBeltDepth(2);
		$threeDepth = $this->nBeltProductDepth(3);
		$fourDepth  = $this->nBeltProductDepth(4);
		/*if ($this->orderSize > 110) {
			$fourDepth = 0;
			$threeDepth = 0;
		}*/
		print_r("FBI: $firstBeltIndex TBI: $thirdBeltIndex Depth:$threeDepth<BR>");
		return $firstBeltIndex > $thirdBeltIndex + $threeDepth ? 2 : 0;
		return 2;
	}
	/*
	checkSpace(startIndex, beltCount, cellsDepth) {
		let startRow = this.state.cellsInBent - cellsDepth;
		for (let i = startRow; i < this.state.cellsInBent; i++) {
			for (let l = 0; l < beltCount; l++) {
				const index = i * 5 + startIndex + l;
				if (this.state.cells[index] !== null)
					return false;
			}
		}
		return true;
	}
	*/
	private function checkSpace($index,$product){
		$startRow = $this->rowCount-ceil((float)$product['width']/$this->cellDepth);
		for($i=$startRow; $i< $this->rowCount; $i++){
			for($j=0;$j< $product['belt_count'];$j++){
				$cellIndex = $i*5+$j;
				if($this->cells[$i][$j+$index]!= '')
					return false;
			}
		}
		print_r("<BR>there is space in row $startRow, index $index<br>");
		return true;
	}
	private function shiftCells($index,$product){
		$cells = $this->cells;
		$count = 0;
		$beltCount = $product['belt_count'];
		if($beltCount > 3)
			$beltCount = 5;
		$cellsDepth = ceil((float)$product['width']/$this->cellDepth);
		if($product['direction'] == "Left"){
			for($i=0; $i< $cellsDepth; $i++)
				for($j=$this->rowCount-1;$j>0 ; $j--)
					for($k=0;$k< $beltCount;$k++)
					{
						$cIndex = $index+$k;// to be completed and tested not completed yet
						$this->cells[$j][$cIndex] =$this->cells[$j-1][$cIndex];
						$this->cells[$j-1][$cIndex] = $product['name'];
					}
		}
		else if($product['direction'] == "Right"){
			//go till the first empty cell in all the contained belts

		}
	}
	private function pickProduct($product,$index){
		$originalIndex = $index; // why are we making the original index  alawys?
		$index = $this->getStartIndex($index,$product);
		print_r("Index now is $index");
		$available = $this->checkSpace($index,$product);
		if($available){
			$index =  $this->shiftCells($index,$product);/// filledCount also
		}
		else {
			// maybe play with timing
			return -1;
		}

		return $index;
	}
    private function calculatTime($order){
		$pickUpTime = 3;
		$time = 0;
		$prevUnit = 1;
		foreach ($order as $product) {
			$moveTime = $product['unit_sort_order'] - $prevUnit > 0 ? ($product['unit_sort_order'] - $prevUnit)*3 :1;
			$time += $pickUpTime + $moveTime;// 3 sec time to pick up the product from the unit!!!
			print_r("<BR> It takes $moveTime sec to pickup this product!!<br>");
			$prevUnit = $product['unit_sort_order'];
		}
		return $time;
    }

	// those functions should be extracted to an outside class
	private function getUnitInformation($productID,$quantity){
		$positionQueryString = 
		"SELECT
			pallet_id,
			shelf_physical_row,
			optp.product_id,
			optp.shelf_id,
			optp.unit_id as unitID,
			ocu.direction as direction,
			optp.start_pallet,
			op.x_position as xPos ,
			os.shelf_physical_row as yPos,
			os.sort_order as shelf_sort_order,
			ocu.sort_order as unit_sort_order,
			op.sort_order as belt_sort_order
		FROM
            `oc_product_to_position` optp  join
            oc_pallet op on optp.start_pallet = op.pallet_id  join
            oc_shelf
            os on os.shelf_id = op.shelf_id join
            oc_unit ocu on ocu.unit_id = os.unit_id
		WHERE
            op.status=1 and
             optp.product_id = " . (int) $productID . " and
             optp.status='Ready'
        ORDER BY
            ocu.sort_order,
            os.sort_order,
            op.x_position
			limit 0," . $quantity;
		//print_r($this);
		//$orderID = $_SESSION['order_id'];
		$position_query = $this->db->query($positionQueryString);

		if($quantity == 1){
			$xPos = $position_query->row['xPos'];
			$yPos = $position_query->row['shelf_physical_row'];
			$direction =  $position_query->row['direction'];
			$unitID = $position_query->row['unitID'];
			$unitSortOrder  = $position_query->row['unit_sort_order'];
			$shelfSortOrder = $position_query->row['shelf_sort_order'];
			$beltSortOrder  = $position_query->row['belt_sort_order'];
			$beltID = $position_query->row['pallet_id'];
		}
		else if($quantity > 1){
			$xPos = array();
			$yPos = array();
			$direction = array();
			$unitID = array();
			$unitSortOrder = array();
			$shelfSortOrder = array();
			$beltSortOrder = array();
			$beltID = array();
			foreach($position_query->rows as $product){
				$xPos[] = $product['xPos'];/// Null ??
				$yPos[] = $product['yPos'];/// Null ??
				$direction[] =  $product['direction'];/// Null ??
				$unitID[] =  $product['unitID'];/// Null ??
				$unitSortOrder[]  = $position_query->row['unit_sort_order'];
				$shelfSortOrder[] = $position_query->row['shelf_sort_order'];
				$beltSortOrder [] = $position_query->row['belt_sort_order'];
				$beltID[] = $position_query->row['pallet_id'];
			}
		}
		return [$xPos,$yPos,$direction,$unitID,$unitSortOrder,$shelfSortOrder,$beltSortOrder,$beltID];
	}   
	
	

    public  function getOrder(){ 
        $products = array();
        $cartContent = $this->db->query("
			SELECT * FROM " . DB_PREFIX . "cart 
			WHERE api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' 
			AND customer_id = '" . (int)$this->customer->getId() . "' 
			AND session_id = '" . $this->db->escape($this->session->getId()) 
        . "'");


        foreach($cartContent->rows as $product){
            $stock = true;
            $productQuery = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_store p2s 
				LEFT JOIN " . DB_PREFIX . "product p 
				ON (p2s.product_id = p.product_id) 
				LEFT JOIN " . DB_PREFIX . "product_description pd 
				ON (p.product_id = pd.product_id) 
				WHERE p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
				AND p2s.product_id = '" . (int)$product['product_id'] . "' 
				AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
				AND p.date_available <= NOW() AND p.status = '1'
            ");

			if ($productQuery->num_rows && ($product['quantity'] > 0)) {

            }            
            // Stock
            if (!$productQuery->row['quantity'] || ($productQuery->row['quantity'] < $product['quantity'])) {
                $stock = false;
            }
            $price = $productQuery->row['price'];

            // unit info
            $unitInformation = $this->getUnitInformation($product['product_id'], $product['quantity']);
            /*print_r("Unit INfo<br>");
            print_r($unitInformation);
            print_r("<br>Unit INfo<br>");*/

            $positionQueryString = "
				SELECT
					shelf_physical_row,
					optp.product_id,
					optp.shelf_id,
					optp.unit_id as unitID,
					ocu.direction as direction,
					optp.start_pallet,
					op.x_position as xPos ,
					os.shelf_physical_row as yPos,
					os.sort_order as shelf_sort_order,
					ocu.sort_order as unit_sort_order,
					op.sort_order as belt_sort_order
					 FROM `oc_product_to_position` optp
					join oc_pallet op on optp.start_pallet = op.pallet_id
					join oc_shelf os on os.shelf_id = op.shelf_id
					join oc_unit ocu on ocu.unit_id = os.unit_id
					WHERE op.status=1
					and  optp.product_id = " . (int) $product['product_id'] . "
                    and optp.status='Ready' limit 0," . $product['quantity'];
            $positions = $this->db->query($positionQueryString);
           /* if ($product['quantity'] == 1) {
                $xPos = $positions->row['xPos'];
                $yPos = $positions->row['shelf_physical_row'];
                $direction = $positions->row['direction'];
                $unitID = $positions->row['unitID'];
                $unitSortOrder = $positions->row['unit_sort_order'];
                $shelfSortOrder = $positions->row['shelf_sort_order'];
                $beltSortOrder = $positions->row['belt_sort_order'];
            } else if ($product['quantity'] > 1) {*/

                $xPos = array();
                $yPos = array();
                $direction = array();
                foreach ($positions->rows as $aProduct) {
                    $xPos[] = $aProduct['xPos']; /// Null ??
                    $yPos[] = $aProduct['yPos']; /// Null ??
                    $direction[] = $aProduct['direction']; /// Null ??
                    $unitID[] = $aProduct['unitID']; /// what is hapenning here? let's debug
                    $unitSortOrder[] = $aProduct['unit_sort_order'];
                    $shelfSortOrder[] = $aProduct['shelf_sort_order'];
					$beltSortOrder[] = $aProduct['belt_sort_order'];
					$products[] = array(
					'cart_id'         => $product['cart_id'],
					'belt_count'      => $productQuery->row['bent_count'],
					'xPos'            => $aProduct['xPos'],//// maybe we have multiple xPos
					'yPos'            => $aProduct['yPos'],/// maybe we have multiple yPos
					'direction'       => $aProduct['direction'],
					'unit_id'         => $aProduct['unitID'],
					'unit_sort_order'  => $aProduct['unit_sort_order'],
					'shelf_sort_order' => $aProduct['shelf_sort_order'],
					'belt_sort_order'  => $aProduct['belt_sort_order'],

					'product_id'      => $productQuery->row['product_id'],
					'name'            => $productQuery->row['name'],
					'model'           => $productQuery->row['model'],
					'quantity'        => $product['quantity'],// not this the quantity in the order
					'total'           => ($price ) * $product['quantity'],
					'weight'          => ($productQuery->row['weight'] ) * $product['quantity'],
					'length'          => $productQuery->row['length'],
					'width'           => $productQuery->row['width'],
            );					
                }
            //}
			
        }
		 
		return $products;
    }


}