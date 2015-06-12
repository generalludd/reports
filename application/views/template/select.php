<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

$count = count ( $templates );
if ($count == 0) {
	$yearEnd = strval ( $narrYear ) - 1;
	print "You have no <b>$narrSubject</b> templates created for grade $stuGrade during $narrTerm $narrYear-$yearEnd</p>";
	print "<p><a href='" . site_url ( "template/list_templates/?kTeach=$kTeach&term=$narrTerm&year=$narrYear" ) . "' class='link' title='Clicking the above link will interrupt creating a narrative for $studentName'>Click Here to create a new template</a></p>";
	
	print "<p><span class='highlight'>Clicking the above link will interrupt creating a narrative for $studentName</span><hr/>";
} else {
	print "Please choose the template you want to use for $studentName";
	foreach ( $templates as $template ) {
		
		$typeString = "";
		if (! empty ( $template->type )) {
			$typeString = "Type: $template->type";
		}
		$grades = format_grade_range ( $template->gradeStart, $template->gradeEnd );
		echo create_button_bar ( array (
				array (
						"text" => sprintf ( "%s for %s %s grade: %s %s", $template->subject, $template->term, format_schoolyear ( $template->year, $template->term ), $grades, $typeString ),
						"class" => "link",
						"href" => site_url ( "narrative/create?kStudent=$kStudent&kTeach=$kTeach&narrYear=$narrYear&narrTerm=$narrTerm&kTemplate=$template->kTemplate&narrSubject=$narrSubject" ) 
				) 
		) );
	}
} // end if-else
echo create_button_bar ( array (
		array (
				"text" => "Enter a narrative without a template",
				"class" => "link new",
				"href" => site_url ( "narrative/create?kStudent=$kStudent&kTeach=$kTeach&narrYear=$narrYear&narrTerm=$narrTerm&narrSubject=$narrSubject" ) 
		) ,
) );
?>