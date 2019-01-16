<?php
/**
 * Created by PhpStorm.
 * User: chrisdart
 * Date: 2019-01-16
 * Time: 15:54
 */
?>
<?php $the_dates = array(); ?>
<?php if (!empty($dates)): ?>
    <p><strong>Previous Test Dates</strong></p>
    <ul>
        <?php foreach ($dates as $date): ?>
            <?php if ($date->year != $current_year): ?>
                <?php if (!in_array($date->testDate, $the_dates)): ?>
                    <li><?php echo format_date($date->testDate); ?> </li>
                <?php endif; ?>
            <?php endif; ?>
            <?php $the_dates[] = $date->testDate; ?>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>