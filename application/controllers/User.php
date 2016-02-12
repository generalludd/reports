<?php defined('BASEPATH') OR exit('No direct script access allowed');

class user extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
	}

	function edit_password()
	{
		$kTeach = $this->input->get("kTeach");
		$userID = $this->session->userdata("userID");
		if($kTeach == $userID || $userID == ROOT_USER){
			$data["kTeach"] = $kTeach;
			$this->load->view("auth/changepass", $data);
		}
	}

	function change_password()
	{
		$output = "You are not authorized to do this!";
		$kTeach = $this->input->post("kTeach");

		$userID = $this->session->userdata("userID");
		$this->load->model("auth_model");
		if($kTeach == $userID || $userID == ROOT_USER){
			$output = "The passwords did not match";
			$current_password = $this->input->post("current_password");

			$new_password = $this->input->post("new_password");

			$check_password = $this->input->post("check_password");

			if($new_password === $check_password){
				$result = $this->auth_model->change_password($kTeach, $current_password, $new_password);
				if($result){
					$output = "Your password has been successfully changed";
				}else{
					$output = "Your original password did not match the one in the database";
				}
			}
		}
		echo $output;
	}




}