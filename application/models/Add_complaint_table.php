<?php

Class Add_complaint_table extends CI_Model{

    var $column_order1 = array(null, 'treeName', null, null);

    var $column_search1 = array('treeName');

    var $column_order2 = array(null, 'processName', null, null);

	var $column_search2 = array('processName');
	
	public $dept_id;
	public $role_id;

	function __construct() {
        // Set table name
        $this->table = 'treecuttingapplications';
		$session_userdata = $this->session->userdata('user_session');
		$this->role_id = $session_userdata[0]['role_id'];
		$this->dept_id = $session_userdata[0]['dept_id'];
    }


    function insertApplication($data = null){
    	if(!empty($data)){
    		$result = $this->db->insert($this->table,$data);
    		if($result) {
				return $this->db->insert_id();
			} else {
				return null;
			}
    	}else{
    		return null;
    	}
    }
 
    function insertTree($treeData = null){
    	if(!empty($treeData)){
    		$result = $this->db->insert('gardendata',$treeData);
    		if($result) {
				return $this->db->insert_id();
			} else {
				return null;
			}
    	}else{
    		return null;
    	}
    }

    function get_permission_type(){
        $query = $this->db->select('*')
							->from('garden_permission')
							->where(array('status' => '1'))
							->get()
							->result_array();

		if($query){
			return $query;
		}else{
			return false;
		}
    }

    function getAppData($searchVal = null, $fromDate = null, $toDate = null, $approval = null, $approvalStatus = null, $i = null, $rowperpage = null, $columnIndex = null, $columnName = null, $columnSortOrder = null){
    	// $query = $this->db->select('*')
					// 		->from($this->table)
					// 		->where(array('is_deleted' => '0', 'status' => '1'))
					// 		->get()
					// 		->result_array();

        $approvalCondn = "";
        switch($approval){
            case '0':
                    $approvalCondn = "(ta.is_deleted = '0' OR ta.is_deleted = '1')";
                break;
            case '1':
                    $approvalCondn = "ta.is_deleted = '0'";
                break;
            case '2':
                    $approvalCondn = "ta.is_deleted = '1'";
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
            $appStatusCond = " WHERE tca.status_id = '".$approvalStatus."'";
           }
        }

        if($columnName == 'sr_no'){
            $columnName = "tca.cutAppId";
        }else{
            $columnName = "tca.".$columnName;
		}

		//get prev role id
		$prevRoleCond = "";

		$prevRole = $this->db->query("SELECT * FROM `permission_access` WHERE access_id < (SELECT access_id from permission_access WHERE dept_id = '".$this->dept_id."' AND role_id = '".$this->role_id."' AND status = '1') AND dept_id = '".$this->dept_id."' AND status = '1' ORDER BY access_id DESC LIMIT 1")->result_array();
		
		if(!empty($prevRole)){
			if($appStatusCond != ''){
				$prevRoleCond = "AND tca.last_role_id = '".$prevRole[0]['role_id']."'";
			}else{
				$prevRoleCond = "WHERE tca.last_role_id = '".$prevRole[0]['role_id']."'";
			}
			
		}

        if($searchVal != ''){
            
            $totalCount = $this->db->query("SELECT COUNT(tca.cutAppId) Totalrows FROM (SELECT ta.*, (SELECT status_title FROM app_status_level WHERE status_id = (SELECT status_id FROM application_remarks WHERE app_id = ta.cutAppId AND dept_id = '".$this->dept_id."' AND status = '1' ORDER BY id DESC limit 1)) remarks, (SELECT status_id FROM application_remarks WHERE app_id = ta.cutAppId AND dept_id = '".$this->dept_id."' ORDER BY id DESC limit 1) status_id, (SELECT role_id FROM application_remarks WHERE application_remarks.app_id = ta.cutAppId ORDER BY id DESC LIMIT 1) AS last_approved_role_id  ,(SELECT permission_type FROM garden_permission WHERE garper_id = ta.permission_type) permission_type_name, (SELECT role_id FROM application_remarks WHERE app_id = ta.cutAppId AND dept_id = '".$this->dept_id."' AND status = '1' ORDER BY id DESC limit 1) last_role_id FROM `treecuttingapplications` ta WHERE ".$approvalCondn." ".$dateCond." AND (ta.formNo like '%$searchVal%' OR ta.applicantName like '%$searchVal%' OR ta.mobile like '%$searchVal%' OR email like '%$searchVal%' OR ta.wardNo like '$searchVal') ORDER BY ta.cutAppId DESC) tca".$appStatusCond.' '.$prevRoleCond." order by ".$columnName." ".$columnSortOrder)->result_array();
			
			$query = $this->db->query("SELECT tca.* FROM (SELECT ta.*, (SELECT status_title FROM app_status_level WHERE status_id = (SELECT status_id FROM application_remarks WHERE app_id = ta.cutAppId AND dept_id = '".$this->dept_id."' AND status = '1' ORDER BY id DESC limit 1)) remarks, (SELECT status_id FROM application_remarks WHERE app_id = ta.cutAppId AND dept_id = '".$this->dept_id."' ORDER BY id DESC limit 1) status_id, (SELECT role_id FROM application_remarks WHERE application_remarks.app_id = ta.cutAppId ORDER BY id DESC LIMIT 1) AS last_approved_role_id ,(SELECT permission_type FROM garden_permission WHERE garper_id = ta.permission_type) permission_type_name, (SELECT role_id FROM application_remarks WHERE app_id = ta.cutAppId AND dept_id = '".$this->dept_id."' AND status = '1' ORDER BY id DESC limit 1) last_role_id FROM `treecuttingapplications` ta WHERE ".$approvalCondn." ".$dateCond." AND (ta.formNo like '%$searchVal%' OR ta.applicantName like '%$searchVal%' OR ta.mobile like '%$searchVal%' OR email like '%$searchVal%' OR ta.wardNo like '$searchVal') ORDER BY ta.cutAppId DESC) tca".$appStatusCond.' '.$prevRoleCond." order by ".$columnName." ".$columnSortOrder." limit ".$i.",".$rowperpage)->result_array();
			
        }else{

			// echo "<pre>";print_r($prevRole);exit;
			
			$totalCount = $this->db->query("SELECT COUNT(tca.cutAppId) Totalrows FROM (SELECT ta.*, (SELECT status_title FROM app_status_level WHERE status_id = (SELECT status_id FROM application_remarks WHERE app_id = ta.cutAppId AND dept_id = '".$this->dept_id."' AND status = '1' ORDER BY id DESC limit 1)) remarks, (SELECT status_id FROM application_remarks WHERE app_id = ta.cutAppId AND dept_id = '".$this->dept_id."' ORDER BY id DESC limit 1) status_id, (SELECT role_id FROM application_remarks WHERE application_remarks.app_id = ta.cutAppId ORDER BY id DESC LIMIT 1) AS last_approved_role_id , (SELECT permission_type FROM garden_permission WHERE garper_id = ta.permission_type) permission_type_name, (SELECT role_id FROM application_remarks WHERE app_id = ta.cutAppId AND dept_id = '".$this->dept_id."' AND status = '1' ORDER BY id DESC limit 1) last_role_id FROM `treecuttingapplications` ta WHERE ".$approvalCondn." ".$dateCond." ORDER BY ta.cutAppId DESC) tca ".$appStatusCond.' '.$prevRoleCond." order by ".$columnName." ".$columnSortOrder)->result_array();
			
			$query = $this->db->query("SELECT tca.* FROM (SELECT ta.*, (SELECT status_title FROM app_status_level WHERE status_id = (SELECT status_id FROM application_remarks WHERE app_id = ta.cutAppId AND dept_id = '".$this->dept_id."' AND status = '1' ORDER BY id DESC limit 1)) remarks, (SELECT status_id FROM application_remarks WHERE app_id = ta.cutAppId AND dept_id = '".$this->dept_id."' ORDER BY id DESC limit 1) status_id, (SELECT role_id FROM application_remarks WHERE application_remarks.app_id = ta.cutAppId ORDER BY id DESC LIMIT 1) AS last_approved_role_id , (SELECT permission_type FROM garden_permission WHERE garper_id = ta.permission_type) permission_type_name, (SELECT role_id FROM application_remarks WHERE app_id = ta.cutAppId AND dept_id = '".$this->dept_id."' AND status = '1' ORDER BY id DESC limit 1) last_role_id FROM `treecuttingapplications` ta WHERE ".$approvalCondn." ".$dateCond." ORDER BY ta.cutAppId DESC) tca ".$appStatusCond.' '.$prevRoleCond." order by ".$columnName." ".$columnSortOrder." limit ".$i.",".$rowperpage)->result_array();
			
        }
        
        $data['totalCount'] = $totalCount;
		$data['query'] = $query;

		if(!empty($query)){
			return $data;
		}else{
			return false;
		}
	}
	
	//new  function
	public function getRoleId($lastRoleId = null){
		if($lastRoleId != ''){
			$res = $this->db->query("SELECT role_id FROM permission_access WHERE access_id > (SELECT access_id FROM `permission_access` WHERE role_id = '".$lastRoleId."' AND dept_id = '".$this->dept_id."' AND status = '1') AND status = '1' ORDER BY access_id LIMIT 1")->result_array();

			return $res;
		}else{
			return false;
		}
	}

    function getAppDataById($appId = null){
    	$query = $this->db->select('*')
							->from($this->table)
							->where(array('is_deleted' => '0','cutAppId' => $appId))
							->get()
							->result_array();

		if($query){
			return $query;
		}else{
			return false;
		}

    }

    function getDocumentData($app_id = null){
    	$query = $this->db->select('*')
							->from('gardendata')
							->where(array('is_deleted' => '0','complain_id' => $app_id))
							->get()
							->result_array();

		if($query){
			return $query;
		}else{
			return false;
		}
    }

    function deleteGarden($gardenId = null){
    	$result = $this->db
							->where('gardenId', $gardenId)	
							->update('gardendata', array('is_deleted'=>'1'));
// 		$str = $this->db->last_query();
// echo $str;
// exit;					
		if($result){
			return true;
		}else{
			return null;
		}	
    }

    function complainEdit($appArray = null, $complainId = null){
    	$query = $this->db
    					  ->where('cutAppId', $complainId)
    					  ->update($this->table, $appArray);

    	if($query){
    		return true;
    	}else{
    		return false;
    	}				  
    }

    function gardenDataUpdate($updateArray = null, $complainId = null, $gardenId = null){
    	$query = $this->db
    					  ->where(array('gardenId' => $gardenId, 'complain_id' => $complainId))
    					  ->update('gardendata', $updateArray);

    	if($query){
    		return true;
    	}else{
    		return false;
    	}
    }

    function complainDelete($complainId = null, $status = null){
        
    	if($status == '1'){
    		$query = $this->db
    					  ->where('cutAppId', $complainId)
    					  ->update($this->table, array('is_deleted' => '1'));

	 	   	$query2 = $this->db
    					  ->where('complain_Id', $complainId)
    					  ->update('gardendata', array('is_deleted' => '1'));
    	}else{
    		$query = $this->db
    					  ->where('cutAppId', $complainId)
    					  ->update($this->table, array('is_deleted' => '0'));

	    	$query2 = $this->db
    					  ->where('complain_Id', $complainId)
    					  ->update('gardendata', array('is_deleted' => '0'));
    	}
    	

    	if($query){
    		return true;
    	}else{
    		return false;
    	}				  
    }

    function getGardenData($complainId = null){
		
		// $query = $this->db->query("SELECT (SELECT treeName FROM treenames WHERE tree_id = gd.tree_id) treeName, (SELECT processName FROM treecuttingprocess WHERE processId = gd.process_id) processName, gd.* FROM gardendata gd WHERE gd.complain_id = '$complainId'")->result_array();
		$query = $this->db->query("SELECT (SELECT treeName FROM treenames WHERE tree_id = gd.tree_id) treeName, CASE WHEN gd.permission_id > 0 THEN (select permission_type from garden_permission WHERE garper_id = gd.permission_id AND status = '1') ELSE (SELECT permission_type FROM garden_permission WHERE garper_id = (SELECT permission_type from treecuttingapplications WHERE cutAppId = gd.complain_Id)) END permission_name, IF(gd.conditionStatus = 1, 'Hazardous', 'Non Hazardous') condition_name, IF(gd.no_of_trees = 0, 1, gd.no_of_trees) totalTrees, gd.* FROM gardendata gd WHERE gd.complain_id = '".$complainId."'")->result_array();

    	if($query){
    		return $query;
    	}else{
    		return null;
    	}
    }

    function changeStatus($appId = null, $status = null){

    	if($status == '1'){
    		$data = array(
    			'status' => 4 
    		);
    	}else{
    		$data = array(
    			'status' => 1 
    		);
    	}

    	$query2 = $this->db
    					  ->where('cutAppId', $appId)
    					  ->update('treecuttingapplications', array('is_deleted' => '1', 'sttaus' => '4'));

    	if($query2){
    		return true;
    	}else{
    		return false;
    	}				  
    }

    function getApprovalStatus(){
        $query = $this->db->query();
    }

    //add tree
    public function countAll(){
        $this->db->from('treenames');
        return $this->db->count_all_results();
    }

    public function countFiltered($postData){
        $this->_get_datatables_query1($postData);
        $query = $this->db->get();
        return $query->num_rows();
    }

    private function _get_datatables_query1($postData){
        $this->db->from('treenames');
                
        $i = 0;
        // loop searchable columns 
        foreach($this->column_search1 as $item){
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
                if(count($this->column_search1) - 1 == $i){
                    // close bracket
                    $this->db->group_end();
                }
            }
            $i++;
        }
         
        if(isset($postData['order'])){
            $this->db->order_by($this->column_order1[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        }else if(isset($this->order)){
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    //add process
    public function countAll1(){
        $this->db->from('treecuttingprocess');
        return $this->db->count_all_results();
    }

    public function countFiltered2($postData){
        $this->_get_datatables_query2($postData);
        $query = $this->db->get();
        return $query->num_rows();
    }

    private function _get_datatables_query2($postData){
        $this->db->from('treecuttingprocess');
                
        $i = 0;
        // loop searchable columns 
        foreach($this->column_search2 as $item){
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
                if(count($this->column_search2) - 1 == $i){
                    // close bracket
                    $this->db->group_end();
                }
            }
            $i++;
        }
         
        if(isset($postData['order'])){
            $this->db->order_by($this->column_order2[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        }else if(isset($this->order)){
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
	}
	
	//new code
	public function getTreeNo(){
		$result = $this->db->query("SELECT t1.random from (SELECT FLOOR(RAND() * 9999) as random FROM `gardendata`) t1 WHERE t1.random NOT IN (SELECT tree_no FROM gardendata WHERE status = '1' AND is_deleted = '0') LIMIT 1")->result_array();

		if($result){
			return $result;
		}else{
			$result[0]['random'] = rand(1000,9999);
			return $result;
		}
	}

	//check for is payable
	public function is_payable($dept_id = null, $role_id = null){
		if($dept_id != '' && $role_id != ''){
			$res = $this->db->query("SELECT payable_status FROM `permission_access` WHERE dept_id = '".$this->dept_id."' AND role_id = '".$role_id."' AND status = '1' ORDER BY access_id DESC limit 1")->result_array();

			if($res){
				return $res;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	//refundable data
	public function getRefundableData($complainId = null, $dept_id = null){
		if($complainId != ''){
			//get Only Hazardous
			
			$res = $this->db->query("SELECT gd.tree_no, gardenId, gd.complain_Id, (SELECT treeName FROM treenames WHERE tree_id = gd.tree_id AND status = '1') treeNames, (CASE WHEN gd.permission_id > 0 THEN (SELECT permission_type FROM garden_permission WHERE garper_id = gd.permission_id AND status = '1') ELSE (SELECT (SELECT permission_type FROM garden_permission WHERE garper_id = tca.permission_type AND status = '1') perm_name FROM treecuttingapplications tca WHERE tca.cutAppId = gd.complain_id AND tca.status = '1') END) permissionType, IF(gd.no_of_trees > 0, gd.no_of_trees, 1) treeNo, (CASE WHEN gd.conditionStatus = '1' THEN 'Hazardous' WHEN gd.conditionStatus = '2' THEN 'Non Hazardous' ELSE '' END) conditionType, gd.reason_permission, gd.enc_image, gd.orig_image, gd.refundable, gd.conditionStatus, gd.refund_approval FROM `gardendata` gd WHERE gd.complain_Id = '".$complainId."' AND gd.status = '1'")->result_array();

			$data['res'] = $res;

			//get final approval role ids

			$finalApprove = $this->db->query("SELECT GROUP_CONCAT(role_id) roleIds FROM `permission_access` WHERE payable_status = '1' AND status = '1' AND dept_id = '".$dept_id."'")->result_array();
			
			//get file approve till
			$lastApprovedBy = $this->db->query("SELECT * FROM `application_remarks` WHERE dept_id = '".$dept_id."' AND app_id = '".$complainId."' AND status = '1' ORDER BY id DESC LIMIT 1")->result_array();
			if(!empty($lastApprovedBy)){
				if($lastApprovedBy[0]['commissionerApproval'] == '1'){
					//get last person role id
					$finalApprove = $this->db->query("SELECT role_id FROM `permission_access` WHERE payable_status = '1' AND status = '1' AND dept_id = '".$dept_id."' ORDER BY access_id DESC limit 1")->result_array();

					if($lastApprovedBy[0]['role_id'] == $finalApprove[0]['role_id']){
						$data['show'] = 1;
					}else{
						$data['show'] = 2;
					}
				}else{
					$finalApprove = $this->db->query("SELECT GROUP_CONCAT(role_id) roleIds FROM `permission_access` WHERE payable_status = '1' AND status = '1' AND dept_id = '".$dept_id."'")->result_array();

					$approvalArray = explode(",",$finalApprove[0]['roleIds']);

					if(in_array($lastApprovedBy[0]['role_id'], $approvalArray)){
						$data['show'] = 1;
					}else{
						$data['show'] = 2;
					}
				}
			}else{
				$data['show'] = 2;
			}	

			if($res){
				return $data;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	public function approveRefund($gardenId = null, $complainId = null, $user_id = null){
		if($gardenId != '' && $complainId != '' && $user_id != ''){
			$res = $this->db->where(array('gardenId' => $gardenId, 'complain_Id' => $complainId))
							->update("gardendata", array('refund_approval' => '1', 'refund_approved_by' => $user_id, 'updated_at' => date('Y-m-d H:i:s')));
			if($res){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	public function approveRefundCancel($complainId = null, $gardenId = null){
		$res = $this->db->where(array('gardenId' => $gardenId, 'complain_Id' => $complainId))
						->update("gardendata", array('refund_approval' => 0, 'refund_approved_by' => 0, 'updated_at' => date('Y-m-d H:i:s')));
		if($res){
			return true;
		}else{
			return false;
		}
	}
	
	//new code dhyey
	public function update_treecutting_master($update_stack , $app_id)
    {
        $this->db->where('cutAppId', $app_id);
        return $this->db->update('treecuttingapplications', $update_stack);
    }
    public function manage_garden_data($recived_data , $garden_id = FALSE)
    {
        if ($garden_id == FALSE) {
            return $this->db->insert('gardendata', $recived_data);
        } else {
            $this->db->where('gardenId',$garden_id);
            return $this->db->update('gardendata', $recived_data);
        }
    }
    
    public function check_approve_by_comm($app_id , $dept_id)
    {
        $this->db->select("(SELECT payable_status FROM permission_access where permission_access.dept_id = application_remarks.dept_id AND permission_access.role_id = application_remarks.role_id AND permission_access.payable_status = 1 LIMIT 1) AS payable , (SELECT role_title FROM roles_table WHERE role_id = application_remarks.role_id LIMIT 1) AS role_title");
        $this->db->from('application_remarks');
        $this->db->where('application_remarks.app_id',$app_id);
        $this->db->where('application_remarks.status',1);
        $this->db->where('application_remarks.dept_id',$dept_id)->order_by('application_remarks.id','desc')->limit(1);
        return $this->db->get()->row();
    }

	
}

?>
