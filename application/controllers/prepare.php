<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Prepare extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$list = $this->uri->segment(3);
		$array = explode("-", $list);
		foreach($array as $item){
			print '<br/><br/>if($this->input->post(&quot;' . $item . '&quot;)){<br/>';
			print '$this->' . $item . ' = $this->input->post(&quot;' . $item . '&quot;);<br/>}';
				
		}
	}

	function pwd()
	{
		$source = $this->uri->segment(3);
		print md5($source);
	}

}