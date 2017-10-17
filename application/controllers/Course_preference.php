<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Course_preference extends MY_Controller {

	function __construct()
	{
		parent::__construct ();
		$this->load->model ( "course_preference_model", "preference" );
	}

	function edit($id)
	{
		$data ['action'] = "update";
		$data ['preference'] = $this->preference->get ( $id );
		$data ['kStudent'] = $data ['preference']->kStudent;
		$this->load->model ( "student_model", "student" );
		$grade = $this->student->get_grade ( $data ['kStudent'] );
		$this->load->model ( "subject_model", "subject" );
		$subject_list = $this->subject->get_by_grade ( $grade );
		$data ['subjects'] = get_keyed_pairs ( $subject_list, array (
				"subject",
				"subject" 
		) );
		$this->load->model("menu_model","menu");
		$data['year'] = $data['preference']->school_year;
		$data['course_preferences'] = get_keyed_pairs($this->menu->get_pairs("course_preference"),array('value','label'));
		$data ['title'] = "Editing a Course Preference";
		$data ['target'] = "course_preference/edit";
		
		$this->_view ( $data );
	}

	function create($kStudent)
	{
		$data ['preference'] = "";
		$data ['kStudent'] = $kStudent;
		
		$this->load->model ( "student_model", "student" );
		$grade = $this->student->get_grade ( $kStudent );
		$this->load->model ( "subject_model", "subject" );
		$subject_list = $this->subject->get_by_grade ( $grade );
		$data ['subjects'] = get_keyed_pairs ( $subject_list, array (
				"subject",
				"subject" 
		) );
		$this->load->model("menu_model","menu");
		$data['course_preferences'] = get_keyed_pairs($this->menu->get_pairs("course_preference"),array('value','label'));
		$data['year'] = get_current_year();
		$data ['title'] = "Adding a Course Preference";
		$data ['target'] = "course_preference/edit";
		
		$data ['action'] = "insert";
		$this->_view ( $data );
	}

	function view()
	{
		$kStudent = $this->input->get ( "kStudent" );
		$data ['kStudent'] = $kStudent;
		$options = array ();
		$options ['school_year'] = NULL;
		$options ['subject'] = NULL;
		if ($this->input->get ( "school_year" )) {
			$options ['school_year'] = $this->input->get ( "school_year" );
		}
		if ($this->input->get ( "subject" )) {
			$options ['subject'] = $this->input->get ( "school_year" );
		}
		$data ['preferences'] = $this->preference->get_all ( $kStudent, $options );
		$data ['target'] = "course_preference/list";
		$data ['title'] = "Course Preferences";
		$this->load->view ( "page/index", $data );
	}

	function insert()
	{
		$this->update ();
	}

	function update()
	{
		$kStudent = $this->input->post ( "kStudent" );
		$data ['kStudent'] = $kStudent;
		$data ['subject'] = $this->input->post ( "subject" );
		$data ['school_year'] = $this->input->post ( "school_year" );
		$data ['term'] = $this->input->post ( "term" );
		$data ['preference'] = $this->input->post ( "preference" );
		$this->preference->update ( $data );
		if ($this->input->post ( "redirect" )) {
			redirect ( $this->input->post ( "redirect" ) );
		} else {
			redirect ( "student/view/$kStudent" );
		}
	}

	function delete()
	{
		$id = $this->input->post ( "id" );
		$kStudent = $this->preference->delete ( $id );
		if (! $this->input->post ( "ajax" )) {
			redirect ( "student/view/$kStudent" );
		}
	}
}