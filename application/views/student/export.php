<?php defined('BASEPATH') OR exit('No direct script access allowed');

$filename = "student_export.csv";
$header = "First Name,Nickname,Last Name,Gender,Birthday,Age,Current Grade,Enrollment Grade,Enrollment Year,Email,Email Active";
if($this->session->userdata("userID") == 1000){
	$header .= ",Email Password";
}
$output = array($header);
foreach($students as $student){
	$line[] = $student->stuFirst;
	$line[] = $student->stuNickname;
	$line[] = $student->stuLast;
	$line[] = $student->stuGender;
	$line[] = format_date($student->stuDOB, "standard");
	$line[] = get_age($student->stuDOB);
	$line[] = format_grade($student->stuGrade);
	$line[] = format_grade($student->baseGrade);
	$line[] = $student->baseYear;
	$line[] = $student->stuEmail;
	if($student->stuEmailPermission == 1){
		$line[] = "Yes";
	}else{
		$line[] = "No";
	}
	if($this->session->userdata("userID") == 1000){
		$line[] = $student->stuEmailPassword;
	}
	$output[] = implode(",", $line);
	$line = NULL;
}

$data = implode("\n", $output);
force_download($filename, $data);