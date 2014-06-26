<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Config extends MY_Controller
{

	function __construct()
	{

		parent::__construct ();
		$this->load->model ( "config_model" );
		$this->load->model ( "global_subject_model", "global_subject" );
	
	}

	function index()
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

	function update()
	{

		$this->global_subject->update ();
		redirect ( "config/show_subject_sort" );
	
	}

}