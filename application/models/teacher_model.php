<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Teacher_model extends CI_Model
{

	var $teachFirst;
	var $teachLast;
	var $teachClass;
	var $username;
	var $email;
	var $gradeStart;
	var $gradeEnd;
	var $is_advisor;
	var $status;
	var $recModified;
	var $recModifier;

	function __construct()
	{

		parent::__construct();
		$this->load->helper('general');

	}


	function prepare_variables()
	{

		$variables = array("teachFirst","teachLast","teachClass","username","email","gradeStart","gradeEnd","is_advisor","status");
		for($i = 0; $i < count($variables); $i++){
			$myVariable = $variables[$i];
			if($this->input->post($myVariable)){
				$this->$myVariable = $this->input->post($myVariable);
			}
		}

		$this->recModified = mysql_timestamp();
		$this->recModifier = $this->session->userdata('userID');

	}


	function get_all($options = array()){
		$userRole = $this->session->userdata("dbRole");
		$userID = $this->session->userdata("userID");
		//    $dbStatus=1;
		//    $dbRole=2;
		$this->db->order_by("status", "DESC");
		$this->db->order_by("dbRole", "DESC");
		$this->db->order_by("teachLast", "ASC");
		$this->db->order_by("gradeStart", "ASC");
		$this->db->order_by("gradeEnd", "ASC");

		if($userRole == 1){
			if($userID != 1000){  //only the administrator should have any reason to see the other administrators.
				$this->db->where("dbRole != " , 1);
			}
			if(array_key_exists("showInactive", $options)){
				$this->db->where_in("status", array(0,1,2));
			}else{
				$this->db->where_in("status", array(1,2));
			}
				
			if(array_key_exists("role",$options)){
				$this->db->where_in("dbRole",$options["role"]);
			}else{
				$this->db->where_in("dbRole", array(2,3));
			}

			if(array_key_exists("gradeRange", $options)){
				$this->db->where("gradeStart >= " .$options["gradeRange"]["gradeStart"]);
				$this->db->where("gradeStart <= " . $options["gradeRange"]["gradeEnd"]);
			}
		}else{
			$this->db->where("dbRole != " , 1);
			$this->db->where("status", 1);

		}

		$this->db->select("teacher.kTeach, teachFirst, teachLast,gradeStart, gradeEnd, dbRole, status");
		$this->db->from("teacher");
		$result = $this->db->get()->result();

		return $result;

	}


	/**
	 * this is a very expensive function to avoid password confusion.
	 * If at any point access were to be expanded, we'd need a separate table for "users" separate from "teachers" (in fact this has already started)
	 * but then the question is whether this would cause an equivalent cost in joins between users and teachers elsewhere.
	 * @param int $kTeach
	 * @param string $select
	 * @return string|boolean
	 */
	function get($kTeach, $select = NULL)
	{
		if($select){
			if(is_array($select)){
				foreach($select as $item){
					$this->db->select($item);
				}
			}else{
				$this->db->select($select);
			}
		}
		/*else{
			$columns = $this->get_columns();
		foreach($columns as $row){
		//avoid returning the password field.
		//@TODO move the passwords to a separate table maybe?
		//maybe the fact that pwd is not actually sent to the browser in any way makes this concern irrelevant.
		if($row->Field != "pwd"){
		$this->db->select($row->Field);
		}
		}
		}
		*/
		$this->db->where('kTeach', $kTeach);
		$this->db->from('teacher');

		$result = $this->db->get()->row();
		if($result){
			return $result;
		}else{
			return false;
		}

	}


	function get_teacher_pairs($dbRole = 2, $status = 1)
	{
		if($dbRole){
			$this->db->where('dbRole', 2);
		}
		if($status){
			$this->db->where('status', 1);
		}
		$this->db->select("CONCAT(teachFirst,' ',teachLast) as teacher", false);
		$this->db->select('kTeach');
		$direction = "ASC";
		$order_field = "teachFirst";

		$this->db->order_by($order_field, $direction);
		$this->db->from('teacher');
		$query = $this->db->get()->result();
		return $query;
	}



	function get_name($kTeach)
	{
		$this->db->select("CONCAT(teachFirst,' ',teachLast) as teacher", false);
		$this->db->from('teacher');
		$this->db->where('kTeach', $kTeach);
		$result = $this->db->get()->row();
		return $result->teacher;
	}



	/**
	 * Setter
	 */
	function insert()
	{
		$this->prepare_variables();
		$this->db->insert('teacher', $this);
		$kTeach = $this->db->insert_id();
		$this->set_db_role($kTeach);
		return $kTeach;
	}

	function set_db_role($kTeach){
		//if($this->input->post("dbRole")){
		$data["dbRole"] = $this->input->post("dbRole");
		if($this->session->userdata("dbRole") == 1 && $this->session->userdata("userID") == 1000){
			$this->db->where("kTeach", $kTeach);
			$this->db->update("teacher",$data);
		}
		//}
	}


	function update($kTeach)
	{

		$this->prepare_variables();
		$this->db->where('kTeach', $kTeach);
		$this->db->update('teacher', $this);
		$this->set_db_role($kTeach);

	}


	function get_columns()
	{
		$result = $this->db->query("SHOW COLUMNS FROM `teacher`")->result();
		return $result;

	}



	function insert_subject($kTeach,$subject, $gradeStart, $gradeEnd)
	{
		$data["kTeach"] = $kTeach;
		$data["subject"] = $subject;
		$data["gradeStart"] = $gradeStart;
		$data["gradeEnd"] = $gradeEnd;
		$this->db->insert("teacher_subject", $data);
		return $this->db->insert_id();

	}

	function delete_subject($kTeach, $kSubject)
	{
		$data["kTeach"] = $kTeach;
		$data["kSubject"] = $kSubject;
		$this->db->delete("teacher_subject", $data);
	}

}