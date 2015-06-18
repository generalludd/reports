<?php
defined('BASEPATH') or exit('No direct script access allowed');
if(count($reports) > 0):
// list.php Chris Dart Nov 20, 2014 2:48:08 PM chrisdart@cerebratorium.com
$sortTerm = sprintf("%s %s",$narrTerm,format_schoolyear($narrYear));
$div_classes[] = "column";

if($narrTerm == "Mid-Year"){
    $div_classes[] = "even";
}else{
    $div_classes[] = "odd";
}

?>

<div class="<?=implode(" ",$div_classes);?>">

    <h4><?=$sortTerm; ?></h4>

<? $print_buttons[] = array(
                "selection" => "print",
        "class"=>"button small",
                "text" => "Preview &amp; Print Report",
                "href" => site_url("narrative/print_student_report/$kStudent/$narrTerm/$narrYear"),
                "target" => "_blank"
        );
        if ($stuGrade > 4) {
            $print_buttons[] = array(
                    "selection" => "print",
                    "text" => "Print Grades",
                    "class"=>"button small",
                    "href" => site_url(
                            sprintf("grade/report_card?kStudent=%s&year=%s&term=%s&subject=0", $kStudent, $narrYear, $narrTerm)),
                    "target" => "_blank"
            );
        }
        ?>
<?=create_button_bar($print_buttons); ?>

        <table class='list'><thead><tr><th><strong>Subject</strong></th><th><strong>Author</strong></th><th>Last Modified</th><th></th></tr></thead><tbody>

<? foreach($reports as $narrative):?>
<tr><td><strong><?=$narrative->narrSubject;?></strong></td>
<td><a href="<?=site_url("narrative/teacher_list/$narrative->kTeach");?>"><?=format_name($narrative->teachFirst,$narrative->teachLast);?></a></td>
<td><?=format_timestamp($narrative->recModified);?></td>
<td><a class="button small" href="<?=site_url("narrative/view/$narrative->kNarrative");?>">View/Edit</a>

</td>
</tr>

<? endforeach; ?>

</tbody></table>
</div>

<? endif;
