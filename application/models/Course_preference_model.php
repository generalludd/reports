<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Course_Preference_model extends MY_Model
{
	var $subject;
	var $school_year;
	var $preference;

	function __construct(){
		parent::__construct();
	}

	function update($data)
	{
		if($this->record_exists($data["kStudent"], $data["subject"], $data["school_year"])){
			$this->db->where("kStudent", $data["kStudent"]);
			$this->db->where("subject", $data["subject"]);
			$this->db->where("school_year", $data["school_year"]);
			$this->db->update("course_preference",$data);
		}else{
			$this->db->insert("course_preference",$data);
		}


	}

	function record_exists($kStudent, $subject, $school_year)
	{
		$this->db->where("kStudent", $kStudent);
		$this->db->where("subject", $subject);
		$this->db->where("school_year", $school_year);
		$this->db->from("course_preference");
		return $this->db->count_all_results();
	}

	function get($id){
		$this->db->where("id", $id);
		$this->db->from("course_preference");
		$result = $this->db->get()->row();
		return $result;
	}
	
	function get_one($kStudent, $options){
		$this->db->where("kStudent",$kStudent);
		$variables = array("subject","school_year");
		foreach($variables as $variable){
			if(array_key_exists($variable, $options) && $options[$variable] != NULL){
				$this->db->where($variable, $options[$variable]);
			}
		}
		$this->db->from("course_preference");
		$this->db->join("menu","menu.value=course_preference.preference");
		$this->db->select("menu.label as preference");
		$result = $this->db->get()->row();
		
		if($result){
			$output = $result->preference;
		}else{
			$output = FALSE;
		}
		return $output;
	}

	function get_all($kStudent, $options = array()){
		$this->db->where("kStudent", $kStudent);
		$variables = array("subject","school_year");
		foreach($variables as $variable){
			if(array_key_exists($variable, $options) && $options[$variable] != NULL){
				$this->db->where($variable, $options[$variable]);
			}
		}

		$this->db->order_by("school_year", "DESC");
		$this->db->order_by("subject");
		$this->db->from("course_preference");
		$this->db->join("menu","menu.value=course_preference.preference");
		$this->db->select("course_preference.school_year, course_preference.subject, course_preference.id");
		$this->db->select("menu.label as preference");
		$result = $this->db->get()->result();
		$this->_log();
		return $result;
	}

	function delete($id){
	    $kStudent = $this->get($id)->kStudent;
	    $this->db->delete("course_preference",array("id"=>$id));
	    return $kStudent;

	}
}