<?php defined('BASEPATH') OR exit('No direct script access allowed');
$date_start = get_cookie("date_start");
$date_end = get_cookie("date_end");
?>
<h5><?php  echo $title;?></h5>
<form id="report_search" name="report_search" action="<?php  echo site_url("report/get_list/$report_type/$report_key");?>" method="get">
<input type="hidden" id="report_key" name="report_key" value="<?php  echo $report_key;?>"/>
<p>
<label for="date_start">Start Date</label>
<input type="date" name="date_start" id="date_start" value="<?php  echo $date_start;?>"/>
</p>
<p>
<label for="date_end">End Date</label>
<input type="date" name="date_end" id="date_end" value="<?php  echo $date_end;?>"/>
</p>
<p>
<label for="category">Category</label>
<?php  echo form_dropdown("category",$categories,"","id='category'");?>

<div class='button-box'>
<input type="submit" class="button" value="Search"/>
</div>
</form>