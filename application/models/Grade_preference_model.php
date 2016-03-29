<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Grade_Preference_model extends CI_Model
{
	var $subject;
	var $school_year;
	var $term;
	var $pass_fail;

	function __construct(){
		parent::__construct();
	}

	function update($data)
	{
		if($this->record_exists($data["kStudent"], $data["subject"], $data["school_year"],$data['term'])){
			$this->db->where("kStudent", $data["kStudent"]);
			$this->db->where("subject", $data["subject"]);
			$this->db->where("school_year", $data["school_year"]);
			$this->db->where("term",$data['term']);
			$this->db->update("grade_preference",$data);
		}else{
			$this->db->insert("grade_preference",$data);
		}


	}

	function record_exists($kStudent, $subject, $school_year, $term)
	{
		$this->db->where("kStudent", $kStudent);
		$this->db->where("subject", $subject);
		$this->db->where("school_year", $school_year);
		$this->db->where("term",$term);
		$this->db->from("grade_preference");
		return $this->db->count_all_results();
	}

	function get($id){
		$this->db->where("id", $id);
		$this->db->from("grade_preference");
		$result = $this->db->get()->row();
		return $result;
	}

	function get_all($kStudent, $options){
		$this->db->where("kStudent", $kStudent);
		$variables = array("subject","school_year","term");
		foreach($variables as $variable){
			if(array_key_exists($variable, $options) && $options[$variable] != NULL){
				$this->db->where($variable, $options[$variable]);
			}
		}

		$this->db->order_by("school_year");
		$this->db->order_by("subject");

		$this->db->from("grade_preference");
		$result = $this->db->get()->result();
		return $result;
	}

	function delete($id){
	    $kStudent = $this->get($id)->kStudent;
	    $this->db->delete("grade_preference",array("id"=>$id));
	    return $kStudent;

	}
}