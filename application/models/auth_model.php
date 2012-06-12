<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 

class Auth_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	function is_user($username)
	{

		$this->db->where("username", $username);
		$this->db->from("teacher");
		$count = $this->db->get()->num_rows();
		$result = false;

		if($count == 1){
			$result = TRUE;
		}

		return $result;

	}

	function validate($username, $password)
	{
		$this->db->where("username", $username);
		$this->db->where("pwd", $this->encrypt($password));
		$this->db->select("kTeach, dbRole");
		$this->db->from("teacher");
		$query = $this->db->get();
		$count = $query->num_rows();
		$output = FALSE;
		if($count == 1){
			$output = $query->row();
		}
		return $output;

	}


	function get_role($kTeach)
	{
		$this->db->where("kTeach", $kTeach);
		$this->db->select("dbRole");
		$this->db->from("teacher");
		$result = $this->db->get()->row();
		return $result->dbRole;
	}


	function set_role($kTeach,$role)
	{
		$this->db->where("kTeach", $kTeach);
		$data["dbRole"] = $role;
		$this->db->update("teacher", $data);
	}

	function get_username($kTeach)
	{
		$this->load->model("teacher_model");
		$teacher = $this->teacher_model->get($kTeach,"username");
		return $teacher->username;
	}


	function change_password($kTeach, $old, $new)
	{
		$result = FALSE;
		$username = $this->get_username($kTeach);
		$is_valid = $this->validate($username, $old);

		if($is_valid){
			$this->db->where("username", $username);
			$this->db->where("pwd",$this->encrypt($old));
			$data["pwd"] = $this->encrypt($new);
			$this->db->update("teacher", $data);
			if($this->validate($username, $new)){
				$result = TRUE;
			}
		}
		return $result;
	}


	function encrypt($text)
	{
		return md5(md5($text));
	}

	function email_exists($email){
		$output = FALSE;
		$this->db->where("email",$email);
		$this->db->select("kTeach");
		$this->db->from("teacher");
		$row = $this->db->get()->row();
		if(!empty($row)){
			$output = $row->kTeach;
		}
		return $output;
	}
	
	function set_reset_hash($kTeach)
	{
		$hash = $this->encrypt(now());
		$data["reset_hash"] = $hash;
		$this->db->where("kTeach", $kTeach);
		$this->db->update("teacher",$data);
		return $hash;
	}
	
	
	function reset_password($kTeach, $reset_hash, $password)
	{
		$this->db->where("kTeach", $kTeach);
		$this->db->where("reset_hash", $reset_hash);
		$this->db->where("`reset_hash` IS NOT NULL");
		$data["pwd"] = $this->encrypt($password);
		$data["reset_hash"] = "";
		$this->db->update("teacher", $data);
		$username = $this->get_username($kTeach);
		return $this->validate($username, $password);
	}
	
	function log($kTeach, $action)
	{
		$data["kTeach"] = $kTeach;
		$data["action"] = $action;
		$data["time"] = mysql_timestamp();
		$data["username"] = $this->get_username($kTeach);
		$this->db->insert("user_log",$data);
	}

}