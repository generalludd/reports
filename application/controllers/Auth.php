<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model("auth_model");
	}

	/**
	 * Redirect requests to this page to the login script.
	 * @param string $username
	 * @param string $errors
	 */
	function index($username = NULL, $errors = NULL)
	{
		$data["errors"] = $errors;
		$data["username"] = $username;
		$data["target"] = "auth/login";
		$this->load->view("auth/index", $data);

	}

	/**
	 * login script both logs in and sets the required session data to keep the user logged in.
	 * Also adds useful non-critical cookies to enhance the user experience.
	 * (No secure data is publised to these cookies)
	 */
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
				//required session items for authenticated session
				$data["username"] = $username;
				$data["dbRole"] = $result->dbRole;
				$data["userID"] = $result->kTeach;
				//useful cookies for enhanced user experience
				bake_cookie("gradeStart", $result->gradeStart);
				bake_cookie("gradeEnd", $result->gradeEnd);
				bake_cookie("isAdvisor",$result->isAdvisor);
				if(get_value($result, "submits_report_card",FALSE)){
					bake_cookie("submits_report_card", $result->submits_report_card);
				}

				//load preferences for UI adjustments. No secure data is stored in these cookies.
				//These cookies are not necessary for the system to function
				$this->load->model("preference_model","preference");
				$preferences = $this->preference->get_distinct($result->kTeach);
				set_user_cookies($preferences);
				bake_cookie("student_sort_order",$this->preference->get($result->kTeach,"student_sort_order"));
				//if the teacher is an advisor, get the number of unread reports;
				if($result->isAdvisor == 1){
					$this->load->model("student_report_model","report");
					$unread_reports =  $this->report->get_count($result->kTeach);
					$data["unread_reports"] = $unread_reports;
					bake_cookie("unread_reports", $unread_reports);
				}
				$this->session->set_userdata($data);
				$redirect = true;
			}
		}
		if($redirect){
			//The uri cookie is baked in the MY_Controller.php when the users is not logged in.
			//Thus it is baked in all controller classes that extend this master controller which is all controllers except auth.php
			if($uri = $this->input->cookie("uri")){
				redirect($uri);
			}else{
				redirect("");
			}

		}else{
			$this->index($username, "Your username or password are not correct. Please try again");
		}
	}

	/**
	 * destroy the session data and return the user to the login screen.
	 */
	function logout()
	{

		//make sure someone is logged in before logging the logout
		if($this->session->userdata("userID")){
			//$this->auth_model->log($this->session->userdata("userID"),"logout");
		}
		//destroy the session anyway.
		$this->session->sess_destroy();
		redirect("/");
	}

	/**
	 * Function to change passwords for users
	 * This is entirely dependent on ajax and does not function properly without that aspect of the UI.
	 */
	function change_password()
	{
		//declare the default response.
		$output = "You are not authorized to do this!";

		$kTeach = $this->input->post("kTeach");
		$userID = $this->session->userdata("userID");

		//Unauthorized users are those who are not the ROOT_USER and are trying to change someone else's password
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
			$hash = $this->auth_model->set_resetHash($kTeach);
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
		$data["resetHash"] = $this->uri->segment(4);
		$data["errors"] = array($errors);
		if($data["kTeach"] != "" && $data["resetHash"] != ""){
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
		$resetHash = $this->input->post("resetHash");
		$password = $this->input->post("new_password");
		$check_password = $this->input->post("check_password");
		$result = $this->auth_model->reset_password($kTeach, $resetHash, $password);
		if($result){
			$this->index("","You can now log in with your new password");
		}else{
			$this->start_reset("An error occurred. Please try again or ask for technical support");
		}
	}

}
