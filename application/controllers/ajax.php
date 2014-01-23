<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 * @author administrator
 * this script is for ajax access to simple php scripts mostly the helper functions.
 */
class ajax extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

	}

	function current_grade()
	{
		$baseGrade = $this->input->get("baseGrade");
		$baseYear = $this->input->get("baseYear");
		echo get_current_grade($baseGrade, $baseYear);
	}

	function current_year()
	{
		echo get_current_year();
	}

	function current_term()
	{
		echo get_current_term();
	}

	function format_grade()
	{
		$grade = $this->input->get("grade");
		echo format_grade($grade);
	}

}