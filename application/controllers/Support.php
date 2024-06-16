<?php
if (!defined('BASEPATH'))
    exit ('No direct script access allowed');

class Support extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model("support_model");
    }

    function list_all($kStudent = FALSE, $errors = NULL)
    {
        $this->load->model("student_model");
        $this->load->model("course_preference_model", "preference");

        $student = $this->student_model->get($kStudent);
        $data ['kStudent'] = $kStudent;
        $data ['student'] = $student;
        $data ['student_name'] = format_name($student->stuFirst, $student->stuLast, $student->stuNickname);
        $has_current = $this->support_model->get_current($kStudent);
        $data ['has_current'] = get_value($has_current, "year");
        $data ['title'] = sprintf("Friends School of Minnesota<br/>Student Support (504 Plan) Report for %s", $data ['student_name']);
        $support = $this->support_model->get_all($kStudent);
        foreach ($support as $entry) {

            $entry->course_preferences = $this->preference->get_all($entry->kStudent, array(
                "school_year" => $entry->year
            ));
            $entry->testDates = $this->support_model->get_test_dates($entry->kStudent);
        }
        $data ['support'] = $support;
        $data ['target'] = "support/list";
        $this->load->model("file_model");
        $data ['support_files'] = $this->file_model->get_for_student($kStudent);
        $data ['errors'] = $errors;
        $this->load->view("page/index", $data);
    }

    function view($kSupport)
    {
        $data ['print'] = FALSE;
        $data ['sidebar'] = FALSE;
        // @TODO change this to queries instead of uri segments.
        if ($this->uri->segment(4) == "print") {
            $data ['print'] = TRUE;
        }
        if ($this->uri->segment(4) == "sidebar") {
            $data ['sidebar'] = TRUE;
        }
        $support = $this->support_model->get($kSupport);
        $student = format_name($support->stuFirst, $support->stuLast, $support->stuNickname);
        $this->load->model("file_model");
        $data ['support_files'] = $this->file_model->get_all($kSupport);
        $options = array(
            "school_year" => get_current_year()
        );
        $this->load->model("course_preference_model", "preference");
        $support->course_preferences = $this->preference->get_all($support->kStudent, $options);
        $support->testDates = $this->support_model->get_test_dates($support->kStudent);
        $support->student = $student;
        $data ['student'] = $student;
        $data ['entry'] = $support;

        $data ['title'] = "Learning Support Documentation (504 Plan) for $student";
        $data ['target'] = "support/view";
        if ($data ['sidebar']) {
            $this->load->view("support/sidebar", $data);
        } else {
            $this->load->view("page/index", $data);
        }
    }

    function create($kStudent, $year = NULL)
    {
        if ($kStudent) {
            $this->load->model("student_model");
            if ($year) {
                $data ['year'] = $year;
            } else {
                $data ['year'] = get_current_year();
            }
            $data['scripts'] = ['ckeditor.js', 'editor.js'];
            $data ['action'] = "insert";
            $data ['support'] = $this->student_model->get($kStudent);
            $data ['title'] = "Add Student Support Documentation";
            $data ['support_files'] = false;
            $data ['year_list'] = get_year_list(FALSE, TRUE);
            $data ['target'] = "support/edit";
            $this->load->view("page/index", $data);
        }
    }

    function insert()
    {
        if ($this->input->post("kStudent")) {
            $kSupport = $this->support_model->insert();
            $kStudent = $this->input->post("kStudent");
            if ($this->input->post("ajax") == 1) {
                echo $kSupport;
            } else {
                redirect("support/list_all/$kStudent");
            }
        }
    }

    function edit($kSupport)
    {
        if ($kSupport) {
            $data['scripts'] = ['ckeditor.js', 'editor.js'];
            $data ['action'] = "update";
            $support = $this->support_model->get($kSupport);
            $support->testDates = $this->support_model->get_test_dates($support->kStudent);

            $data ['support'] = $support;
            $data ['title'] = "Editing Student Support";
            $this->load->model("file_model");
            $data ['support_files'] = $this->file_model->get_all($kSupport);
            $data ['year_list'] = get_year_list(FALSE, TRUE);
            $data ['target'] = "support/edit";
            $this->load->view("page/index", $data);
        }
    }

    function update()
    {
        if ($this->input->post("kSupport")) {
            $kSupport = $this->input->post("kSupport");
            $kStudent = $this->input->post("kStudent");
            $result = $this->support_model->update($kSupport);
            if ($this->input->post("ajax") != 1) {
                redirect("support/list_all/$kStudent");
            } else {
                echo $result;
            }
        }
    }

    function delete()
    {
        if ($this->input->post("kSupport")) {
            $kSupport = $this->input->post("kSupport");
            $kStudent = $this->input->post("kStudent");
            $this->support_model->delete($kSupport);
            redirect("support/list_all/$kStudent");
        }
    }

    /**
     * * FILE MANAGEMENT **
     */
    function new_file()
    {
        if ($this->input->post('kSupport')) {
            $data ['kSupport'] = $this->input->post('kSupport');
            $data ['kStudent'] = $this->input->post('kStudent');
            $data ['error'] = '';
            $data ['file'] = null;
            $this->load->view('support/file', $data);
        }
    }

    function attach_file()
    {
        $config ['upload_path'] = './uploads';
        $this->load->helper('directory');
        $config ['allowed_types'] = 'gif|jpg|png|pdf|rtf|PDF|JPG|JPEG|RTF|doc|docx|DOC|DOCX';
        $config ['max_size'] = '0'; // rely on PHP's built-in limit
        $config ['max_width'] = '0';
        $config ['max_height'] = '0';

        $this->load->library('upload', $config);
        $kStudent = $this->input->post('kStudent');

        if (!$this->upload->do_upload()) {
            $error = array(
                'error' => $this->upload->display_errors()
            );
            $this->session->set_flashdata("warning", $error ['error']);
            $kSupport = $this->input->post("kSupport");
            redirect("support/edit/$kSupport");

            // $this->list_all($kStudent,$error);
        } else {
            $file_data = $this->upload->data();
            $data ['file_display_name'] = $file_data ['file_name'];
            $data ['file_description'] = $this->input->post('file_description');
            $this->load->model("file_model");
            $kStudent = $this->input->post('kStudent');
            $kSupport = $this->input->post("kSupport");
            $kFile = $this->file_model->insert($kSupport, $file_data);
            redirect("support/edit/$kSupport");
        }
    }

    function delete_file()
    {
        $kFile = $this->input->post("kFile");
        $this->load->model("file_model");
        $kSupport = $this->input->post("kSupport");
        $this->file_model->delete($kFile);
        redirect("support/edit/$kSupport");
    }
}