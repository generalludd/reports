<?php
// support/view.php
$had_meeting = "No";

if ($entry->meeting == 1) {
	$had_meeting = "Yes";
}

$has_iep = "No";

if ($entry->hasIEP == 1) {
	$has_iep = "Yes";
}

$has_spps = "No";

if ($entry->hasSPPS == 1) {
	$has_spps = "Yes";
}
$files_array = array ();
if ($support_files) {
	foreach ( $support_files as $file_item ) {
		if ($file_item->kSupport == $entry->kSupport) {
			$files_array [] = $file_item;
		}
	}
}

$year = $entry->year;
$year_end = $year + 1;
$test_date = $entry->testDate;
if ($test_date) {
	$test_date = format_date ( $test_date, "standard" );
} else {
	$test_date = "None";
}

if ($print) {
	echo "<h2>Learning Support for $student</h2>";
}

?>
<fieldset class="support-view">
	<legend><?=$year . "-" . $year_end?></legend>
<?

if (! $print) {
	
	$buttons [] = array (
			"selection" => "support",
			"href" => site_url ( "support/edit/$entry->kSupport" ),
			"text" => "Edit",
			"class" => "button edit small" 
	);
	
	$buttons [] = array (
			"selection" => "print",
			"href" => site_url ( "support/view/$entry->kSupport/print" ),
			"target" => "_blank",
			"text" => "Print" ,
			"class"=>"small button print"
	);
	print create_button_bar ( $buttons );
}
?>
<h4>Diagnosis/Description</h4>
	<p><?=$entry->specialNeed;?></p>
	<p>
		Has had fall meeting: <strong><?=$had_meeting;?></strong>
	</p>
	<p>
		Test Date: <strong><?=$test_date;?></strong>
	</p>

	<h4>Outside Support/Treatments</h4>
	<div><?=$entry->outsideSupport?></div>
	<p>
		IEP on File: <strong><?=$has_iep;?></strong>
	</p>
	<p>
		Saint Paul Public Schools Support: <strong><?=$has_spps;?></strong>
	</p>
<? if($entry->modification): ?>
<h4>Accommodations</h4>
	<div><?=$entry->modification; ?></div>
<? endif;?>

<? if(!empty($files_array) && !$print):?>
<div class='file-attachments'>
		<h4>File Attachments</h4>
		<table class="list files">
			<thead>
				<tr>
					<th>
						<strong>File Name</strong>
					</th>
					<th>
						<strong>Description</strong>
					</th>
				</tr>
			</thead>
			<tbody>
	<? foreach($files_array as $file):?>
	<tr>
					<td class='file-name'>
						<a href='<?=base_url("uploads/$file->file_name");?>' target='_blank'><?=$file->file_display_name;?></a>
					</td>
					<td class='file-description'><?=$file->file_description;?></td>
				</tr>
	<? endforeach; ?>
	</tbody>
		</table>
	</div>
		
<?	endif; ?>

</fieldset>
