<?php 
	class Image_details_table extends CI_Model {
		
		public $table = 'image_details';


		public function getImageDetailsById($image_id = null) {
			// echo'<pre>';print_r($image_id);exit;
			$query = $this->db->select('*')
							->from($this->table)
							->where(array('image_id'=>$image_id,'is_deleted'=>'0'))
							->get()
							->row_array();
			// echo'ss<pre>';print_r($query);exit;
			if($query) {
				return $query;
			} else {
				return null;
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
