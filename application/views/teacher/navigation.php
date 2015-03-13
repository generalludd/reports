<?php
$buttons = array();
if($this->session->userdata("dbRole") == 1 && $kTeach != $this->session->userdata("userID") && $teacher->dbRole == 2){
	$buttons["list_narratives"] = array("selection" => "narrative", "type" => "span", "text" => "List Narratives", "class"=>array("button","teacher_narrative_search"),"id"=>"tns_$kTeach");
	$buttons["list_templates"] = array("selection" => "template/list_templates", "href"=>  site_url("template/list_templates/?kTeach=$kTeach&term=$term&year=$year") , "text" => "Subject Templates" );
	if($teacher->gradeEnd > 4){
		$buttons["list_grades"] = array("selection" => "assignment", "text" => "Grades","class"=> array("button","search-assignments"),"id" =>"sa_$kTeach","title" => "Search for current grade charts");
	}
	$buttons["list_students"] = array("selection" => "student/teacher_student_list", "href"=>site_url("student/teacher_student_list/$kTeach"), "text"=>"List Students");
	$buttons["list_teacher_narratives"] = array("selection" => "narrative/teacher_list", "href"=> site_url("narrative/teacher_list/$kTeach/print"), "text" => "Print Narratives" );

}
if($teacher->dbRole == 2 && $teacher->is_advisor == 1){
		$buttons[] = array("selection"=>"report/get_list/advisor", "href" =>site_url("report/get_list/advisor/$kTeach"),"text" => sprintf("%ss",STUDENT_REPORT));
}
$buttons[] = array("selection" =>"report/get_list/teacher","href"=>site_url("report/get_list/teacher/$kTeach"),"text"=> sprintf("Submitted %ss", STUDENT_REPORT));

$buttons[] = array("selection"=>"teacher/view","text"=>"Account Info","href"=>site_url("teacher/view/$kTeach"));

$options["selection"] = $this->uri->segment(1);
$options["id"] = "teacher-buttons";
$button_bar = create_button_bar($buttons, $options);

print $button_bar;
