<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Config extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model("config_model");
	}


	function index()
	{
		$this->config_model->restore();
	}


}