<?php defined('BASEPATH') OR exit('No direct script access allowed');
$edit_buttons[] = array("item" => "student", "text" => "Student Info", "class" => "button info", "href"=>site_url("student/view/$kStudent"));
$edit_buttons[] = array("item" => "report", "text" => "Add $student_report", "class" => "button new", "href" => site_url("report/create/$kStudent"));
?>
<h3>
	<?=$title;?>
</h3>
<?=create_button_bar($edit_buttons);?>
<input
	type="hidden" id="report_type" name="report_type"
	value="<?=$report_type;?>" />
<input
	type="hidden" id="report_key" name="report_key"
	value="<?=$report_key;?>" />

<fieldset class="search_fieldset">
	<legend>Search Parameters</legend>
	<?
	if(isset($options)){

		$keys = array_keys($options);
		$values = array_values($options);

		echo "<ul>";

		for($i = 0; $i < count($options); $i++){
			$key = $keys[$i];
			$value = $values[$i];
			switch($key){
				case "date_range":
					$date_start = $options["date_range"]["date_start"];
					$date_end = $options["date_range"]["date_end"];
					echo "<li>From: <strong>$date_start</strong></li>";
					echo "<li>To: <strong>$date_end</strong></li>";
					break;
			}
		}
		echo "</ul>";

	}else{
		echo "<p>Showing All Submissions</p>";
	}
	?>

	<div class="button-box">
		<a class="button report_search" id="student_<?=$kStudent?>">Refine
			Search</a>
	</div>
</fieldset>
<?if(!empty($reports)):?>
<table class="report list">
	<thead>
		<tr>
			<th>Category</th>
			<th>Submitted by</th>
			<th>Date</th>
			<th>Rank</th>

			<th>Read</th>

			<th></th>
		</tr>

	</thead>
	<tbody>

		<?
		foreach($reports as $report){
			$teacher =  format_name($report->teachFirst, $report->teachLast);?>
		<tr>
			<td><?=$report->category;
			if($report->category == "Missing Homework"){
				if($report->assignment_status == 1){
					echo  " (Turned In Late)";
				}
			}
			?>
			</td>
			<td><?=$teacher;?></td>
			<td><?=format_date($report->report_date,"standard");?></td>
			<td><?=$report->rank;?></td>
			<td><?
			$checked = "";
			if($report->is_read == 1){
				$checked = "checked";
			}
			$readonly = "readonly";
			if($report->kAdvisor == $this->session->userdata("userID")){
				$readonly = "";
			}
			?> <input type="checkbox" value="1"
				id="is-read_<?=$report->kReport;?>" class="report-is-read" name="is_read" <?=$readonly;?> <?=$checked?> />
			</td>

			<td><a href="<?=site_url("report/edit/$report->kReport");?>"
				class="button edit">Edit</a></td>

			<?}



			?>
		</tr>
	</tbody>
</table>
<? elseif(isset($options)): ?>
<p>No reports have been submitted for this student within the given
	search range.</p>
<? else:?>
<p>No reports have been submitted for this student.</p>
<? endif; ?>
