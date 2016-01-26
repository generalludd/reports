<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		//@TODO consider loading classes as needed within functions
		$this->load->model("template_model");
		$this->load->model("narrative_model");
		$this->load->helper("template");
	}

	/**
	 * initiates the creation of a new narrative by allowing the teacher/editor
	 * to select a subject and thus list templates available to apply to a new narrative
	 */
	function show_selector()
	{
		$this->load->model("student_model");
		$kTeach = $this->input->get_post("kTeach");
		$kStudent = $this->input->get_post("kStudent");
		$options = array();
		if($this->input->get_post("term")  != ""){
			$options["where"]["term"] = $this->input->get_post("term");
		}else{
			$options["where"]["term"] = get_current_term();
		}

		if($this->input->get_post("year") != ""){
			$options["where"]["year"] = $this->input->get_post("year");
		}else{
			$options["where"]["year"] = get_current_year();
		}

		if($this->input->get_post("subject")){
			$options["where"]["subject"] = $this->input->get_post("subject");
		}else{
			$options["where"]["subject"] = $this->input->cookie("narrative_subject");//$this->session->userdata("narrative_subject");
		}
		$options["stuGrade"] = $this->student_model->get_grade($kStudent);
		$studentName = $this->student_model->get_name($kStudent);
		$data["templates"] = $this->template_model->get_all($kTeach, $options);
		$data["stuGrade"] = $options["stuGrade"];
		$data["kTeach"] = $kTeach;
		$data["kStudent"] = $kStudent;
		$data["narrTerm"] = $options["where"]["term"];
		$data["narrYear"] = $options["where"]["year"];
		$data["narrSubject"] = $options["where"]["subject"];
		$data["studentName"] = $studentName;
		$data["title"] = "Creating a Narrative for $studentName";
		$data["target"] = "template/select";
		if($this->input->post("ajax")){
			$this->load->view($data["target"], $data);
		}else{
			$this->load->view("page/index", $data);
		}
	}

/**
 * list templates for a given teacher based on input data queries
 * kTeach, term, year, subject, gradeStart, gradeEnd, include_inactive, and type. 
 * Only kTeach is mandatory. 
 */
	function list_templates()
	{
		$this->load->model("teacher_model");
		$kTeach = $this->input->get_post("kTeach");
		$options = array();
		if($this->input->get_post("term") != ""){
			$options["where"]["term"] = $this->input->get_post("term");
		}

		if($this->input->get_post("year") != "0"){
			$options["where"]["year"] = $this->input->get_post("year");
		}

		if($this->input->get("subject") != NULL){
			$options["where"]["subject"] = $this->input->get_post("subject");
			//$this->session->set_userdata("template_subject",$options["where"]["subject"]);
			bake_cookie("template_subject",$options["where"]["subject"]);
				
		}

		if($this->input->get_post("type") != ""){
			$options["where"]["type"] = $this->input->get_post("type");
		}

		if($this->input->get_post("gradeStart") && $this->input->get_post("gradeEnd")){
			$options["grade_range"]["gradeStart"] = $this->input->get_post("gradeStart");
			$options["grade_range"]["gradeEnd"] = $this->input->get_post("gradeEnd");
		}

		$include_inactive = FALSE;
		if($this->input->get_post("include_inactive")){
			$include_inactive = TRUE;
		}
		
		$data["templates"] = $this->template_model->get_all($kTeach,$options, $include_inactive);
		$data["kTeach"] = $kTeach;
		$data["teacher"] = $this->teacher_model->get_name($kTeach);
		$data["target"] = "template/list";
		$data["title"] = "Listing Subject Templates for " . $data["teacher"];
		if($include_inactive){
			$options["Include Inactive Templates"] = "Yes";
		}
		$data["options"] = $options;
		$this->load->view("page/index", $data);

	}

	/**
	 * search dialog for finding templates. 
	 * This redirects on submit to the above list_templates() function
	 */
	function search()
	{
		$this->load->model("subject_model");
		$this->load->model("teacher_model");
		$this->load->model("menu_model");
		$kTeach = $this->uri->segment(3);
		$data["kTeach"] = $kTeach;
		$grades = $this->menu_model->get_pairs("grade");
		$data["grade_list"] = get_keyed_pairs($grades,array("value","label"));
		$data["years"] = get_year_list(TRUE);
		$subjects = $this->subject_model->get_for_teacher($kTeach);
		$data["subject"] = $this->input->cookie("template_subject");//$this->session->userdata("template_subject");
		$data["subjects"] = get_keyed_pairs($subjects, array("subject","subject"),TRUE);
		$data["grades"] = $this->teacher_model->get($kTeach,array("gradeStart","gradeEnd"));
		$this->load->view("template/search", $data);
	}

/**
 * Edit templates based on a uri segment template id (kTemplate)
 */
	function edit()
	{
		$this->load->model("teacher_model");

		$this->load->model("menu_model");
		$kTemplate = $this->uri->segment(3);
		$this->load->model("subject_model");
		$template = $this->template_model->get($kTemplate);
		$data["template"] = $template;
		$data["kTeach"] = $template->kTeach;
		$subjects = $this->subject_model->get_for_teacher($template->kTeach);
		$data["subjects"] = get_keyed_pairs($subjects, array("subject","subject"),FALSE);
		$data["years"] = get_year_list();
		$grades = $this->menu_model->get_pairs("grade");
		$data["gradeStart"] = "";
		$data["gradeEnd"] = "";
		$data["grade_list"] = get_keyed_pairs($grades,array("value","label"));
		$data['rich_text'] = TRUE;
		$data["target"] = "template/edit";
		$data["action"] = "update";
		$data["title"] = "Editing a Subject Template";
		$this->load->view("page/index", $data);

	}

	/**
	 * create a new template based on the uri $kTeach value
	 * shows a new form prepopulated with basic information including the teacher's
	 * grade range taught, available subjects, and kTeach
	 */
	function create()
	{
		$kTeach = $this->uri->segment(3);
		$this->load->model("subject_model");
		$this->load->model("teacher_model");
		$this->load->model("menu_model");
		$kTeach = $this->uri->segment(3);
		$data["kTeach"] = $kTeach;
		$data["years"] = get_year_list();
		$grades = $this->menu_model->get_pairs("grade");
		$data["grade_list"] = get_keyed_pairs($grades,array("value","label"));
		$grade = $this->teacher_model->get($kTeach,array("gradeStart","gradeEnd"));
		$data["gradeStart"] = $grade->gradeStart;
		$data["gradeEnd"] = $grade->gradeEnd;
		$subjects = $this->subject_model->get_for_teacher($kTeach);
		$data["subjects"] = get_keyed_pairs($subjects, array("subject","subject"),FALSE);
		$data["template"] = FALSE;
		$data['rich_text'] = TRUE;
		$data["target"] = "template/edit";
		$data["action"] = "insert";
		$data["title"] = "Creating a New Subject Template";
		$this->load->view("page/index", $data);

	}

	/**
	 * insert a new template
	 * This offers both an ajax interface and standard form submission option. 
	 * The AJAX option is for the auto-save feature and publishes information
	 * about the latest auto-save (or failure)
	 * After insertion using the standard form submit, it redirects to the list of templates
	 * based on the criteria of the saved template (current year, kTeach, term, etc)
	 */
	function insert()
	{
		$kTemplate = $this->template_model->insert();
		$data['kTemplate'] = $kTemplate;
		if($this->input->post("ajax")){
			if($kTemplate){
				$data['message'] = "The template was successfully added at: " . date("m-d-Y g:i:s a");
				echo json_encode($data);
			}else{
				$data['message'] ="The template did not get saved correctly. Please copy the text into your favorite text editor and contact technical support";
				echo json_encode($data);
			}

		}else{
			$kTeach = $this->input->post("kTeach");
			$year = $this->input->post("year");
			$term = $this->input->post("term");
			$subject = $this->input->post("subject");
			redirect("template/list_templates/?kTeach=$kTeach&term=$term&year=$year&subject=$subject");
		}
	}

	/**
	 * updates the template with either an AJAX option for auto-saving or with 
	 * a standard form-submission that redirects to the list of available templates
	 * based on key criteria from the template being updated. 
	 */
	function update()
	{
		$kTemplate = $this->input->post("kTemplate");
		$data['kTemplate'] = $kTemplate;
		$this->template_model->update($kTemplate);
		if($this->input->post("ajax")){
			$data['message'] = "The template was successfully saved at " . date("m-d-Y g:i:s a");
			echo json_encode($data);
		}else{
			$kTeach = $this->input->post("kTeach");
			$year = $this->input->post("year");
			$term = $this->input->post("term");
			$subject = $this->input->post("subject");
			redirect("template/list_templates/?kTeach=$kTeach&term=$term&year=$year&subject=$subject");
		}

	}





}
