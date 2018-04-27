<?php
class Student_benchmark extends MY_Controller {

	function __construct()
	{
		parent::__construct ();
		$this->load->model ( "student_benchmark_model", "student_benchmark" );
		$this->load->model ( "student_model", "student" );
	}

	function select()
	{
		if ($this->input->get ( "search" )) {
			$this->load->model ( "subject_model", "subject" );
			$this->load->model ( "menu_model" );
			$kStudent = $this->input->get ( "kStudent" );
			$student = $this->student->get ( $kStudent );
			$data ['student'] = $student;
			$data ['action'] = "update";
			$subjects = $this->subject->get_all ( array (
					"has_benchmarks"=>TRUE,
			) );
			$data ['subjects'] = get_keyed_pairs ( $subjects, array (
					"subject",
					"subject" 
			), TRUE );
			if ($this->input->get ( "student_grade" )) {
				$data ['student_grade'] = $this->input->get ( "student_grade" );
			} else {
				$data ['student_grade'] = get_current_grade ( $student->baseGrade, $student->baseYear );
			}
			$data ['target'] = "student_benchmark/select";
			$data ['title'] = "Search for Student Benchmarks";
			$data['refine'] = $this->input->get("refine");
			if ($this->input->get ( "ajax" ) == 1) {
				$this->load->view ( $data ['target'], $data );
			} else {
				$this->load->view ( "page/index", $data );
			}
		} else {
			$this->load->model ( "benchmark_model", "benchmarks" );
			
			$kStudent = $this->input->get ( "kStudent" );
			$data ['kStudent'] = $kStudent;
			$subject = $this->input->get ( "subject" );
			$data ['subject'] = $subject;
			bake_cookie("benchmark_subject",$subject);
			$student_grade = $this->input->get ( "student_grade" );
			$data ['student_grade'] = $student_grade;
			$term = $this->input->get ( "term" );
			$data ['term'] = $term;
			$year = $this->input->get ( "year" );
			$data ['year'] = $year;
			if ($term == "Mid-Year") {
				$quarters = 2;
			} else {
				$quarters = 4;
			}
			$data ['quarters'] = $quarters;
			$quarter = $this->input->get ( "quarter" );
			$data ['quarter'] = $quarter;
			bake_cookie("benchmark_quarter",$quarter);
			if ($kStudent && $term && $year) {
				$student = $this->student->get ( $kStudent );
				$data ['student'] = $student;
				$options = array (
						"grade_range" => array (
								"gradeStart" => $student_grade,
								"gradeEnd" => $student_grade 
						) 
				);
				$benchmarks = $this->benchmarks->get_list ( $year, $subject, $options );
				foreach ( $benchmarks as $benchmark ) {
					$benchmark->quarters = array ();
					for($i = 1; $i <= $quarters; $i ++) {
						$benchmark->quarters [] = array (
								"quarter" => $i,
								"grade" => $this->student_benchmark->get_one ( $kStudent, $benchmark->kBenchmark, $i ) 
						);
					}
				}
				$data ['benchmarks'] = $benchmarks;
				if ($this->input->get ( "edit" )) {
					$data ['target'] = "student_benchmark/edit";
				} else {
					$data ['target'] = "student_benchmark/report";
				}
				$data ['title'] = sprintf("Benchmarks for %s, Grade %s, %s, %s",format_name($student->stuFirst, $student->stuLast, $student->stuNickname), $student_grade, $term, format_schoolyear($year));
				
				$this->load->view ( "page/index", $data );
			} else {
				echo "error";
			}
		}
	}

	function report()
	{
		$this->load->model ( "benchmark_model", "benchmarks" );
		
		$kStudent = $this->input->get ( "kStudent" );
		$data ['kStudent'] = $kStudent;
		$subject = $this->input->get ( "subject" );
		$data ['subject'] = $subject;
		$student_grade = $this->input->get ( "student_grade" );
		$data ['student_grade'] = $student_grade;
		$term = $this->input->get ( "term" );
		$data ['term'] = $term;
		$year = $this->input->get ( "year" );
		$data ['year'] = $year;
		if ($term == "Mid-Year") {
			$quarters = 2;
		} else {
			$quarters = 4;
		}
		$data ['quarters'] = $quarters;
		$quarter = $this->input->get ( "quarter" );
		$data ['quarter'] = $quarter;
		
		if ($kStudent && $term && $year) {
			$student = $this->student->get ( $kStudent );
			$data ['student'] = $student;
			$options = array (
					"grade_range" => array (
							"gradeStart" => $student_grade,
							"gradeEnd" => $student_grade 
					) 
			);
			$benchmarks = $this->benchmarks->get_list ( $year, $subject, $options );
			foreach ( $benchmarks as $benchmark ) {
				$benchmark->quarters = array ();
				for($i = 1; $i <= $quarters; $i ++) {
					$benchmark->quarters [] = array (
							"quarter" => $i,
							"grade" => $this->student_benchmark->get_one ( $kStudent, $benchmark->kBenchmark, $i ) 
					);
				}
			}
			if ($this->input->get ( "edit" )) {
				$data ['benchmarks'] = $this->student_benchmark->get ( $kStudent, $student_grade, $term, $year, $quarter, $subject );
			} else {
				$data ['benchmarks'] = $benchmarks;
			}
			$data ['title'] = "Benchmark Report";
			$data ['target'] = "student_benchmark/report";
			$this->load->view ( "page/index", $data );
		} else {
			echo "error";
		}
	}

	function edit_all($kStudent)
	{
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
		$data ['year'] = $year;
		$data ['term'] = $term;
		$data ['quarter'] = $quarter;
		$data ['benchmarks'] = $this->student_benchmark->get ( $kStudent, $student_grade, $term, $year, $quarter, $subject );
		$student_name = format_name ( $student->stuFirst, $student->stuLast, $student->stuNickname );
		$data ['title'] = "Editing Benchmarks for $student_name: $subject, $student_grade, $term, $year";
		$data ['kStudent'] = $kStudent;
		if ($this->input->get ( 'kTeach' )) {
			$data ['kTeach'] = $this->input->get ( 'kTeach' );
		} else {
			$data ['kTeach'] = USER_ID;
		}
		$data ['target'] = "student_benchmark/edit";
		if ($this->input->get ( "ajax" )) {
			$this->load->view ( $data ['target'], $data );
		} else {
			$this->load->view ( "page/index", $data );
		}
	}

	function update()
	{
		$kStudent = $this->input->post ( "kStudent" );
		$kTeach = USER_ID;
		$kBenchmark = $this->input->post ( "kBenchmark" );
		$grade = $this->input->post ( "grade" );
		if($grade === 0){
			$grade = "0";
		}
		$quarter = $this->input->post ( "quarter" );
		$term = $this->input->post ( "term" );
		$year = $this->input->post ( "year" );
		$comment = $this->input->post ( "comment" );
		$output = $this->student_benchmark->update ( $kStudent, $kBenchmark, $grade, $comment, $quarter, $term, $year );
		if ($output) {
			echo OK;
		}
	}
}