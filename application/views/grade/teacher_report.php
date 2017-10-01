<?php

//$this->load->view("student/navigation", array("kStudent",$kStudent));
$i = 0; //iteration of row numbers for css styling and printing

?>
<h1 class='no-print'>Report Card Printout for Teacher</h1>
<?php $buttons[] = array("selection"=>"none","type"=>"span","class"=>array("button","print","do-print"),"text"=>"Print");
echo create_button_bar($buttons); ?>
<div id='report-card'>

	<?php foreach($charts as $chart): ?>

	<div class='report-chart report-<?php  echo $i;?>'>

		<div class='report-title'>
			Friends School of Minnesota <br />Report Card for
			<?php  echo $students[$i];?>
		</div>

		<div class='report-header report-term'>
			<?php  echo "$term, $year";?>
		</div>

		<?php if($cutoff_date):?>
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
