<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subject_model extends CI_Model
{
	var $subject = "";
	var $gradeStart = "";
	var $gradeEnd = "";

	function __construct()
	{

		parent::__construct();

	}

	function prepare_variables()
	{

		if($this->input->post('subject')){
			$this->subject = $this->input->post('subject');
		}

		if($this->input->post('gradeStart')){
			$this->gradeStart = $this->input->post('gradeStart');
		}

		if($this->input->post('gradeEnd')){
			$this->gradeEnd = $this->input->post('gradeEnd');
		}

	}


	function get_all($gradeRange = array())
	{
		if($gradeRange){
			if(array_key_exists("gradeStart", $gradeRange) && array_key_exists("gradeEnd", $gradeRange)){
				$this->db->or_where("subject.gradeStart BETWEEN " . $gradeRange["gradeStart"] . " AND " . $gradeRange["gradeEnd"]);
				$this->db->or_where("subject.gradeEnd BETWEEN " . $gradeRange["gradeStart"] . " AND " . $gradeRange["gradeEnd"]);
				//$this->db->or_where("subject.gradeStart <= " . $gradeRange["gradeStart"]);
				//$this->db->or_where("subject.gradeEnd <= " . $gradeRange["gradeEnd"]);
			}
		}
		$this->db->order_by('subject.subject','ASC');
		$this->db->from('subject');
		$result = $this->db->get()->result();
		return $result;

	}
	
	
	function get_for_teacher($kTeach){
		$this->db->where("kTeach",$kTeach);
		$this->db->from("teacher_subject");
		$result = $this->db->get()->result();
		return $result;
	}
	
	
   function get_missing($kTeach, $gradeRange = array())
    {
        $subjects = $this->get_all();
        $teacherSubjects = $this->get_for_teacher($kTeach);

        $subjectList = array();
        
        foreach($teacherSubjects as $subject){
            $subjectList[] = $subject->subject;
        }
        
        $missingSubjects = array();
        foreach($subjects as $subject){
            $hasMatch = in_array($subject->subject,$subjectList);
            if(!$hasMatch){
                $missingSubjects[$subject->subject] = $subject->subject;
            }
        }
        return $missingSubjects;
    }

    
	function get_by_grade($grade, $format = FALSE){
		$this->db->select('subject');
		$this->db->from('subject');
		$this->db->where("$grade BETWEEN gradeStart AND gradeEnd",NULL,FALSE);
		$result = $this->db->get()->result();

		if($format == "string"){
			foreach($result as $subject){
				$list[] = $subject->subject;
			}
			$output = implode(",", $list);
		}else{
			$output = $result;
		}
		return $result;
	}




	function update($kSubject)
	{

		$this->prepare_variables();
		$this->db->where('kSubject', $kSubject);
		$this->from('subject');
		$this->db->update();


	}


	function insert()
	{
		$this->prepare_variables();
		$this->from('subject');
		$this->db->insert();
		return $this->db->insert_id();
	}

	
	
	function get_id($subject){
		$this->db->where('subject', $subject);
		$this->db->select('kSubject');
		$this->db->from('subject');
		$result = $this->db->get()->row();
		return $result->kSubject;
	}
	
	

	function get_name($kSubject){
		$this->db->where('kSubject', $kSubject);
		$this->db->select('subject');
		$this->db->from('subject');
		$result = $this->db->get()->row();
		return $result->subject;
	}
}