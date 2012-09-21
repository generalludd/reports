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
		$stuGroup = NULL;
		
		if($this->input->get("stuGroup")){
			$stuGroup = $this->input->get("stuGroup");
		}
		$this->session->set_userdata("stuGroup",$stuGroup);
		

		$year = get_current_year();
		if($this->input->get("year")){
			$year = $this->input->get("year");
			$this->session->set_userdata("year",$year);

		}
		$data["grades"] = $this->assignment->get_grades($kTeach,$term,$year,$gradeStart,$gradeEnd,$stuGroup);
		$data["assignments"] = $this->assignment->get_for_teacher($kTeach,$term,$year);
		$data["kTeach"] = $kTeach;
		$data["term"] = $term;
		$data["year"] = $year;
		$data["stuGroup"] = $stuGroup;
		$data["gradeStart"] = $gradeStart;
		$data["gradeEnd"] = $gradeEnd;
		$data["target"] = "assignment/chart";
		$data["title"] = "Grade Chart";
		$this->load->view("page/index",$data);
	}
	
	function report_card(){
		$kStudent = $this->input->get("kStudent");
		if($this->input->get("kTeach")){
			$options["kTeach"] = $this->input->get("kTeach");
				
		}
		if($this->input->get("subject")){
			$options["subject"] = $this->input->get("subject");
		}
		
		$term = get_current_term();
		if($this->input->get("term")){
			$term = $this->input->get("term");
		
		}
		$year = get_current_year();
		if($this->input->get("year")){
			$year = $this->input->get("year");
		}

		$data["grades"] = $this->assignment->get_for_student($kStudent,$term,$year,$options);
		$data["target"] = "grade/chart";
		$data["title"] = "Report Card";
		$this->load->view("page/print",$data);
	}

	function search()
	{
		$data["kTeach"] = $this->session->userdata("userID");
		$data["term"] = $this->session->userdata("term");
		$data["year"] = $this->session->userdata("year");
		$data["gradeStart"] = $this->session->userdata("gradeStart");
		$data["gradeEnd"] = $this->session->userdata("gradeEnd");
		$data["stuGroup"] = $this->session->userdata("stuGroup");
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
		$data["categories"] = get_keyed_pairs($categories, array("kCategory","category"));
		$data["target"] = "assignment/edit";
		$data["title"] = "Create an Assignment";
		$this->load->view($data["target"],$data);

	}

	function insert()
	{
		$kAssignment = $this->assignment->insert();
		$kTeach = $this->input->post("kTeach");
		$term = $this->input->post("term");
		$year = $this->input->post("year");
		$gradeStart = $this->input->post("gradeStart");
		$gradeEnd = $this->input->post("gradeEnd");
		$students = $this->grade->batch_insert($kAssignment,$kTeach,$term,$year);
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
		$data["categories"] = get_keyed_pairs($categories, array("kCategory","category"));
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

	function delete()
	{
		if($this->input->post("kAssignment")){
			$kAssignment = $this->input->post("kAssignment");
			$assignment = $this->assignment->get($kAssignment);
			$this->assignment->delete($kAssignment);
		}
		$kTeach = $assignment->kTeach;
		$term = $assignment->term;
		$year = $assignment->year;
		$gradeStart = $assignment->gradeStart;
		$gradeEnd = $assignment->gradeEnd;
		redirect("assignment/chart?kTeach=$kTeach&term=$term&year=$year&gradeStart=$gradeStart&gradeEnd=$gradeEnd");
	}
	
	
	function create_category()
	{
		$data["category"] = NULL;
		$data["action"] = "insert";
		$data["kTeach"] = $this->uri->segment(3);
		$this->load->view("assignment/category_row", $data);
	}
	
	function insert_category()
	{
		$kTeach = $this->input->post("kTeach");
		$category = $this->input->post("category");
		$weight = $this->input->post("weight");
		if($category && $weight){
			$data["category"] = $category;
			$data["kTeach"] = $kTeach;
			$data["weight"] = $weight;
		}
		$kCategory = $this->assignment->insert_category($data);
		$category = $this->assignment->get_category($kCategory);
		$data["category"] = $category;
		$data["action"] = "update";
		$data["kTeach"] = $kTeach;
		$this->load->view("assignment/category_row",$data);
		
		
		
	}
	
	
	function edit_categories()
	{
		$data["kTeach"] = $this->uri->segment(3);
		$data["categories"] = $this->assignment->get_categories($data["kTeach"]);
		$this->load->view("assignment/categories",$data);
	}
	
	function update_category()
	{
		$kCategory = $this->input->post("kCategory");
		$data["category"] = $this->input->post("category");
		$data["weight"] = $this->input->post("weight");
		$this->assignment->update_category($kCategory,$data);
		
	}

}