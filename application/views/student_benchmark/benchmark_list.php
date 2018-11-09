<?php
/**
 * Created by PhpStorm.
 * User: chrisdart
 * Date: 11/7/18
 * Time: 3:49 PM
 */
$humanitiesTeacher = 0;
?>

<p><?php echo $benchmark->benchmark; ?></p>
<ul>
    <li>Subject: <?php echo $benchmark->subject; ?></li>
    <li>Quarter: <?php echo $quarter; ?>, <?php echo $benchmark->year; ?></li>
    <li>Grade(s): <?php echo format_grade_range($benchmark->gradeStart, $benchmark->gradeEnd); ?></li>
</ul>
<p>
    <a href="<?php echo base_url("benchmark/teacher_list?subject=$benchmark->subject&year=$benchmark->year&gradeStart=$benchmark->gradeStart&gradeEnd=$benchmark->gradeEnd"); ?>">Go
        Back</a>
</p>

<table class="list">
    <thead>
    <tr>
        <th>Student</th>
        <th>Quarter</th>
        <th>Grade & Comments</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($students as $student): ?>
        <?php if ($benchmark->subject == "Humanities"): ?>
            <?php if ($student->humanitiesTeacher != $humanitiesTeacher): ?>
                <tr>
                    <td colspan="3">Humanities Teacher: <?php echo $student->humanitiesTeacherName; ?></td>
                </tr>
            <?php $humanitiesTeacher = $student->humanitiesTeacher;?>
            <?php endif; ?>
        <?php endif ?>
        <tr>
            <td>
                <?php echo link_student($student,"student_benchmark/select?kStudent=$student->kStudent&student_grade=$student->stuGrade&subject=$benchmark->subject&year=$benchmark->year&quarter=$quarter", "View %s Benchmarks for $benchmark->subject", FALSE); ?>
            </td>
            <td><?php echo $quarter; ?></td>
            <td><input type="text"
                       data-benchmark="<?php echo $benchmark->kBenchmark; ?>"
                       data-student="<?php echo $student->kStudent; ?>"
                       data-quarter="<?php echo $quarter; ?>"
                       id="g_<?php $benchmark->kBenchmark; ?>"
                       name="grade"
                       size="2"
                       class="benchmark-grade benchmark-string"
                       value="<?php echo get_value($student->benchmark, 'grade'); ?>"
                    <?php echo $benchmark->year != get_current_year() ? "readonly" : ""; ?>
                />
                <input type="text"
                       data-benchmark="<?php echo $benchmark->kBenchmark; ?>"
                       data-student="<?php echo $student->kStudent; ?>"
                       data-quarter="<?php echo $quarter; ?>"
                       name="comment"
                       class="benchmark-comment benchmark-string"
                       value="<?php echo get_value($student->benchmark, "comment", ""); ?>"/>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>

</table>