<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model("auth_model");
	}

	function index($username = NULL, $errors = NULL)
	{
		$data["errors"] = $errors;
		$data["username"] = $username;
		$data["target"] = "auth/login";
		$this->load->view("auth/index", $data);

	}

	function login()
	{
		$redirect = false;
		$username = "";
		if($this->input->post("username") && $this->input->post("password")){
			$username = $this->input->post("username");
			$password =  $this->input->post("password");
			$result = $this->auth_model->validate($username, $password);
			if($result){
				$this->auth_model->log($result->kTeach, "login");
				$data["username"] = $username;
				$data["dbRole"] = $result->dbRole;
				$data["userID"] = $result->kTeach;
				$data["gradeStart"] = $result->gradeStart;
				$data["gradeEnd"] = $result->gradeEnd;
				$this->session->set_userdata($data);
				$redirect = true;
			}
		}
		if($redirect){
			redirect("");
		}else{
			$this->index($username, "Your username or password are not correct. Please try again");
		}
	}


	function logout()
	{

		//make sure someone is logged in before logging the logout
		if($this->session->userdata("userID")){
			$this->auth_model->log($this->session->userdata("userID"),"logout");
		}
		//destroy the session anyway.
		$this->session->sess_destroy();
		redirect("/");
	}


	function change_password()
	{
		$output = "You are not authorized to do this!";
		$kTeach = $this->input->post("kTeach");

		$userID = $this->session->userdata("userID");

		if($kTeach == $userID || $userID == 1000){
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

	/****** FORGOTTEN PASSWORD RESETTING FUNCTIONS ******/

	/**
	 *
	 * Begin the process of resetting a user account by displaying
	 * a dialog
	 * @param string or array $errors
	 */
	function start_reset($errors = NULL)
	{
		$data["errors"] = $errors;
		$data["target"] = "auth/request_reset";
		$this->load->view("auth/index", $data);
	}

	/**
	 *
	 * Send the reset hash based on the email address provided.
	 */
	function send_reset()
	{
		$email = trim($this->input->get_post("email"));
		$kTeach = $this->auth_model->email_exists($email);
		if($kTeach){
			$hash = $this->auth_model->set_reset_hash($kTeach);
			$link = site_url("auth/show_reset/$kTeach/$hash");
			$this->email->from("technology@fsmn.org");
			$this->email->to($email);
			$this->email->subject("Password Reset");
			$this->email->message("Click on the following link to reset your password: $link");
			$this->email->send();
			$errors = "An email has been sent to your account with instructions for resetting your password.";
			$this->index("",$errors);
		}else{
			$this->start_reset("The email address you entered does not exist in the database, please try again");
		}
	}

	/**
	 *
	 * Show the reset dialog
	 * @param string or array $errors
	 */
	function show_reset($errors = NULL)
	{
		$data["kTeach"] = $this->uri->segment(3);
		$data["reset_hash"] = $this->uri->segment(4);
		$data["errors"] = array($errors);
		if($data["kTeach"] != "" && $data["reset_hash"] != ""){
			$data["target"] = "auth/reset_password";
			$this->load->view("auth/index", $data);
		}else{
			$this->logout();
		}
	}

	/**
	 *
	 * finish up the reset process
	 */
	function complete_reset()
	{
		$kTeach = $this->input->post("kTeach");
		$reset_hash = $this->input->post("reset_hash");
		$password = $this->input->post("new_password");
		$check_password = $this->input->post("check_password");
		$result = $this->auth_model->reset_password($kTeach, $reset_hash, $password);
		if($result){
			$this->index("","You can now log in with your new password");
		}else{
			$this->start_reset("An error occurred. Please try again or ask for technical support");
		}
	}


}
