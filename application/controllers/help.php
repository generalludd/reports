<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Help extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model("help_model");
	}
	
	function get()
	{
		$helpTopic = $this->input->get("helpTopic");
		$helpSubtopic = $this->input->get("helpSubtopic");
		echo $this->help_model->get($helpTopic, $helpSubtopic);
	}
	
}