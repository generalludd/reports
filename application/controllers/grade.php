<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Grade extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model("grade_model","grade");
		$this->load->model("assignment_model","assignment");
	}

	function index()
	{
		redirect();
	}

	/**
	 * editing the grades for a given student, term, subject, teacher
	 * the query script get_for_student in this context returns all the assignments possible for easy editing as a chart.
	 */
	function edit()
	{
		$this->load->model("menu_model");
		$kStudent = $this->input->get_post("kStudent");
		$kTeach = $this->input->get_post("kTeach");
		if($kStudent && $kTeach){
			$year = $this->input->get_post("year");
			if(!$year){
				$year = $this->input->cookie("year");//get_current_year();
			}
			$term = $this->input->get_post("term");
			if(!$term){
				$term = $this->input->cookie("term");//get_current_term();
			}
			$options["grade_range"]["gradeStart"] = $this->input->get_post("gradeStart");
			if(!$options["grade_range"]["gradeStart"]){
				$options["grade_range"]["gradeStart"] = $this->input->cookie("gradeStart");
			}
			$options["grade_range"]["gradeEnd"] = $this->input->get_post("gradeEnd");
			if(!$options["grade_range"]["gradeEnd"]){
				$options["grade_range"]["gradeEnd"] = $this->input->cookie("gradeEnd");
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

	}


	/**
	 * edit_cell allows editing of a single grade in a chart for a given student.
	 */
	function edit_cell()
	{
		$kAssignment = $this->input->get("kAssignment");
		$kStudent = $this->input->get("kStudent");
		if($kAssignment && $kStudent){
			$this->load->model("menu_model");
			$data["grade"] = $this->grade->get($kStudent,$kAssignment);
			$footnotes = $this->menu_model->get_pairs("grade_footnote");
			$data["footnotes"] = get_keyed_pairs($footnotes, array("value","label"),TRUE);
			$status = $this->menu_model->get_pairs("grade_status");
			$data["status"] = get_keyed_pairs($status, array("value","label"),TRUE);
			$this->load->view("grade/edit_cell",$data);
		}
	}


	/**
	 * Edit a column of grades by assignment
	 */
	function edit_column()
	{

		$kAssignment = $this->input->get_post("kAssignment");
		$stuGroup = NULL;
		if($this->input->cookie("stuGroup")){
			$stuGroup = $this->input->cookie("stuGroup");
		}
		$this->load->model("menu_model");
		$data["assignment"] = $this->assignment->get($kAssignment);
		$data["grades"] = $this->assignment->get_assignment_grades($kAssignment, $stuGroup);
		$footnotes = $this->menu_model->get_pairs("grade_footnote");
		$data["footnotes"] = get_keyed_pairs($footnotes, array("value","label"),TRUE);
		$status = $this->menu_model->get_pairs("grade_status");
		$data["status"] = get_keyed_pairs($status, array("value","label"),TRUE);
		$data["target"] = "grade/edit_column";
		$data["title"] = "Editing Grades for a Given Assignment";
		$this->load->view($data["target"],$data);
	}


	/**
	 * select_student offers a selection option to find a student to add to a grade chart.
	 */
	function select_student()
	{
		$data["kTeach"] = $this->input->get("kTeach");
		$data["term"] = $this->input->cookie("term");
		$data["year"] = $this->input->cookie("year");
		$data["js_class"] = "select-student-for-grades";
		$data["action"] = "grade/edit";
		$this->load->view("student/mini_selector",$data);
	}


	/**
	 * update also inserts (through the update script in grade_method).
	 */
	function update()
	{
		$kStudent = $this->input->post("kStudent");
		$kAssignment = $this->input->post("kAssignment");
		if($kStudent && $kAssignment){
			$points = $this->input->post("points");
			$status = $this->input->post("status");
			$footnote = $this->input->post("footnote");
			$result = $this->grade->update($kStudent,$kAssignment, $points,$status,$footnote);
			echo $result;
		}
	}


	/**
	 * update_value updates a grade value based on a grade. There can be only one
	 * kStudent-kAssignment pair in the db
	 */
	function update_value()
	{
		$kStudent = $this->input->post("kStudent");
		$kAssignment = $this->input->post("kAssignment");
		if($kStudent && $kAssignment){
			$key = $this->input->post("key");
			$value = $this->input->post("value");
			$result = $this->grade->update_value($kStudent,$kAssignment, $key, $value);
			echo $value;
		}
	}

	
	/**
	 * delete_row this deletes all of a students records for an entire term. 
	 * warnings are provided through jQuery/javascript assignment.js file
	 */
	function delete_row()
	{
		$kTeach = $this->input->post("kTeach");
		$kStudent = $this->input->post("kStudent");
		$year = $this->input->post("year");
		$term = $this->input->post("term");
		$this->grade->delete_row($kStudent, $kTeach, $term, $year);
		echo $this->db->last_query();
	}

	/**
	 * select_report_card provides a dialog for selecting the report card for a given student based on year, term, cutoff-date, and subject
	 */
	function select_report_card()
	{
		if($kStudent = $this->input->get("kStudent")){
			$data["kStudent"] = $kStudent;
			$term = get_current_term();
			$data["terms"] = get_term_menu("term",$term);
			$year = get_current_year();
			$data["years"] = get_year_list();
			$subjects = $this->grade->get_subjects($kStudent,$term,$year);
			$data["subjects"] = get_keyed_pairs($subjects, array("subject","subject"), TRUE);
			$this->load->view("grade/selector",$data);
		}
	}

	/**
	 * report_card displays a report card based on submitted criteria for year, term, cutoff-date, and subject.
	 */
	function report_card(){
		if($kStudent = $this->input->get("kStudent")){
			$this->load->model("student_model","student");
			$kTeach = NULL;
			$options = array();
			$options["from"] = "grade";
			$options["join"] = "assignment";
			/*
			 * This print option is here mostly to avoid user confusion since
			* the grade reports will print well whether or not the navigation or button bars are visible or not.
			*/
			$print = FALSE;
			if($this->input->get("print")){
				$print = TRUE;
			}

			$output["print"] = $print;

			if($kTeach = $this->input->get("kTeach")){
				$options["kTeach"] = $kTeach;
			}
			$output["cutoff_date"] = FALSE;
			if($cutoff_date = $this->input->get("cutoff_date")){
				bake_cookie("cutoff_date",$cutoff_date);
				$options["cutoff_date"] = format_date($cutoff_date,"mysql");
				$output["cutoff_date"] = $cutoff_date;
			}

			$term = get_current_term();
			if($this->input->get("term")){
				$term = $this->input->get("term");
			}
			$output["term"] = $term;


			$year = get_current_year();
			if($this->input->get("year")){
				$year = $this->input->get("year");
			}
			$output["year"] = $year;

			if($subject = $this->input->get("subject")){
				$array = array("subject" => $subject);
				$subjects[] = (object) $array;
			}else{
				$subjects = $this->grade->get_subjects($kStudent,$term,$year);
			}
			$data["target"] = "grade/report_card";

			$student = $this->student->get($kStudent);


			$data["kStudent"] = $kStudent;
			$data["student"] = $student;
			$output["student_name"] =  format_name($student->stuNickname, $student->stuLast );
			$data["title"] = $output["student_name"];
			$output["charts"] = array();

			$i = 0;
			foreach($subjects as $subject){
				$options["subject"] = $subject->subject;
				$data["grades"] = $this->assignment->get_for_student($kStudent,$term,$year,$options);
				//if the student has any grades entered, process them here, otherwise ignore.
				$this->load->model("grade_preference_model","grade_preferences");
				$data["pass_fail"] = $this->grade_preferences->get_all($kStudent,array("school_year"=>$year,"subject"=>$subject->subject));
				if(count($data["grades"])){
					$data["subject"] = $subject->subject;
					$data["count"] = $i;// count is used to identify the chart number in the output for css purposes.
					$output["charts"][] = $this->load->view("grade/chart",$data,TRUE);
					$i ++;
				}
			}
	

			if($output){
				if($print){
					$this->load->view("page/print",$output);
				}else{
					$this->load->view("page/index",$output);
				}
			}

		}
	}

}