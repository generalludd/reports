<?php defined('BASEPATH') OR exit('No direct script access allowed');

$dbRole = $this->session->userdata("dbRole");
$userID = $this->session->userdata("userID");

$term = get_current_term();
$year = get_current_year();
$user_buttons[] = array("selection"=>"teacher", "text"=>"My Account","href"=>site_url("teacher/view/$userID"));
$user_buttons[] = array("selection"=>"preference", "text" => "Preferences", "href" => site_url("preference/view/$userID") );
if($this->session->userdata("userID")== 1000){
	$user_buttons[] = array("selection"=>"admin","text"=>"Site Admin","href"=>site_url("admin"));
}else{
	$user_buttons[] = array("selection" => "feedback", "text" =>"Feedback", "type" => "span", "class" => "button create_feedback");
}
$user_buttons[] = array("selection" => "auth", "text" => "Log Out", "href" => site_url("auth/logout"),"title" => $this->session->userdata("username"));

print create_button_bar($user_buttons);