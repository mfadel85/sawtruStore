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
        try {
			$address="192.168.250.37";//127.0.0.1
			$address=IP;//127.0.0.1
            //$address="127.0.0.1";//127.0.0.1
            $order = array(
                'Delivery' => 1,
				'OrderID' => 200
			);

            $json_data = json_encode($order);// path need to be changed
            
			$port="11111";
			$sock=socket_create(AF_INET,SOCK_STREAM,0) or die("Cannot create a socket");
			socket_connect($sock,$address,$port) or die("Could not connect to the socket");
			socket_write($sock,$json_data);
			//$read=socket_read($sock,1024);
			//echo $read; 
			socket_close($sock);
			$this->response->setOutput($this->load->view('common/success'));				
		} catch (Exception $e) {
			print_r($e.Message);
		}
    }
}