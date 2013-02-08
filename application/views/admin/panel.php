<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>


<h2>System-Wide Control Panel</h2>
<? if($this->input->cookie("admin")): ?>
<div class='notice'><?=$this->input->cookie("admin");?></div>
<? endif; ?>
<p><a href="<?=site_url("preference_type/type_list");?>" class="button">Preference Types</a>&nbsp;Edit the preference types used throughout the system for various functions</p>
<p><a href="#" class="button log_search">Search Logs</a>&nbsp;Search the site logs for login and logout history. </p>
<p><a href="student/update_grades" class="button">Update Student Grades</a>&nbsp;Update all student grades to the current year.</p>
<p><a href="<?=site_url("menu/show");?>" class="button">System Menus</a>&nbsp;Edit the dropdown menu values that are used in attendance, grades, narratives and elsewhere in the system</p> 