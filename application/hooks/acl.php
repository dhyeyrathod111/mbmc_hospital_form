    <?php
    /**
     * This class will be called by the post_controller_constructor hook and act as ACL
     * 
     * @author ChristianGaertner
     */
    require_once( BASEPATH .'database/DB'. EXT );
    class ACL {
        
        /**
         * Array to hold the rules
         * Keys are the role_id and values arrays
         * In this second level arrays the key is the controller and value an array with key method and value boolean
         * @var Array 
         */
        Protected $perms;
        /**
         * The field name, which holds the role_id
         * @var string 
         */
        Protected $role_field;
        /**
         * Contstruct in order to set rules
         * @author ChristianGaertner
         */
        public function __construct() {

            $this->role_field = 'user_session';
            $this->listed_controller();
            $this->unlisted_controller();
            
        }
        /**
         * The main method, determines if the a user is allowed to view a site
         * @author ChristianGaertner
         * @return boolean
         */

        public function listed_controller() {
            // $this->perms[1]['homeController']['index']        = true;
            // $this->perms[3]['homeController']['index']        = true;
            // $this->perms[8]['homeController']['index']        = true;
            // $this->perms[10]['homeController']['index']        = true;
            $CI =& get_instance();
            $session_data = $CI->session->userdata($this->role_field);
            
            $user_id = $session_data[0]['user_id'];
            $role_id = $session_data[0]['role_id'];
			$dept_id = $session_data[0]['dept_id'];
			$is_superadmin = $session_data[0]['is_superadmin'];

			$listed_data = $CI->db->query("SELECT up.role_id, up.category_id, (SELECT controller FROM `app_routes` WHERE id = up.route_id) controller, (SELECT method FROM `app_routes` WHERE id = up.route_id) method FROM `user_permissions` up WHERE up.route_status = '1' AND up.role_id = '".$role_id."' AND up.dept_id = '".$dept_id."' AND up.status = '1' AND up.is_deleted = '0'")->result_array();
            
            if(!empty($listed_data)){
                foreach ($listed_data as $keyData => $valData) {
				   $this->perms[$valData['role_id']][$valData['controller']][$valData['method']] = true; 
				   if($is_superadmin != '1'){
					if($valData['category_id'] == '4'){
						$CI->session->set_userdata('delete_status', '1');
					}
				   }
                }
            }else{
                $CI->session->set_userdata('delete_status', '0');
            }

            // echo "<pre>".print_r($listed_data);exit;
            // echo $_SESSION['delete_status'];exit;    
        }


        public function unlisted_controller() {
            // $this->perms[2]['homeController']['index']        = false;
            // $this->perms[0]['homeController']['index']        = false;
            $CI =& get_instance();
            $session_data = $CI->session->userdata($this->role_field);
            
            $user_id = $session_data[0]['user_id'];
            $role_id = $session_data[0]['role_id'];
            $dept_id = $session_data[0]['dept_id'];
			
			//should get 404
            $unlisted_data = $CI->db->query("SELECT up.role_id, up.category_id, (SELECT controller FROM `app_routes` WHERE id = up.route_id) controller, (SELECT method FROM `app_routes` WHERE id = up.route_id) method FROM `user_permissions` up WHERE up.route_status = '0' AND up.role_id = '".$role_id."' AND up.dept_id = '".$dept_id."' AND up.status = '1' AND up.is_deleted = '0'")->result_array();
            // echo "<pre>".print_r($unlisted_data);exit;
            if(!empty($unlisted_data)){
                foreach ($unlisted_data as $keyData => $valData) {
                   $this->perms[$valData['role_id']][$valData['controller']][$valData['method']] = false; 
                   if($valData['category_id'] == '4'){
                        $CI->session->set_userdata('delete_status', '0');
                   }
                }
            }else{
                $CI->session->set_userdata('delete_status', '0');
            }    
        }

        public function auth() {

            $CI =& get_instance();
            // echo'<pre>';print_r($CI->session);exit;
            if (!isset($CI->session)) { 
                # Sessions are not loaded
                $CI->load->library('session');
            } 
            
            if (!isset($CI->router)) { 
                # Router is not loaded
                $CI->load->library('router');
            }
            
            $class = $CI->router->fetch_class();

            $method = $CI->router->fetch_method();
            // echo'<pre>';print_r($this->perms);exit;
            // Is rule defined?
            $is_ruled = false;
            if(!empty($this->perms))
            {
                foreach ($this->perms as $role) { 
                    // echo'<pre>';print_r($role[$class][$method]);exit;
                        # Loop through all rules
                    if (isset($role[$class][$method])) { 
                        # For this role exists a rule for this route
                        $is_ruled = true;
                    }
                }
            }

            if (!$is_ruled) { 
                # No rule defined for this route
                // ignording the ACL
                return;
            }

            $session_data = $CI->session->userdata($this->role_field);
            if ($session_data != null) {

                
                $user_id = $session_data[0]['user_id'];
                $role_id = $session_data[0]['role_id'];

                $db =& DB();

                $query = $db->select('user_id,role_id')
                            ->where(array('user_id'=>$user_id,'status'=>'1','is_deleted'=>'0'))->get( 'users_table' );
                $result = $query->result_array();
                // echo'<pre>';print_r($result);
                # Role_ID successfully determined ==> User is logged in
                if(!empty($result)) {
                    $db_user_id = $result[0]['user_id'];
                    $db_role_id = $result[0]['role_id'];
                    // echo'<pre>';print_r($db_user_id);echo $db_role_id;exit;
                    if(($db_role_id == $role_id) && ($db_user_id == $user_id)) {
                        
                        if ($this->perms[$db_role_id][$class][$method]) { 
                            return true;
                        } else { 
                            redirect('error/403','refresh');
                        }
                    } else {

                        if ($this->perms[$db_role_id][$class][$method]) { 
                            return true;
                        } else { 

                            redirect('error/403','refresh');
                        }
                    }
                    // echo'<pre>';print_r($db_role_id);exit;
                } else {
                    redirect('login','refresh');
                }
                

                // echo'<pre>';var_dump(@$this->perms[$session_data][$class][$method]);exit;
                
            } else {
                redirect('login','refresh');
            }
        }
    }

    ?>
