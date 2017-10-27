<?php
class Student_benchmark_model extends MY_Model {
	var $quarter;
	var $term;
	var $year;
	var $kBenchmark;
	var $kStudent;
	var $grade;
	var $comment;

	function prepare_variables()
	{
		$variables = array (
				"quarter",
				"term",
				"year",
				"kBenchmark",
				"kStudent",
				"grade",
				"comment" 
		);
		for($i = 0; $i < count ( $variables ); $i ++) {
			$myVariable = $variables [$i];
			if ($this->input->post ( $myVariable )) {
				$this->$myVariable = $this->input->post ( $myVariable );
			}
		}
	}

	function get($kStudent, $student_grade, $term, $year, $quarter, $subject = NULL)
	{
		$this->db->from ( "benchmark" );
		
		$this->db->select ( "benchmark.kBenchmark, benchmark.category, benchmark.benchmark, benchmark.subject" );
		if ($subject) {
			$this->db->where ( "benchmark.subject", $subject );
		}
		
		// set up the query to find only the quarter requested if a quarter has been requested
		// this may be that a quarter must be required
		// showing multiple quarters might be best done in code instead of a query
		// as this would allow for the creation of tables with multiple values for each benchmark
		$quarters = " AND student_benchmark.quarter IS NOT NULL";
		if ($quarter) {
			for($i = 1; $i < 5; $i ++) {
				if ($i != $quarter) {
					$join [] = "student_benchmark.quarter != $i";
				}
			}
			$quarters =  sprintf (" AND (%s)", implode(" AND ", $join));
			
		} else {
			$this->db->order_by ( "benchmark.subject, student_benchmark.quarter" );
		}
		$this->db->select ( "student_benchmark.kStudent, student_benchmark.grade, student_benchmark.comment, student_benchmark.term, student_benchmark.quarter, student_benchmark.year" );
		$this->db->join ( "student_benchmark", "benchmark.kBenchmark = student_benchmark.kBenchmark $quarters", "LEFT OUTER" );
		$this->db->where ( "(student_benchmark.kStudent IS NULL OR student_benchmark.kStudent = '$kStudent')", NULL, TRUE );
		
// 		$this->db->where ( "benchmark.gradeStart >= ", $student_grade );
// 		$this->db->where ( "benchmark.gradeEnd <= ", $student_grade );
		$this->db->where("$student_grade BETWEEN benchmark.gradeStart AND benchmark.gradeEnd", NULL, FALSE);
		$this->db->where ( "benchmark.year", $year );
		$this->db->where ( "(student_benchmark.year = '$year' OR student_benchmark.year IS NULL)", NULL, FALSE );
		$this->db->where ( "(student_benchmark.term = '$term' OR student_benchmark.term IS NULL)", NULL, FALSE );
		$this->db->order_by ( "benchmark.subject, benchmark.category, benchmark.weight, benchmark.benchmark" );
		$result = $this->db->get ()->result ();
		return $result;
	}
	
	function get_one($kStudent, $kBenchmark, $quarter){
		$this->db->from("student_benchmark");
		$this->db->where("kStudent",$kStudent);
		$this->db->where("kBenchmark",$kBenchmark);
		$this->db->where("quarter",$quarter);
		$result = $this->db->get()->row();
		return $result;
	}
	
	function update($kStudent, $kBenchmark, $grade, $comment, $quarter, $term, $year){
		$output = FALSE;
		$query = sprintf("REPLACE INTO student_benchmark (kStudent, kBenchmark, quarter, term, year, grade, comment) VALUES('%s','%s','%s','%s','%s','%s','%s')",$kStudent,$kBenchmark,$quarter,$term,$year,$grade,$comment);
		$this->db->query($query);
		return TRUE;
	}
}