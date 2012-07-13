<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Grade extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model("grade_model","grade");
		$this->load->model("assignment_model","assignment");
	}
	
	function edit()
	{
		$kStudent = $this->input->get_post("kStudent");
		$kTeach = $this->input->get_post("kTeach");
		$data["kStudent"] = $kStudent;
		$data["kTeach"] = $kTeach;
		$data["grades"] = $this->assignment->get_for_student($kStudent,$kTeach,"Year-End",2011);
		$this->load->view("grade/edit",$data);
		
		
	}
	
	function select_student()
	{
		$data["kTeach"] = $this->input->get("kTeach");
		$data["term"] = $this->input->get("term");
		$data["year"] = $this->input->get("year");
		$data["js_class"] = "select-student-for-grades";
		$data["action"] = "grade/edit";
		$this->load->view("student/mini_selector",$data);
	}
	
	function update()
	{
		$kStudent = $this->input->post("kStudent");
		$kAssignment = $this->input->post("kAssignment");
		$points = $this->input->post("points");
		$result = $this->grade->update($kStudent,$kAssignment,$points);
		echo OK;
	}
	
	
	
}