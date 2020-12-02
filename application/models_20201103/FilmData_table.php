<?php

Class FilmData_table extends CI_Model{

	var $column_order = array(null,'form_no','contact_person', 'place_of_shooting',null,null);

        //set column field database for datatable searchable 
        var $column_search = array('form_no','contact_person', 'place_of_shooting');

	function __construct() {
        // Set table name
        $this->table = 'filmdata';

    }


    function insert($data = null){
    	$res = $this->db->insert($this->table, $data);

    	if($this->db->insert_id() > 0){
    		return $this->db->insert_id();
    	}else{
    		return null;
    	}
    }

    function delete($act = null, $id = null){
        if($act == '1'){
            $upArray = array('status' => '2');
        }elseif ($act == '2') {
            $upArray = array('status' => '1');
        }

        $this->db->where('film_id', $id)
                 ->update($this->table, $upArray);

        return true;         
    }


    function getAppData($searchVal = null, $fromDate = null, $toDate = null, $approval = null, $approvalStatus = null, $i = null, $rowperpage = null, $columnIndex = null, $columnName = null, $columnSortOrder = null){

    	$sessData = $this->session->userdata('user_session');
    	$dept_id = $sessData[0]['dept_id'];

        // echo $fromDate." - ".$toDate." - ".$approval." - ".$approvalStatus;exit;

        $approvalCondn = "";

        switch($approval){
            case '0': 
                $approvalCondn = "(ta.status = '1' OR ta.status = '2')";
                break;
            case '1': 
                $approvalCondn = "ta.status = '1'";
                break;
            case '2': 
                $approvalCondn = "ta.status = '2'";
                break;        
        }

        //for date condition
        $dateCond = "";
        if($fromDate != '' && $toDate != ''){
            $dateCond = "AND date(ta.date_added) >= '".$fromDate."' AND date(ta.date_added) <= '".$toDate."'";
        }

        //approvalstatus condn
        $appStatusCond = "";

        if($approvalStatus != ''){
           if($approvalStatus != '0'){
            $appStatusCond = " WHERE fd.status_id = '".$approvalStatus."'";
           }
        }

        if($columnName == 'sr_no'){
            $columnName = "fd.film_id";
        }else{
            $columnName = "fd.".$columnName;
        }
        
    	if($searchVal != ''){
            $query = $this->db->query("SELECT fd.* FROM (SELECT ta.*, (SELECT status_title FROM app_status_level WHERE status_id = (SELECT status_id FROM application_remarks WHERE app_id = ta.film_id AND dept_id = '".$dept_id."' ORDER BY id DESC limit 1)) remarks, (SELECT status_id FROM application_remarks WHERE app_id = ta.film_id AND dept_id = '".$dept_id."' ORDER BY id DESC limit 1) status_id FROM `filmdata` ta WHERE ".$approvalCondn." ".$dateCond." AND (ta.form_no like '%$searchVal%' OR ta.contact_person like '%searchVal%' OR ta.place_of_shooting like '%$searchVal%' OR ta.reason_for_lic like '%$searchVal%')) fd ".$appStatusCond." order by ".$columnName." ".$columnSortOrder." limit ".$i.",".$rowperpage)->result_array();
        }else{
            $query = $this->db->query("SELECT fd.* FROM (SELECT ta.*, (SELECT status_title FROM app_status_level WHERE status_id = (SELECT status_id FROM application_remarks WHERE app_id = ta.film_id AND dept_id = '".$dept_id."' ORDER BY id DESC limit 1)) remarks, (SELECT status_id FROM application_remarks WHERE app_id = ta.film_id AND dept_id = '".$dept_id."' ORDER BY id DESC limit 1) status_id FROM `filmdata` ta WHERE ".$approvalCondn." ".$dateCond." ) fd ".$appStatusCond." order by ".$columnName." ".$columnSortOrder." limit ".$i.",".$rowperpage)->result_array();
        }

    	return $query;	
    }

    public function countFiltered($postData){
        $this->_get_datatables_query($postData);
        $query = $this->db->get();
        return $query->num_rows();
    }

    function appDataById($appId = null){
    	$query = $this->db->select('*')
							->from($this->table)
							->where(array('is_deleted' => '0','film_id' => $appId))
							->get()
							->result_array();

		if($query){
			return $query;
		}else{
			return false;
		}
    }

    function updateFilm($updateArray = null, $id = null){
    	$this->db->where('film_id', $id)
    			 ->update($this->table, $updateArray);
    }
    private function _get_datatables_query($postData){
             
        $this->db->from($this->table);
 
        $i = 0;
        // loop searchable columns 
        foreach($this->column_search as $item){
            // if datatable send POST for search
            if($postData['search']['value']){
                // first loop
                if($i===0){
                    // open bracket
                    $this->db->group_start();
                    $this->db->like($item, $postData['search']['value']);
                }else{
                    $this->db->or_like($item, $postData['search']['value']);
                }
                
                // last loop
                if(count($this->column_search) - 1 == $i){
                    // close bracket
                    $this->db->group_end();
                }
            }
            
            $i++;
        }
         
        if(isset($postData['order'])){
            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        }else if(isset($this->order)){
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
}
?>    