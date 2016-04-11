<?php ?>
<h4><?php echo $title;?></h4>
<form id="report_card_selector" name="report_card_selector"
	action="<?=site_url("grade/report_card");?>" 
	method="get" target="blank">
	<input type="hidden" id="kStudent" name="kStudent"
		value="<?=$kStudent;?>" />

	<p>
		<label for="year">Year: </label>
		<?=form_dropdown("year",$years,get_current_year());?>
	</p>
	<p>
		<label for="term">Term:</label>
		<?=$terms;?>
	</p>
	<p>
		<label for="cutoff_date">Cutoff-Date: </label><input type="date"
			id="cutoff_date" name="cutoff_date" value="<?php echo $this->input->cookie("cutoff_date");?>"/>
	</p>
	<p>
		<label for="subject">Subject: </label>
		<?=form_dropdown("subject",$subjects);?>
	</p>
	<p>
		<input type="submit" value="search" />
	</p>

</form>
