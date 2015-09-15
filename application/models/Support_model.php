<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Support_model extends CI_Model {
	var $kStudent;
	var $year;
	var $specialNeed;
	var $modification;
	var $meeting;
	var $testDate;
	var $outsideSupport;
	var $hasIEP;
	var $hasSPPS;

	function __construct()
	{
		parent::__construct ();
	}

	function prepare_variables()
	{
		$variables = array (
				"kStudent",
				"year",
				"specialNeed",
				"modification",
				"meeting",
				"testDate",
				"outsideSupport",
				"hasIEP",
				"hasSPPS" 
		);
		
		for($i = 0; $i < count ( $variables ); $i ++) {
			$myVariable = $variables [$i];
			if ($this->input->post ( $myVariable )) {
				$this->$myVariable = $this->input->post ( $myVariable );
			}
		}
		
		$this->recModified = mysql_timestamp ();
		$this->recModifier = $this->session->userdata ( 'userID' );
	}

	function insert()
	{
		$this->prepare_variables ();
		$this->db->insert ( 'support', $this );
		return $this->db->insert_id ();
	}

	function update($kSupport)
	{
		$this->prepare_variables ();
		$this->db->where ( "kSupport", $kSupport );
		$this->db->update ( "support", $this );
		$support = $this->get ( $kSupport );
		return format_timestamp ( $support->recModified );
	}

	function delete($kSupport)
	{
		$this->db->where ( "kSupport", $kSupport );
		$this->db->from ( "support" );
		$this->db->delete ();
	}

	function get($kSupport)
	{
		$this->db->where ( "kSupport", $kSupport );
		$this->db->where ( "`support`.`kStudent` = `student`.`kStudent`" );
		$this->db->order_by ( "year", "DESC" );
		$this->db->select ( "support.*" );
		$this->db->select ( "student.stuFirst, student.stuLast, student.stuNickname" );
		$this->db->from ( "support" );
		$this->db->from ( "student" );
		$result = $this->db->get ()->row ();
		return $result;
	}

	function get_all($kStudent)
	{
		$this->db->from ( "support" );
		$this->db->where ( "kStudent", $kStudent );
		$this->db->order_by ( "year", "DESC" );
		$result = $this->db->get ()->result ();
		return $result;
	}

	function get_current($kStudent)
	{
		$year = get_current_year ();
		$this->db->where ( "kStudent", $kStudent );
		$this->db->order_by ( "year", "DESC" );
		$this->db->limit ( 1 );
		$this->db->from ( "support" );
		$query = $this->db->get ();
		$count = $query->num_rows ();
		$result = false;
		if ($count > 0) {
			$result = $query->row ();
		}
		return $result;
	}
}


