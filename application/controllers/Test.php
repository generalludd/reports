<?php

class Test extends CI_Controller {

	function index(){
		$to = "chrisd@fsmn.org";
		$subject = "Test Message";
		$message = "A message";
		$headers = "from:chrisd@fsmn.org";
		mail (  $to ,  $subject ,  $message, $headers);
	}


}
