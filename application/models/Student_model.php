<?php
if (! defined('BASEPATH')) exit('No direct script access allowed');

class Student_model extends MY_Model
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
    var $humanitiesTeacher;
    var $isEnrolled;
    var $isGraduate;
    var $stuEmail;
    var $stuEmailPermission;
    var $stuEmailPassword;
    var $recModified;
    var $recModifier;

    function __construct ()
    {
        parent::__construct();
    }

    function prepare_variables ()
    {
        $variables = array(
                'kTeach',
                'stuFirst',
                'stuLast',
                'stuNickname',
                'stuGender',
                'stuDOB',
                'baseGrade',
                'baseYear',
                'humanitiesTeacher',
                'isEnrolled',
                'isGraduate',
                'stuEmail',
                'stuEmailPermission',
                'stuEmailPassword',
                'stuGroup'
        );
        for ($i = 0; $i < count($variables); $i ++) {
            $myVariable = $variables[$i];
            if ($this->input->post($myVariable)) {
                $this->$myVariable = $this->input->post($myVariable);
            }
        }

        $this->recModified = mysql_timestamp();
        $this->recModifier = $this->session->userdata('userID');
    }

    /**
     * Getter
     */
    function get ($kStudent, $fields = NULL, $include_teacher = FALSE)
    {
        $this->db->where('kStudent', $kStudent);
        $this->db->from('student');
        if ($fields) {
            $this->db->select($fields);
        } else {
            $this->db->select("student.*,(`baseGrade`+" . get_current_year() . "-`baseYear`) as stuGrade");
        }
        $this->db->join("teacher", "teacher.kTeach=student.kTeach");
        $this->db->select("teacher.teachFirst, teacher.teachLast");
        $this->db->join("teacher as humanitiesTeacher", "student.humanitiesTeacher = humanitiesTeacher.kTeach", "LEFT");
        $this->db->select("humanitiesTeacher.teachFirst as humanitiesFirst,humanitiesTeacher.teachLast as humanitiesLast");

        $result = $this->db->get()->row();
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    function get_value ($kStudent, $fieldName)
    {
        $this->db->where('kStudent', $kStudent);
        if (is_array($fieldName)) {
            foreach ($field as $fieldName) {
                $this->db->select($field);
            }
        }
        $this->db->from('student');
        $result = $this->db->get()->row();
        return $result->$fieldName;
    }

    function find_students ($stuName)
    {
        $this->load->model("preference_model", "preference");
        $include_former_students = $this->preference->get($this->session->userdata("userID"), "show_former_students");
        if ($include_former_students != "yes") {
            $this->db->where("isEnrolled", 1);
        }
        $this->db->where("(CONCAT(`stuFirst`,' ', `stuLast`) LIKE '%$stuName%' OR CONCAT(`stuNickname`,' ', `stuLast`) LIKE '%$stuName%')");
        $this->db->order_by("stuFirst", "ASC");
        $this->db->order_by("stuLast", "ASC");
        $year = get_current_year();
        $this->db->select("student.*,(baseGrade+$year-baseYear) AS listGrade");

        $result = $this->db->get("student")->result();
        return $result;
    }

    function count ($field_name, $field_value, $where = FALSE)
    {
        $this->db->select("COUNT(`$field_name`) AS `$field_name`");
        $this->db->where($field_name, $field_value);
        if ($where) {
            if (is_array($where)) {
                $where_keys = array_keys($where);
                $where_values = array_values($where);
                for ($i = 0; $i < count($where); $i ++) {
                    $key = $where_keys[$i];
                    $value = $where_values[$i];
                    $this->db->where("$key != $value");
                }
            }
        }

        $this->db->from("student");
        $output = $this->db->get()->row();
        return $output->$field_name;
    }

    /**
     *
     * @param $kTeach int This returns the students assigned to either a
     *        classroom
     *        teacher or middle school
     *        advisor depending on the grade of the student
     *
     */
    function get_students_by_class ($kTeach, $grade_range = array())
    {
        $this->db->where('student.isEnrolled', 1);
        $this->db->where('student.kTeach', $kTeach);
        $this->db->where("`student`.`kTeach`=`teacher`.`kTeach`");
        $this->db->order_by("stuGrade", "ASC");
        $this->db->order_by('stuLast', 'ASC');
        $this->db->order_by('stuFirst', 'ASC');
        $this->db->from('student');
        $this->db->from("teacher");
        $this->db->select("*");
        $year = get_current_year();
        $this->db->select(sprintf("($year- `baseYear`  + `baseGrade`) as `stuGrade`", get_current_year()));
        $result = $this->db->get()->result();
        return $result;
    }


    /**
     * Lists enrolled students by grade with added constraints.
     * The constraints array can contain kTeach, and a select variable with
     * a list of fields to include in the result
     *
     * @param int $gradeStart
     * @param int $gradeEnd
     * @param array $constraints
     */
    function get_students_by_grade ($gradeStart, $gradeEnd, $constraints = array())
    {
    	$year = get_current_year();
    	 
        if ($gradeStart == $gradeEnd) {
            $this->db->where("(`baseGrade` -`baseYear` + $year) = $gradeStart", FALSE,FALSE);
        } else {
            $this->db->where("(baseGrade - baseYear  + $year) BETWEEN  $gradeStart AND $gradeEnd",FALSE,FALSE);
        }

        if (array_key_exists("kTeach", $constraints)) {
            $this->db->where("kTeach", $constraints["kTeach"]);
        } elseif (array_key_exists("humanitiesTeacher", $constraints)) {
            $this->db->where("humanitiesTeacher", $constraints['humanitiesTeacher']);
        }
        
        if(array_key_exists("stuGroup",$constraints)){
        	$this->db->where("stuGroup",$constraints['stuGroup']);
        }
        // @TODO need to have an override here to allow this to fork depending
        // on the calling method
        // some situations may require showing students by grade to include
        // enrolled students.
        $this->db->where("isEnrolled", 1);
        if (array_key_exists("select", $constraints)) {
            $this->db->select($constraints["select"]);
        } else {
            $this->db->select("student.*");
            $this->db->select("`baseGrade` - `baseYear` + $year as `stuGrade`",FALSE);
        }
        $this->db->order_by("stuGrade");
        $this->db->order_by("stuLast");
        $this->db->order_by("stuFirst");

        $this->db->from("student");
        $result = $this->db->get()->result();
        $this->_log();
        return $result;
    }

    
    /**
     * 
     * @param int $year
     * @param array $options
     * options include 
     * grades
     * includeFormerStudents
     * stuGroup (middle school)
     * kTeach (advisor or classroom teacher)
     * humanitiesTeacher (kTeach)
     * hasNeeds (has special needs/accommodations)
     * sorting (first_last or last_first);
     * 
     */
    
    function get_all($year, $options = array()){
    	
    	
    	$this->db->from("student");
    	 
    	if (array_key_exists("grades",$options)) {
    		$this->db->where_in("`baseGrade`+$year-`baseYear`", $options["grades"],FALSE);
    	} else {
    		$this->db->where_in("`baseGrade` + $year - `baseYear`",array(0,1,2,3,4,5,6,7,8),FALSE,FALSE);
    	}
    	
    	if (array_key_exists("includeFormerStudents",$options) && $options["includeFormerStudents"] == 1) {
    		$this->db->where_in("isEnrolled", array(
    				0,
    				1
    		));
    	} else {
    		$this->db->where("(`isEnrolled` = 1 OR `isGraduate` = 1)", NULL, FALSE);
    	}
    	
    	if(array_key_exists("stuGroup",$options)){
    		$this->db->where("stuGroup",$options["stuGroup"]);
    	}
    	
    	if(array_key_exists("kTeach",$options) && !empty($options['kTeach'])){
    		$this->db->where("student.kTeach",$options['kTeach']);
    		$this->db->order_by("teacher.teachFirst");
    		 
    		 
    	}
    	
    	$this->db->join("teacher","student.kTeach=teacher.kTeach");
    	 
    	if(array_key_exists("humanitiesTeacher",$options)){
    		$this->db->where("student.humanitiesTeacher",$options['humanitiesTeacher']);
    		$this->db->order_by("humanitiesTeacher.teachFirst");
    		$humanitiesTeacher = $options['humanitiesTeacher'];
    		$this->db->join("teacher as humanitiesTeacher","student.humanitiesTeacher = humanitiesTeacher.kTeach");
    		
    	}else{
    		$this->db->join("teacher as humanitiesTeacher","student.humanitiesTeacher = humanitiesTeacher.kTeach","LEFT");
    	}
    	
    	
    	if(array_key_exists("hasNeeds",$options)){
    		$this->db->join("support", "student.kStudent = support.kStudent");
            $this->db->group_by("support.kStudent");
    	}
    	
    	if(array_key_exists("class_list",$options)){
    		$this->db->order_by("teacher.gradeStart");
    		$this->db->order_by("teacher.teachClass");
    	}
    	$this->db->order_by("stuGrade", "ASC");
    	
    	if(array_key_exists("sorting",$options)){
    		if($options["sorting"] == "last_first"){
    			$this->db->order_by("student.stuLast");
    			$this->db->order_by("student.stuNickname");
    			$this->db->order_by("student.stuFirst");
    		}elseif($options["sorting"] == "first_last"){
    			$this->db->order_by("student.stuNickname");
    			$this->db->order_by("student.stuFirst");
    			$this->db->order_by("student.stuLast");
    		}
    	}else{//by default search by first name/last name
    		$this->db->order_by("student.stuNickname");
    		$this->db->order_by("student.stuFirst");
    		$this->db->order_by("student.stuLast");
    	}
    	
    	$this->db->select("student.*,(baseGrade+$year-baseYear) AS stuGrade",TRUE);
        $this->db->select("CONCAT(teacher.teachFirst, ' ' , teacher.teachLast) as teacherName",FALSE);
        $this->db->select("teacher.teachClass");
        $this->db->select("CONCAT(humanitiesTeacher.teachFirst, ' ' ,humanitiesTeacher.teachLast) as humanitiesTeacher",FALSE);
        $this->db->group_by("student.kStudent");
    	$result = $this->db->get()->result();
    	$this->_log();
    	return $result;
    }


    function get_grade ($kStudent, $narrYear = NULL)
    {
        $baseGrade = $this->get_value($kStudent, 'baseGrade');
        $baseYear = $this->get_value($kStudent, 'baseYear');
        return get_current_grade($baseGrade, $baseYear, $narrYear);
    }

    function get_name ($kStudent)
    {
        $student = $this->get($kStudent, 'stuFirst,stuLast,stuNickname');
        $output = format_name($student->stuFirst, $student->stuLast, $student->stuNickname);
        return $output;
    }

    /**
     * Does the student have records in the system in the narratives, grades,
     * etc?
     * This is used to determine if a student record can be deleted or not.
     * Student records cannot be deleted if there are dependent records
     * elsewhere
     *
     * @param int $kStudent
     */
    function has_records ($kStudent)
    {
        $output = 0;
        $tables = array(
                "narrative",
                "grade",
                "support"
        );
        foreach ($tables as $table) {
            $this->db->from($table);
            $this->db->where("kStudent", $kStudent);
            $output += $this->db->count_all_results();
        }
        return $output;
    }

    /**
     * Setter
     */
    function insert ()
    {
        $this->prepare_variables();
        $this->db->insert('student', $this);
        return $this->db->insert_id();
    }

    function update ($kStudent)
    {
        $this->prepare_variables();
        $this->kStudent = $kStudent;
        $this->db->where('kStudent', $kStudent);
        $this->db->update('student', $this);
    }

    function update_value ($kStudent, $data)
    {
        $this->db->where('kStudent', $kStudent);
        $this->db->update('student', $data);
    }

    function update_grades ()
    {
    }

    /**
     * Delete student record only if the student has no entries in other tables.
     *
     * @param number $kStudent
     * @return comma-separated string with initial boolean and message--used by
     *         javascript to determine the alert and response.
     *         @TODO maybe develop a set of generic database key->string pairs
     *         for messages?
     */
    function delete ($kStudent)
    {
        $output = "0,You do not have permission to delete student records. Please contact the system administrator for assistance";
        if ($this->session->userdata("dbRole") == 1) {
            if ($this->has_records($kStudent) == 0) {
                $this->db->delete('student', array(
                        'kStudent' => $kStudent
                ));
                $output = "1,The student record was successfully deleted";
            } else {
                $output = "0,The student record has multiple dependent entries in other tables and cannot be deleted";
            }
        }
        return $output;
    }
}