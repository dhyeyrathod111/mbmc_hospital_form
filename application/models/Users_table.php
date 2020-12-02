<?php 
	class Users_table extends CI_Model {
		
		function __construct() {
	        // Set table name
	        $this->table = 'users_table';

	        // Set orderable column fields
	        $this->column_order = array(null,'user_id','user_name','user_mobile','email_id','dept_title','role_title','users.status',null);

	        // Set searchable column fields
	        $this->column_search = array('user_id','user_name','user_mobile','email_id','dept_title','role_title','users.status');

	        // Set default order
	        $this->order = array('user_id' => 'desc');
	    }

		public function getAllUsers() {
	    	// echo'<pre>';print_r('hihi');exit;
			$query = $this->db->select('*')
							->from($this->table)
							->where('is_deleted','0')
							->get()
							->result_array();

			// echo'<pre>';print_r($query);exit;
			if($query) {
				return $query;
			} else {
				return null;
			}
		}

		public function getAllUsersByrolesDept() {
	    	// echo'<pre>';print_r('hihi');exit;
			$query = $this->db->select('users.*,role.role_title,dept.dept_title')
							->from("$this->table as users")
							->join('roles_table AS role', 'users.role_id = role.role_id')
							->join('department_table AS dept', 'dept.dept_id = users.dept_id')
							->where('users.is_deleted','0')
							->get()
							->result_array();
			// echo'<pre>';print_r($query);exit;
			// echo'<pre>';print_r($this->db->last_query());exit;
			if($query) {
				return $query;
			} else {
				return null;
			}
		}

		public function getUserdetailsById($user_id = null) {
	    	// echo'<pre>';print_r('hihi');exit;
			$query = $this->db->select('users.*,role.role_title,dept.dept_title')
							->from("$this->table as users")
							->join('roles_table AS role', 'users.role_id = role.role_id')
							->join('department_table AS dept', 'dept.dept_id = users.dept_id')
							->where(array('users.is_deleted' => '0','users.user_id' => $user_id))
							->get()
							->row_array();
			// echo'<pre>';print_r($query);exit;
			// echo'<pre>';print_r($this->db->last_query());exit;
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
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}

		public function update($data = null,$where = null) {
			// echo'aaa<pre>';print_r($data);exit;
			if($data != null) {
				$this->db->where('user_id',$where);
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

		public function check_email($email = null, $loginType = null) {
			if($email != null) {
				// $result = $this->db->select('*')
				// 			->from($this->table ut)
				// 			->where(array('email_id' => $email,'is_user' => $loginType))
				// 			->get()
				// 			->result_array();
				$result = $this->db->query("SELECT ut.*, (SELECT is_superadmin FROM roles_table WHERE role_id = ut.role_id) is_superadmin FROM `".$this->table."` ut WHERE ut.email_id = '".$email."' AND ut.is_user = '".$loginType."'")->result_array();

                // echo $this->db->last_query();exit;
				// echo'<pre>';print_r($result);exit;
				if($result) {
					return $result;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}

		 /*
	     * Fetch members data from the database
	     * @param $_POST filter data based on the posted parameters
	     */
	    public function getRows($postData) {
	        $this->_get_datatables_query($postData);
	        if($postData['length'] != -1){
	            $this->db->limit($postData['length'], $postData['start']);
	        }
	        $query = $this->db->get();
	        return $query->result_array();
	    }
	    
	    /*
	     * Count all records
	     */
	    public function countAll() {
	        $this->db->select('users.*,role.role_title,dept.dept_title')
				->from("$this->table as users")
				->join('roles_table AS role', 'users.role_id = role.role_id')
				->join('department_table AS dept', 'dept.dept_id = users.dept_id');
	        return $this->db->count_all_results();
	    }
	    
	    /*
	     * Count records based on the filter params
	     * @param $_POST filter data based on the posted parameters
	     */
	    public function countFiltered($postData){
	        $this->_get_datatables_query($postData);
	        $query = $this->db->get();
	        return $query->num_rows();
	    }
	    
	    /*
	     * Perform the SQL queries needed for an server-side processing requested
	     * @param $_POST filter data based on the posted parameters
	     */
	    private function _get_datatables_query($postData){
	         
	        $this->db->select('users.*,role.role_title,dept.dept_title')
				->from("$this->table as users")
				->join('roles_table AS role', 'users.role_id = role.role_id')
				->join('department_table AS dept', 'dept.dept_id = users.dept_id');
	 
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
	    
	    //channges dhey
	    public function updateUserDept($user_id , $dept_id)
	    {
	    	$this->db->set('dept_id',$dept_id);
	    	$this->db->where('user_id', $user_id);
	    	return $this->db->update('users_table');
	    }
	    //End changes
	    
	    public function register_save($insertArray = null){
	       if(!empty($insertArray)){
	           $res = $this->db->insert("users_table", $insertArray);
	           
	           if($res){
	               return true;
	           }else{
	               return false;
	           }
	       }else{
	           return false;
	       }
		}
		
		//new code
		public function getRolesByDept($dept_id = null){
			if($dept_id != '0'){
				$res = $this->db->query("SELECT pa.role_id, (SELECT role_title FROM roles_table WHERE role_id = pa.role_id AND status = '1') roleTitle FROM `permission_access` pa WHERE pa.dept_id = '".$dept_id."' AND pa.status = '1'")->result_array();
			}else{
				// $res = $this->db->query("")->result_array();
			}
			

			if(!empty($res)){
				return $res;
			}else{
				return false;
			}
		}
		public function getUserByEmailID($email_id)
	    {
	    	$this->db->select('*');
			$this->db->from('users_table');
			$this->db->where('email_id',$email_id);
			return $this->db->get()->row();
	    }
	    public function update_user($update_user_stack,$email_id)
	    {
	    	$this->db->where('email_id', $email_id);
			return $this->db->update('users_table', $update_user_stack);
	    }
	    public function update_stack_by_keygen($update_user_stack,$access_key)
	    {
	    	$this->db->where('user_keygen', $access_key);
			return $this->db->update('users_table', $update_user_stack);
	    }
	    public function get_user_by_keygen($access_key)
	    {
	    	$this->db->select('*');
			$this->db->from('users_table');
			$this->db->where('user_keygen',$access_key);
			return $this->db->get()->row();
	    }
	    public function getWordForUsers($condition_payload)
	    {
	    	$this->db->select('*');
			$this->db->from('ward');
			$this->db->where($condition_payload);
			return $this->db->get()->result();
	    }
	}

?>
