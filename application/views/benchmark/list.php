<?php


$thisSubject = "";
$thisCategory = "";
$thisTerm = "";
$thisGrade = "";
$output = "<p></p>";
if($benchmarks){
	$table[] =  "<table id='benchmarks' class='list'>";
	foreach($benchmarks as $benchmark){
		$termDisplay = $benchmark->term . " " . format_schoolyear($benchmark->year);
		if($benchmark->gradeStart != $benchmark->gradeEnd){
			$currentGrade = "Grades $benchmark->gradeStart-$benchmark->gradeEnd";
		}else{
			$currentGrade = "Grade $benchmark->gradeStart";
		}
		if($currentGrade != $thisGrade){
			$table[] = "<tr class='benchmark-header benchmark-grade'><td colspan='7'>$currentGrade</td></tr>";
			$thisGrade = $currentGrade;
			$thisSubject = "";
			$thisTerm = "";
			$thisCategory = "";
		}
		if($benchmark->subject != $thisSubject){
			$table[] = "<tr class='benchmark-subject benchmark-header'><td colspan='7'>$benchmark->subject</td></tr>";
			$thisSubject = $benchmark->subject;
			$thisTerm = "";
			$thisCategory = "";
		}
		if($termDisplay != $thisTerm){
			$table[] = "<tr class='benchmark-term benchmark-header'><td colspan='7'>$termDisplay</td></tr>";
			$thisTerm = $termDisplay;
		}
		if($benchmark->category != $thisCategory){
			$table[] = "<tr class='benchmark-category benchmark-header'><td colspan='7'>Category: $benchmark->category</td></tr>";
			$thisCategory = $benchmark->category;
		}

		$benchmarkRow = "<tr class='benchmark-row' id='benchmark_$benchmark->kBenchmark'><td class='benchmark-text' title='$currentGrade, $termDisplay'>$benchmark->benchmark</td>";
		#$benchmarkRow .= "<td  class='benchmark-info'>$currentGrade<br/>$termDisplay</td>";
		$benchmarkRow .= "<td><a class='link edit_benchmark' id='edit_$benchmark->kBenchmark'>Edit</a></td>";
		$benchmarkRow .= "<td><a class='link duplicate_benchmark' id='duplicate_$benchmark->kBenchmark'>Duplicate</a></td>";
		$benchmarkRow .= "<td><a class='link delete_benchmark' id='delete_$benchmark->kBenchmark'>Delete</a></td></tr>";
		$table[] = $benchmarkRow;

	}//end while
	$table[] = "</table>";
	$output = implode("\n",$table);

}else{
	$output = "<p>You do not have any benchmarks for the search terms you provided.</p>";
}



//      $chartMessage=getChartMessage($kTeach);
$term=get_current_term();
$year=get_current_year();
//      print $chartMessage;

$buttons[] = array("text"=>"New Benchmark","class"=>"new_benchmark new button");
$buttons[] = array("text"=>"Search for Benchmarks","class"=>"button show_benchmark_search");
$buttons[] = array("text"=>"Edit Chart Legends","class"=>"button legend_search edit");
print create_button_bar($buttons);
?>
<!-- <div class='button-bar'>
<span class='new_benchmark new button'>New Benchmark</span>
<span class='show_benchmark_search button'>Search for Benchmarks</span>
<span class='legend_search button edit'>Edit Chart Legends</span> -->
<?
if( $this->session->userdata("dbRole") == 1){
	print "<a class='button' href='" .site_url("benchmark/teacher_list?term=" . get_current_term() . "&year=" . get_current_year()) ." '>Show Current Benchmarks</a> ";
}
?>
</div>
<?
$output .= "<input type='hidden' id='term' name='term' value='$term'/><input type='hidden' id='year' name='year' value='$year>'/>";
print $output;
