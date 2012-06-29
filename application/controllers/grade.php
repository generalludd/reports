<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Grade extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model("grade_model","grade");
		$this->load->model("assignment_model","assignment");
	}
	
	function index()
	{
		$output = $this->assignment->get_for_student(5002,8,3,"Year-End",2011);
		print_r($output);
		
		
	}
	
}