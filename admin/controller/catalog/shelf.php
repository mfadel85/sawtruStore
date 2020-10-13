<?php
class ControllerCatalogShelf extends Controller {
    private $error = array();

    public function index(){
        $this->load->language('catalog/row');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('catalog/unit');
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
		$data['add'] = $this->url->link('catalog/row/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['copy'] = $this->url->link('catalog/row/copy', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('catalog/row/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['units'] = array();
        $unit_total = $this->model_catalog_unit->getTotalUnits($filter_data);
        $results = $this->model_catalog_unit->getUnits($filter_data);
        foreach($results as $result){
            $data['units'][] = array(
                'unit_id' => $result['unit_id'],
                'barcode' => $result['barcode'],
                'name'    => $result['name'],
                'edit'       => $this->url->link('catalog/unit/edit', 'user_token=' . $this->session->data['user_token'] . '&unit_id=' . $result['unit_id'] . $url, true)

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
        $data['results'] = sprintf($this->language->get('text_pagination'), ($shelf_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($shelf_total - $this->config->get('config_limit_admin'))) ? $shelf_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $shelf_total, ceil($shelf_total / $this->config->get('config_limit_admin')));
        



        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/shelf_list', $data));
    }
}