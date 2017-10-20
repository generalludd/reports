<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * FSMN CodeIgniter Class
 * @author chrisdart
 * @packageCodeIgniter
 * @subpackage Libraries
 * @link https://github.com/fsmn
 *
 */
class Menu_lib {
	protected $CI;
	
	public function __construct()
	{
		// Assign the CodeIgniter super-object
		$this->CI =& get_instance();
		
		$this->CI->load->model("menu_model","menu");
	}
	
	public function get_label($category, $key){
		return $this->CI->menu->get_label($category, $value); 
	}
	
}