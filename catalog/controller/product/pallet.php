<?php
class ContollerProductPallet extends Controller {

	private $error = array();

	public function index(){
		$this->load->language('catalog/pallet');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->model('catalgo/pallet');
		$this->getList();
	}

	public function getPallet($palletID){
		$this->model('catalgo/pallet');
		$palletInfo = $this->model_product_pallet->getPallet($palletID);
	}
}