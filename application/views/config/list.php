<?php
?>


<h1 class="page-title"><?php echo $title;?></h1>

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