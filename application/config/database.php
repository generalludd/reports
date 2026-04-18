<?php
$active_group = 'default';
$query_builder = TRUE;

if(file_exists(APPPATH.'config/database.local.php')){
  include(APPPATH.'config/database.local.php');
}
