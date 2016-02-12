<?php defined('BASEPATH') OR exit('No direct script access allowed');
/***
 * Class allows for administration of global properties for the application.
 * Only the administrative user can access this feature.
 */

class admin extends MY_Controller{

	function __construct()
	{
		parent::__construct();
		if($this->session->userdata("userID") == ROOT_USER){
			$this->load->model("preference_type_model","preference");
			$this->load->model("auth_model");
		}else{
			redirect("/");
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see MY_Controller::index()
	 * go to the main admin control panel page
	 */
	function index()
	{
		$data["target"] = "admin/panel";
		$data["title"] = "Site Administration";
		$this->load->view("page/index", $data);
		delete_cookie('admin');

	}

	/**
	 * while logged in as administrator, masquerade as another user for testing purposes.
	 * expects the third segment of the uri to be the teacher to be masqueraded as
	 */
	function masquerade()
	{
		if($this->session->userdata("userID") == ROOT_USER){ //administrator account
			$userID = $this->uri->segment(3);
			$this->load->model("teacher_model");
			$teacher = $this->teacher_model->get($userID);
			if($teacher){
				$data['username'] = $teacher->username;
				$data['dbRole'] = $teacher->dbRole;
				$data['userID'] = $teacher->kTeach;
				//set the number of unread reports for advisors as is done with standard login.
				if($teacher->isAdvisor == 1){
					$this->load->model("student_report_model","report");
					$unread_reports = $this->report->get_count($teacher->kTeach);
					bake_cookie("unread_reports",$unread_reports);
				}
				bake_cookie("gradeStart",$teacher->gradeStart);
				bake_cookie("gradeEnd",$teacher->gradeEnd);
				bake_cookie("isAdvisor",$teacher->isAdvisor);
				if(get_value($teacher, "submits_report_card",FALSE)){
					bake_cookie("submits_report_card", $teacher->submits_report_card);
				}

				$this->load->model("preference_model");
				$preferences = $this->preference_model->get_distinct($teacher->kTeach);
				set_user_cookies($preferences);
				$this->session->set_userdata($data);
				redirect("/");
			}
		}
	}

	/**
	 * show the user access log. Most folks don't deliberately log out so the database mostly shows login times
	 */
	function show_log()
	{
		$options = array();
		if($this->input->get_post("kTeach")){
			$options["kTeach"] = $this->input->get_post("kTeach");
		}

		if($this->input->get_post("username")){
			$options["username"] = $this->input->get_post("username");
		}

		if($this->input->get_post("action")){
			$options["action"] = $this->input->get_post("action");
		}

		if($this->input->get_post("time_start") && $this->input->get_post("time_end")){
			$time_start = $this->input->get_post("time_start");
			$time_end = $this->input->get_post("time_end");
			$time_end .= " 23:59:59";// make the end time the end of the same day
			$options["date_range"]["time_start"] = $time_start;
			$options["date_range"]["time_end"] = $time_end;
		}

		$data["header"] = array("username","timestamp","action");
		$data["logs"] = $this->auth_model->get_log($options);
		$data["options"] = $options;
		$data["target"] = "admin/log";
		$data["title"] = "User Log";
		$this->load->view("page/index",$data);
	}

	/**
	 * Show a dialog to search the log for specific users, range of dates, and events (log in or log off).
	 */
	function search_log()
	{
		$users = $this->auth_model->get_usernames();
		$data["users"] = get_keyed_pairs($users,array("username","user"),TRUE);
		$data["actions"] = array("login" => "login","logout" => "logout");
		$this->load->view("admin/search_log",$data);
	}
}