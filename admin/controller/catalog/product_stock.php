<?php
class ControllerCatalogProductStock extends Controller {
	private $error = array();

	public function index(){
		$this->load->language('catalog/product_stock');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/product_stock');
		$this->load->model('catalog/product');
		//$this->document->addScript('https://requirejs.org/docs/release/2.3.5/minified/require.js');
		$this->document->addScript('view/javascript/node_modules/barcoder/lib/barcoder.js');
		$data =  array();
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');	
        $data['sak'] = "This is the beginning.";

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);
		$data['user_token'] = $this->session->data['user_token'];

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/product_stock', 'user_token=' . $this->session->data['user_token'], true)
		);

		if (!isset($this->request->get['pos'])) {
			$data['action'] = $this->url->link('ccatalog/product_stock/add', 'user_token=' . $this->session->data['user_token'] , true);
		} else {
			$data['action'] = $this->url->link('catalog/product_stock/remove', 'user_token=' . $this->session->data['user_token'] . '&attribute_group_id=' . $this->request->get['attribute_group_id'], true);
		}

		$data['cancel'] = $this->url->link('catalog/product_stock', 'user_token=' . $this->session->data['user_token'] , true);

		$this->response->setOutput($this->load->view('catalog/product_stock',$data));
		//$this->response->redirect($this->url->link('catalog/product', 'user_token=' . $this->session->data['user_token'] . $url, true));

	}
	
}