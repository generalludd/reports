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
		$this->chart();
	}


	function chart()
	{
		$kTeach = $this->session->userdata("userID");
		if($this->input->get("kTeach")){
			$kTeach = $this->input->get("kTeach");
		}
		$gradeStart = $this->input->get("gradeStart");
		$this->session->set_userdata("gradeStart",$gradeStart);
		$gradeEnd = $this->input->get("gradeEnd");
		$this->session->set_userdata("gradeEnd",$gradeEnd);

		$term = get_current_term();
		if($this->input->get("term")){
			$term = $this->input->get("term");
			$this->session->set_userdata("term",$term);

		}

		$year = get_current_year();
		if($this->input->get("year")){
			$year = $this->input->get("year");
			$this->session->set_userdata("year",$year);

		}
		$data["grades"] = $this->assignment->get_grades($kTeach,$term,$year,$gradeStart,$gradeEnd);
		$data["assignments"] = $this->assignment->get_for_teacher($kTeach,$term,$year);
		$data["kTeach"] = $kTeach;
		$data["term"] = $term;
		$data["year"] = $year;
		$data["target"] = "grade/chart";
		$data["title"] = "Grade Chart";
		$this->load->view("page/index",$data);
	}

	function search()
	{
		$data["kTeach"] = $this->session->userdata("userID");
		$data["term"] = $this->session->userdata("term");
		$data["year"] = $this->session->userdata("year");
		$data["gradeStart"] = $this->session->userdata("gradeStart");
		$data["gradeEnd"] = $this->session->userdata("gradeEnd");
		$this->load->view("assignment/search",$data);
	}


	function create()
	{
		$data["assignment"] = NULL;
		$data["action"] = "insert";
		$this->load->model("subject_model");
		$kTeach = $this->session->userdata("userID");
		$subjects = $this->subject_model->get_for_teacher($kTeach);
		$data['subjects'] = get_keyed_pairs($subjects, array('subject', 'subject'));
		$categories = $this->assignment->get_categories($this->session->userdata("userID"));
		$data["categories"] = get_keyed_pairs($categories, array("category","category"),NULL,TRUE);
		$data["target"] = "assignment/edit";
		$data["title"] = "Create an Assignment";
		$this->load->view($data["target"],$data);

	}

	function insert()
	{
		$this->assignment->insert();
		$kTeach = $this->input->post("kTeach");
		$term = $this->input->post("term");
		$year = $this->input->post("year");
		$gradeStart = $this->input->post("gradeStart");
		$gradeEnd = $this->input->post("gradeEnd");

		redirect("assignment/chart?kTeach=$kTeach&term=$term&year=$year&gradeStart=$gradeStart&gradeEnd=$gradeEnd");
	}

	function edit()
	{
		$kAssignment = $this->input->get("kAssignment");
		$assignment = $this->assignment->get($kAssignment);
		$this->load->model("subject_model");
		$kTeach = $assignment->kTeach;
		$subjects = $this->subject_model->get_for_teacher($kTeach);
		$data['subjects'] = get_keyed_pairs($subjects, array('subject', 'subject'));
		$data["assignment"] = $assignment;
		$data["action"] = "update";
		$categories = $this->assignment->get_categories($assignment->kTeach);
		$data["categories"] = get_keyed_pairs($categories, array("category","category"),NULL,TRUE);
		$this->load->view("assignment/edit",$data);
	}

	function update()
	{
		$kAssignment = $this->input->post("kAssignment");
		$this->assignment->update($kAssignment);
		$kTeach = $this->input->post("kTeach");
		$term = $this->input->post("term");
		$year = $this->input->post("year");
		$gradeStart = $this->input->post("gradeStart");
		$gradeEnd = $this->input->post("gradeEnd");
		redirect("assignment/chart?kTeach=$kTeach&term=$term&year=$year&gradeStart=$gradeStart&gradeEnd=$gradeEnd");
	}


}