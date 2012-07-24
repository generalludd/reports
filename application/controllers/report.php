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
		$data["title"] = sprintf("Adding an %s for %s", STUDENT_REPORT, $data["student"]);
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
		$data["title"] = sprintf("Viewing %s for %s", STUDENT_REPORT, $data["student"]);
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
		$data["title"] = sprintf("Editing an %s for %s", STUDENT_REPORT, $data["student"]);
		$data["target"] = "report/edit";
		$this->load->view("page/index",$data);
	}

	function update()
	{
		$kReport = $this->input->post("kReport");
		$this->report->update($kReport);
		redirect("report/view/$kReport");
	}

	function get_list()
	{
		$type = $this->uri->segment(3);
		$key = $this->uri->segment(4);
		$data["student_report"] = STUDENT_REPORT;
		if($type && $key){
			switch($type){
				case "student":
					$this->load->model("student_model","student");
					$person = $this->student->get($key,"stuFirst,stuLast,stuNickname");
					$title = sprintf("for %s" , format_name($person->stuFirst,$person->stuLast, $person->stuNickname));
					$data["kStudent"] = $key;

					break;
				case "teacher":
					$this->load->model("teacher_model","teacher");
					$person = $this->teacher->get($key,"teachFirst,teachLast");
					$title = sprintf("by %s %s", $person->teachFirst,$person->teachLast);
					break;
				case "advisor":
					$this->load->model("teacher_model","teacher");
					$person = $this->teacher->get($key,"teachFirst as advisorFirst,teachLast as advisorLast");
					$title = sprintf("to %s %s",$person->advisorFirst,$person->advisorLast);
					break;
			}
			$options = array();
			if($this->input->get("date_start") && $this->input->get("date_end")){
				$options["date_range"]["date_start"] = $this->input->get("date_start");
				$options["date_range"]["date_end"] = $this->input->get("date_end");
				$data["options"] = $options;
				
			}
			$data["person"] = $person;
			$data["reports"] = $this->report->get_list($type,$key,$options);
			$data["type"] = $type;
			$data["title"] = sprintf("%ss Submitted %s", $data["student_report"], $title);
			$data["target"] = "report/" . $type . "_list";
			$this->load->view("page/index",$data);
		}
	}

}