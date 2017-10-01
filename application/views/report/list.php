<?php

defined('BASEPATH') or exit('No direct script access allowed');
if ($report_type == "student") {
    $this->load->view("student/navigation", array(
            "student" => $person,
    ));
} 
?>
<fieldset class="search_fieldset">
	<legend>Search Parameters</legend>
	<?php 
if (isset($options) && !empty($options)) {

    $keys = array_keys($options);
    $values = array_values($options);

    echo "<ul>";

    for ($i = 0; $i < count($options); $i ++) {
        $key = $keys[$i];
        $value = $values[$i];
        switch ($key) {
            case "date_range":
                $date_start = $options["date_range"]["date_start"];
                $date_end = $options["date_range"]["date_end"];
                printf( "<li>From: <strong>%s</strong></li>",format_date_range($date_start, $date_end));
                break;
            default:
                echo sprintf("<li>%s <strong>%s</strong></li>", ucfirst($key), $value);
                break;
        }
    }
    echo "</ul>";
} else {
    echo "<p>Showing All Submissions</p>";
}

print create_button_bar(array(array("text"=>"Refine Search","href"=>site_url("report/search?report_type=$report_type&report_key=$report_key"),"class"=>"button dialog" )))
?>
</fieldset>
<?php if(!empty($reports)): ?>
<?php if($type == "student"):?>
<p>
	<strong> Advisor: <?php  echo format_name($reports[0]->advisorFirst, $reports[0]->advisorLast);?>
	</strong>
</p>
<?php endif;?>
<table class="report list">
	<thead>
		<tr>
			<th>Category</th>
			<th>Submitted by</th>
			<th>Date</th>
			<th>Assignment Details</th>
			<th>Comment</th>
			<th>Rank</th>
			<th>Read</th>
			<th>Parent(s) Contacted</th>
			<th>Contact Details</th>
			<th>Contact Date</th>
			<th class='field buttons'></th>
		</tr>
	</thead>
	<tbody>

		<?php 
    $current_student = "";

    foreach ($reports as $report) {
        $teacher = link_teacher(format_name($report->teachFirst, $report->teachLast),$report->kTeach);
        if ($type == "advisor" || $type == "teacher") {
            $student = format_name($report->stuFirst, $report->stuLast, $report->stuNickname);

            if ($current_student != $student) {
                $current_student = $student;
                echo sprintf("<tr><td colspan='10' class='field report-student'><a href='%s' title='view %s&rsquo;s %ss'>%s</a></td></tr>",
                        site_url("report/get_list/student/$report->kStudent"), $current_student, STUDENT_REPORT, $current_student);
            }
        }
        ?>
		<tr>
			<td class='field report-category'><?php  echo $report->category;if ($report->category == "Missing Homework") {if ($report->assignment_status == 1) {echo " (Turned In Late)";}}?></td>
			<td class='field report-teacher-name'><?php  echo $teacher;?>
			</td>
			<td class='field report-date'><?php  echo format_date($report->report_date);?>
			</td>
			<td class='field report-assignment'><?php  echo $report->assignment;?></td>
			<td class='field report-comment'><?php  echo $report->comment;?></td>
			<td class='field report-rank'><?php  echo $report->label;?>
			</td>
			<td class='field report-is-read'><?php 
        $checked = "";
        $is_read = X;
        if ($report->is_read == 1) {
            $checked = "checked";
            $is_read = OK;
        }
        if ($report->kAdvisor == $this->session->userdata("userID")) :
            ?> <input
				type="checkbox"
				value="1"
				id="is-read_<?php  echo $report->kReport;?>"
				class="report-is-read"
				name="is_read"
				<?php  echo $checked;?> /> <?php else: ?>
				<?php  echo $is_read;?> <?php endif; ?></td>
			<td class='field report-parent-contact'><?php  echo $report->parent_contact;?>
			</td>
			<td class='field report-contact-method'><?php  echo $report->contact_method;?>
			</td>
			<td class='field report-contact-date'><?php if($report->contact_date): echo format_date($report->contact_date);endif;?>
			</td>
			<td class='field buttons'><a
				href="<?php  echo site_url("report/edit/$report->kReport");?>"
				class="button edit dialog">Edit</a></td>

			<?php 

}

    ?>
		</tr>
	</tbody>
</table>
<?php else: ?>
<p>
	There are no <?php echo STUDENT_REPORT;?>s for the given search criteria
</p>
<?endif;
