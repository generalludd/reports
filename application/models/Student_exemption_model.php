<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Student_exemption_model extends MY_Model {
	var $kStudent;
	var $kSubject;
	var $schoolYear;
	var $notes;

	function prepare_variables()
	{
		$variables = array (
				"kStudent",
				"kSubject",
				"schoolYear",
				"notes" 
		);
		for($i = 0; $i < count ( $variables ); $i ++) {
			$myVariable = $variables [$i];
			if ($this->input->post ( $myVariable )) {
				$this->$myVariable = $this->input->post ( $myVariable );
			}
		}
	}

	function get($kStudent, $kSubject, $schoolYear)
	{
		$this->db->from ( "student_exemption" );
		$this->db->where ( "kStudent", $kStudent );
		$this->db->where ( "kSubject", $kSubject );
		$this->db->where ( "schoolYear", $schoolYear );
		$output = $this->db->get ()->row ();
		return $output;
	}

	function insert()
	{
		$this->prepare_variables ();
		$this->db->insert ( "student_exemption", $this );
	}

	function update()
	{
		$this->prepare_variables ();
		$this->db->where ( "kStudent", $this->kStudent );
		$this->db->where ( "kSubject", $this->kStubject );
		$this->db->where ( "schoolYear", $this->schoolYear );
		$this->db->update ( "student_exemption", $this );
	}

	function delete($kStudent, $kSubject, $schoolYear)
	{
		$this->prepare_variables ();
		$this->db->where ( "kStudent", $this->kStudent );
		$this->db->where ( "kSubject", $this->kStubject );
		$this->db->where ( "schoolYear", $this->schoolYear );
		$this->db->delete ( "student_exemption" );
	}
}