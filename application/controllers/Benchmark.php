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
/**
 * @TODO make kTeach a uri segment
 */
	function search()
	{
		$kTeach = $this->session->userdata ( "userID" );
		$data ['kTeach'] = $kTeach;
		
		//$data ['termMenu'] = get_term_menu ( "term", get_current_term () );
		$data ['subjects'] = $this->subject_model->get_for_teacher ( $kTeach );
		$data ['subject_list'] = get_keyed_pairs ( $data ['subjects'], array (
				"subject",
				"subject" 
		), FALSE );
		$data ['yearStart'] = get_current_year ();
		$data ['yearEnd'] = $data ['yearStart'] + 1;
		if (! get_cookie ( "benchmark_grade_start" ) || ! get_cookie ( "benchmark_grade_end" )) {
// 			$teacher = $this->teacher_model->get ( $kTeach, array (
// 					"gradeStart",
// 					"gradeEnd" 
// 			) );
			$data ['gradeStart'] = 5;
			$data ['gradeEnd'] = 8;
		} else {
			$data ['gradeStart'] = get_cookie ( "benchmark_grade_start" );
			$data ['gradeEnd'] = get_cookie ( "benchmark_grade_end" );
		}
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
		if ($this->input->get ( "year" )) {
			$year = $this->input->get ( "year" );
			//$term = $this->input->get ( "term" );
			$subject = $this->input->get ( "subject" );
			bake_cookie ( "benchmark_subject", $subject );
// 			if ($this->input->get ( 'quarter' )) {
// 				$options ['quarter'] = $this->input->get ( 'quarter' );
// 			}
			//$quarter = $this->input->get ( "quarter" );
			$options ['grade_range'] ['gradeStart'] = $this->input->get ( "gradeStart" );
			bake_cookie ( "benchmark_grade_start", $this->input->get ( "gradeStart" ) );
			
			$options ['grade_range'] ['gradeEnd'] = $this->input->get ( "gradeEnd" );
			bake_cookie ( "benchmark_grade_end", $this->input->get ( "gradeEnd" ) );
			$data ['kTeach'] = $this->input->get ( "kTeach" );
			$data ['benchmarks'] = $this->benchmark_model->get_list ($year, $subject, $options );
			$data ['title'] = "Benchmark Editing";
			$data ['target'] = "benchmark/list";
			$this->load->view ( "page/index", $data );
		}
	}

	function edit($kBenchmark)
	{
		$kTeach = $this->session->userdata ( "userID" );
		$benchmark = $this->benchmark_model->get ( $kBenchmark );
		$data ['kBenchmark'] = $kBenchmark;
		$data ['gradeStart'] = $benchmark->gradeStart;
		$data ['gradeEnd'] = $benchmark->gradeEnd;
		$data ['year'] = $benchmark->year;
		$data ['term'] = $benchmark->term;
		$data ['subject'] = $benchmark->subject;
		$data ['category'] = $benchmark->category;
		$data ['benchmark'] = $benchmark->benchmark;
		$data ['weight'] = $benchmark->weight;
		$data ['quarter'] = $benchmark->quarter;
		$data['categories'] = $this->benchmark_model->get_categories($benchmark->subject, $benchmark->year, $benchmark->gradeStart, $benchmark->gradeEnd);
		
		$subjects = $this->subject_model->get_for_teacher ( $kTeach );
		$data ['subjects'] = get_keyed_pairs ( $subjects, array (
				"subject",
				"subject" 
		), FALSE );
		$data ['action'] = "update";
		$data ['target'] = "benchmark/edit";
		$data ['title'] = "Editing Benchmark";
		if ($this->input->get ( "ajax" )) {
			$this->load->view ( $data ['target'], $data );
		} else {
			$this->load->view ( "page/index", $data );
		}
	}

	function create()
	{
		$data ['kBenchmark'] = "";
		$kTeach = $this->session->userdata ( "userID" );
		$teacher = $this->teacher_model->get ( $kTeach );
		if (get_cookie ( "benchmark_grade_start" ) || get_cookie ( "benchmark_grade_end" )) {
			$data ['gradeStart'] = get_cookie ( "benchmark_grade_start" );
			$data ['gradeEnd'] = get_cookie ( "benchmark_grade_end" );
		} else {
			$data ['gradeStart'] = $teacher->gradeStart;
			$data ['gradeEnd'] = $teacher->gradeEnd;
		}
		$data ['year'] = get_current_year ();
		$data ['term'] = get_current_term ();
		$data ['subject'] = get_cookie ( "benchmark_subject" );
		$data ['category'] = "";
		$data ['benchmark'] = "";
		$data ['weight'] = 0;
		$data ['quarter'] = get_cookie ( "benchmark_quarter" );
		$data['categories'] = $this->benchmark_model->get_categories($data['subject'], $data['year'], $data['gradeStart'], $data['gradeEnd']);
		
		$subjects = $this->subject_model->get_for_teacher ( $kTeach );
		$data ['subjects'] = get_keyed_pairs ( $subjects, array (
				"subject",
				"subject" 
		), FALSE );
		$data ['action'] = "insert";
		$data ['target'] = "benchmark/edit";
		$data ['title'] = "Create a Benchmark";
		if ($this->input->get ( "ajax" )) {
			$this->load->view ( $data ['target'], $data );
		} else {
			$this->load->view ( "page/index", $data );
		}
	}

	function duplicate($kBenchmark)
	{
		$kTeach = $this->session->userdata ( "userID" );
		$benchmark = $this->benchmark_model->get ( $kBenchmark );
		$data ['kBenchmark'] = NULL;
		$data ['gradeStart'] = $benchmark->gradeStart;
		$data ['gradeEnd'] = $benchmark->gradeEnd;
		$data ['year'] = $benchmark->year;
		//$data ['term'] = $benchmark->term;
		$data ['subject'] = $benchmark->subject;
		$data ['category'] = $benchmark->category;
		$data['categories'] = $this->benchmark_model->get_categories($benchmark->subject, $benchmark->year, $benchmark->gradeStart, $benchmark->gradeEnd);
		
		$data ['benchmark'] = $benchmark->benchmark;
		$data ['weight'] = $benchmark->weight;
		//$data ['quarter'] = $benchmark->quarter;
		$subjects = $this->subject_model->get_for_teacher ( $kTeach );
		$data ['subjects'] = get_keyed_pairs ( $subjects, array (
				"subject",
				"subject" 
		), FALSE );
		$data ['action'] = "insert";
		$data ['target'] = "benchmark/edit";
		$data ['title'] = "Duplcated Benchmark";
		if ($this->input->get ( "ajax" )) {
			$this->load->view ( $data ['target'], $data );
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

	

	/**
	 * Create a printable report for the benchmarks for a student, subject, term and year for printing.
	 *
	 * @param int $kStudent        	
	 * @param string $subject        	
	 * @param string $term        	
	 * @param int $year        	
	 */
	function print_student($kStudent, $subject, $quarter, $term, $year)
	{
		$this->load->model ( "student_model", "student" );
		$student = $this->student->get ( $kStudent );
		$student_grade = get_current_grade ( $student->baseGrade, $student->baseYear, $year );
		$benchmarks = $this->benchmark_model->get_for_student ( $kStudent, $subject, $student_grade, $term, $year, $quarter );

		//$this->load->model ( "benchmark_legend_model", "legend" );
// 		$current_subject = "";
// 		foreach ( $benchmarks as $benchmark ) {
// 			if ($current_subject != $benchmark->subject) {
// 				$benchmark->legend = $this->legend->get_one ( array (
// 						"subject" => $benchmark->subject,
// 						"term" => $term,
// 						"year" => $year 
// 				) )->legend;
// 				$current_subject = $benchmark->subject;
// 			}
// 		}
		$data ['benchmarks'] = $benchmarks;
		$data ['student'] = $student;
		$data ['kStudent'] = $kStudent;
		$data ['student_grade'] = $student_grade;
		$data ['subject'] = $subject;
		$data ['quarter'] = $quarter;
		$data ['term'] = $term;
		$data ['year'] = $year;
		$data ['standalone'] = TRUE;
		$data ['title'] = sprintf ( "%s's Benchmarks, Grade: %s, %s, Quarter %s,  %s", format_name ( $student->stuFirst, $student->stuLast, $student->stuNickname ), $student_grade, $term, $quarter, format_schoolyear ( $year ) );
		$data ['target'] = "benchmark/chart";
		$this->load->view ( "page/index", $data );
	}
	
	function print_for_student($kStudent,$subject, $quarter, $term, $year){
		$this->load->model ( "student_model", "student" );
		$student = $this->student->get ( $kStudent );
		$student_grade = get_current_grade ( $student->baseGrade, $student->baseYear, $year );
		$options['quarter'] = $quarter;
		$options['grade_range'] = array('gradeStart'=>$student_grade, 'gradeEnd'=>$student_grade);
		$benchmarks = $this->benchmark_model->get_list($year, $term, $subject, $options);
		foreach($benchmarks as $benchmark){
			$marks = $this->benchmark_model->get_for_student_by_id($benchmark->kBenchmark, $kStudent);
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
