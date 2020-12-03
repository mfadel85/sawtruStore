<?php
class ControllerCatalogSupply extends Controller {
   public function addSupply() {
     // product_id, quantity
    $this->load->model('catalog/supply');
    $json = array();		
    if (!isset($_REQUEST['product_id']) ) {
        $json['error']=1;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    if (!isset($_REQUEST['quantity']) ) {
        $json['error']=1;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    $product_id = $_REQUEST['product_id'];
    $quantity   = $_REQUEST['quantity'];
    $this->model_catalog_supply->addSupply($product_id,$quantity);
    $this->response->redirect($this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'] . $url, true));

   }
}