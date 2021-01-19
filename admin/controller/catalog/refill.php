<?php
class ControllerCatalogRefill  extends Controller {
    private $error = array();

    public function index(){
        print_r("Hello!!");
        $this->load->language('catalog/refill');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/refill');

        $this->getList();
        
    }
}