<?php defined('BASEPATH') OR exit('No direct script access allowed');


class admin extends My_Controller{
	
	function __construct()
	{
		parent::__construct();
		if($this->session->userdata("userID") == 1000){
			$this->load->model("preference_type_model","preference");
			$this->load->model("email_model");
		
		}else{
			redirect("/");
		}
	}
	
	function index()
	{
		$data["target"] = "admin/panel";
		$data["title"] = "Site Administration";
		$this->load->view("page/index", $data);
	}
	
	
}