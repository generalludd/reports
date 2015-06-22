<?php

$i = 0; //iteration of row numbers for css styling and printing
$classes = array("report-card");
if(isset($batch) && $batch){
    $classes[] = "page-break";
}
?>

<!-- grade/report_card -->
<div id='report-card' class="<?=implode(" ",$classes);?>">

	<? foreach($charts as $chart): ?>

	<div class='report-chart report-<?=$i;?>'>
<? if(isset($batch) && $batch):?>

<? else: ?>
		<div class='report-title'>
			Friends School of Minnesota Report Card for
			<?=$student_name;?>
		</div>
		<? endif; ?>
		<? if($i == 0 ) :
		$buttons[] = array("selection"=>"none","type"=>"span","class"=>array("button","print","do-print","small"),"text"=>"Print");
		echo create_button_bar($buttons);
		endif; ?>
		<div class='report-header report-term'>
			<?="$term, " . format_schoolyear($year, $term);?>
		</div>

		<? if(isset($cutoff_date) && $cutoff_date):?>
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
