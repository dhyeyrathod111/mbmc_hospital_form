<?php 
	class Auth_sessions_table extends CI_Model {
		
		public $table = 'auth_sessions';

		public function insert($data = null) {
			
			if($data != null) {
				$result = $this->db->insert($this->table,$data);
				
				if($result) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}

		public function update($data = null) {
			// echo'aaa<pre>';print_r($data);exit;
			if($data != null) {
				$result = $this->db->update($this->table,$data);
				
				if($result) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
	}

?>
