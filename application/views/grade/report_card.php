<?php

$i = 0; //iteration of row numbers for css styling and printing
$classes = array("report-card");
if(isset($batch) && $batch){
    $classes[] = "page-break";
}
?>

<!-- grade/report_card -->
<div id='report-card' class="<?php  echo implode(" ",$classes);?>">

	<?php foreach($charts as $chart): ?>

	<div class='report-chart report-<?php  echo $i;?>'>
<?php if(isset($batch) && $batch):?>

<?php else: ?>

		<?php endif; ?>
		<?php if($i == 0 ) :
		$buttons[] = array("selection"=>"none","type"=>"span","class"=>array("button","print","do-print","small"),"text"=>"Print");
		echo create_button_bar($buttons);
		endif; ?>
		<div class='report-header report-term'>
		&nbsp;
			<?php //"$term, " . format_schoolyear($year, $term);?>
		</div>

		<?php if(isset($cutoff_date) && $cutoff_date):?>
		<div class='report-header report-cutoff'>
			For grades given through
			<?php  echo $cutoff_date;?>
		</div>
		<?php endif;?>
		<?php  echo $chart; ?>
	</div>
	<?php $i++; //iterate to row numbers?>

	<?php endforeach; ?>

</div>
