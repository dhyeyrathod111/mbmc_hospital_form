<?php 

class Authenticate {

    private $CI;

    function __construct()
    {
        $this->CI =& get_instance();
    }


    function loginCheck()
    {
       $CI = &get_instance();   
        // $CI->load->library('session');
        // --- Then ---
       // echo'<pre>';print_r($CI );exit;
        $session = $CI->session->userdata('user_session');
        echo'ss<pre>';print_r($session);exit;
        if($session[0]['role_id'] !='' && $session[0]['user_id'] !='') {
            return true;
            // echo'<pre>';print_r('hhihihi');exit;
        } else {
            // redirect('Myerror/access_denied','refresh');
            // echo'<pre>';print_r('hhihijjijijijijijijihi');exit;
        }
        // echo'<pre>';print_r($var);exit;

    }
}

?>