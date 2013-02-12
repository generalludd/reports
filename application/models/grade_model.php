<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Grade_model extends CI_Model
{

	var $kTeach;
	var $kStudent;
	var $kAssignment;
	var $points;
	var $status;
	var $footnote;

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * prepare_variables is a standard script in all models to gether the $_POST
	 * values and assign them to the class variables.
	 */
	function prepare_variables()
	{
		$variables = array("kTeach","kStudent","kAssignment","points","status","footnote");
		for($i = 0; $i < count($variables); $i++){
			$myVariable = $variables[$i];
			if($this->input->post($myVariable)){
				$this->$myVariable = $this->input->post($myVariable);
			}
		}
	}

	/**
	 *
	 * @param int $kStudent
	 * @param int $kAssignment
	 * @return object array
	 * Gets a single row record of a given kStudent kAssignment pair
	 */
	function get($kStudent,$kAssignment)
	{
		$this->db->where("kStudent",$kStudent);
		$this->db->where("kAssignment",$kAssignment);
		$this->db->from("grade");
		$output = $this->db->get()->row();
		return $output;
	}


	/**
	 * has_grade
	 * @param int $kStudent
	 * @param int $kAssignment
	 * determines if a student has a grade for a given assignment.
	 */
	function has_grade($kStudent,$kAssignment)
	{
		$this->db->where("kAssignment",$kAssignment);
		$this->db->where("kStudent",$kStudent);
		$this->db->from("grade");
		$result = $this->db->get()->num_rows();
		return $result;
	}


	/**
	 * batch_insert
	 * @param int $kAssignment
	 * @param int $kTeach
	 * @param varchar $term
	 * @param in $year
	 * Finds all the students with a given assignment of the same term, teacher
	 * and year and creates new records for the student.
	 * the points value is provided by the controller. The UI offers a choice
	 * to either give the students a starting value of "0" or of the total points available.
	 */
	function batch_insert($kAssignment,$kTeach,$term,$year,$grade_start,$grade_end,$points)
	{
		$this->db->select("distinct(`grade`.`kStudent`)");
		$this->db->from("assignment");
		$this->db->join("grade","grade.kAssignment = assignment.kAssignment","LEFT");
		$this->db->join("student", "grade.kStudent = student.kStudent");
		$this->db->where("(student.stuGrade BETWEEN $grade_start AND $grade_end)");
		$this->db->where("assignment.kTeach",$kTeach);
		$this->db->where("term",$term);
		$this->db->where("year",$year);
		$this->db->where("grade.kStudent IS NOT NULL");
		$this->db->where("grade.kAssignment !=$kAssignment");
		$students = $this->db->get()->result();
		foreach($students as $student){
			$this->update($student->kStudent, $kAssignment, $points, NULL, NULL);
		}
		return $students;
	}

	function batch_adjust_points($kAssignment,$percentage)
	{
		$this->db->query("UPDATE `grade` SET `points` = `points` * $percentage WHERE `kAssignment` = $kAssignment");
	}

	/**
	 *
	 * @param int $kStudent
	 * @param int $kAssignment
	 * @param double $points
	 * @param varchar $status
	 * @param int $footnote
	 * @return boolean or insert id
	 * updates and also inserts (if the kStudent-kAssignment pair is not present in the table).
	 * I suppose I could have used a REPLACE INTO function and made the kStudent kAssignment pair
	 * a table key, but I did not.
	 */
	function update($kStudent, $kAssignment, $points, $status,$footnote)
	{
		$output = FALSE;
		$data = array("points" => $points,"status"=>$status,"footnote"=>$footnote);
		if($this->has_grade($kStudent, $kAssignment) > 0){
			$this->db->where("kAssignment",$kAssignment);
			$this->db->where("kStudent",$kStudent);
			$this->db->update("grade", $data);
			$output = TRUE;
		}else{
			$data["kStudent"] = $kStudent;
			$data["kAssignment"] = $kAssignment;
			$this->db->insert("grade", $data);
			$output = $this->db->insert_id();
		}
		return $output;
	}

	/**
	 *
	 * @param int $kStudent
	 * @param int $kAssignment
	 * @param varchar $key
	 * @param varchar $value
	 * @return FALSE or key name
	 * update a grade value based on kStudent and kAssignment
	 */
	function update_value($kStudent, $kAssignment, $key, $value)
	{
		$output = FALSE;
		$output = $this->has_grade($kStudent,$kAssignment);
		if($this->has_grade($kStudent, $kAssignment) == 1){
			$this->db->where("kStudent",$kStudent);
			$this->db->where("kAssignment", $kAssignment);
			$data = array($key => $value);
			if($this->db->update("grade",$data)){
				$output = $this->get($kStudent,$kAssignment)->$key;
			}else{
				$output = FALSE;
			}
		}
		return $output;
	}

	/**
	 * DEPRECATED
	 * @param int $kTeach
	 * @param varchar $category
	 * @return weight value for a teacher and category
	 * This cannot work because it doesn't require the grade range for the subject.
	 */
	function calculate_weight($kTeach, $category)
	{
		$this->db->select("weight");
		$this->db->where("category",$category);
		$this->db->where("kTeach",$kTeach);
		$this->db->from("assignment_category");
		$result = $this->db->get()->row()->weight;
		return $result;
	}


	/**
	 * UNUSED/DEPRECATED
	 * @param int $kStudent
	 * @param varchar $term
	 * @param int $year
	 * @param string $kTeach
	 * @return array of objects
	 * Get the grades for a given student (optionally for a specific teacher) grouped by category.
	 * This function is not used. 
	 */
	function get_totals($kStudent, $term,$year, $kTeach = NULL){
		$this->db->where("grade.kStudent",$kStudent);
		if($kTeach){
			$this->db->where("assignment.kTeach",$kTeach);
		}
		$this->db->where("assignment.term", $term);
		$this->db->where("assignment.year",$year);
		$this->db->select("grade.kStudent,assignment.kTeach,assignment.kAssignment,assignment.kCategory,category.category, sum(grade.points)/sum(assignment.points) as category_average");
		$this->db->join("assignment","grade.kAssignment=assignment.kAssignment");
		$this->db->join("assignment_category as category","assignment.kCategory=category.kCategory");
		$this->db->group_by("assignment.kCategory");
		$this->db->from("grade");
		$result = $this->db->get()->result();
		return $result;
	}

	/**
	 * DEPRECATED
	 * @param int $kTeach
	 * @param int $gradeStart
	 * @param int $gradeEnd
	 * @param varchar $term
	 * @param int $year
	 * @param string $kCategory
	 * @return array of db objects
	 * This script is not used. It was designed to get all the grades from a 
	 * view called grade_total.  
	 */
	function get_summary($kTeach, $gradeStart, $gradeEnd, $term, $year, $kCategory = NULL)
	{
		$this->db->from("grade_total");
		$this->db->where("grade_total.kTeach",$kTeach);
		$this->db->where("assignment.gradeStart",$gradeStart);
		$this->db->where("assignment.gradeEnd",$gradeEnd);
		$this->db->where("grade_total.term",$term);
		$this->db->where("grade_total.year",$year);
		if($kCategory){
			$this->db->where("grade_total.kCategory",$kCategory);
		}
		$this->db->join("assignment","assignment.kCategory=grade_total.kCategory");
		$this->db->order_by("grade_total.kCategory");
		$this->db->group_by("kStudent");
		$this->db->select("grade_total.kStudent,grade_total.kCategory");
		$result = $this->db->get()->result();
		return $result;
	}

	/**
	 * DEPRECATED This does not produce an accurate result because it does not account for Abs and Exc. Category totals are not evaluated
	 * in the business logic as appropriate instead of in the model.
	 * @param int $kStudent
	 * @param varchar $term
	 * @param int $year
	 * @param date $cutoff_date optional mysql  date format (yyyy-mm-dd)
	 * @return object
	 * get a distinct list of categories for subjects with totals for the given term & year to produce a report card.
	 */
	function get_categories($kStudent, $term, $year, $options = array()){
		if(array_key_exists("cutoff_date",$options)){
			$this->db->where(sprintf("`assignment`.`date` <= '%s'", $options["cutoff_date"]));
		}

		if(array_key_exists("subject",$options)){
			$this->db->where("assignment.subject",$options["subject"]);
		}
		$this->db->from("grade");
		$this->db->where("kStudent",$kStudent);
		$this->db->join("assignment","grade.kAssignment=assignment.kAssignment","LEFT");
		$this->db->join("assignment_category as category","assignment.kCategory = category.kCategory","LEFT");
		$this->db->select("category.category");
		$this->db->select("subject");
		$this->db->select("SUM(grade.points) as grade_points");
		$this->db->select("SUM(assignment.points) as total_points");
		$this->db->select("category.weight");
		$this->db->order_by("subject");
		$this->db->group_by("assignment.kCategory");
		$result = $this->db->get()->result();
		return $result;
	}

	/**
	 *
	 * @param int $kStudent
	 * @param varchar $term
	 * @param int $year
	 * @param date $cutoff_date optional standard US date (mm-dd-yyyy) format converted in script to mysql
	 * @return object
	 * get a distinct list of subjects for a student for the term, year and optional cutoff date.
	 */
	function get_subjects($kStudent, $term, $year, $cutoff_date = NULL){
		if($cutoff_date){
			$this->db->where(sprintf("`assignment`.`date` <= '%s'", format_date($cutoff_date,"mysql")));
		}
		$this->db->from("grade");
		$this->db->where("kStudent",$kStudent);
		$this->db->join("assignment","grade.kAssignment=assignment.kAssignment","LEFT");
		$this->db->select("subject");
		$this->db->select("SUM(grade.points) as grade_points");
		$this->db->select("SUM(assignment.points) as total_points");
		$this->db->order_by("subject");
		$this->db->group_by("subject");
		$result = $this->db->get()->result();
		return $result;


	}


	/**
	 * Collect the list of current students for a teacher in preparation for creating all grade reports.
	 * @param int $kTeach
	 * @param string $term
	 * @param int $year
	 * @param int $gradeStart
	 * @param int $gradeEnd
	 * @param string $cutoff_date
	 */
	function get_reports($kTeach, $term, $year, $gradeStart, $gradeEnd, $cutoff_date = NULL){
		$this->db->select("DISTINCT(student.kStudent) as kStudent, student.stuLast,student.stuFirst,student.stuNickname");
		$this->db->from("grade");
		$this->db->join("student","grade.kStudent = student.kStudent");
		$this->db->join("assignment","grade.kAssignment = assignment.kAssignment");
		$this->db->where("assignment.kTeach",$kTeach);
		$this->db->where("assignment.term",$term);
		$this->db->where("assignment.gradeStart",$gradeStart);
		$this->db->where("assignment.gradeEnd",$gradeEnd);
		if($cutoff_date){
			$this->db->where("assignment.date <= '$cutoff_date'");
		}
		$this->db->order_by("student.stuLast");
		$this->db->order_by("student.stuFirst");
		$result = $this->db->get()->result();
		return $result;
	}

	/**
	 * DEPRECATED
	 * This script is not used. The deletion of a single grade would be problematic. Ï
	 * @param unknown $kGrade
	 */
	function delete_grade($kGrade)
	{
		//$delete = array("kGrade" => $kGrade);
		//$this->db->delete("grade",$delete);
	}

	/**
	 *
	 * @param unknown $kStudent
	 * @param unknown $kTeach
	 * @param unknown $term
	 * @param unknown $year
	 */
	function delete_row($kStudent,$kTeach, $term, $year)
	{
		if($kTeach && $kStudent && $term && $year){
			$query = sprintf("DELETE grade FROM grade, assignment WHERE `grade`.`kAssignment` = `assignment`.`kAssignment` AND `assignment`.`kTeach` = '%s'
					AND `grade`.`kStudent` = '%s' AND `assignment`.`term` = '%s' AND `assignment`.`year` = '%s'", $kTeach, $kStudent, $term, $year);
			$this->db->query($query);
		}
	}


}