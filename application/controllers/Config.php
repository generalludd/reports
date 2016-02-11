<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Config extends MY_Controller {

	function __construct()
	{
		parent::__construct ();
		$this->load->model ( "config_model" );
		$this->load->model ( "global_subject_model", "global_subject" );
	}

	function index()
	{
		if ($this->session->userdata ( "dbRole" ) == 1) {
			$data["items"] = $this->config_model->get_all();
			$data["title"] = "Editing Site-Wide Configuration Variables";
			$data["target"] = "config/list";
			$this->load->view("page/index",$data);
		}else{
			$this->session->set_flashdata("message","You are not authorized to configure the global settings.");
			redirect();
		}
	}
	
	function create(){
		$data["title"]= "Add a Global Configuration Variable";
		$data["target"] = "config/create";
		$data["action"] = "insert";
		$data["config"] = NULL;
		if($this->input->get("ajax")==1){
			$this->load->view($data["target"],$data);
		}else{
			$this->load->view("page/index",$data);
		}
	}
	
	function edit($kConfig){
		$data["config"] = $this->config_model->get($kConfig);
		$data["title"] = "Edit a Global Configuration Variable";
		$data["target"] = "config/edit";
		$data["action"] = "update";
		if($this->input->get("ajax")==1){
			$this->load->view($data["target"],$data);
		}else{
			$this->load->view("page/index",$data);
		}
	}
 
	function update(){
		$kConfig = $this->input->post("kConfig");
		$this->config_model->update($kConfig);
		redirect("config");
	}
	
	function restore()
	{
		$this->config_model->restore ();
	}

	function show_subject_sort()
	{
		$data ['subjects'] = $this->global_subject->get_all ();
		$data ['target'] = "admin/subject_sort_list";
		$data ['title'] = "Global Subject Sorting";
		$this->load->view ( "page/index", $data );
	}

	function edit_sort()
	{
		$grade_start = $this->input->get ( "grade_start" );
		$grade_end = $this->input->get ( "grade_end" );
		$context = $this->input->get ( "context" );
		$data ["action"] = "update";
		$data ["title"] = "Subject Sorting";
		$data ["target"] = "admin/subject_sort";
		$data ["sort_order"] = $this->global_subject->get ( $grade_start, $grade_end, $context );
		if ($this->input->get ( "ajax" ) == 1) {
			$this->load->view ( $data ['target'], $data );
		} else {
			$this->load->view ( "page/index", $data );
		}
	}

	function update_subject_sort()
	{
		$this->global_subject->update ();
		redirect ( "config/show_subject_sort" );
	}
}