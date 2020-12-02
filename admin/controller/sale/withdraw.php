<?php
class ControllerSaleWithdraw extends Controller {
    public function index(){
        $this->load->language('sale/withdraw');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('sale/withdraw');
        $this->getList();
    }
    public function edit(){
        $this->load->model('catalog/product');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_product->addProduct($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if (isset($this->request->get['filter_sku'])) {
				$url .= '&filter_sku=' . $this->request->get['filter_sku'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->response->redirect($this->url->link('catalog/product', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function add() {
		$this->load->language('catalog/product');


		$this->load->model('sale/withdraw');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			$this->model_sale_withdraw->addWithdraw( $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';



			$this->response->redirect($this->url->link('sale/withdraw', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}
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
        //$data['add'] = $this->url->link('sale/withdraw/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['withdraws'] = array();
        $data['action'] = $this->url->link('sale/withdraw/add', 'user_token=' . $this->session->data['user_token'] . $url, true);

        $withdraw_total = $this->model_sale_withdraw->getTotalWithdraws();
        $results =$this->model_sale_withdraw->getWithdraws();
        $max = $this->model_sale_withdraw->getMax();
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
        $data['max'] = $max;
        $data['results'] = sprintf($this->language->get('text_pagination'), ($withdraw_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($withdraw_total - $this->config->get('config_limit_admin'))) ? $withdraw_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $withdraw_total, ceil($withdraw_total / $this->config->get('config_limit_admin')));
        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/withdraw_list', $data));

    }
}