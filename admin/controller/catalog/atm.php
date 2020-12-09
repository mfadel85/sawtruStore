<?php
class ControllerCatalogATM extends Controller {
    private $error = array();
    
    public function index(){
        $this->load->language('catalog/atm');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('catalog/atm');
        if($this->request->server['REQUEST_METHOD'] == 'POST'){
            $this->model_catalog_atm->editAtm($this->request->get['id'],$this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
            $url = '';
            $this->response->redirect($this->url->link('catalog/atm', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }
        $this->getForm();
    }

    protected function getForm(){

        if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
        }        
        if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
        }
        $url = '';
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/atm', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );  
        $data['atm'] = $this->model_catalog_atm->getAtm();
        $id = $data['atm']['id'];
		
        $data['action'] = $this->url->link('catalog/atm/udpate', 'user_token=' . $this->session->data['user_token'] . '&id=' .  $id . $url, true);
    
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/atm', $data));         

    }
    public function udpate(){

    }
}