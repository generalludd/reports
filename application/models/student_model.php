<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Student_model extends CI_Model
{
	var $kTeach;
	var $stuFirst;
	var $stuLast;
	var $stuNickname;
	var $stuGender;
	var $stuDOB;
	var $stuGroup;
	var $baseGrade = 0;
	var $baseYear;
	var $isEnrolled;
	var $isGraduate;
	var $stuEmail;
	var $stuEmailPermission;
	var $stuEmailPassword;
	var $recModified;
	var $recModifier;

	function __construct()
	{

		parent::__construct ();
	
	}

	function prepare_variables()
	{

		$variables = array (
				'kTeach',
				'stuFirst',
				'stuLast',
				'stuNickname',
				'stuGender',
				'stuDOB',
				'baseGrade',
				'baseYear',
				'isEnrolled',
				'isGraduate',
				'stuEmail',
				'stuEmailPermission',
				'stuEmailPassword',
				'stuGroup' 
		);
		for($i = 0; $i < count ( $variables ); $i ++) {
			$myVariable = $variables [$i];
			if ($this->input->post ( $myVariable )) {
				 if ($myVariable == "stuDOB") {
					$this->stuDOB = format_date ( $this->input->post ( 'stuDOB' ), 'mysql' );
				} else {
					$this->$myVariable = $this->input->post ( $myVariable );
				}
			}
		}
				
		$this->recModified = mysql_timestamp ();
		$this->recModifier = $this->session->userdata ( 'userID' );
	
	}

	/**
	 * Getter
	 */
	function get($kStudent, $fields = NULL, $include_teacher = FALSE)
	{

		$this->db->where ( 'kStudent', $kStudent );
		$this->db->from ( 'student' );
		if ($fields) {
			$this->db->select ( $fields );
		}else{
			$this->db->select("*,(`baseGrade`+". get_current_year() . "-`baseYear`) as stuGrade");
		}
		if ($include_teacher) {
			$this->db->join ( "teacher", "teacher.kTeach=student.kTeach" );
		}
		$result = $this->db->get ()->row ();
		if ($result) {
			return $result;
		} else {
			return false;
		}
	
	}

	function get_value($kStudent, $fieldName)
	{

		$this->db->where ( 'kStudent', $kStudent );
		if (is_array ( $fieldName )) {
			foreach ( $field as $fieldName ) {
				$this->db->select ( $field );
			}
		}
		$this->db->from ( 'student' );
		$result = $this->db->get ()->row ();
		return $result->$fieldName;
	
	}

	function find_students($stuName)
	{

		$this->load->model ( "preference_model", "preference" );
		$include_former_students = $this->preference->get ( $this->session->userdata ( "userID" ), "show_former_students" );
		if ($include_former_students != "yes") {
			$this->db->where ( "isEnrolled", 1 );
		}
		$this->db->where ( "(CONCAT(`stuFirst`,' ', `stuLast`) LIKE '%$stuName%' OR CONCAT(`stuNickname`,' ', `stuLast`) LIKE '%$stuName%')" );
		$this->db->order_by ( "stuFirst", "ASC" );
		$this->db->order_by ( "stuLast", "ASC" );
		$year = get_current_year();
		$this->db->select ( "student.*,(baseGrade+$year-baseYear) AS listGrade" );
		
		$result = $this->db->get ( "student" )->result ();
		return $result;
	
	}

	function count($field_name, $field_value, $where = FALSE)
	{

		$this->db->select ( "COUNT(`$field_name`) AS `$field_name`" );
		$this->db->where ( $field_name, $field_value );
		if ($where) {
			if (is_array ( $where )) {
				$where_keys = array_keys ( $where );
				$where_values = array_values ( $where );
				for($i = 0; $i < count ( $where ); $i ++) {
					$key = $where_keys [$i];
					$value = $where_values [$i];
					$this->db->where ( "$key != $value" );
				}
			}
		}
		
		$this->db->from ( "student" );
		$output = $this->db->get ()->row ();
		return $output->$field_name;
	
	}

	/**
	 *
	 * @param $kTeach int
	 *        	This returns the students assigned to either a classroom teacher or middle school
	 *        	advisor depending on the grade of the student
	 *        	
	 */
	function get_students_by_class($kTeach)
	{

		$this->db->where ( 'student.isEnrolled', 1 );
		$this->db->where ( 'student.kTeach', $kTeach );
		$this->db->where ( "`student`.`kTeach`=`teacher`.`kTeach`" );
		$this->db->order_by ( "stuGrade", "ASC" );
		$this->db->order_by ( 'stuLast', 'ASC' );
		$this->db->order_by ( 'stuFirst', 'ASC' );
		$this->db->from ( 'student' );
		$this->db->from ( "teacher" );
		$result = $this->db->get ()->result ();
		return $result;
	
	}

	/**
	 *
	 * @param $kTeach int
	 *        	alias for get_students_by_class. This has been deprecated for clarification purposes.
	 */
	function get_students_by_teacher($kTeach)
	{

		return $this->get_students_by_class ( $kTeach );
	
	}

	/**
	 *
	 *
	 * Lists enrolled students by grade with added constraints.
	 * The constraints array can contain kTeach, and a select variable with
	 * a list of fields to include in the result
	 * 
	 * @param int $gradeStart        	
	 * @param int $gradeEnd        	
	 * @param array $constraints        	
	 */
	function get_students_by_grade($gradeStart, $gradeEnd, $constraints = array())
	{

		if ($gradeStart == $gradeEnd) {
			$this->db->where ( "(`baseGrade`+2014-`baseYear`)", $gradeStart );
		} else {
			$this->db->where ( "`baseGrade`+2014-`baseYear`  BETWEEN $gradeStart AND $gradeEnd" );
		}
		
		if (get_array_value ( $constraints, "kTeach" )) {
			$this->db->where ( "kTeach", $constraints ["kTeach"] );
		} elseif (get_array_value ( $constraints, 'humanitiesTeacher' )) {
			$this->db->where ( "humanitiesTeacher", $constraints ['humanitiesTeacher'] );
		}
		// @TODO need to have an override here to allow this to fork depending on the calling method
		// some situations may require showing students by grade to include enrolled students.
		$this->db->where ( "isEnrolled", 1 );
		if (array_key_exists ( "select", $constraints )) {
			$this->db->select ( $constraints ["select"] );
		}else{
			$year = get_current_year();
			$this->db->select("student.*,(`baseGrade`+$year-`baseYear`) as `stuGrade`");
		}
		$this->db->order_by ( "stuGrade" );
		$this->db->order_by ( "stuLast" );
		$this->db->order_by ( "stuFirst" );
		
		$this->db->from ( "student" );
		$result = $this->db->get ()->result ();
		$this->session->set_flashdata("notice",$this->db->last_query());
		return $result;
	
	}

	/**
	 *
	 *
	 * Find students based on a range of parameters
	 * 
	 * @param int $year        	
	 * @param array $grades        	
	 * @param boolean $hasNeeds        	
	 * @param boolean $includeFormerStudents        	
	 */
	function advanced_find($year, $grades = array(), $hasNeeds = 0, $includeFormerStudents = 0, $sorting = NULL)
	{

		$this->db->select ( "student.*,(baseGrade+$year-baseYear) AS stuGrade" );
		if (! empty ( $grades )) {
			$this->db->where_in ( "`baseGrade`+$year-`baseYear`", $grades );
		}else{
			$this->db->where("`baseGrade` + $year - `baseYear` BETWEEN 0 AND 8");
		}
		//if (! $includeFormerStudents) {
			//$this->db->where ( "`baseGrade`+$year-`baseYear` < 9" );
		//}
		if ($includeFormerStudents == 1) {
			$this->db->where_in ( "isEnrolled", array (
					0,
					1 
			) );
		} else {
			$this->db->where ( "(`isEnrolled` = 1 OR `isGraduate` = 1)",NULL,FALSE );
		}
		
		if ($hasNeeds == 1) {
			$this->db->join ( "support", "student.kStudent = support.kStudent" );
			$this->db->group_by ( "support.kStudent" );
		}
		
		$this->db->from ( "student" );
		$this->db->order_by ( "stuGrade", "ASC" );
		
		if ($sorting == "first_last") {
			$this->db->order_by ( "stuFirst,stuLast", "ASC" );
		} else {
			$this->db->order_by ( "stuLast,stuFirst", "ASC" );
		}
		
		$result = $this->db->get ()->result ();
		$this->session->set_flashdata('notice', $this->db->last_query());
		return $result;
	
	}

	/**
	 * DEPRECATED/UNUSED
	 * I don't think this is used anywhere else.
	 * 
	 * @param string $fields
	 *        	fields to select
	 * @param string $order_fields
	 *        	fields to order the results
	 * @param array $where_pairs
	 *        	field=>value pairs for "where" constraints
	 */
	function get_distinct_values($fields, $order_fields = null, $where_pairs = array())
	{

		$this->db->from ( 'student' );
		$this->db->distinct ();
		
		if (is_array ( $fields )) {
			foreach ( $fields as $field ) {
				$this->db->select ( $field );
			}
		} else {
			$this->db->select ( $fields );
		}
		
		if ($order_fields) {
			if (is_array ( $order_fields )) {
				foreach ( $order_fields as $order ) {
					$this->db->order_by ( $order );
				}
			} else {
				$this->db->order_by ( $order_fields );
			}
		}
		
		if (is_array ( $where_pairs )) {
			$keys = array_keys ( $where_pairs );
			$values = array_values ( $where_pairs );
			for($i = 0; $i < count ( $where_pairs ); $i ++) {
				$this->db->where ( $keys [$i], $values [$i] );
			}
		}
		
		$result = $this->db->get ()->result ();
		return $result;
	
	}

	function get_grade($kStudent, $narrYear = NULL)
	{

		$baseGrade = $this->get_value ( $kStudent, 'baseGrade' );
		$baseYear = $this->get_value ( $kStudent, 'baseYear' );
		return get_current_grade ( $baseGrade, $baseYear, $narrYear );
	
	}

	function get_name($kStudent)
	{

		$student = $this->get ( $kStudent, 'stuFirst,stuLast,stuNickname' );
		$output = format_name ( $student->stuFirst, $student->stuLast, $student->stuNickname );
		return $output;
	
	}

	/**
	 * Does the student have records in the system in the narratives, grades, etc?
	 * This is used to determine if a student record can be deleted or not.
	 * Student records cannot be deleted if there are dependent records elsewhere
	 * 
	 * @param int $kStudent        	
	 */
	function has_records($kStudent)
	{

		$output = 0;
		$tables = array (
				"narrative",
				"grade",
				"support" 
		);
		foreach ( $tables as $table ) {
			$this->db->from ( $table );
			$this->db->where ( "kStudent", $kStudent );
			$output += $this->db->count_all_results ();
		}
		return $output;
	
	}

	/**
	 * Setter
	 */
	function insert()
	{

		$this->prepare_variables ();
		$this->db->insert ( 'student', $this );
		return $this->db->insert_id ();
	
	}

	function update($kStudent)
	{

		$this->prepare_variables ();
		$this->kStudent = $kStudent;
		$this->db->where ( 'kStudent', $kStudent );
		$this->db->update ( 'student', $this );
	
	}

	function update_value($kStudent, $data)
	{

		$this->db->where ( 'kStudent', $kStudent );
		$this->db->update ( 'student', $data );
	
	}

	function update_grades()
	{

	
	}

	/**
	 * Delete student record only if the student has no entries in other tables.
	 * 
	 * @param number $kStudent        	
	 * @return comma-separated string with initial boolean and message--used by javascript to determine the alert and response.
	 *         @TODO maybe develop a set of generic database key->string pairs for messages?
	 */
	function delete($kStudent)
	{

		$output = "0,You do not have permission to delete student records. Please contact the system administrator for assistance";
		if ($this->session->userdata ( "userID" ) == 1000) {
			if ($this->has_records ( $kStudent ) == 0) {
				$this->db->delete ( 'student', array (
						'kStudent' => $kStudent 
				) );
				$output = "1,The student record was successfully deleted";
			} else {
				$output = "0,The student record has multiple dependent entries in other tables and cannot be deleted";
			}
		}
		return $output;
	
	}

}