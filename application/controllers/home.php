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
		$data["student_sort"] = get_keyed_pairs($student_sort, array("value","label"));
		$data['target'] = "student/search";
		$data['title'] = "Narrative Reporting System";
		$data['currentYear'] = get_current_year();
		$data['yearList'] = get_year_list();
		$this->load->view('page/index', $data );
	}


}