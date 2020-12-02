<?php
class ControllerSaleWithdraw extends Controller {
    public function index(){
        $this->load->language('sale/withdraw');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('sale/withdraw');
        $this->getList();
    }
    public function getList(){
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);
        $url = '';

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/withdraw', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );        
        $data['add'] = $this->url->link('sale/withdraw/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['withdraws'] = array();

        $withdraw_total = $this->model_sale_withdraw->getTotalWithdraws();
        $results =$this->model_sale_withdraw->getWithdraws();

        foreach($results as $result){
            $data['withdraws'][] = array(
                'transaction_id' => $result['transaction_id'],
                'amount'         => $result['amount'],
                'date_added'     => date($this->language->get('date_format_short'), strtotime($result['date_added'])),

            );
        }
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
		$pagination = new Pagination();
		$pagination->total = $withdraw_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('sale/withdraw', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($withdraw_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($withdraw_total - $this->config->get('config_limit_admin'))) ? $withdraw_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $withdraw_total, ceil($withdraw_total / $this->config->get('config_limit_admin')));
        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/withdraw_list', $data));

    }
}