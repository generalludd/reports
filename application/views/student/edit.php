<?php #student_edit.inc$baseGrade = get_value($student, 'baseGrade', 0);$baseYear = get_value($student, 'baseYear', get_current_year());$currentYear = get_current_year();$currentGrade = format_grade(get_current_grade($baseGrade, $baseYear, $currentYear));$stuDOB = get_value($student, 'stuDOB', NULL);if($stuDOB){	$stuDOB = format_date($stuDOB, 'standard');}$teacherLabel = get_teacher_type($currentGrade);?><form id="studentEditor" action="<?=base_url();?>index.php/student/<?=$action;?>" method="post" name="studentEditor">		   <input type="hidden" id="kStudent" name="kStudent" value='<?=get_value($student, 'kStudent'); ?>'>	<p><label for='stuFirst'>First Name</label>	<input class="required" type="text" name="stuFirst"  id='stuFirst' value='<?=get_value($student,'stuFirst'); ?>' size="24"/><!--	<span class='notice' id="stuFirstErr">-->	</p>	<p>	<label for='stuNickname'>Nickname</label>        <input type="text"  name="stuNickname"  id='stuNickname' class='required' value='<?=get_value($student, 'stuNickname'); ?>' size="24"/>  </p>	<p><label for='stuLast'>Last Name</label>	<input class="required" type="text" name="stuLast" id='stuLast' value='<?=get_value($student, 'stuLast');?>' size="24"/><!--	<span class='notice' id="stuLastErr">-->	</p>	<p><label for='stuDOB'>Birthdate: (mm/dd/yyyy)</label>		<input type="text" name="stuDOB" id='stuDOB' size="24" class="required"  value='<?=$stuDOB;?>' ><!--		<span class="notice" id="stuDOBErr"></span>-->		</p>	<p><label for='baseGrade'>Grade at Year of Enrollment</label>		<?=form_dropdown('baseGrade', $gradePairs, get_value($student, 'baseGrade', 0), 'id="baseGrade"');?><!--		<span class='notice' id="baseGradeErr"></span>-->		</p>	<p><label for='baseYear'>School Year of Enrollment</label>		<input type="text" class="required" name="baseYear" id="baseYear" value="<?=$baseYear; ?>" size="5" maxlength="4"/>		<input type="text" id="baseYearEnd" name="baseYearEnd" readonly size="5" maxlength="4" value="<?=$baseYear + 1; ?>"/><!--		<span class='notice' id="baseYearErr"></span>-->		</p>	<p><label for='stuGrade'>(Current Grade: <span id='gradeText'><?=$currentGrade;?></span>)</label>	<input readonly type="hidden" name="stuGrade" id="stuGrade" value='<?=$currentGrade; ?>'/></p>	<? if($currentGrade > 4 ):?>		<p>		<label for="stuGroup">Middle School Group</label>		<?=form_dropdown("stuGroup",array("A"=>"A","B"=>"B"), get_value($student,'stuGroup'),"id='stuGroup'");?><br/>		<span class='footnote highlight'>The grade part of this group (eg. 7/8, 5/6) is calculated by the student grade.</span>		</p>	<? endif;?>	<p><label for='stuGender'>Gender</label>		<?=form_dropdown('stuGender', $genderPairs, get_value($student, 'stuGender', 0), 'id="stuGender"'); ?>		</p>		<fieldset><legend id='generate-email'>Email</legend>		<p><label for="stuEmail">Address&nbsp;</label>		<!-- &nbsp;<span class='link' id='generate-email'>Generate</span>  -->		<input type="text" name="stuEmail" id="stuEmail" value="<?=get_value($student,'stuEmail');?>"/>		<br/><span id="valid-email"></span>		</p>		<p>		<label for="stuEmailPassword" id="stu-password-label">Password&nbsp;</label>		<input type="text" id="stuEmailPassword" name="stuEmailPassword" value="<?=get_value($student, 'stuEmailPassword');?>"/>		</p>		<p>		<label for="stuEmailPermission">Parent Permission Received&nbsp;</label>		<input type="checkbox" id="stuEmailPermission" name="stuEmailPermission" value="1" <? if(get_value($student, 'stuEmailPermission', 0) == 1){echo "checked";} ?>/>		</p>		</fieldset>			<p><label for='isEnrolled'>Is Enrolled</label>		<input type=checkbox value=1 id="isEnrolled" name="isEnrolled" <? if(get_value($student, 'isEnrolled', 0) == 1){echo "checked";} ?>/></p>		<p><label for='kTeach'><?=$teacherLabel; ?></label><?=form_dropdown('kTeach', $teacherPairs, get_value($student, 'kTeach', 0), 'id="kTeach"'); ?></p><div class='button-box'><?        $buttons[] = "<input type='submit' class='save_student button' value='Save'/>";        if($action == "update"){            $buttons[] = "<span class='delete_student delete button'>Delete</span>";        }        $buttonBar=join("", $buttons);        echo $buttonBar;?></div></form>