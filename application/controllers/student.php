<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('student_model');
		$this->load->model('teacher_model');
		$this->load->model('menu_model');
		$this->load->helper('general');

	}



	function view()
	{
		$kStudent = $this->uri->segment(3);
		$student = $this->student_model->get($kStudent);
		$data["student"] = $student;
		$data["teacherName"] = "";
		if(!empty($student->kTeach)){
			$data["teacherName"] = $this->teacher_model->get_name($student->kTeach);
		}
		$data["target"] = "student/view";
		$data["title"] = "Viewing $student->stuFirst $student->stuLast";
		if($this->input->post("ajax")){
			$this->load->view($data["target"], $data);
		}else{
			$this->load->view("page/index", $data);

		}
	}


	function find_by_name()
	{
		$stuName = $this->input->get("stuName");
		$data["students"] = $this->student_model->find_students($stuName);
		$data["teacher"] = NULL;
		$this->load->view("student/list", $data);
	}



	function create()
	{
		$data["student"] = NULL;
		$data["action"] = "insert";
		$grades = $this->menu_model->get_pairs("grade");
		$data["gradePairs"] = get_keyed_pairs($grades, array("value", "label"));
		$gender = $this->menu_model->get_pairs("gender");
		$data["genderPairs"] =  get_keyed_pairs($gender, array("value","label"));
		$teachers = $this->teacher_model->get_teacher_pairs();
		$data["teacherPairs"] = get_keyed_pairs($teachers, array("kTeach", "teacher"));
		$data["target"] = "student/edit";
		$data["title"] = "Add a New Student";
		if($this->input->post("ajax")){
			$this->load->view($data["target"], $data);
		}else{
			$this->load->view("page/index", $data);
		}

	}


	function edit()
	{
		if($this->input->post("kStudent")){
			$kStudent = $this->input->post("kStudent");
			$student = $this->student_model->get($kStudent);
			if(empty($student->stuEmail) && $student->stuGrade > 2){
				$student->stuEmail = $this->generate_email($kStudent, $student->stuNickname, $student->stuLast);
			}
			$data["student"] = $student;
			$data["action"] = "update";
			$grades = $this->menu_model->get_pairs("grade");
			$data["gradePairs"] = get_keyed_pairs($grades, array("value", "label"));
			$gender = $this->menu_model->get_pairs("gender");
			$data["genderPairs"] =  get_keyed_pairs($gender, array("value","label"));
			$teachers = $this->teacher_model->get_teacher_pairs();
			$data["teacherPairs"] = get_keyed_pairs($teachers, array("kTeach", "teacher"));
			$data["target"] = "student/edit";
			$data["title"] = "Add a New Student";
			if($this->input->post("ajax")){
				$this->load->view($data["target"], $data);
			}else{
				$this->load->view("page/index", $data);
			}
		}
	}




	function update()
	{
		if($this->input->post("kStudent")){
			$kStudent = $this->input->post("kStudent");
			$this->student_model->update($kStudent);
			redirect("/student/view/$kStudent");
		}
	}


	function insert()
	{
		$kStudent = $this->student_model->insert();
		redirect("/student/view/$kStudent");
	}

	function teacher_student_list()
	{
		$kTeach = $this->uri->segment(3);
		$data["students"] = $this->student_model->get_students_by_teacher($kTeach);
		$data["target"] = "student/list";
		$data["title"] = "Student List";
		$this->load->view("page/index", $data);
	}


	function advanced_search(){
		$year = get_current_year();
		$session["year"] = $year;
		$session["grades"] = "";
		$session["hasNeeds"] = "";
		$session["includeFormerStudents"] = "";
		if($this->input->get($year)){
			$year = $this->input->get("year");
			$session["year"] = $year;
		}

		$grades = array();
		if($this->input->get("grades")){
			$grades = $this->input->get("grades");
			$session["grades"] = $grades;
		}
		$hasNeeds = 0;
		if($this->input->get("hasNeeds")){
			$hasNeeds = $this->input->get("hasNeeds");
			$session["hasNeeds"] = $hasNeeds;
		}
		$includeFormerStudents = 0;
		if($this->input->get("includeFormerStudents")){
			$includeFormerStudents = $this->input->get("includeFormerStudents");
			$session["includeFormerStudents"] =  $includeFormerStudents;
		}
		$this->session->set_userdata($session);
		$data["criteria"] = $this->input->get();
		$data["students"] = $this->student_model->advanced_find($year,$grades, $hasNeeds, $includeFormerStudents);
		$data["title"] = "Student List";
		if($this->input->get("export")){
			$this->load->helper("download");
			$this->load->view("student/export", $data);
		}else{
			$data["target"] = "student/results";
			$this->load->view("page/index", $data);
		}
	}

	//@TODO this needs to also check teacher email accounts to avoid duplication there.
	//@TODO this needs an error catch mechanism for situations where there is no post and no submitted variables
	function valid_email($kStudent = FALSE, $stuEmail = FALSE) {
		$count = 0;
		if(!$kStudent){
			$kStudent = $this->input->get_post("kStudent");
		}
		if(!$stuEmail){
			$stuEmail = $this->input->get_post("stuEmail");
		}
		if($stuEmail){
			$count = $this->student_model->count("stuEmail", $stuEmail, array("kStudent"=>$kStudent));
			if($this->input->get_post("validation")){
				echo $count;
			}
		}
		return $count;
	}

	function generate_email($kStudent = FALSE, $first = FALSE){
		if(!$kStudent){
			$kStudent = $this->input->get_post("kStudent");
		}

		if(!$first){
			$first = $this->input->get_post("first");
		}

		if($kStudent && $first){
			$name = $first;
			//@TODO this could be replaced with a grep that selects only letters.
			//@TODO may want to accommodate accented characters.
			$name = str_replace(" ","",$name);
			$stuEmail = strtolower("$name") . "01";
			if($this->input->get_post("ajax") == TRUE){
				echo $stuEmail;
			}
			return $stuEmail;
		}
	}

	function update_all_emails()
	{
		$students = $this->student_model->get_students_by_grade(3,8);
		foreach($students as $student){
			$this->student_model->update_value($student->kStudent, array("stuEmail"=> $this->generate_email($student->kStudent, $student->stuNickname)));
		}
	}

/******* MAINTENANCE SCRIPTS *********/
	function update_grades()
	{
		$this->student_model->update_grades();
		$cookie = array(
				'name'   => 'admin',
				'value'  => 'Student grades were successfully updated',
				'expire' => '1',
		);
		
		$this->input->set_cookie($cookie);
		redirect("admin");		
	}

}
