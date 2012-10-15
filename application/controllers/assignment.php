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
		$data["assignments"] = $this->assignment->get_for_teacher($kTeach,$term,$year,$gradeStart,$gradeEnd);
		$data["category_count"] = 0;
		if(empty($data["assignments"])){
			$data["category_count"] = $this->assignment->count_categories($kTeach, $gradeStart, $gradeEnd);
		}
		//$data["totals"] = $this->grade->get_summary($kTeach, $gradeStart, $gradeEnd, $term, $year);
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


	function search()
	{
		$data["kTeach"] = $this->session->userdata("userID");
		if($this->input->get("kTeach")){
			$data["kTeach"] = $this->input->get("kTeach");
		}
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
		$userID = $this->session->userdata("userID");
		$gradeStart = $this->session->userdata("gradeStart");
		$gradeEnd = $this->session->userdata("gradeEnd");
		$categories = $this->assignment->get_categories($userID, $gradeStart, $gradeEnd);
		if(empty($categories)){
			$gradeRange = sprintf("grades %s to %s", $gradeStart, $gradeEnd);
			if($gradeStart == $gradeEnd){
				$gradeRange = sprintf("grade %s", $gradeStart);
			}
			printf('<p>You must create categories for %s first.<p/>',$gradeRange);
		}else{
			$data["categories"] = get_keyed_pairs($categories, array("kCategory","category"));
			$data["target"] = "assignment/edit";
			$data["title"] = "Create an Assignment";
			$this->load->view($data["target"],$data);
		}

	}

	function insert()
	{
		$kAssignment = $this->assignment->insert();
		$kTeach = $this->input->post("kTeach");
		$term = $this->input->post("term");
		$year = $this->input->post("year");
		$gradeStart = $this->input->post("gradeStart");
		$gradeEnd = $this->input->post("gradeEnd");
		$points = 0;
		if($this->input->post("prepopulate") == 1){
			$points = $this->input->post("points");
		}
		$students = $this->grade->batch_insert($kAssignment,$kTeach,$term,$year,$gradeStart,$gradeEnd,$points);
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
		$categories = $this->assignment->get_categories($assignment->kTeach, $assignment->gradeStart, $assignment->gradeEnd);
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
		$gradeStart = $this->input->post("gradeStart");
		$gradeEnd = $this->input->post("gradeEnd");
		$data = array();
		if($category && $weight && $gradeStart && $gradeEnd){
			$data["category"] = $category;
			$data["kTeach"] = $kTeach;
			$data["weight"] = $weight;
			$data["gradeStart"] = $gradeStart;
			$data["gradeEnd"] = $gradeEnd;

			$kCategory = $this->assignment->insert_category($data);
			$category = $this->assignment->get_category($kCategory);
			$data["category"] = $category;
			$data["action"] = "update";
			$data["kTeach"] = $kTeach;
			$this->load->view("assignment/category_row",$data);
		}else{
			echo "Something didn't work right. We are working on the problem.";
		}


	}


	function edit_categories()
	{
		$data["kTeach"] = $this->uri->segment(3);
		$data["gradeStart"] = $this->session->userdata("gradeStart");
		$data["gradeEnd"] = $this->session->userdata("gradeEnd");
		$data["categories"] = $this->assignment->get_categories($data["kTeach"], $data["gradeStart"] , $data["gradeEnd"]);
		$this->load->view("assignment/categories",$data);
	}

	function update_category()
	{
		$kCategory = $this->input->get_post("kCategory");
		$data["category"] = $this->input->get_post("category");
		$data["weight"] = $this->input->get_post("weight");
		$data["gradeStart"] = $this->input->get_post("gradeStart");
		$data["gradeEnd"] = $this->input->get_post("gradeEnd");
		$this->assignment->update_category($kCategory,$data);
		print $this->db->last_query();

	}

}