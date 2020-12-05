<?php 

Class Add_tree_table extends CI_Model{

	function __construct() {
        // Set table name
        $this->table = 'treenames';

    }


    //Get All Data
    public function getAllData(){
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

    //Insert data
    public function insertTree($treeData = null){
    	// print_r($treeData);exit;
    	if($treeData != ''){
    		$result = $this->db->insert($this->table,$treeData);
    		if($result) {
				return $this->db->insert_id();
			} else {
				return null;
			}
    	}else{
    		return null;
    	}
    	
    }

    //delete tree
    public function deleteTree($treeId = null, $status = null, $actualStatus = null){
    	
    	if($treeId != ''){
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
							->where('tree_id', $treeId)	
							->update($this->table, $data);

			if($result){
				return true;
			}else{
				return null;
			}			
    	}
    }

    //update tree
    public function treeEdit($data = null, $treeId = null){
    	$result = $this->db
							->where('tree_id', $treeId)	
							->update($this->table, $data);
				
		if($result) {
			return true;
		} else {
			return null;
		}
    }

    public function getTreeById($appId = '')
    {
        $query = $this->db->select('*')
                            ->from($this->table)
                            ->where(array('is_deleted' => '0','tree_id' => $appId))
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