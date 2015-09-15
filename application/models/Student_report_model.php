<?php

defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Student_report_model extends CI_Model {
	var $kStudent;
	var $kTeach;
	var $kAdvisor;
	var $is_read;
	var $rank;
	var $category;
	var $assignment;
	var $assignment_status;
	var $report_date;
	var $comment;
	var $parent_contact;
	var $contact_date;
	var $contact_method;
	var $recModifier;
	var $recModified;

	function prepare_variables()
	{
		$variables = array (
				"kStudent",
				"kTeach",
				"kAdvisor",
				"is_read",
				"rank",
				"category",
				"assignment",
				"assignment_status",
				"report_date",
				"comment",
				"parent_contact",
				"contact_date",
				"contact_method" 
		);
		for($i = 0; $i < count ( $variables ); $i ++) {
			$myVariable = $variables [$i];
			if ($this->input->post ( $myVariable )) {
				$post = $this->input->post ( $myVariable );
				$this->$myVariable = $post;
			}
		}
		
		$this->recModified = mysql_timestamp ();
		$this->recModifier = $this->session->userdata ( 'userID' );
	}

	function insert()
	{
		$this->prepare_variables ();
		$this->db->insert ( "student_report", $this );
		$kReport = $this->db->insert_id ();
		return $kReport;
	}

	function update($kReport)
	{
		$this->db->where ( "kReport", $kReport );
		$this->prepare_variables ();
		$this->db->update ( "student_report", $this );
		
		// set the read report count session key to update user interface indicators of unread orange slips
		if ($this->session->userdata ( "userID" ) == $this->kAdvisor) {
			$report_count = $this->get_count ( $this->kAdvisor );
			$data ["report_count"] = $report_count;
			bake_cookie ( "report_count", $report_count );
		}
	}

	function update_value($kReport, $target_field, $target_value)
	{
		$this->db->where ( "kReport", $kReport );
		$data ['recModified'] = mysql_timestamp ();
		$data ['recModifier'] = $this->session->userdata ( 'userID' );
		$data [$target_field] = $target_value;
		$this->db->update ( "student_report", $data );
		// @TODO fix the display of unread count based on this information.
		/*
		 * $kAdvisor = $this->get_value($kReport,"kAdvisor");
		 * if($this->session->userdata("userID") == $kAdvisor){
		 * $report_count = $this->get_count($kAdvisor);
		 * bake_cookie("report_count",$report_count);
		 * }
		 */
	}

	function get_value($kReport, $target_field)
	{
		$this->db->where ( "kReport", $kReport );
		$this->db->select ( $target_field );
		$this->db->from ( "student_report" );
		$result = $this->db->get ()->row ();
		return $result->$target_field;
	}

	function delete($kReport)
	{
		$array = array (
				"kReport" => $kReport 
		);
		$this->db->delete ( "student_report", $array );
	}

	function get($kReport)
	{
		$this->db->where ( "kReport", $kReport );
		$this->db->from ( "student_report" );
		$this->db->join ( "student", "student.kStudent=student_report.kStudent", "LEFT" );
		$this->db->join ( "teacher", "teacher.kTeach=student_report.kTeach", "LEFT" );
		$this->db->join ( "teacher as advisor", "student_report.kAdvisor = advisor.kTeach", "LEFT" );
		$this->db->join ( "teacher as author", "student_report.recModifier = author.kTeach", "LEFT" );
		$this->db->select ( "student_report.*,student.stuFirst,student.stuLast,student.stuNickname,student.stuEmail,teacher.teachFirst,teacher.teachLast,teacher.email as teachEmail,advisor.teachFirst as advisorFirst,advisor.teachLast as advisorLast, advisor.email as advisorEmail,author.teachFirst as authorFirst,author.teachLast as authorLast,author.email as authorEmail" );
		$output = $this->db->get ()->row ();
		
		return $output;
	}

	function get_count($kTeach)
	{
		$this->db->where ( "kAdvisor", $kTeach );
		$this->db->where ( "(is_read = 0 OR is_read IS NULL)" ); // why is_read != 1 doesn't work I don't know.
		$this->db->from ( "student_report" );
		$this->db->select ( "COUNT(kReport) AS unread_reports" );
		$result = $this->db->get ()->row ();
		return $result->unread_reports;
	}

	function get_list($type, $key, $options = array())
	{
		switch ($type) {
			case "student" :
				$this->db->where ( "student_report.kStudent", $key );
				break;
			case "teacher" :
				$this->db->where ( "student_report.kTeach", $key );
				break;
			case "advisor" :
				$this->db->where ( "student_report.kAdvisor", $key );
				break;
		}
		
		if (array_key_exists ( "date_range", $options )) {
			if (array_key_exists ( "date_start", $options ["date_range"] ) && array_key_exists ( "date_end", $options ["date_range"] )) {
				$date_start = $options ["date_range"] ["date_start"];
				$date_end = $options ["date_range"] ["date_end"];
				$this->db->where ( "report_date BETWEEN '$date_start' AND '$date_end'" );
			}
		}
		if (array_key_exists ( "category", $options )) {
			
			$this->db->where ( "student_report.category", $options ["category"] );
		}
		
		$this->db->join ( "teacher", "teacher.kTeach=student_report.kTeach" );
		$this->db->join ( "teacher as advisor", "student_report.kAdvisor=advisor.kTeach" );
		$this->db->join ( "student", "student.kStudent=student_report.kStudent" );
		$this->db->select ( "student_report.*,student.stuFirst,student.stuLast,student.stuNickname,teacher.teachFirst,teacher.teachLast,advisor.teachFirst as advisorFirst,advisor.teachLast as advisorLast,advisor.dbRole,menu.label" );
		$this->db->join ( "menu", "student_report.rank=menu.value AND menu.category='report_rank'", "LEFT" );
		$this->db->from ( "student_report" );
		$this->db->order_by ( "student.stuLast", "ASC" );
		$this->db->order_by ( "student.stuFirst", "ASC" );
		$this->db->order_by ( "report_date", "DESC" );
		$this->db->order_by ( "teacher.teachLast", "ASC" );
		$this->db->order_by ( "teacher.teachFirst", "ASC" );
		$result = $this->db->get ()->result ();
		return $result;
	}
}