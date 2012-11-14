<?php defined('BASEPATH') OR exit('No direct script access allowed');
#student grade chart;
$header = $grades[0];
$teacher = format_name($header->teachFirst,$header->teachLast);
$year = format_schoolyear($header->year);
$student_total = 0;
$assignment_count = 0;
$assignment_total = 0;
$footnotes = array();
$categories = array();
?>

<div class='report-header report-teacher report-<?=$count;?>'>
	<?="$header->subject, $teacher";?>
</div>
<div class='report-body'>
	<table class="report-card">
		<thead>
			<tr>
				<th class='date-column'>Date</th>
				<th class='assignment-column'>Assignment</th>
				<th class='category-colunn'>Category</th>
				<th class='points-column'>Points</th>
				<th class='totals-column'>Possible</th>
				<!-- <th class='notes-column'></th> -->
			</tr>
		</thead>
		<tbody>
			<?	foreach($grades as $grade){
				if(($grade->points > 0 && $grade->total_points == 0) || ($grade->total_points > 0)){
					?>
			<tr>
				<td><?=format_date($grade->date,"standard");?></td>
				<td><?=$grade->assignment; ?>
				</td>
				<td><?=$grade->category;?>
				</td>
				<td><?=$grade->status?$grade->status:$grade->points;?> 
				<? if($grade->footnote){
					echo "<sup>$grade->footnote</sup>";
					$footnotes[$grade->footnote] = $grade->label;
				}?></td>
				<td><?=$grade->total_points > 0?$grade->total_points:"Make-Up Points";?>
				</td>
				<!-- <td class='notes-column'><?=$grade->footnote != 0 ? $grade->label:"";?></td>  -->

			</tr>
			<?
			
			//if the student does not have an assignment listed as absent,excused, incomplete, redo, then calculate the grade otherwise ignore
					if(!$grade->status){
						//$points = $grade->total_points;
						//$student_total += $grade->total_points * $grade->weight;
		
					//}else{
						$points = $grade->points;
						$student_total += $grade->points * $grade->weight;
					
					
					if(!array_key_exists($grade->category,$categories)){
						$categories[$grade->category]["category"] = $grade->category;
						$categories[$grade->category]["weight"] = $grade->weight;
						$categories[$grade->category]["total_points"] = $grade->total_points;
						$categories[$grade->category]["points"] = $points;
					}else{
						$categories[$grade->category]["total_points"] += $grade->total_points;
						$categories[$grade->category]["points"] += $points;
					}
					$assignment_total += $grade->total_points * $grade->weight;
					}
					
				} //end if
			}//end foreach grade
			?>
		</tbody>

	</table>
	<? if(!empty($footnotes)) : 
	asort($footnotes);
	$keys = array_keys($footnotes);
	$values = array_values($footnotes); ?>
	<div class='footnotes'>
		<div class='caption'>Notes</div>
		<ul>
			<? 

			for($i = 0; $i<count($keys); $i++): ?>

			<li><?=sprintf("%s: %s", $keys[$i],$values[$i]);?></li>
			<? endfor;?>
		</ul>
	</div>
	<? endif; ?>
</div>
<div class='report-header report-summary'>
	<?=$header->subject; ?>
	Category Summary
</div>
<div class='report-body'>
	<table class="report-card">
		<thead>
			<tr>
				<th class="category-column">Category</th>
				<th class="points-column">Points</th>
				<th class="totals-column">Possible</th>
				<th class="weight-column">Weight</th>
				<th class="percent-column">Percent</th>
				<th class="grade-column">Grade</th>
		
		</thead>
		<tbody>
			<? foreach($categories as $category): ?>
			<? $category_grade = round($category["points"]/$category["total_points"]*100,2);?>
			<tr>
			<td><?=$category["category"];?></td>
			<td><?=$category["points"];?></td>
			<td><?=$category["total_points"]; ?></td>
			<td><?=$category["weight"];?>%</td>
			<td><?=$category_grade;?>%</td>
			<td><?=calculate_letter_grade($category_grade);?>
			</tr>
			<? endforeach; ?>
		</tbody>
		<tfoot>
			<?
			$grade_total = 0;
			$category_count = 0;
			$total_grade = round($student_total/$assignment_total*100,1);
			echo sprintf("<tr class='final-grade'><td class='label' colspan=4>Grade</td><td colspan=2>%s&#37; (%s)</td><tr>",$total_grade,calculate_letter_grade($total_grade));

			?>

		</tfoot>
	</table>
</div>
