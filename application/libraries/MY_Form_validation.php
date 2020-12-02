<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class MY_Form_validation extends CI_Form_validation {
 	
    protected $CI;

 	public function __construct() {
		parent::__construct();
 		$this->CI =& get_instance();
	}
    public function password_format($str) {           
        $this->CI->form_validation->set_message('password_format', 'Password should contain atleast 1 Capital letter ,Small letter,one special character And Number');
        $validation_match = (preg_match('/[A-Z]/', $str) && preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{1,100}$/i', $str));
        if($validation_match) { 
            return TRUE;
        } else {
            return FALSE;
        }
    }
    public function customEmail($str)
    {
        $this->CI->form_validation->set_message('customEmail', 'Please enter valid email address!');
        $validation_match = (preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i', $str));
        if($validation_match) { 
            return TRUE;
        } else {
            return FALSE;
        }
    }
    public function authenticate_keygen($str)
    {
        $this->CI->form_validation->set_message('authenticate_keygen', 'Please enter valid access key.');
        $validation_match = !$this->CI->form_validation->is_unique($str,'users_table.user_keygen');
        if($validation_match) { 
            return TRUE;
        } else {
            return FALSE;
        }
    }
}