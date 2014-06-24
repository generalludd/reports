<?php #subject_sorter.inc ?>
<p>Sort Narratives for <?=$narrTerm." ".$school_year; ?></p>
<form id="sort_report" action="<?=site_url("narrative/set_sort");?>" method="post" name="sort_report">
    <input type="hidden" name="kStudent" value="<?=$kStudent; ?>" />
    <input type="hidden" name="narrTerm" value="<?=$narrTerm; ?>" />
    <input type="hidden" name="narrYear" value="<?=$narrYear; ?>" />
    <p><input type="text" name="reportSort" id="reportSort" size="65" value="<?=$reportSort; ?>"></p>
    <p><input type="submit" class='button' value="Save"/></p>
</form>
