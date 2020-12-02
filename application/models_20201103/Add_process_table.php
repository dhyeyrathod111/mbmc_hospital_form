<?php 

Class Add_process_table extends CI_Model{

	function __construct() {
        // Set table name
        $this->table = 'treecuttingprocess';

    }

    //get all data
    public function getallData(){
    	$query = $this->db->select('*')
							->from($this->table)
							->where('is_deleted','0')
							->get()
							->result_array();
		if($query){
			return $query;
		}else{
			return false;
		}
    }

    //create Process
    public function insertProcess($processData = null){
    	if(!empty($processData)){
    		$result = $this->db->insert($this->table,$processData);
    		if($result) {
				return $this->db->insert_id();
			} else {
				return null;
			}
    	}else{
    		return null;
    	}
    }

    //delete process
    public function deleteProcess($processId = null, $status = null, $actualStatus = null){
    	if($processId != ''){
    		if($status == 1){
    			//delete
    			$data = array(
	    			'is_deleted' => '1'
	    		);
    		}else{
    			//change status
                if($actualStatus == 1){
                    $data = array(
                       'status' => '2'
                    );
                }else{
                    $data = array(
                       'status' => '1'
                    );    
                }
    		}
    		

    		$result = $this->db
							->where('processId', $processId)	
							->update($this->table, $data);

			if($result){
				return true;
			}else{
				return null;
			}			
    	}
    }

    public function processEdits($data = null, $processId = null){
        $result = $this->db
                            ->where('processId', $processId) 
                            ->update($this->table, $data);
                
        if($result) {
            return true;
        } else {
            return null;
        }
    }

    public function getProcessById($appId = '')
    {
        $query = $this->db->select('*')
                            ->from($this->table)
                            ->where(array('is_deleted' => '0','processId' => $appId))
                            ->get()
                            ->result_array();

        if($query){
            return $query;
        }else{
            return false;
        }
    }
}

?>