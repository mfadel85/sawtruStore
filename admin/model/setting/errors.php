<?php
class ModelSettingErrors extends Model {
    public function index(){

    }
    public function getErrors(){
        $data['errors'] =  array();
        $data['errors'][] = array(
           'error_id'   => '1',
           'error_code'   => 'MS12',
           'status'   => 'Agreed',
           'date_added'   => 'Today'
        
        );
        
        return  $data['errors'];

    }
}