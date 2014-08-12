<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>


<h2>System-Wide Control Panel</h2>
<? if($this->input->cookie("admin")): ?>
<div class='notice'><?=$this->input->cookie("admin");?></div>
<? endif; ?>
<div class="button-box button-bar"><a href="<?=site_url("preference_type/type_list");?>" class="button small">Preference Types</a>&nbsp;Edit the preference types used throughout the system for various functions</div>
<div class="button-box button-bar"><a href="#" class="button small log_search">Search Logs</a>&nbsp;Search the site logs for login and logout history. </div>
<div class="button-box button-bar"><a href="student/update_grades" class="button small">Update Student Grades</a>&nbsp;Update all student grades to the current year.</div>
<div class="button-box button-bar"><a href="<?=site_url("menu/show");?>" class="button small">System Menus</a>&nbsp;Edit the dropdown menu values that are used in attendance, grades, narratives and elsewhere in the system</div>
<div class="button-box button-bar"><a href="<?=site_url("config/show_subject_sort");?>" class="button small">Set Global Subject Order</a>&nbsp;Change the order of subjects that report cards and narratives are arranged</div>