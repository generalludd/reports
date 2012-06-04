<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Suggestion extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('suggestion_model');
		$this->load->model('narrative_model');
	}



	function create()
	{
		$this->load->model('support_model');
		$this->load->model('student_model');
		$this->load->model('teacher_model');
		$kNarrative = $this->uri->segment(3);
		$narrative = $this->narrative_model->get($kNarrative);
		$data["narrative"] = $narrative;
		$teacher = $this->teacher_model->get($narrative->kTeach);
		$data['teacher'] = $teacher;
		$student = $this->student_model->get($narrative->kStudent);
		$data['student'] = $student;
		$data['action'] = "insert";

		$data["hasNeeds"] = $this->support_model->get_current($narrative->kStudent, "kNeed");
		$data['target'] = 'suggestion/edit';
		$data['title'] = "Add Suggestions for Narrative by $teacher->teachFirst $teacher->teachLast for $narrative->narrSubject";
		$this->load->view('page/index', $data);
	}


	function insert()
	{
		$result = $this->suggestion_model->insert();
		if($this->input->post('ajax')){
			echo implode("|",$result);
		}else{
			redirect('narrative/view/'. $kNarrative);
		}
	}


	function update()
	{

		$kNarrative = $this->input->post('kNarrative');
		$result = $this->suggestion_model->update($kNarrative);
		if($this->input->post('ajax')){
			echo implode("|", $result);
		}else{
			redirect('narrative/view/'. $kNarrative);
		}
	}

	function delete()
	{
		if($this->input->post('kNarrative') && $this->input->post('kStudent')){
			$kNarrative = $this->input->post('kNarrative');
			$kStudent = $this->input->post('kStudent');
			$this->suggestion_model->delete($kNarrative);
			echo "The narrative $kNarrative has been successfully backed up and ";
			echo "removed from the list of active narratives";
		}
	}

	function view()
	{
		$this->load->model('student_model');
		$this->load->model('teacher_model');
		$kNarrative = $this->uri->segment(3);
		$suggestion = $this->suggestion_model->get($kNarrative);
		if($suggestion){
			$kStudent = $suggestion->kStudent;
			$kEditor =
			$data['suggestion'] = $suggestion;
			$student = $this->student_model->get($kStudent);
			$editor = $this->teacher_model->get($suggestion->recModifier);
			$data["editor"] = format_name($editor->teachFirst, $editor->teachLast);
			$teacher = $this->teacher_model->get($suggestion->kTeach);
			$teacher_name = format_name($editor->teachFirst, $editor->teachLast);
			$studentName = format_name($student->stuFirst, $student->stuLast, $student->stuNickname);
			$data['target'] = "suggestion/view";
			$data['title'] = "Suggestions for Narrative by $teacher->teachFirst $teacher->teachLast for $suggestion->narrSubject";
			$data['student'] = $student;
			$data['studentName'] = $studentName;
			$this->load->view($data["target"], $data);
		}
	}


	function edit()
	{
		$this->load->model("student_model");
		$this->load->model("teacher_model");
		$this->load->model("support_model");
		$kNarrative = $this->uri->segment(3);
		$suggestion = $this->suggestion_model->get($kNarrative);
		if($suggestion){
			$kStudent = $suggestion->kStudent;
			$kTeach = $suggestion->kTeach;
			$data["narrative"] = $suggestion;
			$student = $this->student_model->get($kStudent);
			$data["student"] = $student;
			$student = $this->student_model->get($kStudent);
			$teacher = $this->teacher_model->get($kTeach);
			$studentName = format_name($student->stuFirst, $student->stuLast, $student->stuNickname);
			$data["hasNeeds"] = $this->support_model->get_current($kStudent, "kNeed");
			$data["target"] = "suggestion/edit";
			$data["action"] = "update";
			$data["title"] = "Editing Suggestions for Narrative by $teacher->teachFirst $teacher->teachLast for $suggestion->narrSubject";
			$data["student"] = $student;
			$data["studentName"] = $studentName;
			$this->load->view("page/index", $data);
		}else{
			redirect("suggestion/create/$kNarrative");
		}
	}



}
