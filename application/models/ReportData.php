<?php

Class ReportData extends CI_Model{

	var $column_order = array(null,'app_id','application_id','department','final_approval','remarks','status','approved_date', null);

        //set column field database for datatable searchable 
    var $column_search = array('app_id','application_id','department','final_approval','remarks','status','approved_date');

	function __construct(){
		$session_userdata = $this->session->userdata('user_session');
            // print_r($session_userdata);exit;
        $this->role_id = $session_userdata[0]['role_id'];
        $this->dept_id = $session_userdata[0]['dept_id'];
	}

	function getData($searchVal = null, $fromDate = null, $toDate = null, $approval = null, $i = null, $rowperpage = null, $columnIndex = null, $columnName = null, $columnSortOrder = null){

		$approvalCondn = "";
        switch($approval){
            case '0':
            		$getCommAppId = $this->db->query("SELECT GROUP_CONCAT(status_id) status_id FROM `app_status_level` WHERE role_id = '".$this->role_id."' AND (status_approve = '1' OR status_approve = '2') AND is_deleted = '0'")->result_array();
                    if($getCommAppId[0]['status_id'] != ''){
                        $approvalCondn = "AND ar.status_id IN (".$getCommAppId[0]['status_id'].")";
                    }
                break;
            case '1':
            		$getCommAppId = $this->db->query("SELECT status_id FROM `app_status_level` WHERE role_id = '".$this->role_id."' AND status_approve = '1' AND is_deleted = '0'")->result_array();

                    $approvalCondn = "AND ar.status_id = '".$getCommAppId[0]['status_id']."'";
                break;
            case '2':
            		$getCommAppId = $this->db->query("SELECT status_id FROM `app_status_level` WHERE role_id = '".$this->role_id."' AND status_approve = '2' AND is_deleted = '0'")->result_array();

                    $approvalCondn = "AND ar.status_id = '".$getCommAppId[0]['status_id']."'";
                break;
        }

        $dateCond = "";
        if($fromDate != '' && $toDate != ''){
            $dateCond = " AND date(t2.created_at) >= '".$fromDate."' AND date(t2.created_at) <= '".$toDate."'";
        }

        if($columnName == 'sr_no'){
            $columnName = "t2.id";
        }else{
            $columnName = 't2.'.$columnName;
        }

		if($searchVal != ''){
			$getReportsData = $this->db->query("SELECT t2.* FROM (SELECT t1.*, (SELECT dept_title FROM department_table WHERE dept_id = t1.dept_id) dept_title, (SELECT role_title FROM roles_table WHERE role_id = t1.role_id) role_title, (SELECT status_title FROM `app_status_level` WHERE status_id = t1.status_id AND status = '1') status_title FROM (SELECT ar.* FROM `application_remarks` ar WHERE ar.dept_id = '".$this->dept_id."' AND ar.role_id = '".$this->role_id."' '".$approvalCondn.$dateCond."' AND ar.is_deleted = '0' AND ar.status = '1') t1) t2 WHERE (t2.dept_title LIKE '%".$searchVal."%' OR t2.app_id LIKE '%".$searchVal."%' OR t2.role_title like '%".$searchVal."%' OR t2.status_title like '%".$searchVal."%') ORDER BY ".$columnName." ".$columnSortOrder." limit ".$i.",".$rowperpage)->result_array();
		}else{
			$getReportsData = $this->db->query("SELECT t2.* FROM (SELECT t1.*, (SELECT dept_title FROM department_table WHERE dept_id = t1.dept_id) dept_title, (SELECT role_title FROM roles_table WHERE role_id = t1.role_id) role_title, (SELECT status_title FROM `app_status_level` WHERE status_id = t1.status_id AND status = '1') status_title FROM (SELECT ar.* FROM `application_remarks` ar WHERE ar.dept_id = '".$this->dept_id."' AND ar.role_id = '".$this->role_id."'".$approvalCondn.$dateCond." AND ar.is_deleted = '0' AND ar.status = '1') t1) t2 ORDER BY ".$columnName." ".$columnSortOrder." limit ".$i.",".$rowperpage)->result_array();
		}

		if($getReportsData){
    		return $getReportsData;
    	}else{
    		return null;
    	}
	}

	public function countFiltered($postData){
        $this->_get_datatables_query($postData);
        $query = $this->db->get();
        return $query->num_rows();
    }

    private function _get_datatables_query($postData){
             
            $this->db->from("application_remarks");
     
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

      public function paymentInsert($paymentInsert = null, $rowId = null){
      	if(!empty($paymentInsert)){
      		$res = $this->db->insert("payment", $paymentInsert);
      		if($res) {
      			$this->db->where("id", $rowId)
      					->update("application_remarks", array('status' => '3'));
				return true;
			} else {
				return false;
			}
      	}else{
      		return false;
      	}	

      }
}