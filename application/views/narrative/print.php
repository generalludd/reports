<?php  ?>
<!DOCTYPE html>

<html>

<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title><?=$title;?>
</title>
<link href='<?=base_url('css/report.css');?>' rel='stylesheet' type='text/css' media='all' />
<link href='<?=base_url('css/print.css');?>' rel='stylesheet' type='text/css' media='print' />

<style>
@media (-webkit-min-device-pixel-ratio:0)
  and (min-resolution:.001dpcm){
  body{
  margin-left: .5in;
  }
  }</style>
</head>

<body class='narrative-report'>
	<p class="school">
		Friends School of Minnesota <br /> 1365 Englewood Avenue <br /> St. Paul, MN 55104
	</p>
	<p class="title">
		<b><?
		echo $narrTerm;
		?> NARRATIVE REPORT </b>
	</p>
	<p class='term'>
		<? echo format_schoolyear($narrYear) . " Academic Year"; ?>
	</p>
	<p>
		<span class="student">Student: <? echo $student; ?>
		</span>
		<span style='text-align: right; float: right'>Absent: <? echo $absent; ?>
		</span>
		<br />
		<span class="student">Grade: <? echo format_grade($stuGrade); ?>
		</span>
		<span style='text-align: right; float: right'>Tardy: <? echo $tardy; ?>
		</span>

	</p>



	<?php
	foreach ( $narratives as $narrative ) {
		$narrText = stripslashes ( $narrative->narrText );
		$teacher = "$narrative->teachFirst $narrative->teachLast";
		print "<div class='subject-row'>";
		print "<div class='subject'>$narrative->narrSubject</div>";
		print "<div class='teacher'>Teacher: $teacher</div>";
		print "</div>";
		$data ['benchmarks'] = FALSE;
		$has_benchmarks = FALSE;
		// benchmarks are only used in grades 5 and up.
		if ($stuGrade > 5) {
			$submits_report_card = $this->preference->get ( $narrative->kTeach, "submits_report_card" );
			if (array_key_exists ( $narrative->narrSubject, $grades ) && isset ( $grades [$narrative->narrSubject] )) {
				printf ( "<div class='grade'>%s Term Grade: %s</div>", $narrTerm, $grades [$narrative->narrSubject] );
				// @TODO this submits_report_card function needs to be cleaned up and moved to the controller.
				if ($narrTerm == "Year-End" && $submits_report_card == "yes") {
					if (array_key_exists ( $narrative->narrSubject, $mid_year_grades )) {
						printf ( "<div class='grade'>Mid-Year Term Grade: %s</div>", $mid_year_grades [$narrative->narrSubject] );
					}
					$final_grade_output = FALSE;
					if (array_key_exists ( $narrative->narrSubject, $mid_year_grades )) {
						if (isset($final_grade) && $final_grade [$narrative->narrSubject]) {
							$final_grade_output = sprintf ( " (%s&#037;)", $final_grade [$narrative->narrSubject] );
						}
						printf ( "<div class='grade'>%s Final Grade: %s%s</div>", $narrative->narrSubject, $year_grade [$narrative->narrSubject] ['grade'], $final_grade_output );
					}
				}
			} else {
				if ($submits_report_card == "yes" && $narrative->narrGrade) {
					print "<div class='grade'>Mid-Year Term Grade: $narrative->narrGrade</div>";
					
					if ($narrTerm == "Year-End") {
						print "<div class='grade'>Year-End Grade: $narrative->narrGrade</div>";
					}
				}
			}
			// @TODO modify insert chart issues here.
// 			$data ['legend'] = $this->legend->get_one ( array (
// 					"kTeach" => $narrative->kTeach,
// 					"subject" => $narrative->narrSubject,
// 					"term" => $narrative->narrTerm,
// 					"year" => $narrative->narrYear 
// 			) );
// 			$has_benchmarks = $this->benchmark_model->student_has_benchmarks ( $narrative->kStudent, $narrative->narrSubject, $narrative->stuGrade, $narrative->narrTerm, $narrative->narrYear );
			
// 			if ($has_benchmarks) {
// 				$data ["benchmarks"] = $this->benchmark_model->get_for_student ( $narrative->kStudent, $narrative->narrSubject, $stuGrade, $narrTerm, $narrYear );
// 			}
		}
		
		$narrText = stripslashes ( $narrative->narrText );
		print "<p>$narrText</p>";
// 		if ($has_benchmarks) {
// 			$this->load->view ( "benchmark/chart", $data );
// 		}
	}
	// end area for clean up
	?>
</body>
</html>
