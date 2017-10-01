<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<h3>List of Backups</h3>
<p>Find the text you want and copy it, then press the back button to edit the current narrative as needed.</p>
<p>
<a href="<?php  echo site_url("narrative/view/$kNarrative");?>" class="button">Back to Narrative</a>
</p>
<?php foreach($backups as $backup):?>
	<h4><?php  echo format_timestamp($backup->recModified);?></h4>
	<div class="narrText">
	<?php  echo $backup->narrText;?>
	</div>

<?endforeach;

