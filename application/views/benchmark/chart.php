<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php if(isset($standalone)):?>
<?php $this->load->view("student/navigation");?>
<h3><?php echo $title; ?></h3>
<?php $buttons[] = array("selection"=>"benchmarks", "href"=>site_url("benchmark/edit_student/$kStudent?subject=$subject&student_grade=$student_grade&quarter=$quarter&term=$term&year=$year"), "class"=>"button edit dialog", "text"=>"Edit Benchmarks");?>
<?php echo create_button_bar($buttons);?>
<?php endif; ?>

<?
if(count($benchmarks)>0 && $benchmarks != 0){
	print "<div class='section'>";
	print "<table class='chart list'>";
	$i=1;
	$currentSubject = "";
	$currentCategory = "";
	$comments = array();
	$currentQuarter = 0;
	foreach($benchmarks as $benchmark){

		if($benchmark->subject != $currentSubject){
			echo "<tr class='benchmark-row'><td colspan=3><h3>$benchmark->subject</h3></td></tr>";
			$currentSubject = $benchmark->subject;
		}
		if(get_value($benchmark, "legend")){
			echo "<tr class='benchmark-legend'><td colspan=3>$benchmark->legend</td></tr>";
		}
		echo "<tr  class='benchmark-row'>";
		if($benchmark->category!=$currentCategory){
			$currentCategory=$benchmark->category;
			print "<td  class='benchmark-text'><strong>$benchmark->category</strong></td>";
		}else{
			print "<td></td>";
		}
		$mark="";
		
		if(strlen($benchmark->comment)>0){
			$comment = "<sup>$i</sup><span class='footnote'>$benchmark->comment</span>";
			$mark = "<sup>$i</sup>";
			$i++;
			$comments[] = $comment;
		}
		print "<td>$benchmark->benchmark</td><td>$benchmark->grade$mark</td>";

	}
	print "</tr></table>";
	if($comments){
		print implode("<br/>", $comments);
	}
	print "</div>";
}

?>
</div>