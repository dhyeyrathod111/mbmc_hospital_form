<?php

Class Advertisement_table extends CI_Model{

	//set column field database for datatable orderable
        var $column_order = array(null,'form_no','name','hoarding_location_address','type_of_adv','illuminate','hoarding_length','hoarding_breadth','height_of_road','type_of_request','pancard','aadhar_card', 'society_notice_status', 'owner_hoarding_name', 'owner_noc_status', 'hoarding_location', 'adv_start_date', 'no_adv_days', 'end_date', 'rate', 'amount', 'applicatio_date', null, null);

        //set column field database for datatable searchable 
        var $column_search = array('form_no','name','hoarding_location_address','type_of_adv','illuminate','hoarding_length','hoarding_breadth','height_of_road','type_of_request','pancard','aadhar_card', 'society_notice_status', 'owner_hoarding_name', 'owner_noc_status', 'hoarding_location', 'adv_start_date', 'no_adv_days', 'end_date', 'rate', 'amount', 'applicatio_date');

        var $coloumn_order1 = array(null, 'name', null, null);

        var $column_search1 = array('name');

        var $coloumn_orderill = array(null, 'name', null, null);

        var $column_searchill = array('name');

	function __construct(){
		$this->tableMain = "advertisement_applications";
		$this->tableAdv = "adv_type";

		$session_userdata = $this->session->userdata('user_session');
            // print_r($session_userdata);exit;
        $this->role_id = $session_userdata[0]['role_id'];
        $this->dept_id = $session_userdata[0]['dept_id'];
	}

	function adv_type(){
		$query = $this->db->query("SELECT * FROM `".$this->tableAdv."` WHERE status = '1'")->result_array();

		return $query;
	}

	function illuminate(){
		$query = $this->db->query("SELECT * FROM `illuminate` WHERE status = '1'")->result_array();

		return $query;
	}

	function req_type(){
		$query = $this->db->query("SELECT * FROM `req_type` WHERE status = '1'")->result_array();

		return $query;
	}

	function insert($insertArray = null){
		$res = $this->db->insert($this->tableMain, $insertArray);

		if($res){
			return $this->db->insert_id();
		}else{
			return null;
		}
	}

	function getAppData($searchVal = null, $fromDate = null, $toDate = null, $approval = null, $approvalStatus = null){

		$approvalCondn = "";
        switch($approval){
            case '0':
                    $approvalCondn = "(aa.status = '1' OR aa.status = '2')";
                break;
            case '1':
                    $approvalCondn = "aa.status = '1'";
                break;
            case '2':
                    $approvalCondn = "aa.status = '2'";
                break;
        }

        //for date condition
        $dateCond = "";
        if($fromDate != '' && $toDate != ''){
            $dateCond = "AND date(aa.application_date) >= '".$fromDate."' AND date(aa.application_date) <= '".$toDate."'";
        }

        //approvalstatus condn
        $appStatusCond = "";

        if($approvalStatus != ''){
           if($approvalStatus != '0'){
            $appStatusCond = " WHERE adv.status_id = '".$approvalStatus."'";
           }
        }

		if($searchVal != ''){
			$query = $this->db->query("SELECT adv.* FROM (SELECT aa.*, (SELECT status_title FROM app_status_level WHERE status_id = (SELECT status_id FROM application_remarks WHERE app_id = aa.adv_id AND dept_id = '".$this->dept_id."' ORDER BY id DESC limit 1)) remarks, (SELECT status_id FROM application_remarks WHERE app_id = aa.adv_id AND dept_id = '".$this->dept_id."' ORDER BY id DESC limit 1) status_id FROM `advertisement_applications` aa WHERE (aa.form_no like '%$searchVal%' OR aa.name like '%$searchVal%' OR aa.hoarding_length like '%$searchVal%' OR aa.hoarding_breadth like '%$searchVal%' OR aa.height_of_road like '%$searchVal%' OR aa.hoarding_location like '%$searchVal%' OR aa.adv_start_date like '%$searchVal%' OR aa.no_adv_days like '%$searchVal%' OR aa.end_date like '%$searchVal%' OR aa.rate like '%$searchVal%' OR aa.amount like '%$searchVal%') AND ".$approvalCondn." AND aa.is_deleted = '0' ".$dateCond.") adv".$appStatusCond)->result_array();
		}else{
			$query = $this->db->query("SELECT adv.* FROM (SELECT aa.*, (SELECT name FROM `adv_type` WHERE adv_id = aa.type_of_adv) adv_type, (SELECT name FROM `illuminate` WHERE ill_id = aa.illuminate) ill_name, (SELECT status_title FROM app_status_level WHERE status_id = (SELECT status_id FROM application_remarks WHERE app_id = aa.adv_id AND dept_id = '".$this->dept_id."' ORDER BY id DESC limit 1)) remarks, (SELECT status_id FROM application_remarks WHERE app_id = aa.adv_id AND dept_id = '".$this->dept_id."' ORDER BY id DESC limit 1) status_id FROM `advertisement_applications` aa WHERE ".$approvalCondn." AND aa.is_deleted = '0' ".$dateCond.") adv".$appStatusCond)->result_array();
		}

		if($query){
			return $query;
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

        function deleteApp($adv_id = null, $actk = null){
	    	if($actk == '1'){
	    		//delete
	    		//temp_lic, lic_data, licdates

	    		$this->db->where('adv_id', $adv_id)->update('advertisement_applications', array('status'=>'2'));
	    		return true;

	    	}else{
	    		//active

	    		$this->db->where('adv_id', $adv_id)->update('advertisement_applications', array('status'=>'1'));
	    		return true;
	    	}
	    }

	    function getLicTypeById($appId = null){
	    	$res = $this->db->query("SELECT * FROM `advertisement_applications` WHERE adv_id = '$appId'")->result_array();

        	return $res;
	    }

	    function updateAdv($updateArray = null, $adv_id = null){
	    	if($adv_id != ''){
	    		$this->db->where('adv_id', $adv_id)
	    				 ->update($this->tableMain, $updateArray);
	    		return true;		 
	    	}else{
	    		return null;
	    	}
	    }

        //adv type
        function getAdvType(){
            $query = $this->db->query("SELECT * FROM ".$this->tableAdv." WHERE (status = '1' OR status = '2')")->result_array();

            return $query;
        }

        public function countAll(){
            $this->db->from('adv_type');
            return $this->db->count_all_results();
        }

        public function countFiltered1($postData){
            $this->_get_datatables_query1($postData);
            $query = $this->db->get();
            return $query->num_rows();
        }

        private function _get_datatables_query1($postData){
                 
            $this->db->from("adv_type");
                
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

        function deactivateAdv($appId = null, $status = null){

            if($status == '1'){
                $data = array(
                    'status' => 2 
                );
            }else{
                $data = array(
                    'status' => 1 
                );
            }

            $query2 = $this->db
                              ->where('adv_id', $appId)
                              ->update('adv_type', $data);

            if($query2){
                return true;
            }else{
                return false;
            }                 
        }

        function insertAdv($insArray = null){
            $res = $this->db->insert($this->tableAdv, $insArray);

            if($res){
                return $this->db->insert_id();
            }else{
                return null;
            }
        }

        function getAdvTypeById($appId = null){
            $res = $this->db->query("SELECT * FROM adv_type WHERE adv_id = '".$appId."'")->result_array();

            if($res){
                return $res;
            }else{
                return null;
            }
        }

        function editData($upArray = null, $adv_id = null){
            $res = $this->db->where('adv_id', $adv_id)
                     ->update($this->tableAdv, $upArray);

            if($res){
                return true;
            }else{
                return false;
            }
        }
        //end adv type

        //illuminate

        function getIlluminate()
        {
            $res = $this->db->query("SELECT * FROM illuminate WHERE status = '1' OR status = '2'")->result_array();

            return $res;
        }

        public function countAllill()
        {
            $this->db->from('illuminate');
            return $this->db->count_all_results();
        }

        public function countFilteredill($postData){
            $this->_get_datatables_queryill($postData);
            $query = $this->db->get();
            return $query->num_rows();
        }

        private function _get_datatables_queryill($postData){
             
            $this->db->from('illuminate');
     
            $i = 0;
            // loop searchable columns 
            foreach($this->column_searchill as $item){
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
                    if(count($this->column_searchill) - 1 == $i){
                        // close bracket
                        $this->db->group_end();
                    }
                }
                
                $i++;
            }
             
            if(isset($postData['order'])){
                $this->db->order_by($this->column_orderill[$postData['order']['0']['column']], $postData['order']['0']['dir']);
            }else if(isset($this->order)){
                $order = $this->order;
                $this->db->order_by(key($order), $order[key($order)]);
            }
        }

        public function deactivateill($ill_id = null, $status = null)
        {
            if($status == '1'){
                $data = array(
                    'status' => 2 
                );
            }else{
                $data = array(
                    'status' => 1 
                );
            }

            $query2 = $this->db
                              ->where('ill_id', $ill_id)
                              ->update('illuminate', $data);

            if($query2){
                return true;
            }else{
                return false;
            }
        }

        public function illInsert($insArray = null){
            $res = $this->db->insert('illuminate', $insArray);

            if($res){
                return $this->db->insert_id();
            }else{
                return false;
            }
        }

        public function getillById($app_id = null){
            $res = $this->db->query("SELECT * FROM illuminate WHERE ill_id = '".$app_id."'")->result_array();

            if($res){
                return $res;
            }else{
                return null;
            }
        }

        public function editIll($upArray = null, $ill_id = null){
            $res = $this->db->where('ill_id', $ill_id)
                            ->update('illuminate', $upArray);

            if($res){
                return true;
            }else{
                return false;
            }

        }
        //End illuminate
}