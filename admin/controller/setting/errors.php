<?php
class ControllerSettingErrors extends Controller {
	private $error = array();

	public function index() {
        $this->load->language('setting/errors');

		$this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/errors');

		$this->getList();
    }
    protected function getList() {
		$data['breadcrumbs'] = array();
        $url = '';

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('setting/errors', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );  
        $data['user_token'] = $this->session->data['user_token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

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
        $data['faults'] = array();
        $fault_total = $this->model_setting_errors->getTotalFaults();
        $results = $this->model_setting_errors->getErrors();
        foreach($results as $result){
            $data['errors'][] = array(
                'error_id'           => $result['error_id'],
                'error_code'         => $result['error_code'],
                'error_explanation'  => $result['error_explanation'],
                'status'             => $result['status'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'view'               => $this->url->link('setting/errors', 'user_token=' . $this->session->data['user_token'] . '&error_id=' . $result['error_id'], true),
            );

        }

        $pagination = new Pagination();
		$pagination->total = $fault_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('tool/errors', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['pagination'] = $pagination->render();

		$this->response->setOutput($this->load->view('setting/errors', $data));
      
    }
}