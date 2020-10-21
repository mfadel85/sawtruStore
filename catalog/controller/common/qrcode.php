<?php
class ControllerCommonQrcode extends Controller {
    public function index(){
        $this->load->language('common/qrcode');
        $data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$this->response->setOutput($this->load->view('common/qrcode', $data));

    }
}