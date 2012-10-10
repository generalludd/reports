<?php 
$this->load->view("student/navigation", array("kStudent",$kStudent));
$i = 0;
?>


<div id='report-card'>

	<? foreach($charts as $chart): ?>
	<div class='report-chart report-<?=$i;?>'>
		<h2>
			Report Card for
			<?=$student_name;?>
		</h2>
		<div class='report-header report-term'>
			<?="$term, $year";?>
		</div>

		<? if($cutoff_date):?>
		<div class='report-header report-cutoff'>
			For grades given through
			<?=$cutoff_date;?>
		</div>
		<? endif;?>
		<?=$chart; ?>
	</div>
		<? $i++; //iterate to row numbers?>
	
	<? endforeach; ?>

</div>
