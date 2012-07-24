<?php defined('BASEPATH') OR exit('No direct script access allowed');
$edit_buttons[] = array("item" => "student", "text" => "Student Info", "class" => "button info", "href"=>site_url("student/view/$kStudent"));
$edit_buttons[] = array("item" => "report", "text" => "Add $student_report", "class" => "button new", "href" => site_url("report/create/$kStudent"));
?>
<h3>
	<?=$title;?>
</h3>
<?=create_button_bar($edit_buttons);?>
<table class="report list">
	<thead>
		<tr>
			<th>Category</th>
			<th>Submitted by</th>
			<th>Date</th>
			<th></th>
		</tr>

	</thead>
	<tbody>

		<?
		foreach($reports as $report){
	$teacher =  format_name($report->teachFirst, $report->teachLast);?>
		<tr>
			<td><?=$report->category;?></td>
			<td><?=$teacher;?></td>
			<td><?=format_date($report->report_date,"standard");?></td>
			
			<td><a href="<?=site_url("report/edit/$report->kReport");?>"
				class="button edit">Edit</a></td>
			<?}?>
	
	</tbody>
</table>
