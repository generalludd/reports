<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Subject_sort_model extends CI_Model
{
	var $kStudent;
	var $narrTerm;
	var $narrYear;
	var $reportSort;
	var $context;
	var $recModifier = "";
	var $recModified = NULL;

	function __construct()
	{
		parent::__construct();
	}

	
	function set_sort($kStudent, $narrTerm, $narrYear, $reportSort,$context)
	{
		$this->kStudent = $kStudent;
		$this->narrTerm = $narrTerm;
		$this->narrYear = $narrYear;
		$this->reportSort = $reportSort;
		$this->context = $context;
		$this->recModifier = $this->session->userdata('userID');
		$this->recModified = mysql_timestamp();
		
		if(!$this->has_sort($kStudent, $narrTerm, $narrYear,$context)){

			$this->db->insert('subject_sort', $this);

		}else{
			$this->db->where('kStudent', $kStudent);
			$this->db->where('narrTerm', $narrTerm);
			$this->db->where('narrYear', $narrYear);
			$this->db->where('context',$context);
			$this->db->update('subject_sort', $this);
		}
	}
	
	

	function has_sort($kStudent, $narrTerm, $narrYear,$context = "grades")
	{

		$this->db->where('kStudent', $kStudent);
		$this->db->where('narrTerm', $narrTerm);
		$this->db->where('narrYear', $narrYear);
		$this->db->where('context',$context);
		$this->db->from('subject_sort');
		$result = $this->db->get()->num_rows();
		return $result;
	}



	function get_sort($kStudent, $narrTerm, $narrYear,$context)
	{
		$this->load->model('narrative_model');
		$has_sort = $this->has_sort($kStudent, $narrTerm, $narrYear,$context);
		if(!$has_sort){
			$output = $this->narrative_model->get_current_student_subjects($kStudent, $narrTerm, $narrYear);
		}else{
			$this->db->where('kStudent', $kStudent);
			$this->db->where('subject_sort.narrTerm', $narrTerm);
			$this->db->where('subject_sort.narrYear', $narrYear);
			$this->db->where("subject_sort.context",$context);
			$this->db->from('subject_sort');
			$result = $this->db->get()->row();
			$output = $result->reportSort;
		}

		$output = str_replace("\r",",",$output);
		return $output;
	}






}