<?php 
if(!$print){
	$this->load->view("student/navigation", array("kStudent",$kStudent));
}
$i = 0; //iteration of row numbers for css styling and printing
?>


<div id='report-card'>

	<? foreach($charts as $chart): ?>

	<div class='report-chart report-<?=$i;?>'>

		<div class='report-title'>
			Friends School of Minnesota <br />Report Card for
			<?=$student_name;?>
		</div>
		<? if($i == 0 ) : 
		$buttons[] = array("selection"=>"none","type"=>"span","class"=>array("button","print"),"text"=>"Print");
		echo create_button_bar($buttons);
		endif; ?>
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
