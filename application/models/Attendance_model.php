<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Attendance_model extends MY_Model {
	var $kAttendance;
	var $kStudent;
	var $attendDate;
	var $attendType;
	var $attendSubtype;
	var $attendLength;
	var $attendNote;
	var $attendOverride;
	var $recModifier;
	var $recModified;

	function __construct()
	{
		parent::__construct ();
		$this->load->helper ( "general" );
	}

	function prepare_variables()
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
				$this->$myVariable = $this->input->post ( $myVariable );
			}
		}
		
		$this->recModified = mysql_timestamp ();
		$this->recModifier = $this->session->userdata ( 'userID' );
	}

	function get($kAttendance)
	{
		$this->db->where ( "kAttendance", $kAttendance );
		$this->db->from ( "student_attendance" );
		$result = $this->db->get ()->row ();
		return $result;
	}

	function get_for_teacher($date, $kTeach)
	{
		$this->db->where ( "attendDate", $date );
		$this->db->where ( "student_attendance.recModifier", $kTeach );
		$this->db->join ( "student", "student_attendance.kStudent=student.kStudent" );
		$this->db->from ( "student_attendance" );
		$this->db->select ( "student_attendance.*" );
		$this->db->select ( "student.stuFirst, student.stuLast, student.stuNickname" );
		$result = $this->db->get ()->result ();
		return $result;
	}

	function insert()
	{
		$this->prepare_variables ();
		$options ["startDate"] = $this->attendDate;
		$options ["endDate"] = $this->attendDate;
		$options ["kStudent"] = $this->kStudent;
		if (! $this->search ( $options )) {
			$this->db->insert ( "student_attendance", $this );
			return $this->db->insert_id ();
		} else {
			return false;
		}
	}

	function mark($date, $kStudent, $type)
	{
		$this->attendDate = $date;
		$this->kStudent = $kStudent;
		if (! $this->search ( array (
				"startDate" => $date,
				"endDate" => $date,
				"kStudent" => $kStudent 
		) )) {
			$this->attendType = $type;
			$this->recModifier = $this->session->userdata ( "userID" );
			$this->recModified = mysql_timestamp ();
			$this->db->insert ( "student_attendance", $this );
			return $this->db->insert_id ();
		}
	}

	function revert($kAttendance, $kTeach)
	{
		// only allows the user who created the attendance mark to uncheck it;
		$attendance = $this->get ( $kAttendance );
		if ($attendance->recModifier == $kTeach || $this->session->userdata ( "dbRole" ) == 1) {
			$this->delete ( $kAttendance );
			return $attendance;
		}else{
			$this->db->where("kAttendance",$kAttendance);
			$this->db->update("student_attendance",array("attendOverride"=>1,"recModifier"=>$kTeach));
			return $attendance;
			
		}
	}

	function update($kAttendance)
	{
		if ($kAttendance) {
			$this->prepare_variables ();
			$this->kAttendance = $kAttendance;
			$this->db->where ( 'kAttendance', $kAttendance );
			$this->db->update ( 'student_attendance', $this );
		}
	}

	function delete($kAttendance)
	{
		if ($kAttendance) {
			$delete_array ["kAttendance"] = $kAttendance;
			$this->db->delete ( "student_attendance", $delete_array );
		}
	}

	function get_menu($category, $options = null)
	{
		$this->db->where ( "category", $category );
		$this->db->order ( "label", "ASC" );
		$this->db->from ( "menu" );
		$result = $this->db->get ();
		return $result;
	}

	function get_list($kStudent = NULL)
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

	function search($options)
	{
		if (key_exists ( "startDate", $options ) || key_exists ( "endDate", $options )) {
			$startDate = FALSE;
			$endDate = FALSE;
			if (array_key_exists ( "startDate", $options )) {
				$startDate = $options ["startDate"];
			}
			if (array_key_exists ( "endDate", $options )) {
				$endDate = $options ["endDate"];
			}
			if ($startDate && ! $endDate) {
				$this->db->where ( 'attendDate >=', $startDate );
			} elseif ($endDate && ! $startDate) {
				$this->db->where ( 'attendDate <=', $endDate );
			} elseif ($startDate && $endDate) {
				$this->db->where ( "attendDate BETWEEN '$startDate' AND '$endDate'" );
			}
			
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
			$this->db->select ( "student_attendance.*" );
			$this->db->select ( "student.*" );
			$year = get_current_year ();
			$this->db->select ( "($year - student.baseYear + student.baseGrade) as stuGrade", FALSE );
			$this->db->join ( "teacher", "student.kTeach = teacher.kTeach" );
			$this->db->select ( "teacher.teachFirst, teacher.teachLast, teacher.teachClass" );
			$this->db->order_by ( "stuGrade" );
			$this->db->order_by ( "student.stuGroup" );
			$this->db->order_by ( "student.kTeach" );
			$this->db->order_by ( "student.stuLast" );
			$this->db->order_by ( "student.stuFirst" );
			$this->db->order_by ( "attendDate", "DESC" );
			$result = $this->db->get ()->result ();
			return $result;
		} else {
			return FALSE;
		}
	}

	function get_by_date($date, $kStudent)
	{
		$this->db->where ( "attendDate", $date );
		$this->db->where ( "kStudent", $kStudent );
		$this->db->from ( "student_attendance" );
		$result = $this->db->get ()->row ();
		return $result;
	}

	/* Deprecated */
	function attendance_count_for_student($type, $values)
	{
		$term = $values ['term'];
		$kStudent = $values ['kStudent'];
		$year = $values ['year'];
		
		$cutOff = $this->config->item ( "mid-month" ) . "-" . $this->config->item ( "mid_day" );
		// @TODO This cut-off bit of code can probably be isolated as a separate function since it is used in at least two other places.
		if ($term == "Mid-Year") {
			$nextYear = $year + 1;
			$between = "'$year-08-15' AND '$nextYear-$cutOff'";
		} else {
			// $year=$year+1;
			// $between="'$year-$cutOff' AND '$year-07-01'";
			$nextYear = $year + 1;
			$between = "'$year-08-15' AND '" . $nextYear . "-07-01'";
		}
		$this->db->where ( "kStudent", $kStudent );
		$this->db->where ( "attendType", $type );
		$this->db->where ( "attendDate BETWEEN $between", FALSE );
		$this->db->where_not ( "attendSubtype", "Holiday" );
		$this->db->order_by ( "attendDate", "ASC" );
		$this->db->from ( "student_attendance" );
		
		$result = $this->db->get ()->num_rows ();
		return $result;
	}

	function summarize($kStudent, $term, $year)
	{
		$this->load->model ( "config_model" );
		$year_start = $this->config_model->get_by_key ( "year-start", "config_value" )->config_value;
		$term_end = $this->config_model->get_by_key( "year-end", "config_value" )->config_value;
		$nextYear = $year + 1;
		
		if ($term == "Mid-Year") {
			$term_end = $this->config_model->get_by_key( "mid-year", "config_value" )->config_value;
		}
		
		$between = "'$year" . "-" . $year_start . "' AND '" . $nextYear . "-$term_end'";
		
		$this->db->where ( "kStudent", $kStudent );
		$this->db->where ( "attendDate BETWEEN $between" );
		$this->db->group_by ( "attendType,attendSubtype,attendLength" );
		$this->db->join ( "menu", "student_attendance.attendType = menu.value","LEFT" );
		$this->db->join ( "menu subtype", "student_attendance.attendSubtype = subtype.label","LEFT" );
		
		$this->db->select ( "count(attendType) as typeCount", FALSE );
		$this->db->select ( "attendType, attendSubtype, attendLength" );
		$this->db->from ( "student_attendance" );
		
		$result = $this->db->get ()->result ();
		$absent = 0;
		$tardy = 0;
		if ($result != false) {
			foreach ( $result as $row ) {
				if ($row->attendType == "Absent" && $row->attendLength == "" && $row->attendSubtype !="Holiday") {
					$absent += $row->typeCount;
				} elseif ($row->attendType == "Absent" && $row->attendLength == "Half-Day" && $row->attendSubtype !="Holiday") {
					$absent += $row->typeCount / 2;
				} elseif ($row->attendType == "Tardy" && $row->attendSubtype != "Holiday") {
					$tardy += $row->typeCount;
				}
			}
		}
		$summary ["absent"] = $absent;
		$summary ["tardy"] = $tardy;
		return $summary;
	}

	function check_truancy($kStudent,$subtype = FALSE)
	{
		$start_date = get_term_start();
		$this->db->from ( "student_attendance" );
		$this->db->where ( "student_attendance.kStudent", $kStudent );
		$this->db->where ( "student_attendance.attendDate >=", $start_date );
		$this->db->where_in ( "student_attendance.attendType", array (
				'Absent'
		) );
		if($subtype){
			$this->db->where("student_attendance.attendSubtype",$subtype);
		}
		$this->db->join ( "student", "student.kStudent=student_attendance.kStudent" );
		$this->db->select ( "COUNT(`attendType`) AS total", FALSE );
		$this->db->select ( "student.*" );
		$this->db->order_by ( "student_attendance.attendDate" );
		$result = $this->db->get ()->row ();
		return $result;
	}

	function get_truants($start_date = YEAR_START, $threshold = TRUANCY_THRESHOLD)
	{
		$this->db->from ( "student_attendance" );
		$this->db->select ( "COUNT(attendType) AS total", FALSE );
		$this->db->select ( "student_attendance.kStudent" );
		$this->db->join ( "student", "student.kStudent = student_attendance.kStudent" );
		$this->db->select ( "student.stuFirst, student.stuLast, student.stuNickname" );
		$this->db->where ( "attendDate >=", $start_date );
		$this->db->group_by ( "student_attendance.kStudent" );
		$result = $this->db->get ()->result ();
		$output = array ();
		foreach ( $result as $truancy ) {
			if ($truancy->total > $threshold) {
				$output [] = $truancy;
			}
		}
		return $output;
	}
}