<?php

//$this->load->view("student/navigation", array("kStudent",$kStudent));
$i = 0; //iteration of row numbers for css styling and printing

?>
<h1 class='no-print'>Report Card Printout for Teacher</h1>
<? $buttons[] = array("selection"=>"none","type"=>"span","class"=>array("button","print","do-print"),"text"=>"Print");
echo create_button_bar($buttons); ?>
<div id='report-card'>

	<? foreach($charts as $chart): ?>

	<div class='report-chart report-<?=$i;?>'>

		<div class='report-title'>
			Friends School of Minnesota <br />Report Card for
			<?=$students[$i];?>
		</div>

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
