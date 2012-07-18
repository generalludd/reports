<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Grade_model extends CI_Model
{

	var $kTeach;
	var $kStudent;
	var $kAssignment;
	var $points;
	var $average;
	var $status;
	var $footnote;

	function __construct()
	{
		parent::__construct();
	}

	function prepare_variables()
	{
		$variables = array("kTeach","kStudent","kAssignment","points","status","footnote");
		for($i = 0; $i < count($variables); $i++){
			$myVariable = $variables[$i];
			if($this->input->post($myVariable)){
				$this->$myVariable = $this->input->post($myVariable);
			}
		}
	}

	
	function get($kStudent,$kAssignment)
	{
		$this->db->where("kStudent",$kStudent);
		$this->db->where("kAssignment",$kAssignment);
		$this->db->from("grade");
		$output = $this->db->get()->row();
		return $output;
	}

	function has_grade($kStudent,$kAssignment)
	{
		$this->db->where("kAssignment",$kAssignment);
		$this->db->where("kStudent",$kStudent);
		$this->db->from("grade");
		$result = $this->db->get()->num_rows();
		return $result;
	}


	function update($kStudent, $kAssignment,$points,$total, $status,$footnote,$category)
	{
		$output = FALSE;
	//this variable is not declared in $_POST or $_GET. It must be calculated. 
		$this->average = $points/$total;
		//if the status is either "Exc" or "Abs" or anything else for that matter,
		// then the grade is counted at full value
		if(!empty($status)){
			$this->average = 0;
		}
		$data = array("points" => $points,"status"=>$status,"footnote"=>$footnote,"average"=>$this->average);
		if($this->has_grade($kStudent, $kAssignment) > 0){
			$this->db->where("kAssignment",$kAssignment);
			$this->db->where("kStudent",$kStudent);
			$this->db->update("grade", $data);
			$output = TRUE;
		}else{
			$data["kStudent"] = $kStudent;
			$data["kAssignment"] = $kAssignment;
			$this->db->insert("grade", $data);
			$output = $this->db->insert_id();
			$output = $this->db->last_query();
		}
		return $this->average; 

	}

	function calculate_weight($kTeach, $category)
	{
		$this->db->where("type","grade_weight");
		$this->db->where("kTeach",$kTeach);
		$this->db->from("preference");
		$result = $this->db->get()->row()->value;
		
		
	}

	function delete($kGrade)
	{
		$delete = array("kGrade" => $kGrade);
		$this->db->delete("grade",$delete);
	}


}