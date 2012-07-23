<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Narrative_support_model extends CI_Model
{
	var $kStudent = "";
	var $narrTerm = "";
	var $narrYear = "";
	var $reportSort = "";
	var $recModifier = "";
	var $recModified = NULL;

	function __construct()
	{
		parent::__construct();
	}

	
	function set_sort($kStudent, $narrTerm, $narrYear, $reportSort)
	{
		$this->kStudent = $kStudent;
		$this->narrTerm = $narrTerm;
		$this->narrYear = $narrYear;
		$this->reportSort = $reportSort;
		$this->recModifier = $this->session->userdata('userID');
		$this->recModified = mysql_timestamp();
		
		if(!$this->has_sort($kStudent, $narrTerm, $narrYear)){

			$this->db->insert('narrative_sort', $this);

		}else{
			$this->db->where('kStudent', $kStudent);
			$this->db->where('narrTerm', $narrTerm);
			$this->db->where('narrYear', $narrYear);
			$this->db->update('narrative_sort', $this);
		}
	}
	
	

	function has_sort($kStudent, $narrTerm, $narrYear)
	{

		$this->db->where('kStudent', $kStudent);
		$this->db->where('narrTerm', $narrTerm);
		$this->db->where('narrYear', $narrYear);
		$this->db->from('narrative_sort');
		$result = $this->db->get()->num_rows();
		return $result;
	}



	function get_sort($kStudent, $narrTerm = NULL, $narrYear = NULL)
	{
		$this->load->model('narrative_model');
		if(!$narrTerm){
			$narrTerm = get_current_term();
		}

		if(!$narrYear){
			$narrYear = get_current_year();
		}

		$has_sort = $this->has_sort($kStudent, $narrTerm, $narrYear);
		if(!$has_sort){
			$output = $this->narrative_model->get_current_student_subjects($kStudent, $narrTerm, $narrYear);
		}else{
			$this->db->where('kStudent', $kStudent);
			$this->db->where('narrative_sort.narrTerm', $narrTerm);
			$this->db->where('narrative_sort.narrYear', $narrYear);
			$this->db->from('narrative_sort');
			$result = $this->db->get()->row();
			$output = $result->reportSort;
		}

		$output = str_replace("\r",",",$output);
		return $output;
	}






}