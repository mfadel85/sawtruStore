<?php
class ContollerCatalogPallet extends Controller {

	private $error = array();

	public function index(){
		$this->load->language('catalog/pallet');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->model('catalog/pallet');
		$this->getList();
	}

	// pallet has to be changed with Belt word later
	public function getPallet($palletID){
		$this->model('catalog/pallet');
		$palletInfo = $this->model_catalog_pallet->getPallet($palletID);
	}

	public function emptyBelt($beltID){
		$this->model('catalog/pallet');
		$this->model_catalog_pallet->emptyBelt($beltID);
	}
	public function emptyShelf($shelfID){
		$this->model('catalog/pallet');
		$this->model_catalog_pallet->emptyShelf($shelfID);
	}	
}