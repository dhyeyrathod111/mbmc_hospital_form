<?php

Class Lic_TradeFact_table extends CI_Model{
 
    //set column field database for datatable orderable
        var $column_order = array(null,'form_no','name','shop_no','address','property_no','shop_name','type_of_business','new_renewal','existing_no','type_of_property','property_date', 'aadhar_no', 'pan_no', 'date_no_obj', 'date_food_lic', 'date_property_tax', 'date_establishment', 'date_assurance',null,null);

        //set column field database for datatable searchable 
        var $column_search = array('form_no','name','shop_no','address','property_no','shop_name','type_of_business','new_renewal','existing_no','type_of_property','property_date', 'aadhar_no', 'pan_no', 'date_no_obj', 'date_food_lic', 'date_property_tax', 'date_establishment', 'date_assurance');

	function __construct() {
        // Set table name
        $this->table = 'trade_faclicapplication';
        $session_userdata = $this->session->userdata('user_session');
            // print_r($session_userdata);exit;
        $this->role_id = $session_userdata[0]['role_id'];
        $this->dept_id = $session_userdata[0]['dept_id'];

    }

    function getData($searchVal = null, $fromDate = null, $toDate = null, $approval = null, $approvalStatus = null, $i = null, $rowperpage = null, $columnIndex = null, $columnName = null, $columnSortOrder = null){
        // echo $fromDate."-".$toDate."-".$approval."-".$approvalStatus;

        $approvalCondn = "";
        switch ($approval) {
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
            $dateCond = "AND date(ta.application_date) >= '".$fromDate."' AND date(ta.application_date) <= '".$toDate."'";
        }

        //approvalstatus condn
        $appStatusCond = "";

        if($approvalStatus != ''){
           if($approvalStatus != '0'){
            $appStatusCond = " WHERE ta.status_id = '".$approvalStatus."'";
           }
        }

        if($columnName == 'sr_no'){
            $columnName = "tda.tradefac_lic_id";
        }else{
            $columnName = "tda.".$columnName;
        }
        
        if($searchVal != ''){
            
            $res = $this->db->query("SELECT tda.* FROM (SELECT ta.*, (SELECT status_title FROM app_status_level WHERE status_id = (SELECT status_id FROM application_remarks WHERE app_id = ta.tradefac_lic_id AND dept_id = '".$this->dept_id."' ORDER BY id DESC limit 1)) remarks, (SELECT status_id FROM application_remarks WHERE app_id = ta.tradefac_lic_id AND dept_id = '".$this->dept_id."' ORDER BY id DESC limit 1) status_id FROM `trade_faclicapplication` ta WHERE ta.is_deleted = '0' AND ".$approvalCondn." ".$dateCond." AND (ta.form_no like '%$searchVal%' OR ta.name like '%searchVal%' OR ta.shop_no like '%$searchVal%' OR ta.shop_name like '%$searchVal%' OR ta.type_of_business like '%$searchVal%')) tda".$appStatusCond." order by ".$columnName." ".$columnSortOrder." limit ".$i.",".$rowperpage)->result_array();

        }else{
            
            $res = $this->db->query("SELECT tda.* FROM (SELECT ta.*, (SELECT status_title FROM app_status_level WHERE status_id = (SELECT status_id FROM application_remarks WHERE app_id = ta.tradefac_lic_id AND dept_id = '".$this->dept_id."' ORDER BY id DESC limit 1)) remarks, (SELECT status_id FROM application_remarks WHERE app_id = ta.tradefac_lic_id AND dept_id = '".$this->dept_id."' ORDER BY id DESC limit 1) status_id FROM `trade_faclicapplication` ta WHERE ta.is_deleted = '0' AND ".$approvalCondn." ".$dateCond.") tda".$appStatusCond." order by ".$columnName." ".$columnSortOrder." limit ".$i.",".$rowperpage)->result_array();
        }

    	return $res;
    }

    function getLicData($licNo = null){
        if($licNo != ''){
            $query = $this->db->query("SELECT * FROM `trade_faclicapplication` WHERE existing_no = '".$licNo."' ORDER BY tradefac_lic_id DESC LIMIT 1")->result_array();

            return $query;
        }else{
            return NULL;
        }
    }

    function getPropType(){
        $query = $this->db->query("SELECT * FROM property_type WHERE status = '1'")->result_array();

        return $query;
    }

    function propById($propId = null){
        $query = $this->db->query("SELECT * FROM property_type WHERE status = '1' AND prop_type_id = '$propId'")->result_array();

        return $query;
    }

    public function countFiltered($postData){
        $this->_get_datatables_query($postData);
        $query = $this->db->get();
        return $query->num_rows();
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

    function appDataById($appId = null){
    	$query = $this->db->select('*')
							->from($this->table)
							->where(array('is_deleted' => '0','tradefac_lic_id' => $appId))
							->get()
							->result_array();

		if($query){
			return $query;
		}else{
			return false;
		}
    }

    //create lic
    function createLic($data = null){
    	if($data != ''){
    		$this->db->insert($this->table, $data);	
    	}

    	if($this->db->insert_id() > 0){
    		return true;
    	}else{
    		return false;
    	}
    }

    function editLic($update = null, $id = null){
        if($update != ''){
            $this->db->where(array('tradefac_lic_id' => $id))
                     ->update($this->table, $update);
            return true;         
        }
    }

    function delete($act = null, $id = null){
        if($act == '1'){
            $upArray = array('status' => '2');
        }elseif ($act == '2') {
            $upArray = array('status' => '1');
        }

        $this->db->where('tradefac_lic_id', $id)
                 ->update($this->table, $upArray);

        return true;         
    }
}    

?>