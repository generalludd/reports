<?php$year = get_value($overview, "year", get_current_year());$message = "";$buttons[] = array("text"=>"Save &amp; Continue","class"=>"button edit","href"=>"javascript:save_continue_overview();");$buttons[] = array("text"=>"<input type='submit' class='button edit overview_save_close' value='Save &amp; Close'/>","type"=>"pass-through");$buttons[] = array("text"=>"Cancel","class"=>"button cancel_form");if($action == "update"){	$buttons[] = array("text"=>"Delete Overview", "href" => "#", "class"=>"button delete delete_record");}?><form id="overview_editor" action="<?php  echo site_url("overview/$action");?>"	method="post" name="overview_editor">	<input type="hidden" id="kTeach" name="kTeach" value="<?php  echo $kTeach;?>" />	<input type="hidden" id="kOverview" name="kOverview"		value="<?php echo get_value($overview,"kOverview"); ?>" />		 <input		type="hidden" id="action" name="action" value="<?php  echo $action;?>" />		 		<input type="hidden" id="isActive" name="isActive" value="<?php echo get_value($overview,"isActive",1);?>" /> 		<input		type="hidden" id="ajax" name="ajax" value="0" />	<?php  echo create_button_bar($buttons,array("id"=>"editing-buttons"));?>			<div id='message' class="alert"><?php  echo $message;?> </div>	<?php if($action == "update"){		$active_buttons[] = array();		if(get_value($overview, "isActive") == 0){			$active_buttons[] = array("text"=>"Activate Overview","title"=>"This overview has been disabled. Click to re-enable","class"=>"button edit activate","id"=>"activation-button","href"=>"#");		}else{			$active_buttons[] = array("text"=>"Deactivate Overview","title"=>"Remove overview from active use","class"=>"button deactivate","id"=>"activation-button","href"=>"#");		}		print create_button_bar($active_buttons);			}?>	<p>		<label for='subject'>Subject:</label>		<?php  echo form_dropdown("subject", $subjects, get_value($overview,"subject"),"id='subject'");?>		&nbsp; <label for="term">Term: </label>		<?php  echo get_term_menu("term", get_value($overview,"term",get_current_term()));?>		&nbsp; <label for="year">School Year: </label>		<?php  echo form_dropdown("year", $years, $year ,"id='year'"); ?>		- <input type="text" id="yearEnd" name="yearEnd" size="5"			maxlength="4" readonly value="<?php  echo $year + 1?>" />	</p>	<p>		<label for="gradeStart">Grade Range:</label>		<?php  echo form_dropdown("gradeStart",$grade_list,get_value($overview,"gradeStart",$gradeStart),"id='gradeStart'");?>		-		<?php  echo form_dropdown("gradeEnd",$grade_list,get_value($overview,"gradeEnd",$gradeEnd),"id='gradeEnd'");?>	</p>		<p>		<textarea name="overview" id="overview" class="ckeditor"			cols="95" style="width:100%;">			<?php  echo get_value($overview,"overview");?>		</textarea>	</p></form><script type="text/javascript">window.setInterval(function(){	tinyMCE.triggerSave();	save_continue_overview();}, 60000);</script><script src="/js/overview.js"></script>