<?php
	defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
	class Grade extends MY_Controller {

		function __construct()
		{
			parent::__construct ();
			$this->load->model ( "grade_model", "grade" );
			$this->load->model ( "assignment_model", "assignment" );
		}

		function index()
		{
			redirect ();
		}

		/**
		 * editing the grades for a given student, term, subject, teacher
		 * the query script get_for_student in this context returns all the
		 * assignments possible for easy editing as a chart.
		 */
		function edit($kStudent,$kTeach)
		{
			$this->load->model ( "menu_model" );
			if ($kStudent && $kTeach) {
				$year = $this->input->get ( "year" );
				if (! $year) {
					$year = $this->input->cookie ( "year" ); // get_current_year();
				}
				$term = $this->input->get ( "term" );
				if (! $term) {
					$term = $this->input->cookie ( "term" ); // get_current_term();
				}
				$options ["grade_range"] ["gradeStart"] = $this->input->get ( "assignment_grade_start" );
				if (! $options ["grade_range"] ["gradeStart"]) {
					$options ["grade_range"] ["gradeStart"] = get_cookie ( "assignment_grade_start" );
				}
				$options ["grade_range"] ["gradeEnd"] = $this->input->get ( "assignment_grade_end" );
				if (! $options ["grade_range"] ["gradeEnd"]) {
					$options ["grade_range"] ["gradeEnd"] = get_cookie ( "assignment_grade_end" );
				}
				
				$footnotes = $this->menu_model->get_pairs ( "grade_footnote" );
				$data ["footnotes"] = get_keyed_pairs ( $footnotes, array (
						"value",
						"label" 
				), TRUE );
				$status = $this->menu_model->get_pairs ( "grade_status" );
				
				$data ["status"] = get_keyed_pairs ( $status, array (
						"value",
						"label" 
				), TRUE );
				$data ["kStudent"] = $kStudent;
				$data ["kTeach"] = $kTeach;
				$options ["kTeach"] = $kTeach;
				$data ["grades"] = $this->assignment->get_for_student ( $kStudent, $term, $year, $options );
				$data ["target"] = "grade/edit";
				$this->load->model("student_model");
				$student = $this->student_model->get_name($kStudent);
				$data ['title'] = "Editing Grades for $student";
				if ($this->input->get ( "ajax" )) {
					$this->load->view ( $data ['target'], $data );
				} else {
					$this->load->view ( "page/index", $data );
				}
			}
		}

		/**
		 * edit_cell allows editing of a single grade in a chart for a given
		 * student.
		 */
		function edit_cell()
		{
			$kAssignment = $this->input->get ( "kAssignment" );
			$kStudent = $this->input->get ( "kStudent" );
			if ($kAssignment && $kStudent) {
				$this->load->model ( "menu_model" );
				$data ["grade"] = $this->grade->get ( $kStudent, $kAssignment );
				$footnotes = $this->menu_model->get_pairs ( "grade_footnote" );
				$data ["footnotes"] = get_keyed_pairs ( $footnotes, array (
						"value",
						"label" 
				) );
				$status = $this->menu_model->get_pairs ( "grade_status" );
				$data ["status"] = get_keyed_pairs ( $status, array (
						"value",
						"label" 
				) );
				$this->load->view ( "grade/edit_cell", $data );
			}
		}

		/**
		 * Edit a column of grades by assignment
		 */
		function edit_column($kAssignment)
		{
			$stuGroup = NULL;
			if ($this->input->cookie ( "stuGroup" )) {
				$stuGroup = $this->input->cookie ( "stuGroup" );
			}
			$this->load->model ( "menu_model" );
			$data ["assignment"] = $this->assignment->get ( $kAssignment );
			$data ["grades"] = $this->assignment->get_assignment_grades ( $kAssignment, $stuGroup );
			$footnotes = $this->menu_model->get_pairs ( "grade_footnote" );
			$data ["footnotes"] = get_keyed_pairs ( $footnotes, array (
					"value",
					"label" 
			), TRUE );
			$status = $this->menu_model->get_pairs ( "grade_status" );
			$data ["status"] = get_keyed_pairs ( $status, array (
					"value",
					"label" 
			), TRUE );
			$data ["target"] = "grade/edit_column";
			$data ["title"] = "Editing Grades for a Given Assignment";
			if ($this->input->get ( "ajax" )) {
				$this->load->view ( $data ["target"], $data );
			} else {
				$this->load->view ( "page/index", $data );
			}
		}

		/**
		 * select_student offers a selection option to find a student to add to a
		 * grade chart.
		 */
		function select_student()
		{
			$data ["kTeach"] = $this->input->get ( "kTeach" );
			$data ["term"] = get_cookie ( "term" );
			$data ["year"] = get_cookie ( "year" );
			$data ["js_class"] = "select-student-for-grades";
			$data ["action"] = "grade/edit";
			$this->load->view ( "student/mini_selector", $data );
		}

		/**
		 * update also inserts (through the update script in grade_method).
		 */
		function update()
		{
			$kStudent = $this->input->post ( "kStudent" );
			$kAssignment = $this->input->post ( "kAssignment" );
			if ($kStudent && $kAssignment) {
				$points = $this->input->post ( "points" );
				$status = $this->input->post ( "status" );
				$footnote = $this->input->post ( "footnote" );
				$result = $this->grade->update ( $kStudent, $kAssignment, $points, $status, $footnote );
				$grade = $this->grade->get ( $kStudent, $kAssignment );
				$points = $grade->points;
				if ($grade->points == 0 && $grade->assignment_total == 0) {
					$points = "";
				} // end if points
				
				if ($grade->footnote) {
					$points .= "[$grade->footnote]";
				} // end if footnote
				echo $points;
			}
		}

		/**
		 * update_value updates a grade value based on a grade.
		 * There can be only one
		 * kStudent-kAssignment pair in the db
		 */
		function update_value()
		{
			$kStudent = $this->input->post ( "kStudent" );
			$kAssignment = $this->input->post ( "kAssignment" );
			if ($kStudent && $kAssignment) {
				$key = $this->input->post ( "key" );
				$value = $this->input->post ( "value" );
				$result = $this->grade->update_value ( $kStudent, $kAssignment, $key, $value );
				echo OK;
			}
		}

		/**
		 * delete_row this deletes all of a students records for an entire term.
		 *
		 * warnings are provided through jQuery/javascript assignment.js file
		 */
		function delete_row()
		{
			$kTeach = $this->input->post ( "kTeach" );
			$kStudent = $this->input->post ( "kStudent" );
			$year = $this->input->post ( "year" );
			$term = $this->input->post ( "term" );
			$this->grade->delete_row ( $kStudent, $kTeach, $term, $year );
			echo $this->db->last_query ();
		}

		function batch_print()
		{
			if ($this->input->post ( "action" ) == "select") {
				
				$this->load->model ( "subject_model" );
				$kTeach = $this->input->post ( "kTeach" );
				$subjects = $this->subject_model->get_for_teacher ( $kTeach );
				$data ['subjects'] = get_keyed_pairs ( $subjects, array (
						'subject',
						'subject' 
				) );
				$data ["stuGroup"] = FALSE;
				$data ['kTeach'] = $kTeach;
				$data ["gradeStart"] = get_cookie ( "assignment_grade_start" );
				$data ["gradeEnd"] = get_cookie ( "assignment_grade_end" );
				
				$data ['ids'] = implode ( ",", $this->input->post ( "ids" ) );
				$this->load->view ( "grade/batch_selector", $data );
			} elseif ($this->input->post ( "action" ) == "print") {
				
				$ids = explode ( ",", $this->input->post ( "ids" ) );
				$kTeach = $this->input->post ( "kTeach" );
				$term = $this->input->post ( "term" );
				$year = $this->input->post ( "year" );
				$subject = $this->input->post ( "subject" );
				$this->load->model ( "student_model", "student" );
				$options = array ();
				$options ["from"] = "grade";
				$options ["join"] = "assignment";
				$options ["kTeach"] = $kTeach;
				
				$this->load->model ( "grade_preference_model", "grade_preferences" );
				$options ['subject'] = $subject;
				foreach ( $ids as $kStudent ) {
					$data = array ();
					$data ["subject"] = $subject;
					$data ["pass_fail"] = $this->grade_preferences->get_all ( $kStudent, array (
							"school_year" => $year,
							"subject" => $subject ,
							"term"=>$term,
					) );
					$data ["print_student_name"] = TRUE;
					$data ["student"] = $this->student->get ( $kStudent );
					$data ["grades"] = $this->assignment->get_for_student ( $kStudent, $term, $year, $options );
					$data ["batch"] = TRUE;
					$output ["charts"] [] = $this->load->view ( "grade/chart", $data, TRUE );
				}
				
				$output ["title"] = "Batch Report Cards";
				$output ["term"] = $term;
				$output ["year"] = $year;
				$output ["target"] = "grade/report_card";
				$this->load->view ( "page/index", $output );
			}
		}

		/**
		 * select_report_card provides a dialog for selecting the report card for a
		 * given student based on year, term, cutoff-date, and subject
		 */
		function select_report_card($kStudent)
		{
			if ($kStudent) {
				$data ["kStudent"] = $kStudent;
				$this->load->model ( "student_model" );
				$student = $this->student_model->get_name ( $kStudent );
				$term = get_current_term ();
				$data ["terms"] = get_term_menu ( "term", $term );
				$year = get_current_year ();
				$data ["years"] = get_year_list ();
				$subjects = $this->grade->get_subjects ( $kStudent, $term, $year );
				$data ["subjects"] = get_keyed_pairs ( $subjects, array (
						"subject",
						"subject" 
				), TRUE );
				$data ["title"] = "Search For Grades for $student";
				$data ["target"] = "grade/selector";
				if ($this->input->get ( "ajax" )) {
					$this->load->view ( $data ["target"], $data );
				} else {
					$this->load->view ( "page/index", $data );
				}
			}
		}

		/**
		 * report_card displays a report card based on submitted criteria for year,
		 * term, cutoff-date, and subject.
		 */
		function report_card($print = FALSE)
		{
			if ($kStudent = $this->input->get ( "kStudent" )) {
				$this->load->model ( "student_model", "student" );
				$kTeach = NULL;
				$options = array ();
				$options ["from"] = "grade";
				$options ["join"] = "assignment";
				
				if ($kTeach = $this->input->get ( "kTeach" )) {
					$options ["kTeach"] = $kTeach;
				}
				$output ["cutoff_date"] = FALSE;
				if ($cutoff_date = $this->input->get ( "cutoff_date" )) {
					bake_cookie ( "cutoff_date", $cutoff_date );
					$options ["cutoff_date"] =  $cutoff_date;
					$output ["cutoff_date"] = format_date($cutoff_date);
				}
				
				$term = get_current_term ();
				if ($this->input->get ( "term" )) {
					$term = $this->input->get ( "term" );
				}
				$output ["term"] = $term;
				
				$year = get_current_year ();
				if ($this->input->get ( "year" )) {
					$year = $this->input->get ( "year" );
				}
				$output ["year"] = $year;
				$student = $this->student->get ( $kStudent, "stuNickname,stuLast" );
				
				//if there's a subject submitted, then use that, otherwise get all the subjects
				//the student is registered for
				if ($subject = $this->input->get ( "subject" )) {
					$array = array (
							"subject" => $subject 
					);
					$subjects [] = ( object ) $array;
				} else {
					$subjects = $this->grade->get_subjects ( $kStudent, $term, $year );
				}
				
				$data ["target"] = "grade/report_card";
				$data ["kStudent"] = $kStudent;
				$data ["student"] = $student;
				$output ["student_name"] = format_name ( $student->stuNickname, $student->stuLast );
				$data ["title"] = $output ["student_name"];
				$output ["charts"] = array ();
				$i = 0;
				foreach ( $subjects as $subject ) {
					if ($subject->subject != "Music") { // music does not offer grades for print-out
						$options ["subject"] = $subject->subject;
						$data ["grades"] = $this->assignment->get_for_student ( $kStudent, $term, $year, $options );
						// if the student has any grades entered, process them here,
						// otherwise ignore.
						$this->load->model ( "grade_preference_model", "grade_preferences" );
						$data ["pass_fail"] = $this->grade_preferences->get_all ( $kStudent, array (
								"school_year" => $year,
								"subject" => $subject->subject,
								"term"=>$term,
						) );
						if (count ( $data ["grades"] )) {
							$data ["subject"] = $subject->subject;
							$data ["count"] = $i; // count is used to identify the chart
							                      // number in the output for css
							                      // purposes.
							$output ["charts"] [] = $this->load->view ( "grade/chart", $data, TRUE );
						}
					}
				}
				
				if ($output) {
					
					$this->load->view ( "page/index", $output, $print );
				}
			}
		}
	}