<?php
namespace Cart;
class Cart {
	private $data = array();

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
	
	public function getUnitInformation($productID,$quantity){
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
			and  optp.product_id = " . (int)$productID . " 
			and optp.status='Ready' ORDER BY ocu.sort_order,os.sort_order,op.x_position  limit 0,".$quantity  ;
			//error_log($positionQueryString);
			//die();
		$position_query = $this->db->query($positionQueryString);
		/*print_r("<BR>Before anything Position Query<BR>");
		print_r($position_query);
		print_r("<BR>END<BR>");*/

		if($quantity == 1){
			$xPos = $position_query->row['xPos'];
			$yPos = $position_query->row['shelf_physical_row'];
			$direction =  $position_query->row['direction'];
			$unitID = $position_query->row['unitID'];
			$unitSortOrder  = $position_query->row['unit_sort_order'];
			$shelfSortOrder = $position_query->row['shelf_sort_order'];
			$beltSortOrder  = $position_query->row['belt_sort_order'];
		}

		else if($quantity > 1){

			$xPos = array();
			$yPos = array();
			$direction = array();
			$unitID = array();
			$unitSortOrder = array();
			$shelfSortOrder = array();
			$beltSortOrder = array();
			foreach($position_query->rows as $product){
				$xPos[] = $product['xPos'];/// Null ??
				$yPos[] = $product['yPos'];/// Null ??
				$direction[] =  $product['direction'];/// Null ??
				$unitID[] =  $product['unitID'];/// Null ??
				$unitSortOrder[]  = $position_query->row['unit_sort_order'];
				$shelfSortOrder[] = $position_query->row['shelf_sort_order'];
				$beltSortOrder [] = $position_query->row['belt_sort_order'];

			}
		}
		return [$xPos,$yPos,$direction,$unitID,$unitSortOrder,$shelfSortOrder,$beltSortOrder];
	}

	public static function position_compare($position1,$position2){
		// compare by unit_sort_order, shelf_sort_order, belt_sort_order
		if($position1['unitSortOrder']< $position2['unitSortOrder']){
			print_r("<BR>first<BR>");
			return -1;
		}
		elseif($position1['unitSortOrder'] > $position2['unitSortOrder']){
			print_r("<BR>second<BR>");
			return 1;
		}

		if($position1['shelfSortOrder'] < $position2['shelfSortOrder'])
		{
			print_r("<BR>third<BR>");
			return -1;
		}
		elseif($position1['shelfSortOrder'] > $position2['shelfSortOrder']){
			print_r("<BR>fourth<BR>");
			return 1;
		}
		if($position1['beltSortOrder'] < $position2['beltSortOrder'])
		{
			print_r("<BR>fifth<BR>");
			return -1;
		}
		else
		{
			print_r("<BR>sixth<BR>");
			return 1;
		}			
	}
	
	public function getOrderForPLC(){
		$product_data = array();
		$cart_query = $this->db->query("
			SELECT * FROM " . DB_PREFIX . "cart 
			WHERE api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' 
			AND customer_id = '" . (int)$this->customer->getId() . "' 
			AND session_id = '" . $this->db->escape($this->session->getId()) 
		. "'");
		print_r("<BR>Before anything Cart Query<BR>");
		print_r($cart_query);
		print_r("<BR>END<BR>");
		foreach ($cart_query->rows as $cart) {

			$stock = true;

			$product_query = $this->db->query("
				SELECT * FROM " . DB_PREFIX . "product_to_store p2s 
				LEFT JOIN " . DB_PREFIX . "product p 
				ON (p2s.product_id = p.product_id) 
				LEFT JOIN " . DB_PREFIX . "product_description pd 
				ON (p.product_id = pd.product_id) 
				WHERE p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
				AND p2s.product_id = '" . (int)$cart['product_id'] . "' 
				AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
				AND p.date_available <= NOW() AND p.status = '1'
			");	
			if ($product_query->num_rows && ($cart['quantity'] > 0)) {
				$price = $product_query->row['price'];

				// Product Discounts
				$discount_quantity = 0;

				foreach ($cart_query->rows as $cart_2) {
					if ($cart_2['product_id'] == $cart['product_id']) {
						$discount_quantity += $cart_2['quantity'];
					}
				}

				$product_discount_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$cart['product_id'] . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity <= '" . (int)$discount_quantity . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");

				if ($product_discount_query->num_rows) {
					$price = $product_discount_query->row['price'];
				}

				// Product Specials
				$product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$cart['product_id'] . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");

				if ($product_special_query->num_rows) {
					$price = $product_special_query->row['price'];
				}

				// Reward Points
				$product_reward_query = $this->db->query("SELECT points FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$cart['product_id'] . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'");

				if ($product_reward_query->num_rows) {
					$reward = $product_reward_query->row['points'];
				} else {
					$reward = 0;
				}	
				// Stock
				if (!$product_query->row['quantity'] || ($product_query->row['quantity'] < $cart['quantity'])) {
					$stock = false;
				}	
				$option_weight = 0;

				$option_price = 0;
				$recurring = false;	
				$unitInformation = $this->getUnitInformation($cart['product_id'],$cart['quantity']);
				print_r("<BR>UNIT INFO");
				print_r($unitInformation);
				print_r("<BR>ENDS<BR>");
				$product_data[] = array(
					'cart_id'         => $cart['cart_id'],
					'bent_count'      => $product_query->row['bent_count'],
					'xPos'            => $unitInformation[0],//// maybe we have multiple xPos
					'yPos'            => $unitInformation[1],/// maybe we have multiple yPos
					'direction'       => $unitInformation[2],
					'unit_sort_order'  => $unitInformation[4],
					'shelf_sort_order' => $unitInformation[5],
					'belt_sort_order'  => $unitInformation[6],
					'unit_id'         => $unitInformation[3],
					'product_id'      => $product_query->row['product_id'],
					'name'            => $product_query->row['name'],
					'model'           => $product_query->row['model'],
					'shipping'        => $product_query->row['shipping'],
					'image'           => $product_query->row['image'],
					'quantity'        => $cart['quantity'],
					'minimum'         => $product_query->row['minimum'],
					'subtract'        => $product_query->row['subtract'],
					'stock'           => $stock,
					'price'           => ($price + $option_price),
					'total'           => ($price + $option_price) * $cart['quantity'],
					'reward'          => $reward * $cart['quantity'],
					'points'          => ($product_query->row['points'] ? ($product_query->row['points'] + $option_points) * $cart['quantity'] : 0),
					'tax_class_id'    => $product_query->row['tax_class_id'],
					'weight'          => ($product_query->row['weight'] + $option_weight) * $cart['quantity'],
					'weight_class_id' => $product_query->row['weight_class_id'],
					'length'          => $product_query->row['length'],
					'width'           => $product_query->row['width'],
					'height'          => $product_query->row['height'],
					'length_class_id' => $product_query->row['length_class_id'],
					'recurring'       => $recurring
				);
			} else {

				$this->remove($cart['cart_id']);
			}					
					
		}
		// we can sort and add three additional 
		//
		$jsonProducts = [];
		$productsCount = 0;
		foreach ($product_data as $product) {

			$productsCount += $product['quantity'];
			if ($product['quantity'] > 1) {

				for ($i = 0; $i < (int) $product['quantity']; $i++) {

					$currentArary = array();
					$currentArary['name'] = $product['name'];
					$currentArary['quantity'] = 1;
					$currentArary['xPos'] = $product['xPos'][$i];
					$currentArary['yPos'] = $product['yPos'][$i];
					$currentArary['unitID'] = $product['unit_id'][$i]; /// check this
					$currentArary['direction'] = $product['direction'][$i]; /// check this
					$currentArary['bentCount'] = $product['bent_count'];
					$currentArary['price'] = $product['price'];
					$currentArary['unitSortOrder'] = $product['unit_sort_order'][$i];
					$currentArary['shelfSortOrder'] = $product['shelf_sort_order'][$i];
					$currentArary['beltSortOrder'] = $product['belt_sort_order'][$i];

					$jsonProducts[] = $currentArary;
				}
			} else {
				$currentArary = array();
				$currentArary['name'] = $product['name'];
				$currentArary['quantity'] = $product['quantity'];
				$currentArary['xPos'] = $product['xPos'];
				$currentArary['yPos'] = $product['yPos'];
				$currentArary['unitID'] = $product['unit_id']; /// check this
				$currentArary['direction'] = $product['direction']; /// check this
				$currentArary['bentCount'] = $product['bent_count'];
				$currentArary['price'] = $product['price'];
				$currentArary['unitSortOrder'] = $product['unit_sort_order'];
				$currentArary['shelfSortOrder'] = $product['shelf_sort_order'];
				$currentArary['beltSortOrder'] = $product['belt_sort_order'];
				$jsonProducts[] = $currentArary;

			}
		}
		usort($jsonProducts, array($this, "position_compare"));
		$products = $this->getActiveBelts($jsonProducts);

		$order = array(
			'OrderID' => $this->session->data['order_id'],
			'ProductsCount' => $productsCount,
			'Products' => $products,
			'OrderStatus' => 'waiting',
		);
		// to add activeBelt, 

		return $order;

	}

	public function getActiveBelts($products){
		return $products;
	}

	public function getProducts() {

		$product_data = array();

		$cart_query = $this->db->query("
			SELECT * FROM " . DB_PREFIX . "cart 
			WHERE api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' 
			AND customer_id = '" . (int)$this->customer->getId() . "' 
			AND session_id = '" . $this->db->escape($this->session->getId()) 
		. "'");

		foreach ($cart_query->rows as $cart) {

			$stock = true;

			$product_query = $this->db->query("
				SELECT * FROM " . DB_PREFIX . "product_to_store p2s 
				LEFT JOIN " . DB_PREFIX . "product p 
				ON (p2s.product_id = p.product_id) 
				LEFT JOIN " . DB_PREFIX . "product_description pd 
				ON (p.product_id = pd.product_id) 
				WHERE p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
				AND p2s.product_id = '" . (int)$cart['product_id'] . "' 
				AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
				AND p.date_available <= NOW() AND p.status = '1'
			");

			if ($product_query->num_rows && ($cart['quantity'] > 0)) {
				$option_price = 0;
				$option_points = 0;
				$option_weight = 0;

				$option_data = array();

				foreach (json_decode($cart['option']) as $product_option_id => $value) {
					$option_query = $this->db->query("
						SELECT po.product_option_id, po.option_id, od.name, o.type 
						FROM " . DB_PREFIX . "product_option po 
						LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) 
						LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) 
						WHERE po.product_option_id = '" . (int)$product_option_id . "' 
						AND po.product_id = '" . (int)$cart['product_id'] . "' 
						AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

					if ($option_query->num_rows) {
						if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'radio') {
							$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$value . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

							if ($option_value_query->num_rows) {
								if ($option_value_query->row['price_prefix'] == '+') {
									$option_price += $option_value_query->row['price'];
								} elseif ($option_value_query->row['price_prefix'] == '-') {
									$option_price -= $option_value_query->row['price'];
								}

								if ($option_value_query->row['points_prefix'] == '+') {
									$option_points += $option_value_query->row['points'];
								} elseif ($option_value_query->row['points_prefix'] == '-') {
									$option_points -= $option_value_query->row['points'];
								}

								if ($option_value_query->row['weight_prefix'] == '+') {
									$option_weight += $option_value_query->row['weight'];
								} elseif ($option_value_query->row['weight_prefix'] == '-') {
									$option_weight -= $option_value_query->row['weight'];
								}

								if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $cart['quantity']))) {
									$stock = false;
								}

								$option_data[] = array(
									'product_option_id'       => $product_option_id,
									'product_option_value_id' => $value,
									'option_id'               => $option_query->row['option_id'],
									'option_value_id'         => $option_value_query->row['option_value_id'],
									'name'                    => $option_query->row['name'],
									'value'                   => $option_value_query->row['name'],
									'type'                    => $option_query->row['type'],
									'quantity'                => $option_value_query->row['quantity'],
									'subtract'                => $option_value_query->row['subtract'],
									'price'                   => $option_value_query->row['price'],
									'price_prefix'            => $option_value_query->row['price_prefix'],
									'points'                  => $option_value_query->row['points'],
									'points_prefix'           => $option_value_query->row['points_prefix'],
									'weight'                  => $option_value_query->row['weight'],
									'weight_prefix'           => $option_value_query->row['weight_prefix']
								);
							}
						} elseif ($option_query->row['type'] == 'checkbox' && is_array($value)) {

							foreach ($value as $product_option_value_id) {

								$option_value_query = $this->db->query("SELECT pov.option_value_id, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix, ovd.name FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (pov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

								if ($option_value_query->num_rows) {
									if ($option_value_query->row['price_prefix'] == '+') {
										$option_price += $option_value_query->row['price'];
									} elseif ($option_value_query->row['price_prefix'] == '-') {
										$option_price -= $option_value_query->row['price'];
									}

									if ($option_value_query->row['points_prefix'] == '+') {
										$option_points += $option_value_query->row['points'];
									} elseif ($option_value_query->row['points_prefix'] == '-') {
										$option_points -= $option_value_query->row['points'];
									}

									if ($option_value_query->row['weight_prefix'] == '+') {
										$option_weight += $option_value_query->row['weight'];
									} elseif ($option_value_query->row['weight_prefix'] == '-') {
										$option_weight -= $option_value_query->row['weight'];
									}

									if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $cart['quantity']))) {
										$stock = false;
									}

									$option_data[] = array(
										'product_option_id'       => $product_option_id,
										'product_option_value_id' => $product_option_value_id,
										'option_id'               => $option_query->row['option_id'],
										'option_value_id'         => $option_value_query->row['option_value_id'],
										'name'                    => $option_query->row['name'],
										'value'                   => $option_value_query->row['name'],
										'type'                    => $option_query->row['type'],
										'quantity'                => $option_value_query->row['quantity'],
										'subtract'                => $option_value_query->row['subtract'],
										'price'                   => $option_value_query->row['price'],
										'price_prefix'            => $option_value_query->row['price_prefix'],
										'points'                  => $option_value_query->row['points'],
										'points_prefix'           => $option_value_query->row['points_prefix'],
										'weight'                  => $option_value_query->row['weight'],
										'weight_prefix'           => $option_value_query->row['weight_prefix']
									);
								}
							}
						} elseif ($option_query->row['type'] == 'text' || $option_query->row['type'] == 'textarea' || $option_query->row['type'] == 'file' || $option_query->row['type'] == 'date' || $option_query->row['type'] == 'datetime' || $option_query->row['type'] == 'time') {
							$option_data[] = array(
								'product_option_id'       => $product_option_id,
								'product_option_value_id' => '',
								'option_id'               => $option_query->row['option_id'],
								'option_value_id'         => '',
								'name'                    => $option_query->row['name'],
								'value'                   => $value,
								'type'                    => $option_query->row['type'],
								'quantity'                => '',
								'subtract'                => '',
								'price'                   => '',
								'price_prefix'            => '',
								'points'                  => '',
								'points_prefix'           => '',
								'weight'                  => '',
								'weight_prefix'           => ''
							);
						}
					}
				}

				$price = $product_query->row['price'];

				// Product Discounts
				$discount_quantity = 0;

				foreach ($cart_query->rows as $cart_2) {
					if ($cart_2['product_id'] == $cart['product_id']) {
						$discount_quantity += $cart_2['quantity'];
					}
				}

				$product_discount_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$cart['product_id'] . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity <= '" . (int)$discount_quantity . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");

				if ($product_discount_query->num_rows) {
					$price = $product_discount_query->row['price'];
				}

				// Product Specials
				$product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$cart['product_id'] . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");

				if ($product_special_query->num_rows) {
					$price = $product_special_query->row['price'];
				}

				// Reward Points
				$product_reward_query = $this->db->query("SELECT points FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$cart['product_id'] . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'");

				if ($product_reward_query->num_rows) {
					$reward = $product_reward_query->row['points'];
				} else {
					$reward = 0;
				}

				// Downloads
				$download_data = array();

				$download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download p2d LEFT JOIN " . DB_PREFIX . "download d ON (p2d.download_id = d.download_id) LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE p2d.product_id = '" . (int)$cart['product_id'] . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

				foreach ($download_query->rows as $download) {
					$download_data[] = array(
						'download_id' => $download['download_id'],
						'name'        => $download['name'],
						'filename'    => $download['filename'],
						'mask'        => $download['mask']
					);
				}

				// Stock
				if (!$product_query->row['quantity'] || ($product_query->row['quantity'] < $cart['quantity'])) {
					$stock = false;
				}

				$recurring_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recurring r LEFT JOIN " . DB_PREFIX . "product_recurring pr ON (r.recurring_id = pr.recurring_id) LEFT JOIN " . DB_PREFIX . "recurring_description rd ON (r.recurring_id = rd.recurring_id) WHERE r.recurring_id = '" . (int)$cart['recurring_id'] . "' AND pr.product_id = '" . (int)$cart['product_id'] . "' AND rd.language_id = " . (int)$this->config->get('config_language_id') . " AND r.status = 1 AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'");

				if ($recurring_query->num_rows) {
					$recurring = array(
						'recurring_id'    => $cart['recurring_id'],
						'name'            => $recurring_query->row['name'],
						'frequency'       => $recurring_query->row['frequency'],
						'price'           => $recurring_query->row['price'],
						'cycle'           => $recurring_query->row['cycle'],
						'duration'        => $recurring_query->row['duration'],
						'trial'           => $recurring_query->row['trial_status'],
						'trial_frequency' => $recurring_query->row['trial_frequency'],
						'trial_price'     => $recurring_query->row['trial_price'],
						'trial_cycle'     => $recurring_query->row['trial_cycle'],
						'trial_duration'  => $recurring_query->row['trial_duration']
					);
				} else {
					$recurring = false;
				}
				$unitInformation = $this->getUnitInformation($cart['product_id'],$cart['quantity']);
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
					and  optp.product_id = " . (int)$cart['product_id'] . " 
					and optp.status='Ready' limit 0,".$cart['quantity'] ;
					//error_log($positionQueryString);
					//die();
				$position_query = $this->db->query($positionQueryString);

				//print_r($position_query);
				if($cart['quantity'] == 1){
					$xPos = $position_query->row['xPos'];
					$yPos = $position_query->row['shelf_physical_row'];
					$direction =  $position_query->row['direction'];
					$unitID = $position_query->row['unitID'];
					$unitSortOrder  = $position_query->row['unit_sort_order'];
					$shelfSortOrder = $position_query->row['shelf_sort_order'];
					$beltSortOrder  = $position_query->row['belt_sort_order'];
				}

				else if($cart['quantity']> 1){

					$xPos = array();
					$yPos = array();
					$direction = array();
					foreach($position_query->rows as $product){
						$xPos[] = $product['xPos'];/// Null ??
						$yPos[] = $product['yPos'];/// Null ??
						$direction[] =  $product['direction'];/// Null ??
						$unitID[] =  $product['unitID'];/// Null ??
						$unitSortOrder[]  = $position_query->row['unit_sort_order'];
						$shelfSortOrder[] = $position_query->row['shelf_sort_order'];
						$beltSortOrder [] = $position_query->row['belt_sort_order'];
					}
				}

				//// if product out of stock handle
				/// MFH 
				$product_data[] = array(
					'cart_id'         => $cart['cart_id'],
					'bent_count'      => $product_query->row['bent_count'],
					'xPos'            => $unitInformation[0],//// maybe we have multiple xPos
					'yPos'            => $unitInformation[1],/// maybe we have multiple yPos
					'direction'       => $unitInformation[2],
					'unit_id'         => $unitInformation[3],
					'unit_sort_order'  => $unitInformation[4],
					'shelf_sort_order' => $unitInformation[5],
					'belt_sort_order'  => $unitInformation[6],

					'product_id'      => $product_query->row['product_id'],
					'name'            => $product_query->row['name'],
					'model'           => $product_query->row['model'],
					'shipping'        => $product_query->row['shipping'],
					'image'           => $product_query->row['image'],
					'option'          => $option_data,
					'download'        => $download_data,
					'quantity'        => $cart['quantity'],
					'minimum'         => $product_query->row['minimum'],
					'subtract'        => $product_query->row['subtract'],
					'stock'           => $stock,
					'price'           => ($price + $option_price),
					'total'           => ($price + $option_price) * $cart['quantity'],
					'reward'          => $reward * $cart['quantity'],
					'points'          => ($product_query->row['points'] ? ($product_query->row['points'] + $option_points) * $cart['quantity'] : 0),
					'tax_class_id'    => $product_query->row['tax_class_id'],
					'weight'          => ($product_query->row['weight'] + $option_weight) * $cart['quantity'],
					'weight_class_id' => $product_query->row['weight_class_id'],
					'length'          => $product_query->row['length'],
					'width'           => $product_query->row['width'],
					'height'          => $product_query->row['height'],
					'length_class_id' => $product_query->row['length_class_id'],
					'recurring'       => $recurring
				);
			} else {

				$this->remove($cart['cart_id']);
			}
		}

		return $product_data;
	}

	public function add($product_id, $quantity = 1, $option = array(), $recurring_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "cart WHERE api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "' AND product_id = '" . (int)$product_id . "' AND recurring_id = '" . (int)$recurring_id . "' AND `option` = '" . $this->db->escape(json_encode($option)) . "'");

		if (!$query->row['total']) {
			$this->db->query("INSERT " . DB_PREFIX . "cart SET api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "', customer_id = '" . (int)$this->customer->getId() . "', session_id = '" . $this->db->escape($this->session->getId()) . "', product_id = '" . (int)$product_id . "', recurring_id = '" . (int)$recurring_id . "', `option` = '" . $this->db->escape(json_encode($option)) . "', quantity = '" . (int)$quantity . "', date_added = NOW()");
		} else {
			$this->db->query("UPDATE " . DB_PREFIX . "cart SET quantity = (quantity + " . (int)$quantity . ") WHERE api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "' AND product_id = '" . (int)$product_id . "' AND recurring_id = '" . (int)$recurring_id . "' AND `option` = '" . $this->db->escape(json_encode($option)) . "'");
		}
	}

	public function update($cart_id, $quantity) {
		$this->db->query("UPDATE " . DB_PREFIX . "cart SET quantity = '" . (int)$quantity . "' WHERE cart_id = '" . (int)$cart_id . "' AND api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
	}

	public function remove($cart_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE cart_id = '" . (int)$cart_id . "' AND api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
	}

	public function clear() {
		$this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
	}

	public function getRecurringProducts() {
		$product_data = array();

		foreach ($this->getProducts() as $value) {
			if ($value['recurring']) {
				$product_data[] = $value;
			}
		}

		return $product_data;
	}

	public function getWeight() {
		$weight = 0;

		foreach ($this->getProducts() as $product) {
			if ($product['shipping']) {
				$weight += $this->weight->convert($product['weight'], $product['weight_class_id'], $this->config->get('config_weight_class_id'));
			}
		}

		return $weight;
	}

	public function getSubTotal() {
		$total = 0;

		foreach ($this->getProducts() as $product) {
			$total += $product['total'];
		}

		return $total;
	}

	public function getTaxes() {
		$tax_data = array();

		foreach ($this->getProducts() as $product) {
			if ($product['tax_class_id']) {
				$tax_rates = $this->tax->getRates($product['price'], $product['tax_class_id']);

				foreach ($tax_rates as $tax_rate) {
					if (!isset($tax_data[$tax_rate['tax_rate_id']])) {
						$tax_data[$tax_rate['tax_rate_id']] = ($tax_rate['amount'] * $product['quantity']);
					} else {
						$tax_data[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $product['quantity']);
					}
				}
			}
		}

		return $tax_data;
	}

	public function getTotal() {
		$total = 0;

		foreach ($this->getProducts() as $product) {
			$total += $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'];
		}

		return $total;
	}

	public function countProducts() {
		$product_total = 0;

		$products = $this->getProducts();

		foreach ($products as $product) {
			$product_total += $product['quantity'];
		}

		return $product_total;
	}

	public function hasProducts() {
		return count($this->getProducts());
	}

	public function hasRecurringProducts() {
		return count($this->getRecurringProducts());
	}

	public function hasStock() {
		foreach ($this->getProducts() as $product) {
			if (!$product['stock']) {
				return false;
			}
		}

		return true;
	}

	public function hasShipping() {
		foreach ($this->getProducts() as $product) {
			if ($product['shipping']) {
				return true;
			}
		}

		return false;
	}

	public function hasDownload() {
		foreach ($this->getProducts() as $product) {
			if ($product['download']) {
				return true;
			}
		}

		return false;
	}
}
