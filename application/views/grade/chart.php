<?php defined('BASEPATH') OR exit('No direct script access allowed');
#student grade chart;
$header = $grades[0];
$student = format_name($header->stuFirst, $header->stuLast, $header->stuNickname);
$teacher = format_name($header->teachFirst,$header->teachLast);
$year = format_schoolyear($header->year);
$student_total = 0;
$assignment_count = 0;

?>
<h2>Friends School of Minnesota</h2>
<h3>
	<?=$student;?>
</h3>
<p>
	<?="$header->subject<br/>$header->term, $year";?>
	<br />
	<?=$teacher;?>
</p>
<table class="report-card">
	<thead>
		<tr>
			<th class='date-column'>Date</th>
			<th class='assignment-column'>Assignment</th>
			<th class='category-colunn'>Category</th>
			<th class='points-column'>Points</th>
			<th class='totals-column'>Possible</th>
			<th class='notes-column'></th>
		</tr>
	</thead>
	<tbody>
		<?
foreach($grades as $grade){?>
		<tr>
			<td><?=format_date($grade->date,"standard");?></td>
			<td><?=$grade->assignment;?></td>
			<td><?=$grade->category;?></td>
			<td><?=$grade->status?$grade->status:$grade->points;?></td>
			<td><?=$grade->total_points;?></td>
			<td class='notes-column'><?=$grade->footnote != 0 ? $grade->label:"";?>
			</td>
		</tr>
		<?
		$student_total += $grade->average;
		$assignment_count++;
}
?>

	</tbody>
	<tfoot>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td> <?
		$final_grade = round(($student_total/$assignment_count),2) * 100;
		echo calculate_letter_grade($final_grade) . " ($final_grade%)";?></td>
		<td></td>
		<td class='notes-column'></td>
	</tr>
	</tfoot>
</table>
