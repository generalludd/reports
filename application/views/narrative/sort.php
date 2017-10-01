<?php #subject_sorter.inc ?>
<p>Sort Narratives for <?php  echo $narrTerm." ".$school_year; ?></p>
<form id="sort_report" action="<?php  echo site_url("narrative/set_sort");?>" method="post" name="sort_report">
    <input type="hidden" name="kStudent" value="<?php  echo $kStudent; ?>" />
    <input type="hidden" name="narrTerm" value="<?php  echo $narrTerm; ?>" />
    <input type="hidden" name="narrYear" value="<?php  echo $narrYear; ?>" />
    <p><input type="text" name="reportSort" id="reportSort" size="65" value="<?php  echo $reportSort; ?>"></p>
    <p><input type="submit" class='button' value="Save"/></p>
</form>
