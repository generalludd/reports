<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Assignment_model extends MY_Model {
	var $kTeach;
	var $kCategory;
	var $assignment;
	var $date;
	var $points;
	var $points_type;
	var $subject;
	var $term;
	var $year;
	var $gradeStart;
	var $gradeEnd;

	function prepare_variables()
	{
		$variables = array (
				"kTeach",
				"assignment",
				"kCategory",
				"date",
				"points",
				"points_type",
				"subject",
				"term",
				"year",
				"gradeStart",
				"gradeEnd" 
		);
		
		for($i = 0; $i < count ( $variables ); $i ++) {
			$myVariable = $variables [$i];
			if ($this->input->post ( $myVariable )) {
				$post = $this->input->post ( $myVariable );
				$this->$myVariable = $post;
			}
		}
	}

	function get($kAssignment)
	{
		$this->db->where ( "kAssignment", $kAssignment );
		$this->db->join ( "assignment_category as category", "assignment.kCategory = category.kCategory", "LEFT" );
		$this->db->select ( "assignment.*,category.weight,category.category" );
		$this->db->from ( "assignment" );
		$result = $this->db->get ()->row ();
		return $result;
	}

	function insert($variables = array())
	{
		if (empty ( $variables )) {
			$this->prepare_variables ();
			$this->db->insert ( "assignment", $this );
		} else {
			$this->db->insert ( "assignment", $variables );
		}
		$kAssignment = $this->db->insert_id ();
		return $kAssignment;
	}

	function update($kAssignment)
	{
		// get the values of the current assignment to be able to adjust student
		// grades according to the new assignment grade
		$old_assignment = $this->get ( $kAssignment );
		$this->prepare_variables ();
		$this->db->where ( "kAssignment", $kAssignment );
		$this->db->update ( "assignment", $this );
		// if the grade is not 0 then adjust the student points accordingly.
		// 0 points for a grade will be calculated as make-up or extra-credit points for quizzes
		// or other assignments.
		if ($this->points != 0) {
			$this->load->model ( "grade_model", "grade" );
			$percentage = $this->points / $old_assignment->points;
			$this->grade->batch_adjust_points ( $kAssignment, $percentage );
		}
	}

	/**
	 * get all the grades for a given teacher, term, grade range, student
	 * grouping and an optional date-range limiter.
	 *
	 * @param int $kTeach        	
	 * @param varchar $term        	
	 * @param int $year        	
	 * @param int $gradeStart        	
	 * @param int $gradeEnd        	
	 * @param string $stuGroup        	
	 * @param array $date_range        	
	 * @return query result rows
	 */
	function get_grades($kTeach, $term, $year, $gradeStart, $gradeEnd, $stuGroup = NULL, $date_range = array(), $sort_order = NULL)
	{
		$this->db->from ( "assignment" );
		$this->db->where ( "assignment.term", $term );
		$this->db->where ( "assignment.year", $year );
		$this->db->where ( "assignment.kTeach", $kTeach );
		$this->db->where ( "(assignment.gradeStart = $gradeStart OR assignment.gradeEnd = $gradeEnd)" );
		if ($stuGroup) {
			$this->db->where ( "student.stuGroup", $stuGroup );
		}
		if ($date_range) {
			$this->db->where ( sprintf ( "(`assignment`.`date` BETWEEN '%s' AND '%s')", $date_range ["date_start"], $date_range ["date_end"] ) );
		}
		$this->db->where ( "((`student`.`baseGrade` + $year -`student`.`baseYear`) BETWEEN $gradeStart AND $gradeEnd)" );
		$this->db->join ( "grade", "assignment.kAssignment=grade.kAssignment" );
		$this->db->join ( "student", "grade.kStudent=student.kStudent" );
		$this->db->join ( "assignment_category as category", "assignment.kCategory = category.kCategory", "LEFT" );
		if ($sort_order == "stuLast") {
			$this->db->order_by ( "student.stuLast" );
			$this->db->order_by ( "student.stuFirst" );
		} else {
			$this->db->order_by ( "student.stuFirst" );
			$this->db->order_by ( "student.stuLast" );
		}
		$this->db->order_by ( "student.kStudent" );
		$this->db->order_by ( "assignment.date" );
		$this->db->order_by ( "assignment.kAssignment" );
		$this->db->order_by ( "assignment.term" );
		$this->db->order_by ( "assignment.year" );
		$this->db->select ( "student.kStudent,student.stuFirst,student.stuLast,student.stuNickname, (`student`.`baseGrade` + $year -`student`.`baseYear`) as `stuGrade`,student.stuGroup" );
		$this->db->select ( "grade.*" );
		$this->db->select ( "assignment.kTeach, assignment.assignment,assignment.points as assignment_total,assignment.subject, assignment.term,assignment.year" );
		$this->db->select ( "category.weight,category.category" );
		$result = $this->db->get ()->result ();
		return $result;
	}

	/**
	 * get grades for a given assignement optionally limited to a specific
	 * student grouping
	 *
	 * @param integer $kAssignment        	
	 * @param string $stuGroup        	
	 * @return object array of grades for a given assignment
	 *         This returns an array of all the grades (as objects) for a given
	 *         assignment so they can be edited quickly in a column
	 */
	function get_assignment_grades($kAssignment, $stuGroup = NULL)
	{
		$this->db->where ( "assignment.kAssignment", $kAssignment );
		if ($stuGroup) {
			$this->db->where ( "student.stuGroup", $stuGroup );
		}
		$year = get_current_year ();
		if ($this->input->cookie ( "year" )) {
			$year = $this->input->cookie ( "year" );
		}
		$this->db->join ( "grade", "assignment.kAssignment=grade.kAssignment" );
		$this->db->join ( "student", "grade.kStudent=student.kStudent" );
		$this->db->join ( "assignment_category as category", "assignment.kCategory = category.kCategory", "LEFT" );
		$sort_order = get_cookie ( "student_sort_order" );
		if ($sort_order == "stuLast") {
			$this->db->order_by ( "student.stuLast" );
			$this->db->order_by ( "student.stuFirst" );
		} else {
			$this->db->order_by ( "student.stuFirst" );
			$this->db->order_by ( "student.stuLast" );
		}
		$this->db->order_by ( "student.kStudent" );
		$this->db->order_by ( "assignment.date" );
		$this->db->order_by ( "assignment.kAssignment" );
		$this->db->order_by ( "assignment.term" );
		$this->db->order_by ( "assignment.year" );
		$this->db->select ( "student.kStudent,student.stuFirst,student.stuLast,student.stuNickname, (`baseGrade` + $year -`baseYear`) as`stuGrade`,student.stuGroup" );
		$this->db->select ( "grade.*" );
		$this->db->select ( "assignment.kTeach, assignment.assignment,assignment.points as assignment_total,assignment.subject, assignment.term,assignment.year" );
		$this->db->select ( "category.weight,category.category" );
		$result = $this->db->get ( "assignment" )->result ();
		return $result;
	}

	/**
	 * This collects the grades for a given student for the current term and
	 * year with options including the current teacher.
	 * Perhaps this should be with the grade model. But here it is!
	 *
	 * @param int $kStudent        	
	 * @param string $term        	
	 * @param int $year        	
	 * @param array $options,
	 *        	a limiter including the teacher (kTeach),
	 *        	subject. grade_range is a puzzlement given the term and year
	 *        	are required.
	 * @return array of student grade objects, the result of the query
	 *        
	 */
	function get_for_student($kStudent, $term, $year, $options = array())
	{
		$from = "assignment";
		$join = "grade";
		
		if (array_key_exists ( "from", $options ) && array_key_exists ( "join", $options )) {
			$from = $options ["from"];
			$join = $options ["join"];
		}
		if ($term) {
			$this->db->where ( "assignment.term", $term );
		}
		$this->db->where ( "assignment.year", $year );
		
		// $this->db->where("grade.kStudent",$kStudent);
		if (array_key_exists ( "kTeach", $options )) {
			$this->db->where ( "assignment.kTeach", $options ["kTeach"] );
		}
		if (array_key_exists ( "subject", $options )) {
			$this->db->where ( "assignment.subject", $options ["subject"] );
		}
		
		if (array_key_exists ( "grade_range", $options )) {
			$gradeStart = $options ["grade_range"] ["gradeStart"];
			$gradeEnd = $options ["grade_range"] ["gradeEnd"];
			$this->db->where ( "assignment.gradeStart>=", $gradeStart );
			$this->db->where ( "assignment.gradeEnd<=", $gradeEnd );
		}
		
		// $this->db->where("assignment.gradeStart = category.gradeStart");
		// $this->db->where("assignment.gradeEnd = category.gradeEnd");
		
		$this->db->from ( $from );
		$this->db->join ( $join, "assignment.kAssignment=grade.kAssignment AND grade.kStudent = $kStudent", "LEFT" );
		$this->db->join ( "student", "student.kStudent=grade.kStudent", "LEFT" );
		$this->db->join ( "teacher", "teacher.kTeach=assignment.kTeach", "LEFT" );
		$this->db->join ( "menu", "grade.footnote = menu.value AND menu.category='grade_footnote'", "LEFT" );
		$this->db->join ( "assignment_category as category", "assignment.kCategory = category.kCategory", "LEFT" );
		$this->db->select ( "category.category,category.weight" );
		$this->db->select ( "assignment.kAssignment, assignment.term, assignment.year, assignment.subject, assignment.date, assignment.assignment,assignment.points_type, assignment.points as total_points" );
		$this->db->select ( "grade.kGrade,grade.points,grade.status,grade.footnote" );
		$this->db->select ( "menu.label" );
		$this->db->select ( "student.stuFirst,student.stuNickname,student.stuLast,student.stuGroup" );
		$this->db->select ( "teacher.teachFirst,teacher.teachLast" );
		$this->db->order_by ( "assignment.subject" );
		$this->db->order_by ( "assignment.date" );
		$this->db->order_by ( "assignment.kAssignment" );
		$this->db->order_by ( "assignment.kCategory" );
		$result = $this->db->get ()->result ();
		$this->_log();
		return $result;
	}

	/**
	 *
	 * @param int $kTeach        	
	 * @param varchar $term        	
	 * @param int $year        	
	 * @param int $gradeStart        	
	 * @param int $gradeEnd        	
	 * @param array $date_range        	
	 * @return query result object array
	 */
	function get_for_teacher($kTeach, $term, $year, $gradeStart, $gradeEnd, $date_range = array())
	{
		$this->db->where ( "assignment.kTeach", $kTeach );
		$this->db->where ( "assignment.term", $term );
		$this->db->where ( "assignment.year", $year );
		$this->db->where ( "(assignment.gradeStart = $gradeStart OR assignment.gradeEnd = $gradeEnd)" );
		if ($date_range) {
			$this->db->where ( sprintf ( "(`assignment`.`date` BETWEEN '%s' AND '%s')", $date_range ["date_start"], $date_range ["date_end"] ) );
		}
		$this->db->from ( "assignment" );
		$this->db->join ( "assignment_category as category", "assignment.kCategory = category.kCategory", "LEFT" );
		$this->db->join ( "teacher", "assignment.kTeach=teacher.kTeach", "LEFT" );
		$this->db->select ( "assignment.*,category.weight,category.category,teacher.teachFirst,teacher.teachLast" );
		$this->db->order_by ( "assignment.date" );
		$this->db->order_by ( "assignment.kAssignment" );
		$this->db->order_by ( "assignment.term" );
		$this->db->order_by ( "assignment.year" );
		$output = $this->db->get ()->result ();
		return $output;
	}

	function delete($kAssignment)
	{
		$delete_array = array (
				"kAssignment" => $kAssignment 
		);
		$this->db->delete ( "assignment", $delete_array );
		$this->db->delete ( "grade", $delete_array );
	}

	/**
	 * *** CATEGORY WEIGHTS *****
	 */
	function insert_category($values = array())
	{
		$kCategory = FALSE;
		if (array_key_exists ( "kTeach", $values )) {
			return $this->_replace_into("assignment_category",$values);
		
// 			$query = sprintf("REPLACE INTO assignment_category ( `kTeach`, `category`, `weight`, `gradeStart`, `gradeEnd`, `term`, `year`) VALUES('%s','%s','%s','%s','%s','%s','%s')",
// 					$values->kTeach,
// 					$values->category,
// 					$values->weight,
// 					$values->gradeStart,
// 					$values->gradeEnd,
// 					$values->term,
// 					$values->year
// 			);
// 			$this->db->query($query);
// 			$kCategory = $this->db->insert_id ();
			$this->_log ( "notice" );
		}
		return $kCategory;
	}

	function update_category($kCategory, $values = array())
	{
		if (! empty ( $values )) {
			$this->db->where ( "kCategory", $kCategory );
			$this->db->update ( "assignment_category", $values );
		}
	}

	function get_category($kCategory)
	{
		$this->db->where ( "kCategory", $kCategory );
		$this->db->from ( "assignment_category" );
		$result = $this->db->get ()->row ();
		return $result;
	}

	function get_categories($kTeach, $gradeStart, $gradeEnd, $year, $term)
	{
		$this->db->distinct ( "category" );
		if ($kTeach) {
			$this->db->where ( "kTeach", $kTeach );
		}
		$this->db->where ( "kTeach", $kTeach );
		$this->db->where ( "gradeStart", $gradeStart );
		$this->db->where ( "gradeEnd", $gradeEnd );
		$this->db->where ( "year", $year );
		$this->db->where ( "term", $term );
		$this->db->order_by ( "weight", "DESC" );
		$result = $this->db->get ( "assignment_category" )->result ();
		return $result;
	}

	function count_categories($kTeach, $gradeStart, $gradeEnd, $year, $term)
	{
		$this->db->select ( "COUNT(kCategory) as count" );
		$this->db->where ( "kTeach", $kTeach );
		$this->db->where ( "gradeStart", $gradeStart );
		$this->db->where ( "gradeEnd", $gradeEnd );
		$this->db->where ( "term", $term );
		$this->db->where ( "year", $year );
		$result = $this->db->get ( "assignment_category" )->row ();
		return $result->count;
	}
}