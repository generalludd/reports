<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Narrative_model extends MY_Model
{
	var $kNarrative;
	var $kStudent;
	var $kTeach;
	var $stuGrade = 0;
	var $narrText;
	var $narrTerm;
	var $narrSubject;
	var $narrGrade;
	var $narrYear;
	var $recModified;
	var $recModifier;

	function __construct()
	{

		parent::__construct ();

	}

	function prepare_variables()
	{
		// @TODO clean up the kindergarten conundrum.
		$variables = array (
				"kStudent",
				"kTeach",
				"stuGrade",
				"narrText",
				"narrTerm",
				"narrSubject",
				"narrGrade",
				"narrYear"
		);
		for($i = 0; $i < count ( $variables ); $i ++) {
			$myVariable = $variables [$i];
			if ($this->input->post ( $myVariable )) {
				if ($myVariable == "stuGrade" && $this->input->post ( $myVariable ) == "") {
					$this->$myVariable = 0;
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
	function get($kNarrative, $include_student = FALSE, $fields = FALSE)
	{

		$this->db->where ( 'kNarrative', $kNarrative );
		if ($fields) {
			$this->db->select ( $fields );
		}
		if ($include_student) {
			$year = get_current_year();
			$this->db->where ( '`narrative`.`kStudent`', '`student`.`kStudent`', FALSE );
			$this->db->select ( "student.stuFirst, student.stuLast, student.stuNickname,(`student`.`baseGrade` + $year - `student`.`baseYear`) as `stuGrade`, student.baseGrade, student.baseYear,narrative.*" );
			$this->db->from ( 'narrative,student' );
		} else {
			$this->db->from ( "narrative" );
		}
		$result = $this->db->get ()->row ();
		return $result;

	}

	function get_multiple($narratives)
	{

		$narrative_list = implode ( ",", $narratives );
		$this->db->where ( "kNarrative in ('$narrative_list')" );
		$this->db->from ( "narrative" );
		$result = $this->db->get ()->result ();
		return $result;

	}

	function get_value($kNarrative, $fieldName)
	{

		$this->db->where ( 'kNarrative', $kNarrative );
		$this->db->select ( $fieldName );
		$this->db->from ( 'narrative' );
		$result = $this->db->get ()->row ();

		return $result->$fieldName;

	}

	function get_for_student($kStudent, $options = array())
	{

		$where [] = "`narrative`.`kStudent` = '$kStudent' AND `teacher`.`kTeach` = `narrative`.`kTeach`";
		$from [] = "narrative";
		$from [] = "teacher";
		if (array_key_exists ( 'narrTerm', $options ) && array_key_exists ( 'narrYear', $options )) {
			$where [] = "narrTerm = '{$options['narrTerm']}' AND narrYear = '{$options['narrYear']}'";
			$where [] = "narrative.kTeach = teacher.kTeach AND narrative.kStudent = student.kStudent";
			$from [] = "student";
		}elseif(array_key_exists('narrYear',$options)){
		    $where[] = sprintf("`narrYear` = '%s'",$options['narrYear']);
		}
		$query = "SELECT narrative.*, `teacher`.`teachFirst`, `teacher`.`teachLast` ";
		$query .= " FROM `" . implode ( "`,`", $from ) . "`";
		$query .= " WHERE " . implode ( " AND ", $where );
		$query .= " ORDER BY `narrYear` DESC,";
		$query .= " CASE WHEN `narrTerm` = 'Year-End' THEN 1 ELSE 2 END";

		// this allows optional sorting of subjects (if the student does well in one subject,
		// the teacher/editors may wish to have it appear before those the student struggles with
		$this->load->model("student_model");
		$student = $this->student_model->get($kStudent);

		$this->load->model("global_subject_model","global_subject");
		$year = get_current_year();
		if(array_key_exists("narrYear",$options)){
			$year = $options['narrYear'];
		}
		$subjects = $this->global_subject->get_by_grade(get_current_grade($student->baseGrade, $student->baseYear,$year),"narratives");
		if (array_key_exists ( 'reportSort', $options )) {
			$subjects = $options ['reportSort'];
		}
		$subjectOrder = str_replace("subject","narrSubject", get_subject_order ( $subjects ));

		$query .= ", $subjectOrder";
		$result = $this->db->query ( $query )->result ();
		return $result;

	}

	/**
	 *
	 * @param
	 *        	$kStudent
	 * @param
	 *        	$narrTerm
	 * @param $narrYear This
	 *        	creates a menu of subjects for which a student has not yet had reports written for the given year and term.
	 */
	function get_current_student_subjects($kStudent, $narrTerm, $narrYear)
	{

		$this->db->where ( 'kStudent', $kStudent );
		$this->db->where ( 'narrTerm', $narrTerm );
		$this->db->where ( 'narrYear', $narrYear );

		$this->db->select ( 'DISTINCT(`narrSubject`)', TRUE );
		$list = $this->db->get ( 'narrative' )->result ();
		if ($list) {
			foreach ( $list as $subject ) {
				$subjects [] = $subject->narrSubject;
			}
			$output = implode ( ",", $subjects );
		} else {
			$output = FALSE;
		}
		return $output;

	}

	/**
	 *
	 * @param
	 *        	$kTeach
	 * @param
	 *        	$subject
	 * @param
	 *        	$gradeStart
	 * @param
	 *        	$gradeEnd
	 * @param
	 *        	$narrTerm
	 * @param $narrYear produces
	 *        	a result of all the students within a grade range for whom no narrative has been written by a given teacher for the term year and subject
	 */
	function get_missing($kTeach, $subject, $gradeStart, $gradeEnd, $narrTerm, $narrYear)
	{

		$options ["kTeach"] = $kTeach;
		$options ["narrSubject"] = $subject;
		$options ["gradeStart"] = $gradeStart;
		$options ["gradeEnd"] = $gradeEnd;
		$options ["narrTerm"] = $narrTerm;
		$options ["narrYear"] = $narrYear;
		return $this->get_narratives ( $options );

	}

	function has_narrative($kStudent, $kTeach, $subject, $narrTerm, $narrYear)
	{

		$this->db->where ( "kStudent", $kStudent );
		$this->db->where ( "kTeach", $kTeach );
		$this->db->where ( "narrSubject", $subject );
		$this->db->where ( "narrTerm", $narrTerm );
		$this->db->where ( "narrYear", $narrYear );
		$this->db->from ( "narrative" );
		$this->db->select ( "kNarrative" );
		$result = $this->db->get ()->result ();
		return $result;

	}

	function get_narratives($options)
	{

		if (array_key_exists ( "kTeach", $options )) {
			$this->db->where ( "narrative.kTeach", $options ["kTeach"] );
		}

		if (array_key_exists ( "gradeStart", $options ) && array_key_exists ( "gradeEnd", $options ) && $options['gradeStart']!="" && $options['gradeEnd'] != "") {
			$this->db->where ( sprintf("narrative.stuGrade BETWEEN %s AND %s",$options ["gradeStart"],  $options ["gradeEnd"]), FALSE, FALSE);
		}

		if (array_key_exists ( "kStudent", $options )) {
			$this->db->where ( "narrative.kStudent", $options ['kStudent'] );
		}
		$narrYear = get_current_year();
		if (array_key_exists ( "narrYear", $options )) {
			$this->db->where ( "narrative.narrYear", $options ["narrYear"] );
			$narrYear = $options['narrYear'];
		} else {
			$this->db->where ( "narrative.narrYear", get_current_year () );
		}

		if (array_key_exists ( "narrTerm", $options )) {
			$this->db->where ( "narrative.narrTerm", $options ["narrTerm"] );
		} else {
			$this->db->where ( "narrative.narrTerm", get_current_term () );
		}

		if (array_key_exists ( "narrSubject", $options )) {
			$this->db->where ( "narrative.narrSubject", $options ["narrSubject"] );
		}

		if (array_key_exists ( "order", $options )) {
			$this->db->order_by ( "order", $options ['order'] );
		} else {
			//$this->db->order_by ( "narrative.stuGrade,student.stuLast,student.stuFirst ASC" );
		}

		$this->db->from ( "narrative,teacher as modifier" );
		$this->db->join ( "student", "narrative.kStudent = student.kStudent", "left" );
		$this->db->where ( "narrative.recModifier = modifier.kTeach" );
		$this->db->select ( "modifier.teachFirst,modifier.teachLast" );
		$this->db->select ( "narrative.*,(`student`.`baseGrade` + $narrYear - `student`.`baseYear`) as `currentGrade`, student.stuFirst, student.stuLast, student.stuNickname" );
		$result = $this->db->get ()->result ();
$this->_log("notice");
		return $result;

	}

	function get_years($kStudent){
	    $this->db->from("narrative,student");
	    $this->db->where("narrative.kStudent",$kStudent);
	    $this->db->group_by("narrYear");
	    $this->db->select("narrYear");
	    $this->db->select("(narrYear - baseYear + baseGrade) as stuGrade");
	    $this->db->order_by("narrYear","DESC");
	    $result = $this->db->get()->result();
	    return $result;
	}

	/**
	 * Setter
	 */
	function insert()
	{

		$this->prepare_variables ();
		$this->db->insert ( 'narrative', $this );
		$kNarrative = $this->db->insert_id ();
		$recModified = $this->get_value ( $kNarrative, 'recModified' );
		return array (
				"kNarrative"=>$kNarrative,
				"timestamp"=>format_timestamp ( $recModified ),
		);

	}

	function update($kNarrative)
	{

		$this->prepare_variables ();
		$this->backup ( $kNarrative );
		$this->kNarrative = $kNarrative;
		$this->db->where ( 'kNarrative', $kNarrative );
		$this->db->update ( 'narrative', $this );
		$recModified = $this->get_value ( $kNarrative, 'recModified' );
		return array (
				"kNarrative"=>$kNarrative,
				"timestamp"=>format_timestamp ( $recModified )
		);

	}

	function delete($kNarrative)
	{

		$this->load->model ( "backup_model" );
		$this->backup_model->backup ( $kNarrative );
		$delete_array ['kNarrative'] = $kNarrative;
		$this->db->delete ( 'narrative', $delete_array );

	}

	function text_replace($search, $replace, $kTeach, $narrYear, $narrTerm, $gradeStart, $gradeEnd)
	{
		if(!empty($search)){
		$this->db->select ( "kNarrative" );


		if ($gradeStart == $gradeEnd) {
			$this->db->where("stuGrade",$gradeStart);
		}else{
			$this->db->where ( sprintf("narrative.stuGrade BETWEEN %s AND %s",$gradeStart,  $gradeEnd), FALSE, FALSE);
		}

		$this->db->where ( "kTeach", $kTeach );
		$this->db->where ( "narrTerm", $narrTerm );
		$this->db->where ( "narrYear", $narrYear );
		$this->db->from ( "narrative" );
		$narrative_list = $this->db->get ()->result ();
		$narratives = array ();
		$count = 0;
		if (count ( $narrative_list ) > 0) {
			foreach ( $narrative_list as $narrative ) {
				$text = $this->get_value ( $narrative->kNarrative, "narrText" );
				$position = strpos ( $text, $search );
				if ($position >= 0) {
					$text = str_replace ( $search, $replace, $text );
					$this->backup ( $narrative->kNarrative );
					$this->update_text ( $narrative->kNarrative, $text );
					$narratives [] = $this->get ( $narrative->kNarrative, TRUE );
					$count ++;
				}
			}
		}

		return array (
				"count" => $count,
				"narratives" => $narratives
		);
		}else{
			return array("count"=>0,
					"narratives"=>FALSE,
			);
			
			
		}

	}

	function update_text($kNarrative, $narrText, $backup = FALSE)
	{

		if ($backup) {
			$this->backup ( $kNarrative );
		}
		$this->db->where ( "kNarrative", $kNarrative );
		$data = array (
				"narrText" => $narrText,
				"recModifier" => $this->session->userdata ( "userID" ),
				"recModified" => mysql_timestamp ()
		);
		$this->db->update ( "narrative", $data );

	}

	function update_value($kNarrative, $field, $value, $backup = FALSE)
	{

		if ($backup) {
			$this->backup ( $kNarrative );
		}
		$values = array (
				$field => $value
		);
		$this->db->where ( "kNarrative", $kNarrative );
		$this->db->update ( "narrative", $values );
		return $this->get_value ( $kNarrative, "narrGrade" );

	}

	function backup($kNarrative)
	{

		$savedText = $this->get_value ( $kNarrative, 'narrText' );
		$this->load->model ( 'backup_model' );
		// see how long ago the last backup happened
		$lastBackup = $this->backup_model->get_last_backup_value ( $kNarrative, 'recModified' );
		$backupDate = strtotime ( $lastBackup );
		$interval = time () - $backupDate;
		$baseInterval = 180; // seconds since last backup

		// compare the current narrative to the backed-up version
		$baseDifference = 99; // percent difference from the saved version
		$difference = $this->backup_model->get_backup_difference ( $kNarrative, $savedText );

		// back up the changes as matches the condition
		if ($difference < $baseDifference && $interval > $baseInterval) {
			$this->backup_model->insert ( $kNarrative );
		}

	}

}