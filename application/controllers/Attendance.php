<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Attendance extends MY_Controller {

	function __construct()
	{
		parent::__construct ();
		$this->load->model ( "attendance_model" );
	}
	
	function take(){
		
	}

	/**
	 * create a dialog for inserting a new attendance entry for a student
	 * @TODO the error with this script could be converted easily into a CONSTANT
	 * used throughout the system.
	 * A function that produces the error could accept
	 * the missing field as the parameter.
	 */
	function create($kStudent = FALSE)
	{
		if ($kStudent) {
			$this->load->model ( "student_model" );
			$data ["kStudent"] = $kStudent;
			$data["student"] = $this->student_model->get_name($kStudent);
			$data["title"] = sprintf("Adding Attendance for %s",$data["student"]);
			$data ["attendance"] = NULL;
			$data ["kAttendance"] = NULL;
			$this->load->model ( "menu_model" );
			$attendList = $this->menu_model->get_pairs ( "attendance" );
			$data ["attendTypes"] = get_keyed_pairs ( $attendList, array (
					"label",
					"value" 
			), TRUE );
			$attendSublist = $this->menu_model->get_pairs ( "attend-subtype" );
			$data ["attendSubtypes"] = get_keyed_pairs ( $attendSublist, array (
					"label",
					"value" 
			), TRUE );
			$data ["action"] = "insert";
			$data["target"] = "attendance/edit";
			if($this->input->get("ajax")){
				$this->load->view($data["target"],$data);
			}else{
				$this->load->view("page/index",$data);
			}
		} else {
			print "<p>A student identification key was not provided but is required";
			print " for this script to function. Please see the developer for assistance.</p>";
		}
	}

	/**
	 * display a dialog for edigint a given student's attendance record.
	 * @TODO should this be locked down after a term ends or after a grace period
	 * after end of term?
	 */
	function edit($kAttendance)
	{
		if ($kAttendance) {
			$this->load->model ( "student_model" );
			$data ["kAttendance"] = $kAttendance;
			$data ["attendance"] = $this->attendance_model->get ( $kAttendance );
			$data ["kStudent"] = $data ["attendance"]->kStudent;
			$data ["student"] = $this->student_model->get_name ( $data ["kStudent"] );
			$data['title'] = sprintf("Editing Attendance for %s",$data['student']);
			$data['target'] = "attendance/edit";
			$this->load->model ( "menu_model" );
			$attendList = $this->menu_model->get_pairs ( "attendance" );
			$data ["attendTypes"] = get_keyed_pairs ( $attendList, array (
					"label",
					"value" 
			), TRUE );
			$attendSublist = $this->menu_model->get_pairs ( "attend-subtype" );
			$data ["attendSubtypes"] = get_keyed_pairs ( $attendSublist, array (
					"label",
					"value" 
			), TRUE );
			$data ["action"] = "update";
			if($this->input->get("ajax")){
				$this->load->view($data['target'],$data);
			}else{
				$this->load->view ( "page/index", $data );
			}
		} else {
			print "<p>An attendence identification key was not provided but is required";
			print " for this script to function. Please see the developer for assistance.</p>";
		}
	}

	/**
	 * insert a newly created attendance record.
	 * Show list based on the student's ID
	 */
	function insert()
	{
		if ($this->input->post ( "kStudent" )) {
			$kStudent = $this->input->post ( "kStudent" );
			$kAttendance = $this->attendance_model->insert ();
			$error = FALSE;
			if (! $kAttendance) {
				$error = "This student already has an attendance record for " . $this->input->post("attendDate");
			}
			$this->session->set_flashdata("warning",$error);
			redirect ( "attendance/search/$kStudent" );
		}
	}

	/**
	 * update an edited attendance record.
	 * Show a list based on the student's ID
	 */
	function update()
	{
		if ($this->input->post ( "action" ) == "delete") {
			$this->delete ();
		} elseif ($this->input->post ( "kAttendance" )) {
			$kAttendance = $this->input->post ( "kAttendance" );
			$this->attendance_model->update ( $kAttendance );
		}
		$kStudent = $this->input->post ( "kStudent" );
		redirect ( "attendance/search/$kStudent" );
	}

	/**
	 * delete an attendance record.
	 * Warnings about deletion are given using
	 * jQuery javascript.
	 */
	function delete()
	{
		if ($this->input->post ( "kAttendance" )) {
			$kAttendance = $this->input->post ( "kAttendance" );
			$this->attendance_model->delete ( $kAttendance );
		}
	}

	/**
	 * show the search dialog for finding attendance records based on student (if provided)
	 * or merely over a term for all students based on the available criteria.
	 */
	function show_search($kStudent = NULL)
	{
		$this->load->model ( "menu_model" );
		$this->load->model("teacher_model","teacher");
		$data ["student"] = NULL;
		$data['kStudent'] = NULL;
		$data['title'] = "Searching Attendance";
		if ($kStudent) {
			$data ["kStudent"] = $kStudent;
			$this->load->model ( "student_model" );
			$data ["student"] = $this->student_model->get_name ( $kStudent );
			$data['title'] = sprintf("Searching attendance for %s",$data['student']);
		}
		$attendList = $this->menu_model->get_pairs ( "attendance" );
		$data ["attendTypes"] = get_keyed_pairs ( $attendList, array (
				"label",
				"value" 
		), TRUE );
		$attendSublist = $this->menu_model->get_pairs ( "attend-subtype" );
		$data ["attendSubtypes"] = get_keyed_pairs ( $attendSublist, array (
				"label",
				"value" 
		), TRUE );

		$data ['target'] = "attendance/search";
		if ($this->input->get ( "ajax" )) {
			$this->load->view ( $data ['target'], $data );
		} else {
			$this->load->view ( "page/index", $data );
		}
	}

	/**
	 * produce search results for a given search.
	 * 
	 * @param string $error
	 *        	The error is optional and is not currently used in the scripts.
	 */
	function search($error = NULL)
	{
		$data ["errors"] = $error;
		
		$data ["kStudent"] = NULL;
		$data ["student"] = NULL;
		
		$this->load->model ( "student_model" );
		
		// has student information been passed to this script?
		if ($this->uri->segment ( 3 )) {
			$data ["kStudent"] = $this->uri->segment ( 3 );
		}
		
		if ($this->input->get ( "kStudent" ) > 0) {
			$data ["kStudent"] = $this->input->get ( "kStudent" );
		}
		
		if ($data ["kStudent"]) {
			$data ["student"] = $this->student_model->get ( $data ["kStudent"] );
		}
		
		$data ["startDate"] = get_current_year () . "-08-01";
		if ($this->input->get ( "startDate" )) {
			$data ["startDate"] = format_date ( $this->input->get ( "startDate" ), "mysql" );
		}
		
		$data ["endDate"] = date ( "Y-m-j" );
		if ($this->input->get ( "endDate" )) {
			$data ["endDate"] = format_date ( $this->input->get ( "endDate" ), "mysql" );
		}
		
		$data ["attendType"] = NULL;
		if ($this->input->get ( "attendType" )) {
			$data ["attendType"] = $this->input->get ( "attendType" );
		}
		
		$data ["attendSubtype"] = NULL;
		if ($this->input->get ( "attendSubtype" )) {
			$data ["attendSubtype"] = $this->input->get ( "attendSubtype" );
		}
			
		$data ['attendance'] = $this->attendance_model->search ( $data );
		// @TODO add a line displaying the search query
		$data ["title"] = "Attendance Search Results";
		$data ["target"] = "attendance/list";
		$data ["action"] = "search";
		$this->load->view ( "page/index", $data );
	}

	function check_attendance(){
		if($this->input->get("search") == 1){
			//search interface
			$this->load->model("teacher_model","teacher");
			$humanities_teachers = $this->teacher->get_for_subject("humanities");
			$data['humanities_teachers'] = get_keyed_pairs($humanities_teachers,array("kTeach","teacherName"),TRUE);
			$data['stuGroup'] = array("","A","B");
			$teachers = $this->teacher->get_teacher_pairs();
			$data['teachers'] = get_keyed_pairs($teachers,array("kTeach","teacher"),TRUE);
			$data['target'] = "attendance/check_search";
				$data['title'] = "Check Attendance";
			if($this->input->get("ajax")==1){
				$this->load->view($data['target'],$data);
			}else{
			$this->load->view("page/index",$data);
			}
		}
	}
	
	/**
	 * summarize the student's attendance for final printed reports.
	 */
	function summarize()
	{
		$kStudent = $this->uri->segment ( 3 );
		
		$term = get_current_term ();
		if ($this->uri->segment ( 4 )) {
			$term = $this->uri->segment ( 4 );
		}
		
		$year = get_current_year ();
		if ($this->uri->segment ( 5 )) {
			$year = $this->uri->segment ( 5 );
		}
		
		$attendance = $this->attendance_model->summarize ( $kStudent, $term, $year );
		print "Days Tardy: " . $attendance ['tardy'] . ", Days Absent: " . $attendance ["absent"];
	}
}
