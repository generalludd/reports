<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attendance extends MY_Controller {

	function __construct()
	{
		
		parent::__construct();
		$this->load->model("attendance_model");
		
	}

	
	function create()
	{
		if($this->input->post("kStudent")){
			$this->load->model("student_model");
			$data["kStudent"] = $this->input->post("kStudent");
			$data["student"] = $this->student_model->get_name($data["kStudent"]);
			$data["attendance"] = NULL;
			$data["kAttendance"] = NULL;
			$this->load->model("menu_model");
			$attendList = $this->menu_model->get_pairs("attendance");
			$data["attendTypes"] = get_keyed_pairs($attendList, array("label","value"), TRUE);
			$attendSublist = $this->menu_model->get_pairs("attend-subtype");
			$data["attendSubtypes"] = get_keyed_pairs($attendSublist, array("label","value"), TRUE);
			$data["action"] = "insert";
			$this->load->view("attendance/edit",$data);
		}else{
			print "<p>A student identification key was not provided but is required";
			print " for this script to function. Please see the developer for assistance.</p>";
		}

			
	}

	function edit()
	{
		if($this->input->post("kAttendance")){
			$this->load->model("student_model");
			$data["kAttendance"] = $this->input->post("kAttendance");
			$data["attendance"] = $this->attendance_model->get($data["kAttendance"]);
			$data["kStudent"] = $data["attendance"]->kStudent;
			$data["student"] = $this->student_model->get_name($data["kStudent"]);
			$this->load->model("menu_model");
			$attendList = $this->menu_model->get_pairs("attendance");
			$data["attendTypes"] = get_keyed_pairs($attendList, array("label","value"), TRUE);
			$attendSublist = $this->menu_model->get_pairs("attend-subtype");
			$data["attendSubtypes"] = get_keyed_pairs($attendSublist, array("label","value"), TRUE);
			$data["action"] = "update";
			$this->load->view("attendance/edit",$data);
		}else{
			print "<p>A student identification key was not provided but is required";
			print " for this script to function. Please see the developer for assistance.</p>";
		}
	}

	function insert()
	{
		if($this->input->post("kStudent")){
			$kStudent = $this->input->post("kStudent");
			$kAttendance = $this->attendance_model->insert();
			$error = FALSE;
			if(!$kAttendance){
				$error = "dup";
			}
			redirect("attendance/get_list/$kStudent/$error");
		}
	}

	function update()
	{
		if($this->input->post("action") == "delete"){
			$this->delete();
		}elseif($this->input->post("kAttendance")){
			$kAttendance = $this->input->post("kAttendance");
			$this->attendance_model->update($kAttendance);

		}
		$kStudent = $this->input->post("kStudent");
		redirect("attendance/get_list/$kStudent");
	}

	function delete()
	{
		if($this->input->post("kAttendance")){
			$kAttendance = $this->input->post("kAttendance");
			$this->attendance_model->delete($kAttendance);
		}
	}

	function purge()
	{
			
	}

	
	function show_search()
	{
		$this->load->model("menu_model");
		$data["kStudent"] = NULL;
		$data["student"] = NULL;
		if($this->input->post("kStudent") > 0){
			$data["kStudent"] = $this->input->post("kStudent");
			$this->load->model("student_model");
			$data["student"] = $this->student_model->get_name($data["kStudent"]);
		}
		$attendList = $this->menu_model->get_pairs("attendance");
		$data["attendTypes"] = get_keyed_pairs($attendList, array("label","value"), TRUE);
		$attendSublist = $this->menu_model->get_pairs("attend-subtype");
		$data["attendSubtypes"] = get_keyed_pairs($attendSublist, array("label","value"), TRUE);
		$this->load->view("attendance/search", $data);
	}

	
	function search($error = NULL)
	{
		$data["errors"] = $error;
		
		$data["kStudent"] = NULL;
		$data["student"] = NULL;

		$this->load->model("student_model");

		//has student information been passed to this script?
		if($this->uri->segment(3)){
			$data["kStudent"] = $this->uri->segment(3);
		}

		if($this->input->post("kStudent") > 0){
			$data["kStudent"] = $this->input->post("kStudent");
		}

		if($data["kStudent"]){
			$data["student"] = $this->student_model->get($data["kStudent"]);
		}

		$data["startDate"] = get_current_year() . "-08-01";
		if($this->input->post("startDate")){
			$data["startDate"] = format_date($this->input->post("startDate"),"mysql");
		}

		$data["endDate"] = date("Y-m-j");
		if($this->input->post("endDate")){
			$data["endDate"] = format_date($this->input->post("endDate"),"mysql");
		}

		$data["attendType"] = NULL;
		if($this->input->post("attendType")){
			$data["attendType"] = $this->input->post("attendType");
		}

		$data["attendSubtype"] = NULL;
		if($this->input->post("attendSubtype")){
			$data["attendSubtype"] = $this->input->post("attendSubtype");
		}

		$data['attendance'] = $this->attendance_model->search($data);
		//@TODO add a line displaying the search query
		$data["title"] = "Attendance Search Results";
		$data["target"] = "attendance/list";
		$data["action"] = "search";
		$this->load->view("page/index", $data);
	}


	function get_list()
	{
		$this->search($this->uri->segment(4));
		return false;
		/*$data["kStudent"] = NULL;
		$data["student"] = NULL;
		$this->load->model("student_model");
		if($this->uri->segment(3)){
			$kStudent = $this->uri->segment(3);
			$data["kStudent"] = $kStudent;
			$data["student"] = $this->student_model->get_name($kStudent);
		}
		$data["action"] = "list";
		$data["attendance"] = $this->attendance_model->get_list($kStudent);
		$data["title"] = "Attendance Report";
		$data["target"] = "attendance/list";
		$this->load->view("page/index", $data);
		*/

	}

	
	function summarize()
	{
		$kStudent = $this->uri->segment(3);

		$term = get_current_term();
		if($this->uri->segment(4)){
			$term = $this->uri->segment(4);
		}

		$year = get_current_year();
		if($this->uri->segment(5)){
			$year = $this->uri->segment(5);
		}

		$attendance = $this->attendance_model->summarize($kStudent, $term, $year);
		print "Days Tardy: " . $attendance['tardy'] . ", Days Absent: " . $attendance["absent"];
	}

}
