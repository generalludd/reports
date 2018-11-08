<?php
/**
 * Created by PhpStorm.
 * User: chrisdart
 * Date: 11/7/18
 * Time: 4:10 PM
 */

?>
<h4><?php echo $title;?></h4>
<form id="edit-one-benchmark" method="post" action="<?php echo site_url("student_benchmark/update?edit_one=1"); ?>">
    <input type="hidden" name="kStudent" value="<?php echo $kStudent; ?>"/>
    <input type="hidden" name="kBenchmark" value="<?php echo $kBenchmark; ?>"/>
    <p>
        <label for="quarter">Quarter</label>
        <input type="text" size="2" name="quarter" value="<?php echo $quarter; ?>" readonly/></p>
    <p>
        <label for="year">Term</label>
        <input type="text" size="10" name="year" value="<?php echo $year; ?>" readonly/>
    </p>
    <p>
        <label for="term">Benchmark</label>
        <input type="text" size="3" name="grade" value="<?php echo get_value($benchmark,"grade"); ?>"/>
    </p>
    <p>
        <label for="term">Comment</label>
        <input type="text" name="comment" value="<?php echo get_value($benchmark,"comment"); ?>"/>
    </p>
    <p>
        <input type='submit' class='save_student button' value='Save'/>
    </p>
</form>
