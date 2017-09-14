<?php ?>
<!-- teacher/list.php -->
	<fieldset class="search_fieldset">
		<legend>Search Parameters</legend>
		<?
if (! empty($options)) {

    $keys = array_keys($options);
    $values = array_values($options);

    echo "<ul>";

    for ($i = 0; $i < count($options); $i ++) {
        $key = $keys[$i];
        $value = $values[$i];
        switch ($key) {
            case "showInactive":
                echo "<li>Show Inactive/Former Users: <strong>Yes</strong></li>";
                break;
            case "gradeRange":
                    $gradeStart = format_grade($options["gradeRange"]["gradeStart"]);
                    $gradeEnd = format_grade($options["gradeRange"]["gradeEnd"]);
                    if ($gradeStart == $gradeEnd) {
                        echo "<li>Grade: <strong>$gradeStart</strong></li>";
                    } else {
                        echo "<li>Grade Range: <strong>$gradeStart-$gradeEnd</strong></li>";
                    }
                break;
            case "roles":
                echo "<li>Roles<ul>";
                foreach ($value as $role) {

                    echo "<li>" . $role["label"] . "</li>";
                }
                echo "</ul></li>";
                break;
            default:
                echo "<li>" . ucfirst($keys[$i]) . ": <strong>";
                echo $values[$i] . "</strong></li>";
        }
    }
    echo "</ul>";
} else {
    echo "<p>Showing all Users.</p>";
}

 print create_button_bar(array(array("text"=>"Refine Search", "href"=>site_url("teacher/show_search?refine=1"),"class"=>"button dialog search")));
?>
	</fieldset>
	<?=create_button_bar(array(array("text"=>"New User","class"=>"button new dialog","href"=>site_url("teacher/create"),"dbRole"=>1)));?>
<div class="column-group">

<? foreach($roles as $role):?>
<div class="column">
	<h4><?php echo $role["label"];?></h4>
<ul class="no-style list">
<?php foreach($teachers as $teacher):?>
<?php if($teacher->dbRole == $role['value']):?>
<?php $css = $teacher->status === 0? "disabled inactive":"active";?>
<li class="<?php echo $css;?>">
<a href="<?php echo site_url("teacher/view/$teacher->kTeach");?>" class="link">
<?php echo format_name($teacher->teachFirst, $teacher->teachLast); ?>
</a><?php echo $teacher->dbRole != 1? sprintf(" (%s)", format_grade_range($teacher->gradeStart, $teacher->gradeEnd)): "";?>
</li>
<?php endif;?>
<?php endforeach; ?>
</ul>

</div>

<?php endforeach; ?>
</div>


