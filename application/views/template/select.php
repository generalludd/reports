<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


?>

<form name='template_selector' method='post'
	action='<?=site_url("narrative/create");?>' id='template_selector'><?
	$count=count($templates);
	print "<input type='hidden' id='kStudent' name='kStudent' value='$kStudent' />";//used for ajax functionality
	print "<input type='hidden' id='kTeach' name='kTeach' value='$kTeach' />";//used for ajax functionality
	print "<input type='hidden' id='narrYear' name='narrYear' value='$narrYear'/>";//used for ajax
	print "<input type='hidden' id='narrTerm' name='narrTerm' value='$narrTerm'/>";//used for ajax
	print "<input type='hidden' id='narrSubject' name='narrSubject' value='$narrSubject'/>";//used for ajax
	print "<input type='hidden' id='kTemplate' name='kTemplate' value=0/>";
	if($count==0){
		$yearEnd = strval($narrYear)-1;
		print "You have no <b>$narrSubject</b> templates created for grade $stuGrade during $narrTerm $narrYear-$yearEnd</p>";
		print "<p><a href='" . site_url("template/list_templates/?kTeach=$kTeach&term=$narrTerm&year=$narrYear") . "' class='link' title='Clicking the above link will interrupt creating a narrative for $studentName'>Click Here to create a new template</a></p>";

		print "<p><span class='highlight'>Clicking the above link will interrupt creating a narrative for $studentName</span><hr/>";
	}else{
		print "Please choose the template you want to use for $studentName";
		foreach($templates as $template){
			$typeString = "";
			if(!empty($template->type)){
				$typeString = "Type: $template->type";
			}
			$grades = format_grade_range($template->gradeStart, $template->gradeEnd);
			print "<p><span class='button add_narrative' id='t_$template->kTemplate'>$template->subject for $template->term $template->year, grade: $grades $typeString</span></p>";
		}
		print "<hr/>";
	}//end if-else
	?>
<p><span class='button add_narrative'>Enter a narrative without a
template</span></p>

</form>
