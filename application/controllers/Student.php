<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

/**
 * student management interface *
 */
class Student extends MY_Controller {

	function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'student_model' );
		$this->load->model ( 'teacher_model' );
		$this->load->model ( 'menu_model' );
		$this->load->helper ( 'general' );
	}

	/**
	 * show a sutdent's information based on the student's id
	 */
	function view()
	{
		$kStudent = $this->uri->segment ( 3 );
		$student = $this->student_model->get ( $kStudent );
		$student->pronouns = $this->menu_model->get_label ( "gender", $student->stuGender );
		$data ['student'] = $student;
		$data ['teacherName'] = "";
		if (! empty ( $student->kTeach )) {
			// $data['teacherName'] = $this->teacher_model->get_name( $student->kTeach);
		}
		$options = array (
				"school_year" => get_current_year () 
		);
		$this->load->model ( "grade_preference_model", "preference" );
		$data ['grade_preferences'] = $this->preference->get_all ( $kStudent, $options );
		// if($student->stuGrade > 4){
		// $data['humanitiesTeacher'] =
		// $this->teacher_model->get($student->humanitiesTeacher,"teachFirst")->teachFirst;
		// }
		
		$data ['target'] = "student/view";
		$data ['title'] = "Viewing $student->stuFirst $student->stuLast";
		if ($this->input->post ( "ajax" )) {
			$this->load->view ( $data ['target'], $data );
		} else {
			$this->load->view ( "page/index", $data );
		}
	}

	/**
	 * find a student by name based on the get input
	 * the "mini" option produces a miniaturized list with less information
	 * displayed
	 * to the user
	 */
	function find_by_name()
	{
		$stuName = $this->input->get ( "stuName" );
		$data ['criteria'] = array ();
		$target = "student/list";
		if ($this->input->get ( "type" ) == "mini") {
			$target = "student/mini_list";
			$data ['js_class'] = $this->input->get ( "js_class" );
		}
		$data ['students'] = $this->student_model->find_students ( $stuName );
		$data ['teacher'] = NULL;
		$this->load->view ( $target, $data );
	}

	/**
	 * display an interface for creating a new student record
	 */
	function create()
	{
		$data ['student'] = NULL;
		$data ['action'] = "insert";
		$grades = $this->menu_model->get_pairs ( "grade" );
		$data ['gradePairs'] = get_keyed_pairs ( $grades, array (
				"value",
				"label" 
		) );
		$gender = $this->menu_model->get_pairs ( "gender" );
		$data ['genderPairs'] = get_keyed_pairs ( $gender, array (
				"value",
				"label" 
		) );
		$teachers = $this->teacher_model->get_teacher_pairs ();
		$data ['teacherPairs'] = get_keyed_pairs ( $teachers, array (
				"kTeach",
				"teacher" 
		) );
		$data ['target'] = "student/edit";
		$data ['title'] = "Add a New Student";
		$this->_view ( $data );
	}

	/**
	 * display an edit interface for
	 */
	function edit($kStudent)
	{
		if ($kStudent) {
			$student = $this->student_model->get ( $kStudent );
			if (empty ( $student->stuEmail ) && get_current_grade ( $student->baseGrade, $student->baseYear, get_current_year () ) > 2) {
				$student->stuEmail = $this->generate_email ( $kStudent, $student->stuNickname, $student->stuLast );
			}
			$humanitiesTeachers = $this->teacher_model->get_for_subject ( "Humanities" );
			$data ['humanitiesTeachers'] = get_keyed_pairs ( $humanitiesTeachers, array (
					"kTeach",
					"teacherName" 
			) );
			$data ['student'] = $student;
			$data ['action'] = "update";
			$grades = $this->menu_model->get_pairs ( "grade" );
			$data ['gradePairs'] = get_keyed_pairs ( $grades, array (
					"value",
					"label" 
			) );
			$gender = $this->menu_model->get_pairs ( "gender" );
			$data ['genderPairs'] = get_keyed_pairs ( $gender, array (
					"value",
					"label" 
			) );
			$teachers = $this->teacher_model->get_teacher_pairs ();
			$data ['teacherPairs'] = get_keyed_pairs ( $teachers, array (
					"kTeach",
					"teacherName" 
			) );
			$data ['target'] = "student/edit";
			$data ['title'] = sprintf ( "Edit %s", format_name ( $student->stuFirst, $student->stuLast, $student->stuNickname ) );
			$this->_view ( $data );
		}
	}

	function update()
	{
		if ($this->input->post ( "kStudent" )) {
			$kStudent = $this->input->post ( "kStudent" );
			$this->student_model->update ( $kStudent );
			redirect ( "/student/view/$kStudent" );
		}
	}

	function edit_classes()
	{
		if ($this->input->get ( "search" )) {
			$data ['title'] = "Sort Classes";
			$data ['target'] = "student/class/search";
			$data ['currentYear'] = get_current_year ();
			$data ['yearList'] = get_year_list ();
			
			$data ['groupings'] = array (
					"" => "",
					"humanitiesTeacher" => "Humanities",
					"classroom" => "Classroom",
					"advisory" => "Advisory",
					"ab" => "MS A/B Groups" 
			);
			if ($this->input->get ( "ajax" )) {
				$this->load->view ( $data ['target'], $data );
			} else {
				$this->load->view ( "page/index", $data );
			}
		} else {
			$this->load->helper ( "portlet" );
			$options = array ();
			$grades = array ();
			if ($type = $this->input->get ( "type" )) {
				$options ['type'] = $type;
				if ($type == "advisory") {
					$options ['grades'] = array (
							5,
							6,
							7,
							8 
					);
				}
			}
			burn_cookie ( "grades" );
			if ($this->input->get ( "grades" )) {
				$grades = $this->input->get ( "grades" );
				if (! empty ( $grades )) {
					$options ['grades'] = $grades;
					bake_cookie ( "grades", implode ( ",", $grades ) );
				}
			}
			
			$year = get_current_year ();
			if ($this->input->get ( "year" )) {
				$year = $this->input->get ( "year" );
			}
			$options ['grouping'] = TRUE;
			$data ['students'] = $this->student_model->get_all ( $year, $options );
			switch ($type) {
				case "humanitiesTeacher" :
					$data ['groups'] = $this->teacher_model->get_for_subject ( "Humanities" );
					$data ['target'] = "student/class/humanities";
					$title = sprintf ( "Middle School %s Humanities", implode ( "/", $grades ) );
					break;
				case "ab" :
					$data ['groups'] = array (
							"A",
							"B" 
					);
					$data ['target'] = "student/class/ab";
					$title = sprintf ( "Middle School %s A/B", implode ( "/", $grades ) );
					break;
				case "classroom" :
					$data ['groups'] = $this->teacher_model->get_by_grade_range ( $grades );
					$data ['target'] = "student/class/classroom";
					switch ($grades) {
						case array (
								1,
								2 
						) :
							$title = "Jungle/Prairie";
							break;
						case array (
								3,
								4 
						) :
							$title = "Bayou/Tundra";
							break;
						case array (
								0 
						) :
							$title = "Mississippi";
							break;
					}
					break;
				case "advisory" :
					$data ['groups'] = $this->teacher_model->get_teacher_pairs ( 2, 1, "advisor" );
					$title = "Middle School Advisory";
					$data ['target'] = "student/class/classroom";
					break;
			}
			$data ['type'] = $type;
			$data ['scripts'] = array (
					"portlet.js" 
			);
			$data ['styles'] = array (
					"portlet.css" 
			);
			$data ['title'] = "Organize Students by $title " . format_schoolyear ( $year );
			$this->load->view ( "page/index", $data );
		}
	}

	function update_class()
	{
		$kStudent = $this->input->post ( "kStudent" );
		$type = $this->input->post ( "type" );
		$id = $this->input->post ( "id" );
		$result = 0;
		switch ($type) {
			case "humanitiesTeacher" :
				$data ["humanitiesTeacher"] = $id;
				break;
			case "classroom" :
			case "advisory" :
				$data ["kTeach"] = $id;
				break;
			case "ab" :
				$data ['stuGroup'] = $id;
				break;
		}
		$result = $this->student_model->update_value ( $kStudent, $data );
		echo $result;
	}

	function insert()
	{
		$kStudent = $this->student_model->insert ();
		redirect ( "/student/view/$kStudent" );
	}

	function delete()
	{
		// set the default response.
		// the jquery javascript expects a comma-separated string with a boolean
		// followed by an alert string
		$result = "0,This script failed because no student id was submitted";
		
		// only allow the administrator to delete a student record.
		if ($this->session->userdata ( 'dbRole' ) == '1') {
			if ($this->input->post ( "kStudent" )) {
				$kStudent = $this->input->post ( "kStudent" );
				$result = $this->student_model->delete ( $kStudent );
			}
		}
		echo $result;
	}

	function teacher_student_list()
	{
		$kTeach = $this->uri->segment ( 3 );
		$data ['students'] = $this->student_model->get_students_by_class ( $kTeach );
		$data ['target'] = "student/list";
		$data ['title'] = "Student List";
		$this->load->view ( "page/index", $data );
	}

	function search()
	{
		$year = get_current_year ();
		burn_cookie ( "year" );
		if ($this->input->get ( "year" )) {
			$year = $this->input->get ( "year" );
			bake_cookie ( "year", $year );
		}
		$options = array ();
		$grades = array ();
		burn_cookie ( "grades" );
		if ($this->input->get ( "grades" )) {
			$grades = $this->input->get ( "grades" );
			if (! empty ( $grades )) {
				$options ['grades'] = $grades;
				bake_cookie ( "grades", implode ( ",", $grades ) );
			}
		}
		$hasNeeds = 0;
		burn_cookie ( "hasNeeds" );
		if ($this->input->get ( "hasNeeds" )) {
			$hasNeeds = $this->input->get ( "hasNeeds" );
			$options ['hasNeeds'] = $hasNeeds;
			bake_cookie ( "hasNeeds", $hasNeeds );
		}
		$stuGroup = 0;
		burn_cookie ( "stuGroup" );
		if ($this->input->get ( "stuGroup" )) {
			$stuGroup = $this->input->get ( "stuGroup" );
			$options ['stuGroup'] = $stuGroup;
			bake_cookie ( "stuGroup", $stuGroup );
		}
		
		$includeFormerStudents = 0;
		burn_cookie ( "includeFormerStudents" );
		if ($this->input->get ( "includeFormerStudents" )) {
			$includeFormerStudents = $this->input->get ( "includeFormerStudents" );
			$options ['includeFormerStudents'] = $includeFormerStudents;
			bake_cookie ( "includeFormerStudents", $includeFormerStudents );
		}
		$kTeach = 0;
		burn_cookie ( "kTeach" );
		if ($this->input->get ( "kTeach" )) {
			$kTeach = $this->input->get ( "kTeach" );
			$options ['kTeach'] = $kTeach;
			$options ['teacher'] = $this->teacher_model->get ( $kTeach )->teacherName;
			bake_cookie ( "kTeach", $kTeach );
		}
		
		$humanitiesTeacher = 0;
		burn_cookie ( "humanitiesTeacher" );
		if ($this->input->get ( "humanitiesTeacher" )) {
			$humanitiesTeacher = $this->input->get ( "humanitiesTeacher" );
			$options ['humanitiesTeacher'] = $humanitiesTeacher;
			$options ['humanitiesName'] = $this->teacher_model->get ( $humanitiesTeacher )->teacherName;
			bake_cookie ( "humanitiesTeacher", $humanitiesTeacher );
		}
		
		$sorting = NULL;
		if ($this->input->get ( "sorting" )) {
			$sorting = $this->input->get ( "sorting" );
			$options ['sorting'] = $sorting;
			bake_cookie ( "sorting", $sorting );
		}
		$grouping = NULL;
		if ($this->input->get ( "grouping" )) {
			$grouping = $this->input->get ( "grouping" );
			$options ['grouping'] = $grouping;
			bake_cookie ( 'grouping', $grouping );
		}
		// $this->session->set_userdata($session);
		$data ['students'] = $this->student_model->get_all ( $year, $options );
		$options ['year'] = $year;
		$data ['criteria'] = $options;
		$data ['title'] = "Student List";
		if ($this->input->get ( "export" )) {
			$this->load->helper ( "download" );
			$this->load->view ( "student/export", $data );
		} else {
			$data ['target'] = "student/results";
			$this->load->view ( "page/index", $data );
		}
	}
	
	// @TODO this needs to also check teacher email accounts to avoid
	// duplication there.
	// @TODO this needs an error catch mechanism for situations where there is
	// no post and no submitted variables
	function valid_email($kStudent = FALSE, $stuEmail = FALSE)
	{
		$count = 0;
		if (! $kStudent) {
			$kStudent = $this->input->get_post ( "kStudent" );
		}
		if (! $stuEmail) {
			$stuEmail = $this->input->get_post ( "stuEmail" );
		}
		if ($stuEmail) {
			$count = $this->student_model->count ( "stuEmail", $stuEmail, array (
					"kStudent" => $kStudent 
			) );
			if ($this->input->get_post ( "validation" )) {
				echo $count;
			}
		}
		return $count;
	}

	function generate_email($kStudent = FALSE, $first = FALSE)
	{
		if (! $kStudent) {
			$kStudent = $this->input->get_post ( "kStudent" );
		}
		
		if (! $first) {
			$first = $this->input->get_post ( "first" );
		}
		
		if ($kStudent && $first) {
			$name = $first;
			// @TODO this could be replaced with a grep that selects only
			// letters.
			// @TODO may want to accommodate accented characters.
			$name = str_replace ( " ", "", $name );
			$stuEmail = strtolower ( "$name" ) . "1@fsmn.org";
			if ($this->input->get_post ( "ajax" ) == TRUE) {
				echo $stuEmail;
			}
			return $stuEmail;
		}
	}

	function update_all_emails()
	{
		$students = $this->student_model->get_students_by_grade ( 3, 3 );
		foreach ( $students as $student ) {
			$this->student_model->update_value ( $student->kStudent, array (
					"stuEmail" => $this->generate_email ( $student->kStudent, $student->stuNickname ) 
			) );
			$this->student_model->update_value ( $student->kStudent, array (
					"stuEmailPassword" => $this->generate_password ( $student->stuFirst, $student->stuLast ) 
			) );
		}
	}

	function generate_password($stuFirst = NULL, $stuLast = NULL)
	{
		if (! $stuFirst) {
			$stuFirst = $this->input->get ( "stuFirst" );
		}
		if (! $stuLast) {
			$stuLast = $this->input->get ( "stuLast" );
		}
		
		$password = $stuFirst [0] . $stuLast [strlen ( $stuLast ) - 1] . "@1365!";
		echo $password;
		return $password;
	}

	/**
	 * ***** MAINTENANCE SCRIPTS ********
	 */
	function update_grades()
	{
		$this->student_model->update_grades ();
		$this->input->set_cookie ( array (
				'name' => 'admin',
				'value' => 'Student grades were successfully updated',
				'expire' => '60' 
		) );
		redirect ( "admin" );
	}
}
