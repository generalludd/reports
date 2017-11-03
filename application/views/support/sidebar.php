<?php #support/view.php

$had_meeting = "No";


if($entry->meeting == 1){
    $had_meeting = "Yes";
}

 $has_iep = "No";
 
if($entry->hasIEP == 1){
   $has_iep  = "Yes";
}

$has_spps = "No";

if( $entry->hasSPPS == 1 ){
    $has_spps = "Yes";
}
$files_array = array();
if($support_files){
	foreach($support_files as $file_item){
		if($file_item->kSupport == $entry->kSupport){
			$files_array[] = $file_item;
		}
	}
}


$year = $entry->year;
$year_end = $year + 1;
$test_date = $entry->testDate;
if($test_date){
    $test_date = format_date($test_date, "standard");
}else{
    $test_date = "None";
}

if($print){
	echo "<h2>Learning Support for $student</h2>";
}

$buttons[] = array("selection" => "close", "type" => "span", "text"=>"Close", "class" => "button close-sidebar", "id" => "close-sidebar");
print create_button_bar($buttons);
?>
<h3><?php  echo $year . "-" . $year_end?></h3>
<?php if($entry->strengths):?>
<h4>Strengths</h4>
<p><?php echo $entry->strengths; ?></p>
<?php endif;?>
<?php if($entry->strategies):?>
<h4>Strategies</h4>
<p><?php echo $entry->strategies; ?></p>
<?php endif;?>
<h4>Diagnosis/Description</h4>
<p><?php  echo $entry->specialNeed;?></p>
<p>Has had fall meeting: <strong><?php  echo $had_meeting;?></strong></p>
<p>Test Date: <strong><?php  echo $test_date;?></strong></p>

<h4>Outside Support/Treatments</h4>
<div><?php  echo $entry->outsideSupport?></div>
<p>IEP on File: <strong><?php  echo $has_iep;?></strong></p>
<p>Saint Paul Public Schools Support: <strong><?php  echo $has_spps;?></strong></p>
<?php if($entry->modification): ?>
<h4>Accommodations</h4>
<div><?php  echo $entry->modification; ?></div>
<?php endif;?>

<?php if(!empty($files_array) && !$print):?>
<div class='file-attachments'>
<h4 >File Attachments</h4>
<table class="list files">
	<thead>
		<tr>
			<th><strong>File Name</strong></th>
			<th><strong>Description</strong></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($files_array as $file):?>
	<tr><td class='file-name'><a href='<?php  echo base_url("uploads/$file->file_name");?>' target='_blank'><?php  echo $file->file_display_name;?></a></td>
	<td class='file-description'><?php  echo $file->file_description;?></td></tr>
	<?php endforeach; ?>
	</tbody>
</table>
<?php	endif; ?></div>

