<?php
class ControllerExtensionModuleProductNotification extends Controller {
    // admin/model/catalog/product/addProduct/before
    public function addProduct(&$route, &$args) {
        $this->load->model('catalog/product');

        // While the product being added, we add the next statement below by loading the catalog product model
        // by using the route (optional) and the scalar array of $args.
    }
}