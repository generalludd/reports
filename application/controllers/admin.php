<?php defined('BASEPATH') OR exit('No direct script access allowed');


class admin extends MY_Controller{

	function __construct()
	{
		parent::__construct();
		if($this->session->userdata("userID") == 1000){
			$this->load->model("preference_type_model","preference");
			$this->load->model("email_model");
			$this->load->model("auth_model");
		}else{
			redirect("/");
		}
	}

	function index()
	{
		$data["target"] = "admin/panel";
		$data["title"] = "Site Administration";
		$this->load->view("page/index", $data);
		delete_cookie('admin');

	}

	function masquerade()
	{
		if($this->session->userdata("username") == "administrator"){
			$userID = $this->uri->segment(3);
			$this->load->model("teacher_model");
			$teacher = $this->teacher_model->get($userID);
			if($teacher){
				$data['username'] = $teacher->username;
				$data['dbRole'] = $teacher->dbRole;
				$data['userID'] = $teacher->kTeach;
				$data['gradeStart'] =$teacher->gradeStart;
				$data['gradeEnd'] = $teacher->gradeEnd;
				$data['is_advisor'] = $teacher->is_advisor;
				//set the number of unread reports for advisors as is done with standard login.
				if($teacher->is_advisor == 1){
					$this->load->model("student_report_model","report");
					$data["unread_reports"] = $this->report->get_count($teacher->kTeach);
				}
				$this->session->set_userdata($data);
				
				redirect("/");
			}
		}
	}

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
			$time_start = format_date($this->input->get_post("time_start"),"mysql");
			$time_end = format_date($this->input->get_post("time_end"),"mysql");
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

	function search_log()
	{
		$users = $this->auth_model->get_usernames();
		$data["users"] = get_keyed_pairs($users,array("username","user"),TRUE);
		$data["actions"] = array("login" => "login","logout" => "logout");
		$this->load->view("admin/search_log",$data);
	}
}