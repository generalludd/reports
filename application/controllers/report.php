<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model("student_report_model","report");
	}


	function create()
	{

		$kStudent = $this->uri->segment(3);
		$this->load->model("student_model","student");
		$this->load->model("teacher_model","teacher");
		$this->load->model("menu_model","menu");
		$data["kStudent"] = $kStudent;
		$report =  $this->student->get($kStudent,"stuFirst,stuLast,stuNickname,teachFirst as advisorFirst,teachLast as advisorLast,teacher.kTeach as kAdvisor",TRUE);
		$data["student"] = format_name($report->stuFirst,$report->stuLast,$report->stuNickname);
		$data["advisor"] = format_name($report->advisorFirst,$report->advisorLast);
		$data["report"] = $report;
		$data["methods"] = array("In Person","Over the Phone","Via Email");
		$data["kTeach"] = $this->session->userdata("userID");
		$data["categories"] = get_keyed_pairs($this->menu->get_pairs("report_category"),array("value","label"));
		$data["methods"] = $this->menu->get_pairs("report_contact_method");
		$data["action"] = "insert";
		$data["title"] = "Adding an 'Orange Slip' for " . $data["student"];
		$data["target"] = "report/edit";
		$this->load->view("page/index",$data);

	}


	function insert()
	{
		//@TODO email advisor and student(?)
		$kReport = $this->report->insert();
		redirect("report/view/$kReport");
	}

	function view(){
		$report = $this->report->get($this->uri->segment(3));
		$data["report"] = $report;
		$data["student"] = format_name($report->stuFirst,$report->stuLast, $report->stuNickname);
		$data["advisor"] = format_name($report->advisorFirst,$report->advisorLast);
		$data["teacher"] = format_name($report->teachFirst, $report->teachLast);
		$data["title"] = "Viewing 'Orange Slip' Report for " . $data["student"];
		$data["target"] = "report/view";
		$this->load->view("page/index",$data);
	}

	function edit()
	{
		$kReport = $this->uri->segment(3);
		$this->load->model("menu_model","menu");
		$report =  $this->report->get($kReport);
		$data["student"] = format_name($report->stuFirst,$report->stuLast,$report->stuNickname);
		$data["advisor"] = format_name($report->advisorFirst,$report->advisorLast);
		$data["kStudent"] = $report->kStudent;
		$data["report"] = $report;
		$data["methods"] = array("In Person","Over the Phone","Via Email");
		$data["kTeach"] = $report->kTeach;
		$data["categories"] = get_keyed_pairs($this->menu->get_pairs("report_category"),array("value","label"));
		$data["methods"] = $this->menu->get_pairs("report_contact_method");
		$data["action"] = "update";
		$data["title"] = "Editing an 'Orange Slip' for " . $data["student"];
		$data["target"] = "report/edit";
		$this->load->view("page/index",$data);
	}

	function update()
	{
		$kReport = $this->input->post("kReport");
		$this->report->update($kReport);
		redirect("report/view/$kReport");
	}

	function student_list()
	{
		$kStudent = $this->uri->segment(3);
		$reports = $this->report->get_for_student($kStudent);
		$student = format_name($reports[0]->stuFirst,$reports[0]->stuLast,$reports[0]->stuNickname);
		$data["kStudent"] = $kStudent;
		$data["reports"] = $reports;
		$data["target"] = "report/student_list";
		$data["title"] = "Listing Orange Slips for $student";
		$this->load->view("page/index",$data);
	}

	function advisor_list()
	{
		$data["title"] = "Nothing to See Here";
		$data["target"] = "report/teacher_list";
		$this->load->view("page/index",$data);
	}

	function teacher_list()
	{
		$data["title"] = "Nothing to See Here";
		$data["target"] = "report/teacher_list";
		$this->load->view("page/index",$data);
	}
}