<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Config {
	protected $CI;
	
	public function __construct()
	{
		// Assign the CodeIgniter super-object
		$this->CI =& get_instance();
	}
	
	public function get_config($key){
		$this->CI->load->model("config_model","config");
		return $this-CI->config->get_by_key($key); 
	}
	
}