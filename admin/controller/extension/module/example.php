<?php 

class ControllerExtensionModuleExample extends Controller {
	public function install(){
		$this->load->model("setting/event");
		$this->model_setting_event->addEvent("example","admin/view/common/column_left/before","extension/module/example/injectAdminMenu");
	}
	public function uninstall(){
		$this->load->model("setting/event");
		$this->model_setting_event->deleteEventByCode("example");
	}

	public function injectAdminMenu($eventRoute,&$data){
		//print_r('I love you ');
		/*$data['menus'][] = array(
			'id'       => 'menu-example',
			'icon'     => 'fa fa-shopping-cart fa-fw',
			'name'     => 'Example',
			'href' 	   => $this->url->link('extension/module/example',true),
			'children' => array()
		);*/
	}
}