<?php 

Class GodownApp_table extends CI_Model{

	//set column field database for datatable orderable
        var $column_order = array(null, 'form_no', 'name', 'address_1', 'mobileNo', 'god_address1', 'product_name', 'godown_area', 'type_of_godown', 'start_date', 'other_muncipal_lic', 'renewal_lic', 'old_lic_no', 'explosive', 'pending_dues', 'disapprove_earlier', 'date_added', null, null);

        //set column field database for datatable searchable 
        var $column_search = array('form_no', 'name', 'address_1', 'mobileNo', 'god_address1', 'product_name', 'godown_area', 'type_of_godown', 'start_date', 'other_muncipal_lic', 'renewal_lic', 'old_lic_no', 'explosive', 'pending_dues', 'disapprove_earlier', 'date_added');

	function __construct() {
        // Set table name
        $this->table = 'godownapplication';

    }

    function insert($insertArray = null){
    	$res = $this->db->insert($this->table, $insertArray);
    	if($res){
    		return $this->db->insert_id();
    	}else{
    		return null;
    	} 
    }

    function getDataByLic($licNo = null){

    	if($licNo != ''){
    		$query = $this->db->query("SELECT * FROM `godownapplication` WHERE lic_no = '".$licNo."' AND is_deleted = '0'")->result_array();

    		if($query){
    			return $query;
    		}else{
    			return null;
    		}
    	}else{
    		return null;
    	}
    }

    function getDataById($appid = null){
    	if($appid != ''){
    		$query = $this->db->query("SELECT * FROM `godownapplication` WHERE godown_id = '$appid'")->result_array();

    		if($query){
    			return $query;
    		}else{
    			return null;
    		}
    	}else{
    		return null;
    	}
    }

    function edit($updateArray, $appId){
    	$this->db->where('godown_id', $appId)
    			 ->update($this->table, $updateArray);

    	return true;
    }

    function getAppData($searchVal = null, $fromDate = null, $toDate = null, $approval = null, $approvalStatus = null, $i = null, $rowperpage = null, $columnIndex = null, $columnName = null, $columnSortOrder = null){
        $sessData = $this->session->userdata('user_session');
        $dept_id = $sessData[0]['dept_id'];

        // echo $fromDate."-".$toDate."-".$approval."-".$approvalStatus;exit;

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
            $appStatusCond = " WHERE gd.status_id = '".$approvalStatus."'";
           }
        }

        if($columnName == 'sr_no'){
            $columnName = "gd.godown_id";
        }else{
            $columnName = "gd.".$columnName;
        }

    	if($searchVal != ''){
            $query = $this->db->query("SELECT gd.* FROM (SELECT ta.*, (SELECT status_title FROM app_status_level WHERE status_id = (SELECT status_id FROM application_remarks WHERE app_id = ta.godown_id AND dept_id = '7' ORDER BY id DESC limit 1)) remarks FROM `godownapplication` ta WHERE ".$approvalCondn." ".$dateCond." AND (ta.form_no like '%$searchVal%' OR ta.name like '%searchVal%' OR ta.mobileNo like '%$searchVal%' OR ta.type_of_godown like '%$searchVal%' OR ta.pending_dues like '%$searchVal%')) gd".$appStatusCond." order by ".$columnName." ".$columnSortOrder." limit ".$i.",".$rowperpage)->result_array();
        }else{
            $query = $this->db->query("SELECT gd.* FROM (SELECT ta.*, (SELECT status_title FROM app_status_level WHERE status_id = (SELECT status_id FROM application_remarks WHERE app_id = ta.godown_id AND dept_id = '".$dept_id."' ORDER BY id DESC limit 1)) remarks, (SELECT status_id FROM application_remarks WHERE app_id = ta.godown_id AND dept_id = '".$dept_id."' ORDER BY id DESC limit 1) status_id FROM `godownapplication` ta WHERE ".$approvalCondn." ".$dateCond." ) gd ".$appStatusCond." order by ".$columnName." ".$columnSortOrder." limit ".$i.",".$rowperpage)->result_array();
        }

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


     function delStorage($appId = null, $ids = null){
     	if($ids == '1'){
     		$statusArray = array('status' => 2);	
     	}else{
     		$statusArray = array('status' => 1);
     	}
     	

     	$this->db->where('godown_id', $appId)
     			 ->update($this->table, $statusArray);

     	return true;		 	
     }
}
?>    