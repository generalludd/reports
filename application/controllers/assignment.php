<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Assignment extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model("grade_model","grade");
		$this->load->model("assignment_model","assignment");
		$this->load->helper("grade_helper");
	}

	function index()
	{
		$this->chart();
	}

	/**
	 * produce a chart of assignments and student grades based on the submitted criteria
	 */
	function chart()
	{
		$kTeach = $this->session->userdata("userID");
		if($this->input->get("kTeach")){
			$kTeach = $this->input->get("kTeach");
		}
		$gradeStart = $this->input->get("gradeStart");
		bake_cookie("gradeStart",$gradeStart);
		$gradeEnd = $this->input->get("gradeEnd");
		bake_cookie("gradeEnd",$gradeEnd);


		$term = get_current_term();
		if($this->input->get("term")){
			$term = $this->input->get("term");
			bake_cookie("term", $term);
		}

		$stuGroup = NULL;
		if($this->input->get("stuGroup")){
			$stuGroup = $this->input->get("stuGroup");
		}

		bake_cookie("stuGroup",$stuGroup);

		$year = get_current_year();
		if($this->input->get("year")){
			$year = $this->input->get("year");
			bake_cookie("year",$year);
		}
		
		$date_range = array();
		if($this->input->get("date_start") && $this->input->get("date_end")){
			$date_start = format_date($this->input->get("date_start"),"mysql");
			$date_end = format_date($this->input->get("date_end"),"mysql");
			$date_range["date_start"] = $date_start;
			$date_range["date_end"] = $date_end;
				
		}
		$grade_options ["from"] = "grade";
		$grade_options ["join"] = "assignment";
		
		$data["grades"] = $this->assignment->get_grades($kTeach,$term,$year,$gradeStart,$gradeEnd,$stuGroup, $date_range);
		foreach($data['grades'] as $grade){
			$grade_options ['subject'] = $grade->subject;
			$grade->final_grade = $this->assignment->get_for_student ( $grade->kStudent, $grade->term, $grade->year, $grade_options );
		}
		$data["assignments"] = $this->assignment->get_for_teacher($kTeach,$term,$year,$gradeStart,$gradeEnd, $date_range);
		$data["category_count"] = 0;
		if(empty($data["assignments"])){
			$data["category_count"] = $this->assignment->count_categories($kTeach, $gradeStart, $gradeEnd);
		}
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

	/**
	 * display a search dialog for showing a grade chart.
	 */
	function search()
	{
		$data["kTeach"] = $this->session->userdata("userID");
		if($this->input->get("kTeach")){
			$data["kTeach"] = $this->input->get("kTeach");
		}
		$data["term"] = $this->input->cookie("term");
		$data["year"] = $this->input->cookie("year");
		$data["gradeStart"] = $this->input->cookie("gradeStart");
		$data["gradeEnd"] = $this->input->cookie("gradeEnd");
		$data["stuGroup"] = $this->input->cookie("stuGroup");
		$this->load->view("assignment/search",$data);
	}

	/**
	 * display a dialog for creating a new assignment
	 */
	function create()
	{
		$data["assignment"] = NULL;
		$data["action"] = "insert";
		$this->load->model("subject_model");
		$kTeach = $this->session->userdata("userID");
		$subjects = $this->subject_model->get_for_teacher($kTeach);
		$data['subjects'] = get_keyed_pairs($subjects, array('subject', 'subject'));
		$userID = $this->session->userdata("userID");
		//$gradeStart = $this->session->userdata("gradeStart");
		//$gradeEnd = $this->session->userdata("gradeEnd");
		$gradeStart = $this->input->cookie("gradeStart");
		$gradeEnd = $this->input->cookie("gradeEnd");
		$year = $this->input->cookie("year");
		$term = $this->input->cookie("term");
		$categories = $this->assignment->get_categories($userID, $gradeStart, $gradeEnd,$year, $term);
		if(empty($categories)){
			$gradeRange = sprintf("grades %s to %s", $gradeStart, $gradeEnd);
			if($gradeStart == $gradeEnd){
				$gradeRange = sprintf("grade %s", $gradeStart);
			}
			printf('<p>You must create categories for %s first for %s, %s.<p/>',$gradeRange,$term,$year);
		}else{
			$data["categories"] = get_keyed_pairs($categories, array("kCategory","category"));
			$data["target"] = "assignment/edit";
			$data["title"] = "Create an Assignment";
			$this->load->view($data["target"],$data);
		}

	}

	/**
	 * insert an assignment into the database
	 */
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

	/**
	 * display a dialog for editing an assignment
	 */
	function edit()
	{
		$kAssignment = $this->input->get("kAssignment");
		$assignment = $this->assignment->get($kAssignment);
		$this->session->set_flashdata("notice",$this->db->last_query());
		$this->load->model("subject_model");
		$kTeach = $assignment->kTeach;
		$subjects = $this->subject_model->get_for_teacher($kTeach);
		$data['subjects'] = get_keyed_pairs($subjects, array('subject', 'subject'));
		$data["assignment"] = $assignment;
		$data["action"] = "update";
		$categories = $this->assignment->get_categories($assignment->kTeach, $assignment->gradeStart, $assignment->gradeEnd,$assignment->term, $assignment->year);
		$data["categories"] = get_keyed_pairs($categories, array("kCategory","category"));
		$this->load->view("assignment/edit",$data);
	}

	/**
	 * update an assignment and redirect to the established grade/date range for the assignment.
	 */
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

	/**
	 * delete an assignment and return to the assignment's term and grade range
	 */
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

	/**
	 * return a table row for creating assignment weight categories (AJAX-based).
	 */
	function create_category()
	{
		$data["category"] = NULL;
		$data["action"] = "insert";
		$data["kTeach"] = $this->uri->segment(3);
		$this->load->view("assignment/category_row", $data);
	}

	/**
	 * add a created category into the database
	 */
	function insert_category()
	{
		$kTeach = $this->input->post("kTeach");
		$category = $this->input->post("category");
		$weight = $this->input->post("weight");
		$gradeStart = $this->input->post("gradeStart");
		$gradeEnd = $this->input->post("gradeEnd");
		$year = $this->input->post("year");
		$term = $this->input->post("term");
		
		$data = array();
		if($category && $weight && $gradeStart && $gradeEnd){
			$data["category"] = $category;
			$data["kTeach"] = $kTeach;
			$data["weight"] = $weight;
			$data["gradeStart"] = $gradeStart;
			$data["gradeEnd"] = $gradeEnd;
			$data["year"] = $year;
			$data["term"] = $term;
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

	/**
	 * display a dialog for editing assignment weight categories.
	 */
	function edit_categories()
	{
		$data["kTeach"] = $this->uri->segment(3);
		$data["gradeStart"] = $this->input->cookie("gradeStart");
		$data["gradeEnd"] = $this->input->cookie("gradeEnd");
		$data["year"] = $this->input->cookie("year");
		$data["term"] = $this->input->cookie("term");
		$data["categories"] = $this->assignment->get_categories($data["kTeach"], $data["gradeStart"] , $data["gradeEnd"],$data["year"],$data["term"]);
		$this->load->view("assignment/categories",$data);
	}

	/**
	 * update an assignment weight category
	 */
	function update_category()
	{
		$kCategory = $this->input->post("kCategory");
		$data["category"] = $this->input->post("category");
		$data["weight"] = $this->input->post("weight");
		$data["gradeStart"] = $this->input->post("gradeStart");
		$data["gradeEnd"] = $this->input->post("gradeEnd");
		$data["term"] = $this->input->post("term");
		$data["year"] = $this->input->post("year");
		$this->assignment->update_category($kCategory,$data);
		$this->session->set_flashdata("notice", $this->db->last_query());

	}

}