<?php defined('BASEPATH') OR exit('No direct script access allowed');

class benchmark_legend extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model("benchmark_legend_model", "legend");
		$this->load->model("subject_model");

	}


	function get()
	{
		$variables = array("kTeach","title","term","year","gradeStart","gradeEnd","subject","legend");
		$params = array();
		for($i = 0; $i < count($variables); $i++){
			$myVariable = $variables[$i];
			if($this->input->post($myVariable)){
				$params[$myVariable] = $this->input->post($myVariable);
			}
		}
		$legend = $this->legend->get_one($params);
		return $legend;
	}
	
	function view()
	{
		$kLegend = $this->uri->segment(3);
		$legend = $this->legend->get($kLegend);
		$this->load->model("teacher_model");
		$data["legend"] = $legend;
		$teacher = $this->teacher_model->get($legend->kTeach, "teachFirst,teachLast");
		$data["teacher"] = $teacher->teachFirst . " " . $teacher->teachLast;
		$data["title"] = "Benchmark Legend";
		$data["target"] = "benchmark_legend/view";
		$this->load->view("page/index", $data);
		
	}


	function edit()
	{
		$kLegend = $this->uri->segment(3);
		$legend = $this->legend->get($kLegend);
		$data["legend"] = $legend;
		$subjects = $this->subject_model->get_for_teacher($this->session->userdata("userID"));

		$data["subjects"] = get_keyed_pairs($subjects, array("subject","subject"),FALSE);

		$data["action"] = "update";
		$data["target"] = "benchmark_legend/edit";
		$data["title"] = "Editing Benchmark Legend";
		$this->load->view("page/index", $data);
	}


	function create()
	{
		$data["legend"] = (object) array("kTeach"=>$this->session->userdata("userID"));
		$subjects = $this->subject_model->get_for_teacher($this->session->userdata("userID"));
		$data["subjects"] = get_keyed_pairs($subjects, array("subject","subject"),FALSE);
		$data["action"] = "insert";
		$data["target"] = "benchmark_legend/edit";
		$data["title"] = "Create a New Legend";
		$this->load->view("page/index", $data);
	}


	function update()
	{
		$kLegend = $this->input->post("kLegend");
		if($kLegend){
			$this->legend->update($kLegend);
		}
		redirect("benchmark_legend/view/$kLegend");
	}


	function insert()
	{
		$kLegend = 	$this->legend->insert();
		redirect("benchmark_legend/view/$kLegend");
	}



	function list_all()
	{
		$this->load->model("benchmark_legend_model");
		$variables = array("kTeach","title","term","year","gradeStart","gradeEnd","subject","legend");
		$params = array();
		for($i = 0; $i < count($variables); $i++){
			$myVariable = $variables[$i];
			if($this->input->get_post($myVariable)){
				$params[$myVariable] = $this->input->get_post($myVariable);
			}
		}
		$data["params"] = $params;
		$data["legends"] = $this->legend->search($params);
		$data["target"] ="benchmark_legend/list";
		$data["title"] = "Benchmark Legend List";
		$data["kTeach"] = $this->session->userdata("userID");
		$this->load->view("page/index", $data);
	}
	
	

	function search()
	{
		$this->load->model("subject_model");
		$this->load->model("teacher_model");
		$this->load->model("menu_model");
		$kTeach = $this->session->userdata("userID");
		$data["kTeach"] = $kTeach;
		$grades = $this->menu_model->get_pairs("grade");
		$data["grade_list"] = get_keyed_pairs($grades,array("value","label"));
		$data["years"] = get_year_list(TRUE);
		$subjects = $this->subject_model->get_for_teacher($kTeach);
		$data["subjects"] = get_keyed_pairs($subjects, array("subject","subject"),TRUE);
		$data["grades"] = $this->teacher_model->get($kTeach,array("gradeStart","gradeEnd"));
		$this->load->view("benchmark_legend/search", $data);
	}
}