<?php defined('BASEPATH') OR exit('No direct script access allowed');
$edit_buttons[] = array("item" => "student", "text" => "Student Info", "class" => "button info", "href"=>site_url("student/view/$kStudent"));
$edit_buttons[] = array("item" => "report", "text" => "Add $student_report", "class" => "button new", "href" => site_url("report/create/$kStudent"));
?>
<h3>
	<?=$title;?>
</h3>
<?=create_button_bar($edit_buttons);?>


<?if(!empty($reports)):?>

<? $this->load->view("report/table.php",array($reports,$type="student"));?>
<? elseif(isset($options)): ?>
<p>No reports have been submitted for this student within the given
	search.</p>
<? else:?>
<p>No reports have been submitted for this student.</p>
<? endif; 
