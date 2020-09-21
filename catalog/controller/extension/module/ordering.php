<?php 
class ControllerExtensionModuleOrdering extends Controller {
	public function install(){
		$this->load->model('extension/event');
		$this->model_extension_event->addEvent('ordering','post.order.history.add','module/ordering/bring');
		$data['heading_title'] = $this->language->get('heading_title');

	}

	public function uninstall(){
		$this->load->model('extension/event');
		$this->model_extension_event->deleteEvent('ordering');
	}

	public function bring(){
		die();
		print_r(' Hello New Order!! ');

	}
}