<?php
class Overview extends MY_Controller {

	function __construct()
	{
		parent::__construct ();
		$this->load->model ( "overview_model", "overview" );
	}

	function create($kTeach)
	{
		$this->load->model ( "teacher_model" );
		$this->load->model ( "menu_model" );
		$this->load->model ( "subject_model" );
		$template = NULL;
		$data ['overview'] = "";
		$data ['kTeach'] = $kTeach;
		$subjects = $this->subject_model->get_for_teacher ( $kTeach );
		$data ['subjects'] = get_keyed_pairs ( $subjects, array (
				"subject",
				"subject" 
		), FALSE );
		$data ['years'] = get_year_list ();
		$grades = $this->menu_model->get_pairs ( "grade" );
		$data ['grade_list'] = get_keyed_pairs ( $grades, array (
				"value",
				"label" 
		) );
		$grade = $this->teacher_model->get ( $kTeach, array (
				"gradeStart",
				"gradeEnd" 
		) );
		/* if the teacher teaches more than 2 grades (specialists and humanities teachers) we need to narrow the range */
		if($grade->gradeEnd - $grade->gradeStart > 1){
		    $grade->gradeEnd = $grade->gradeStart;
        }
		$data ['gradeStart'] = $grade->gradeStart;
		$data ['gradeEnd'] = $grade->gradeEnd;
		$data ['isActive'] = 1;
		$data['scripts'] = ['ckeditor.js', 'editor.js'];
		$data ['target'] = "overview/edit";
		$data ['action'] = "insert";
		$data ['title'] = "Editing a Subject Overview";
		$this->load->view ( "page/index", $data );
	}

	function insert()
	{
		$kOverview = $this->overview->insert ();
		$data ['kOverview'] = $kOverview;
		if ($this->input->post ( "ajax" )) {
			if ($kOverview) {
				$data ['message'] = "The overview was successfully added at: " . date ( "m-d-Y g:i:s a" );
				echo json_encode ( $data );
			} else {
				$data ['message'] = "The overview did not get saved correctly. Please copy the text into your favorite text editor and contact technical support";
				echo json_encode ( $data );
			}
			$this->session->set_flashdata ( "message", $data ['message'] );
		} else {
			$kTeach = $this->input->post ( "kTeach" );
			$year = $this->input->post ( "year" );
			$term = $this->input->post ( "term" );
			$subject = $this->input->post ( "subject" );
			redirect ( "overview/show_all/?kTeach=$kTeach&term=$term&year=$year&subject=$subject" );
		}
	}

	/**
	 * Edit overview based on a uri segment overview id (kOverview)
	 */
	function edit($kOverview)
	{
		$this->load->model ( "teacher_model" );
		
		$this->load->model ( "menu_model" );
		$this->load->model ( "subject_model" );
		$overview = $this->overview->get ( $kOverview );
		$data ['overview'] = $overview;
		$data ['kTeach'] = $overview->kTeach;
		$subjects = $this->subject_model->get_for_teacher ( $overview->kTeach );
		$data ['subjects'] = get_keyed_pairs ( $subjects, array (
				"subject",
				"subject" 
		), FALSE );
		$data ['years'] = get_year_list ();
		$grades = $this->menu_model->get_pairs ( "grade" );
		$data ['gradeStart'] = "";
		$data ['gradeEnd'] = "";
		$data ['grade_list'] = get_keyed_pairs ( $grades, array (
				"value",
				"label" 
		) );
		$data['scripts'] = ['ckeditor.js', 'editor.js'];
		$data ['target'] = "overview/edit";
		$data ['action'] = "update";
		$data ['title'] = "Editing a Subject Overview";
		$this->load->view ( "page/index", $data );
	}

	function update()
	{
		$kOverview = $this->input->post ( "kOverview" );
		if ($this->input->get ( "delete" )) {
			$this->_delete ( $kOverview );
		} else {
			$data ['kOverview'] = $kOverview;
			$this->overview->update ( $kOverview );
			if ($this->input->post ( "ajax" )) {
				$data ['message'] = "The overview was successfully saved at " . date ( "m-d-Y g:i:s a" );
				echo json_encode ( $data );
			} else {
				$kTeach = $this->input->post ( "kTeach" );
				$year = $this->input->post ( "year" );
				$term = $this->input->post ( "term" );
				$subject = $this->input->post ( "subject" );
				$gradeStart = $this->input->post ( "gradeStart" );
				$gradeEnd = $this->input->post ( "gradeEnd" );
				redirect ( "overview/show_all/?kTeach=$kTeach&term=$term&year=$year&subject=$subject&gradeStart=$gradeStart&gradeEnd=$gradeEnd" );
			}
		}
	}

	/**
	 * search dialog for finding overviews.
	 * This redirects on submit to the view() function
	 */
	function search($kTeach)
	{
		$this->load->model ( "subject_model" );
		$this->load->model ( "teacher_model" );
		$this->load->model ( "menu_model" );
		$data ['kTeach'] = $kTeach;
		$grades = $this->menu_model->get_pairs ( "grade" );
		$data ['grade_list'] = get_keyed_pairs ( $grades, array (
				"value",
				"label" 
		) );
		$data ['years'] = get_year_list ( TRUE );
		$subjects = $this->subject_model->get_for_teacher ( $kTeach );
		$data ['subject'] = NULL;
		$data ['subjects'] = get_keyed_pairs ( $subjects, array (
				"subject",
				"subject" 
		), TRUE );
		$data ['grades'] = $this->teacher_model->get ( $kTeach, array (
				"gradeStart",
				"gradeEnd" 
		) );
		$this->load->view ( "overview/search", $data );
	}

	/**
	 * list overviews for a given teacher based on input data queries
	 * kTeach, term, year, subject, gradeStart, gradeEnd
	 * Only kTeach is mandatory.
	 */
	function show_all()
	{
		if ($kTeach = $this->input->get ( "kTeach" )) {
			$this->load->model ( "teacher_model" );
			$options = array ();
			if ($this->input->get ( "term" )) {
				$options ['where'] ['term'] = $this->input->get ( "term" );
			}
			
			if ($this->input->get ( "year" )) {
				$options ['where'] ['year'] = $this->input->get ( "year" );
			}
			
			if ($this->input->get ( "subject" )) {
				$options ['where'] ['subject'] = $this->input->get ( "subject" );
				// $this->session->set_userdata("template_subject",$options['where']['subject']);
				bake_cookie ( "overview_subject", $options ['where'] ['subject'] );
			}
			
			$include_inactive = FALSE;
			if ($this->input->get ( "include_inactive" )) {
				$include_inactive = TRUE;
			}
			
			if ($this->input->get ( "gradeStart" ) && $this->input->get ( "gradeEnd" )) {
				$options ['grade_range'] ['gradeStart'] = $this->input->get ( "gradeStart" );
				$options ['grade_range'] ['gradeEnd'] = $this->input->get ( "gradeEnd" );
			}
			
			$data ['overviews'] = $this->overview->get_all ( $kTeach, $options, $include_inactive );
			$data ['kTeach'] = $kTeach;
			$data ['teacher'] = $this->teacher_model->get_name ( $kTeach );
			$data ['target'] = "overview/list";
			$data ['title'] = "Listing Subject Overviews for " . link_teacher ( $data ['teacher'], $kTeach );
			$data ['options'] = $options;
			$this->load->view ( "page/index", $data );
		}
	}

	function view($kOverview)
	{
		$overview = $this->overview->get ( $kOverview );
		if ($this->input->get ( "ajax" ) == 1) {
			echo $this->load->view ( "overview/view", array (
					"overview" => $overview 
			), TRUE );
		} else {
			$data ['target'] = "overview/view";
			$data ['title'] = "Overview";
			$data ['overview'] = $overview;
			$this->load->view ( "page/index", $data );
		}
	}

	private function _delete($kOverview)
	{
		$overview = $this->overview->delete ( $kOverview );
		$this->session->set_flashdata ( "message", "The overview was successfully deleted" );
		redirect ( "overview/show_all/?kTeach=$overview->kTeach&term=$overview->term&year=$overview->year&subject=$overview->subject&gradeStart=$overview->gradeStart&gradeEnd=$overview->gradeEnd" );
	}
}