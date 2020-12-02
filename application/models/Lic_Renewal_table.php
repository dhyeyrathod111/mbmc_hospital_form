<?php 

Class Lic_Renewal_table extends CI_Model{

    //set column field database for datatable orderable
        var $column_order = array(null,'form_no','license_no','licName','name','stall_address','renewalDate','expiryDate','expiryLicenseId','aadharId','panId','remarks', 'created_date', null);

        //set column field database for datatable searchable 
        var $column_search = array('form_no' , 'license_no' , 'licName' , 'name' , 'stall_address' , 'renewalDate' , 'expiryDate' , 'expiryLicenseId' , 'aadharId' , 'panId' , 'remarks' , 'created_date');

        var $column_order1 = array(null, 'lic_name', null, null);

        var $column_search1 = array('name');

	function __construct() {
        // Set table name
        $this->table = 'temp_lic';

        $session_userdata = $this->session->userdata('user_session');
            // print_r($session_userdata);exit;
        $this->role_id = $session_userdata[0]['role_id'];
        $this->dept_id = $session_userdata[0]['dept_id'];

    }
 
    function getAppData($searchVal = null, $fromDate = null, $toDate = null, $approval = null, $approvalStatus = null, $i = null, $rowperpage = null, $columnIndex = null, $columnName = null, $columnSortOrder = null){
    	// $query = $this->db->select('*')
    	// 				  ->from($this->table)
    	// 				  ->where(array('is_deleted' => '0', 'status' => '1'))
    	// 				  ->get()
    	// 				  ->result_array();

        $approvalCondn = "";
        switch($approval){
            case '0':
                    $approvalCondn = "(tl.status = '1' OR tl.status = '2')";
                break;
            case '1':
                    $approvalCondn = "tl.status = '1'";
                break;
            case '2':
                    $approvalCondn = "tl.status = '2'";
                break;
        }

        //for date condition
        $dateCond = "";
        if($fromDate != '' && $toDate != ''){
            $dateCond = "AND date(tl.created_date) >= '".$fromDate."' AND date(tl.created_date) <= '".$toDate."'";
        }

        //approvalstatus condn
        $appStatusCond = "";

        if($approvalStatus != ''){
           if($approvalStatus != '0'){
            $appStatusCond = " WHERE temp.status_id = '".$approvalStatus."'";
           }
        }

        if($columnName == 'sr_no'){
            $columnName = "temp.lic_id";
        }else{
            $columnName = "temp.".$columnName;
        }

        if($searchVal != ''){
            $query = $this->db->query("SELECT temp.* FROM (SELECT tl.*, (SELECT lic_name FROM lic_type WHERE lic_type_id = tl.lic_type) licName, (SELECT renewalDate FROM `licdates` WHERE lic_id = tl.lic_id AND status = '1') renewalDate, (SELECT GROUP_CONCAT(file_name) FROM lic_data WHERE temp_lic_id = tl.lic_id AND status = 0) filename, (SELECT license_no FROM `licdates` WHERE lic_id = tl.lic_id AND status = '1') license_no, (SELECT expiryDate FROM `licdates` WHERE lic_id = tl.lic_id AND status = '1') expiryDate, (SELECT data_id FROM `lic_data` WHERE temp_lic_id = tl.lic_id AND type = '1' and status  = 0 ORDER BY data_id DESC) expiryLicenseId, (SELECT file_name FROM `lic_data` WHERE temp_lic_id = tl.lic_id AND type = '1' and status  = 0 ORDER BY data_id DESC) expiryLicenseFile, (SELECT file_name FROM `lic_data` WHERE temp_lic_id = tl.lic_id AND type = '2' and status  = 0 ORDER BY data_id DESC) aadharFile, (SELECT file_name FROM `lic_data` WHERE temp_lic_id = tl.lic_id AND type = '3' and status  = 0 ORDER BY data_id DESC) panFile, (SELECT status_title FROM app_status_level WHERE status_id = (SELECT status_id FROM application_remarks WHERE app_id = tl.lic_id AND dept_id = '".$this->dept_id."' ORDER BY id DESC limit 1)) remarks, (SELECT status_id FROM application_remarks WHERE app_id = tl.lic_id AND dept_id = '".$this->dept_id."' ORDER BY id DESC limit 1) status_id FROM `temp_lic` tl WHERE ".$approvalCondn.' '.$dateCond." AND tl.is_deleted = 0) temp WHERE (temp.form_no like '%$searchVal%' OR temp.licName like '%$searchVal%' OR temp.license_no like '%$searchVal%' OR temp.name like '%$searchVal%' OR temp.renewalDate like '%$searchVal%' OR temp.expiryDate like '%$searchVal%')".$appStatusCond." order by ".$columnName." ".$columnSortOrder." limit ".$i.",".$rowperpage)->result_array();
        }else{

            $query = $this->db->query("SELECT temp.* FROM (SELECT tl.*, (SELECT lic_name FROM lic_type WHERE lic_type_id = tl.lic_type) licName, (SELECT renewalDate FROM `licdates` WHERE lic_id = tl.lic_id AND status = '1') renewalDate, (SELECT GROUP_CONCAT(file_name) FROM lic_data WHERE temp_lic_id = tl.lic_id AND status = 0) filename, (SELECT license_no FROM `licdates` WHERE lic_id = tl.lic_id AND status = '1') license_no, (SELECT expiryDate FROM `licdates` WHERE lic_id = tl.lic_id AND status = '1') expiryDate, (SELECT data_id FROM `lic_data` WHERE temp_lic_id = tl.lic_id AND type = '1' and status  = 0 ORDER BY data_id DESC) expiryLicenseId, (SELECT file_name FROM `lic_data` WHERE temp_lic_id = tl.lic_id AND type = '1' and status  = 0 ORDER BY data_id DESC) expiryLicenseFile, (SELECT file_name FROM `lic_data` WHERE temp_lic_id = tl.lic_id AND type = '2' and status  = 0 ORDER BY data_id DESC) aadharFile, (SELECT file_name FROM `lic_data` WHERE temp_lic_id = tl.lic_id AND type = '3' and status  = 0 ORDER BY data_id DESC) panFile, (SELECT status_title FROM app_status_level WHERE status_id = (SELECT status_id FROM application_remarks WHERE app_id = tl.lic_id AND dept_id = '".$this->dept_id."' ORDER BY id DESC limit 1)) remarks, (SELECT status_id FROM application_remarks WHERE app_id = tl.lic_id AND dept_id = '".$this->dept_id."' ORDER BY id DESC limit 1) status_id FROM `temp_lic` tl WHERE ".$approvalCondn.' '.$dateCond." AND tl.is_deleted = 0) temp".$appStatusCond." order by ".$columnName." ".$columnSortOrder." limit ".$i.",".$rowperpage)->result_array();
        }
    	
    	// echo $this->db->last_query();
    	// print_r($query);exit;				  
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

    function appDataById($appId = null){
        $query = $this->db->select('*')
                            ->from($this->table)
                            ->where(array('is_deleted' => '0','lic_id' => $appId))
                            ->get()
                            ->result_array();

        if($query){
            return $query;
        }else{
            return false;
        }
    }

    function getLicType(){
    	$query1 = $this->db->select('*')
    				  ->from('lic_type')
    				  ->where(array('is_deleted'=>'0', 'status' => '1'))
    				  ->get()
    				  ->result_array();

    	if($query1){
    		return $query1;
    	}else{
    		return null;
    	}
    }

    function insertDate($dateArray = null){
    	$query = $this->db->insert('licdates', $dateArray);

    	if($query){
    		return true;
    	}else{
    		return false;
    	}
    }

    function getLicType1(){
    	$query1 = $this->db->select('*')
    				  ->from('lic_type')
    				  ->where(array('is_deleted'=>'0'))
    				  ->get()
    				  ->result_array();

    	if($query1){
    		return $query1;
    	}else{
    		return null;
    	}
    }

    function insertType($name){
    	$insArray = array(
    		'lic_name' => $name,
    		'date_added' => date("Y-m-d H:i:s")
    	);

    	$query = $this->db->insert('lic_type', $insArray);

    	if($query){
    		return true;
    	}else{
    		return false;
    	}
    }

    function updateLic($licName, $licId){
    	$updateArr = array(
    		'lic_name' => $licName,
    		'date_added' => date("Y-m-d H:i:s")
    	);

    	$this->db->where('lic_type_id', $licId)
    			 ->update('lic_type', $updateArr);

    	return true;
    }

    function deleteLic($licId){
    	$udpArr = array('is_deleted' => '1');

    	$this->db->where('lic_type_id', $licId)
    			 ->update('lic_type', $udpArr);

    	return true;
    }

    function deactivateLic($lic_type_id, $licStatus){

    	if($licStatus == 1){
    		$udpArr = array('status' => '2');
    	}else{
    		$udpArr = array('status' => '1');
    	}

    	// print_r($udpArr);echo $lic_type_id;exit;

    	$this->db->where('lic_type_id', $lic_type_id)
    			 ->update('lic_type', $udpArr);

    	return true;
    }

    // function LastAppId(){
    // 	$query = $this->db->select('*')
    // 						->limit(1)
    // 						->order_by('lic_id', "DESC")
    // 						->get($this->table)
    // 						->row();
    // 	// print_r($query);exit;
    // 	if(!empty($query)){
    // 		return $query;
    // 	}else{
    // 		return null;
    // 	}
    // }

    function createLic($licArray = null){
    	$this->db->insert('temp_lic', $licArray);

    	$insertedId = $this->db->insert_id();

    	if($insertedId != ''){
    		return $insertedId;
    	}else{
    		return null;
    	}
    }

    function insertLic($licData = null, $licId = null, $columnName = null){
    	$this->db->insert('lic_data', $licData);

    	$insertedId = $this->db->insert_id();

    	if($columnName != ''){
    		$udpArr = array($columnName => $insertedId);

	    	$this->db->where('lic_id', $licId)
	    			 ->update('temp_lic', $udpArr);
    	}

    	if($insertedId != ''){
    		return $insertedId;
    	}else{
    		return null;
    	}
    }

    function renewDate($lic_id = null, $insArray = null){

    	$this->db->where('lic_id', $lic_id)
    			 ->update('licdates', array('status'=>'2'));

    	$this->db->insert('licdates', $insArray);

    	$lastId = $this->db->insert_id();

    	if($lastId > 0){
    		return true;
    	}else{
    		return false;
    	}
    }

    function deleteApp($licId = null, $actk = null){
    	if($actk == '1'){
    		//delete
    		//temp_lic, lic_data, licdates

    		$this->db->where('lic_id', $licId)->update('temp_lic', array('status'=>'2'));
    		$this->db->where('lic_id', $licId)->update('licdates', array('status'=>'2'));
    		$this->db->where('temp_lic_id', $licId)->update('lic_data', array('status'=>'1'));

    		return true;
    	}else{
    		//active

    		$this->db->where('lic_id', $licId)->update('temp_lic', array('status'=>'1'));
    		$this->db->where('lic_id', $licId)->update('licdates', array('status'=>'1'));
    		$this->db->where('temp_lic_id', $licId)->update('lic_data', array('status'=>'0'));
    		return true;
    	}
    }

    function AppDetailsById($appId = null){
  		$query = $this->db->query("SELECT tl.*, (SELECT lic_name FROM lic_type WHERE lic_type_id = tl.lic_type) licName, (SELECT renewalDate FROM `licdates` WHERE lic_id = tl.lic_id) renewalDate, (SELECT GROUP_CONCAT(file_name) FROM lic_data WHERE temp_lic_id = tl.lic_id AND status = 0 AND type = '1') earlierLic, (SELECT GROUP_CONCAT(file_name) FROM lic_data WHERE temp_lic_id = tl.lic_id AND status = 0 AND type = '2') aadhar, (SELECT GROUP_CONCAT(file_name) FROM lic_data WHERE temp_lic_id = tl.lic_id AND status = 0 AND type = '3') pan,(SELECT license_no FROM `licdates` WHERE lic_id = tl.lic_id AND status = '1') license_no, (SELECT expiryDate FROM `licdates` WHERE lic_id = tl.lic_id) expiryDate FROM `temp_lic` tl WHERE (tl.status = 1 OR tl.status = 2) AND tl.lic_id = '".$appId."' AND tl.is_deleted = 0")->result_array();
  		
    	// echo $this->db->last_query();
    	// print_r($query);exit;				  
    	if($query){
    		return $query;
    	}else{
    		return null;
    	}  	
    }

    function editLic($data = null, $status = null, $lic_id = null, $statusImages = null){

    	if($statusImages == 1){
    		$this->db->where("lic_id" , $lic_id)
    				 ->update('temp_lic', $data['tempArray']);

            if($status == 2)
            {
                $this->db->where('lic_id', $lic_id)
                     ->update('licdates', array('status'=>'2'));

                $this->db->insert('licdates', array($data['insertNew']));

                return $this->db->insert_id();
            }         
    		
    		return true;

    	}elseif ($statusImages == 2) {
    		$this->db->where('lic_id', $lic_id)
                 ->update('temp_lic', $data['tempArray']);

            if($status == 2)
            {
                $this->db->where('lic_id', $lic_id)
                     ->update('licdates', array('status'=>'2'));

                $this->db->insert('licdates', array($data['insertNew']));
            }    

            // echo "<pre>";
            // print_r($data);exit;

            foreach($data['images'] as $key => $value){
                if($value != ''){
                    if($key == 0){
                        $this->db->where(array('temp_lic_id'=>$lic_id, 'type'=>'1'))
                                 ->update('lic_data', array('status'=>'1'));

                        $insertArray = array(
                            'temp_lic_id' => $lic_id,
                            'type' => '1',
                            'file_name' => $value['file_name'],
                            'path' => $value['file_path'],
                            'date_added' => date("Y-m-d H:i:s")
                        );

                        $this->db->insert('lic_data', $insertArray);
                    }elseif ($key == 1) {
                        $this->db->where(array('temp_lic_id'=>$lic_id, 'type'=>'2'))
                                 ->update('lic_data', array('status'=>'1'));

                        $insertArray = array(
                            'temp_lic_id' => $lic_id,
                            'type' => '2',
                            'file_name' => $value['file_name'],
                            'path' => $value['file_path'],
                            'date_added' => date("Y-m-d H:i:s")
                        );
                        $this->db->insert('lic_data', $insertArray);
                    }elseif ($key == 2) {
                        $this->db->where(array('temp_lic_id'=>$lic_id, 'type'=>'3'))
                                 ->update('lic_data', array('status'=>'1'));

                        $insertArray = array(
                            'temp_lic_id' => $lic_id,
                            'type' => '3',
                            'file_name' => $value['file_name'],
                            'path' => $value['file_path'],
                            'date_added' => date("Y-m-d H:i:s")
                        );
                        $this->db->insert('lic_data', $insertArray);
                    }
                }
            }

            return true;
    	}
    }

    function getLicTypeById($appId = null){
        $res = $this->db->query("SELECT * FROM `lic_type` WHERE lic_type_id = '$appId'")->result_array();

        return $res;
    }

    //licType
    public function countAll(){
        $this->db->from('lic_type');
        return $this->db->count_all_results();
    }

    public function countFiltered1($postData){
        $this->_get_datatables_query1($postData);
        $query = $this->db->get();
        return $query->num_rows();
    }

    private function _get_datatables_query1($postData){
             
            $this->db->query("SELECT * FROM lic_type WHERE status = '1' OR status = '2' AND is_deleted = '0'");
                
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
}
?>