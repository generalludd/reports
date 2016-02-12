<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//application "index.php" file. This is home.
class Home extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$this->load->model("menu_model");
		if($this->input->get("refine")){
			$data['refine'] = TRUE;
		}else{
			$data['refine'] = FALSE;
		}
		$student_sort = $this->menu_model->get_pairs("student_sort");
		$data["body_classes"] = array("front");
		$data["student_sort"] = get_keyed_pairs($student_sort, array("value","label"));
		$this->load->model("teacher_model","teacher");
		$teachers = $this->teacher->get_teacher_pairs(2,1,"homeroom");
		$data['teachers'] = get_keyed_pairs($teachers, array("kTeach","teacher"),TRUE);
		$humanitiesTeachers = $this->teacher->get_for_subject("humanities");
		$data["humanitiesTeachers"] = get_keyed_pairs($humanitiesTeachers,array("kTeach","teacherName"),TRUE);
		$data['target'] = "student/search";
		$data['title'] = "Narrative Reporting System";
		$data['currentYear'] = get_current_year();
		$data['yearList'] = get_year_list();
		$this->load->view('page/index', $data );
	}

	function fix_ids(){
		$tables = array(
				"assignment",
				"assignment_category",
				"backup",
				"benchmark_legend",
				"chart_legend",
				"concept",
				"grade_scale",
				"narrative",
				"narrative_edit",
				"preference",
				"query_tracking",
				"student",
				"student_benchmark",
				"student_concept",
				"student_report",
				"teacher_subject",
				"template",
				"user_log",
		);
		
		for($i=0; $i<count($tables);$i++){
		$table = $tables[$i];
		 printf("update `%s`,`teacher` set `%s`.`kTeach` = `teacher`.`user_id` where `%s`.`kTeach` = `teacher`.`kTeach`;",$table,$table,$table);
		
		}
		print "update `student`,`teacher` set `student`.`humanitiesTeacher` = `teacher`.`user_id` where `student`.`humanitiesTeacher` = `teacher`.`kTeach`;";
		print "update `student_report`,`teacher` set `student_report`.`kAdvisor` = `teacher`.`user_id` where `student_report`.`kAdvisor` = `teacher`.`kTeach`;";
		
		$modifiers = array(
				
				"backup",
				"benchmark",
				"benchmark_legend",
				"chart_legend",
				"narrative",
				"student",
				"student_attendance",
				"student_report",
				"subject_sort",
				"support",
				"support_file",
				"template",
		);
		for($i=0; $i<count($modifiers);$i++){
			$modifier = $modifiers[$i];
			printf("update `%s`,`teacher` set `%s`.`recModified` = `teacher`.`user_id` where `%s`.`recModified` = `teacher`.`kTeach`;",$modifier,$modifier,$modifier);
		
		}
		
	}

}