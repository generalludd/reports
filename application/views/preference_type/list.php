<?php defined('BASEPATH') OR exit('No direct script access allowed');

?>

<h3>Administer Preference Types</h3>
<?php $buttons[] = array("selection"=>"preference_type", "text"=>"New", "class"=>"button new create_preference_type");
echo create_button_bar($buttons);

		?>
<?php foreach($preferences as $preference):
$buttons = array();?>
<div id="ptdisplay-<?php  echo $preference->type;?>"><h4 id="<?php  echo $preference->type;?>">
	<?php  echo $preference->name;?>
</h4>
<?php $buttons[] = array("selection"=>"preference_type", "text"=>"Edit","class"=>"button edit edit_preference_type","id"=>"pt!$preference->type");
$buttons[] = array("selection" => "preference_type","text"=>"Delete","class"=>"button delete delete_preference_type","id"=>"dt!$preference->type");

echo create_button_bar($buttons);?>

<div 
<?php if($type == $preference->type): echo "class='highlight'"; endif;?>>

	<p>
		<label>Machine Name (type): </label>
		<?php  echo $preference->type;?>
	</p>
	<p>
		<label>Description: </label>
		<?php  echo $preference->description;?>
	</p>
	<p>
		<label>Options: </label>
		<?php  echo $preference->options;?>
	</p>
	<p>
		<label>Format: </label>
		<?php  echo $preference->format;?>
	</p>
</div>
</div>



<?php endforeach; ?>