<?php
class ControllerCatalogSupply extends Controller {
    public function index(){
        $this->load->language('catalog/manufacturer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/supply');

		$this->getList();
    }
   public function addSupply() {

        $this->load->model('catalog/supply');
        $json = array();		
        if (!isset($_REQUEST['product_id']) ) {
            $json['error']=1;
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }
        if (!isset($_REQUEST['quantity']) ) {
            $json['error']=1;
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        }
        $product_id = $_REQUEST['product_id'];
        $quantity   = $_REQUEST['quantity'];
        $this->model_catalog_supply->addSupply($product_id,$quantity);
        if($_REQUEST['dir']=='products'){
            $this->response->redirect($this->url->link('catalog/product', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }
        $this->response->redirect($this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'] . $url, true));

    }
    public function send(){
 
    }

    public function delete(){

    }
    public function deleteItem(){
        print_r("good");

    }
    public function getList(){

        $this->load->model('catalog/supply');
        $url = "";
        $results = $this->model_catalog_supply->getSupplyList();
        $data['supplies'] = array();
        foreach ($results as $result) {
			$data['supplies'][] = array(
				'supply_id'       =>  $result['supply_id'],
				'product_name'    => $result['product_name'],
				'user_id'         => $result['user_id'],
				'quantity'        => $result['quantity'],
				'date_added'      => $result['date_added'],
				'empty'          => $this->url->link('catalog/supply/deleteItem', 'user_token=' . $this->session->data['user_token'] . '&supply_id=' . $result['supply_id'] . $url, true)
			);
		}
		$data['add'] = $this->url->link('catalog/supply/send', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('catalog/supply/deleteItem', 'user_token=' . $this->session->data['user_token'] . $url, true);

	    $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/supply', $data));
	}
}