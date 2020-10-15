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
            if($shelf['noBelts'] ){
                $beltsLink = 'catalog/shelf/generate';
                $type = "generate";
            }
            else {
                $beltsLink = 'catalog/shelf/editBarcodes';
                $type = "modify";

            }
                

            $data['shelves'][]= array(
                'shelf_id' => $shelf['shelf_id'],
                'barcode'  => $shelf['barcode'],
                'height'   => $shelf['height'],
                'unit_id'  => $shelf['unit_id'],
                'unit_name'=> $shelf['unit_name'],
                'physical_row' => $shelf['physical_row'],
                'width'    => $shelf['width'],
                'noBelts'  => $shelf['noBelts'],
                'type'    => $type,
                'edit'     => $this->url->link('catalog/shelf/edit', 'user_token=' . $this->session->data['user_token'] . '&shelf_id=' . $shelf['shelf_id'] . $url, true),
                'generate'     => $this->url->link($beltsLink, 'user_token=' . $this->session->data['user_token'] . '&shelf_id=' . $shelf['shelf_id'] .'&type=' . $type . $url, true),
            );
        }


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
    public function generate(){
        $this->load->model('catalog/shelf');

        if(($this->request->server['REQUEST_METHOD'] == 'POST') /*&& $this->validateForm()*/){
            //print_r($this->request->get['shelf_id']);
            $this->model_catalog_shelf->generateBelts($this->request->get['shelf_id'],$this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('catalog/shelf', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }        
        $this->getBeltsForm(); 
        
    }
    public function editBarcodes(){
        $this->load->language('catalog/shelf');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('catalog/shelf');
        if(($this->request->server['REQUEST_METHOD'] == 'POST') /*&& $this->validateForm()*/){
            //print_r($this->request->get['shelf_id']);
            //print_r($this->request->post);
            $this->model_catalog_shelf->updateBarcodes($this->request->get['shelf_id'],$this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
            $url = '';
            $this->response->redirect($this->url->link('catalog/shelf', 'user_token=' . $this->session->data['user_token'] . $url, true));

        }
        $this->getBeltsForm(); 
    }
    public function getBeltsForm(){
        $this->load->model('catalog/unit');
        $this->load->model('catalog/shelf'); 
        $this->load->language('catalog/shelf');
  
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
        //print_r($this->request);
		if ($this->request->get['type'] =="generate") {
			$data['action'] = $this->url->link('catalog/shelf/generate', 'user_token=' . $this->session->data['user_token']. '&shelf_id=' . $this->request->get['shelf_id']  . $url, true);
		} else {
			$data['action'] = $this->url->link('catalog/shelf/editBarcodes', 'user_token=' . $this->session->data['user_token'] . '&shelf_id=' . $this->request->get['shelf_id'] . $url, true);
        }        
        $data['cancel'] = $this->url->link('catalog/shelf', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['beltCount'] = 8;// to be defined automatically
        $data['belts'][] = array();
        $results = $this->model_catalog_shelf->getBelts($this->request->get['shelf_id']);
        /*echo "<pre>";
        print_r($results);
        echo "</pre>";*/
        foreach($results as $result){

            $data['belts'][] = array(
                'belt_id' => $result['pallet_id'],
                'barcode' => $result['barcode'],
            );
        }
        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view('catalog/shelf_generate', $data));    

    }    
    public function add(){
        $this->load->language('catalog/shelf');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('catalog/shelf');
        //print_r($this->validateForm());
        if(($this->request->server['REQUEST_METHOD'] == 'POST') /*&& $this->validateForm()*/){
			$this->model_catalog_shelf->addShelf($this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$url = '';
			$this->response->redirect($this->url->link('catalog/shelf', 'user_token=' . $this->session->data['user_token'] . $url, true));

        }
        $this->getForm();
    }

    public function edit(){
        $this->load->language('catalog/shelf');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('catalog/shelf');
        if(($this->request->server['REQUEST_METHOD'] == 'POST') /*&& $this->validateForm()*/){
            //print_r($this->request->get['shelf_id']);
            //print_r($this->request->post);
            $this->model_catalog_shelf->editShelf($this->request->get['shelf_id'],$this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
            $url = '';
            $this->response->redirect($this->url->link('catalog/shelf', 'user_token=' . $this->session->data['user_token'] . $url, true));

        }
        $this->getForm(); 
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
            $shelf_info = $this->model_catalog_shelf->getShelf($this->request->get['shelf_id']);
        }   
        if (isset($this->request->post['shelf_physical_row'])) {
			$data['physical_row'] = $this->request->post['shelf_physical_row'];
		} elseif (!empty($shelf_info)) {
			$data['physical_row'] = $shelf_info['shelf_physical_row'];
		} else {
			$data['physical_row'] = '';
        }

		if (isset($this->request->post['barcode'])) {
			$data['barcode'] = $this->request->post['barcode'];
		} elseif (!empty($shelf_info)) {
			$data['barcode'] = $shelf_info['barcode'];
		} else {
			$data['barcode'] = '';
        }  

		if (isset($this->request->post['height'])) {
			$data['height'] = $this->request->post['height'];
		} elseif (!empty($shelf_info)) {
			$data['height'] = $shelf_info['height'];
		} else {
			$data['height'] = '';
        }          

		if (isset($this->request->post['unit_id'])) {
			$data['unit_id'] = $this->request->post['unit_id'];
		} elseif (!empty($shelf_info)) {
			$data['unit_id'] = $shelf_info['unit_id'];
		} else {
			$data['unit_id'] = '';
        }   

		if (isset($this->request->post['width'])) {
			$data['width'] = $this->request->post['width'];
		} elseif (!empty($shelf_info)) {
			$data['width'] = $shelf_info['width'];
		} else {
			$data['width'] = '';
        }             

        $data['user_token'] = $this->session->data['user_token'];
        $this->load->model('localisation/language');
        $data['languages'] = $this->model_localisation_language->getLanguages();        


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
    public function delete(){

        $this->load->language('catalog/shelf');

		$this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/shelf');

        if (isset($this->request->post['selected']) /*&& $this->validateDelete()*/) {
            foreach ($this->request->post['selected'] as $shelf_id) {
                $this->model_catalog_shelf->deleteShelf($shelf_id);
            }
            $this->response->redirect($this->url->link('catalog/shelf', 'user_token=' . $this->session->data['user_token'] . $url, true));

        }
    }
}