<?php
class ControllerCatalogRefill  extends Controller {
    private $error = array();

    public function index(){
        $this->load->language('catalog/refill');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/refill');

        $this->getForm();
    }
    public function getForm(){

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
 
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/refill', $data));               
    }
    public function getBelts($productID){


        // return as json data
    }
}