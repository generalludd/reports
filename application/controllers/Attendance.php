<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Attendance extends MY_Controller {

	function __construct()
	{
		parent::__construct ();
		$this->load->model ( "attendance_model", "attendance" );
		$this->load->helper ( "attendance" );
	}

	function take()
	{
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
			$data ["student"] = $this->student_model->get_name ( $kStudent );
			$data ["title"] = sprintf ( "Adding Attendance for %s", $data ["student"] );
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
			$data ["target"] = "attendance/edit";
			if ($this->input->get ( "ajax" )) {
				$this->load->view ( $data ["target"], $data );
			} else {
				$this->load->view ( "page/index", $data );
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
			$data ["attendance"] = $this->attendance->get ( $kAttendance );
			$data ["kStudent"] = $data ["attendance"]->kStudent;
			$data ["student"] = $this->student_model->get_name ( $data ["kStudent"] );
			$data ['title'] = sprintf ( "Editing Attendance for %s", $data ['student'] );
			$data ['target'] = "attendance/edit";
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
			if ($this->input->get ( "ajax" )) {
				$this->load->view ( $data ['target'], $data );
			} else {
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
			$kAttendance = $this->attendance->insert ();
			$error = FALSE;
			if (! $kAttendance) {
				$error = "This student already has an attendance record for " . $this->input->post ( "attendDate" );
			}
			$this->session->set_flashdata ( "warning", $error );
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
			$this->attendance->update ( $kAttendance );
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
			$this->attendance->delete ( $kAttendance );
		}
	}

	/**
	 * show the search dialog for finding attendance records based on student (if provided)
	 * or merely over a term for all students based on the available criteria.
	 */
	function show_search($kStudent = NULL)
	{
		$this->load->model ( "menu_model" );
		$this->load->model ( "teacher_model", "teacher" );
		$data ["student"] = NULL;
		$data ['kStudent'] = NULL;
		$data ['title'] = "Searching Attendance";
		if ($kStudent) {
			$data ["kStudent"] = $kStudent;
			$this->load->model ( "student_model" );
			$data ["student"] = $this->student_model->get_name ( $kStudent );
			$data ['title'] = sprintf ( "Searching attendance for %s", $data ['student'] );
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
		
		$data ['attendance'] = $this->attendance->search ( $data );
		// @TODO add a line displaying the search query
		$data ["title"] = "Attendance Search Results";
		$data ["target"] = "attendance/list";
		$data ["action"] = "search";
		$this->load->view ( "page/index", $data );
	}

	function check()
	{
		if ($this->input->get ( "search" ) == 1) {
			// search interface
			$this->load->model ( "teacher_model", "teacher" );
			$humanities_teachers = $this->teacher->get_for_subject ( "humanities" );
			$data ['humanities_teachers'] = get_keyed_pairs ( $humanities_teachers, array (
					"kTeach",
					"teacherName" 
			), TRUE );
			$data ['stuGroup'] = array (
					"",
					"a"=>"A",
					"b"=>"B" 
			);
			$teachers = $this->teacher->get_teacher_pairs ();
			$data ['teachers'] = get_keyed_pairs ( $teachers, array (
					"kTeach",
					"teacher" 
			), TRUE );
			$data ['target'] = "attendance/checklist/search";
			$data ['title'] = "Check Attendance";
			if ($this->input->get ( "ajax" ) == 1) {
				$this->load->view ( $data ['target'], $data );
			} else {
				$this->load->view ( "page/index", $data );
			}
		} else {
			if ($date = $this->input->get ( "date" )) {
				if ($kTeach = $this->input->get ( "kTeach" )) {
					$options ['kTeach'] = $kTeach;
				} elseif ($humanitiesTeacher = $this->input->get ( "humanitiesTeacher" )) {
					$options ['humanitiesTeacher'] = $humanitiesTeacher;
				}
				if ($gradeStart = $this->input->get ( 'gradeStart' )) {
					if(strtolower($gradeStart) == "k"){
						$gradeStart = 0;
					}
					$options ["gradeStart"] = $gradeStart;
					
				}
				if ($gradeEnd = $this->input->get ( "gradeEnd" )) {
					if(strtolower($gradeEnd) == "k"){
						$gradeEnd = 0;
					}
					$options ["gradeEnd"] = $gradeEnd;
				}
				if ($stuGroup = $this->input->get ( "stuGroup" )) {
					$options ['stuGroup'] = $stuGroup;
				}
				$this->load->model ( "student_model", "student" );
				$students = $this->student->get_students_by_grade ( $options ['gradeStart'], $options ['gradeEnd'], $options );
				foreach ( $students as $student ) {
					if(!$kTeach){
						$kTeach = $this->session->userdata("userID");
					}
					$student->attendance = $this->attendance->get_by_date ( $date, $student->kStudent );
					$student->buttons = $this->_checklist_buttons( $date, $student->kStudent, $kTeach, get_value($student->attendance,"kAttendance"));
				}
				$data ["students"] = $students;
				$data ["target"] = "attendance/checklist/list";
				$data ["title"] = "Attendance Checklist for $date";
				$this->load->view ( "page/index", $data );
			}
		}
	}

	function absent()
	{
		if ($date = $this->input->get ( "date" )) {
			if ($kStudent = $this->input->get ( "kStudent" )) {
				$kAttendance = $this->attendance->mark ( $date, $kStudent, "Absent" );
				if ($kAttendance) {
					$kTeach = $this->session->userdata ( "userID" );
					echo $this->_checklist_buttons( $date, $kStudent,$kTeach, $kAttendance);
					
// 					$output = sprintf ( "<a href='%s' class='button inline edit small revert-absence'>Revert</a>", base_url ( "attendance/revert?kTeach=$kTeach&kAttendance=$kAttendance" ) );
// 					echo $output;
				}
			}
		}
	}

	function revert()
	{
		if ($kAttendance = $this->input->get ( "kAttendance" )) {
			if ($kTeach = $this->input->get ( "kTeach" )) {
				$record = $this->attendance->revert ( $kAttendance, $kTeach );
				$kTeach = $this->session->userdata ( "userID" );
				echo $this->_checklist_buttons($record->attendDate, $record->kStudent, $kTeach);
			}
		}
	}

	function complete($date, $kTeach)
	{
		$this->load->model ( "teacher_model", "teacher" );
		$date = format_date ( $date );
		$teacher = $this->teacher->get ( $kTeach, "email,teachFirst,teachLast" );
		$subject = sprintf ( "Attendance for %s %s, %s", $teacher->teachFirst, $teacher->teachLast, $date );
		
		$data ['subject'] = $subject;
		$data ['records'] = $this->attendance->get_for_teacher ( $date, $kTeach );
		$data ['teacher_name'] = format_name ( $teacher->teachFirst, $teacher->teachLast );
		$message = $this->load->view ( "attendance/checklist/email", $data, TRUE );
		$this->email->from ( $teacher->email );
		$this->email->to ( "frontoffice@fsmn.org" );
	// $this->email->cc($teacher->email);
		
		$this->email->subject ( $subject );
		$this->email->message ( $message );
		$this->email->set_alt_message ( $subject );
		$this->email->send ();
		if ($this->session->userdata ( "userID" ) == 1000) {
			$this->email->print_debugger ();
		}
		echo "<p>The front office has been notified of your attendance</p>";
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
		
		$attendance = $this->attendance->summarize ( $kStudent, $term, $year );
		print "Days Tardy: " . $attendance ['tardy'] . ", Days Absent: " . $attendance ["absent"];
	}
	
	function _checklist_buttons( $date, $kStudent,$kTeach, $kAttendance=NULL){
		if($kAttendance){
			$buttons[] = array("text"=>"Revert","class"=>"button inline edit small revert-absence","href"=>base_url("attendance/revert?kTeach=$kTeach&kAttendance=$kAttendance"));
		}else{
			$buttons[] = array("text"=>"Mark Absent","class"=>"button inline new small attendance-check","href"=>base_url("attendance/absent?date=$date&kStudent=$kStudent"));
		    $buttons[] = array("text"=>"Present","class"=>"button inline small mark-present","id"=>sprintf("mark-present_%s",$kStudent)); 
		}
		return create_button_bar($buttons);
	}
}
