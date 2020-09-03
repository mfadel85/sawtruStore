<?php

class ControllerCatalogStockManagement extends Controller {
    private $error = array();

    public function index(){
		
		$this->document->addScript('https://code.jquery.com/ui/1.12.1/jquery-ui.js'); /// no need for this at all
		$this->document->addStyle('//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');

        $this->load->language('catalog/stock_management');
        $this->document->setTitle($this->language->get('heading_title'));
        $data = array();
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
			'href' => $this->url->link('catalog/stock_management', 'user_token=' . $this->session->data['user_token'], true)
		);
        
        
        $this->response->setOutput($this->load->view('catalog/stock_management',$data));
    }
}