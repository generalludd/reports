<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author administrator
 * The preference_type table contains the available preferences users can set for their accounts. 
 * Preference types can be created by the administrator or through the database directly, and then set by users.
 * Of course, they don't do anything unless they are recognized programmatically in the application scripts. 
 */
class Preference_type_model extends CI_Model
{
	
	var $type;
	var $name;
	var $description;
	var $options;
	var $format;
	var $sort_order;
	var $rec_modified;
	var $rec_modifier;
	
	function __construct()
	{
		parent::__construct();
	}
	
	function prepare_variables()
	{
	
		$variables = array("type","name","description","format","sort_order", "options");
		for($i = 0; $i < count($variables); $i++){
			$myVariable = $variables[$i];
			if($this->input->post($myVariable)){
				$this->$myVariable = $this->input->post($myVariable);
			}
		}
	
		$this->rec_modified = mysql_timestamp();
		$this->rec_modifier = $this->session->userdata('userID');
	}
	
	
	
	function insert()
	{
		$this->prepare_variables();
		$this->db->insert("preference_type", $this);
		$type = $this->db->insert_id();
		return $type;
	}
	
	
	
	function update($type)
	{
		
		$this->db->where("type", $type);
		$update["description"] = $this->input->post("description");
		$update["name"] = $this->input->post("name");
		$update["rec_modifier"] = $this->session->userdata("userID");
		$update["rec_modified"] = mysql_timestamp();
		$this->db->update("preference_type", $update);	
		
	}
	
	
	
	function get( $type )
	{
		
		$this->db->where("type",$type);
		$this->db->from("preference_type");
		$result = $this->db->get()->row();
		return $result;
		
	}
	
	
	
	function get_all()
	{
		$this->db->from("preference_type");
		$this->db->order_by("sort_order");
		$this->db->order_by("type");
		$result = $this->db->get()->result();
		return $result;
	}
	
	
	function get_formats()
	{
		$this->db->distinct("format");
		$this->db->from("preference_type");
		$this->db->order_by("format");
		$result = $this->db->get()->result();
		return $result;
	}
	
	function delete($type)
	{
		
		$this->db->delete("preference_type",array("type"=>$type));
	}
}