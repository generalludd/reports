<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>


    <fieldset class="search_fieldset">
        <legend>Search Parameters</legend>
        <?php
        if (!empty($options)):?>
            <ul>
                <?php foreach ($options as $key => $value): ?>
                    <li><strong><?php echo $key; ?></strong> <?php echo $value; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Showing all Log Entries.</p>
        <?php endif;
        ?>

        <div class="button-box">
            <a class="button log_search">Refine Search</a>
        </div>
    </fieldset>
<table class="list">
    <thead>
    <tr>
        <th>Timestamp</th>
         <th>Action</th>
    </tr>
    </thead>
<tbody>
<?php $username = FALSE;?>

<?php foreach ($logs as $log):?>
<?php if($log->username != $username):?>
<tr>
    <td colspan="2"><?php echo $log->username;?></td>
</tr>
<?php $username = $log->username;?>
<?php endif; ?>
<tr>
<td><?php echo format_timestamp($log->time);?></td>
    <td><?php echo $log->action;?></td>
</tr>
<?php endforeach; ?>
</tbody>

</table>
