<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Benchmark extends MY_Controller {

	function __construct()
	{
		parent::__construct ();
		$this->load->helper ( "general" );
		$this->load->model ( "teacher_model" );
		$this->load->model ( "benchmark_model" );
		$this->load->model ( "subject_model" );
	}

	/**
	 * Deprecated
	 */
	function show_search()
	{
		$this->search ();
	}

	function search()
	{
		$kTeach = $this->session->userdata ( "userID" );
		$data ["kTeach"] = $kTeach;
		$data ["termMenu"] = get_term_menu ( "term", get_current_term () );
		$data ["subjects"] = $this->subject_model->get_for_teacher ( $kTeach );
		$data ["subject_list"] = get_keyed_pairs ( $data ["subjects"], array (
				"subject",
				"subject" 
		), FALSE );
		$data ["yearStart"] = get_current_year ();
		$data ["yearEnd"] = $data ["yearStart"] + 1;
		$teacher = $this->teacher_model->get ( $kTeach, array (
				"gradeStart",
				"gradeEnd" 
		) );
		$data ["gradeStart"] = $teacher->gradeStart;
		$data ["gradeEnd"] = $teacher->gradeEnd;
		$data ['target'] = "benchmark/search";
		$data ['title'] = "Searching Benchmarks";
		if ($this->input->get ( "ajax" )) {
			$this->load->view ( $data ['target'], $data );
		} else {
			$this->load->view ( "page/index", $data );
		}
	}

	function teacher_list()
	{
		if ($this->input->get ( "year" ) && $this->input->get ( "term" )) {
			$year = $this->input->get ( "year" );
			$term = $this->input->get ( "term" );
			$subject = $this->input->get ( "subject" );
			$grade_range ["gradeStart"] = $this->input->get ( "gradeStart" );
			$grade_range ["gradeEnd"] = $this->input->get ( "gradeEnd" );
			$data ["kTeach"] = $this->input->get ( "kTeach" );
			
			$data ["benchmarks"] = $this->benchmark_model->get_list ( $term, $year, $subject, $grade_range );
			
			$data ["title"] = "Benchmark Editing";
			$data ["target"] = "benchmark/list";
			$this->load->view ( "page/index", $data );
		}
	}

	function edit($kBenchmark)
	{
		$kTeach = $this->session->userdata ( "userID" );
		$benchmark = $this->benchmark_model->get ( $kBenchmark );
		$data ["kBenchmark"] = $kBenchmark;
		$data ["gradeStart"] = $benchmark->gradeStart;
		$data ["gradeEnd"] = $benchmark->gradeEnd;
		$data ["year"] = $benchmark->year;
		$data ["term"] = $benchmark->term;
		$data ["subject"] = $benchmark->subject;
		$data ["category"] = $benchmark->category;
		$data ["benchmark"] = $benchmark->benchmark;
		$data ["weight"] = $benchmark->weight;
		$subjects = $this->subject_model->get_for_teacher ( $kTeach );
		$data ["subjects"] = get_keyed_pairs ( $subjects, array (
				"subject",
				"subject" 
		), FALSE );
		$data ["action"] = "update";
		$data ["target"] = "benchmark/edit";
		$data ['title'] = "Editing Benchmark";
		if ($this->input->get ( "ajax" )) {
			$this->load->view ( $data ["target"], $data );
		} else {
			$this->load->view ( "page/index", $data );
		}
	}

	function create()
	{
		$data ["kBenchmark"] = "";
		$kTeach = $this->session->userdata ( "userID" );
		$teacher = $this->teacher_model->get ( $kTeach );
		$data ["gradeStart"] = $teacher->gradeStart;
		$data ["gradeEnd"] = $teacher->gradeEnd;
		$data ["year"] = get_current_year ();
		$data ["term"] = get_current_term ();
		$data ["subject"] = "";
		$data ["category"] = "";
		$data ["benchmark"] = "";
		$data ["weight"] = 0;
		$subjects = $this->subject_model->get_for_teacher ( $kTeach );
		$data ["subjects"] = get_keyed_pairs ( $subjects, array (
				"subject",
				"subject" 
		), FALSE );
		$data ["action"] = "insert";
		$data ["target"] = "benchmark/edit";
		$data["title"]= "Create a Benchmark";
		if($this->input->get("ajax")){
		$this->load->view ( $data ["target"], $data );
		}else{
			$this->load->view("page/index",$data);
		}
	}

	function duplicate($kBenchmark)
	{
		$kTeach = $this->session->userdata ( "userID" );
		$benchmark = $this->benchmark_model->get ( $kBenchmark );
		$data ["kBenchmark"] = NULL;
		$data ["gradeStart"] = $benchmark->gradeStart;
		$data ["gradeEnd"] = $benchmark->gradeEnd;
		$data ["year"] = $benchmark->year;
		$data ["term"] = $benchmark->term;
		$data ["subject"] = $benchmark->subject;
		$data ["category"] = $benchmark->category;
		$data ["benchmark"] = $benchmark->benchmark;
		$data ["weight"] = $benchmark->weight;
		$subjects = $this->subject_model->get_for_teacher ( $kTeach );
		$data ["subjects"] = get_keyed_pairs ( $subjects, array (
				"subject",
				"subject" 
		), FALSE );
		$data ["action"] = "insert";
		$data ["target"] = "benchmark/edit";
		$data['title'] = "Duplcated Benchmark";
		if ($this->input->get ( "ajax" )) {
			$this->load->view ( $data ["target"], $data );
		} else {
			$this->load->view ( "page/index", $data );
		}
	}

	function parse()
	{
	}

	function insert()
	{
		$this->benchmark_model->insert ();
		$year = $this->input->post ( "year" );
		$term = $this->input->post ( "term" );
		$subject = $this->input->post ( "subject" );
		$gradeStart = $this->input->post ( "gradeStart" );
		$gradeEnd = $this->input->post ( "gradeEnd" );
		redirect ( "benchmark/teacher_list/?year=$year&term=$term&subject=$subject&gradeStart=$gradeStart&gradeEnd=$gradeEnd" );
	}

	function update()
	{
		$kBenchmark = $this->input->post ( "kBenchmark" );
		$this->benchmark_model->update ( $kBenchmark );
		$year = $this->input->post ( "year" );
		$term = $this->input->post ( "term" );
		$subject = $this->input->post ( "subject" );
		$gradeStart = $this->input->post ( "gradeStart" );
		$gradeEnd = $this->input->post ( "gradeEnd" );
		redirect ( "benchmark/teacher_list/?year=$year&term=$term&subject=$subject&gradeStart=$gradeStart&gradeEnd=$gradeEnd" );
	}

	function delete()
	{
		$kBenchmark = $this->input->post ( "kBenchmark" );
		$this->benchmark_model->delete ( $kBenchmark );
	}

	function edit_for_student($kNarrative)
	{
		$this->load->model ( "narrative_model" );
		// @TODO get studentGrade as calculation
		$narrative = $this->narrative_model->get ( $kNarrative, TRUE, "narrative.kStudent, narrative.narrSubject,stuGrade, narrative.narrTerm,narrative.narrYear,student.stuFirst,student.stuLast,student.stuNickname" );
		$data ["benchmarks"] = $this->benchmark_model->get_for_student ( $narrative->kStudent, $narrative->narrSubject, $narrative->stuGrade, $narrative->narrTerm, $narrative->narrYear );
		$student = format_name($narrative->stuFirst,$narrative->stuNickname, $narrative->stuLast);
		$data['title'] = "Editing Benchmarks for $student: $narrative->narrSubject, $narrative->stuGrade, $narrative->narrTerm, $narrative->narrYear";
		$data['target'] = "benchmark/edit_for_student";
		if($this->input->get("ajax")){
		$this->load->view ($data['target'], $data );
		}else{
			$this->load->view("page/index",$data);
		}
	}

	function update_for_student()
	{
		$kStudent = $this->input->get_post ( "kStudent" );
		$kTeach = $this->input->get_post ( "kTeach" );
		$kBenchmark = $this->input->get_post ( "kBenchmark" );
		$grade = $this->input->get_post ( "grade" );
		$comment = $this->input->get_post ( "comment" );
		$output = $this->benchmark_model->update_for_student ( $kStudent, $kBenchmark, $kTeach, $grade, $comment );
		if ($output) {
			echo OK;
		}
	}

	function get_legend()
	{
		$this->load->model ( "benchmark_legend_model" );
		$legend = $this->benchmark_legend_model->get ();
	}
	
	// @TODO create a system for sorting categories
/**
 * -needs a new table, or some kind of general sorting table with flexible keys
 * -probably need to constrain user input to a dropdown with "Other..."
 * that offers previously used categories (for current term/subject/grade only?)
 * -ideally the sort is saved in a plain text field with term/subject/grade identifiers
 * (learning opportunity--how to concatinate n fields of the same type/class--maybe javascript?)
 */
}