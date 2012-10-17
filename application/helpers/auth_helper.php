<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function is_logged_in($data)
{
	$result = false;
	if(array_key_exists("username", $data) && array_key_exists("dbRole", $data) && array_key_exists("userID", $data)){
			$result = true;
	}
	return $result;

}

function set_user_cookies($cookies)
{
	foreach($cookies as $cookie){
		$data = array();
		$data["name"] = $cookie->type;
		$data["value"] = $cookie->value;
		$data["expire"] = 0;
		set_cookie($data);
	}
}