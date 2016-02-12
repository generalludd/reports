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
					"value",
					"label" 
			), TRUE );
			$attendSublist = $this->menu_model->get_pairs ( "attend-subtype" );
			$data ["attendSubtypes"] = get_keyed_pairs ( $attendSublist, array (
					"value",
					"label" 
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
				$error = "This student already has an attendance record for " . format_date ( $this->input->post ( "attendDate" ) );
				$this->session->set_flashdata ( "warning", $error );
			} else {
				$subtype = $this->input->post ( "attendSubtype" );
				if ($subtype && $subtype == "Unexcused") {
					$truancy = $this->attendance->check_truancy ( $kStudent, "Unexcused" );
				} else {
					$subtype = FALSE;
					$truancy = $this->attendance->check_truancy ( $kStudent );
				}
				$this->truancy_notification ( $truancy, $subtype );
			}
			
			redirect ( "attendance/search/$kStudent?showAll=1" );
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
		redirect ( "attendance/search/$kStudent?showAll=1" );
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
		$data ['kStudent'] = $kStudent;
		$data ['title'] = "Searching Attendance (all students)";
		if ($kStudent) {
			$data ["kStudent"] = $kStudent;
			$this->load->model ( "student_model" );
			$data ["student"] = $this->student_model->get_name ( $kStudent );
			$data ['title'] = sprintf ( "Searching attendance for %s", $data ['student'] );
		}
		$attendList = $this->menu_model->get_pairs ( "attendance" );
		$data ["attendTypes"] = get_keyed_pairs ( $attendList, array (
				"value",
				"label" 
		), TRUE );
		$attendSublist = $this->menu_model->get_pairs ( "attend-subtype" );
		$data ["attendSubtypes"] = get_keyed_pairs ( $attendSublist, array (
				"value",
				"label" 
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
	function search($kStudent = NULL)
	{
		$data ["kStudent"] = $kStudent;
		
		// has student information been passed to this script?
		
		if ($kStudent) {
			$this->load->model ( "student_model" );
			$data ["student"] = $this->student_model->get ( $data ["kStudent"] );
		} else {
			$data ["student"] = NULL;
		}
		$startDate = FALSE;
		$data ['startDate'] = $startDate;
		if ($this->input->get ( "startDate" )) {
			$startDate = $this->input->get ( "startDate" );
			$data ["startDate"] = $startDate;
		} elseif ($this->input->get ( "showAll" )) {
			$startDate = get_current_term () == "Mid-Year" ? YEAR_START : MID_YEAR;
			$data ['startDate'] = $startDate;
		}
		$endDate = FALSE;
		$data ["endDate"] = $endDate;
		if ($this->input->get ( "endDate" )) {
			$endDate = $this->input->get ( "endDate" );
			$data ["endDate"] = $endDate;
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
		
		$data ['summary'] = NULL;
		
		if ($kStudent) {
			$data ['summary'] = $this->attendance->summarize ( $kStudent, get_current_term (), get_current_year () );
		}
		// @TODO add a line displaying the search query
		
		$data ["title"] = sprintf ( "Attendance Search Results: %s", format_date_range ( $startDate, $endDate ) );
		$data ["target"] = "attendance/list";
		$data ["action"] = "search";
		$this->load->view ( "page/index", $data );
	}

	function check()
	{
		$this->load->model ( "teacher_model", "teacher" );
		
		if ($this->input->get ( "search" ) == 1) {
			// search interface
			$humanities_teachers = $this->teacher->get_for_subject ( "humanities" );
			$data ['humanities_teachers'] = get_keyed_pairs ( $humanities_teachers, array (
					"kTeach",
					"teacherName" 
			), TRUE );
			$data ['stuGroup'] = array (
					"",
					"a" => "A",
					"b" => "B" 
			);
			$teacher = $this->teacher->get ( $this->session->userdata ( "userID" ) );
			$data ['teacher'] = $teacher;
			if (get_cookie ( "gradeStart" ) == 5 && get_cookie ( "gradeEnd" ) == 8) {
				$teachers = $this->teacher->get_teacher_pairs ( 2, 1, "advisors" );
			} else {
				$teachers = $this->teacher->get_teacher_pairs ( 2, 1, "lower-school" );
			}
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
				$cookie_day = ""; // sprintf ( "%s-", date ( "D" ) );
				burn_cookie ( $cookie_day . "kTeach" );
				if ($kTeach = $this->input->get ( "kTeach" )) {
					$options ['kTeach'] = $kTeach;
					bake_cookie ( $cookie_day . "kTeach", $kTeach );
				} elseif ($humanitiesTeacher = $this->input->get ( "humanitiesTeacher" )) {
					$options ['humanitiesTeacher'] = $humanitiesTeacher;
					bake_cookie ( $cookie_day . "humanitiesTeacher", $humanitiesTeacher );
				} else {
					burn_cookie ( $cookie_day . "humanitiesTeacher" );
				}
				
				if ($gradeStart = $this->input->get ( 'gradeStart' )) {
					if (strtolower ( $gradeStart ) == "k") {
						$gradeStart = 0;
					}
					$options ["gradeStart"] = $gradeStart;
					bake_cookie ( $cookie_day . "gradeStart", format_grade ( $gradeStart ) );
				} else {
					burn_cookie ( $cookie_day . "gradeStart" );
				}
				if ($gradeEnd = $this->input->get ( "gradeEnd" )) {
					if (strtolower ( $gradeEnd ) == "k") {
						$gradeEnd = 0;
					}
					$options ["gradeEnd"] = $gradeEnd;
					bake_cookie ( $cookie_day . "gradeEnd", format_grade ( $gradeEnd ) );
				} else {
					burn_cookie ( $cookie_day . "gradeEnd" );
				}
				if ($stuGroup = $this->input->get ( "stuGroup" )) {
					$options ['stuGroup'] = $stuGroup;
					bake_cookie ( $cookie_day . "stuGroup", $stuGroup );
					$stuGroup = strtoupper ( $stuGroup );
				} else {
					$stuGroup = "";
					burn_cookie ( $cookie_day . "stuGroup" );
				}
				$this->load->model ( "student_model", "student" );
				$students = $this->student->get_students_by_grade ( $options ['gradeStart'], $options ['gradeEnd'], $options );
				foreach ( $students as $student ) {
					if (! $kTeach) {
						$kTeach = $this->session->userdata ( "userID" );
					}
					$student->attendance = $this->attendance->get_by_date ( $date, $student->kStudent );
					$student->buttons = $this->_checklist_buttons ( $date, $student->kStudent, $kTeach, array("kAttendance"=>get_value ( $student->attendance, "kAttendance" )) );
				}
				$teachClass = "";
				if ($gradeEnd < 5 || ($gradeStart == 5 && $gradeEnd == 8) || $humanitiesTeacher) {
					
					if (($gradeStart == 5 && $gradeEnd == 8) || $gradeEnd < 5) {
						$teacher = $this->teacher->get ( $kTeach );
					} elseif ($humanitiesTeacher) {
						$teacher = $this->teacher->get ( $humanitiesTeacher );
					}
					$teachClass = sprintf ( ", %s", $teacher->teachClass );
				}
				$data ['options'] = $options;
				$data ["students"] = $students;
				$data ["target"] = "attendance/checklist/list";
				$data ["title"] = sprintf ( "Attendance Checklist for %s, Grade%s %s%s%s", format_date ( $date, "standard" ), $gradeStart != $gradeEnd ? "s" : "", format_grade_range ( $gradeStart, $gradeEnd ), $stuGroup, $teachClass );
				$this->load->view ( "page/index", $data );
			}
		}
	}

	function absent()
	{
		// @TODO convert to a POST form, since this is not really secure!
		if ($info = $this->input->post ( "info" )) {
			$data = explode ( "_", $info );
			$type = $data [0];
			$date = $data [1];
			$kStudent = $data [2];
			$kAttendance = $this->attendance->mark ( $date, $kStudent, $type );
			$this->truancy_notification ( $this->attendance->check_truancy ( $kStudent ) );
			if ($kAttendance) {
				$kTeach = $this->session->userdata ( "userID" );
				echo $this->_checklist_buttons ( $date, $kStudent, $kTeach, array("kAttendance"=>$kAttendance ));
			}
		}
	}

	function revert()
	{
		if ($kAttendance = $this->input->get ( "kAttendance" )) {
			if ($kTeach = $this->input->get ( "kTeach" )) {
				$record = $this->attendance->revert ( $kAttendance, $kTeach );
				$kTeach = $this->session->userdata ( "userID" );
				if($record->attendOverride == 1){
					echo $this->_checklist_buttons ( $record->attendDate, $record->kStudent, $kTeach, array("override"=>TRUE) );
				}else{
					echo $this->_checklist_buttons ( $record->attendDate, $record->kStudent, $kTeach );
				}
			}
		}
	}

	function complete($date, $kTeach)
	{
		$this->load->model ( "teacher_model", "teacher" );
		$teacher = $this->teacher->get ( $kTeach, "email,teachFirst,teachLast" );
		$subject = sprintf ( "Attendance for %s %s, %s", $teacher->teachFirst, $teacher->teachLast, format_date ( $date ) );
		
		$data ['subject'] = $subject;
		$search_array = FALSE;
		
// 		$options = array (
// 				"humanitiesTeacher",
// 				"stuGroup",
// 				"kTeach",
// 				"gradeStart",
// 				"gradeEnd" 
// 		);
// 		$cookie_day = "";  // sprintf ( "%s-", date ( "D" ) );
// 		for ( $i=0;$i<count($options);$i++ ) {
// 			print get_cookie($cookie_day . $options[$i]);
				
// 			if (get_cookie ( $options[$i] )) {
// 				$search_array [$options[$i]] = get_cookie ($cookie_day .  $options[$i] );
// 			}

// 		}
		if ($search_array) {
			$search_array['startDate'] = $date;
			$search_array['attendType'] = array("Absent","Tardy");
			$data ['records'] = $this->attendance->search ( $search_array );
		} else {
			$data ['records'] = $this->attendance->get_for_teacher ( $date, $kTeach );
		}
		
		$data ['teacher_name'] = format_name ( $teacher->teachFirst, $teacher->teachLast );
		$message = $this->load->view ( "attendance/checklist/email", $data, TRUE );
		$this->email->from ( $teacher->email );
		$this->email->to ( "frontoffice@fsmn.org" );
		// $this->email->cc($teacher->email);
		
		$this->email->subject ( $subject );
		$this->email->message ( $message );
		$this->email->set_alt_message ( $message );
		$this->email->send ();
		if ($this->session->userdata ( "userID" ) == ROOT_USER) {
			$this->email->print_debugger ();
		}
		$note = "<p>The front office has been notified of your attendance.</p>";
		if ($this->input->get ( "ajax" )) {
			echo $note;
		} else {
			$this->session->set_flashdata ( "warning", $note );
			redirect ( "/" );
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
		
		$attendance = $this->attendance->summarize ( $kStudent, $term, $year );
		print "Days Tardy: " . $attendance ['tardy'] . ", Days Absent: " . $attendance ["absent"];
	}


	
	/**
	 *
	 * @param stdObj $record
	 *        	send an email notification to head of school when a student is truant after a threshold is met.
	 */
	function truancy_notification($record, $subtype = FALSE)
	{
		if (($subtype == "Unexcused" && $record->total > UNEXCUSED_THRESHOLD) || $record->total > TRUANCY_THRESHOLD) {
			$subtype || $subtype = "total";
			$today = date ( 'Y-m-d' );
			if (get_current_term () == "Mid-Year") {
				$startDate = YEAR_START;
			} else {
				$startDate = MID_YEAR;
			}
			$student = format_name ( $record->stuNickname, $record->stuLast );
			$subject = sprintf ( "Truancy alert for %s", $student );
			if ($subtype == "Unexcused" ) {
				$threshold = UNEXCUSED_THRESHOLD;
			}elseif($subtype == "Illness"){
				$threshold = ILLNESS_THRESHOLD;
			} else {
				$threshold = TRUANCY_THRESHOLD;
			}
			$body ['absences'] = sprintf ( "As of %s, %s has had %s %s absences since the start of the term.", date ( 'm/d/Y' ), $student, $record->total, strtolower ( $subtype ) );
			$body ['handbook'] = sprintf ( "This exceeds the limit of %s %s absences as identified in the school handbook.", $threshold, strtolower ( $subtype ) );
			$body ['link'] = sprintf ( "You can view %s's record <a href='%s'>here.</a>", $record->stuNickname, site_url ( "attendance/search/$record->kStudent?startDate=$startDate" ) );
			$this->email->from ( "frontoffice@fsmn.org" );
			$this->email->to ( "head@fsmn.org,assistanthead@fsmn.org" );
			$message = implode ( "\n", $body );
			$this->email->subject ( $subject );
			$this->email->message ( $message );
			$this->email->send ();
			if ($this->session->userdata ( "userID" ) == ROOT_USER) {
				$this->email->print_debugger ();
			}
			
			$this->session->set_flashdata ( "warning", sprintf ( "%s %s An alert message has been sent to the head and assistant head of school. You do not need take any further action.", $body ['absences'], $body ['handbook'] ) );
		}
	}

	function _checklist_buttons($date, $kStudent, $kTeach, $options = array())
	{
		if (array_key_exists("kAttendance",$options) && $options["kAttendance"] != NULL) {
			$kAttendance = $options['kAttendance'];
			$buttons [] = array (
					"text" => "Revert",
					"class" => "button inline edit small revert-absence",
					"href" => base_url ( "attendance/revert?kTeach=$kTeach&kAttendance=$kAttendance" ) 
			);
		
		} else {
			$buttons [] = array (
					"text" => "Mark Tardy <i class='fa fa-clock-o'></i>",
					"class" => "button inline new small attendance-check tardy",
					"href" => base_url ( "attendance/absent?date=$date&kStudent=$kStudent&attendType=Tardy" ),
					"id" => sprintf ( "Tardy_%s_%s", $date, $kStudent ) 
			);
			$buttons [] = array (
					"text" => "Mark Absent <i class='fa fa-calendar-times-o'></i>",
					"class" => "button inline small attendance-check absent",
					"href" => base_url ( "attendance/absent?date=$date&kStudent=$kStudent&attendType=Absent" ),
					"id" => sprintf ( "Absent_%s_%s", $date, $kStudent ) 
			);
			$buttons [] = array (
					"text" => "Present",
					"class" => "button inline small mark-present",
					"id" => sprintf ( "mark-present_%s", $kStudent ) 
			);
		}
		return create_button_bar ( $buttons );
	}
}
