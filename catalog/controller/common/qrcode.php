<?php
require_once(DIR_STORAGE .'vendor/autoload.php');

use Endroid\QrCode\QrCode;
class ControllerCommonQrcode extends Controller {
    public function index(){
        //$qrCode = new QrCode('Life is too short to be generating QR codes');
        //header('Content-Type: '.$qrCode->getContentType());
        //$qrcode =  $qrCode->writeString();
        //$dataUri = $qrCode->writeDataUri();
		$this->load->model('tool/image');
        $this->load->language('common/qrcode');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');
        $data['qrcodeImage'] = "image/qrcode.png";
		$this->response->setOutput($this->load->view('common/qrcode', $data));

    }
}