<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author Dhyey Rathod
 */
class MY_Controller extends CI_Controller
{
	public $authorised_user;

	public function __construct()
	{
		parent::__construct();
		if (!empty($this->session->userdata('user_session')[0]['user_id'])) {
			$this->authorised_user = $this->session->userdata('user_session')[0];
		} else {
			$this->authorised_user = array();
		}
		$this->load->helper('string');$this->load->helper('emailtrigger');
		$this->email_trigger = new Emailtrigger ;
	}

	private function router_manager()
	{
		if (!empty($this->authorised_user) && $this->input->server('REQUEST_METHOD') == 'GET' && $this->authorised_user['dept_id'] != 0) {
			$query_result = $this->db->select('*')->from('app_routes')->where('dept_id',0)->or_where('dept_id',$this->authorised_user['dept_id'])->get()->result();$route_array = array();
			foreach ($query_result as $key => $oneRoute) :
				if ($oneRoute->sub_slug == $this->uri->segment(1) || $oneRoute->slug == $this->uri->segment(1)) array_push($route_array,$oneRoute);
			endforeach ;
			if ($this->authorised_user['is_superadmin'] != 1) {
				if (count($route_array) == 0) redirect('error/404');
			}
		}
	}
	
	protected function file_upload( $userfile , $path = FALSE , $config = FALSE)
	{
		$_FILES['image_data']['name'] = $userfile['name'];
	    $_FILES['image_data']['type'] = $userfile['type'];
	    $_FILES['image_data']['tmp_name'] = $userfile['tmp_name'];
	    $_FILES['image_data']['error'] = $userfile['error'];
	    $_FILES['image_data']['size'] = $userfile['size'];
	    if ($config != FALSE) {
	    	$this->upload->initialize($config);
	    } else {
	    	$config['upload_path'] = $path;$config['allowed_types'] = '*';$config['encrypt_name'] = TRUE;
	    	$this->upload->initialize($config);
	    }
	    if ($this->upload->do_upload('image_data')) {
	    	$image_response = array(
	    		'status' => TRUE,'file_data'=>$this->upload->data(),
	    	);
	    } else {
	    	$image_response = array(
	    		'status' => FALSE,'file_data'=>FALSE,'error'=>$this->upload->display_errors(),
	    	);
	    } 
	    return json_encode($image_response);
	}
}
