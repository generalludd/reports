<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Assignment_model extends CI_Model
{
	var $kTeach;
	var $assignment;
	var $category;
	var $date;
	var $semester;
	var $term;
	var $year;
	var $gradeStart;
	var $gradeEnd;

	function prepare_variables()
	{
		$variables = array("kTeach","assignment","category","date","semester","term","year","gradeStart","gradeEnd");

		for($i = 0; $i < count($variables); $i++){
			$myVariable = $variables[$i];
			if($this->input->post($myVariable)){
				$post = $this->input->post($myVariable);
				if($myVariable == "date"){
					$this->$myVariable = format_date($post,"mysql");
				}else{
					$this->$myVariable = $post;
				}
			}
		}
	}


	function insert()
	{
		$this->prepare_variables();
		$kAssignment = $this->db->insert("assignment",$this);
		return $kAssignment;
	}


	function update($kAssignment)
	{
		$this->prepare_variables();
		$this->db->where("kAssignment",$kAssignment);
		$this->db->update("assignment", $this);
	}


	function delete($kAssignment)
	{
		$delete = array("kAssignment"=>$kAssignment);
		$this->db->delete("assignment",$delete);
	}


	function get_categories($kTeach = FALSE)
	{
		$this->db->distinct("category");
		if($kTeach){
			$this->db->where("kTeach",$kTeach);
		}
		$result = $this->db->get("assignment")->result();
		return $result;
	}


	function get_for_student($kStudent,$kTeach,$term,$year)
	{
		$this->db->where("assignment.term",$term);
		$this->db->where("assignment.year",$year);
		$this->db->where("assignment.kTeach",$kTeach);
		$this->db->join("grade","assignment.kAssignment=grade.kAssignment AND grade.kStudent = $kStudent","LEFT");
		$this->db->select("assignment.kAssignment as kAssignment, assignment.category, assignment.assignment, assignment.points as total_points,grade.kStudent, grade.points,grade.kTeach");
		$this->db->order_by("assignment.date");
		$this->db->order_by("assignment.category");
		$result = $this->db->get("assignment")->result();
		return $result;

	}
	
	function get_grades($kTeach,$term,$year)
	{
		$this->db->where("term",$term);
		$this->db->where("year",$year);
		$this->db->where("assignment.kTeach",$kTeach);
		$this->db->where("student.stuGrade",8);
		$this->db->join("grade","assignment.kAssignment=grade.kAssignment");
		$this->db->join("student","grade.kStudent=student.kStudent");
		$this->db->order_by("student.kStudent");
		$this->db->order_by("assignment.kAssignment");
		$this->db->order_by("assignment.date");
		$this->db->order_by("assignment.term");
		$this->db->order_by("assignment.year");
		$result = $this->db->get("assignment")->result();
		return $result;
	}

	function get_for_teacher($kTeach,$term,$year)
	{
		$this->db->where("kTeach",$kTeach);
		$this->db->where("term",$term);
		$this->db->where("year",$year);
		$this->db->from("assignment");
		$this->db->order_by("assignment.date");
		$this->db->order_by("assignment.term");
		$this->db->order_by("assignment.semester");
		$this->db->order_by("assignment.year");
		$output = $this->db->get()->result();
		return $output;
	}

}