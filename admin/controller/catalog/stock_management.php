<?php

/**
 * Template File Doc Comment
 * 
 * PHP version 7
 *
 * @category Template_Class
 * @package  Template_Class
 * @author   Author <author@domain.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://localhost/
 */

/**
 * Template Class Doc Comment
 * 
 * Template Class
 * 
 * @category Template_Class
 * @package  Template_Class
 * @author   Author <author@domain.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://localhost/
 */

class ControllerCatalogStockManagement extends Controller
{

    private $_error = array();
    /**
     * Describes index function
     *
     * @return void
     **/
	
    public function index()
    {	
		$this->document->addScript('view/javascript/jquery-ui.js');
        $this->document->addStyle('view/stylesheet/jquery-ui.css');

        $this->load->language('catalog/stock_management');
        $this->document->setTitle($this->language->get('heading_title'));
        $data = array();
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');			
        $data['sak'] = "This is the beginning.";
		// get the product list
		// get the map fully		
		//// we need the product list
		/// we need the product to position list
//		//// we need the product list
		/// we need the product to position list
		$this->load->model('catalog/product');
		$this->load->model('catalog/pallet');
		$extractedMap = $this->extractMap();
		$data['map'] = $extractedMap;
		$data['unitCount'] = count($extractedMap);
		
		
		
		$data['products'] = array();
		$data['products'] = $this->model_catalog_product->getProducts();

		$data['decreasedProducts'] = array();
		$data['decreasedProducts'] = $this->model_catalog_product->getDecreasedProducts();
		
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link(
				'common/dashboard', 
				'user_token=' . $this->session->data['user_token'], 
				true
			)
		);
		$data['user_token'] = $this->session->data['user_token'];

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/stock_management', 'user_token=' . $this->session->data['user_token'], true)
		);
        
        
        $this->response->setOutput($this->load->view('catalog/stock_management',$data));
	}
	public function extractMap(){
		$this->load->model('catalog/pallet');
		$map = $this->model_catalog_pallet->getMap();
		return $map;


	}
}