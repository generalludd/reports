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
		$data["ranks"] = get_keyed_pairs($this->menu->get_pairs("report_rank"),array("value","label"));
		
		$data["kStudent"] = $kStudent;
		$report =  $this->student->get($kStudent,"stuFirst,stuLast,stuNickname,teachFirst as advisorFirst,teachLast as advisorLast,teacher.kTeach as kAdvisor",TRUE);
		$data["student"] = format_name($report->stuFirst,$report->stuLast,$report->stuNickname);
		$data["advisor"] = format_name($report->advisorFirst,$report->advisorLast);
		$data["report"] = $report;
		$data["methods"] = array("In Person","Over the Phone","Via Email");
		$data["kTeach"] = $this->session->userdata("userID");
		$data["statuses"] = get_keyed_pairs($this->menu->get_pairs("report_status"),array("value","label"));
		$data["categories"] = get_keyed_pairs($this->menu->get_pairs("report_category"),array("value","label"));
		$data["methods"] = $this->menu->get_pairs("report_contact_method");
		$data["action"] = "insert";
		$data["title"] = sprintf("Adding an %s for %s", STUDENT_REPORT, $data["student"]);
		$data["target"] = "report/edit";
		if($this->input->get("ajax")){
			$this->load->view($data["target"], $data);
		}else{
			$this->load->view("page/index",$data);
		}
	}


	function insert()
	{
		//@TODO email advisor and student(?)
		$kReport = $this->report->insert();
		if($this->input->post("email_advisor")){
			$this->notify($kReport);
		}
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
		$data["ranks"] = get_keyed_pairs($this->menu->get_pairs("report_rank"),array("value","label"));
		$data["categories"] = get_keyed_pairs($this->menu->get_pairs("report_category"),array("value","label"));
		$data["methods"] = $this->menu->get_pairs("report_contact_method");
		$data["action"] = "update";
		$data["title"] = sprintf("Editing an %s for %s", STUDENT_REPORT, $data["student"]);
		$data["target"] = "report/edit";
		if($this->input->get("ajax")){
			$this->load->view($data["target"],$data);
		}else{
			$this->load->view("page/index",$data);
		}
	}


	function update()
	{

		$kReport = $this->input->post("kReport");
		$this->report->update($kReport);
		if($this->session->userdata("is_advisor") == 1){
			$this->load->model("student_report_model","report");
			$data["unread_reports"] = $this->report->get_count($this->session->userdata("userID"));
			$this->session->set_userdata($data);
		}
		redirect("report/view/$kReport");
	}


	function delete()
	{
		$kReport = $this->input->post("kReport");
		$kStudent = $this->input->post("kStudent");
		$this->report->delete($kReport);
		redirect("report/get_list/student/$kStudent");
	}


	function search()
	{
		$data["report_key"] = $this->input->get("report_key");
		$data["report_type"] = $this->input->get("report_type");
		if($data["report_type"] == "student"){
			$this->load->model("student_model","student");
			$person = $this->student->get($data["report_key"],"stuFirst,stuLast,stuNickname");
			$name = format_name($person->stuFirst,$person->stuLast, $person->stuNickname);
			$preposition = "for";
		}else{
			$this->load->model("teacher_model","teacher");
			$person = $this->teacher->get($data["report_key"],"teachFirst,teachLast");
			$name = format_name($person->teachFirst,$person->teachLast);
			$preposition = ($data["report_type"]=="teacher"?"by":"to");
		}
		$data["title"] = sprintf("Searching for %ss submitted %s %s",STUDENT_REPORT,$preposition,$name);
		$this->load->view("report/search",$data);
	}


	function get_list()
	{
		$type = $this->uri->segment(3);
		$key = $this->uri->segment(4);
		$data["student_report"] = STUDENT_REPORT;
		
		if($type && $key){
			$data["report_key"] = $key;
			$data["report_type"] = $type;
			switch($type){
				case "student":
					$this->load->model("student_model","student");
					$person = $this->student->get($key,"stuFirst,stuLast,stuNickname");
					$title = sprintf("for %s" , format_name($person->stuFirst,$person->stuLast, $person->stuNickname));
					$data["kStudent"] = $key;
					
					$data["target"] = "report/" . $type . "_list";

					break;
				case "teacher":
					$this->load->model("teacher_model","teacher");
					$person = $this->teacher->get($key,"teachFirst,teachLast");
					$title = sprintf("by %s %s", $person->teachFirst,$person->teachLast);
					$data["target"] = "report/" . $type . "_list";

					break;
				case "advisor":
					$this->load->model("teacher_model","teacher");
					$person = $this->teacher->get($key,"teachFirst as advisorFirst,teachLast as advisorLast");
					$title = sprintf("to %s %s",$person->advisorFirst,$person->advisorLast);
					$data["target"] = "report/teacher_list";

					break;
			}
			$options = array();
			if($this->input->get("date_start") && $this->input->get("date_end")){
				$date_start = $this->input->get("date_start");
				$date_end =  $this->input->get("date_end");
				$options["date_range"]["date_start"] = $date_start;
				$options["date_range"]["date_end"] = $date_end;
				$this->session->set_userdata("date_start",$date_start);
				$this->session->set_userdata("date_end",$date_end);
				$data["options"] = $options;

			}
			$data["person"] = $person;
			$data["reports"] = $this->report->get_list($type,$key,$options);
				
			$data["type"] = $type;
			$data["title"] = sprintf("%ss Submitted %s", $data["student_report"], $title);
			$this->load->view("page/index",$data);
		
				
		}
	}


	function notify($kReport)
	{
		$report = $this->report->get($kReport);
		//$config = $this->initialize();
		//$this->email->initialize($config);
		$student = format_name($report->stuFirst, $report->stuLast, $report->stuNickname);
		$subject = sprintf("%s submission from %s %s for %s",STUDENT_REPORT,$report->teachFirst, $report->teachLast,$student);
		$body[] = "Student: " . $student;
		$body[] = "Category: " . $report->category;
		$body[] = "Date: " . format_date($report->report_date, "standard");
		if(isset($report->assignment)){
			$body[] = "Assignment: $report->assignment";
		}
		if(isset($report->comment)){
			$body[] = "Comments: " . $report->comment;
		}
		$body[] = sprintf("Link to %s: %s",STUDENT_REPORT, site_url("report/view/$kReport"));
		$this->email->from($report->teachEmail);
		$this->email->to($report->advisorEmail);
		$cc_list[] = $report->teachEmail;
		if($this->input->post("email_student")){
			$cc_list[] = $this->email->cc($report->stuEmail);
		}
		$cc = implode(",",$cc_list);
		$this->email->cc($cc);
		$message = implode("\n", $body);

		$this->email->subject($subject);
		$this->email->message($message);
		$this->email->send();
		if($this->session->userdata("userID") == 1000){
			$this->email->print_debugger();
		}
		$this->session->set_userdata("notice",sprintf("The %s has been sent to %s at %s",STUDENT_REPORT,$report->advisorFirst,$report->advisorEmail));
	}

}