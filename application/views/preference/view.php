<?php 
$this->load->view("teacher/navigation",array("teacher"=>$teacher, "kTeach" => $kTeach,"term"=>get_current_term(),"year"=>get_current_year()));

?>
<h2>Set Preferences for your Account</h2>
<input
	type='hidden' id='kTeach' name='kTeach' value='<?=$kTeach;?>' />
<p class='notice'>Note that this is a beta testing area. Do not make
changes unless you understand how each of these work.</p>

<?php
foreach($preferences as $preference){
	if($preference->format=='menu'){
		$list = explode(",",$preference->options);
		$output = displayMenu($list,$preference->type,$preference->value,'edit_preference');
	}elseif($format=="radio"){
		$data = array("name" => $preference->type, "id" => $preference->type, "value" => TRUE, "checked"=> $preference->value);
		$output = form_radio($data, "class='edit_peference'");
	}else{
		$output="<input type='text' name='$preference->type' id='$preference->type' class='edit_preference' size='24' value='$preference->value'>";
	}
	print "<h4>$preference->name</h4><p>$preference->description<br/>$output <span id='stat$preference->type'></span></p>";
}

function displayMenu($list,$id,$value,$class=null){
	if($class!=null){
		$classText="class='$class'";
	}
	$output="<select id='$id' name='$id' $classText>";
	for($i=0;$i<count($list);$i++){
		$row=$list[$i];
		$output.="<option value='$row'";
		if($value==$row){
			$output.="selected";
		}
		$output.=">$row</option>";
	}
	$output.="</select>";
	return $output;
}

