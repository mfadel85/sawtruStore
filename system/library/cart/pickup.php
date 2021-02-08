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

	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->customer = $registry->get('customer');
		$this->session = $registry->get('session');
		$this->db = $registry->get('db');
		$this->tax = $registry->get('tax');
		$this->weight = $registry->get('weight');

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
    public function start(){
        $order = $this->getOrder();
        print_r("before<br>");

        foreach ($order as $value) {
            print_r("<br>");
            print_r($value);
            print_r("<br>");
        }
        print_r("after<br>");
        usort($order,array($this,"sorterMain"));
        foreach ($order as $value) {
            print_r("<br>");
            print_r($value);
            print_r("<br>");
        }


    }

    private function algorithm1(){

    }


    
    
    function sorterMain($a,$b){
        if(($a["belt_count"] < 4 && $b["belt_count"] < 4) || $a["belt_count"] != $b["belt_count"]){
            return $a["unit_sort_order"] - $b["unit_sort_order"];
        } else {
                return $a["belt_count"]-$b["belt_count"];
        }
    }
    private function sortOrderSec($order){

    } 
    private function calculatTime($order){

    }
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
		/*$query = $this->db->query("UPDATE oc_order_product 
					SET betlID =". (int)$position_query->row['pallet_id']
					." where product_id=" . (int) $productID . " and order_id=".(int)$orderID);*/
		// add pallet_id for oc_product_order update set pallet_id

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
            if ($product['quantity'] == 1) {
                $xPos = $positions->row['xPos'];
                $yPos = $positions->row['shelf_physical_row'];
                $direction = $positions->row['direction'];
                $unitID = $positions->row['unitID'];
                $unitSortOrder = $positions->row['unit_sort_order'];
                $shelfSortOrder = $positions->row['shelf_sort_order'];
                $beltSortOrder = $positions->row['belt_sort_order'];
            } else if ($cart['quantity'] > 1) {

                $xPos = array();
                $yPos = array();
                $direction = array();
                foreach ($positions->rows as $aProduct) {
                    $xPos[] = $aProduct['xPos']; /// Null ??
                    $yPos[] = $aProduct['yPos']; /// Null ??
                    $direction[] = $aProduct['direction']; /// Null ??
                    $unitID[] = $product['unitID']; /// Null ??
                    $unitSortOrder[] = $aProduct['unit_sort_order'];
                    $shelfSortOrder[] = $aProduct['shelf_sort_order'];
                    $beltSortOrder[] = $aProduct['belt_sort_order'];
                }
            }
			$products[] = array(
					'cart_id'         => $product['cart_id'],
					'belt_count'      => $productQuery->row['bent_count'],
					'xPos'            => $unitInformation[0],//// maybe we have multiple xPos
					'yPos'            => $unitInformation[1],/// maybe we have multiple yPos
					'direction'       => $unitInformation[2],
					'unit_id'         => $unitInformation[3],
					'unit_sort_order'  => $unitInformation[4],
					'shelf_sort_order' => $unitInformation[5],
					'belt_sort_order'  => $unitInformation[6],

					'product_id'      => $productQuery->row['product_id'],
					'name'            => $productQuery->row['name'],
					'model'           => $productQuery->row['model'],
					'quantity'        => $productQuery->row['quantity'],
					'total'           => ($price ) * $product['quantity'],
					'weight'          => ($productQuery->row['weight'] ) * $product['quantity'],
					'length'          => $productQuery->row['length'],
					'width'           => $productQuery->row['width'],
            );
        }
		 
		return $products;
    }
    private function algorithm2(){

    }
    private function shiftCells(){

    }
}