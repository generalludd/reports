<?php

$thisSubject = "";
$thisCategory = "";
$thisTerm = "";
$thisGrade = "";
$output = "<p></p>";
if($benchmarks){
	$table[] =  "<table id='benchmarks' class='list'>";
	foreach($benchmarks as $benchmark){
		$termDisplay = format_schoolyear($benchmark->year);
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

 		$benchmarkRow = sprintf("<tr class='benchmark-row' id='benchmark_%s'><td class='benchmark-text' title='%s, %s'>%s</td>",$benchmark->kBenchmark,$currentGrade, $termDisplay,$benchmark->benchmark);
		$benchmarkRow .= sprintf("<td><a class='link dialog' href='%s'>Edit</a></td>",site_url("benchmark/edit/$benchmark->kBenchmark"));
		$benchmarkRow .= sprintf("<td><a class='link dialog' href='%s'>Duplicate</a></td>",site_url("benchmark/duplicate/$benchmark->kBenchmark"));
		$benchmarkRow .= sprintf("<td style='white-space:nowrap;'>Student Entries<br/>
<a class='link' href='%s' title='Quarter 1'>Q1</a> <a class='link' href='%s' title='Quarter 2'>Q2</a> <a class='link' href='%s' title='Quarter 3'>Q3</a> <a class='link' href='%s' title='Quarter 4'>Q4</a>",
			site_url("student_benchmark/list_by_benchmark/$benchmark->kBenchmark?quarter=1"),
			site_url("student_benchmark/list_by_benchmark/$benchmark->kBenchmark?quarter=2"),
			site_url("student_benchmark/list_by_benchmark/$benchmark->kBenchmark?quarter=3"),
			site_url("student_benchmark/list_by_benchmark/$benchmark->kBenchmark?quarter=4"));
		$benchmarkRow .= sprintf("<td><a class='link delete_benchmark' id='delete_%s'>Delete</a></td></tr>",$benchmark->kBenchmark);
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

$buttons[] = array("text"=>"New Benchmark","href"=>site_url("benchmark/create"),"class"=>"dialog new button");
$buttons[] = array("text"=>"Search for Benchmarks","class"=>"button dialog","href"=>site_url("benchmark/search"));
$buttons[] = array("text"=>"View Chart Legend","class"=>"button dialog edit","href"=>site_url("benchmark_legend/view"));

//$buttons[] = array("text"=>"Edit Chart Legends","class"=>"button dialog edit","href"=>site_url("benchmark_legend/search"));
print create_button_bar($buttons);
?>
<?php 
//$output .= "<input type='hidden' id='term' name='term' value='$term'/><input type='hidden' id='year' name='year' value='$year>'/>";
print $output;
