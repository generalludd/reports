<?php
$dbRole = $this->session->userdata ( "dbRole" );
$userID = $this->session->userdata ( "userID" );
// $gradeStart = $this->session->userdata("gradeStart");
$gradeStart = $this->input->cookie ( "gradeStart" );
// $gradeEnd = $this->session->userdata("gradeEnd");
$gradeEnd = $this->input->cookie ( "gradeEnd" );
// $isAdvisor = $this->session->userdata("isAdvisor");
$isAdvisor = $this->input->cookie ( "isAdvisor" );
$unread_reports = $this->input->cookie ( "unread_reports" ); // $this->session->userdata("unread_reports");
$count = "";
$count_text = "";
if ($unread_reports > 0) {
	$count = sprintf ( "<span class='unread'>%s</span>", $unread_reports );
	$plural = "";
	if ($unread_reports > 1) {
		$plural = "s";
	}
	$count_text = sprintf ( "(You have %s unread report%s.)", $unread_reports, $plural );
}
$term = get_current_term ();
$year = get_current_year ();
$buttons [] = array (
		"selection" => "home",
		"text" => "Home",
		"href" => site_url () 
);
$buttons [] = array (
		"selection" => "search",
		"text" => '<input type="text" id="student-quick-search" name="student-quick-search" class="mobile" size="20" placeholder="find students" />',
		"type" => "pass-through" 
);
$buttons [] = array (
		"selection" => "attendance",
		"text" => "Check Attendance",
		"class" => "search button dialog mobile",
		"href" => site_url ( "attendance/check?search=1" ) 
);
$buttons [] = array (
		"selection" => "attendance",
		"text" => "Search Attendance",
		"class" => array (
				"button",
				"dialog" 
		),
		"href" => site_url ( "attendance/show_search" ),
		"title" => "Search attendance records",
		"dbRole" => 1 
);
$buttons [] = array (
		"selection" => "student",
		"text" => "New Student",
		"class" => array (
				"button",
				"new",
				"dialog" 
		),
		"href" => site_url ( "student/create" ),
		"title" => "Add a new student to the database",
		"dbRole" => 1 
);
$buttons [] = array (
		"selection" => "teacher",
		"text" => "List Teachers",
		"href" => site_url ( "teacher?gradeStart=0&gradeEnd=8" ),
		"title" => "List all the teachers &amp; other users in the database",
		"dbRole" => 1 
);
$buttons [] = array (
		"selection" => "student",
		"text" => "Edit Classes",
		"href" => site_url ( "student/edit_classes?search=1&ajax=1" ),
		"title" => "Organize students into the various classrooms, advisories, A/B, and humanities",
		"class" => array (
				"button",
				"dialog" 
		),
		"dbRole" => 1 
);

$buttons [] = array (
		"selection" => "narrative",
		"text" => "Narrative Search &amp; Replace",
		"href" => site_url ( "narrative/search" ),
		"title" => "Search &amp; Replace Narrative Text",
		"dbRole" => 1 
);

$buttons [] = array (
		"selection" => "benchmark",
		"text" => "Benchmark Templates",
		"class" => array (
				"button",
				"dialog" 
		),
		"href" => site_url ( "benchmark/search" ),
		"title" => "Edit your benchmark templates"
);

$buttons [] = array (
		"selection" => "overview",
		"text" => "Subject Overviews",
		"class" => array (
				"button",
				"dialog" 
		),
		"href" => site_url ( "overview/search/$userID" ),
		"dbRole" => 2,
		"title" => "Edit overviews for the subjects and grade ranges you teach" 
);

$buttons [] = array (
		"selection" => "template",
		"text" => "Subject Templates",
		"href" => site_url ( "template/list_templates/?kTeach=$userID&term=$term&year=$year" ),
		"dbRole" => 2 
);
$buttons [] = array (
		"selection" => "narrative/teacher_list",
		"text" => "Current Narratives",
		"class" => array (
				"button",
				"dialog" 
		),
		"href" => site_url ( "narrative/search_teacher_narratives/$userID" ),
		"title" => "List all of your narratives",
		"dbRole" => 2 
);
$buttons [] = array (
		"selection" => "narrative/show_missing",
		"text" => "Missing Narratives",
		"class" => array (
				"button",
				"dialog" 
		),
		"href" => site_url ( "narrative/search_missing/$userID" ),
		"title" => "Show the students for whom you have not yet written a report this term",
		"dbRole" => 2 
);
if ($dbRole == 2) {
	if ($isAdvisor) {
		$buttons [] = array (
				
				"selection" => "report/get_list",
				"text" => sprintf ( "%ss%s", STUDENT_REPORT, $count ),
				"href" => site_url ( "report/get_list/advisor/$userID" ),
				"title" => sprintf ( "Show your %ss %s", strtolower ( STUDENT_REPORT ), $count_text ) 
		);
	}
	if ($gradeEnd > 4) {
		$buttons [] = array (
				"selection" => "assignment",
				"text" => "Grades",
				"class" => array (
						"button",
						"dialog" 
				),
				"id" => "sa_$userID",
				"title" => "Search for current grade charts",
				"href" => site_url ( "assignment/search/$userID" ) 
		);
	}
	
	$buttons [] = array (
			"selection" => "student",
			"text" => "List Students",
			"href" => site_url ( "student/search?kTeach=$userID&year=" . get_current_year () ),
			"title" => "List your students" 
	);
}
if($dbRole==2 || $dbRole ==3){
	$buttons [] = array (
			"selection" => "student",
			"text" => "View Classes",
			"href" => site_url ( "student/view_classes?search=1&ajax=1" ),
			"title" => "View students in the various classrooms, advisories, A/B, and humanities",
			"class" => array (
					"button",
					"dialog"
			),
	);
}
print create_button_bar ( $buttons );
