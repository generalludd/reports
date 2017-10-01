<?php defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div id="mini-selector">
<form name="student-mini-selector" id="student-mini-selector" method="get" action="<?php  echo $action;?>">
<input type="hidden" id="kTeach" name="kTeach" value="<?php  echo $kTeach;?>"/>
<input type="hidden" id="term" name="term" value="<?php  echo $term;?>"/>
<input type="hidden" id="year" name="year" value="<?php  echo $year;?>"/>
<input type="hidden" id="js_class" name="js_class" value="<?php  echo $js_class;?>"/>
<p><label for="student-dropdown">Type the Name of the Student You want to Add</label><br/>
<input type="text" id="student-dropdown" name="student-dropdown" value=""/></p>
</form>
</div>