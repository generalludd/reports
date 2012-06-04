<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{

		$data['target'] = "student/search";
		$data['title'] = "Narrative Reporting System";
		$data['currentYear'] = get_current_year();
		$data['yearList'] = get_year_list();
		$this->load->view('page/index', $data );
	}
	

}