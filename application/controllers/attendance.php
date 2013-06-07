<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attendance extends MY_Controller {

	function __construct()
	{

		parent::__construct();
		$this->load->model("attendance_model");

	}

	/**
	 * create a dialog for inserting a new attendance entry for a student
	 * @TODO the error with this script could be converted easily into a CONSTANT
	 * used throughout the system. A function that produces the error could accept
	 * the missing field as the parameter.
	 */
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


	/**
	 * display a dialog for edigint a given student's attendance record.
	 * @TODO should this be locked down after a term ends or after a grace period
	 * after end of term?
	 */
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
			print "<p>An attendence identification key was not provided but is required";
			print " for this script to function. Please see the developer for assistance.</p>";
		}
	}

	/**
	 * insert a newly created attendance record. Show list based on the student's ID
	 */
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


	/**
	 * update an edited attendance record. Show a list based on the student's ID
	 */
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

	/**
	 * delete an attendance record. Warnings about deletion are given using
	 * jQuery javascript.
	 */
	function delete()
	{
		if($this->input->post("kAttendance")){
			$kAttendance = $this->input->post("kAttendance");
			$this->attendance_model->delete($kAttendance);
		}
	}

	/**
	 * show the search dialog for finding attendance records based on student (if provided)
	 * or merely over a term for all students based on the available criteria.
	 */
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


	/**
	 * produce search results for a given search.
	 * @param string $error
	 * The error is optional and is not currently used in the scripts.
	 */
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

	/**
	 * deprecated for search();
	 * @return boolean
	 */
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

	/**
	 * summarize the student's attendance for final printed reports.
	 */
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
