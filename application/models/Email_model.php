<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Email_model extends CI_Model
{
	var $default;
	var $mailpath;
	var $protocol;
	var $smtp_host;
	var $smtp_auth;
	var $smtp_port;
	var $smtp_user;
	var $smtp_pass;
	var $newline;
	var $charset;

	function prepare_variables()
	{
		$variables = array("default","mailpath","protocol","smtp_host","smtp_auth","smtp_port","smtp_user","smtp_pass","newline","charset");

		for($i = 0; $i < count($variables); $i++){
			$myVariable = $variables[$i];
			if($this->input->post($myVariable)){
				$this->$myVariable = $this->input->post($myVariable);
			}
		}
	}


	function insert()
	{
		$this->prepare_variables();
		$kEmail = $this->db->insert("email",$this);

	}


	function update($kEmail)
	{
		$this->prepare_variables();
		$this->db->where("kEmail",$kEmail);
		$this->db->update("email",$this);
	}


	function get($kEmail)
	{
		$this->db->from("email");
		$this->db->where("kEmail", $kEmail);
		$result = $this->db->get()->row();
		return $result;
	}


	function get_all(){
		$this->db->from("email");
		$this->db->order_by("default");
		$result = $this->db->get()->result();
		return $result;
	}

	function get_default()
	{
		$this->db->from("email");
		$this->db->where("default",1);
		$result = $this->db->get()->row();
		return $result;
	}


	function delete($kEmail)
	{
		$deletion = array("kEmail" => $kEmail);
		$this->db->delete("email", $deletion);
	}


}


