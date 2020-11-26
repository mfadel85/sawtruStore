<?php
class ControllerExtensionDashboardErrors extends Controller{

    public function index(){
        $this->load->language('extension/dashboard/errors');
		$this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('dashboard_errors', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/dashboard/errors', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/dashboard/errors', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard', true);

		if (isset($this->request->post['dashboard_errors_width'])) {
			$data['dashboard_errors_width'] = $this->request->post['dashboard_errors_width'];
		} else {
			$data['dashboard_errors_width'] = $this->config->get('dashboard_errors_width');
		}

		$data['columns'] = array();
		
		for ($i = 3; $i <= 12; $i++) {
			$data['columns'][] = $i;
		}
				
		if (isset($this->request->post['dashboard_errors_status'])) {
			$data['dashboard_errors_status'] = $this->request->post['dashboard_errors_status'];
		} else {
			$data['dashboard_errors_status'] = $this->config->get('dashboard_errors_status');
		}

		if (isset($this->request->post['dashboard_errors_sort_order'])) {
			$data['dashboard_errors_sort_order'] = $this->request->post['dashboard_errors_sort_order'];
		} else {
			$data['dashboard_errors_sort_order'] = $this->config->get('dashboard_errors_sort_order');
		}        

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/dashboard/errors_form', $data));
    }
    protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/dashboard/errors')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
    public function dashboard(){
        $this->load->language('extension/dashboard/errors');
        $data['user_token'] = $this->session->data['user_token'];

        $data['errors'] = array();

        $filter_data = array(
            'sort'  => 'o.date_added',
            'order' => 'DESC',
            'start' => 0,
            'limit' => 5
        );
        $this->load->model('setting/errors');
        $results = $this->model_setting_errors->getErrors();

        foreach($results as $result){
            $data['errors'][] = array(
                'error_id'           => $result['error_id'],
                'error_code'         => $result['error_code'],
                'error_explanation'  => $result['error_explanation'],
                'status'             => $result['status'],
                //'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'date_added'         => $result['date_added'],
                'view'               => $this->url->link('tool/errors', 'user_token=' . $this->session->data['user_token'] . '&error_id=' . $result['error_id'], true),

            );

        }
		return $this->load->view('extension/dashboard/error_info', $data);

    }
}