<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Grade_preference extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model("grade_preference_model", "preference");
	}

	function edit()
	{
		$id = $this->input->get("id");
		$data["preference"] = $this->preference->get($id);
		$this->load->view("grade_preference/edit",  $data);
	}
	
	function show()
	{
		$kStudent = $this->input->get("kStudent");
		$data["kStudent"] = $kStudent;
		$options = array();
		$options["school_year"] = NULL;
		$options["subject"] = NULL;
		if($this->input->get("school_year")){
			$options["school_year"] = $this->input->get("school_year");
		}
		if($this->input->get("subject")){
			$options["subject"] = $this->input->get("school_year");
		}
		$data["preferences"] = $this->preference->get_all($kStudent, $options);
		$data["target"] = "grade_preference/list";
		$data["title"] = "grade preferences";
		$this->load->view("page/index",$data);
	}


	function insert()
	{
		$this->update();
	}

	function update()
	{
		$data["kStudent"] = $this->input->get("kStudent");
		$data["subject"] = $this->input->get("subject");
		$data["school_year"] = $this->input->get("school_year");
		$data["pass_fail"] = $this->input->get("pass_fail");
		$this->preference->update($data);
	}
}