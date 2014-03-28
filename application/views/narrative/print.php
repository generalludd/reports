<?php #narrative_print_header.inc ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Report for <?=$title;?>
</title>
<script type="text/javascript"
	src="<?=base_url() . 'js/jquery.min.js'?>"></script>
<link href='<?=base_url(). 'css/color.css';?>' rel='stylesheet'
	type='text/css' media='all' />

<link href='<?=base_url(). 'css/print.css';?>' rel='stylesheet'
	type='text/css' media='all' />

<style type="text/css" media="print">
.control_panel {
	display: none;
}

body {
	margin-left: 0in;
}
</style>

<script type="text/javascript">
    
    $(document).ready(function(){
        var baseP = 16;
        var baseTd = 14;
        $('.increaseFont').live('click',function(event){
            var pSize = parseInt($('p').css('font-size'));
            pSize += 2;
            var tdSize = parseInt($('td').css('font-size'));
            tdSize += 2;
            changeFont(pSize, tdSize);
            });
            
            $('.resetFont').live('click',function(event){
                changeFont(baseP, baseTd);
            });

            $('.reduceFont').live('click',function(event){
            var pSize = parseInt($('p').css('font-size'));
            pSize -= 2;
            if (pSize < baseP){
                pSize = baseP;
                tdSize = baseTd;
            }
            changeFont(pSize, tdSize);
            });

            $('.print').live('click', function(event){
                window.print();
            });
    })
    
        function changeFont(pSize,tdSize){
            $('p').css('font-size',pSize + 'px'); 
            $('td').css('font-size',tdSize + 'px');
            var footerSize = parseInt(pSize) - 4;
            $('.footnote').css('font-size', footerSize + 'px');
        }
    </script>
</head>

<body>
	<div class='control_panel'>
		<span class='font_label'>Font Size:</span> <span
			class='button increaseFont'>Increase</span>&nbsp; <span
			class='button resetFont'>Reset</span>&nbsp; <span
			class='button reduceFont'>Reduce</span>&nbsp; <span
			class='button print'>Print</span>
	</div>
	<p class="school">
		Friends School of Minnesota <br /> 1365 Englewood Avenue <br /> St.
		Paul, MN 55104
	</p>
	<p class="title">
		<b><? 
		echo $narrTerm; ?> NARRATIVE REPORT </b>
	</p>
	<p class='term'>
		<? echo format_schoolyear($narrYear) . " Academic Year"; ?>
	</p>
	<p>
		<span class="student">Student: <? echo $student; ?>
		</span> <span style='text-align: right; float: right'>Absent: <? echo $absent; ?>
		</span> <br /> <span class="student">Grade: <? echo format_grade($stuGrade); ?>
		</span> <span style='text-align: right; float: right'>Tardy: <? echo $tardy; ?>
		</span>

	</p>



	<?php
	foreach($narratives as $narrative){
		$narrText=stripslashes($narrative->narrText);
		$teacher = "$narrative->teachFirst $narrative->teachLast";
		print "<div class='subject-row'>";
		print "<div class='subject'>$narrative->narrSubject</div>";
		print "<div class='teacher'>Teacher: $teacher</div>";
		print "</div>";
		$data['benchmarks'] = FALSE;
		$has_benchmarks = FALSE;
		//benchmarks are only used in grades 5 and up.
		if($stuGrade > 4){
 			printf("<div class='grade'>%s Grade: %s</div>",$narrTerm, $grades[$narrative->narrSubject]);
 			//@TODO modify insert chart issues here.
 			$data['legend'] = $this->legend->get_one(array("kTeach"=>$narrative->kTeach, "subject"=>$narrative->narrSubject, "term"=> $narrative->narrTerm, "year"=>$narrative->narrYear ));
 			$has_benchmarks = $this->benchmark_model->student_has_benchmarks($narrative->kStudent, $narrative->narrSubject, $narrative->stuGrade, $narrative->narrTerm, $narrative->narrYear);

 			if($has_benchmarks){
				$data["benchmarks"] = $this->benchmark_model->get_for_student($narrative->kStudent,$narrative->narrSubject,$stuGrade, $narrTerm, $narrYear);
			}
		}

		$narrText = strip_slashes($narrative->narrText);
		print "<p>$narrText</p>";
		if($has_benchmarks){
				$this->load->view("benchmark/chart", $data);
		}
	}
	//end area for clean up
	?>
</body>
</html>
