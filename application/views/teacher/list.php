<?php ?>
<div class='info-box'>
	<h2>Teacher List</h2>
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
?>

		<div class="button-box">
			<a class="button teacher_search">Refine Search</a>
		</div>
	</fieldset>
	<div class="button-box">
		<a
			href="<?=site_url("teacher/create");?>"
			class="button teacher_create new small">New User</a>
	</div>
</div>
<? foreach($roles as $role):?>
<div class="column column-3">
	<h4><?=$role["label"];?></h4>
	<table class="list">
		<tbody>
<? foreach ($teachers as $teacher):?>
<? if($teacher->dbRole == $role["value"]):?>

				<tr <? echo $teacher->status != 1? "class='disabled inactive'":"";?>>
				<td style="width: 50%;"><?=format_name($teacher->teachFirst,$teacher->teachLast);?></td>
				<td><? echo $teacher->dbRole !=1 ?  format_grade_range($teacher->gradeStart, $teacher->gradeEnd): ""; ?> </td>
				<td><a
					href="<?=site_url("teacher/view/$teacher->kTeach");?>"
					class='button'>View/Edit</a></td>
			</tr>

<? endif; ?>
<? endforeach; ?>
</tbody>
	</table>
</div>
<? endforeach;?>


