<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Narrative extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('narrative_model');
		$this->load->helper('template');
	}



	function create()
	{
		$this->load->model('support_model');
		$this->load->model('student_model');
		$this->load->model('teacher_model');
		$this->load->model('template_model');
		$this->load->model('subject_model');
		$this->load->model("suggestion_model");

		$kStudent = $this->input->get_post('kStudent');
		$kTeach = $this->input->get_post('kTeach');
		$data['narrSubject'] = $this->input->get_post('narrSubject');
		$data['narrTerm'] = get_current_term();
		$data['narrYear'] = get_current_year();
		$data['kTeach'] = $kTeach;
		$student = $this->student_model->get($kStudent);
		$data['student'] = $student;
		$subjects = $this->subject_model->get_for_teacher($kTeach);
		$data['subjects'] = get_keyed_pairs($subjects, array('subject', 'subject'));
		$teacher = $this->teacher_model->get($kTeach);
		$data['teacher'] = $teacher;
		$teachers = $this->teacher_model->get_teacher_pairs();
		$data['teacherPairs'] = get_keyed_pairs($teachers, array('kTeach', 'teacher'));
		$data['studentName'] = format_name($student->stuFirst, $student->stuLast, $student->stuNickname);
		$data['default_grade'] = $this->input->cookie("default_grade");
		$data['narrative'] = NULL;
		$data['narrText'] = "";
		$data['action'] = "insert";

		if($this->input->post('kTemplate') != 0){

			$kTemplate = $this->input->post('kTemplate');
			$name = $student->stuNickname;
			$gender = $student->stuGender;
			$template = $this->template_model->get($kTemplate);
			$data['narrText'] = parse_template($template->template, $name, $gender);

		}
		$data["hasNeeds"] = $this->support_model->get_current($kStudent, "kSupport");
		$data["hasSuggestions"] = NULL;
		$data['target'] = 'narrative/edit';
		$data['title'] = "Add a Narrative for $student->stuFirst $student->stuLast";
		$this->load->view('page/index', $data);
	}


	function insert()
	{
		$result = $this->narrative_model->insert();
		if($this->input->post('ajax')){
			echo implode("|",$result);
		}else{
			redirect('narrative/view/'. $result[0]);
		}
	}


	function update()
	{

		$kNarrative = $this->input->post('kNarrative');
		$result = $this->narrative_model->update($kNarrative);
		if($this->input->post('ajax')){
			echo implode("|", $result);
		}else{
			redirect('narrative/view/'. $kNarrative);
		}
	}

	function delete()
	{
		if($this->input->post('kNarrative') && $this->input->post('kStudent')){
			$kNarrative = $this->input->post('kNarrative');
			$kStudent = $this->input->post('kStudent');
			$this->narrative_model->delete($kNarrative);
			echo "The narrative $kNarrative has been successfully backed up and ";
			echo "removed from the list of active narratives";
		}
	}

	function view()
	{
		$this->load->model('student_model');
		$this->load->model('teacher_model');
		$this->load->model('suggestion_model');
		$this->load->model('benchmark_model');
		$this->load->model('benchmark_legend_model','legend');
		$this->load->model('backup_model');
		$kNarrative = $this->uri->segment(3);
		$narrative = $this->narrative_model->get($kNarrative, TRUE);
		$kStudent = $narrative->kStudent;
		$kTeach = $narrative->kTeach;
		$data['narrative'] = $narrative;
		$data['has_benchmarks'] = $this->benchmark_model->student_has_benchmarks($kStudent, $narrative->narrSubject, $narrative->stuGrade, $narrative->narrTerm, $narrative->narrYear);
		$data['benchmarks_available'] = $this->benchmark_model->benchmarks_available($narrative->narrSubject, $narrative->stuGrade, $narrative->narrTerm, $narrative->narrYear);
		if($data['has_benchmarks']){
			$data['legend'] = $this->legend->get_one(array("kTeach"=>$kTeach, "subject"=>$narrative->narrSubject, "term"=> $narrative->narrTerm, "year"=>$narrative->narrYear ));
			$data["benchmarks"] = $this->benchmark_model->get_for_student($kStudent, $narrative->narrSubject, $narrative->stuGrade, $narrative->narrTerm, $narrative->narrYear);
		}
		$student = $this->student_model->get($kStudent);
		$teacher = $this->teacher_model->get($kTeach);
		$studentName = format_name($student->stuFirst, $student->stuLast, $student->stuNickname);
		$data['target'] = "narrative/view";
		$data['title'] = "Viewing Narrative Report for $studentName for $narrative->narrSubject";
		//@TODO edits/suggestions checking.
		$data["hasSuggestions"] = $this->suggestion_model->exists($kNarrative);
		$data["backups"] = $this->backup_model->get_all($kNarrative);
		$data['studentName'] = $studentName;
		$data['teacher'] = format_name($teacher->teachFirst, $teacher->teachLast);
		$this->load->view("page/index", $data);
	}


	function edit()
	{
		$this->load->model('student_model');
		$this->load->model("teacher_model");
		$this->load->model("template_model");
		$this->load->model("subject_model");
		$this->load->model("support_model");
		$this->load->model("suggestion_model");

		$kNarrative = $this->uri->segment(3);

		$narrative = $this->narrative_model->get($kNarrative, TRUE);
		$kStudent = $narrative->kStudent;
		$kTeach = $narrative->kTeach;

		$data["narrative"] = $narrative;
		$student = $this->student_model->get($kStudent);
		$data["student"] = $student;
		$subjects = $this->subject_model->get_for_teacher($kTeach);
		$data["subjects"] = get_keyed_pairs($subjects, array("subject", "subject"));
		$teacher = $this->teacher_model->get($kTeach);
		$data["teacher"] = $teacher;
		$teachers = $this->teacher_model->get_teacher_pairs();
		$data["teacherPairs"] = get_keyed_pairs($teachers, array("kTeach", "teacher"));
		$data["narrText"] = "";
		$studentName = format_name($student->stuFirst, $student->stuLast, $student->stuNickname);
		$data["hasNeeds"] = $this->support_model->get_current($kStudent, "kSupport");

		// Get the value of the default_grade preference.
		$data['default_grade'] = $this->input->cookie("default_grade");
		//$data["needsButton"] = $this->get_need_button($kStudent);
		// 		$data["suggestionsButton"] = $this->get_suggestion_button($kNarrative);
		$data["hasSuggestions"] = TRUE;// $this->suggestion_model->exists($kNarrative);
		$data["target"] = "narrative/edit";
		$data["action"] = "update";
		$data["title"] = "Editing Narrative Report for $studentName for $narrative->narrSubject";
		$data["student"] = $student;
		$data["studentName"] = $studentName;
		$this->load->view("page/index", $data);
	}

	function edit_inline()
	{
		//@TODO check  with kTeach against user ID or allow only editor/admin user role
		$kNarrative = $this->input->get_post("kNarrative");
		$data["narrative"] = $this->narrative_model->get($kNarrative,FALSE, "kNarrative,narrText,kTeach");
		$this->load->view("narrative/edit_inline", $data);


	}

	function update_inline()
	{
		$kTeach = $this->input->post("kTeach");
		$narrText = $this->input->post("narrText");
		$kNarrative = $this->input->post("kNarrative");
		$dbRole = $this->session->userdata("dbRole");
		$userID = $this->session->userdata("userID");
		if($kTeach == $userID || $dbRole == 1){
			$this->narrative_model->update_text($kNarrative,$narrText);
		}
		$output =  $this->narrative_model->get($kNarrative, FALSE, "narrText, recModified");
		echo $output->narrText . "||" . format_timestamp($output->recModified);
	}

	function update_grade(){
		$kNarrative = $this->input->post("kNarrative");
		$result = "";
		if($kNarrative){
			$narrGrade = $this->input->post("narrGrade");
			$result = $this->narrative_model->update_value($kNarrative,"narrGrade",$narrGrade);
		}
		echo $result;
	}

	function student_list(){
		$this->load->model("student_model");
		$this->load->model("teacher_model");
		$this->load->model("subject_model");
		$this->load->model("suggestion_model");
		$this->load->model("narrative_sort_model");
		$this->load->model("preference_model");
		$data["accordion"] = $this->preference_model->get($this->session->userdata("userID"), "accordion");
		$data["defaultYear"] = get_current_year();
		$data["defaultTerm"] = get_current_term();

		$kStudent = $this->uri->segment(3);
		$student = $this->student_model->get($kStudent);
		$studentName = format_name($student->stuFirst, $student->stuLast, $student->stuNickname);
		$data["studentName"] = $studentName;
		$data["student"] = $student;
		$data["target"] = "narrative/student_list";
		$data["action"] = "add";
		$data["title"] = "List of Narratives for $studentName";
		if(is_numeric($this->uri->segment(4))){
			$narrYear = $this->uri->segment(4);
		}

		if($this->uri->segment(5)){
			$narrTerm = $this->uri->segment(5);
		}

		$reportSort = $this->narrative_sort_model->get_sort($kStudent, $data["defaultTerm"], $data["defaultYear"]);
		$data["reportSort"] = $reportSort;
		$options["reportSort"] = $data["reportSort"];
		$narratives = $this->narrative_model->get_for_student($kStudent, $options);
		$data["userRole"] = $this->session->userdata("dbRole");
		$data["userID"] = $this->session->userdata("userID");
		$data["narratives"] = $narratives;
		$this->load->view("page/index", $data);
	}


	function teacher_list()
	{
		$this->load->model("teacher_model");
		$kTeach = $this->uri->segment(3);
		if(empty($kTeach)){
			$kTeach = $this->input->get_post("kTeach");
		}
		$options["kTeach"] = $kTeach;

		if($this->input->get_post("gradeStart") && $this->input->get_post("gradeEnd")){
			$options["gradeStart"] = $this->input->get_post("gradeStart");
			bake_cookie("gradeStart", $options["gradeStart"]);
			$options["gradeEnd"] = $this->input->get_post("gradeEnd");
			bake_cookie("gradeEnd", $options["gradeEnd"]);
		}

		if($this->input->get_post("subject")){
			$options["narrSubject"] = $this->input->get_post("subject");
			bake_cookie("narrative_subject", $options["narrSubject"]);

		}

		$options["narrYear"] = get_current_year();
		if($this->input->get_post("narrYear")){
			$options["narrYear"] = $this->input->get_post("narrYear");
			bake_cookie("narrYear", $options["narrYear"]);

		}

		$options["narrTerm"] = get_current_term();
		if($this->input->get_post("narrTerm")){
			$options["narrTerm"] = $this->input->get_post("narrTerm");
			bake_cookie("narrTerm", $options["narrTerm"]);

		}
		$data["narratives"] = $this->narrative_model->get_narratives($options);
		$data["options"] = $options;
		$teacher = $this->teacher_model->get_name($kTeach);
		$data["teacher"] = $teacher;
		$data["kTeach"] = $kTeach;
		$data["title"] = "Showing current narratives for $teacher";
		$data["target"] = "narrative/teacher_list";
		if($this->uri->segment(4) == "print"){
			$this->load->view("page/print", $data);
		}else{
			$this->load->view("page/index", $data);

		}

	}

	function search_teacher_narratives()
	{
		$this->load->model('teacher_model');
		$this->load->model('subject_model');
		$this->load->model("menu_model");
		$kTeach = $this->uri->segment(3);
		if(empty($kTeach)){
			$kTeach = $this->session->userdata("userID");
		}
		$data["kTeach"] = $kTeach;
		$teachers = $this->teacher_model->get_teacher_pairs();
		$data['teachers'] = get_keyed_pairs($teachers, array('kTeach', 'teacher'));
		$data["subject"] = $this->input->cookie("narrative_subject");//$this->session->userdata("narrative_subject");
		$subjects = $this->subject_model->get_for_teacher($kTeach);
		$data["subjects"] = get_keyed_pairs($subjects, array("subject", "subject"));

		if($this->session->userdata("userRole") == 1){
			$subjects = $this->subject_model->get_all();

		}
		$grade_list = $this->menu_model->get_pairs("grade");

		$data["grades"] = get_keyed_pairs($grade_list, array("value","label"));

		//$data["gradeStart"] = $this->session->userdata("gradeStart");
		//$data["gradeEnd"] = $this->session->userdata("gradeEnd");
		$data["gradeStart"] = $this->input->cookie("gradeStart");
		$data["gradeEnd"] = $this->input->cookie("gradeEnd");
		if(empty($data["gradeStart"]) || empty($data["gradeEnd"])){
			$teacher_grades = $this->teacher_model->get($kTeach,"gradeStart,gradeEnd");
			$data["gradeStart"] = $teacher_grades->gradeStart;
			$data["gradeEnd"] = $teacher_grades->gradeEnd;
		}
		$data["narrTerm"] = $this->input->cookie("narrTerm");//$this->session->userdata("narrTerm");
		if(empty($data["narrTerm"])){
			$data["narrTerm"] = get_current_term();
		}
		$data["narrYear"] = $this->input->cookie("narrYear");//$this->session->userdata("narrYear");
		if(empty($data["narrYear"])){
			$data["narrYear"] = get_current_year();
		}
		$this->load->view("narrative/teacher_search",$data);
	}


	function search_missing(){
		$data["kTeach"] = $this->input->get_post("kTeach");
		$this->load->model("teacher_model");
		$this->load->model("subject_model");
		$this->load->model("menu_model");
		$teacher = $this->teacher_model->get($data["kTeach"]);
		$data["gradeStart"] = $teacher->gradeStart;
		$data["gradeEnd"] = $teacher->gradeEnd;
		$subject_list = $this->subject_model->get_for_teacher($data["kTeach"]);
		$data["subject"] = $this->input->cookie("narrative_subject");//$this->session->userdata("narrative_subject");
		$data["subjects"] = get_keyed_pairs($subject_list, array("subject", "subject"));
		$grade_list = $this->menu_model->get_pairs("grade");
		$data["grades"] = get_keyed_pairs($grade_list, array("value","label"));
		$this->load->view("narrative/search_missing", $data);
	}


	function show_missing()
	{
		$this->load->model("subject_model");
		$this->load->model("student_model");
		$this->load->model("teacher_model");
		$data["kTeach"] = $this->input->get_post("kTeach");
		$data["gradeStart"] = $this->input->get_post("gradeStart");
		$data["gradeEnd"] = $this->input->get_post("gradeEnd");
		$data["subject"] = $this->input->get_post("subject");
		$data["narrYear"] = get_current_year();
		$data["narrTerm"] = get_current_term();
		bake_cookie("narrative_subject", $data["subject"]);
		$constraints = array();
		if($data["subject"] == "Humanities"){
			$constraints["humanitiesTeacher"] = $data["kTeach"];
		}elseif($data["subject"] == "Academic Progress" || $data["subject"] == "Social/Emotional"){
			$constraints["kTeach"] = $data["kTeach"];
		}
		$data["students"] = $this->student_model->get_students_by_grade($data["gradeStart"], $data["gradeEnd"], $constraints);
		$data["teacher"] = $this->teacher_model->get_name($data["kTeach"]);
		$data["target"] = "narrative/show_missing";
		$data["title"] = "Showing Missing Narratives for " . $data["teacher"];
		$this->load->view("page/index", $data);
	}

	function print_student_report()
	{
		if($this->uri->segment(5)){
			$this->load->model("narrative_sort_model");
			$this->load->model("student_model");
			$this->load->model("attendance_model");
			$this->load->model("benchmark_model");
			//$this->load->model("preference_model");
			$this->load->model("benchmark_legend_model", "legend");
			$kStudent = $this->uri->segment(3);
			$narrTerm = $this->uri->segment(4);
			$narrYear = $this->uri->segment(5);
			$student_obj = $this->student_model->get($kStudent, "stuFirst,stuLast,stuNickname,baseGrade,baseYear");
			$student = format_name($student_obj->stuFirst, $student_obj->stuLast, $student_obj->stuNickname);
			$stuGrade = get_current_grade($student_obj->baseGrade, $student_obj->baseYear, $narrYear);
			$attendance = $this->attendance_model->summarize($kStudent, $narrTerm, $narrYear);
			$data["tardy"] = $attendance["tardy"];
			$data["absent"] = $attendance["absent"];
			$data["stuGrade"] = $this->student_model->get_grade($kStudent, $narrYear);
			$data["narrYear"] = $narrYear;
			$data["narrTerm"] = $narrTerm;
			$narratives = $this->narrative_model->get_for_student($kStudent, array("narrTerm"=>$narrTerm, "narrYear"=> $narrYear));
			/*
			 foreach($narratives as $narrative){
			$data["benchmarks"][$narrative->narrSubject] = $this->benchmark_model->get_for_student($kStudent,$narrative->narrSubject,$stuGrade, $narrTerm, $narrYear);
			}
			*/
			$data["narratives"] = $narratives;
			$data["student"] = $student;
			$data["title"] = "Narrative Report for $student";
			$this->load->view("narrative/print", $data);
		}
	}


	function select_type()
	{
		$this->load->model("subject_model");
		$data["kStudent"] = $this->input->post("kStudent");
		$data["kTeach"] = $this->session->userdata("userID");
		$currentTerm = get_current_term();
		$data["term_menu"] = get_term_menu("term", $currentTerm);
		$data["currentYear"] = get_current_year();
		$data["year_list"] = get_year_list();
		$data["title"] = "Select Narrative Type";
		$data["target"] = "narrative/select_type";
		$subject_list = $this->subject_model->get_for_teacher($data["kTeach"]);
		$data["subjects"] = get_keyed_pairs($subject_list, array("subject", "subject"));

		if($this->input->post("ajax")){
			$this->load->view($data["target"], $data);
		}
	}


	function show_sorter()
	{
		$this->load->model("narrative_sort_model");
		$kStudent = $this->input->post("kStudent");
		$data["kStudent"] = $kStudent;
		$data["narrTerm"] = $this->input->post("narrTerm");
		$data["narrYear"] = $this->input->post("narrYear");
		$data["reportSort"] = $this->narrative_sort_model->get_sort($kStudent, $data["narrTerm"], $data["narrYear"]);
		$data["kTeach"] = 1000;
		$data["target"] = "narrative/sorter";
		$data["title"] = "Sorting Narratives";
		$data["school_year"] = format_schoolyear($data["narrYear"], $data["narrTerm"]);
		$this->load->view($data["target"], $data);
	}


	function set_sort()
	{
		$this->load->model("narrative_sort_model");
		$kStudent = $this->input->post("kStudent");
		$narrYear = $this->input->post("narrYear");
		$narrTerm = $this->input->post("narrTerm");
		$reportSort = $this->input->post("reportSort");
		$this->narrative_sort_model->set_sort($kStudent, $narrTerm, $narrYear, $reportSort);
		redirect("narrative/student_list/$kStudent");
	}



	function search()
	{
		$data["currentTerm"] = get_current_term();
		$data["terms"] = get_term_menu("narrTerm", $data["currentTerm"]);
		$data["currentYear"] = get_current_year();
		$data["years"] = get_year_list();
		$this->load->model('menu_model');
		$grade_pairs = 	$this->menu_model->get_pairs("grade");
		$data["grades"] = get_keyed_pairs($grade_pairs, array("value","label"));
		$this->load->model('teacher_model');
		$teacher_pairs = $this->teacher_model->get_teacher_pairs();
		$data["teachers"] = get_keyed_pairs($teacher_pairs, array("kTeach","teacher"));
		$data["kTeach"] = $this->session->userdata("userID");
		$data["target"] = "narrative/search";
		$data["title"] = "Narrative Search & Replace";
		$this->load->view("page/index",$data);
	}


	function replace(){
		$search = $this->input->post("search");
		$replace = $this->input->post("replace");
		$this->load->model("teacher_model");
		$gradeStart = $this->input->post("gradeStart");
		$gradeEnd = $this->input->post("gradeEnd");
		$kTeach = $this->input->post("kTeach");
		$narrYear = $this->input->post("narrYear");
		$narrTerm = $this->input->post("narrTerm");
		$data = $this->narrative_model->text_replace($search, $replace, $kTeach, $narrYear, $narrTerm, $gradeStart, $gradeEnd);
		$data["gradeStart"] = $gradeStart;
		$data["gradeEnd"] = $gradeEnd;
		$data["teacher"] = $this->teacher_model->get($kTeach, array("teachFirst","teachLast"));
		$data["narrYear"] = $narrYear;
		$data["narrTerm"]  = $narrTerm;
		$data["replace"] = $replace;
		$data["search"] = $search;
		$data["target"] = "narrative/search_results";
		$data["title"] = "Narrative Search & Replace Results";
		$this->load->view("page/index", $data);
	}


	function list_backups()
	{
		$kNarrative = $this->uri->segment(3);
		$this->load->model("backup_model");
		$data["backups"] = $this->backup_model->get_all($kNarrative,"recModified,narrText");
		$data["kNarrative"] = $kNarrative;
		$data["target"] = "narrative/backup_list";
		$data["title"] = "Narrative Backups";
		$this->load->view("page/index",$data);
	}


}
