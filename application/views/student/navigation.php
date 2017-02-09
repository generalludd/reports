<?php
$today = date("Y-m-d");
if($today < MID_YEAR){
	$start_date = YEAR_START;
}else{
	$start_date = MID_YEAR;
}
$buttons[] = array("selection" => "student/[A-z]", "href" => site_url("student/view/$kStudent"), "text"=>"Student Info");

$buttons[] =array("selection" => "narrative", "href"=>site_url("narrative/student_list/$kStudent"), "text"=>"Narratives");
// $buttons[] = array("selection" => "attendance", "href"=>  site_url("student_attendance/search/$kStudent?startDate=$start_date&endDate=$today"), "text" => "Attendance" );

// $buttons[] = array("selection"=>"attendance","href"=>site_url("student_attendance/create/$student->kStudent"),"class"=>"button dialog new","text"=>"Add Attendance");

$buttons[] = array("selection"=>"benchmarks", "href"=>site_url("student_benchmark/select/?search=true&kStudent=$kStudent&refine=1"), "class"=>"button dialog", "text"=>"Benchmarks", "title"=>"Search for this student&rsquo;s benchmarks");
$buttons[] = array("selection" => "support", "href"=> site_url("support/list_all/$kStudent"), "text" => "Learning Support" );
if(get_value($student,"stuGrade",0) >= 6){
	$buttons[] = array("selection" => "report/get_list/student", "href" => site_url("report/get_list/student/$kStudent"), "text" => sprintf("%ss",STUDENT_REPORT));
	$buttons[] = array("selection" => "grade/report_card", "class"=> array("button","dialog"), "id"=> sprintf("gss_%s",$kStudent), "text" => "Grades", "href" => site_url("grade/select_report_card/$kStudent"));
}
$options["selection"] = $this->uri->segment(1);
$options["id"] = "student-buttons";
$button_bar = create_button_bar($buttons, $options);
echo $button_bar;
