<?php

defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class benchmark_legend extends MY_Controller {

	function __construct()
	{
		parent::__construct ();
		$this->load->model ( "benchmark_legend_model", "legend" );
		$this->load->model ( "subject_model" );
	}

	/**
	 *
	 * @return object array
	 *         returns an ojbect array from the benchmark_legend model with foot notes for a given
	 *         benchmark chart.
	 */
	function get()
	{
		$variables = array (
				"kTeach",
				"title",
				"term",
				"year",
				"gradeStart",
				"gradeEnd",
				"subject",
				"legend" 
		);
		$params = array ();
		for($i = 0; $i < count ( $variables ); $i ++) {
			$myVariable = $variables [$i];
			if ($this->input->post ( $myVariable )) {
				$params [$myVariable] = $this->input->post ( $myVariable );
			}
		}
		$legend = $this->legend->get_one ( $params );
		return $legend;
	}

	/**
	 * produce a page displaying the legend info for teachers to evaluate and edit.
	 */
	function view()
	{
		$kLegend = $this->uri->segment ( 3 );
		$legend = $this->legend->get ( $kLegend );
		$this->load->model ( "teacher_model" );
		$data ["legend"] = $legend;
		$teacher = $this->teacher_model->get ( $legend->kTeach, "teachFirst,teachLast" );
		$data ["teacher"] = $teacher->teachFirst . " " . $teacher->teachLast;
		$data ["title"] = "Benchmark Legend";
		$data ["target"] = "benchmark_legend/view";
		$this->load->view ( "page/index", $data );
	}

	/**
	 * edit a given benchmark legend.
	 */
	function edit()
	{
		$kLegend = $this->uri->segment ( 3 );
		$legend = $this->legend->get ( $kLegend );
		$data ["legend"] = $legend;
		$subjects = $this->subject_model->get_for_teacher ( $this->session->userdata ( "userID" ) );
		
		$data ["subjects"] = get_keyed_pairs ( $subjects, array (
				"subject",
				"subject" 
		), FALSE );
		$data ["rich_text"] = TRUE;
		$data ["action"] = "update";
		$data ["target"] = "benchmark_legend/edit";
		$data ["title"] = "Editing Benchmark Legend";
		$this->load->view ( "page/index", $data );
	}

	/**
	 * create a dialog for inserting a new benchmark legend
	 */
	function create()
	{
		$kTeach = $this->session->userdata ( "userID" );
		$data ["legend"] = ( object ) array (
				"kTeach" => $kTeach 
		);
		$subjects = $this->subject_model->get_for_teacher ( $kTeach );
		$data ["subjects"] = get_keyed_pairs ( $subjects, array (
				"subject",
				"subject" 
		), FALSE );
		$data["rich_text"] = TRUE;
		$data ["action"] = "insert";
		$data ["target"] = "benchmark_legend/edit";
		$data ["title"] = "Create a New Legend";
		$this->load->view ( "page/index", $data );
	}

	/**
	 * update the benchmark legend in the database
	 */
	function update()
	{
		$kLegend = $this->input->post ( "kLegend" );
		if ($kLegend) {
			$this->legend->update ( $kLegend );
		}
		redirect ( "benchmark_legend/view/$kLegend" );
	}

	/**
	 * insert the a new legend into the database
	 */
	function insert()
	{
		$kLegend = $this->legend->insert ();
		redirect ( "benchmark_legend/view/$kLegend" );
	}

	/**
	 * list all the benchmark legends based on any search criteria submitted in the post data
	 */
	function list_all()
	{
		$this->load->model ( "benchmark_legend_model" );
		$variables = array (
				"kTeach",
				"title",
				"term",
				"year",
				"gradeStart",
				"gradeEnd",
				"subject",
				"legend" 
		);
		$params = array ();
		for($i = 0; $i < count ( $variables ); $i ++) {
			$myVariable = $variables [$i];
			if ($this->input->get_post ( $myVariable )) {
				$params [$myVariable] = $this->input->get_post ( $myVariable );
			}
		}
		$data ["params"] = $params;
		$data ["legends"] = $this->legend->search ( $params );
		$data ["target"] = "benchmark_legend/list";
		$data ["title"] = "Benchmark Legend List";
		$data ["kTeach"] = $this->session->userdata ( "userID" );
		$this->load->view ( "page/index", $data );
	}

	/**
	 * display a search dialog for finding benchmarks for a given teacher.
	 */
	function search()
	{
		$this->load->model ( "subject_model" );
		$this->load->model ( "teacher_model" );
		$this->load->model ( "menu_model" );
		$kTeach = $this->session->userdata ( "userID" );
		$data ["kTeach"] = $kTeach;
		$grades = $this->menu_model->get_pairs ( "grade" );
		$data ["grade_list"] = get_keyed_pairs ( $grades, array (
				"value",
				"label" 
		) );
		$data ["years"] = get_year_list ( TRUE );
		$subjects = $this->subject_model->get_for_teacher ( $kTeach );
		$data ["subjects"] = get_keyed_pairs ( $subjects, array (
				"subject",
				"subject" 
		), TRUE );
		$data ["grades"] = $this->teacher_model->get ( $kTeach, array (
				"gradeStart",
				"gradeEnd" 
		) );
		$data ["title"] = "Search Benchmarks";
		$data ["target"] = "benchmark_legend/search";
		if ($this->input->get ( "ajax" )) {
			$this->load->view ( $data ["target"], $data );
		} else {
			$this->load->view ( "page/index", $data );
		}
	}
}