<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//application "index.php" file. This is home.
class Home extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$this->load->model("menu_model");
		$student_sort = $this->menu_model->get_pairs("student_sort");
		$data["body_classes"] = array("front");
		$data["student_sort"] = get_keyed_pairs($student_sort, array("value","label"));
		$this->load->model("teacher_model","teacher");
		$teachers = $this->teacher->get_teacher_pairs(2,1,"homeroom");
		$data['teachers'] = get_keyed_pairs($teachers, array("kTeach","teacher"),TRUE);
		$humanitiesTeachers = $this->teacher->get_for_subject("humanities");
		$data["humanitiesTeachers"] = get_keyed_pairs($humanitiesTeachers,array("kTeach","teacherName"),TRUE);
		$data['target'] = "student/search";
		$data['title'] = "Narrative Reporting System";
		$data['currentYear'] = get_current_year();
		$data['yearList'] = get_year_list();
		$this->load->view('page/index', $data );
	}


}