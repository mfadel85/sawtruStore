<?php
class ModelSettingErrors extends Model {
    public function index(){

    }
    public function getErrors(){
        $data['errors'] =  array();
        $faults = $this->db->query("select * from oc_fault of join oc_fault_status ofs on of.status_id = ofs.status_id");
        foreach($faults->rows as $fault){
            $data['errors'][] = array(
                'error_id'   => $fault['fault_id'],
                'error_code'   => $fault['fault_code'],
                'error_explanation'   => $fault['explanation'],
                'status'   => $fault['status_name'],
                'date_added'   => 'Today 2:04 PM'
            );
        }
 
        return  $data['errors'];
    }
}