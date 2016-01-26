<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Template_model extends CI_Model
{
	var $kTeach;
	var $template;
	var $subject;
	var $term;
	var $year;
	var $gradeStart = 0;
	var $gradeEnd = 0;
	var $type;
	var $isActive = 0;
	var $recModified;
	var $recModifier;


	function __construct()
	{
		parent::__construct();
		$this->load->helper('general');
	}


	function prepare_variables()
	{
		$variables = array("kTeach","template","subject","term","year","gradeStart","gradeEnd","type","isActive");
		for($i = 0; $i < count($variables); $i++){
			$myVariable = $variables[$i];
			if($this->input->post($myVariable)){
				$this->$myVariable = $this->input->post($myVariable);
			}
		}

		$this->recModified = mysql_timestamp();
		$this->recModifier = $this->session->userdata('userID');

	}


	function get($kTemplate)
	{
		$this->db->where('kTemplate', $kTemplate);
		$this->db->from('template');
		$result = $this->db->get()->row();
		return $result;
	}


/**
 * 
 * @param int $kTeach
 * @param array $options
 * @param string $include_inactive
 * @return query results
 * this is used both to generate a list of templates for a given teacher based on search criteria in the $options value and also
 * for generating template options to apply to a given student narrative based on the student's grade and subject. 
 */
	function get_all($kTeach, $options = array(), $include_inactive = FALSE){
		if(!empty($options)){
			//search for a list of items submitted as key-value pairs. 
			//This makes for easy search queries on predictably structured field-value pairs. 
			if(array_key_exists("where", $options)){
				$keys = array_keys($options["where"]);
				$values = array_values($options["where"]);
				for($i = 0; $i < count($options["where"]); $i++){
					if(ucfirst($values[$i]) == "K"){
						$this->db->where($keys[$i], "0");
					}else{
						$this->db->where($keys[$i], $values[$i]);
					}
				}
			}
			if(array_key_exists("grade_range", $options)){
				$gradeStart = $options["grade_range"]["gradeStart"];
				$gradeEnd = $options["grade_range"]["gradeEnd"];
				$this->db->where("(`gradeStart` >= '$gradeStart' AND `gradeEnd` <= '$gradeEnd')");

			}
			//if a student grade value is submitted, we're presenting a list of templates to apply to a student narrative.
			if(array_key_exists("stuGrade",$options)){
				$stuGrade = $options['stuGrade'];
				$this->db->where("('$stuGrade' BETWEEN `gradeStart` AND `gradeEnd`)");
			}
				
		}

		if($include_inactive == FALSE){
			$this->db->where("isActive", 1);

		}
		$this->db->where("kTeach", $kTeach);
		$this->db->order_by("year", "DESC");
		$this->db->order_by("term", "DESC");
		$this->db->order_by("gradeStart,gradeEnd");
		$this->db->from('template');
		$result = $this->db->get()->result();
		return $result;
	}


	function insert()
	{
		$this->prepare_variables();
		$this->db->insert("template",$this);
		return $this->db->insert_id();
	}


	function update($kTemplate)
	{
		$this->prepare_variables();
		$this->db->where("kTemplate", $kTemplate);
		$this->db->update("template", $this);

	}


	function delete($kTemplate)
	{
		$update_array = array("isActive"=>0);
		$this->db->where("kTemplate",$kTemplate);
		$this->db->update("template", $update_array);
	}


	function get_inactive($kTeach = NULL){
		if($kTeach){
			$this->db->where("kTeach", $kTeach);
		}
		$this->db->where("isActive",0);
		$this->db->from("template");
		$this->db->order_by("kTeach");
		$this->db->order_by("year","DESC");
		$this->db->order_by("term","DESC");
		$result = $this->db->get()->result();
		return $result;
	}


}