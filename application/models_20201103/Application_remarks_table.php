<?php 
	class Application_remarks_table extends CI_Model {
		
		function __construct() {
	        // Set table name
	        $this->table = 'application_remarks';
	    }

	    public function getAllRemarksById($id = null) {

	    	$session_userdata = $this->session->userdata('user_session');
			// print_r($session_userdata);exit;
			$role_id = $session_userdata[0]['role_id'];
			$dept_id = $session_userdata[0]['dept_id'];

			$query = $this->db->select('remarks.*,users.user_name')
							->from("$this->table as remarks")
							->order_by("remarks.id", "desc")
							->join('users_table as users','remarks.user_id = users.user_id')
							->where(array('remarks.app_id'=> $id,'remarks.is_deleted'=>'0', 'remarks.dept_id' => $dept_id))
							->get()
							->result_array();
							// echo'<pre>';print_r($this->db->last_query());exit;
			if($query) {
				return $query;
			} else {
				return null;
			}
		}

		public function insert($data = null) {
			// echo'ss<pre>';print_r($data);exit;
			if($data != null) {
				$result = $this->db->insert($this->table,$data);
				
				if($result) {
					return $this->db->insert_id();
				} else {
					return null;
				}
			} else {
				return null;
			}
		}

		public function update($data = null,$id = null) {
			if($data != null) {
				$result = $this->db
							->where('id', $id)	
							->update($this->table, $data);
				
				if($result) {
					return $this->db->insert_id();
				} else {
					return null;
				}
			} else {
				return null;
			}
		}
		
		public function updateAllPrevious($dept_id = null, $role_id = null,$app_id=null){
			if($dept_id != '' && $role_id != ''){
				$res = $this->db->where(array("dept_id" => $dept_id, "role_id" => $role_id,"app_id"=>$app_id))->update($this->table, array("status" => '2'));
				if($res){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
	}

?>
