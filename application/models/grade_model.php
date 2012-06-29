<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Grade_model extends CI_Model
{

	var $kTeach;
	var $kStudent;
	var $kAssignment;
	var $points;

	function __construct()
	{
		parent::__construct();
	}

	function prepare_variables()
	{
		$variables = array("kTeach","kStudent","kAssignment","points");
		for($i = 0; $i < count($variables); $i++){
			$myVariable = $variables[$i];
			if($this->input->post($myVariable)){
				$this->$myVariable = $this->input->post($myVariable);
			}
		}
	}


	function has_grade($kStudent,$kAssignment)
	{
		$this->db->where("kAssignment",$kAssignment);
		$this->db->where("kStudent",$kStudent);
		$this->db->from("grade");
		$result = $this->db->get()->num_rows();
		return $result;
	}


	function update($kStudent, $kAssignment,$points)
	{
		$output = FALSE;
		$data = array("points" => $points);
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
		return $this->db->last_query();

	}


	function delete($kGrade)
	{
		$delete = array("kGrade" => $kGrade);
		$this->db->delete("grade",$delete);
	}


}