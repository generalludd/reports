<?php defined('BASEPATH') OR exit('No direct script access allowed');

$dbRole = $this->session->userdata("dbRole");
$userID = $this->session->userdata("userID");

$term = get_current_term();
$year = get_current_year();
$user_buttons[] = array("item"=>"preference", "text" => "Preferences", "href" => site_url("preference/view/$userID") );
if($this->session->userdata("userID")== 1000){
	$user_buttons[] = array("item"=>"admin","text"=>"Site Admin","href"=>site_url("admin"));
}else{
	$user_buttons[] = array("item" => "feedback", "text" =>"Feedback", "type" => "span", "class" => "button create_feedback");
}
$user_buttons[] = array("item" => "auth", "text" => "Log Out", "href" => site_url("auth/logout"),"title" => $this->session->userdata("username"));

print create_button_bar($user_buttons);