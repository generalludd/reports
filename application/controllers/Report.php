<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author chrisdart
 * control generation of student behavioral reports
 * reports are known at FSM as Orange Slips, but, in preparation for generic use,
 * ths title could be anything.
 * The term "Orange Slip" is defined in config/constants.php
*/
class Report extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model("student_report_model","report");
	}

	/*
	 * create an interface for adding a new student report
	*/
	function create()
	{

		$kStudent = $this->uri->segment(3);
		$this->load->model("student_model","student");
		$this->load->model("teacher_model","teacher");
		$this->load->model("menu_model","menu");
		$data["ranks"] = get_keyed_pairs($this->menu->get_pairs("report_rank"),array("value","label"));
		$data["kTeach"] = $this->session->userdata("userID");
		//if the individual is not a teacher, show a dropdown list of teachers on whose behalf.
		//Include the author as an option
		$data["is_teacher"] = TRUE;
		if($this->session->userdata('dbRole') != 2){
			$this->load->model("teacher_model");
			$teachers = $this->teacher_model->get_teacher_pairs();
			$data['teachers'] = get_keyed_pairs($teachers, array('kTeach', 'teacher'),NULL,NULL,array('value'=>"Myself",'name'=>$data["kTeach"]));
			$data["is_teacher"] = FALSE;
		}
		$data["kStudent"] = $kStudent;
		$report =  $this->student->get($kStudent,"stuFirst,stuLast,stuNickname,teacher.teachFirst as advisorFirst,teacher.teachLast as advisorLast,teacher.kTeach as kAdvisor",TRUE);
		$data["student"] = format_name($report->stuFirst,$report->stuLast,$report->stuNickname);
		$data["advisor"] = format_name($report->advisorFirst,$report->advisorLast);
		$data["report"] = $report;
		$data["methods"] = array("In Person","Over the Phone","Via Email");

		$data["statuses"] = get_keyed_pairs($this->menu->get_pairs("report_status"),array("value","label"));
		$data["categories"] = get_keyed_pairs($this->menu->get_pairs("report_category"),array("value","label"));
		$data["methods"] = $this->menu->get_pairs("report_contact_method");
		$data["action"] = "insert";
		$data["title"] = sprintf("Adding an %s for %s", STUDENT_REPORT, $data["student"]);
		$data["target"] = "report/edit";
		$this->_view($data);
		
	}

	/**
	 * insert a new student report
	 */
	function insert()
	{
		//@TODO email advisor and student(?)
		$kReport = $this->report->insert();
		if($this->input->post("email_advisor")){
			$this->notify($kReport);
		}
		redirect("report/view/$kReport");
	}

	/**
	 * show a report
	 */
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

	/**
	 * show an edit screen for a given report
	 */
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
		$data["is_teacher"] = TRUE;
		if($this->session->userdata('dbRole') != 2){
			$this->load->model("teacher_model");
			$teachers = $this->teacher_model->get_teacher_pairs();
			$data['teachers'] = get_keyed_pairs($teachers, array('kTeach', 'teacher'),NULL,NULL,array('value'=>"Myself",'name'=>$data["kTeach"]));
			$data["is_teacher"] = FALSE;
		}
		$data["ranks"] = get_keyed_pairs($this->menu->get_pairs("report_rank"),array("value","label"));
		$data["categories"] = get_keyed_pairs($this->menu->get_pairs("report_category"),array("value","label"));
		$data["methods"] = $this->menu->get_pairs("report_contact_method");
		$data["action"] = "update";
		$data["title"] = sprintf("Editing an %s for %s", STUDENT_REPORT, $data["student"]);
		$data["target"] = "report/edit";
		$this->_view($data);
	}

	/**
	 * update a student report
	 */
	function update()
	{

		$kReport = $this->input->post("kReport");
		$this->report->update($kReport);
		if($this->input->cookie("isAdvisor") == 1){
			$this->load->model("student_report_model","report");
			$userID = $this->session->userdata("userID");
			$unread_reports = $this->report->get_count($userID);
			bake_cookie("unread_reports", $unread_reports);
		}

		redirect("report/view/$kReport");
	}

	/**
	 * update a single value for a give kReport
	 */
	function update_value()
	{
		$kReport = $this->input->get_post("kReport");
		$target_field = $this->input->get_post("target_field");
		$target_value = $this->input->get_post("target_value");
		$this->report->update_value($kReport,$target_field,$target_value);
		echo $this->report->get_value($kReport,$target_field);
	}

	/**
	 * delete a student report
	 */
	function delete()
	{
		$kReport = $this->input->post("kReport");
		$kStudent = $this->input->post("kStudent");
		$this->report->delete($kReport);
		redirect("report/get_list/student/$kStudent");
	}

	/**
	 * generate a search result
	 */
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
		$this->load->model("menu_model","menu");

		$data["categories"] = get_keyed_pairs($this->menu->get_pairs("report_category"),array("value","label"),TRUE);

		$data["title"] = sprintf("Searching for %ss submitted %s %s",STUDENT_REPORT,$preposition,$name);
		$data["target"] = "report/search";
		$this->_view($data);
	}

	/**
	 * get a list of reports based on certain submitted criteria
	 */
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
					$person = $this->student->get($key,"stuFirst,stuLast,stuNickname,student.*,(`baseGrade`+" . get_current_year() . "-`baseYear`) as stuGrade");
					$title = sprintf("for %s" , format_name($person->stuFirst,$person->stuLast, $person->stuNickname));
					$data["kStudent"] = $key;

					//$data["target"] = "report/" . $type . "_list";

					break;
				case "teacher":
					$this->load->model("teacher_model","teacher");
					$person = $this->teacher->get($key,"teachFirst,teachLast,dbRole,isAdvisor,gradeStart,gradeEnd,kTeach");
					$title = sprintf("by %s %s", $person->teachFirst,$person->teachLast);

					break;
				case "advisor":
					$this->load->model("teacher_model","teacher");
					$person = $this->teacher->get($key,"teachFirst as advisorFirst,teachLast as advisorLast,dbRole,isAdvisor,gradeStart,gradeEnd,kTeach");
					$title = sprintf("to %s %s",$person->advisorFirst,$person->advisorLast);
					break;
			}
			$options = array();
			if($this->input->get("date_start") && $this->input->get("date_end")){
				$date_start = $this->input->get("date_start");
				$date_end =  $this->input->get("date_end");
				$options["date_range"]["date_start"] = $date_start;
				$options["date_range"]["date_end"] = $date_end;

				//$this->session->set_userdata("date_start",$date_start);
				//$this->session->set_userdata("date_end",$date_end);
				bake_cookie("date_start", $date_start);
				bake_cookie("date_end", $date_end);
			}

			if($this->input->get("category")){
				$options["category"] = $this->input->get("category");
			}

			$data["options"] = $options;
			$data["target"] = "report/list";
			$data["person"] = $person;

			$data["reports"] = $this->report->get_list($type,$key,$options);
			$data["type"] = $type;
			$data["title"] = sprintf("%ss Submitted %s", $data["student_report"], $title);
			$this->load->view("page/index",$data);


		}
	}

	/**
	 * @param unknown $kReport
	 * send an email notification about a student report to the student advisor
	 */
	function notify($kReport)
	{
		$report = $this->report->get($kReport);
		//$config = $this->initialize();
		//$this->email->initialize($config);
		$student = format_name($report->stuFirst, $report->stuLast, $report->stuNickname);
		$subject = sprintf("%s submission from %s %s for %s",STUDENT_REPORT,$report->teachFirst, $report->teachLast,$student);
		$body[] = "Student: " . $student;
		$body[] = "Category: " . $report->category;
		$body[] = "Date: " . format_date($report->report_date);
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
		//the author was reporting on behalf of a teacher, send the author a copy too.
		if($report->recModifier != $report->kTeach){
			$cc_list[] = $report->authorEmail;
		}
		if($this->input->post("email_student")){
			$cc_list[] = $this->email->cc($report->stuEmail);
		}
		$cc = implode(",",$cc_list);
		$this->email->cc($cc);
		$message = implode("\n", $body);

		$this->email->subject($subject);
		$this->email->message($message);
		$this->email->send();
		if($this->session->userdata("userID") == ROOT_USER){
			$this->email->print_debugger();
		}
		$this->session->set_userdata("notice",sprintf("The %s has been sent to %s at %s",STUDENT_REPORT,$report->advisorFirst,$report->advisorEmail));
	}

}