<?php ?>
<h3><?php echo $title;?></h3>
<form id="benchmarkSearch" action="<?=site_url("benchmark/teacher_list");?>" method="get" name="benchmarkSearch">
<p><input type="hidden" name="kTeach" id="kTeach" value="<?=$kTeach;?>"/>
<label for="subject">Subject:</label> 
<?=form_dropdown('subject', $subject_list, "", "id='subject'"); ?>
    </p>
    <p><label for="term">Term:</label> <?=$termMenu; ?>
    <input type="text" name="year" id="year" size="5" maxlength="4" class="year"  value="<?=$yearStart; ?>"/>
    -<input type="text" id="yearEnd" class='yearEnd' readonly value="<?=$yearEnd; ?>" size="5"/></p>
    <p>Grade Range: <input type="text" name="gradeStart" class="gradeStart" size="3" maxlength="1" value="<?=$gradeStart?>" />-
    <input type="text" name="gradeEnd" size="3" maxlength="1" class="gradeEnd" value="<?=$gradeEnd;?>" /></p>
            <p>
            <input type="submit" class="button" id="continue_2" value="continue"/>
            </p>
</form>
