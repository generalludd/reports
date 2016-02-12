<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// feedback.php Chris Dart Jan 6, 2012 9:34:59 AM chrisdart@cerebratorium.com

class Feedback extends My_Controller {

	function __construct()
	{

		parent::__construct();

	}

	/**
	 * show and populate a dialog for providing feedback about the system
	 */
	function create()
	{
		$path = $this->input->get_post("path");
		$segments = explode("/", $path);
		$data["subject"] = "";
		$data["action"] = "";
		$data["feedback"] = "";
		$data["urgency"] = "";

		$data["subject"] = $path;//implode(",", $segments);
		if(!$data["subject"]){
			$data["subject"] = "General";
		}
		$data["target"] = "feedback/edit";
		$this->load->view($data["target"],$data);

	}


	/**
	 * insert a record of the feedback and email it to the administrator. 
	 */
	function add()
	{
		$this->load->model("teacher_model","teacher");
		$kTeach = $this->session->userdata("userID");
		//$config = $this->initialize();
		//$this->email->initialize($config);

		$teacher = $this->teacher->get($kTeach,"email,teachFirst,teachLast");
		$message = "Database Feedback from $teacher->teachFirst $teacher->teachLast";
		$message .= "\n" . $this->input->get_post("subject");
		$message .= "\n" . $this->input->get_post("feedback");
		$urgency = "";
		if($this->input->get_post("rank")!=""){
			$urgency = ", Urgency: " . $this->input->get_post("rank");
		}
		$subject = $this->input->get_post("subject");
		$subject = "Narrative System Feedback $urgency ";

		$this->email->from($teacher->email);
		$this->email->to("technology@fsmn.org");
		$this->email->cc($teacher->email);


		$this->email->subject($subject);
		$this->email->message($message);
		$this->email->send();
		if($this->session->userdata("userID") == ROOT_USER){
			$this->email->print_debugger();
		}
		echo "<p>Your feedback has been sent.<br/>A copy of your message will appear in your inbox at $teacher->email</p>";

	}
	

}