<?php
/**
 * Created by PhpStorm.
 * User: chrisdart
 * Date: 11/7/18
 * Time: 4:10 PM
 */

?>

<form id="edit-one-benchmark" method="post" action="<?php echo site_url("student_benchmark/update?edit_one=1"); ?>">
    <input type="hidden" name="kStudent" value="<?php echo $benchmark->kStudent; ?>"/>
    <input type="hidden" name="kBenchmark" value="<?php echo $benchmark->kBenchmark; ?>"/>
    <p>
        <label for="quarter">Quarter</label>
        <input type="text" size="2" name="quarter" value="<?php echo $benchmark->quarter; ?>" readonly/></p>
    <p>
        <label for="term">Term</label>
        <input type="text" size="10" name="term" value="<?php echo $benchmark->term; ?>" readonly/>
    </p>

    <p>
        <label for="year">Term</label>
        <input type="text" size="10" name="year" value="<?php echo $benchmark->year; ?>" readonly/>
    </p>
    <p>
        <label for="term">Grade</label>
        <input type="text" size="3" name="grade" value="<?php echo $benchmark->grade; ?>"/>
    </p>
    <p>
        <label for="term">Comment</label>
        <input type="text" name="comment" value="<?php echo $benchmark->comment; ?>"/>
    </p>
    <p>
        <input type='submit' class='save_student button' value='Save'/>
    </p>
</form>
