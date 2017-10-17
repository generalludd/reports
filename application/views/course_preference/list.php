<?php

defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

?>
<div class="block" id="course-preferences">
	<h3>Course Preferences</h3>
	<p>Course Preferences are for students who may be uniquely graded as pass/fail in subjects traditionally graded with letter grades or who may be exempt from a specific course</p>
<?php if(!empty($course_preferences)):?>
<table class="grid list">
		<thead>
			<tr>
				<th>Subject</th>
				<th class="no-wrap">School Year</th>
				<th class="no-wrap">Term</th>
				<th>Preference</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
<?php foreach($course_preferences as $preference):?>

<tr id="grade-preference-row_<?php  echo $preference->id;?>">
				<td><?php  echo $preference->subject;?></td>
				<td class="no-wrap"><?php  echo format_schoolyear($preference->school_year);?></td>
				<td class="no-wrap"><?php echo $preference->term;?></td>
				<td><?php echo get_value($preference,"preference");?></td>
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
				"href" => site_url ( "course_preference/edit/$preference->id" ),
				"id" => sprintf ( "edit-course-preference_%s", $preference->id ) 
		);
		$buttons [] = array (
				"text" => "Delete",
				"class" => array (
						"link small delete delete-course-preference" 
				),
				"id" => sprintf ( "delete-course-preference_%s", $preference->id ) 
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
<a href="<?php echo site_url("course_preference/create/$student->kStudent");?>" class="link new dialog" id="<?php printf ( "add-course-preference_%s", $student->kStudent);?>">Add Course Preference</a>
</p>

</div>