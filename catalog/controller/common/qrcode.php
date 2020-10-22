<?php

class ControllerCommonQrcode extends Controller {
    public function index(){

		$this->load->model('tool/image');
        $this->load->language('common/qrcode');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');
        $data['qrcodeImage'] = "image/qrcode.png";
		$this->response->setOutput($this->load->view('common/qrcode', $data));

    }
    public function deliver(){
        print_r("I will deliver take it easy man");
        // take the order id and send a command to the c# program
    }
}