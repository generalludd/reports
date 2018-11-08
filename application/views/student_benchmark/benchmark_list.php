<?php
/**
 * Created by PhpStorm.
 * User: chrisdart
 * Date: 11/7/18
 * Time: 3:49 PM
 */

?>

<p><?php echo $benchmark->benchmark;?></p>
<ul>
    <li >Subject: <?php echo $benchmark->subject; ?></li>
    <li>Quarter: <?php echo $quarter;?>, <?php echo $benchmark->year; ?></li>
    <li>Grade(s): <?php echo format_grade_range($benchmark->gradeStart, $benchmark->gradeEnd);?></li>
</ul>
<p>
    <a href="<?php echo base_url("benchmark/teacher_list?subject=$benchmark->subject&year=$benchmark->year&gradeStart=$benchmark->gradeStart&gradeEnd=$benchmark->gradeEnd");?>">Go Back</a>
</p>

<table class="list">
    <thead>
    <tr>
        <th>Student</th>
        <th>Quarter</th>
        <th>Grade</th>
        <th>Comments</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($students as $student): ?>
        <tr>
            <td><?php echo format_name($student->stuFirst, $student->stuLast, $student->stuNickname);?></td>
            <td><?php echo $quarter;?></td>
            <td><?php echo get_value($student->benchmark,"grade");?></td>
            <td><?php echo get_value($student->benchmark,"comment");?></td>
            <td><a href="<?php echo base_url("student_benchmark/edit_one/$benchmark->kBenchmark/$student->kStudent/$quarter")?>" class="link dialog">Edit</a></td>
        </tr
    <?php endforeach; ?>
    </tbody>

</table>