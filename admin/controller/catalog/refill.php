<?php
class ControllerCatalogRefill  extends Controller {
    private $error = array();

    public function index(){
        $this->load->language('catalog/refill');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/refill');

        $this->getForm();
    }
    private function getForm(){

    	$data['breadcrumbs'] = array();

      $data['breadcumbs'][] =array(
            'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
      );
      $url = '';
        $data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/unit', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );
			$data['user_token'] = $this->session->data['user_token'];
			$this->load->model('catalog/product');
			

			$data['outOfStock'] = array(
				['name' => "test1", "quantity" => 3]
			);
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('catalog/refill', $data));               
    }
    public function getBelts(){

        $barcode = $this->request->post['barcode'];
        // should we get productBarcode from get Requres? $this->request->get['barcode']
        $this->load->model('catalog/refill');
        $output= $this->model_catalog_refill->getBelts($barcode);
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($output));
		}
		public function fill(){
      $beltID   = $this->request->post['beltID'];
			$quantity  = $this->request->post['quantity'];
			$this->load->model('catalog/refill');
			$output = $this->model_catalog_refill->refill($beltID,$quantity);

    }
}