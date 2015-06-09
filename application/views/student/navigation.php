<?php

$buttons[] = array("selection" => "student/[A-z]", "href" => site_url("student/view/$kStudent"), "text"=>"Student Info");

$buttons[] =array("selection" => "narrative", "href"=>site_url("narrative/student_list/$kStudent"), "text"=>"Narratives");

$buttons[] = array("selection" => "attendance", "href"=>  site_url("attendance/search/$kStudent"), "text" => "Attendance" );

$buttons[] = array("selection" => "support", "href"=> site_url("support/list_all/$kStudent"), "text" => "Learning Support" );
if(get_value($student,"stuGrade",0) >= 5){
	$buttons[] = array("selection" => "report/get_list/student", "href" => site_url("report/get_list/student/$kStudent"), "text" => sprintf("%ss",STUDENT_REPORT));
	$buttons[] = array("selection" => "grade/report_card", "class"=> array("button","dialog"), "id"=> sprintf("gss_%s",$kStudent), "text" => "Grades", "href" => base_url("grade/select_report_card/$kStudent"));
}
$options["selection"] = $this->uri->segment(1);
$options["id"] = "student-buttons";
$button_bar = create_button_bar($buttons, $options);
echo $button_bar;
