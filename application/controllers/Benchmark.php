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
		//$data ["termMenu"] = get_term_menu ( "term", get_current_term () );
		$data ["subjects"] = $this->subject_model->get_for_teacher ( $kTeach );
		$data ["subject_list"] = get_keyed_pairs ( $data ["subjects"], array (
				"subject",
				"subject" 
		), FALSE );
		$data ["yearStart"] = get_current_year ();
		$data ["yearEnd"] = $data ["yearStart"] + 1;
		if (! get_cookie ( "benchmark_grade_start" ) || ! get_cookie ( "benchmark_grade_end" )) {
			$teacher = $this->teacher_model->get ( $kTeach, array (
					"gradeStart",
					"gradeEnd" 
			) );
			$data ["gradeStart"] = $teacher->gradeStart;
			$data ["gradeEnd"] = $teacher->gradeEnd;
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
			$options ['grade_range'] ["gradeStart"] = $this->input->get ( "gradeStart" );
			bake_cookie ( "benchmark_grade_start", $this->input->get ( "gradeStart" ) );
			
			$options ['grade_range'] ["gradeEnd"] = $this->input->get ( "gradeEnd" );
			bake_cookie ( "benchmark_grade_end", $this->input->get ( "gradeEnd" ) );
			$data ["kTeach"] = $this->input->get ( "kTeach" );
			$data ["benchmarks"] = $this->benchmark_model->get_list ($year, $subject, $options );
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
		$data ['quarter'] = $benchmark->quarter;
		$data['categories'] = $this->benchmark_model->get_categories($benchmark->subject, $benchmark->year, $benchmark->gradeStart, $benchmark->gradeEnd);
		
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
		if (get_cookie ( "benchmark_grade_start" ) || get_cookie ( "benchmark_grade_end" )) {
			$data ["gradeStart"] = get_cookie ( "benchmark_grade_start" );
			$data ["gradeEnd"] = get_cookie ( "benchmark_grade_end" );
		} else {
			$data ["gradeStart"] = $teacher->gradeStart;
			$data ["gradeEnd"] = $teacher->gradeEnd;
		}
		$data ["year"] = get_current_year ();
		$data ["term"] = get_current_term ();
		$data ["subject"] = get_cookie ( "benchmark_subject" );
		$data ["category"] = "";
		$data ["benchmark"] = "";
		$data ["weight"] = 0;
		$data ['quarter'] = get_cookie ( "benchmark_quarter" );
		$data['categories'] = $this->benchmark_model->get_categories($data["subject"], $data['year'], $data['gradeStart'], $data['gradeEnd']);
		
		$subjects = $this->subject_model->get_for_teacher ( $kTeach );
		$data ["subjects"] = get_keyed_pairs ( $subjects, array (
				"subject",
				"subject" 
		), FALSE );
		$data ["action"] = "insert";
		$data ["target"] = "benchmark/edit";
		$data ["title"] = "Create a Benchmark";
		if ($this->input->get ( "ajax" )) {
			$this->load->view ( $data ["target"], $data );
		} else {
			$this->load->view ( "page/index", $data );
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
		//$data ["term"] = $benchmark->term;
		$data ["subject"] = $benchmark->subject;
		$data ["category"] = $benchmark->category;
		$data['categories'] = $this->benchmark_model->get_categories($benchmark->subject, $benchmark->year, $benchmark->gradeStart, $benchmark->gradeEnd);
		
		$data ["benchmark"] = $benchmark->benchmark;
		$data ["weight"] = $benchmark->weight;
		//$data ["quarter"] = $benchmark->quarter;
		$subjects = $this->subject_model->get_for_teacher ( $kTeach );
		$data ["subjects"] = get_keyed_pairs ( $subjects, array (
				"subject",
				"subject" 
		), FALSE );
		$data ["action"] = "insert";
		$data ["target"] = "benchmark/edit";
		$data ['title'] = "Duplcated Benchmark";
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
		$student = format_name ( $narrative->stuFirst, $narrative->stuNickname, $narrative->stuLast );
		$data ['title'] = "Editing Benchmarks for $student: $narrative->narrSubject, $narrative->stuGrade, $narrative->narrTerm, $narrative->narrYear";
		$data ['kStudent'] = $narrative->kStudent;
		$data ['kTeach'] = $narrative->kTeach;
		$data ['target'] = "benchmark/edit_for_student";
		$data['year'] = $this->input->get("year");
		$data['term'] = $this->input->get('term');
		$data['quarter'] = $this->input->get('quarter');
		if ($this->input->get ( "ajax" )) {
			$this->load->view ( $data ['target'], $data );
		} else {
			$this->load->view ( "page/index", $data );
		}
	}

	function update_for_student()
	{
		$kStudent = $this->input->post ( "kStudent" );
		$kTeach = USER_ID;
		$kBenchmark = $this->input->post ( "kBenchmark" );
		$grade = $this->input->post ( "grade" );
		if ($grade > 10) {
			echo "Must be less than 10";
			die ();
		}
		$comment = $this->input->post ( "comment" );
		$output = $this->benchmark_model->update_for_student ( $kStudent, $kBenchmark, $kTeach, $grade, $comment );
		if ($output) {
			echo OK;
		}
	}

	function select_student($kStudent = NULL)
	{
		if ($kStudent) {
			$this->load->model ( "student_model", "student" );
			$this->load->model ( "menu_model" );
			$student = $this->student->get ( $kStudent );
			$data ["student"] = $student;
			$data ["action"] = "update";
			$subjects = $this->subject_model->get_all ( array (
					"gradeStart" => 5,
					"gradeEnd" => 8 
			) );
			$data ["subjects"] = get_keyed_pairs ( $subjects, array (
					"subject",
					"subject" 
			), TRUE );
			if ($this->input->get ( "gradeStart" ) && $this->input->get ( "gradeEnd" )) {
				$data ['gradeStart'] = $this->input->get ( "gradeStart" );
				$data ['gradeEnd'] = $this->input->get ( "gradeEnd" );
			} else {
				$data ['gradeStart'] = get_current_grade ( $student->baseGrade, $student->baseYear );
				$data ['gradeEnd'] = get_current_grade ( $student->baseGrade, $student->baseYear );
			}
			$data ['target'] = "benchmark/select";
			$data ['title'] = "Search for Student Benchmarks";
			if ($this->input->get ( "ajax" ) == 1) {
				$this->load->view ( $data ['target'], $data );
			} else {
				$this->load->view ( "page/index", $data );
			}
		} else {
			$kStudent = $this->input->get ( "kStudent" );
			$subject = $this->input->get ( "subject" );
			$grade = $this->input->get ( "gradeStart" );
			$year = $this->input->get ( "year" );
			$term = $this->input->get ( "term" );
			$quarter = NULL;
			if ($this->input->get ( "quarter" )) {
				$quarter = $this->input->get ( "quarter" );
			}
			$this->print_student ( $kStudent, $subject, $quarter, $term, $year );
		}
	}

	function edit_student($kStudent)
	{
		$this->load->model ( "student_model", "student" );
		$this->load->model ("student_benchmark_model", "student_benchmark");
		$student = $this->student->get ( $kStudent );
		$subject = $this->input->get ( "subject" );
		$student_grade = get_current_grade ( $student->baseGrade, $student->baseYear );
		if ($this->input->get ( 'student_grade' )) {
			$student_grade = $this->input->get ( 'student_grade' );
		}
		$term = get_current_term ();
		if ($this->input->get ( 'term' )) {
			$term = $this->input->get ( 'term' );
		}
		
		$year = get_current_year ();
		if ($this->input->get ( 'year' )) {
			$year = $this->input->get ( 'year' );
		}
		$quarter = FALSE;
		if ($this->input->get ( "quarter" )) {
			$quarter = $this->input->get ( "quarter" );
		}
		$data['year'] = $year;
		$data['term'] = $term;
		$data['quarter'] = $quarter;
		$data ["benchmarks"] = $this->benchmark_model->get_for_student ( $kStudent, $subject, $student_grade, $term, $year, $quarter );
		$student_name = format_name ( $student->stuFirst, $student->stuLast, $student->stuNickname );
		$data ['title'] = "Editing Benchmarks for $student_name: $subject, $student_grade, $term, $year";
		$data ['kStudent'] = $kStudent;
		if ($this->input->get ( 'kTeach' )) {
			$data ['kTeach'] = $this->input->get ( 'kTeach' );
		} else {
			$data ['kTeach'] = USER_ID;
		}
		$data ['target'] = "benchmark/edit_for_student";
		if ($this->input->get ( "ajax" )) {
			$this->load->view ( $data ['target'], $data );
		} else {
			$this->load->view ( "page/index", $data );
		}
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
		$data ['title'] = sprintf ( "Benchmarks for %s, Grade: %s, %s, Quarter %s,  %s", format_name ( $student->stuFirst, $student->stuLast, $student->stuNickname ), $student_grade, $term, $quarter, format_schoolyear ( $year ) );
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
