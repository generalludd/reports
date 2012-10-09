<?php 
$this->load->view("student/navigation", array("kStudent",$kStudent));

?>

<h2>Report Card for <?=$student_name;?></h2>
<div id='report-card'>
<? if($cutoff_date):?>
	 <div class='report-header report-cutoff'>For grades given through <?=$cutoff_date;?></div>
<? endif;?>
<div class='report-header report-term'><?="$term, $year";?></div>
<? foreach($charts as $chart){
	echo $chart;
} ?>

</div>