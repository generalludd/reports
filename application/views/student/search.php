<?php #student_search.inc$saved_grades = array();if($this->input->cookie("grades")){	$saved_grades = explode(",",$this->input->cookie("grades"));}$lower_school = implode("\r", create_grade_checklist(0, 4,"grades", $saved_grades));$middle_school = implode("\r", create_grade_checklist(5,8,"grades", $saved_grades));$needs_checked = "";if($this->input->cookie("hasNeeds")){	$needs_checked = "checked";}$former_students_checked = "";if($this->input->cookie("includeFormerStudents")){	$former_students_checked = "checked";}$sorting = "last_first";if($this->input->cookie("sorting")){	$sorting = $this->input->cookie("sorting");}?><div id="advancedSearch">	<h5>Class Search</h5>	<h6>Search for groups of students by class &amp; year</h6>	<form id="searchForm"		action="<?=site_url("student/advanced_search");?>" method="get"		name="searchForm">		<fieldset>			<legend>Grades</legend>			<ol class="search">				<?=$lower_school;?>			</ol>			<ol class="search">				<?=$middle_school;?>			</ol>		</fieldset>		<fieldset class='advanced'>			<legend>Advanced</legend>			<div class='advanced'>				<input type="checkbox" name="hasNeeds" id="hasNeeds" value="1"				<?=$needs_checked;?> /> <label for="hasNeeds">Only Show Students					with Learning Support</label><br /> <input type="checkbox"					name="includeFormerStudents" id="includeFormerStudents" value="1"					<?=$former_students_checked;?> /> <label					for="includeFormerStudents">Include Former Students</label>			</div>		</fieldset>		<fieldset>			<legend>Sorting</legend>			<label for="sorting">Sorting Order: </label>			<?=form_dropdown("sorting",$student_sort,$sorting,"id='sorting'");?>		</fieldset>		<p id="yearSearch">			School Year<br />			<?=form_dropdown('year', $yearList, $currentYear,"id='year'"); ?>			- <input type="text" id='yearEnd' name="yearEnd" size="5"				maxlength="4" readonly value="<?=$currentYear + 1?>" />		</p>		<p style="text-align: center;">			<input type="submit" class="button" value="Search" />		</p>	</form></div>