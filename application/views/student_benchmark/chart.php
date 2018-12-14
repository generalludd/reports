<?php
/**
 * Report of student benchmarks for every quarter within search parameters
 */
$current_subject = "";
$current_category = "";
$footnote_count = 1;
$footnotes = array();

?>
<?php $this->load->view("student/navigation"); ?>
<?php if ($subject): ?>
    <?php $buttons[] = array("selection" => "benchmarks", "href" => site_url("student_benchmark/select/?kStudent=$kStudent&subject=$subject&student_grade=$student_grade&quarter=$quarter&term=$term&year=$year&edit=1"), "class" => "button edit", "text" => "Edit"); ?>
<?php endif; ?>
<?php $buttons[] = array("selection" => "benchmarks", "href" => "javascript:print();", "class" => "button print", "text" => "Print"); ?>
<?php $buttons[] = array("selection" => "benchmarks", "href" => site_url("student_benchmark/select?search=1&refine=1&kStudent=$kStudent"), "class" => "button dialog", "text" => "Refine Search"); ?>

<?php echo create_button_bar($buttons, array("class" => "small")); ?>
    <div class="benchmark-legend">
        <?php $this->load->view("benchmark/legend"); ?>
    </div>
<?php foreach ($subjects as $subject): ?>
<div class="benchmark-group">

    <h3><?php echo $subject->subject; ?></h3>
    <table class="chart list">
        <thead>
        <tr class="benchmark-header">
            <th style="text-align: right; padding-right: 10px;">Quarters
            </th>
            <?php for ($i = 1; $i <= $quarters; $i++): ?>
                <th class="benchmark-quarters"><?php echo "$i"; ?>
                </th>
            <?php endfor; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($subject->benchmarks as $benchmark): ?>
            <?php if ($benchmark->category != $current_category): ?>
                <tr class="benchmark-header">
                    <td colspan=<?php echo $quarters + 1; ?>><?php echo stripslashes($benchmark->category); ?></td>
                </tr>
                <?php $current_category = $benchmark->category; ?>
            <?php endif; ?>
            <?php $my_row = 0; ?>
            <?php foreach ($benchmark->quarters as $quarter): ?>
                <?php $my_row += in_array(get_value($quarter['grade'], "grade"), array("E", "M", "P", "S", "0")); ?>
            <?php endforeach; ?>
            <?php if ($my_row): ?>
                <tr class="benchmark-row">
                    <td><?php echo stripslashes($benchmark->benchmark); ?></td>

                    <?php foreach ($benchmark->quarters as $grade): ?>

                        <td class="benchmark-grade <?php echo get_value($grade['grade'], "grade", "X") == "X" ? "not-assessed" : ""; ?>"><?php echo get_value($grade['grade'], "grade", "X"); ?>
                            <?php if (get_value($grade['grade'], "comment")): ?>
                                <sup><?php echo $footnote_count; ?></sup>
                                <?php $footnotes[] = array("count" => $footnote_count, "comment" => $grade['grade']->comment); ?>
                                <?php $footnote_count++; ?>

                            <?php endif; ?>
                        </td>

                    <?php endforeach; ?>
                </tr>
            <?php endif; ?>

        <?php endforeach; ?>

        <?php if (!empty($footnotes)): ?>
            <?php foreach ($footnotes as $footnote): ?>

                <tr class="benchmark-footnotes">
                    <td>
                        <sup><?php echo $footnote['count']; ?></sup>
                        <?php echo $footnote['comment']; ?>
                    </td>
                </tr>
            <?php endforeach; ?>

        <?php endif; ?>
    </tbody>
    </table>
    <?php $footnotes = array();
    $footnote_count = 1; ?>
</div>

<?php endforeach;
