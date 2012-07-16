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
		$this->load->model("menu_model");
		$kStudent = $this->input->get_post("kStudent");
		$kTeach = $this->input->get_post("kTeach");
		$footnotes = $this->menu_model->get_pairs("grade_footnote");
		$data["footnotes"] = get_keyed_pairs($footnotes, array("value","label"),TRUE);
		$status = $this->menu_model->get_pairs("grade_status");
		$data["status"] = get_keyed_pairs($status, array("value","label"),TRUE);
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
		$status = $this->input->post("status");
		$footnote = $this->input->post("footnote");
		$result = $this->grade->update($kStudent,$kAssignment,$points,$status,$footnote);
		echo OK;
	}
	
	
	
}