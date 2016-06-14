<?php #subject_sorter.inc ?>
<style>
  #reportSorting { list-style-type: none; margin: 0; padding: 0; width: 60%;}
  #reportSorting li:hover {cursor: pointer;}
  #reportSorting li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; }
  #reportSorting li { background-image: url(/css/images/edit_item.png);background-repeat: no-repeat;background-position-y:center }
  </style>

<form id="sort_report" action="<?=site_url("config/$action");?>" method="post" name="sort_report">
 <input type="hidden" name="subjects" id="subjects" size=65 value="<?=$sort_order->subjects; ?>">
<label for="grade_start">Starting Grade: </label>
<input type="number" name="grade_start" id="grade_start" value="<?=$sort_order->grade_start;?>"/>
<label for="grade_start">Ending Grade: </label>
<input type="number" name="grade_end" id="grade_end" value="<?=$sort_order->grade_end;?>"/>
<p><label for="context">Context</label><?=form_dropdown("context",array("narratives"=>"Narratives","grades"=>"Grades"),get_value($sort_order,"context","grades"));?>
    </p>
    <? $subjects = explode(",",$sort_order->subjects);?>
<ul id="reportSorting">

<?foreach($subjects as $subject):?>
	<li><?=$subject;?></li>
<?endforeach;?>
</ul>
    <p><input type="submit" class='button' value="Save"/></p>
</form>


<script type="text/javascript">$(function(){
	$("#reportSorting").sortable();
});
var subject_order="";
function save_order(){
 $("#reportSorting li").each(function(i){
	 if(subject_order == ""){
		 subject_order = $(this).html();
	 }else{
		 subject_order += "," + $(this).html();
	 }
 });
 console.log(subject_order);
 $("#subjects").val(subject_order);
 subject_order = "";
}
$("#reportSorting").live("mouseout",save_order);
</script>
