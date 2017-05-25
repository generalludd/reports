<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php if(isset($standalone)):?>
<?php $this->load->view("student/navigation");?>
<h3><?php echo $title; ?></h3>
<?php $buttons[] = array("selection"=>"benchmarks", "href"=>site_url("benchmark/edit_student/$kStudent?subject=$subject&student_grade=$student_grade&quarter=$quarter&term=$term&year=$year"), "class"=>"button edit dialog", "text"=>"Edit Benchmarks");?>
<?php echo create_button_bar($buttons);?>
<?php endif; ?>

<?
if (count ( $benchmarks ) > 0 && $benchmarks != 0) :
	?>
	<div class="benchmark-legend">
	<?php $this->load->view("benchmark/legend");?>
	</div>
<div class='section'>
	<table class='chart list'>
	<?php
	
$i = 1;
	$currentSubject = "";
	$currentCategory = "";
	$comments = array ();
	$currentQuarter = 0;
	foreach ( $benchmarks as $benchmark ) :
		?>
<?php if($benchmark->grade != "X" && $benchmark->grade !=""):?>
	<?php	if($benchmark->subject != $currentSubject):?>
			<tr class='benchmark-header'>
			<td colspan=2>
				<h3><?php echo $benchmark->subject;?></h3>
			</td>
		</tr>
			<?php $currentSubject = $benchmark->subject;?>
	<?php endif;?>
	<?php	if($benchmark->category!=$currentCategory):?>
		<?php $currentCategory=$benchmark->category;?>
		<tr class="benchmark-row">
		<td class='benchmark-text' colspan=2>
					<strong><?php echo $benchmark->category;?></strong>
				</td>
				</tr>
	
	<?php endif; ?>
	 <tr class=benchmark-row>
	<?php $mark="";?>
		<?php
			if (strlen ( $benchmark->comment ) > 0) {
				$comment = sprintf ( "<sup>%s</sup><span class='footnote'>%s</span>", $i, $benchmark->comment );
				$mark = sprintf ( "<sup>%s</sup>", $i );
				$i ++;
				$comments [] = $comment;
			} // endif;
			?>
		 <td class="benchmark-description"><?php echo $benchmark->benchmark;?></td>
		<td><?php echo $benchmark->grade . $mark;?></td>
	<?php  endif;?>
	<?php endforeach;?>
	</tr>
	</table>
	<?php
	
if ($comments) {
		print implode ( "<br/>", $comments );
	} ?>
	</div>
<?php endif;?>
</div>