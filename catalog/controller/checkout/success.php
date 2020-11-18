<?php
class ControllerCheckoutSuccess extends Controller {
	public function index() {
		error_log("Success debugging started");

		$this->load->language('checkout/success');
		$total = $this->cart->getTotal();
		if (isset($this->session->data['order_id'])) {
		    $products = $this->cart->getProducts();
		    $jsonProducts = [];
		    $productsCount = 0;
		    foreach ($products as $product) {

				$productsCount += $product['quantity'];
				if($product['quantity']>1){

					for($i=0;$i<(int)$product['quantity'];$i++){

						$currentArary = array();
						$currentArary['name'] = $product['name'];
						$currentArary['quantity'] = 1;
						$currentArary['xPos'] = $product['xPos'][$i];
						$currentArary['yPos'] = $product['yPos'][$i];
						$currentArary['unitID'] = $product['unit_id'];/// check this
						$currentArary['direction'] = $product['direction'][$i];/// check this
						$currentArary['bentCount'] = $product['bent_count']; 
						$currentArary['price']     = $product['price']; 

						$jsonProducts[] = $currentArary;
					}
				}	
				else {

					$currentArary = array();
					$currentArary['name'] = $product['name'];
					$currentArary['quantity'] = $product['quantity'];
					$currentArary['xPos'] = $product['xPos'];
					$currentArary['yPos'] = $product['yPos'];
					$currentArary['unitID'] = $product['unit_id'];/// check this
					$currentArary['direction'] = $product['direction'];/// check this
					$currentArary['bentCount'] = $product['bent_count']; 
					$currentArary['price'] = $product['price']; 

					$jsonProducts[] = $currentArary;

				}			
			}

			$order = array(
				'OrderID'       => $this->session->data['order_id'],
				'ProductsCount' => $productsCount,
				'Products'      => $jsonProducts,
				'OrderStatus'   => 'waiting',
				'total'         => $total
			);
			print_r($order);
			$json_data = json_encode($order);// path need to be changed
			$this->cart->clear();

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['guest']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);
			unset($this->session->data['totals']);
		}

		$this->document->setTitle($this->language->get('heading_title'));
		error_log("ZXX Step 7");

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_basket'),
			'href' => $this->url->link('checkout/cart')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_checkout'),
			'href' => $this->url->link('checkout/checkout', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_success'),
			'href' => $this->url->link('checkout/success')
		);

		if ($this->customer->isLogged()) {
			$data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', true), $this->url->link('account/order', '', true), $this->url->link('account/download', '', true), $this->url->link('information/contact'));
		} else {
			$data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
		}

		$data['continue'] = $this->url->link('common/home');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		try {
			$address="192.168.1.108";
			$address=CONNECTORIP;
			$port="11111";
			$sock=socket_create(AF_INET,SOCK_STREAM,0) or die("Cannot create a socket");
			if(!socket_connect($sock,$address,$port)){
				$this->load->model("checkout/order");
				$this->model_checkout_order->addOrderHistory($order['OrderID'], 17);

				print_r("stuck here!!!!");
			}
			else {
				socket_write($sock,$json_data);
				$read=socket_read($sock,3072);
				$data['result'] = $read;
				print_r("<BR>Main Path<BR>");
			}
			socket_close($sock);


			$this->response->setOutput($this->load->view('common/success', $data));				
		} catch (ErrorException $ex) {
			print_r("<BR>Exception Path<BR>");
			print_r("Messaeg is : ".$e.Message);
			// how to handle this error?
			// add the order to be sent after a certain period
			$this->response->setOutput($this->load->view('common/success', $data));				

		}
	}
}