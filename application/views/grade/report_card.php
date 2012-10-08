<?php ?>

<h2>Report Card for <?=$student;?></h2>
<? if($cutoff_date):?>
	 <h3>For grades given through <?=$cutoff_date;?></h3>
<? endif;?>
<h3><?="$term, $year";?></h3>
<? foreach($charts as $chart){
	echo $chart;
}