<?php
class ControllerCatalogFixStock extends Controller {
    private $error = array();
    //#mfhedit
    // this class is to fix the stock between oc_product_to_position and oc_pallet and oc_product
    public function addPromotion(){
        $this->load->model('catalog/product');
        $this->load->model('catalog/product_stock');
        $this->load->model('catalog/pallet');
        $products = $this->model_catalog_product->getProducts();
        //print_r($products);

    }
	public function index(){
        /// get all the active products from oc_product_to_position
        //foreach everyone first compare with oc_product_to_position and update oc_product according to oc_product_to_position
        //update oc_pallet (the start cell and middle and end), according to oc_product_to_position
        // give a report about all the products changed
        $this->load->model('catalog/product');
        $this->load->model('catalog/product_stock');
        $this->load->model('catalog/pallet');
        $products = $this->model_catalog_product->getProducts();
        foreach($products as $product){
            //print_r($product);
            $quantity = $this->model_catalog_pallet->getProductQuantity($product['product_id']);
            $palletQuantity = $this->model_catalog_pallet->getPalletProductQuantity($product['product_id']);
            $productID = $product['product_id'];

            if($quantity != $product['quantity']){
                $oldQuantity = $product['quantity'];
                $this->model_catalog_product->updateQuantity( $productID,$quantity);
                print_r("<BR>Şerefsizliğe $productID bak $quantity vs $oldQuantity<BR>");
            }
            if($quantity !=  $palletQuantity){
                $includedBelts = $this->model_catalog_pallet->getBeltsProduct($product['product_id']);
                print_r("<BR>Product ID is:  $productID <BR>Şerefsizliğe $productID bakmissin $quantity vs   $palletQuantity <BR>");

                foreach($includedBelts as $belt){
                    print_r(PHP_EOL.$belt).PHP_EOL;
                    $this->model_catalog_pallet->updateBeltQuantity( $belt['quantity'],$belt['start_pallet']);
                }
                    
            }   
                
        }
        
    }

   

}