<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
	// @TODO: refine all get_post to the appropriate method.
class Teacher extends MY_Controller {

	function __construct()
	{
		parent::__construct ();
		$this->load->model ( "teacher_model" );
		$this->load->model ( "subject_model" );
		$this->load->model ( "menu_model" );
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @see MY_Controller::index() listing all teachers.
	 */
	function index()
	{
		$data ["target"] = "teacher/list";
		$data ["options"] = array ();
		if ($this->input->get_post ( "showInactive" )) {
			$data ["options"] ["showInactive"] = TRUE;
		}
		
		if ($this->input->get_post ( "showAdmin" )) {
			$data ["options"] ["showAdmin"] = TRUE;
		}
		
		$roles = $this->input->get_post ( "role" );
		if (! $roles) {
			$roles = array (
					0 => 2,
					1 => 3 
			);
		}
		foreach ( $roles as $role ) {
			$data ["roles"] [] = array (
					"value" => $role,
					"label" => $this->menu_model->get_label ( "dbRole", $role ) 
			);
		}
		$data ["options"] ["roles"] = $roles;
		/*
		 * $data["options"]["gradeRange"]["gradeStart"] = -1;
		 * $data["options"]["gradeRange"]["gradeEnd"] = -1;
		 * if ($this->input->get("gradeStart") && $this->input->get("gradeEnd")) {
		 * $gradeStart = $this->input->get("gradeStart");
		 * $gradeEnd = $this->input->get("gradeEnd");
		 * $data["options"]["gradeRange"]["gradeStart"] = $gradeStart;
		 * $data["options"]["gradeRange"]["gradeEnd"] = $gradeEnd;
		 * bake_cookie("gradeStart", $gradeStart);
		 * bake_cookie("gradeEnd", $gradeEnd);
		 * }
		 */
		
		$data ["teachers"] = $this->teacher_model->get_all ( $data ["options"] );
		$data ["options"] ["roles"] = $data ["roles"];
		$data ["title"] = "List of Teachers";
		$this->load->view ( "page/index", $data );
	}

	/**
	 * create a new editor/aide/administrator.
	 * At some point I want to separate teachers from these other roles.
	 * Teacher should be a characteristic of a user, This is a legacy of the way
	 * the system
	 * was originally developed.
	 */
	function create()
	{
		if ($this->session->userdata ( "dbRole" ) == 1) {
			$data ["dbRole"] = 2;
			$data ["action"] = "insert";
			$data ["target"] = "teacher/edit";
			$data ["title"] = "Insert a New Teacher";
			$data ["subjects"] = $this->subject_model->get_all ();
			$dbRoles = $this->menu_model->get_pairs ( "dbRole" );
			$data ["dbRoles"] = get_keyed_pairs ( $dbRoles, array (
					"value",
					"label" 
			) );
			$userStatus = $this->menu_model->get_pairs ( "userStatus" );
			$data ["userStatus"] = get_keyed_pairs ( $userStatus, array (
					"value",
					"label" 
			) );
			$grades = $this->menu_model->get_pairs ( "grade" );
			$data ["grades"] = get_keyed_pairs ( $grades, array (
					"value",
					"label" 
			) );
			$classrooms = $this->menu_model->get_pairs ( "classroom" );
			$data ["classrooms"] = get_keyed_pairs ( $classrooms, array (
					"value",
					"label" 
			) );
			$data ["teacher"] = NULL;
			if ($this->input->get_post ( "ajax" )) {
				$this->load->view ( $data ["target"], $data );
			} else {
				$this->load->view ( "page/index", $data );
			}
		} else {
			$this->index ();
		}
	}

	/**
	 * Show a given teacher based on the uri_segment.
	 */
	function view($kTeach)
	{
		if ($kTeach) {
			$kTeach = $this->uri->segment ( 3 );
			$teacher = $this->teacher_model->get ( $kTeach );
			if (! empty ( $teacher )) {
				$data ["year"] = get_current_year ();
				$data ["term"] = get_current_term ();
				$data ["kTeach"] = $kTeach;
				$data ["teacher"] = $teacher;
				$data ["subjects"] = $this->subject_model->get_for_teacher ( $kTeach );
				$classrooms = $this->menu_model->get_pairs ( "classroom" );
				$data ["classrooms"] = get_keyed_pairs ( $classrooms, array (
						"value",
						"label" 
				) );
				$grades = $this->menu_model->get_pairs ( "grade" );
				$data ["grades"] = get_keyed_pairs ( $grades, array (
						"value",
						"label" 
				) );
				$data ["target"] = "teacher/view";
				$data ["title"] = "Viewing Information for $teacher->teachFirst $teacher->teachLast";
				$this->load->view ( "page/index", $data );
			} else {
				$this->session->set_flashdata ( "warning", "No such user was found!" );
				redirect ( "teacher" );
			}
		} else {
			$this->session->set_flashdata ( "warning", "No teacher id was given!" );
			
			redirect ( "teacher" );
		}
	}

	/**
	 * edit the teacher's record.
	 */
	function edit()
	{
		$kTeach = $this->input->get_post ( "kTeach" );
		if ($this->session->userdata ( "userID" ) == $kTeach || $this->session->userdata ( "dbRole" ) == 1) {
			$teacher = $this->teacher_model->get ( $kTeach );
			$data ["dbRole"] = $this->session->userdata ( "dbRole" );
			$data ["userID"] = $this->session->userdata ( "userID" );
			$data ["teacher"] = $teacher;
			$data ["action"] = "update";
			$data ["subjects"] = $this->subject_model->get_for_teacher ( $kTeach );
			$dbRoles = $this->menu_model->get_pairs ( "dbRole" );
			$data ["dbRoles"] = get_keyed_pairs ( $dbRoles, array (
					"value",
					"label" 
			) );
			$userStatus = $this->menu_model->get_pairs ( "userStatus" );
			$data ["userStatus"] = get_keyed_pairs ( $userStatus, array (
					"value",
					"label" 
			) );
			$grades = $this->menu_model->get_pairs ( "grade" );
			$data ["grades"] = get_keyed_pairs ( $grades, array (
					"value",
					"label" 
			) );
			$classrooms = $this->menu_model->get_pairs ( "classroom" );
			$data ["classrooms"] = get_keyed_pairs ( $classrooms, array (
					"value",
					"label" 
			) );
			$data ["target"] = "teacher/edit";
			$data ["title"] = "Editing $teacher->teachFirst $teacher->teachLast";
			if ($this->input->get_post ( "ajax" )) {
				$this->load->view ( $data ["target"], $data );
			} else {
				$this->load->view ( 'page/index', $data );
			}
		} else {
			print "You are not authorized to edit this teacher record";
		}
		// }
	}

	function update()
	{
		if ($this->input->post ( "kTeach" )) {
			$kTeach = $this->input->post ( "kTeach" );
			$this->teacher_model->update ( $kTeach );
			redirect ( "teacher/view/$kTeach" );
		}
	}

	function insert()
	{
		if ($this->session->userdata ( "dbRole" ) == 1) {
			$kTeach = $this->teacher_model->insert ();
			redirect ( "teacher/view/$kTeach" );
		}
	}

	function teacher_menu()
	{
		if ($data = $this->input->get ( "data" )) {
			$data = json_decode ( $data );
		}
		$db_role = 2; // default to teachers
		if ($this->input->get ( "db_role" )) {
			$db_role = $this->input->get ( "db_role" );
		}
		$status = 1; // default to active users
		if ($this->input->get ( "status" )) {
			$status = $this->input->get ( "status" );
		}
		$teacher_group = "";
		if ($this->input->get ( "teacher_group" )) {
			$teacher_group = $this->input->get ( "teacher_group" );
		}
		
		$kTeach = "";
		if ($this->input->get ( "kTeach" )) {
			$kTeach = $this->input->get ( "kTeach" );
		}
		$settings = "";
		if ($this->input->get ( "settings" )) {
			$settings = $this->input->get ( "settings" );
		}
		$teachers = $this->teacher_model->get_teacher_pairs ( $db_role, $status, $teacher_group );
		$selections = get_keyed_pairs ( $teachers, array (
				"kTeach",
				"teacher" 
		), TRUE );
		$output = form_dropdown ( "kTeach", $selections, $kTeach, $settings );
		echo $output;
	}

	/**
	 * display a filtering dialog for showing various users of the system.
	 */
	function show_search()
	{
		$grade_list = $this->menu_model->get_pairs ( "grade" );
		/*
		 * $data["grades"] = get_keyed_pairs($grade_list, array(
		 * "value",
		 * "label"
		 * ),TRUE);
		 */
		$this->load->view ( "teacher/search" );
	}

	/**
	 * This is an ajax-supporting script designed to provide calling
	 * scripts a quick way to display a teacher's subject menu drop-down on the
	 * fly.
	 */
	function subject_menu()
	{
		$kTeach = $this->input->get_post ( "kTeach" );
		$this->load->model ( "subject_model" );
		$subjects = get_keyed_pairs ( $this->subject_model->get_for_teacher ( $kTeach ), array (
				"subject",
				"subject" 
		) );
		echo form_dropdown ( "subject", $subjects, $this->input->cookie ( "current_subject" ), "id='subject'" );
	}

	/**
	 * This is another ajax-supporting script that provides a list of grade
	 * options on the fly based on a teacher's profile.
	 */
	function grade_range($kTeach)
	{
		$this->load->model ( "menu_model" );
		$teacher_grades = $this->teacher_model->get ( $kTeach, "gradeStart,gradeEnd" );
		$grades = get_keyed_pairs ( $this->menu_model->get_pairs ( "grade" ), array (
				"value",
				"label" 
		) );
		if(($teacher_grades->gradeEnd - $teacher_grades->gradeStart) < 3){
		$grades = array($teacher_grades->gradeStart => format_grade($teacher_grades->gradeStart),$teacher_grades->gradeEnd => format_grade($teacher_grades->gradeEnd));
		}
		$output = form_dropdown ( "gradeStart", $grades, $teacher_grades->gradeStart, "id='gradeStart'" );
		$output .= "-" . form_dropdown ( "gradeEnd", $grades, $teacher_grades->gradeEnd, "id='gradeEnd'" );
		if ($this->input->get ( "ajax" ) == 1) {
			echo $output;
		} else {
			return $output;
		}
	}

	/**
	 * display a list of availabe subjects a teacher doesn't already have
	 * associated with their profile
	 */
	function add_subject()
	{
		$data ["kTeach"] = $this->input->get_post ( "kTeach" );
		$data ["gradeStart"] = $this->input->get_post ( "gradeStart" );
		$data ["gradeEnd"] = $this->input->get_post ( "gradeEnd" );
		
		$data ["subjects"] = $this->subject_model->get_missing ( $data ["kTeach"], $data );
		
		$grades = $this->menu_model->get_pairs ( "grade" );
		$data ["grades"] = get_keyed_pairs ( $grades, array (
				"value",
				"label" 
		) );
		$this->load->view ( "teacher/edit_subject", $data );
	}

	/**
	 * used with ajax, this adds a subject to the teacher's profile after the
	 * add_subject method has been called.
	 */
	function insert_subject()
	{
		$kTeach = $this->input->post ( "kTeach" );
		$subject = $this->input->post ( "subject" );
		$gradeStart = $this->input->post ( "subGradeStart" );
		$gradeEnd = $this->input->post ( "subGradeEnd" );
		$this->teacher_model->insert_subject ( $kTeach, $subject, $gradeStart, $gradeEnd );
		$teacher = $this->teacher_model->get ( $kTeach, "gradeStart,gradeEnd" );
		$data ['subjects'] = $this->subject_model->get_for_teacher ( $kTeach );
		$this->load->view ( "teacher/subject_list", $data );
	}

	/**
	 * used by ajax scripts, this deletes a subject on the fly.
	 */
	function delete_subject()
	{
		$kTeach = $this->input->post ( "kTeach" );
		$kSubject = $this->input->post ( "kSubject" );
		$this->teacher_model->delete_subject ( $kTeach, $kSubject );
		$data ['subjects'] = $this->subject_model->get_for_teacher ( $kTeach );
		$this->load->view ( "teacher/subject_list", $data );
	}
}