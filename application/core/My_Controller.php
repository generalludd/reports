<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_Controller extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if(!is_logged_in($this->session->all_userdata())){
			redirect("auth");
			die();
		}
	}



}