<?php
$buttons = array();
if($teacher->dbRole == 2){

	$buttons[] = array("item" => "student", "href"=>site_url("student/teacher_student_list/$kTeach"), "text"=>"List Students");
	//$buttons[] = array("item" => "narrative", "href"=> site_url("narrative/teacher_list/$kTeach"), "text" => "List Narratives" );
	$buttons[] = array("item" => "narrative", "type" => "span", "text" => "List Narratives", "class"=>"button teacher_narrative_search","id"=>"tns_$kTeach");

	$buttons[] = array("item" => "narrative", "href"=> site_url("narrative/teacher_list/$kTeach/print"), "text" => "Print Narratives" );
	$buttons[] = array("item" => "template", "href"=>  site_url("template/list_templates/?kTeach=$kTeach&term=$term&year=$year") , "text" => "Subject Templates" );
	if($teacher->is_advisor == 1){
		$buttons[] = array("item"=>"report", "href" =>site_url("report/advisor_list/$kTeach"),"text" => "Orange Slips");
	}
	$options["selection"] = $this->uri->segment(1);
	$options["id"] = "teacher-buttons";
}
$buttons[] = array("item" =>"report","href"=>site_url("report/teacher_list/$kTeach"),"text"=>"Submitted Orange Slips");

$button_bar = create_button_bar($buttons, $options);

print $button_bar;
