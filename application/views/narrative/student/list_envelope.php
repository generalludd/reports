<?php ?>

<h3>
		<?php echo $title;?>
</h3>

<?php
$data ["kStudent"] = $student->kStudent;
$this->load->view ( "student/navigation", $data );

$buttons [] = array (
		"selection" => "narrative",
		"text" => "Add Narrative for $student->stuNickname",
		"href" => site_url ( "narrative/select_type/?kTeach=$userID&kStudent=$student->kStudent" ),
		"class" => "button new dialog small" 
);
echo create_button_bar ( $buttons );

?>
<div class='student-narrative-list'>
<?php if (isset($reports) && count ( $reports ) > 0) :?>
<?php 	foreach ( $reports as $report ) :	?>
<div class="report-row">
<? foreach ( $report as $term ) : ?>
			<?php echo $term; ?> 
		<?php endforeach; ?>
</div>
<?php endforeach ;?>
<?php else:?> 
	<p>There are no reports entered for <?php echo $studentName;?></p>
<?php endif; ?>
</div>

