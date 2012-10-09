<?php
$buttons = array();
if($teacher->dbRole == 2){

	$buttons[] = array("item" => "student", "href"=>site_url("student/teacher_student_list/$kTeach"), "text"=>"List Students");
	//$buttons[] = array("item" => "narrative", "href"=> site_url("narrative/teacher_list/$kTeach"), "text" => "List Narratives" );
	$buttons[] = array("item" => "narrative", "type" => "span", "text" => "List Narratives", "class"=>"button teacher_narrative_search","id"=>"tns_$kTeach");

	$buttons[] = array("item" => "narrative", "href"=> site_url("narrative/teacher_list/$kTeach/print"), "text" => "Print Narratives" );
	$buttons[] = array("item" => "template", "href"=>  site_url("template/list_templates/?kTeach=$kTeach&term=$term&year=$year") , "text" => "Subject Templates" );
	if($teacher->is_advisor == 1){
		$buttons[] = array("item"=>"report", "href" =>site_url("report/get_list/advisor/$kTeach"),"text" => sprintf("%ss",STUDENT_REPORT));
	}

}
$buttons[] = array("item" =>"report","href"=>site_url("report/get_list/teacher/$kTeach"),"text"=> sprintf("Submitted %ss", STUDENT_REPORT));
if($teacher->gradeEnd > 4){
	$buttons[] = array("item" => "assignment", "text" => "Grades","class"=> "button search-assignments","id" =>"sa_$kTeach","title" => "Search for current grade charts");
}

$options["selection"] = $this->uri->segment(1);
$options["id"] = "teacher-buttons";
$button_bar = create_button_bar($buttons, $options);

print $button_bar;
