<?php
class Overview extends MY_Controller {

	function __construct()
	{
		parent::__construct ();
		$this->load->model ( "overview_model", "overview" );
	}

	function create($kTeach)
	{
		$this->load->model("teacher_model");
		$this->load->model("menu_model");
		$this->load->model("subject_model");
		$template = NULL;
		$data["overview"] = "";
		$data["kTeach"] = $kTeach;
		$subjects = $this->subject_model->get_for_teacher($kTeach);
		$data["subjects"] = get_keyed_pairs($subjects, array("subject","subject"),FALSE);
		$data["years"] = get_year_list();
		$grades = $this->menu_model->get_pairs("grade");
		$data["gradeStart"] = "";
		$data["gradeEnd"] = "";
		$data["grade_list"] = get_keyed_pairs($grades,array("value","label"));
		$data['rich_text'] = TRUE;
		$data["target"] = "overview/edit";
		$data["action"] = "insert";
		$data["title"] = "Editing a Subject Overview";
		$this->load->view("page/index", $data);	}

	function insert()
	{
	}

/**
 * Edit overview based on a uri segment overview id (kOverview)
 */
	function edit($kOverview)
	{
		$this->load->model("teacher_model");

		$this->load->model("menu_model");
		$this->load->model("subject_model");
		$template = $this->overview->get($kOverview);
		$data["overview"] = $overview;
		$data["kTeach"] = $overview->kTeach;
		$subjects = $this->subject_model->get_for_teacher($overview->kTeach);
		$data["subjects"] = get_keyed_pairs($subjects, array("subject","subject"),FALSE);
		$data["years"] = get_year_list();
		$grades = $this->menu_model->get_pairs("grade");
		$data["gradeStart"] = "";
		$data["gradeEnd"] = "";
		$data["grade_list"] = get_keyed_pairs($grades,array("value","label"));
		$data['rich_text'] = TRUE;
		$data["target"] = "overview/edit";
		$data["action"] = "update";
		$data["title"] = "Editing a Subject Overview";
		$this->load->view("page/index", $data);

	}

	function update($kOverview)
	{
	}
	
	/**
	 * search dialog for finding templates.
	 * This redirects on submit to the above list_templates() function
	 */
	function search($kTeach)
	{
		$this->load->model("subject_model");
		$this->load->model("teacher_model");
		$this->load->model("menu_model");
		$data["kTeach"] = $kTeach;
		$grades = $this->menu_model->get_pairs("grade");
		$data["grade_list"] = get_keyed_pairs($grades,array("value","label"));
		$data["years"] = get_year_list(TRUE);
		$subjects = $this->subject_model->get_for_teacher($kTeach);
		$data["subject"] = NULL;
		$data["subjects"] = get_keyed_pairs($subjects, array("subject","subject"),TRUE);
		$data["grades"] = $this->teacher_model->get($kTeach,array("gradeStart","gradeEnd"));
		$this->load->view("overview/search", $data);
	}

	/**
	 * list overviews for a given teacher based on input data queries
	 * kTeach, term, year, subject, gradeStart, gradeEnd
	 * Only kTeach is mandatory.
	 */
	function view()
	{
		if ($kTeach = $this->input->get("kTeach")) {
			$this->load->model ( "teacher_model" );
			$options = array ();
			if ($this->input->get ( "term" )) {
				$options ["where"] ["term"] = $this->input->get ( "term" );
			}
			
			if ($this->input->get ( "year" )) {
				$options ["where"] ["year"] = $this->input->get ( "year" );
			}
			
			if ($this->input->get ( "subject" )) {
				$options ["where"] ["subject"] = $this->input->get ( "subject" );
				// $this->session->set_userdata("template_subject",$options["where"]["subject"]);
				bake_cookie ( "overview_subject", $options ["where"] ["subject"] );
			}
			
			$include_inactive = FALSE;
			if($this->input->get("include_inactive")){
				$include_inactive = TRUE;
			}
			
			
			if ($this->input->get ( "gradeStart" ) && $this->input->get ( "gradeEnd" )) {
				$options ["grade_range"] ["gradeStart"] = $this->input->get ( "gradeStart" );
				$options ["grade_range"] ["gradeEnd"] = $this->input->get ( "gradeEnd" );
			}
			
			$data ["overviews"] = $this->overview->get_all ( $kTeach, $options, $include_inactive );
			$data ["kTeach"] = $kTeach;
			$data ["teacher"] = $this->teacher_model->get_name ( $kTeach );
			$data ["target"] = "overview/list";
			$data ["title"] = "Listing Subject Overviews for " . $data ["teacher"];
			$data ["options"] = $options;
			$this->load->view ( "page/index", $data );
		}
	}
}