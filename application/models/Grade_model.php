<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Grade_model extends MY_Model
{

    var $kTeach;

    var $kStudent;

    var $kAssignment;

    var $points;

    var $status;

    var $footnote;

    function __construct ()
    {
        parent::__construct();
    }

    /**
     * prepare_variables is a standard script in all models to gether the $_POST
     * values and assign them to the class variables.
     */
    function prepare_variables ()
    {
        $variables = array(
                "kTeach",
                "kStudent",
                "kAssignment",
                "points",
                "status",
                "footnote"
        );
        for ($i = 0; $i < count($variables); $i ++) {
            $myVariable = $variables[$i];
            if ($this->input->post($myVariable)) {
                $this->$myVariable = $this->input->post($myVariable);
            }
        }
    }

    /**
     *
     * @param int $kStudent
     * @param int $kAssignment
     * @return object array
     *         Gets a single row record of a given kStudent kAssignment pair
     */
    function get ($kStudent, $kAssignment)
    {
        $this->db->where("kStudent", $kStudent);
        $this->db->where("assignment.kAssignment", $kAssignment);
        $this->db->join("assignment","grade.kAssignment= assignment.kAssignment");
        $this->db->where("assignment.kAssignment",$kAssignment);
        $this->db->from("grade");
        $this->db->select("grade.*");
        $this->db->select("assignment.points as assignment_total");
        $output = $this->db->get()->row();
        $this->_log("notice");
        return $output;
    }

    /**
     * has_grade
     *
     * @param int $kStudent
     * @param int $kAssignment
     *            determines if a student has a grade for a given assignment.
     */
    function has_grade ($kStudent, $kAssignment)
    {
        $this->db->where("kAssignment", $kAssignment);
        $this->db->where("kStudent", $kStudent);
        $this->db->from("grade");
        $result = $this->db->get()->num_rows();
        return $result;
    }

    /**
     *
     * @param int $kStudent
     * @param varchar $term
     * @param int $year
     *            Does the student have grades for the given term?
     */
    function has_grades ($kStudent, $term, $year)
    {
        $this->db->from("grade");
        $this->db->join("assignment",
                "grade.kAssignment = assignment.kAssignment");
        $this->db->where("kStudent", $kStudent);
        $this->db->where("assignment.term", $term);
        $this->db->where("assignment.year", $year);
        $result = $this->db->get()->num_rows();
    }

    /**
     * batch_insert
     *
     * @param int $kAssignment
     * @param int $kTeach
     * @param varchar $term
     * @param in $year
     *            Finds all the students with a given assignment of the same
     *            term, teacher
     *            and year and creates new records for the student.
     *            the points value is provided by the controller. The UI offers
     *            a choice
     *            to either give the students a starting value of "0" or of the
     *            total points available.
     */
    function batch_insert ($kAssignment, $kTeach, $term, $year, $grade_start,
            $grade_end, $points)
    {
        $this->db->select("distinct(`grade`.`kStudent`)");
        $this->db->from("assignment");
        $this->db->join("grade", "grade.kAssignment = assignment.kAssignment",
                "LEFT");
        $this->db->join("student", "grade.kStudent = student.kStudent");
        $this->db->where(
                "((student.baseGrade + $year - student.baseYear) BETWEEN $grade_start AND $grade_end)");
        $this->db->where("assignment.kTeach", $kTeach);
        $this->db->where("term", $term);
        $this->db->where("year", $year);
        $this->db->where("grade.kStudent IS NOT NULL");
        $this->db->where("grade.kAssignment !=$kAssignment");
        $students = $this->db->get()->result();
        $this->load->model("subject_model", "subject");
        $subjects = $this->subject->get_for_teacher($kTeach);
        if (empty($students)) {
            $this->load->model("student_model", "student");

            if (get_value($subjects[0], "subject") != "Humanities") {
                $students = $this->student->get_all($year,
                        array("grades"=>array($grade_start,$grade_end),
                        ));
            } else {

                $students = $this->student->get_students_by_grade($grade_start,
                        $grade_end, array(
                                "humanitiesTeacher" => $kTeach
                        ));
                $this->_log("notice");

            }
        }

        foreach ($students as $student) {
            $this->update($student->kStudent, $kAssignment, $points, NULL, NULL);
        }
        return $students;
    }

    /**
     *
     * @param int $kAssignment
     * @param double $percentage
     *            update all student grades for a given assignment based on the
     *            percentage change.
     *            In the UI if a teacher changes the number of points for a
     *            given assignment
     *            this script is called to automatically update all the points
     *            proportionally
     */
    function batch_adjust_points ($kAssignment, $percentage)
    {
        $this->db->query(
                "UPDATE `grade` SET `points` = `points` * $percentage WHERE `kAssignment` = $kAssignment");
    }

    /**
     *
     * @param int $kStudent
     * @param int $kAssignment
     * @param double $points
     * @param varchar $status
     * @param int $footnote
     * @return boolean or insert id
     *         updates and also inserts (if the kStudent-kAssignment pair is not
     *         present in the table).
     *         I suppose I could have used a REPLACE INTO function and made the
     *         kStudent kAssignment pair
     *         a table key, but I did not.
     */
    function update ($kStudent, $kAssignment, $points = NULL, $status = 0, $footnote = 0)
    {
        $output = FALSE;
        $data = array(
                "points" => $points?$points:0,
                "status" => $status?$status:0,
                "footnote" => $footnote?$footnote:0,
        );
        if ($this->has_grade($kStudent, $kAssignment) > 0) {
            $this->db->where("kAssignment", $kAssignment);
            $this->db->where("kStudent", $kStudent);
            $this->db->update("grade", $data);
            $output = TRUE;
        } else {
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
     *         update a grade value based on kStudent and kAssignment
     */
    function update_value ($kStudent, $kAssignment, $key, $value)
    {
        $output = FALSE;
        $output = $this->has_grade($kStudent, $kAssignment);
        if ($this->has_grade($kStudent, $kAssignment) == 1) {
            $this->db->where("kStudent", $kStudent);
            $this->db->where("kAssignment", $kAssignment);
            $data = array(
                    $key => $value
            );
            if ($this->db->update("grade", $data)) {
                $output = $this->get($kStudent, $kAssignment)->$key;
            } else {
                $output = FALSE;
            }
        }
        return $output;
    }

    /**
     *
     * @param int $kStudent
     * @param varchar $term
     * @param int $year
     * @param
     *            options array optional expects cutoff_date: standard US date
     *            (mm-dd-yyyy) format converted in script to mysql
     * @return object get a distinct list of subjects for a student for the
     *         term, year and optional cutoff date.
     */
    function get_subjects ($kStudent, $term, $year, $options = array())
    {
        if (array_key_exists('cutoff_date', $options)) {
            $this->db->where(
                    sprintf("`assignment`.`date` <= '%s'",
                            $options['cutoff_date']));
        }
        $subject_sort = 'subject';
        $this->load->model("subject_sort_model", "subject_sort");

        if ($this->subject_sort->has_sort($kStudent, $term, $year, "grades")) {
            $subject_sort = get_subject_order(
                    $this->subject_sort->get_sort($kStudent, $term, $year,
                            "grades"));
        } else {
            $this->load->model("global_subject_model", "global_subject");
            $this->load->model("student_model");
            $student = $this->student_model->get($kStudent,
                    "baseGrade,baseYear");
            $stuGrade = get_current_grade($student->baseGrade,
                    $student->baseYear, $year);
            $subject_sort = get_subject_order(
                    $this->global_subject->get_by_grade($stuGrade, "grades"));
        }
        $query = sprintf(
                "SELECT `subject` FROM (`grade`) LEFT JOIN `assignment` ON `grade`.`kAssignment`=`assignment`.`kAssignment` WHERE `grade`.`kStudent` = '%s' GROUP BY `subject` ORDER BY %s",
                $kStudent, $subject_sort);
        $result = $this->db->query($query)->result();
        return $result;
    }

    /**
     * Collect the list of current students for a teacher in preparation for
     * creating all grade reports.
     *
     * @param int $kTeach
     * @param string $term
     * @param int $year
     * @param int $gradeStart
     * @param int $gradeEnd
     * @param string $cutoff_date
     */
    function get_reports ($kTeach, $term, $year, $gradeStart, $gradeEnd,
            $cutoff_date = NULL)
    {
        $this->db->select(
                "DISTINCT(student.kStudent) as kStudent, student.stuLast,student.stuFirst,student.stuNickname");
        $this->db->from("grade");
        $this->db->join("student", "grade.kStudent = student.kStudent");
        $this->db->join("assignment",
                "grade.kAssignment = assignment.kAssignment");
        $this->db->where("assignment.kTeach", $kTeach);
        $this->db->where("assignment.term", $term);
        $this->db->where("assignment.gradeStart", $gradeStart);
        $this->db->where("assignment.gradeEnd", $gradeEnd);
        if ($cutoff_date) {
            $this->db->where("assignment.date <= '$cutoff_date'");
        }
        $this->db->order_by("student.stuLast");
        $this->db->order_by("student.stuFirst");
        $result = $this->db->get()->result();
        return $result;
    }

    /**
     *
     * @param unknown $kStudent
     * @param unknown $kTeach
     * @param unknown $term
     * @param unknown $year
     */
    function delete_row ($kStudent, $kTeach, $term, $year)
    {
        if ($kTeach && $kStudent && $term && $year) {
            $query = sprintf(
                    "DELETE grade FROM grade, assignment WHERE `grade`.`kAssignment` = `assignment`.`kAssignment` AND `assignment`.`kTeach` = '%s'
					AND `grade`.`kStudent` = '%s' AND `assignment`.`term` = '%s' AND `assignment`.`year` = '%s'",
                    $kTeach, $kStudent, $term, $year);
            $this->db->query($query);
        }
    }
}