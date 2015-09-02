<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Attendance_model extends MY_Model
	{
		var $kAttendance = NULL;
		var $kStudent = NULL;
		var $attendDate = NULL;
		var $attendType = NULL;
		var $attendSubtype = NULL;
		var $attendLength = NULL;
		var $attendNote = NULL;
		var $recModifier = NULL;
		var $recModified = NULL;

		function __construct ()
		{
			parent::__construct ();
			$this->load->helper ( "general" );
		}

		function prepare_variables ()
		{
			$variables = array (
					"kAttendance",
					"kStudent",
					"attendDate",
					"attendType",
					"attendSubtype",
					"attendLength",
					"attendNote" 
			);
			
			for($i = 0; $i < count ( $variables ); $i ++) {
				$myVariable = $variables [$i];
				if ($this->input->post ( $myVariable )) {
					if ($myVariable == "attendDate") {
						$this->$myVariable = format_date ( $this->input->post ( $myVariable ), "mysql" );
					}
					else {
						$this->$myVariable = $this->input->post ( $myVariable );
					}
				}
			}
			
			$this->recModified = mysql_timestamp ();
			$this->recModifier = $this->session->userdata ( 'userID' );
		}

		function get ( $kAttendance )
		{
			$this->db->where ( "kAttendance", $kAttendance );
			$this->db->from ( "student_attendance" );
			$result = $this->db->get ()->row ();
			return $result;
		}

		function insert ()
		{
			$this->prepare_variables ();
			$options ["startDate"] = $this->attendDate;
			$options ["endDate"] = $this->attendDate;
			$options ["kStudent"] = $this->kStudent;
			if (! $this->search ( $options )) {
				$this->db->insert ( "student_attendance", $this );
				return $this->db->insert_id ();
			}
			else {
				return false;
			}
		}

		function update ( $kAttendance )
		{
			if ($kAttendance) {
				$this->prepare_variables ();
				$this->kAttendance = $kAttendance;
				$this->db->where ( 'kAttendance', $kAttendance );
				$this->db->update ( 'student_attendance', $this );
			}
		}

		function delete ( $kAttendance )
		{
			if ($kAttendance) {
				$delete_array ["kAttendance"] = $kAttendance;
				$this->db->delete ( "student_attendance", $delete_array );
			}
		}

		function get_menu ( $category, $options = null )
		{
			$this->db->where ( "category", $category );
			$this->db->order ( "label", "ASC" );
			$this->db->from ( "menu" );
			$result = $this->db->get ();
			return $result;
		}

		function get_list ( $kStudent = NULL )
		{
			if ($kStudent) {
				$this->db->where ( "student_attendance.kStudent", $kStudent );
				$current_year = get_current_year ();
				$this->db->where ( "attendDate > '$current_year-08-01'" );
			}
			$this->db->where ( "`student_attendance`.`kStudent`", "`student`.`kStudent`", FALSE );
			$this->db->order_by ( "student_attendance.kStudent", "ASC" );
			$this->db->order_by ( "attendDate", "DESC" );
			
			$this->db->from ( "student_attendance" );
			$this->db->from ( "student" );
			$result = $this->db->get ()->result ();
			return $result;
		}

		function search ( $options )
		{
			if (key_exists ( "startDate", $options ) && key_exists ( "endDate", $options )) {
				$startDate = $options ["startDate"];
				$endDate = $options ["endDate"];
				$this->db->where ( "attendDate BETWEEN '$startDate' AND '$endDate'" );
				if (key_exists ( "attendType", $options )) {
					if (! empty ( $options ["attendType"] )) {
						$this->db->where ( "attendType", $options ["attendType"] );
					}
				}
				
				if (key_exists ( "attendSubtype", $options )) {
					if (! empty ( $options ["attendSubtype"] )) {
						$this->db->where ( "attendSubtype", $options ["attendSubtype"] );
					}
				}
				if ($options ["kStudent"] > 0) {
					$this->db->where ( "student_attendance.kStudent", $options ["kStudent"] );
				}
				$this->db->where ( "`student_attendance`.`kStudent`", "`student`.`kStudent`", FALSE );
				$this->db->from ( "student_attendance" );
				$this->db->from ( "student" );
				$this->db->order_by ( "student.stuLast" );
				$this->db->order_by ( "student.stuFirst" );
				$this->db->order_by ( "attendDate", "DESC" );
				$result = $this->db->get ()->result ();
				return $result;
			}
			else {
				return FALSE;
			}
		}

		function get_by_date ( $date, $kStudent )
		{
			$this->db->where("attendDate",format_date($date,"mysql"));
			$this->db->where("kStudent",$kStudent);
			$this->db->from("student_attendance");
			$result = $this->db->get ()->row ();
			return $result;
		}

		function attendance_count_for_student ( $type, $values )
		{
			$term = $values ['term'];
			$kStudent = $values ['kStudent'];
			$year = $values ['year'];
			
			$cutOff = $this->config->item ( "mid-month" ) . "-" . $this->config->item ( "mid_day" );
			// @TODO This cut-off bit of code can probably be isolated as a separate function since it is used in at least two other places.
			if ($term == "Mid-Year") {
				$nextYear = $year + 1;
				$between = "'$year-08-15' AND '$nextYear-$cutOff'";
			}
			else {
				// $year=$year+1;
				// $between="'$year-$cutOff' AND '$year-07-01'";
				$nextYear = $year + 1;
				$between = "'$year-08-15' AND '" . $nextYear . "-07-01'";
			}
			$this->db->where ( "kStudent", $kStudent );
			$this->db->where ( "attendType", $type );
			$this->db->where ( "attendDate BETWEEN $between", FALSE );
			$this->db->order_by ( "attendDate", "ASC" );
			$this->db->from ( "student_attendance" );
			$result = $this->db->get ()->num_rows ();
			return $result;
		}

		function summarize ( $kStudent, $term, $year )
		{
			$this->load->model ( "config_model" );
			$year_start = $this->config_model->get ( "year-start", "config_value" )->config_value;
			$term_end = $this->config_model->get ( "year-end", "config_value" )->config_value;
			$nextYear = $year + 1;
			
			if ($term == "Mid-Year") {
				$term_end = $this->config_model->get ( "mid-year", "config_value" )->config_value;
			}
			
			$between = "'$year" . "-" . $year_start . "' AND '" . $nextYear . "-$term_end'";
			
			$this->db->where ( "kStudent", $kStudent );
			$this->db->where ( "attendDate BETWEEN $between" );
			$this->db->group_by ( "attendType,attendSubtype,attendLength" );
			$this->db->select ( "count(attendType) as typeCount", FALSE );
			$this->db->select ( "attendType, attendSubtype, attendLength" );
			$this->db->from ( "student_attendance" );
			
			$result = $this->db->get ()->result ();
			$absent = 0;
			$tardy = 0;
			if ($result != false) {
				foreach ( $result as $row ) {
					if ($row->attendType == "Absent" && $row->attendLength == "") {
						$absent += $row->typeCount;
					}
					elseif ($row->attendType == "Absent" && $row->attendLength == "Half-Day") {
						$absent += $row->typeCount / 2;
					}
					elseif ($row->attendType == "Tardy") {
						$tardy += $row->typeCount;
					}
				}
			}
			$summary ["absent"] = $absent;
			$summary ["tardy"] = $tardy;
			return $summary;
		}
	}