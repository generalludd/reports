<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Help_model extends CI_Model 
{
	var $helpTopic;
	var $helpSubtopic;
	var $helpText;
	
	function __construct()
	{
		parent::__construct();
	}
	
	function prepare_variables()
	{
		$variables = array("helpTopic","helpSubtopic","helpText");
		for($i = 0; $i < count($variables); $i++){
			$myVariable = $variables[$i];
			if($this->input->post($myVariable)){
				$this->$myVariable = $this->input->post($myVariable);
			}
		}
		
		//$this->recModified = mysql_timestamp();
		//$this->recModifier = $this->session->userdata('userID');
	}
	
	function get($helpTopic,$helpSubtopic=NULL)
	{
		$this->db->select("helpText");
		$this->db->where("helpTopic", $helpTopic);
		if($helpSubtopic){
			$this->db->where("helpSubtopic", $helpSubtopic);
		}
		$this->db->from("help");
		$row = $this->db->get()->row();
		return $row->helpText;
	}//end showHelp
	
}