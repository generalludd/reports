<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Assignment_model extends CI_Model
{
	var $kTeach;
	var $kCategory;
	var $assignment;
	var $date;
	var $points;
	var $subject;
	var $term;
	var $year;
	var $gradeStart;
	var $gradeEnd;

	function prepare_variables()
	{
		$variables = array("kTeach","assignment","kCategory","date","points","subject","term","year","gradeStart","gradeEnd");

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

	function get($kAssignment)
	{
		$this->db->where("kAssignment",$kAssignment);
		$this->db->join("assignment_category as category","assignment.kCategory = category.kCategory");
		$this->db->select("assignment.*,category.weight,category.category");
		$this->db->from("assignment");
		$result = $this->db->get()->row();
		return $result;
	}



	function insert()
	{
		$this->prepare_variables();
		$this->db->insert("assignment",$this);
		$kAssignment = $this->db->insert_id();
		return $kAssignment;
	}


	function update($kAssignment)
	{
		$this->prepare_variables();
		$this->db->where("kAssignment",$kAssignment);
		$this->db->update("assignment", $this);
	}




	function get_for_student($kStudent,$term,$year,$options = array())
	{
		$this->db->where("assignment.term",$term);
		$this->db->where("assignment.year",$year);
		if(array_key_exists("kTeach",$options)){
			$this->db->where("assignment.kTeach",$options["kTeach"]);
		}
		if(array_key_exists("subject",$options)){
			$this->db->where("assignment.subject",$options["subject"]);
		}
		$this->db->join("grade","assignment.kAssignment=grade.kAssignment AND grade.kStudent = $kStudent","LEFT");
		$this->db->join("student","student.kStudent=grade.kStudent","LEFT");
		$this->db->join("teacher","teacher.kTeach=assignment.kTeach","LEFT");
		$this->db->join("menu","grade.footnote = menu.value AND menu.category='grade_footnote'","LEFT");
		$this->db->join("assignment_category as category","assignment.kCategory = category.kCategory","LEFT");

		$this->db->select("category.category,category.weight,assignment.kAssignment, assignment.term, assignment.year, assignment.subject, assignment.date, assignment.assignment, assignment.points as total_points,grade.points,grade.average,grade.status,grade.footnote,menu.label,student.stuFirst,student.stuNickname,student.stuLast,teacher.teachFirst,teacher.teachLast");
		$this->db->order_by("assignment.date");
		$this->db->order_by("assignment.kAssignment");
		$this->db->order_by("assignment.kCategory");
		$result = $this->db->get("assignment")->result();
		return $result;

	}

	function get_grades($kTeach,$term,$year,$gradeStart,$gradeEnd)
	{
		$this->db->where("term",$term);
		$this->db->where("year",$year);
		$this->db->where("assignment.kTeach",$kTeach);
		$this->db->where("(assignment.gradeStart = $gradeStart OR assignment.gradeEnd = $gradeEnd)");
		//$this->db->where("student.stuGrade in ($gradeStart,$gradeEnd)");
		$this->db->join("grade","assignment.kAssignment=grade.kAssignment");
		$this->db->join("student","grade.kStudent=student.kStudent");
		$this->db->join("assignment_category as category","assignment.kCategory = category.kCategory","LEFT");
		$this->db->order_by("student.stuLast");
		$this->db->order_by("assignment.date");
		$this->db->order_by("assignment.kAssignment");
		$this->db->order_by("assignment.term");
		$this->db->order_by("assignment.year");
		$result = $this->db->get("assignment")->result();
		return $result;
	}

	function get_for_teacher($kTeach,$term,$year)
	{
		$this->db->where("assignment.kTeach",$kTeach);
		$this->db->where("term",$term);
		$this->db->where("year",$year);
		$this->db->from("assignment");
		$this->db->join("assignment_category as category","assignment.kCategory = category.kCategory","LEFT");
		$this->db->select("assignment.*,category.weight,category.category");
		$this->db->order_by("assignment.date");
		$this->db->order_by("assignment.kAssignment");
		$this->db->order_by("assignment.term");
		$this->db->order_by("assignment.year");
		$output = $this->db->get()->result();
		return $output;
	}

	function delete($kAssignment){
		$delete_array = array("kAssignment"=>$kAssignment);
		$this->db->delete("assignment",$delete_array);
		$this->db->delete("grade",$delete_array);
	}


	/***** CATEGORY WEIGHTS ******/
	function insert_category($values = array())
	{
		$kCategory = FALSE;
		if(array_key_exists("kTeach",$values)){
			$this->db->insert("assignment_category",$values);
			$kCategory = $this->db->insert_id();
		}
		return $kCategory;
	}


	function update_category($kCategory,$values = array())
	{
		if(!empty($values)){
			$this->db->where("kCategory",$kCategory);
			$this->db->update("assignment_category",$values);
		}
	}

	function get_category($kCategory)
	{
		$this->db->where("kCategory",$kCategory);
		$this->db->from("assignment_category");
		$result = $this->db->get()->row();
		return $result;
	}


	function get_categories($kTeach = FALSE)
	{
		$this->db->distinct("category");
		if($kTeach){
			$this->db->where("kTeach",$kTeach);
		}
		$this->db->where("kTeach",$kTeach);
		$this->db->order_by("category");
		$result = $this->db->get("assignment_category")->result();
		return $result;
	}

}