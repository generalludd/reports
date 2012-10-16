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
	 * Finds all the students with a given assignment of the same term, teacher and year and creates new records for the student.
	 * @TODO Maybe adding the points could be a preference for each teacher.
	 */
	function batch_insert($kAssignment,$kTeach,$term,$year,$grade_start, $grade_end,$points)
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
		print $this->db->last_query();
		foreach($students as $student){
			//$data = array("kAssignment"=>$kAssignment, "kStudent"=>$student->kStudent,"points"=>"0");
			//$this->db->insert("grade",$data);
			$this->update($student->kStudent, $kAssignment, $kTeach, $points, NULL, NULL, NULL, NULL);
		}
		return $students;
	}

	function batch_adjust_points($kAssignment,$percentage)
	{
		$this->db->query("UPDATE `grade` SET `points` = `points` * $percentage WHERE `kAssignment` = $kAssignment");
	}


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

	function update_value($kStudent, $kAssignment, $key, $value)
	{
		$this->db->where("kStudent",$kStudent);
		$this->db->where("kAssignment", $kAssignment);
		$data = array($key => $value);
		if($this->db->update("grade",$data)){
			$output = TRUE;
		}else{
			$output = FALSE;
		}
		return $output;
	}

	function calculate_weight($kTeach, $category)
	{
		$this->db->select("weight");
		$this->db->where("category",$category);
		$this->db->where("kTeach",$kTeach);
		$this->db->from("assignment_category");
		$result = $this->db->get()->row()->weight;
		return $result;

	}

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

	function get_summary($kTeach, $gradeStart, $gradeEnd, $term, $year, $kCategory = NULL)
	{

		/*select `grade`.`kStudent` AS `kStudent`,`grade`.`kGrade` AS `kGrade`,
		 * (sum(`grade`.`points`) / sum(`assignment`.`points`)) AS `average`,`assignment`.`kCategory` AS `kCategory`,`assignment`.`kTeach` AS `kTeach`,`assignment`.`term` AS `term`,
		* `assignment`.`year` AS `year` from (`grade` join `assignment` on((`grade`.`kAssignment` = `assignment`.`kAssignment`)))
		* group by `grade`.`kGrade`

		*/
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

	function delete($kGrade)
	{
		$delete = array("kGrade" => $kGrade);
		$this->db->delete("grade",$delete);
	}


}