<?php ?>
<div class='info-box'>
	<h2>Teacher List</h2>
	<fieldset class="search_fieldset">
		<legend>Search Parameters</legend>
		<?
		if(!empty($options)){

			$keys = array_keys($options);
			$values = array_values($options);

			echo "<ul>";

			for($i = 0; $i < count($options); $i++){
				if($keys[$i] == "showAdmin" || $keys[$i] == "showInactive"){
					echo "<li>" . ucfirst($keys[$i]) .": <strong>Yes</strong></li>";
				}elseif($keys[$i] == "gradeRange"){
					$gradeStart = $options["gradeRange"]["gradeStart"];
					$gradeEnd = $options["gradeRange"]["gradeEnd"];
					if($gradeStart == $gradeEnd){
						echo "<li>Grade: <strong>$gradeStart</strong></li>";
					}else{
						echo "<li>Grade Range: <strong>$gradeStart-$gradeEnd</strong></li>";
					}
				}else{
					echo "<li>" . ucfirst($keys[$i]) .": <strong>";
					echo $values[$i] . "</strong></li>";
				}
			}
			echo "</ul>";
				
		}else{
			echo "<p>Showing all Users.</p>";

		}
		?>

		<div class="button-box">
			<a class="button teacher_search">Refine Search</a>
		</div>
	</fieldset>
	<a href="<?=site_url("teacher/create");?>"
		class="button teacher_create new">New User</a>
	<table class='list'>
		<tbody>
			<?


			$currentStatus="";
			$currentRole="";
			foreach($teachers as $teacher){
				$kTeach = $teacher->kTeach;
				$teacherName = "$teacher->teachFirst $teacher->teachLast";
				$gradeStart = format_grade($teacher->gradeStart);
				$gradeEnd  =format_grade($teacher->gradeEnd);
				$gradePair = "$gradeStart - $gradeEnd";
				if($teacher->dbRole != $currentRole){
					$roleRow="";
					$currentRole=$teacher->dbRole;
					if($teacher->status == 1){
						switch($currentRole){
							case 1:
								$roleRow="<tr><td colspan='3' style='font-weight:bold'>Administrators</td></tr>";
								break;
							case 2:
								$roleRow="<tr><td colspan='3' style='font-weight:bold'>Faculty</td></tr>";
								break;
							case 3:
								$roleRow="<tr><td colspan='3' style='font-weight:bold'>Aides and Support Staff</td></tr>";
								break;
							default:
								break;
						}
					}
					echo $roleRow;
				}

				if($teacher->status != $currentStatus){
					$statusRow="";
					$currentStatus=$teacher->status;
					if($currentStatus!=1){
						$statusRow="<tr><td colspan='3' style='font-weight:bold'>Inactive/Former Teachers</td></tr>";
					}
					echo $statusRow;
				}

				if($gradeStart == $gradeEnd){
					$gradePair = $gradeStart;
				}
				echo "<tr><td style='width:50%'>$teacherName</td><td>";
				if($currentRole != 1){
					echo $gradePair;
				}
				echo "</td><td><a href=". site_url("teacher/view/$kTeach") . " class='button'>View/Edit</a>";
				// if(teacherHasNarratives($kTeach)){
				//     echo "&nbsp;<span class='button teacher_narratives_list' id='n_$kTeach'>Narratives</span>";
				//   }
				echo "</td></tr>";

			}
			?>
		</tbody>
	</table>

</div>
