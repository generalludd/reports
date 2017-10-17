<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
// student grade chart;
if(isset($kStudent) && (!isset($batch) || !$batch)){
$this->load->view("student/navigation");
}
$header = $grades [0];
$teacher = format_name ( $header->teachFirst, $header->teachLast );
$year = format_schoolyear ( $header->year );
$student_total = 0;
$assignment_count = 0;
$assignment_total = 0;
$footnotes = array ();
$categories = array ();
$weight_sums = 0;
$count = 0;
?>

<!-- grade/chart -->
<div class='grade-report report-teacher report-<?php  echo $count;?>'>
	<div class='report-body'>
		<div class='report-header'>
	<?php  echo "$header->subject, $teacher";?>
	<?php if($course_preference):?>
	<br />Grades are Pass/Fail
	<?php endif;?>
</div>

<?php if(isset($print_student_name)):?>
<h2><?php  echo format_name($student->stuFirst,$student->stuLast, $student->stuNickname);?></h2>
<?php endif; ?>


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
			<?php
			
			foreach ( $grades as $grade ) {
				if (($grade->points > 0 && $grade->total_points == 0) || ($grade->total_points > 0)) {
					?>
			<tr>
					<td><?php  echo format_date($grade->date);?></td>
					<td><?php  echo $grade->assignment; ?>
				</td>
					<td><?php  echo $grade->category;?>
				</td>
					<td><?php  echo $grade->status?$grade->status:$grade->points;?>
				<?php
					
					if ($grade->footnote) {
						echo "<sup>$grade->footnote</sup>";
						$footnotes [$grade->footnote] = $grade->label;
					}
					?></td>
					<td><?php  echo $grade->total_points > 0?$grade->total_points:capitalize($grade->points_type);?>
				</td>

				</tr>
			<?php
					
					// if the student does not have an assignment listed as absent,excused, incomplete, redo, then calculate the grade otherwise ignore
					if (empty ( $grade->status )) {
						$points = $grade->points;
						$student_total += $grade->points * $grade->weight;
						//if the category has not been added to the total categories then add it to the $categories array for the totals count below. 
						//If it does exist, just update the totals for the category. 
						if (! array_key_exists ( $grade->category, $categories )) {
							$categories [$grade->category] ['category'] = $grade->category;
							$categories [$grade->category] ['weight'] = $grade->weight;
							$categories [$grade->category] ['total_points'] = $grade->total_points;
							$categories [$grade->category] ['points'] = $points;
						} else {
							// get the total possible points for this category;
							$categories [$grade->category] ['total_points'] += $grade->total_points;
							$categories [$grade->category] ['points'] += $points;
							
						}
					}
				} // end if
			} // end foreach grade
			?>
		</tbody>

		</table>
	<?php
	
	if (! empty ( $footnotes )) :
		asort ( $footnotes );
		$keys = array_keys ( $footnotes );
		$values = array_values ( $footnotes );
		?>
	<div class='footnotes'>
			<div class='caption'>Notes</div>
			<ul>
			<?php 
		
		for($i = 0; $i < count ( $keys ); $i ++) :
			?>

			<li><?php  echo sprintf("%s: %s", $keys[$i],$values[$i]);?></li>
			<?php endfor;?>
		</ul>
		</div>
	<?php endif; ?>
	</div>
	<div class='report-summary'>
		<div class='report-header'>
	<?php  echo $header->subject; ?>
	Category Summary
	<?php if($course_preference):?>
	<br />Grades are Pass/Fail
	<?php endif;?>
</div>


		<table class="report-card">
			<thead>
				<tr>
					<th class="category-column">Category</th>
					<th class="points-column">Points</th>
					<th class="totals-column">Possible</th>
					<th class="percent-column">Percent</th>
					<th class="weight-column">Weight</th>
					<th class="grade-column">Grade</th>
			
			</thead>
			<tbody>
			<?php foreach($categories as $category): ?>
			<?php $category_grade = round($category['points']/$category['total_points']*100,2);?>
			<tr>
					<td><?php  echo $category['category'];?></td>
					<td><?php  echo $category['points'];?></td>
					<td><?php  echo $category['total_points']; ?></td>
					<td><?php  echo $category_grade;?>%</td>
					<td><?php  echo $category['weight'];?>%</td>
					<td><?php  echo calculate_letter_grade($category_grade, $course_preference);?></td>


				</tr>
			<?php $assignment_total += $category_grade * $category['weight']; ?>
			<?php $weight_sums += $category['weight'];?>
			<?php endforeach; ?>
		</tbody>
			<tfoot>
			<?php 
			$grade_total = 0;
			$category_count = 0;
			$total_grade = round ( $assignment_total / $weight_sums, 1 );
			echo sprintf ( "<tr class='final-grade'><td class='label' colspan=4>Grade</td><td colspan=2>%s&#37; (%s)</td><tr>", $total_grade, calculate_letter_grade ( $total_grade, $course_preference ) );
			
			?>

		</tfoot>
		</table>
	</div>
</div>