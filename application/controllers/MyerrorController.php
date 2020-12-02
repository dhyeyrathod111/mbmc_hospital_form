<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'controllers/Common.php';

class MyerrorController extends Common {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function access_denied()
	{
		// echo'<pre>';print_r('hihi');exit;
		$this->load->view('errors/403');
	}

	public function not_found()
	{
		// echo'<pre>';print_r('404');exit;
		$this->load->view('errors/404');
	}

	public function session_timeout()
	{
		// echo'<pre>';print_r('hihi');exit;
		$this->load->view('errors/408');
	}
}
