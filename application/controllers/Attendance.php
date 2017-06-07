<?php
class Attendance extends MY_Controller {

	function __construct()
	{
		parent::__construct ();
		$this->load->model ( "Attendance_model", "attendance" );
	}

	function edit($kStudent, $term, $year)
	{
		$data ['attendance'] = $this->attendance->get ( $kStudent, $term, $year );
		$data ['kStudent'] = $kStudent;
		$data ['term'] = $term;
		$data ['year'] = $year;
		$data ['action'] = "update";
		$data ['target'] = "attendance/edit";
		$data ['title'] = "Editing Attendance";
		if ($this->input->get ( "ajax" )) {
			$this->load->view ( $data ['target'], $data );
		} else {
			$this->load->view ( "page/index", $data );
		}
	}

	function update()
	{
		$kStudent = $this->input->post ( "kStudent" );
		$term = $this->input->post ( "term" );
		$year = $this->input->post ( "year" );
		$absent = $this->input->post ( "absent" );
		$tardy = $this->input->post ( "tardy" );
		$this->attendance->update ( $kStudent, $term, $year, $absent, $tardy );
		redirect ( "narrative/student_list/$kStudent" );
	}
}