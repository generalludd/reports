<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Suggestion_model extends CI_Model
{

	var $kNarrative;
	var $kTeach;
	var $kStudent;
	var $narrText;
	var $narrTerm;
	var $narrYear;
	var $recModifier;
	var $recModified;


	function __construct()
	{
		parent::__construct();
	}

	function prepare_variables()
	{
		$variables = array("kNarrative","kTeach","kStudent","narrText", "narrTerm","narrYear");
		for($i = 0; $i < count($variables); $i++){
			$myVariable = $variables[$i];
			if($this->input->post($myVariable)){
				$this->$myVariable = $this->input->post($myVariable);
			}
		}

		$this->recModified = mysql_timestamp();
		$this->recModifier = $this->session->userdata('userID');
	}

	function get($kNarrative){
		$this->db->where('kNarrative', $kNarrative);
		$this->db->from('suggestion');
		$result = $this->db->get()->row();
		return $result;
	}

	function get_value($kNarrative, $fieldName)
	{
		$this->db->where('kNarrative', $kNarrative);
		$this->db->select($fieldName);
		$this->db->from('narrative');
		$result = $this->db->get()->row();

		return $result->$fieldName;
	}


	function exists($kNarrative)
	{

		$this->db->where("kNarrative", $kNarrative);
		$this->db->from('suggestion');
		$this->db->select("COUNT(kNarrative) as the_count");
		$result = $this->db->get()->row();
		if($result->the_count > 0){
			$output = TRUE;
		}else{
			$output = FALSE;
		}
		return $output;
	}

	function insert()
	{
		$this->prepare_variables();
		$this->insert('suggestion', $this);
		$kNarrative = $this->db->insert_id();
		$recModified = $this->get_value($kNarrative, 'recModified');
		return array($kNarrative, format_timestamp($recModified));
	}

	function update($kNarrative)
	{
		$this->prepare_variables();
		$this->db->where('kNarrative', $kNarrative);
		$this->db->update('narrative', $this);
		$recModified = $this->get_value($kNarrative, 'recModified');
		return array($kNarrative, format_timestamp($recModified));
	}

}

