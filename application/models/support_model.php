<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Support_model extends CI_Model
{
	var $kStudent;
	var $year;
	var $specialNeed;
	var $description;
	var $modification;
	var $classification;
	var $meeting;
	var $testDate;
	var $outsideSupport;
	var $hasIEP;
	var $hasSPPS;

	function __construct()
	{
		parent::__construct();
	}

	function prepare_variables()
	{

		$variables = array("kStudent","year","specialNeed","description","modification","classification","meeting","testDate","outsideSupport","hasIEP","hasSPPS");

		for($i = 0; $i < count($variables); $i++){
			$myVariable = $variables[$i];
			if($this->input->post($myVariable)){
				if($myVariable == "testDate"){
					if($this->input->post($myVariable) != ""){
						$this->$myVariable = format_date($this->input->post($myVariable),"mysql");
					}
				}else {
					$this->$myVariable = $this->input->post($myVariable);
				}
			}
		}

		$this->recModified = mysql_timestamp();
		$this->recModifier = $this->session->userdata('userID');

	}


	function insert()
	{
		$this->prepare_variables();
		$this->db->insert('need', $this);
		return $this->db->insert_id();
	}


	function update($kNeed)
	{
		$this->prepare_variables();
		$this->db->where("kNeed", $kNeed);
		$this->db->update("need", $this);
		$need = $this->get($kNeed);
		return format_timestamp($need->recModified);
	}


	function delete($kNeed)
	{
		$this->db->where("kNeed", $kNeed);
		$this->db->from("need");
		$this->db->delete();
	}


	function get($kNeed)
	{
		$this->db->where("kNeed", $kNeed);
		$this->db->where("`need`.`kStudent` = `student`.`kStudent`");
		$this->db->order_by("year", "DESC");
		$this->db->select("need.*");
		$this->db->select("student.stuFirst, student.stuLast, student.stuNickname");
		$this->db->from("need");
		$this->db->from("student");
		$result = $this->db->get()->row();
		return $result;
	}


	function get_all($kStudent)
	{
		$this->db->from("need");
		$this->db->where("kStudent", $kStudent);
		$this->db->order_by("year", "DESC");
		$result = $this->db->get()->result();
		return $result;
	}


	function get_current($kStudent)
	{

		$year = get_current_year();
		$this->db->where("kStudent", $kStudent);
		$this->db->order_by("year", "DESC");
		$this->db->limit(1);
		$this->db->from("need");
		$query = $this->db->get();
		$count = $query->num_rows();
		$result = false;
		if($count > 0 ) {
			$result = $query->row();
		}
		return $result;

	}

}


