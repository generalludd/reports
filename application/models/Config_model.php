<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Config_model extends CI_Model
{
	var $config_key = NULL;
	var $config_value = NULL;
	var $config_description = NULL;

	function __construct()
	{
		parent::__construct();
	}

	function prepare_variables()
	{
		if($this->input->post("config_key")){
			$this->config_key = $this->input->post("config_key");
		}

		if($this->input->post("config_value")){
			$this->config_value = $this->input->post("config_value");
		}

		if($this->input->post("config_description")){
			$this->config_description = $this->input->post("config_description");
		}
	}
	
	function get($kConfig){
		$this->db->from("config");
		$this->db->where("kConfig",$kConfig);
		$result = $this->db->get()->row();
		return $result;
	}
	

	function get_by_key($config_key, $fields = NULL)
	{

		if(!empty($fields)){
			if(is_array($fields)){
				foreach($fields as $field){
					   $this->db->select($field);
				}
			}else{
				$this->db->select($fields);
			}
		}
		$this->db->where("config_key", $config_key);
		$this->db->from("config");
		$query = $this->db->get();
		$count = $query->num_rows();
		$result = false;

		if($count > 0 ) {
			$result = $query->row();
		}

		return $result;

	}

	function replace($config_key, $config_value, $config_description)
	{

		$sql = "REPLACE INTO `config` SET `config`.`config_key` = ?, `config`.`config_value` = ?, `config`.`config_description` = ?";
		$this->db->query($sql, array($config_key, $config_value, $config_description));

	}

	function get_all($config_group = NULL)
	{
		$this->db->from("config");
		if($config_group){
			$this->db->where("config_group", $config_group);
		}
		$this->db->order_by("config_group");
		$result = $this->db->get()->result();
		return $result;
	}
	
	function update($kConfig){
		$this->db->where("kConfig",$kConfig);
		$this->prepare_variables();
		$this->db->update("config",$this);
	}

	
    /**
     * this is be a function to prepare the global configs for first run or if the database has been damaged somehow.
     * right now there is no UI for access to the global config database. 
     */
    function restore()
    {
        $configs['year-start']['config_value'] = "07-01";
        $configs['year-start']['config_description'] = "The date at which the system should matriculate students to the next grade, etc";
        $configs['mid-year']['config_value'] = "01-20";
        $configs['mid-year']['config_description'] = "The end of the term for attendance purposes";
        $configs['year-end']['config_value'] = "06-08";
        $configs['year-end']['config_description'] = "End of the year for attendance purposes";
        $configs['edits_mid-year']['config_value'] = "02-20";
        $configs['edits_mid-year']['config_description'] = "Cutoff for editing mid-year reports";
        $configs['edits_year-end']['config_value'] = "07-01";
        $configs['edits_year-end']['config_description'] = "Cuttoff for editing year-end reports";
        $keys = array_keys($configs);
        for($i = 0; $i < count($configs); $i++){
            $this->replace( $keys[$i], $configs[$keys[$i]]['config_value'], $configs[$keys[$i]]['config_description']);
        }

    }

}
