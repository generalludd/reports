<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Assignment extends MY_Controller {

	function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'grade_model', 'grade' );
		$this->load->model ( 'assignment_model', 'assignment' );
		$this->load->helper ( 'grade_helper' );
	}

	function index()
	{
		$this->chart ();
	}

	/**
	 * produce a chart of assignments and student grades based on the submitted
	 * criteria
	 */
	function chart()
	{
		$kTeach = $this->session->userdata ( 'userID' );
		if ($this->input->get ( 'kTeach' )) {
			$kTeach = $this->input->get ( 'kTeach' );
		}
		
		$gradeStart = $this->input->get ( 'gradeStart' );
		bake_cookie ( 'assignment_grade_start', $gradeStart );
		$gradeEnd = $this->input->get ( 'gradeEnd' );
		bake_cookie ( 'assignment_grade_end', $gradeEnd );
		
		$term = get_current_term ();
		if ($this->input->get ( 'term' )) {
			$term = $this->input->get ( 'term' );
			bake_cookie ( 'term', $term );
		}
		
		$stuGroup = NULL;
		if ($this->input->get ( 'stuGroup' )) {
			$stuGroup = $this->input->get ( 'stuGroup' );
		}
		
		bake_cookie ( 'stuGroup', $stuGroup );
		
		$year = get_current_year ();
		if ($this->input->get ( 'year' )) {
			$year = $this->input->get ( 'year' );
			bake_cookie ( 'year', $year );
		}
		
		$date_range = array ();
		$date_start = FALSE;
		$date_end = FALSE;
		
		if ($this->input->get ( 'date_start' ) && $this->input->get ( 'date_end' )) {
			$date_start = $this->input->get ( 'date_start' );
			$date_end = $this->input->get ( 'date_end' );
			bake_cookie ( 'assignment_date_start', $date_start );
			bake_cookie ( 'assignment_date_end', $date_end );
			// } elseif (get_cookie('assignment_date_start') &&
			// get_cookie('assignment_date_end')) {
			// $date_start = get_cookie('assignment_date_start');
			// $date_end = get_cookie('assignment_date_end');
		} else {
			burn_cookie ( 'assignment_date_end' );
			burn_cookie ( 'assignment_date_start' );
		}
		if ($date_start && $date_end) {
			$date_range ['date_start'] = $date_start;
			$date_range ['date_end'] = $date_end;
		}
		
		$grade_options ['from'] = 'grade';
		$grade_options ['join'] = 'assignment';
		if ($sort_order = $this->input->get ( 'student_sort_order' )) {
			$this->load->model ( 'preference_model', 'preference' );
			$this->preference->update ( $kTeach, 'student_sort_order', $sort_order );
			bake_cookie ( 'student_sort_order', $sort_order );
		} else {
			$sort_order = get_cookie ( 'student_sort_order' );
		}
		$data ['grades'] = $this->assignment->get_grades ( $kTeach, $term, $year, $gradeStart, $gradeEnd, $stuGroup, $date_range, $sort_order );
		foreach ( $data ['grades'] as $grade ) {
			$grade_options ['subject'] = $grade->subject;
			$grade->final_grade = $this->assignment->get_for_student ( $grade->kStudent, $grade->term, $grade->year, $grade_options );
		}
		$data ['assignments'] = $this->assignment->get_for_teacher ( $kTeach, $term, $year, $gradeStart, $gradeEnd, $date_range );
		
		$data ['category_count'] = $this->assignment->count_categories ( $kTeach, $gradeStart, $gradeEnd, $year, $term );
		
		$data ['kTeach'] = $kTeach;
		$data ['term'] = $term;
		$data ['year'] = $year;
		$data ['stuGroup'] = $stuGroup;
		$data ['gradeStart'] = $gradeStart;
		$data ['gradeEnd'] = $gradeEnd;
		
		if ($this->input->get ( 'print' ) == 1) {
			// print each student report separated with page breaks.
		} else {
			$data ['target'] = 'assignment/chart';
			$data ['title'] = 'Grade Chart';
			$this->load->view ( 'page/index', $data );
		}
	}

	/**
	 * display a search dialog for showing a grade chart.
	 */
	function search($kTeach = FALSE)
	{
		// if the teacher is not identified, search for the current user's grades
		$data ['kTeach'] = $this->session->userdata ( 'userID' );
		if ($kTeach) {
			$data ['kTeach'] = $kTeach;
		}
		$data ['term'] = $this->input->cookie ( 'term' );
		$data ['year'] = $this->input->cookie ( 'year' );
		$data ['gradeStart'] = $this->input->cookie ( 'assignment_grade_start' );
		$data ['gradeEnd'] = $this->input->cookie ( 'assignment_grade_end' );
		$data ['stuGroup'] = $this->input->cookie ( 'stuGroup' );
		$data ['date_range'] ['date_start'] = get_cookie ( 'assignment_date_start' );
		$data ['date_range'] ['date_end'] = get_cookie ( 'assignment_date_end' );
		$data ['target'] = 'assignment/search';
		$data ['title'] = 'Searching for Grades';
		if ($this->input->get ( 'ajax' )) {
			$this->load->view ( $data ['target'], $data );
		} else {
			$this->load->view ( 'page/index', $data );
		}
	}

	/**
	 * display a dialog for creating a new assignment
	 */
	function create($kTeach)
	{
		$data ['assignment'] = NULL;
		$data ['action'] = 'insert';
		$this->load->model ( 'subject_model' );
		$subjects = $this->subject_model->get_for_teacher ( $kTeach );
		$data ['subjects'] = get_keyed_pairs ( $subjects, array (
				'subject',
				'subject' 
		) );
		$userID = $kTeach;
		// $gradeStart = $this->session->userdata('gradeStart');
		// $gradeEnd = $this->session->userdata('gradeEnd');
		$gradeStart = $this->input->cookie ( 'assignment_grade_start' );
		$gradeEnd = $this->input->cookie ( 'assignment_grade_end' );
		$year = $this->input->cookie ( 'year' );
		$term = $this->input->cookie ( 'term' );
		$categories = $this->assignment->get_categories ( $userID, $gradeStart, $gradeEnd, $year, $term );
		if (empty ( $categories )) {
			$gradeRange = sprintf ( 'grades %s to %s', $gradeStart, $gradeEnd );
			if ($gradeStart == $gradeEnd) {
				$gradeRange = sprintf ( 'grade %s', $gradeStart );
			}
			printf ( '<p>You must create categories for %s first for %s, %s.<p/>', $gradeRange, $term, $year );
		} else {
			$data ['categories'] = get_keyed_pairs ( $categories, array (
					'kCategory',
					'category' 
			) );
			$data ['target'] = 'assignment/edit';
			$data ['title'] = 'Create an Assignment';
			$this->load->view ( $data ['target'], $data );
		}
	}

	function create_batch()
	{
		$kTeach = $this->input->get ( 'kTeach' );
		$gradeStart = $this->input->get ( 'gradeStart' );
		$gradeEnd = $this->input->get ( 'gradeEnd' );
		$year = $this->input->get ( 'year' );
		$term = $this->input->get ( 'term' );
		
		$categories = $this->assignment->get_categories ( $kTeach, $gradeStart, $gradeEnd, $year, $term );
		if (empty ( $categories )) {
			$gradeRange = sprintf ( 'grades %s to %s', $gradeStart, $gradeEnd );
			if ($gradeStart == $gradeEnd) {
				$gradeRange = sprintf ( 'grade %s', $gradeStart );
			}
			$this->session->set_flashdata ( 'warning', sprintf ( '<p>You must create categories for %s first for %s, %s.<p/>', $gradeRange, $term, $year ) );
			redirect ( '/' );
		} else {
			$data ['kTeach'] = $kTeach;
			$data ['gradeStart'] = $gradeStart;
			$data ['gradeEnd'] = $gradeEnd;
			$data ['year'] = $year;
			$data ['term'] = $term;
			$data ['count'] = 0; // used in the batch row adding option
			$this->load->model ( 'subject_model' );
			
			$subjects = $this->subject_model->get_for_teacher ( $kTeach );
			$data ['subjects'] = get_keyed_pairs ( $subjects, array (
					'subject',
					'subject' 
			) );
			$data ['categories'] = get_keyed_pairs ( $categories, array (
					'kCategory',
					'category' 
			) );
			$data ['target'] = 'assignment/batch/index';
			$data ['title'] = 'Enter Batch Assignments';
			if ($this->input->get ( "ajax" )) {
				$this->_view ( $data );
			} else {
				$this->load->view ( 'page/index', $data );
			}
		}
	}

	/**
	 * insert an assignment into the database
	 */
	function insert()
	{
		$kAssignment = $this->assignment->insert ();
		$kTeach = $this->input->post ( 'kTeach' );
		$term = $this->input->post ( 'term' );
		$year = $this->input->post ( 'year' );
		$gradeStart = $this->input->post ( 'gradeStart' );
		$gradeEnd = $this->input->post ( 'gradeEnd' );
		$points = 0;
		bake_cookie ( 'kCategory', $this->input->post ( 'kCategory' ) );
		$prepopulate = $this->input->post ( 'prepopulate' );
		bake_cookie ( 'prepopulate', $prepopulate );
		if ($prepopulate == 1) {
			$points = $this->input->post ( 'points' );
		}
		$students = $this->grade->batch_insert ( $kAssignment, $kTeach, $term, $year, $gradeStart, $gradeEnd, $points );
		redirect ( "assignment/chart?kTeach=$kTeach&term=$term&year=$year&gradeStart=$gradeStart&gradeEnd=$gradeEnd" );
	}

	/**
	 * insert batch assignments using a special form.
	 */
	function insert_batch()
	{
		$kTeach = $this->input->post ( 'kTeach' );
		$term = $this->input->post ( 'term' );
		$year = $this->input->post ( 'year' );
		$gradeStart = $this->input->post ( 'gradeStart' );
		$gradeEnd = $this->input->post ( 'gradeEnd' );
		$assignment = $this->input->post ( 'assignment' );
		$count = count ( $assignment );
		
		$date = $this->input->post ( 'date' );
		$points = $this->input->post ( 'points' );
		$prepopulate = $this->input->post ( 'prepopulate' );
		$subject = $this->input->post ( 'subject' );
		$kCategory = $this->input->post ( 'kCategory' );
		$values ['kTeach'] = $kTeach;
		$values ['year'] = $year;
		$values ['term'] = $term;
		$values ['gradeStart'] = $gradeStart;
		$values ['gradeEnd'] = $gradeEnd;
		if ($count < 2) {
			$this->session->set_flashdata ( 'warning', 'You need to enter at least two assignments for this to work' );
			redirect ( 'assignment/create_batch' );
		} else {
			for($i = 0; $i < $count; $i ++) {
				$values ['assignment'] = $assignment [$i];
				$student_points = 0;
				if ($prepopulate [$i] == 1) {
					$student_points = $points [$i];
				}
				$values ['points'] = $points [$i];
				$values ['kCategory'] = $kCategory [$i];
				$values ['subject'] = $subject [$i];
				$values ['date'] = $date [$i];
				$kAssignment = $this->assignment->insert ( $values );
				$this->grade->batch_insert ( $kAssignment, $kTeach, $term, $year, $gradeStart, $gradeEnd, $points [$i] );
			}
			redirect ( "assignment/chart?kTeach=$kTeach&term=$term&year=$year&gradeStart=$gradeStart&gradeEnd=$gradeEnd" );
		}
	}

	/**
	 * display a dialog for editing an assignment
	 */
	function edit($kAssignment)
	{
		$assignment = $this->assignment->get ( $kAssignment );
		$this->load->model ( 'subject_model' );
		$kTeach = $assignment->kTeach;
		$subjects = $this->subject_model->get_for_teacher ( $kTeach );
		$data ['subjects'] = get_keyed_pairs ( $subjects, array (
				'subject',
				'subject' 
		) );
		$data ['assignment'] = $assignment;
		$data ['action'] = 'update';
		$categories = $this->assignment->get_categories ( $assignment->kTeach, $assignment->gradeStart, $assignment->gradeEnd, $assignment->year, $assignment->term );
		$data ['categories'] = get_keyed_pairs ( $categories, array (
				'kCategory',
				'category' 
		) );
		$data ['title'] = sprintf ( 'Editing Assignment %s', $assignment->assignment );
		$data ['target'] = 'assignment/edit';
		if ($this->input->get ( 'ajax' )) {
			$this->load->view ( $data ['target'], $data );
		} else {
			$this->load->view ( 'page/index', $data );
		}
	}

	/**
	 * update an assignment and redirect to the established grade/date range for
	 * the assignment.
	 */
	function update()
	{
		$kAssignment = $this->input->post ( 'kAssignment' );
		$this->assignment->update ( $kAssignment );
		$kTeach = $this->input->post ( 'kTeach' );
		$term = $this->input->post ( 'term' );
		$year = $this->input->post ( 'year' );
		$gradeStart = $this->input->post ( 'gradeStart' );
		$gradeEnd = $this->input->post ( 'gradeEnd' );
		redirect ( "assignment/chart?kTeach=$kTeach&term=$term&year=$year&gradeStart=$gradeStart&gradeEnd=$gradeEnd" );
	}

	/**
	 * delete an assignment and return to the assignment's term and grade range
	 */
	function delete()
	{
		if ($this->input->post ( 'kAssignment' )) {
			$kAssignment = $this->input->post ( 'kAssignment' );
			$assignment = $this->assignment->get ( $kAssignment );
			$this->assignment->delete ( $kAssignment );
		}
		$kTeach = $assignment->kTeach;
		$term = $assignment->term;
		$year = $assignment->year;
		$gradeStart = $assignment->gradeStart;
		$gradeEnd = $assignment->gradeEnd;
		// redirect('assignment/chart?kTeach=$kTeach&term=$term&year=$year&gradeStart=$gradeStart&gradeEnd=$gradeEnd');
	}

	/**
	 * return a table row for creating assignment weight categories
	 * (AJAX-based).
	 */
	function create_category($kTeach)
	{
		$data ['category'] = NULL;
		$data ['action'] = 'insert';
		$data['kTeach'] = $kTeach;
		$this->load->view ( 'assignment/category/row', $data );
	}

	function point_types_menu()
	{
		$this->load->model ( "menu_model", "menu" );
		$menu_items = get_keyed_pairs ( $this->menu->get_pairs ( "points_type" ), array (
				"value",
				"label" 
		) );
		echo form_dropdown ( "points_type", $menu_items );
	}

	/**
	 * add a created category into the database
	 */
	function insert_category()
	{
		$kTeach = $this->input->post ( 'kTeach' );
		$category = $this->input->post ( 'category' );
		$weight = $this->input->post ( 'weight' );
		$gradeStart = $this->input->post ( 'gradeStart' );
		$gradeEnd = $this->input->post ( 'gradeEnd' );
		$year = $this->input->post ( 'year' );
		$term = $this->input->post ( 'term' );
		$data = array ();
		if ($category && $weight && $gradeStart && $gradeEnd) {
			$data ['category'] = $category;
			$data ['kTeach'] = $kTeach;
			$data ['weight'] = $weight;
			$data ['gradeStart'] = $gradeStart;
			$data ['gradeEnd'] = $gradeEnd;
			$data ['year'] = $year;
			$data ['term'] = $term;
			$kCategory = $this->assignment->insert_category ( $data );
			$category = $this->assignment->get_category ( $kCategory );
			$data ['category'] = $category;
			$data ['action'] = 'update';
			$data ['kTeach'] = $kTeach;
			$this->load->view ( 'assignment/category/row', $data );
		} else {
			echo $this->db->last_query ();
		}
	}

	/**
	 * display a dialog for editing assignment weight categories.
	 */
	function edit_categories($kTeach)
	{
		$data ['kTeach'] = $kTeach;
		$data ['gradeStart'] = $this->input->get ( 'gradeStart' );
		$data ['gradeEnd'] = $this->input->get ( 'gradeEnd' );
		$data ['year'] = $this->input->get ( 'year' );
		$data ['term'] = $this->input->get ( 'term' );
		$data ['categories'] = $this->assignment->get_categories ( $data ['kTeach'], $data ['gradeStart'], $data ['gradeEnd'], $data ['year'], $data ['term'] );
		$data ['target'] = 'assignment/category/list';
		if ($this->input->get ( 'ajax' )) {
			$target = $data ['target'];
		} else {
			$target = 'page/index';
			$data ['title'] = 'Editing Categories';
		}
		$this->load->view ( $target, $data );
	}

	/**
	 * update an assignment weight category
	 */
	function update_category()
	{
		$kCategory = $this->input->post ( 'kCategory' );
		$data ['category'] = $this->input->post ( 'category' );
		$data ['weight'] = $this->input->post ( 'weight' );
		$data ['gradeStart'] = $this->input->post ( 'gradeStart' );
		$data ['gradeEnd'] = $this->input->post ( 'gradeEnd' );
		$data ['term'] = $this->input->post ( 'term' );
		$data ['year'] = $this->input->post ( 'year' );
		$this->assignment->update_category ( $kCategory, $data );
	}

	/**
	 * duplicate the grade categories for a teacher from a previous term
	 */
	function duplicate_categories()
	{
		if ($this->input->get ( "dialog" ) == 1) {
			$data ['title'] = "Duplicate Categories from Previous Term";
			$this->load->view ( "assignment/category/duplicate", $data );
		} else if ($this->input->get ( "duplicate" ) == 1) {
			$kTeach = $this->input->get ( "kTeach" );
			$year = $this->input->get ( "year" );
			$term = $this->input->get ( "term" );
			$gradeStart = $this->input->get ( "gradeStart" );
			$gradeEnd = $this->input->get ( "gradeEnd" );
			$sourceTerm = $this->input->get ( "sourceTerm" );
			$sourceYear = $this->input->get ( "sourceYear" );
			$sourceCategories = $this->assignment->get_categories ( $kTeach, $gradeStart, $gradeEnd, $sourceYear, $sourceTerm );
			foreach ( $sourceCategories as $source ) {
				unset($source->kCategory);
				$source->year = $year;
				$source->term = $term;
				$this->assignment->insert_category ( $source );
			}
			redirect ( "assignment/chart/?kTeach=$kTeach&gradeStart=$gradeStart&gradeEnd=$gradeEnd&year=$year&term=$term" );
		}
	}
}
