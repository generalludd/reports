<?php
$current_group = NULL;

$buttons[] = array("text"=>"Add a Variable","class"=>"button dialog new small","href"=>site_url("config/create"),"userID"=>ROOT_USER);
echo create_button_bar($buttons);
?>


<h1 class="page-title"><?php echo $title;?></h1>
<p>
These variables set some site-wide basic information. Unless a variable is actually used in the site code, adding one here will make no difference.
</p>
<table class="table list">
<thead>
<tr>
<th>
</th>
<th>
Key
</th>
<th>
Value
</th>
<th>
Description
</th>
</tr>

</thead>
<tbody>
<?php foreach($items as $item):?>
<?php if($current_group != $item->config_group):?>
<tr>
<td colspan=4>
<?php $current_group = $item->config_group; 
echo ucwords(humanize($item->config_group));?>
</td>
<?php endif;?>
<tr>
<td>
<?php echo create_button(array("text"=>"Edit","class"=>"edit dialog button","href"=>site_url("config/edit/$item->kConfig")));?>
</td>
<td>
<?php echo ucwords(humanize($item->config_key,"_"));?>
</td>
<td>
<?php echo $item->config_value;?>
</td>
<td>
<?php echo $item->config_description; ?>
</td>
</tr>

<?php endforeach;?>

</tbody>


</table>