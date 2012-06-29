<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class File_model extends CI_Model
{
	var $kSupport;
	var $kStudent;
	var $file_display_name;
	var $file_description;
	var $file_name;
	var $file_type;
	var $file_size;
	var $file_path;

	function __construct()
	{
		parent::__construct();
	}

	function prepare_variables($file_data)
	{

		$variables = array("kSupport","kStudent","file_display_name","file_description");
		for($i = 0; $i < count($variables); $i++){
			$myVariable = $variables[$i];
			if($this->input->post($myVariable)){
				$this->$myVariable = $this->input->post($myVariable);
			}
		}

		if(array_key_exists("file_name", $file_data)){
			$this->file_name = $file_data["file_name"];
		}

		if(array_key_exists("file_type", $file_data)){
			$this->file_type = $file_data["file_type"];
		}

		if(array_key_exists("file_size", $file_data)){
			$this->file_size = $file_data["file_size"];
		}

		if(array_key_exists("file_path", $file_data)){
			$this->file_path = $file_data["file_path"];
		}

		$this->recModified = mysql_timestamp();
		$this->recModifier = $this->session->userdata('userID');


	}

	function insert($kSupport, $file_data)
	{
		$this->prepare_variables($file_data);
		$this->db->insert('support_file', $this);
		return $this->db->insert_id();
	}

	function get($kFile)
	{
		$this->db->where('kFile', $kFile);
		$this->db->from('support_file');
		$result = $this->db->get()->row();
		return $result;
	}

	
	function get_for_student($kStudent){
		$this->db->where("kStudent", $kStudent);
		$this->db->from("support_file");
		$result = $this->db->get()->result();
		return $result;
	}
	
	
	function get_all($kSupport)
	{
		$this->db->where('kSupport', $kSupport);
		$this->db->from('support_file');
		$this->db->order_by('file_display_name');
		$result = $this->db->get()->result();
		return $result;
	}

	function delete($kFile)
	{
		$file = $this->get($kFile);
		unlink($file->file_path."/".$file->file_name);
		$id_array = array('kFile' => $kFile);
		$this->db->delete('support_file', $id_array);
	}


	function fetch_values($fields, $file_fields = null){
		$this->db->from('support_file');
		$this->db->distinct();

		if(is_array($fields)){
			foreach($fields as $field){
				$this->db->select($field);
			}
		}else{
			$this->db->select($fields);
		}

		if($file_fields){
			if(is_array($file_fields)){
				foreach($file_fields as $file){
					$this->db->order_by($file);
				}
			}else{
				$this->db->order_by($file_fields);
			}
		}

		$query = $this->db->get();
		$result = $query->result();
		return $result;
	}
}