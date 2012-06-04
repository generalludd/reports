<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function is_logged_in($data)
{
	$result = false;
	if(array_key_exists("username", $data) && array_key_exists("dbRole", $data) && array_key_exists("userID", $data)){
			$result = true;
	}
	return $result;

}