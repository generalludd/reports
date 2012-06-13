<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<h3>List of Backups</h3>
<p>Find the text you want and copy it, then press the back button to edit the current narrative as needed.</p>
<p>
<a href="<?=site_url("narrative/view/$kNarrative");?>" class="button">Back to Narrative</a>
</p>
<? foreach($backups as $backup):?>
	<h4><?=format_timestamp($backup->recModified);?></h4>
	<div class="narrText">
	<?=$backup->narrText;?>
	</div>

<?endforeach;

