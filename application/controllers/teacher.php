<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Teacher extends MY_Controller
{

	function __construct()
	{

		parent::__construct();
		$this->load->model("teacher_model");
		$this->load->model("subject_model");
		$this->load->model("menu_model");

	}

	function index()
	{
		$data["target"] = "teacher/list";
		$data["options"] = array();
		if($this->input->get_post("showInactive")){
			$data["options"]["showInactive"] = TRUE;
		}

		if($this->input->get_post("showAdmin")){
			$data["options"]["showAdmin"] = TRUE;
		}
		if($this->input->get_post("role")){
			$data["options"]["role"] = $this->input->get_post("role");
		}
		if($this->input->get_post("gradeStart") >= 0 && $this->input->get_post("gradeEnd") >= 0){
			$gradeStart = $this->input->get_post("gradeStart");
			$gradeEnd = $this->input->get_post("gradeEnd");
			$data["options"]["gradeRange"]["gradeStart"] = $gradeStart;
			$data["options"]["gradeRange"]["gradeEnd"] = $gradeEnd;
			bake_cookie("gradeStart", $gradeStart);
			bake_cookie("gradeEnd",$gradeEnd);
		}
		$data["teachers"] = $this->teacher_model->get_all($data["options"]);

		$data["title"] = "List of Teachers";
		$this->load->view("page/index", $data);
	}



	function create()
	{
		if($this->session->userdata("dbRole") == 1){
			$data["dbRole"] = 2;
			$data["action"] = "insert";
			$data["target"] = "teacher/edit";
			$data["title"] = "Insert a New Teacher";
			$data["subjects"] = $this->subject_model->get_all();
			$dbRoles = $this->menu_model->get_pairs("dbRole");
			$data["dbRoles"] = get_keyed_pairs($dbRoles, array("value","label"));
			$userStatus = $this->menu_model->get_pairs("userStatus");
			$data["userStatus"] = get_keyed_pairs($userStatus, array("value", "label"));
			$grades = $this->menu_model->get_pairs("grade");
			$data["grades"] = get_keyed_pairs($grades, array("value", "label"));
			$classrooms = $this->menu_model->get_pairs("classroom");
			$data["classrooms"] = get_keyed_pairs($classrooms, array("value", "label"));
			$data["teacher"] = NULL;
			if($this->input->get_post("ajax")){
				$this->load->view($data["target"], $data);
			}else{
				$this->load->view("page/index",$data);
			}
		}else {
			$this->index();
		}
	}


	function view()
	{
		$kTeach = $this->uri->segment(3);

		$teacher = $this->teacher_model->get($kTeach);
		$data["year"] = get_current_year();
		$data["term"] = get_current_term();
		$data["kTeach"] = $kTeach;
		$data["teacher"] = $teacher;
		$data["subjects"] = $this->subject_model->get_for_teacher($kTeach);
		$data["target"] = "teacher/view";
		$data["title"] = "Viewing Information for $teacher->teachFirst $teacher->teachLast";

		$this->load->view("page/index", $data);

	}


	function edit()
	{
		$kTeach = $this->input->get_post("kTeach");
		if($this->session->userdata("userID") == $kTeach || $this->session->userdata("dbRole") == 1){
			$teacher = $this->teacher_model->get($kTeach);
			$data["dbRole"] = $this->session->userdata("dbRole");
			$data["userID"] = $this->session->userdata("userID");
			$data["teacher"] = $teacher;
			$data["action"] = "update";
			$data["subjects"] = $this->subject_model->get_for_teacher($kTeach);
			$dbRoles = $this->menu_model->get_pairs("dbRole");
			$data["dbRoles"] = get_keyed_pairs($dbRoles, array("value","label"));
			$userStatus = $this->menu_model->get_pairs("userStatus");
			$data["userStatus"] = get_keyed_pairs($userStatus, array("value", "label"));
			$grades = $this->menu_model->get_pairs("grade");
			$data["grades"] = get_keyed_pairs($grades, array("value", "label"));
			$classrooms = $this->menu_model->get_pairs("classroom");
			$data["classrooms"] = get_keyed_pairs($classrooms, array("value", "label"));
			$data["target"] = "teacher/edit";
			$data["title"] = "Editing $teacher->teachFirst $teacher->teachLast";
			if($this->input->get_post("ajax")){
				$this->load->view($data["target"], $data);
			}else{
				$this->load->view('page/index', $data);
			}
		}else{
			print "You are not authorized to edit this teacher record";
		}
		//		}
	}


	function update()
	{

		if($this->input->post("kTeach")){
			$kTeach = $this->input->post("kTeach");
			$this->teacher_model->update($kTeach);
			redirect("teacher/view/$kTeach");
		}

	}

	function insert()
	{
		if($this->session->userdata("dbRole") == 1){
			$kTeach = $this->teacher_model->insert();
			redirect("teacher/view/$kTeach");
		}

	}

	function show_search()
	{
		$grade_list = $this->menu_model->get_pairs("grade");
		$data["grades"] = get_keyed_pairs($grade_list, array("value","label"));
		$this->load->view("teacher/search", $data);
	}

	function subject_menu()
	{
		$kTeach = $this->input->get_post("kTeach");
		$this->load->model("subject_model");
		$subjects = get_keyed_pairs($this->subject_model->get_for_teacher($kTeach), array("subject", "subject"));
		echo form_dropdown("subject",$subjects,$this->input->cookie("current_subject"),"id='subject'");
	}

	function grade_range()
	{
		$this->load->model("menu_model");
		$kTeach = $this->input->get_post("kTeach");
		$teacher_grades = $this->teacher_model->get($kTeach,"gradeStart,gradeEnd");
		$grades = get_keyed_pairs($this->menu_model->get_pairs("grade"), array("value","label"));
		$output = form_dropdown("gradeStart", $grades, $teacher_grades->gradeStart, "id='gradeStart'");
		$output .= "-" . form_dropdown("gradeEnd", $grades, $teacher_grades->gradeEnd, "id='gradeEnd'");
		echo $output;
	}


	function add_subject()
	{

		//@TODO NEED missing subjects option
		$data["kTeach"] = $this->input->get_post("kTeach");
		$data["gradeStart"] = $this->input->get_post("gradeStart");
		$data["gradeEnd"] = $this->input->get_post("gradeEnd");

		$data["subjects"] = $this->subject_model->get_missing($data["kTeach"], $data);

		$grades = $this->menu_model->get_pairs("grade");
		$data["grades"] = get_keyed_pairs($grades,array("value","label"));
		$this->load->view("teacher/edit_subject", $data);
	}


	function insert_subject()
	{
		$kTeach = $this->input->get_post("kTeach");
		$subject = $this->input->get_post("subject");
		$gradeStart = $this->input->get_post("subGradeStart");
		$gradeEnd = $this->input->get_post("subGradeEnd");
		$this->teacher_model->insert_subject($kTeach,$subject,$gradeStart, $gradeEnd);
		$teacher = $this->teacher_model->get($kTeach,"gradeStart,gradeEnd");
		$data['subjects'] = $this->subject_model->get_for_teacher($kTeach);
		$this->load->view("teacher/subject_list", $data);

	}


	function delete_subject()
	{
		$kTeach = $this->input->post("kTeach");
		$kSubject = $this->input->post("kSubject");
		$this->teacher_model->delete_subject($kTeach, $kSubject);
		$data['subjects'] = $this->subject_model->get_for_teacher($kTeach);
		$this->load->view("teacher/subject_list", $data);
	}



}