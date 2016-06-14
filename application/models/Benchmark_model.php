<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Benchmark_model extends CI_Model
{
	var $term;
	var $year;
	var $gradeStart;
	var $gradeEnd;
	var $subject;
	var $category;
	var $weight;
	var $benchmark;
	var $recModifier;
	var $recModified;

	function prepare_variables()
	{

		$variables = array("term","year","gradeStart","gradeEnd","subject","category","weight","benchmark");
		for($i = 0; $i < count($variables); $i++){
			$myVariable = $variables[$i];
			if($this->input->post($myVariable)){
				$this->$myVariable = $this->input->post($myVariable);
			}
		}

		$this->recModified = mysql_timestamp();
		$this->recModifier = $this->session->userdata('userID');
	}


	function get($kBenchmark, $select = NULL)
	{
		if($select){
			if(is_array($select)){
				foreach($select as $item){
					$this->db->select($item);
				}
			}else{
				$this->db->select($select);
			}
		}
		$this->db->where('kBenchmark', $kBenchmark);
		$this->db->from('benchmark');
		$result = $this->db->get()->row();
		return $result;
	}


	function get_list($term, $year, $subject, $grade_range = array())
	{
		$this->db->order_by("gradeStart,subject,term,year,category,weight,benchmark", "ASC");
		if($term){
			$this->db->where('term', $term);
		}
		if($year){
			$this->db->where('year', $year);
		}
		if($subject){
			$this->db->where('subject', $subject);
		}

		if(array_key_exists('gradeStart', $grade_range) && array_key_exists('gradeEnd', $grade_range)){
			$gradeStart = $grade_range['gradeStart'];
			$gradeEnd = $grade_range['gradeEnd'];
			if($gradeEnd == ""){
				$gradeEnd = $gradeStart;
			}
			if($gradeStart!=""){
				$this->db->where("`gradeStart` BETWEEN '$gradeStart' AND '$gradeEnd'",NULL, TRUE);
				$this->db->where("`gradeEnd` BETWEEN '$gradeStart' AND '$gradeEnd'",NULL , TRUE);
			}
		}
		$this->db->from('benchmark');

		$result = $this->db->get()->result();
		return $result;
	}

	function insert()
	{
		$this->prepare_variables();
		$this->db->insert('benchmark',$this);
		return $this->db->insert_id();
	}

	function update($kBenchmark){
		$this->prepare_variables();
		$this->db->where('kBenchmark', $kBenchmark);
		$this->db->update('benchmark', $this);
	}

	function delete($kBenchmark)
	{
		$delete_array['kBenchmark'] = $kBenchmark;
		$this->db->delete('benchmark', $delete_array);
	}


	function get_for_student($kStudent, $subject, $grade, $term, $year, $category = FALSE)
	{

		//@TODO Real Problem here is finding the benchmarks for grade ranges
		if($subject != "all"){
			$this->db->where("benchmark.subject", $subject);
		}
		$this->db->where("(benchmark.gradeStart = $grade OR benchmark.gradeEnd = $grade)");
		$this->db->where("benchmark.term", $term);
		$this->db->where("benchmark.year", $year);
		if($category){
			$this->db->where("benchmark.category", $category);
		}
		$this->db->order_by("benchmark.subject, benchmark.category, benchmark.weight ASC");
		$this->db->join("student_benchmark","benchmark.kBenchmark=student_benchmark.kBenchmark AND student_benchmark.kStudent=$kStudent", "LEFT");
		$this->db->select("benchmark.*,student_benchmark.comment,student_benchmark.grade");
		$this->db->from("benchmark");
		$result = $this->db->get()->result();
		return $result;

	}


	function update_for_student( $kStudent, $kBenchmark, $kTeach, $grade, $comment = NULL)
	{
		$output = FALSE;
		if(!empty($kBenchmark) && !empty($kStudent)){
			$data = array("kTeach" => $kTeach, "grade" => $grade);
			if(isset($comment)){
				$data["comment"] = $comment;
			}
			if($this->student_has_benchmark($kStudent, $kBenchmark) > 0){
				$this->db->where("kStudent", $kStudent);
				$this->db->where("kBenchmark", $kBenchmark);
				$this->db->update("student_benchmark", $data);
				$output = TRUE;
			}else{
				$data["kStudent"] = $kStudent;
				$data["kBenchmark"] = $kBenchmark;
				$this->db->insert("student_benchmark", $data);
				$output = $this->db->insert_id();
			}
		}
		return $output;
	}


	function insert_for_student($kStudent, $kBenchmark, $kTeach, $grade, $comment)
	{
		$this->update_for_student( $kStudent, $kBenchmark, $kTeach, $grade, $comment );
	}


	function student_has_benchmark($kStudent, $kBenchmark){
		$this->db->where("kStudent", $kStudent);
		$this->db->where("kBenchmark",$kBenchmark);
		$this->db->from("student_benchmark");
		$result = $this->db->get()->num_rows();
		return $result;
	}
	
	function student_has_benchmarks($kStudent, $subject, $grade, $term, $year, $category = FALSE)
	{

		//@TODO Real Problem here is finding the benchmarks for grade ranges
		if($subject != "all"){
			$this->db->where("benchmark.subject", $subject);
		}
		$this->db->where("(benchmark.gradeStart <= $grade OR benchmark.gradeEnd >= $grade)");
		$this->db->where("benchmark.term", $term);
		$this->db->where("benchmark.year", $year);
		if($category){
			$this->db->where("benchmark.category", $category);
		}
		$this->db->order_by("benchmark.subject, benchmark.category, benchmark.weight ASC");
		$this->db->join("student_benchmark","benchmark.kBenchmark=student_benchmark.kBenchmark AND student_benchmark.kStudent=$kStudent", "RIGHT");
		$this->db->select("student_benchmark.kStudent");
		$this->db->from("benchmark");
		$result = $this->db->get()->result();
		return $result;

	}

	function benchmarks_available($subject, $grade, $term, $year)
	{
		if(empty($grade) || $grade == NULL){
			$grade = 0;
		}
			$this->db->where("subject", $subject);
			$this->db->where("term",$term);
			$this->db->where("year", $year);
			$this->db->where("benchmark.gradeStart <= $grade");
			$this->db->where("benchmark.gradeEnd >= $grade");
			$this->db->from("benchmark");
			$result = $this->db->get()->num_rows();
			return $result;
	}
	


}