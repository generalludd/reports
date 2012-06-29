<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Assignment extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model("grade_model","grade");
		$this->load->model("assignment_model","assignment");
	}

	function index()
	{
		$data["assignments"] = $this->assignment->get_grades(8,3,"Year-End",2011);
		
		$data["target"] = "grade/chart";
		$data["title"] = "Grade Chart";
		$this->load->view("page/index",$data);

	}


}