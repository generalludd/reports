<?php #template/edit.php$year = get_value($template, "year", get_current_year());$message = "";if($action == "update"){	if(get_value($template, "isActive") == 0){		$message = "<span class='highlight'>This is an Inactive Template</span> <span class='button reactivate_template'>Reactivate</span>";	}}?><form id="template_editor" action="<?=site_url("template/$action");?>"	method="post" name="template_editor">	<input type="hidden" id="kTeach" name="kTeach" value="<?=$kTeach;?>" />	<input type="hidden" id="kTemplate" name="kTemplate"		value="<?=get_value($template,"kTemplate"); ?>" /> <input		type="hidden" id="action" name="action" value="<?=$action;?>" /> <input		type="hidden" id="isActive" name="isActive"		value="<?=get_value($template,"isActive",1);?>" /> <input		type="hidden" id="ajax" name="ajax" value="0" />	<ul class="button-list">		<li><span class='button template_save_continue'>Save &amp; Continue</span>		</li>		<li><input type="submit" class="button" value="Save &amp; Close" /></li>		<li><span class='button cancel_template'>Cancel</span></li>				<li>		<? 		if($template):?><span title='Remove template from active use'			class='delete_template button delete'>Disable Template</span>			<?   endif;?>			</li>				</ul>	<p>		<span id='message'><?=$message;?> </span>	</p>	<p>		<label for='subject'>Subject:</label>		<?=form_dropdown("subject", $subjects, get_value($template,"subject"),"id='subject'");?>		&nbsp; <label for="term">Term: </label>		<?=get_term_menu("term", get_value($template,"term",get_current_term()));?>		&nbsp; <label for="year">School Year: </label>		<?=form_dropdown("year", $years, $year ,"id='year'"); ?>		- <input type="text" id="yearEnd" name="yearEnd" size="5"			maxlength="4" readonly value="<?=$year + 1?>" />	</p>	<p>		<label for="gradeStart">Grade Range: </label>		<?=form_dropdown("gradeStart",$grade_list,get_value($template,"gradeStart",$gradeStart),"id='gradeStart'");?>		-		<?=form_dropdown("gradeEnd",$grade_list,get_value($template,"gradeEnd",$gradeEnd),"id='gradeEnd'");?>	</p>	<fieldset style="width: 55%; margin:15px 0;">	<legend>Description (i.e. "Excellent", "Average", "Needs Improvement", etc...)</legend>	<p>		<label for="type"></label>&nbsp;<input type="text" id="type" name="type"			value="<?=get_value($template,"type");?>" size="35" /> <span			class='help button' id="Templates_Template Titles"			title="What is the purpose of the title?">Help</span>	</p>	</fieldset>	<div class='notice' style="width:650px">NOTICE: Always use the word &quot;STUDENT&quot; in		all caps where you want an actual student's name to appear. ALWAYS use		the Masculine 3rd person pronouns (He, His, Him, Himself) here so that		the the computer can insert the correct gender pronouns in the actual		reports. <span class='help link' id='Templates_Pronoun Substitution'		 title="Why the bias?">Why the bias?</span></div>		<p>		<textarea name="template" id="template" class="tinymce" rows="13"			cols="95">			<?=get_value($template,"template");?>		</textarea>	</p></form>