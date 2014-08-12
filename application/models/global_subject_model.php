<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Global_Subject_Model extends CI_Model
{
	var $grade_start;
	var $grade_end;
	var $context;
	var $subjects;

	function __construct()
	{

		parent::__construct ();
	
	}

	function prepare_variables()
	{

		$variables = array (
				"grade_start",
				"grade_end",
				"context",
				"subjects" 
		);
		foreach ( $variables as $variable ) {
			if ($my_value = $this->input->post ( $variable )) {
				$this->$variable = $my_value;
			}
		}
	
	}

	function get($grade_start, $grade_end, $context)
	{

		$this->db->from ( "global_subject" );
		$this->db->where ( "grade_start", $grade_start );
		$this->db->where ( "grade_end", $grade_end );
		$this->db->where ( "context", $context );
		$result = $this->db->get ()->row ();
		return $result;
	
	}

	function get_by_grade($grade, $context)
	{

		$this->db->where ( "$grade BETWEEN `grade_start` and `grade_end`", NULL, FALSE );
		$this->db->where ( "context", $context );
		$this->db->from ( "global_subject" );
		$result = $this->db->get ()->row ();
		$output = FALSE;
		if ($result) {
			$output = $result->subjects;
		}
		return $output;
	
	}

	function get_all()
	{

		$this->db->from ( "global_subject" );
		$this->db->order_by ( "grade_start,context" );
		$result = $this->db->get ()->result ();
		return $result;
	
	}

	function insert()
	{

		$this->prepare_variables ();
		$this->db->insert ( "global_subject", $this );
		return $this->get ( $this->grade_start, $this->grade_end, $this->context );
	
	}

	function update()
	{

		$this->prepare_variables ();
		$this->db->where ( "`grade_start` = '$this->grade_start'", NULL, TRUE );
		$this->db->where ( "grade_end", $this->grade_end );
		$this->db->where ( "context", $this->context );
		$this->db->update ( "global_subject", $this );
		
		return $this->get ( $this->grade_start, $this->grade_end, $this->context );
	
	}

}