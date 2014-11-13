<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Grade_preference extends MY_Controller
{

    function __construct ()
    {
        parent::__construct();
        $this->load->model("grade_preference_model", "preference");
    }

    function edit ()
    {
        $id = $this->input->get("id");
        $data["action"] = "update";
        $data["preference"] = $this->preference->get($id);
        $data["kStudent"] = $data["preference"]->kStudent;
        $this->load->model("student_model", "student");
        $grade = $this->student->get_grade($data["kStudent"]);
        $this->load->model("subject_model", "subject");
        $subject_list = $this->subject->get_by_grade($grade);
        $data["subjects"] = get_keyed_pairs($subject_list, array(
                "subject",
                "subject"
        ));
        $this->load->view("grade_preference/edit", $data);
    }

    function create ()
    {
        $kStudent = $this->input->get("kStudent");
        $data["preference"] = "";
        $data["kStudent"] = $kStudent;

        $this->load->model("student_model", "student");
        $grade = $this->student->get_grade($kStudent);
        $this->load->model("subject_model", "subject");
        $subject_list = $this->subject->get_by_grade($grade);
        $data["subjects"] = get_keyed_pairs($subject_list, array(
                "subject",
                "subject"
        ));
        $data["action"] = "insert";
        $this->load->view("grade_preference/edit", $data);
    }

    function view ()
    {
        $kStudent = $this->input->get("kStudent");
        $data["kStudent"] = $kStudent;
        $options = array();
        $options["school_year"] = NULL;
        $options["subject"] = NULL;
        if ($this->input->get("school_year")) {
            $options["school_year"] = $this->input->get("school_year");
        }
        if ($this->input->get("subject")) {
            $options["subject"] = $this->input->get("school_year");
        }
        $data["preferences"] = $this->preference->get_all($kStudent, $options);
        $data["target"] = "grade_preference/list";
        $data["title"] = "grade preferences";
        $this->load->view("page/index", $data);
    }

    function insert ()
    {
        $this->update();
    }

    function update ()
    {
        $kStudent = $this->input->post("kStudent");
        $data["kStudent"] = $kStudent;
        $data["subject"] = $this->input->post("subject");
        $data["school_year"] = $this->input->post("school_year");
        $data["pass_fail"] = $this->input->post("pass_fail");
        $this->preference->update($data);
        redirect("student/view/$kStudent");
    }

    function delete ()
    {
        $id = $this->input->post("id");
        $kStudent = $this->preference->delete($id);
        if (!$this->input->post("ajax")) {
            redirect("student/view/$kStudent");
        }
    }
}