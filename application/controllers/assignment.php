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
		$data["grades"] = $this->assignment->get_grades(8,"Year-End",2011);
		$data["assignments"] = $this->assignment->get_for_teacher(8,"Year-End",2011);
		$data["kTeach"] = 8;
		$data["target"] = "grade/chart";
		$data["title"] = "Grade Chart";
		$this->load->view("page/index",$data);
	}


}