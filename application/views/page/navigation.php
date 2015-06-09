<?php
$dbRole = $this->session->userdata("dbRole");
$userID = $this->session->userdata("userID");
//$gradeStart = $this->session->userdata("gradeStart");
$gradeStart = $this->input->cookie("gradeStart");
//$gradeEnd = $this->session->userdata("gradeEnd");
$gradeEnd = $this->input->cookie("gradeEnd");
//$is_advisor = $this->session->userdata("is_advisor");
$is_advisor = $this->input->cookie("is_advisor");
$unread_reports = $this->input->cookie("unread_reports");//$this->session->userdata("unread_reports");
$count = "";
$count_text = "";
if($unread_reports > 0){
	$count = sprintf("<span class='unread'>%s</span>",$unread_reports);
	$plural = "";
	if($unread_reports > 1){
		$plural = "s";
	}
	$count_text = sprintf("(You have %s unread report%s.)",$unread_reports,$plural);
}
$term = get_current_term();
$year = get_current_year();
$buttons[] = array("selection"=>"home", "text"=>"Home", "href" => base_url() );
$buttons[] = array("selection" =>"search", "text" => '<input type="text" id="stuSearch" name="stuSearch" size="20" value="find students" />', "type" => "pass-through");

if($dbRole == 1){
	$buttons[] = array("selection" => "student", "text" => "New Student", "class" => array("button","new","add_student"), "type"=>"span", "title" => "Add a new student to the database");
	$buttons[] = array("selection" => "attendance" , "text" => "Search Attendance", "class" => array("button","dialog"), "href" => base_url("attendance/show_search"), "title" => "Search attendance records");
	$buttons[] = array("selection" => "teacher", "text" => "List Teachers", "href" => site_url("teacher?gradeStart=0&gradeEnd=8"), "title" => "List all the teachers &amp; other users in the database");
	$buttons[] = array("selection" => "narrative", "text" => "Narrative Search &amp; Replace", "href" => site_url("narrative/search"), "title" => "Search &amp; Replace Narrative Text");
}elseif($dbRole == 3){ //aides
	$buttons[] = array("selection" => "support", "text" => "Learning Support", "href" => site_url("student/advanced_search?hasNeeds=1&year=" . get_current_year()) );
}else{
	$buttons[] = array("selection" => "template", "text" => "Subject Templates", "href" => site_url("template/list_templates/?kTeach=$userID&term=$term&year=$year"));
	$buttons[] = array("selection" => "benchmark", "text" => "Benchmarks", "class" => array("button","show_benchmark_search"), "type" => "span");
	$buttons[] = array("selection" => "narrative/teacher_list", "text" => "Current Narratives", "class"=>array("button","teacher_narrative_search"), "id"=>"navigation-narrative-search_$userID", "title" => "List all of your narratives" );
	$buttons[] = array("selection" => "narrative/show_missing", "text" => "Missing Narratives", "class" => array("button","missing_narrative_search"), "id" => "mns_$userID", "title" => "Show the students for whom you have not yet written a report this term" );
if($is_advisor){
	$buttons[] = array("selection" => "report/get_list", "text" => sprintf("%ss%s",STUDENT_REPORT,$count), "href"=> site_url("report/get_list/advisor/$userID"), "title" => sprintf("Show your %ss %s", strtolower(STUDENT_REPORT),$count_text));

}
	if($gradeEnd > 4){
		$buttons[] = array("selection" => "assignment", "text" => "Grades","class"=> array("button","search-assignments"),"id" =>"sa_$userID","title" => "Search for current grade charts");
	}

	$buttons[] = array("selection" => "student", "text" => "List Students", "href" => site_url("student/teacher_student_list/$userID"));
}
print create_button_bar($buttons, array("id" =>"navigation-buttons"));

?>





