<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Narrative extends MY_Controller {

	function __construct()
	{
		parent::__construct ();
		$this->load->model ( 'narrative_model' );
		$this->load->helper ( 'template' );
	}

	/**
	 * select a narrative type AJAX interface for selecting a narrative
	 * category.
	 * the result of this action is to display available templates or the option
	 * to create a narrative without template in template.php show_selector
	 */
	function select_type()
	{
		$this->load->model ( "subject_model" );
		$data ["kStudent"] = $this->input->get ( "kStudent" );
		$data ["kTeach"] = $this->session->userdata ( "userID" );
		$currentTerm = get_current_term ();
		$data ["term_menu"] = get_term_menu ( "term", $currentTerm );
		$data ["currentYear"] = get_current_year ();
		$data ["year_list"] = get_year_list ();
		$data ["title"] = "Select Narrative Type";
		$data ["target"] = "narrative/select_type";
		$subject_list = $this->subject_model->get_for_teacher ( $data ["kTeach"] );
		$data ["subjects"] = get_keyed_pairs ( $subject_list, array (
				"subject",
				"subject" 
		) );
		$this->_view ( $data );
	}
	
	// @TODO merge narrative report search for student with joins with teacher
	// and student tables
	/**
	 * create a new narrative.
	 * This can be called directly but is usually called indirectly from a
	 * template creation dialog that narrows down the subject. If a kTemplate
	 * was submitted in the $_POST
	 * query it will apply the template to the new narrative.
	 */
	function create()
	{
		$this->load->model ( 'support_model' );
		$this->load->model ( 'student_model' );
		$this->load->model ( 'teacher_model' );
		$this->load->model ( 'template_model' );
		$this->load->model ( 'subject_model' );
		
		$kStudent = $this->input->get ( 'kStudent' );
		$kTeach = $this->input->get ( 'kTeach' );
		$data ['narrSubject'] = $this->input->get ( 'narrSubject' );
		$data ['narrTerm'] = get_current_term ();
		$data ['narrYear'] = get_current_year ();
		$data ['kTeach'] = $kTeach;
		$student = $this->student_model->get ( $kStudent );
		$data ['student'] = $student;
		$subjects = $this->subject_model->get_for_teacher ( $kTeach );
		$data ['subjects'] = get_keyed_pairs ( $subjects, array (
				'subject',
				'subject' 
		) );
		$teacher = $this->teacher_model->get ( $kTeach );
		$data ['teacher'] = $teacher;
		$teachers = $this->teacher_model->get_teacher_pairs ();
		$data ['teacherPairs'] = get_keyed_pairs ( $teachers, array (
				'kTeach',
				'teacher' 
		) );
		$data ['studentName'] = format_name ( $student->stuFirst, $student->stuLast, $student->stuNickname );
		$data ['default_grade'] = $this->input->cookie ( "default_grade" );
		$data['rich_text'] = TRUE;
		$submits_report_card = $this->input->cookie ( "submits_report_card" );
		if ($submits_report_card == "yes") {
			$this->load->model ( "assignment_model", "assignment" );
			$this->load->helper ( "grade" );
			$grade_options ["from"] = "grade";
			$grade_options ["join"] = "assignment";
			$grade_options ['subject'] = $data ['narrSubject'];
			$this->load->model ( "grade_preference_model", "grade_preferences" );
			$pass_fail = $this->grade_preferences->get_all ( $kStudent, array (
					"school_year" => $data ['narrYear'],
					"subject" => $data ['narrSubject'] 
			) );
			$default_grade = calculate_final_grade ( $this->assignment->get_for_student ( $kStudent, $data ['narrTerm'], $data ['narrYear'], $grade_options ) );
			$data ['default_grade'] = calculate_letter_grade ( $default_grade, $pass_fail );
		}
		$data ['narrative'] = NULL;
		$data ['narrText'] = "";
		$data ['action'] = "insert";
		
		// if there is a kTemplate value with the $_POST, then
		// apply the associated template to the new narrative
		if ($kTemplate = $this->input->get ( 'kTemplate' )) {
			
			$name = $student->stuNickname;
			$gender = $student->stuGender;
			$template = $this->template_model->get ( $kTemplate );
			$data ['narrText'] = parse_template ( $template->template, $name, $gender );
		}
		
		// check to see if the student has special needs
		$data ["hasNeeds"] = $this->support_model->get_current ( $kStudent, "kSupport" );
		$data ['target'] = 'narrative/edit';
		$data ['title'] = "Add a Narrative for $student->stuFirst $student->stuLast";
		$this->load->view ( 'page/index', $data );
	}

	/**
	 * offer either an AJAX insertion for auto-save feature or
	 * standard form submission and return to the view of the new narrative
	 * the AJAX result is split into two elements (kNarrative, time_stamp) that
	 * can be
	 * parsed by the javascript.
	 * the kNarrative value is used to alter the form so that
	 * future submissions will be updates instead of insertions
	 */
	function insert()
	{
		$result = $this->narrative_model->insert ();
		if ($this->input->post ( 'ajax' )) {
			echo json_encode($result);
		} else {
			redirect ( 'narrative/view/' . $result ['kNarrative'] );
		}
	}

	/**
	 * edit a narrative based on a uri segment 3 of the kNarrative.
	 * Edting also includes the calcluation of
	 * the student's term grade based either on report cards or provides an
	 * empty field
	 * for the author to enter a pass/pass with honors note based on their
	 * default_grade preference
	 */
	function edit()
	{
		$this->load->model ( 'student_model' );
		$this->load->model ( "teacher_model" );
		$this->load->model ( "template_model" );
		$this->load->model ( "subject_model" );
		$this->load->model ( "support_model" );
		
		$kNarrative = $this->uri->segment ( 3 );
		
		$narrative = $this->narrative_model->get ( $kNarrative, TRUE );
		$kStudent = $narrative->kStudent;
		$kTeach = $narrative->kTeach;
		
		$data ["narrative"] = $narrative;
		$student = $this->student_model->get ( $kStudent );
		$data ["student"] = $student;
		$subjects = $this->subject_model->get_for_teacher ( $kTeach );
		$data ["subjects"] = get_keyed_pairs ( $subjects, array (
				"subject",
				"subject" 
		) );
		$teacher = $this->teacher_model->get ( $kTeach );
		$data ["teacher"] = $teacher;
		$teachers = $this->teacher_model->get_teacher_pairs ();
		$data ["teacherPairs"] = get_keyed_pairs ( $teachers, array (
				"kTeach",
				"teacher" 
		) );
		$data["rich_text"] = TRUE;
		$data ["narrText"] = "";
		$studentName = format_name ( $student->stuFirst, $student->stuLast, $student->stuNickname );
		$data ["hasNeeds"] = $this->support_model->get_current ( $kStudent, "kSupport" );
		
		// Get the value of the default_grade preference.
		$data ['default_grade'] = $this->input->cookie ( "default_grade" );
		// submits_report_card is also a user preference
		// determine if grades are manually entered or calculated from grade
		// report cards.
		// @TODO this could be done with a simple search for grades to determine
		// if any exists.
		// Having a static declaration untied to the existence of entered grades
		// is risky.
		$submits_report_card = $this->input->cookie ( "submits_report_card" );
		if ($submits_report_card == "yes") {
			$this->load->model ( "assignment_model", "assignment" );
			$this->load->helper ( "grade" );
			$grade_options ["from"] = "grade";
			$grade_options ["join"] = "assignment";
			$grade_options ['subject'] = $narrative->narrSubject;
			$grades = $this->assignment->get_for_student ( $kStudent, $narrative->narrTerm, $narrative->narrYear, $grade_options );
			// if grades have been entered then include that grade, otherwise
			// use the overridden grade for the term
			// this helpls for students taking a class pass-fail and for
			// printing out reports from years prior to the creation of the
			// gradebook option
			if ($grades) {
				$letter_grade = calculate_final_grade ( $grades );
				$data ['default_grade'] = calculate_letter_grade ( $letter_grade );
			} else {
				$data ['default_grade'] = $narrative->narrGrade;
			}
		}
		$data ["target"] = "narrative/edit";
		$data ["action"] = "update";
		$data ["title"] = "Editing Narrative Report for $studentName for $narrative->narrSubject";
		$data ["student"] = $student;
		$data ["studentName"] = $studentName;
		$this->load->view ( "page/index", $data );
	}

	/**
	 * allows simple editing inline for quickly fixing a long list of
	 * narratives.
	 */
	function edit_inline()
	{
		// @TODO check with kTeach against user ID or allow only editor/admin
		// user role
		$kNarrative = $this->input->get_post ( "kNarrative" );
		$data ["narrative"] = $this->narrative_model->get ( $kNarrative, FALSE, "kNarrative,narrText,kTeach" );
		$this->load->view ( "narrative/edit_inline", $data );
	}

	/**
	 * offer either AJAX for auto-save or standard form submission with
	 * a return to a view page for the narrative.
	 * Note that like the insert() function, the result is split into two
	 * elements (kNarrative and time_stamp) that can be
	 * parsed by the javascript for the user display and to compare the output
	 * to the form's data if desired.
	 */
	function update()
	{
		$kNarrative = $this->input->post ( 'kNarrative' );
		$result = $this->narrative_model->update ( $kNarrative );
		if ($this->input->post ( 'ajax' )) {
			echo json_encode($result);
		} else {
			redirect ( 'narrative/view/' . $kNarrative );
		}
	}

	/**
	 * updates the narrative inline with only changes to the body text
	 */
	function update_inline()
	{
		$kTeach = $this->input->post ( "kTeach" );
		$narrText = $this->input->post ( "narrText" );
		$kNarrative = $this->input->post ( "kNarrative" );
		$dbRole = $this->session->userdata ( "dbRole" );
		$userID = $this->session->userdata ( "userID" );
		if ($kTeach == $userID || $dbRole == 1) {
			$this->narrative_model->update_text ( $kNarrative, $narrText );
		}
		$output = $this->narrative_model->get ( $kNarrative, FALSE, "narrText, recModified" );
		echo $output->narrText . "||" . format_timestamp ( $output->recModified );
	}

	/**
	 * updates a term grade inline during editing of large numbers of
	 * narratives.
	 */
	function update_grade()
	{
		$kNarrative = $this->input->post ( "kNarrative" );
		$result = "";
		if ($kNarrative) {
			$narrGrade = $this->input->post ( "narrGrade" );
			$result = $this->narrative_model->update_value ( $kNarrative, "narrGrade", $narrGrade );
		}
		echo $result;
	}

	/**
	 * show a narrative for kNarrative including any benchmarks and the final
	 * grade for the current term
	 * or year.
	 */
	function view($kNarrative)
	{
		$this->load->model ( 'student_model' );
		$this->load->model ( 'teacher_model' );
		$this->load->model ( 'benchmark_model' );
		$this->load->model ( 'benchmark_legend_model', 'legend' );
		$this->load->model ( 'backup_model' );
		$this->load->model ( 'preference_model', 'preference' );
		$kNarrative = $this->uri->segment ( 3 );
		$narrative = $this->narrative_model->get ( $kNarrative, TRUE );
		$kStudent = $narrative->kStudent;
		$kTeach = $narrative->kTeach;
		$data ['narrative'] = $narrative;
		$data ['has_benchmarks'] = $this->benchmark_model->student_has_benchmarks ( $kStudent, $narrative->narrSubject, $narrative->stuGrade, $narrative->narrTerm, $narrative->narrYear );
		$data ['benchmarks_available'] = $this->benchmark_model->benchmarks_available ( $narrative->narrSubject, $narrative->stuGrade, $narrative->narrTerm, $narrative->narrYear );
		if ($data ['has_benchmarks']) {
			$data ['legend'] = $this->legend->get_one ( array (
					"kTeach" => $kTeach,
					"subject" => $narrative->narrSubject,
					"term" => $narrative->narrTerm,
					"year" => $narrative->narrYear 
			) );
			$data ["benchmarks"] = $this->benchmark_model->get_for_student ( $kStudent, $narrative->narrSubject, $narrative->stuGrade, $narrative->narrTerm, $narrative->narrYear );
		}
		// determine if grades are manually entered or calculated from grade
		// report cards.
		$data ['letter_grade'] = $narrative->narrGrade;
		
		// submits_report_card is a preference set at login and when preferences
		// are changed
		$submits_report_card = $this->preference->get ( $kTeach, "submits_report_card" );
		if ($submits_report_card == "yes") {
			$this->load->model ( "assignment_model", "assignment" );
			$this->load->helper ( "grade" );
			$grade_options ["from"] = "grade";
			$grade_options ["join"] = "assignment";
			$grade_options ['subject'] = $narrative->narrSubject;
			$this->load->model ( "grade_preference_model", "grade_preferences" );
			$pass_fail = $this->grade_preferences->get_all ( $kStudent, array (
					"school_year" => $narrative->narrYear,
					"subject" => $narrative->narrSubject 
			) );
			$grades = $this->assignment->get_for_student ( $kStudent, $narrative->narrTerm, $narrative->narrYear, $grade_options );
			if (! empty ( $grades )) {
				$letter_grade = calculate_final_grade ( $grades );
				$data ['letter_grade'] = calculate_letter_grade ( $letter_grade, $pass_fail );
			}
		}
		$teacher = $this->teacher_model->get ( $kTeach );
		$studentName = format_name ( $narrative->stuFirst, $narrative->stuLast, $narrative->stuNickname );
		$data ['target'] = "narrative/view";
		$data ['title'] = "Viewing Narrative Report for $studentName for $narrative->narrSubject";
		$data ["backups"] = $this->backup_model->get_all ( $kNarrative );
		$data ['studentName'] = $studentName;
		$data ['recModifier'] = $this->teacher_model->get ( $narrative->recModifier, 'teachFirst,teachLast' );
		$data ['teacher'] = format_name ( $teacher->teachFirst, $teacher->teachLast );
		$this->load->view ( "page/index", $data );
	}

	/**
	 * backup and delete a narrative report via the delete function in the
	 * narrative_model
	 * echo a response for AJAX to display.
	 */
	function delete()
	{
		if ($this->input->post ( 'kNarrative' ) && $this->input->post ( 'kStudent' )) {
			$kNarrative = $this->input->post ( 'kNarrative' );
			$kStudent = $this->input->post ( 'kStudent' );
			$this->narrative_model->delete ( $kNarrative );
			echo "The narrative $kNarrative has been successfully backed up and ";
			echo "removed from the list of active narratives";
		}
	}

	function student_list()
	{
		$this->load->model ( "student_model" );
		$this->load->model ( "teacher_model" );
		$this->load->model ( "subject_model" );
		$this->load->model ( "subject_sort_model" );
		
		$data ["defaultYear"] = get_current_year ();
		$data ["defaultTerm"] = get_current_term ();
		
		$kStudent = $this->uri->segment ( 3 );
		$student = $this->student_model->get ( $kStudent );
		$studentName = format_name ( $student->stuFirst, $student->stuLast, $student->stuNickname );
		$data ["studentName"] = $studentName;
		$data ["student"] = $student;
		$data ["target"] = "narrative/student/list_envelope";
		$data ["action"] = "add";
		$data ["title"] = "List of Narratives for $studentName";
		if (is_numeric ( $this->uri->segment ( 4 ) )) {
			$narrYear = $this->uri->segment ( 4 );
		}
		
		if ($this->uri->segment ( 5 )) {
			$narrTerm = $this->uri->segment ( 5 );
		}
		$years = $this->narrative_model->get_years ( $kStudent );
		
		foreach ( $years as $year ) {
			$data ['reports'] [$year->narrYear] = array ();
			$reports = $this->narrative_model->get_for_student ( $kStudent, array (
					"narrYear" => $year->narrYear,
					"narrTerm" => "Mid-Year" 
			) );
			$data ['reports'] [$year->narrYear] [] = $this->load->view ( "narrative/student/list", array (
					"reports" => $reports,
					"narrYear" => $year->narrYear,
					"narrTerm" => "Mid-Year",
					"kStudent" => $kStudent,
					"stuGrade" => $year->stuGrade 
			), TRUE );
			$reports = $this->narrative_model->get_for_student ( $kStudent, array (
					"narrYear" => $year->narrYear,
					"narrTerm" => "Year-End" 
			) );
			$data ['reports'] [$year->narrYear] [] = $this->load->view ( "narrative/student/list", array (
					"reports" => $reports,
					"narrYear" => $year->narrYear,
					"narrTerm" => "Year-End",
					"kStudent" => $kStudent,
					"stuGrade" => $year->stuGrade 
			), TRUE );
		}
		$reportSort = $this->subject_sort_model->get_sort ( $kStudent, $data ["defaultTerm"], $data ["defaultYear"], "narrative" );
		$data ["reportSort"] = $reportSort;
		$options ["reportSort"] = $data ["reportSort"];
		$data ["userRole"] = $this->session->userdata ( "dbRole" );
		$data ["userID"] = $this->session->userdata ( "userID" );
		$this->load->view ( "page/index", $data );
	}

	/**
	 * creates a list of narratives written by a given kTeach based on the uri
	 * segment or a $_GET or $_POST
	 * depending on what was submitted for the list.
	 * This accepts a number of other options to limit or expand the search
	 * results.
	 */
	function teacher_list()
	{
		$this->load->model ( "teacher_model" );
		$kTeach = $this->uri->segment ( 3 );
		if (empty ( $kTeach )) {
			$kTeach = $this->input->get ( "kTeach" );
		}
		$options ["kTeach"] = $kTeach;
		
		if ($this->input->get ( "gradeStart" ) >= 0 && $this->input->get ( "gradeEnd" ) >= 0) {
			$options ["gradeStart"] = $this->input->get ( "gradeStart" );
			bake_cookie ( "gradeStart", $options ["gradeStart"] );
			$options ["gradeEnd"] = $this->input->get ( "gradeEnd" );
			bake_cookie ( "gradeEnd", $options ["gradeEnd"] );
		} else {
		}
		
		if ($this->input->get ( "subject" )) {
			$options ["narrSubject"] = $this->input->get ( "subject" );
			bake_cookie ( "narrative_subject", $options ["narrSubject"] );
		}
		
		$options ["narrYear"] = get_current_year ();
		if ($this->input->get ( "narrYear" )) {
			$options ["narrYear"] = $this->input->get ( "narrYear" );
			bake_cookie ( "narrYear", $options ["narrYear"] );
		}
		
		$options ["narrTerm"] = get_current_term ();
		if ($this->input->get ( "narrTerm" )) {
			$options ["narrTerm"] = $this->input->get ( "narrTerm" );
			bake_cookie ( "narrTerm", $options ["narrTerm"] );
		}
		$data ["narratives"] = $this->narrative_model->get_narratives ( $options );
		$data ["options"] = $options;
		$teacher = $this->teacher_model->get_name ( $kTeach );
		$data["rich_text"] = TRUE;
		$data ["teacher"] = $teacher;
		$data ["kTeach"] = $kTeach;
		$data ["title"] = "Showing current narratives for $teacher";
		$data ["target"] = "narrative/teacher_list";
		if ($this->uri->segment ( 4 ) == "print") {
			$this->load->view ( "page/print", $data );
		} else {
			$this->load->view ( "page/index", $data );
		}
	}

	/**
	 * display a se4arch dialog for listing narratives for a given teacher
	 * either based on the uri segment 3 or defaulting to the $_SESSION userID
	 * value of the current user
	 */
	function search_teacher_narratives()
	{
		$this->load->model ( 'teacher_model' );
		$this->load->model ( 'subject_model' );
		$this->load->model ( "menu_model" );
		$kTeach = $this->uri->segment ( 3 );
		if (empty ( $kTeach )) {
			$kTeach = $this->session->userdata ( "userID" );
		}
		$data ["kTeach"] = $kTeach;
		$teachers = $this->teacher_model->get_teacher_pairs ();
		$data ['teachers'] = get_keyed_pairs ( $teachers, array (
				'kTeach',
				'teacher' 
		) );
		$data ["subject"] = $this->input->cookie ( "narrative_subject" ); // $this->session->userdata("narrative_subject");
		$subjects = $this->subject_model->get_for_teacher ( $kTeach );
		$data ["subjects"] = get_keyed_pairs ( $subjects, array (
				"subject",
				"subject" 
		) );
		
		if ($this->session->userdata ( "dbRole" ) == 1) {
			$subjects = $this->subject_model->get_all ();
		}
		$grade_list = $this->menu_model->get_pairs ( "grade" );
		
		$data ["grades"] = get_keyed_pairs ( $grade_list, array (
				"value",
				"label" 
		) );
		
		// $data["gradeStart"] = $this->session->userdata("gradeStart");
		// $data["gradeEnd"] = $this->session->userdata("gradeEnd");
		$data ["gradeStart"] = $this->input->cookie ( "gradeStart" );
		$data ["gradeEnd"] = $this->input->cookie ( "gradeEnd" );
		if (empty ( $data ["gradeStart"] ) || empty ( $data ["gradeEnd"] )) {
			$teacher_grades = $this->teacher_model->get ( $kTeach, "gradeStart,gradeEnd" );
			$data ["gradeStart"] = $teacher_grades->gradeStart;
			$data ["gradeEnd"] = $teacher_grades->gradeEnd;
		}
		$data ["narrTerm"] = $this->input->cookie ( "narrTerm" ); // $this->session->userdata("narrTerm");
		if (empty ( $data ["narrTerm"] )) {
			$data ["narrTerm"] = get_current_term ();
		}
		$data ["narrYear"] = $this->input->cookie ( "narrYear" ); // $this->session->userdata("narrYear");
		if (empty ( $data ["narrYear"] )) {
			$data ["narrYear"] = get_current_year ();
		}
		$data ["title"] = "Searching Teacher Narratives";
		$data ["target"] = "narrative/teacher_search";
		$this->_view ( $data );
	}

	/**
	 * display a search interface to find all missing narratives based on
	 * various listed criteria
	 */
	function search_missing($kTeach)
	{
		$data ["kTeach"] = $kTeach;
		$this->load->model ( "teacher_model" );
		$this->load->model ( "subject_model" );
		$this->load->model ( "menu_model" );
		$teacher = $this->teacher_model->get ( $kTeach );
		$data ["gradeStart"] = $teacher->gradeStart;
		$data ["gradeEnd"] = $teacher->gradeEnd;
		$subject_list = $this->subject_model->get_for_teacher ( $kTeach );
		$data ["subject"] = $this->input->cookie ( "narrative_subject" ); // $this->session->userdata("narrative_subject");
		$data ["subjects"] = get_keyed_pairs ( $subject_list, array (
				"subject",
				"subject" 
		) );
		$grade_list = $this->menu_model->get_pairs ( "grade" );
		$data ["grades"] = get_keyed_pairs ( $grade_list, array (
				"value",
				"label" 
		) );
		$data ["target"] = "narrative/search_missing";
		$data ["title"] = "Search for Missing Narratives";
		$this->_view ( $data );
	}

	/**
	 * result of the search_missing function shows a list of all narratives the
	 * teacher has yet to write for the term.
	 * @TODO this should be updated to reflect that the report card system has a
	 * built-in list of students the teacher
	 * has entered that could be used to evaluate missing reports.
	 */
	function show_missing()
	{
		$this->load->model ( "subject_model" );
		$this->load->model ( "student_model" );
		$this->load->model ( "teacher_model" );
		$data ["kTeach"] = $this->input->get ( "kTeach" );
		$data ["gradeStart"] = $this->input->get ( "gradeStart" );
		$data ["gradeEnd"] = $this->input->get ( "gradeEnd" );
		$data ["subject"] = $this->input->get ( "subject" );
		$data ["narrYear"] = get_current_year ();
		$data ["narrTerm"] = get_current_term ();
		bake_cookie ( "narrative_subject", $data ["subject"] );
		$constraints = array ();
		if ($data ["subject"] == "Humanities") {
			$constraints ["humanitiesTeacher"] = $data ["kTeach"];
		} elseif ($data ["subject"] == "Academic Progress" || $data ["subject"] == "Social/Emotional") {
			$constraints ["kTeach"] = $data ["kTeach"];
		}
		$data ["students"] = $this->student_model->get_students_by_grade ( $data ["gradeStart"], $data ["gradeEnd"], $constraints );
		$data ["teacher"] = $this->teacher_model->get_name ( $data ["kTeach"] );
		$data ["target"] = "narrative/show_missing";
		$data ["title"] = "Showing Missing Narratives for " . $data ["teacher"];
		$this->load->view ( "page/index", $data );
	}

	/**
	 * print the narratives for a given student for a given term
	 * @TODO need to add the report cards as appropriate.
	 */
	function print_student_report()
	{
		if ($this->uri->segment ( 5 )) {
			$this->load->model ( "subject_sort_model" );
			$this->load->model ( "student_model" );
			$this->load->model ( "attendance_model" );
			$this->load->model ( "benchmark_model" );
			// $this->load->model("preference_model");
			$this->load->model ( "benchmark_legend_model", "legend" );
			$kStudent = $this->uri->segment ( 3 );
			$narrTerm = $this->uri->segment ( 4 );
			$narrYear = $this->uri->segment ( 5 );
			$student_obj = $this->student_model->get ( $kStudent, "stuFirst,stuLast,stuNickname,baseGrade,baseYear" );
			$student = format_name ( $student_obj->stuFirst, $student_obj->stuLast, $student_obj->stuNickname );
			$data ["stuGrade"] = get_current_grade ( $student_obj->baseGrade, $student_obj->baseYear, $narrYear );
			$attendance = $this->attendance_model->summarize ( $kStudent, $narrTerm, $narrYear );
			$data ["tardy"] = $attendance ["tardy"];
			$data ["absent"] = $attendance ["absent"];
			$data ["narrYear"] = $narrYear;
			$data ["narrTerm"] = $narrTerm;
			$narratives = $this->narrative_model->get_for_student ( $kStudent, array (
					"narrTerm" => $narrTerm,
					"narrYear" => $narrYear 
			) );
			
			$this->load->model ( "preference_model", "preference" );
			$data ["narratives"] = $narratives;
			// get letter grades for the reports
			$data ['grades'] = array ();
			foreach ( $narratives as $narrative ) {
				$kTeach = $narrative->kTeach;
				$submits_report_card = $this->preference->get ( $kTeach, "submits_report_card" );
				$data ['grades'] [$narrative->narrSubject] = $narrative->narrGrade;
				if ($submits_report_card == "yes") {
					$this->load->model ( "assignment_model", "assignment" );
					$this->load->helper ( "grade" );
					$grade_options ["from"] = "grade";
					$grade_options ["join"] = "assignment";
					$grade_options ['subject'] = $narrative->narrSubject;
					$grades = $this->assignment->get_for_student ( $kStudent, $narrative->narrTerm, $narrative->narrYear, $grade_options );
					if ($narrative->narrTerm == "Year-End") {
						$mid_year_grades = $this->assignment->get_for_student ( $kStudent, "Mid-Year", $narrative->narrYear, $grade_options );
				
					$final_grades = $this->assignment->get_for_student($kStudent, FALSE,$narrative->narrYear,$grade_options);
					}
					// change the narrGrade value if no grades have been entered
					// for this student.
					if (! empty ( $grades )) {
						$this->load->model ( "grade_preference_model", "grade_preferences" );
						$pass_fail = $this->grade_preferences->get_all ( $kStudent, array (
								"school_year" => $data ['narrYear'],
								"subject" => $narrative->narrSubject 
						) );
						$letter_grade = calculate_final_grade ( $grades );
						$data ['grades'] [$narrative->narrSubject] = calculate_letter_grade ( $letter_grade, $pass_fail );
						if ($narrative->narrTerm == "Year-End") {
							$mid_year_grade = calculate_final_grade ( $mid_year_grades );
							// a false value means no grades were entered for
							// the term--assumes student was not enrolled.
							if ($mid_year_grade) {
								$data ['mid_year_grades'] [$narrative->narrSubject] = calculate_letter_grade ( $mid_year_grade, $pass_fail );
									$data ['year_grade'] [$narrative->narrSubject] ['percent'] = $pass_fail?NULL:($letter_grade + $mid_year_grade) / 2;
									$data['final_grade'][$narrative->narrSubject] = calculate_final_grade($final_grades);
								$data ['year_grade'] [$narrative->narrSubject] ['grade'] = calculate_letter_grade ( ($letter_grade + $mid_year_grade) / 2, $pass_fail );
							} else {
								$data ['mid_year_grades'] [$narrative->narrSubject] = "Not Enrolled";
								$data ['year_grade'] [$narrative->narrSubject] ['percent'] = $pass_fail?NULL: $letter_grade;
								$data ['year_grade'] [$narrative->narrSubject] ['grade'] = calculate_letter_grade ( $letter_grade, $pass_fail );
							}
						}
					}
				}
			}
			$data ["student"] = $student;
			$data ["title"] = "Narrative Report for $student";
			$this->load->view ( "narrative/print", $data );
		}
	}

	/**
	 * Shows a form that allows editors to search and replace phrases in a batch
	 * of narratives based on selected
	 * criteria in the form.
	 */
	function search()
	{
		$data ["currentTerm"] = get_current_term ();
		$data ["terms"] = get_term_menu ( "narrTerm", $data ["currentTerm"] );
		$data ["currentYear"] = get_current_year ();
		$data ["years"] = get_year_list ();
		$this->load->model ( 'menu_model' );
		$grade_pairs = $this->menu_model->get_pairs ( "grade" );
		$data ["grades"] = get_keyed_pairs ( $grade_pairs, array (
				"value",
				"label" 
		) );
		$this->load->model ( 'teacher_model' );
		$teacher_pairs = $this->teacher_model->get_teacher_pairs ();
		$data ["teachers"] = get_keyed_pairs ( $teacher_pairs, array (
				"kTeach",
				"teacher" 
		),TRUE );
		$data ["kTeach"] = $this->session->userdata ( "userID" );
		$data ["target"] = "narrative/search";
		$data ["title"] = "Narrative Search & Replace";
		$this->session->set_flashdata("warning","Please follow the instructions very carefully. Mistakes may be recoverable, but not without considerable effort!");
		
		if($this->input->get("ajax")==1){
			$this->load->view($data['target'],$data);
		}else{
		$this->load->view ( "page/index", $data );
		}
	}

	/**
	 * processes the search() function's criteria and replaces text in
	 * narratives accordingly
	 */
	function replace()
	{
		$search = $this->input->post ( "search" );
		$replace = $this->input->post ( "replace" );
		$this->load->model ( "teacher_model" );
		$gradeStart = $this->input->post ( "gradeStart" );
		$gradeEnd = $this->input->post ( "gradeEnd" );
		$kTeach = $this->input->post ( "kTeach" );
		$narrYear = $this->input->post ( "narrYear" );
		$narrTerm = $this->input->post ( "narrTerm" );
		$data = $this->narrative_model->text_replace ( $search, $replace, $kTeach, $narrYear, $narrTerm, $gradeStart, $gradeEnd );
		$data ["gradeStart"] = $gradeStart;
		$data ["gradeEnd"] = $gradeEnd;
		$data ["teacher"] = $this->teacher_model->get ( $kTeach, array (
				"teachFirst",
				"teachLast" 
		) );
		$data ["narrYear"] = $narrYear;
		$data ["narrTerm"] = $narrTerm;
		$data ["replace"] = $replace;
		$data ["search"] = $search;
		$data ["target"] = "narrative/search_results";
		$data ["title"] = "Narrative Search & Replace Results";
		$this->load->view ( "page/index", $data );
	}

	/**
	 * show a list of previous saves that can be viewed and whose data can be
	 * copied into the current
	 * document as desired.
	 * This does not show backups for narratives that have been deleted.
	 * @TODO create an interface for showing deleted narratives for a given
	 * teacher, term, year or other criteria.
	 */
	function list_backups($kNarrative)
	{
		$this->load->model ( "backup_model" );
		$data ["backups"] = $this->backup_model->get_all ( $kNarrative, "recModified,narrText" );
		$data ["kNarrative"] = $kNarrative;
		$data ["target"] = "narrative/backup_list";
		$data ["title"] = "Narrative Backups";
		$this->load->view ( "page/index", $data );
	}
}
