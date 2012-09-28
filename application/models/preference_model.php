<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Preference_model extends CI_Model
{


	function __construct()
	{
		parent::__construct();
	}


	function get($kTeach, $type)
	{
		$value = FALSE;
		$this->db->where('kTeach', $kTeach);
		$this->db->where('type', $type);
		$this->db->select('value');
		$this->db->from('preference');
		$result = $this->db->get()->row();

		if($result){
			$value = $result->value;
		}
			
		return $value;
	}

	
	function get_distinct($kTeach){
		$this->db->distinct("preference.type");
		$this->db->where("kTeach",$kTeach);
		$this->db->from("preference");
		$result = $this->db->get()->result();
		return $result;
	}
	
	function get_all($kTeach)
	{
		$this->db->select("preference_type.*, preference.value,preference.kTeach");
		$this->db->order_by("preference_type.sort_order");
		
		$this->db->from("preference_type");
		$this->db->join("preference", "preference_type.type=preference.type AND preference.kTeach = $kTeach", "left");
		$this->db->distinct("preference.type");
		$result = $this->db->get()->result();
		return $result;
	}
	

	function update($kTeach, $type, $value)
	{
		$output = FALSE;
		$exists = $this->get($kTeach, $type);
		if($exists){
			if(empty($value)){
				$output = $this->delete($kTeach, $type);
			}else{
				$this->db->where("kTeach", $kTeach);
				$this->db->where("type", $type);
				$data["type"] = $type;
				$data["value"] = $value;
				$this->db->update("preference", $data);
				$verification = $this->get($kTeach, $type);
				if($verification == $value){
					$output = TRUE;
				}
			}
		}else{
			$output = $this->insert($kTeach, $type, $value);
		}
		return $output;

	}


	function insert($kTeach, $type, $value)
	{
		$output = FALSE;
		$data["kTeach"] = $kTeach;
		$data["type"] = $type;
		$data["value"] = $value;
		$this->db->insert("preference", $data);
		$verification = $this->get($kTeach, $type);
		if($verification == $value){
			$output = TRUE;
		}
		return $output;
	}

	function delete($kTeach, $type)
	{
		$output = FALSE;
		$this->db->where("kTeach", $kTeach);
		$this->db->where("type", $type);
		$this->db->delete("preference");
		$exists = $this->get($kTeach, $type);
		if(!$exists){
			$output = TRUE;
		}
		return $output;
	}


}