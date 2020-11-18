<?php
class ControllerCatalogUnit extends Controller {
    private $error = array();

    public function index(){
        $this->load->language('catalog/unit');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('catalog/unit');
        $this->getList();
    }
    protected function getList(){
        //filters
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
		$data['add'] = $this->url->link('catalog/unit/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['copy'] = $this->url->link('catalog/unit/copy', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('catalog/unit/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

        $data['units'] = array();

        $unit_total = $this->model_catalog_unit->getTotalUnits($filter_data);
        $results = $this->model_catalog_unit->getUnits($filter_data);
        foreach($results as $result){
            $data['units'][] = array(
                'unit_id' => $result['unit_id'],
                'barcode' => $result['barcode'],
                'name'    => $result['name'],
                'edit'    => $this->url->link('catalog/unit/edit', 'user_token=' . $this->session->data['user_token'] . '&unit_id=' . $result['unit_id'] . $url, true),
                'empty'   => $this->url->link('catalog/unit/emptyUnit', 'user_token=' . $this->session->data['user_token'] . '&unit_id=' . $result['unit_id'] . $url, true)

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
		$pagination->url = $this->url->link('catalog/unit', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();
        $data['statement'] = "likes";
        $data['results'] = sprintf($this->language->get('text_pagination'), ($unit_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($unit_total - $this->config->get('config_limit_admin'))) ? $unit_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $unit_total, ceil($unit_total / $this->config->get('config_limit_admin')));

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/unit_list', $data));
    }
    public function add(){
        $this->load->language('catalog/unit');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('catalog/unit');
        //print_r($this->validateForm());
        if(($this->request->server['REQUEST_METHOD'] == 'POST') /*&& $this->validateForm()*/){
			$this->model_catalog_unit->addUnit($this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$url = '';
			$this->response->redirect($this->url->link('catalog/unit', 'user_token=' . $this->session->data['user_token'] . $url, true));

        }
        $this->getForm();
    }

    public function edit(){
        $this->load->language('catalog/unit');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('catalog/unit');
        if(($this->request->server['REQUEST_METHOD'] == 'POST') /*&& $this->validateForm()*/){
            $this->model_catalog_unit->editUnit($this->request->get['unit_id'],$this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
            $url = '';
            $this->response->redirect($this->url->link('catalog/unit', 'user_token=' . $this->session->data['user_token'] . $url, true));

        }
        $this->getForm();
    }
    public function delete(){

        $this->load->language('catalog/unit');

		$this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/unit');
        $this->load->model('catalog/shelf');

        if (isset($this->request->post['selected']) /*&& $this->validateDelete()*/) {
            foreach ($this->request->post['selected'] as $unitID) {
                $this->model_catalog_shelf->emptyUnit($unitID);
                $this->model_catalog_unit->deleteUnit($unitID);
            }
            $this->response->redirect($this->url->link('catalog/unit', 'user_token=' . $this->session->data['user_token'] . $url, true));

        }
    }
    protected function getForm(){
        $data['text_form'] = !isset($this->request->get['unit_id']) ? $this->language->get('text_add'): $this->language->get('text_edit');
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
			'href' => $this->url->link('catalog/unit', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );   
		if (!isset($this->request->get['unit_id'])) {
			$data['action'] = $this->url->link('catalog/unit/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('catalog/unit/edit', 'user_token=' . $this->session->data['user_token'] . '&unit_id=' . $this->request->get['unit_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('catalog/unit', 'user_token=' . $this->session->data['user_token'] . $url, true);
        if (isset($this->request->get['unit_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$unit_info = $this->model_catalog_unit->getUnit($this->request->get['unit_id']);
        }   
        $data['user_token'] = $this->session->data['user_token'];
        $this->load->model('localisation/language');
        $data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['unit_name']['name'])) {
			$data['name'] = $this->request->post['unit_name']['name'];
		} elseif (!empty($unit_info)) {
			$data['name'] = $unit_info['name'];
		} else {
			$data['name'] = '';
        }
        $name = $data['name'];

		if (isset($this->request->post['barcode'])) {
			$data['barcode'] = $this->request->post['barcode'];
		} elseif (!empty($unit_info)) {
			$data['barcode'] = $unit_info['barcode'];
		} else {
			$data['barcode'] = '';
        }     

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($unit_info)) {
			$data['sort_order'] = $unit_info['sort_order'];
		} else {
			$data['sort_order'] = '';
        }          
        
		if (isset($this->request->post['unit_direction'])) {
			$data['unit_direction'] = $this->request->post['unit_direction'];
		} elseif (!empty($unit_info)) {
			$data['unit_direction'] = $unit_info['direction'];
		} else {
			$data['unit_direction'] = '';
        }    

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/unit_form', $data));    
    }
    public function emptyUnit(){
        $unitID = $this->request->get['unit_id'];
        $this->load->model('catalog/unit');
        $this->load->model('catalog/shelf');
        $this->model_catalog_shelf->emptyUnit($unitID);
        $this->response->redirect($this->url->link('catalog/unit', 'user_token=' . $this->session->data['user_token'] . $url, true));



    }
    protected function validateBarcode($barcode){
        return true;
        /// to validate a barcode whether correct or false
    }
    protected function validateDelete(){
        // has to have no shelves no belts no stock on belts
        return true;
    }
	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/unit')) {
			$this->error['warning'] = $this->language->get('error_permission');
        }


        if ((utf8_strlen($this->request->post['unit_name']['name']) < 1) || (utf8_strlen($this->request->post['unit_name']['name']) > 255)) {
            $this->error['name'][$language_id] = $this->language->get('error_name');
        }
        if ((utf8_strlen($this->request->post['barcode']) < 1) || (utf8_strlen($this->request->post['barcode']) > 255) || !$this->validateBarcode($this->request->post['barcode'])) {
			$this->error['warning'] = "Barcode Error";
        }
    }
}