<?php
class ControllerCatalogShelf extends Controller {
    private $error = array();

    public function index(){
        $this->load->language('catalog/shelf');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('catalog/unit');
        $this->load->model('catalog/shelf');

        $this->getList();
    }

    protected function getList(){
        $data['error_warning'] = false;
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
        $filter_data = array();
		$data['add'] = $this->url->link('catalog/shelf/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['copy'] = $this->url->link('catalog/shelf/copy', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('catalog/shelf/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['units'] = array();
        $unit_total = $this->model_catalog_unit->getTotalUnits($filter_data);
        $results = $this->model_catalog_unit->getUnits($filter_data);
        foreach($results as $result){
            $data['units'][] = array(
                'unit_id' => $result['unit_id'],
                'barcode' => $result['barcode'],
                'name'    => $result['name'],
                'edit'    => $this->url->link('catalog/unit/edit', 'user_token=' . $this->session->data['user_token'] . '&unit_id=' . $result['unit_id'] . $url, true)

            );
        }
        $data['shelves'] = array();
        $shelves_total = $this->model_catalog_shelf->getTotalShelves(0);
        $shelvesResults = $this->model_catalog_shelf->getShelves($filter_data);
        foreach($shelvesResults as $shelf){
            $data['shelves'][]= array(
                'shelf_id' => $shelf['shelf_id'],
                'barcode'  => $shelf['barcode'],
                'height'   => $shelf['height'],
                'unit_id'  => $shelf['unit_id'],
                'unit_name'=> $shelf['unit_name'],
                'physical_row' => $shelf['physical_row'],
                'widht'    => $shelf['width'],
                'edit'     => $this->url->link('catalog/shelf/edit', 'user_token=' . $this->session->data['user_token'] . '&unit_id=' . $result['unit_id'] . $url, true)

            );
        }

        print_r($shelvesResults);

        $data['user_token'] = $this->session->data['user_token'];
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
        }       
        if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		$pagination = new Pagination();
		$pagination->total = $unit_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/shelf', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();
        $data['statement'] = "likes";
        $data['results'] = sprintf($this->language->get('text_pagination'), ($shelves_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($shelves_total - $this->config->get('config_limit_admin'))) ? $shelves_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $shelves_total, ceil($shelves_total / $this->config->get('config_limit_admin')));
        



        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/shelf_list', $data));
    }
    public function add(){
        $this->load->language('catalog/shelf');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('catalog/shelf');
        //print_r($this->validateForm());
        if(($this->request->server['REQUEST_METHOD'] == 'POST') /*&& $this->validateForm()*/){
			$this->model_catalog_unit->addUnit($this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$url = '';
			$this->response->redirect($this->url->link('catalog/shelf', 'user_token=' . $this->session->data['user_token'] . $url, true));

        }
        $this->getForm();
    }

    public function edit($shelf_id){
        
    }    

    public function getForm(){
        $this->load->model('catalog/unit');
        $this->load->model('catalog/shelf');

        $data['text_form'] = !isset($this->request->get['shelf_id']) ? $this->language->get('text_add'): $this->language->get('text_edit');
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
			'href' => $this->url->link('catalog/shelf', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );          

		if (!isset($this->request->get['shelf_id'])) {
			$data['action'] = $this->url->link('catalog/shelf/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('catalog/shelf/edit', 'user_token=' . $this->session->data['user_token'] . '&shelf_id=' . $this->request->get['shelf_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('catalog/shelf', 'user_token=' . $this->session->data['user_token'] . $url, true);
        if (isset($this->request->get['shelf_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$shelf_info = $this->model_catalog_unit->getShelf($this->request->get['shelf_id']);
        }   
        $data['user_token'] = $this->session->data['user_token'];
        $this->load->model('localisation/language');
        $data['languages'] = $this->model_localisation_language->getLanguages();        
		if (isset($this->request->post['barcode'])) {
			$data['barcode'] = $this->request->post['barcode'];
		} elseif (!empty($unit_info)) {
			$data['barcode'] = $unit_info['barcode'];
		} else {
			$data['barcode'] = '';
		} 

        $data['units'] = array();
        $filter_data = array();
        $results = $this->model_catalog_unit->getUnits($filter_data);
        foreach($results as $result){
            $data['units'][] = array(
                'unit_id' => $result['unit_id'],
                'barcode' => $result['barcode'],
                'name'    => $result['name'],
            );
        }
        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view('catalog/shelf_form', $data));    

    }
}