<?php
echo "<div id='subject_list'>";
foreach($subjects as $subject){
    $deleteButton = "<span class='delete link delete_subject small' id='s_$subject->kSubject'>Delete</span>";
    echo "<p>$subject->subject $deleteButton</p>";
}
echo "</div>";
