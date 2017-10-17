<?php

defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

?>
<div class="block" id="grade-preferences">
	<h3>Grade Preferences</h3>
	<p>Grade Preferences are for students who may be uniquely graded as pass/fail in subjects traditionally graded with letter grades</p>
<?php if(!empty($grade_preferences)):?>
<table class="grid list">
		<thead>
			<tr>
				<th>Subject</th>
				<th class="no-wrap">School Year</th>
				<th class="no-wrap">Term</th>
				<th>Pass/Fail?</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
<?php foreach($grade_preferences as $preference):?>

<tr id="grade-preference-row_<?php  echo $preference->id;?>">
				<td><?php  echo $preference->subject;?></td>
				<td class="no-wrap"><?php  echo format_schoolyear($preference->school_year);?></td>
				<td class="no-wrap"><?php echo $preference->term;?></td>
				<td><?php if( $preference->pass_fail == 1):?>
Yes
<?php else: ?>
No
<?endif;?>
</td>
				<td style="width: 150px;">
<?php
		$buttons = array ();
		$buttons [] = array (
				"text" => "Edit",
				"class" => array (
						"link",
						"edit",
						"small",
						"dialog" 
				),
				"href" => site_url ( "grade_preference/edit/$preference->id" ),
				"id" => sprintf ( "edit-grade-preference_%s", $preference->id ) 
		);
		$buttons [] = array (
				"text" => "Delete",
				"class" => array (
						"link small delete delete-grade-preference" 
				),
				"id" => sprintf ( "delete-grade-preference_%s", $preference->id ) 
		);
		echo create_button_bar ( $buttons );
		
		?>
</td>
			</tr>
<?php endforeach;?>
</tbody>
	</table>
<?php endif;?>
<p>
<a href="<?php echo site_url("grade_preference/create/$student->kStudent");?>" class="link new dialog" id="<?php printf ( "add-grade-preference_%s", $student->kStudent);?>">Add Grade Preference</a>
</p>

</div>