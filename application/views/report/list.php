<?php

defined('BASEPATH') or exit('No direct script access allowed');
$header[] = STUDENT_REPORT . "s";
if ($report_type == "student") {
    $header[] = sprintf("for %s", format_name($person->stuNickname, $person->stuLast));
} elseif ($report_type == "teacher") {
    $header[] = sprintf("reported by %s", format_name($person->teachFirst, $person->teachLast));
} else {
    $header[] = sprintf("reported to %s", format_name($person->advisorFirst, $person->advisorLast));
}
?>
<h2><?=implode(" ",$header); ?></h2>
<?
if ($report_type == "student") {
    $this->load->view("student/navigation", array(
            "student" => $person,
    ));
} else {
    $this->load->view("teacher/navigation", array(
            "teacher" => $person,
            "kTeach" => $person->kTeach,
            "term" => get_cookie("term"),
            "year" => get_cookie("year"),
    ));
}
?>
<fieldset class="search_fieldset">
	<legend>Search Parameters</legend>
	<?
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
?>

	<div class="button-box">
		<a class="button dialog" href="<?php echo site_url("report/search?report_type=$report_type&report_key=$report_key");?>">Refine Search</a>
	</div>
</fieldset>
<? if(!empty($reports)): ?>
<? if($type == "student"):?>
<p>
	<strong> Advisor: <?=format_name($reports[0]->advisorFirst, $reports[0]->advisorLast);?>
	</strong>
</p>
<? endif;?>
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

		<?
    $current_student = "";

    foreach ($reports as $report) {
        $teacher = format_name($report->teachFirst, $report->teachLast);
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
			<td class='field report-category'><?=$report->category;if ($report->category == "Missing Homework") {if ($report->assignment_status == 1) {echo " (Turned In Late)";}}?></td>
			<td class='field report-teacher-name'><?=$teacher;?>
			</td>
			<td class='field report-date'><?=format_date($report->report_date);?>
			</td>
			<td class='field report-assignment'><?=$report->assignment;?></td>
			<td class='field report-comment'><?=$report->comment;?></td>
			<td class='field report-rank'><?=$report->label;?>
			</td>
			<td class='field report-is-read'><?
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
				id="is-read_<?=$report->kReport;?>"
				class="report-is-read"
				name="is_read"
				<?=$checked;?> /> <? else: ?>
				<?=$is_read;?> <? endif; ?></td>
			<td class='field report-parent-contact'><?=$report->parent_contact;?>
			</td>
			<td class='field report-contact-method'><?=$report->contact_method;?>
			</td>
			<td class='field report-contact-date'><? if($report->contact_date): echo format_date($report->contact_date);endif;?>
			</td>
			<td class='field buttons'><a
				href="<?=site_url("report/edit/$report->kReport");?>"
				class="button edit dialog">Edit</a></td>

			<?

}

    ?>
		</tr>
	</tbody>
</table>
<? else: ?>
<p>
	There are no
	<?=STUDENT_REPORT;?>
	s for the given search criteria
</p>
<?endif;
