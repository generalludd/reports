<?php defined('BASEPATH') OR exit('No direct script access allowed');

?>
<h3>
	<?=$title;?>
</h3>
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
			<a class="button report_search">Refine Search</a>
		</div>
	</fieldset>
	<? if($reports):?>
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
	$student =  format_name($report->stuFirst, $report->stuLast, $report->stuNickname);?>
		<tr>
			<td><?=$report->category;?></td>
			<td><?=$student;?></td>
			<td><?=format_date($report->report_date,"standard");?></td>
			<td><a href="<?=site_url("report/edit/$report->kReport");?>"
				class="button edit">Edit</a></td>
			<?}
			?>
	
	</tbody>
</table>
<?else:?>
<p>No reports are available for the given search period</p>
<? endif;