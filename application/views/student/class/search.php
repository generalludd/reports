<?php
?>
<style>
    .hidden {
        display: none;
    }

    ul.rows {
        flex-direction: column;
    }
</style>
<form id="class-search" action="<?php echo site_url("student/$action"); ?>"
      method="get">
    <div class="fieldset">
        <label for="year">School Year</label><br/>
      <?php echo form_dropdown('year', $yearList, $currentYear, "id='year' class='year'"); ?>
        - <input type="text" id='yearEnd' name="yearEnd" size="5"
                 maxlength="4" readonly
                 value="<?php echo $currentYear + 1; ?>"/>
    </div>
    <div class="fieldset">

        <label for="type">Class
            Grouping</label><br/><?php echo form_dropdown("type", $groupings, "", "id='type'"); ?>
    </div>
    <div class="hidden grades fieldset">
        <ul class="rows lower-school hidden">
            <li>
                <label for="grades">Kindergarten</label>
                <input type="radio"
                       name="grades[]"
                       id="grades"
                       value="0"></li>
            <li>
                <label for="grades">First & Second Grade</label>
                <input
                        type="radio" name="grades[]" id="grades" value="1"/>
            </li>
            <li>
                <label for="grades">Third & Fourth Grade</label>
                <input
                        type="radio" name="grades[]" id="grades" value="3"/>
            </li>
        </ul>
        <ul class="rows middle-school hidden">
            <li>
                <label for="grades">Fifth & Sixth Grade</label>
                <input
                        type="radio" name="grades[]" id="grades" value="5"/>
            </li>
            <li>
                <label for="grades">Seventh & Eighth Grade</label>
                <input
                        type="radio" name="grades[]" id="grades" value="7"/>
            </li>
        </ul>
    </div>
    <div class="fieldset">
        <input type="submit" class="button"/>
    </div>
</form>

<script type="text/javascript">
    $("#type").on("change", function (event) {
        console.log($(this).val());
        my_val = $(this).val();
        if (my_val == "classroom") {
            $(".grades").show();
            $(".lower-school").show();
            $(".middle-school").hide();
        } else if (my_val == "humanitiesTeacher" || my_val == "ab") {
            $(".grades").show();
            $(".lower-school").hide();
            $(".middle-school").show();
        } else if (my_val == "advisory") {
            $(".grades").hide();
            $(".lower-school").hide();
            $(".middle-school").hide();
        }
        $("input[type='checkbox']").prop("checked", false);

    });
</script>