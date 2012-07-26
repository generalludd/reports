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

		$year = $this->input->get_post("year");
		if(!$year){
			$year = get_current_year();
		}
		$term = $this->input->get_post("term");
		if(!$term){
			$term = get_current_term();
		}
		$footnotes = $this->menu_model->get_pairs("grade_footnote");
		$data["footnotes"] = get_keyed_pairs($footnotes, array("value","label"),TRUE);
		$status = $this->menu_model->get_pairs("grade_status");

		$data["status"] = get_keyed_pairs($status, array("value","label"),TRUE);
		$data["kStudent"] = $kStudent;
		$data["kTeach"] = $kTeach;
		$options["kTeach"] = $kTeach;
		$data["grades"] = $this->assignment->get_for_student($kStudent,$term,$year,$options);
		$this->load->view("grade/edit",$data);


	}

	function edit_cell()
	{
		$kAssignment = $this->input->get("kAssignment");
		$kStudent = $this->input->get("kStudent");
		$this->load->model("menu_model");
		$data["grade"] = $this->grade->get($kStudent,$kAssignment);
		$footnotes = $this->menu_model->get_pairs("grade_footnote");
		$data["footnotes"] = get_keyed_pairs($footnotes, array("value","label"),TRUE);
		$status = $this->menu_model->get_pairs("grade_status");
		$data["status"] = get_keyed_pairs($status, array("value","label"),TRUE);
		$this->load->view("grade/edit_cell",$data);
	}


	function select_student()
	{
		$data["kTeach"] = $this->input->get("kTeach");
		$data["term"] = $this->session->userdata("term");
		$data["year"] = $this->session->userdata("year");
		$data["js_class"] = "select-student-for-grades";
		$data["action"] = "grade/edit";
		$this->load->view("student/mini_selector",$data);
	}

	function update()
	{
		$kStudent = $this->input->post("kStudent");
		$kAssignment = $this->input->post("kAssignment");
		$assignment = $this->assignment->get($kAssignment);
		//$total points is needed to calculate the grade average.
		//this calculation happens in the model to make more elegant code.
		$total = $assignment->points;
		$category = $assignment->category;
		$points = $this->input->post("points");
		$status = $this->input->post("status");
		$footnote = $this->input->post("footnote");
		$result = $this->grade->update($kStudent,$kAssignment,$points,$total,$status,$footnote,$category);
		echo OK;
	}



}