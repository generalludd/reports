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
	
	
	function insert()
	{
		$this->prepare_variables();
		$kGrade = $this->db->insert("grade",$this);
		return $kGrade;
	}
	
	
	function update($kGrade)
	{
		$this->prepare_variables();
		$this->db->where("kGrade",$kGrade);
		$this->db->update("grade",$this);
	}
	
	
	function delete($kGrade)
	{
		$delete = array("kGrade" => $kGrade);
		$this->db->delete("grade",$delete);
	}
	

}