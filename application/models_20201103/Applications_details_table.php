<?php 
	class Applications_details_table extends CI_Model {
		
		public $table = 'applications_details';

		public function getLastId() {
	    	// echo'<pre>';print_r('hihi');exit;
			$query = $this->db->select('application_id')
							->from($this->table)
							->where(array('status' => '1','is_deleted'=> '0'))
							->order_by("application_id", "desc")
							->limit('1')
							->get()
							->row_array();
			// $sql = $this->db->last_query();
			// echo'<pre>';print_r($sql);exit;
		 	// echo'<pre>';print_r($query);exit;
			if($query) {
				return $query;
			} else {
				return false;
			}
		}

		public function getDeptId($app_id = null) {
			$query = $this->db->select('dept_id')
							->from($this->table)
							->where(array('application_id'=> $app_id,'status' => '1','is_deleted'=> '0'))
							->get()
							->row_array();
			if($query) {
				return $query;
			} else {
				return false;
			}
		}

		public function insert($data = null) {
			
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

		public function update($data = null,$app_id = null) {
			// echo'aaa<pre>';print_r($app_id);exit;
			if($data != null) {
				$this->db->where('application_id',$app_id);
				$result = $this->db->update($this->table,$data);
				// echo'<pre>';print_r($this->db->last_query());exit;
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
