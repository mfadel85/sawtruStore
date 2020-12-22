<?php
class ControllerExtensionDashboardComplaint extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/dashboard/complaint');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('dashboard_complaint', $this->request->post);

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
			'href' => $this->url->link('extension/dashboard/complaint', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/dashboard/complaint', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard', true);

		if (isset($this->request->post['dashboard_complaint_width'])) {
			$data['dashboard_complaint_width'] = $this->request->post['dashboard_complaint_width'];
		} else {
			$data['dashboard_complaint_width'] = $this->config->get('dashboard_complaint_width');
		}

		$data['columns'] = array();
		
		for ($i = 3; $i <= 12; $i++) {
			$data['columns'][] = $i;
		}
				
		if (isset($this->request->post['dashboard_complaint_status'])) {
			$data['dashboard_complaint_status'] = $this->request->post['dashboard_complaint_status'];
		} else {
			$data['dashboard_complaint_status'] = $this->config->get('dashboard_complaint_status');
		}

		if (isset($this->request->post['dashboard_complaint_sort_order'])) {
			$data['dashboard_complaint_sort_order'] = $this->request->post['dashboard_complaint_sort_order'];
		} else {
			$data['dashboard_complaint_sort_order'] = $this->config->get('dashboard_complaint_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/dashboard/complaint_form', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/dashboard/complaint')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	public function dashboard() {
		$this->load->language('extension/dashboard/complaint');

		$data['user_token'] = $this->session->data['user_token'];

		// Last 5 Orders
		$data['orders'] = array();

		$filter_data = array(
			'sort'  => 'o.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => 5
		);

		$this->load->model('sale/return');
		
		$results = $this->model_sale_return->getReturns($filter_data);

		foreach ($results as $result) {
			$data['orders'][] = array(
				'order_id'   => $result['order_id'],
				'name'   => $result['firstname']." ".$result['lastname'],
				'status'     => $result['return_status'],
				'product'    => $result['product'],
				'date_ordered' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'view'       => $this->url->link('sale/return/edit', 'user_token=' . $this->session->data['user_token'] . '&return_id=' . $result['return_id'], true),
			);
		}

		return $this->load->view('extension/dashboard/complaint_info', $data);
	}
}
