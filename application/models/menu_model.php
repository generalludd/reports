<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Menu_model extends CI_Model
{
	var $category = "";
	var $label = "";
	var $value = "";

	function __construct()
	{
		parent::__construct();
	}

	function get_pairs($category, $order = null)
	{

		$this->db->where('category', $category);
		$this->db->select('label');
		$this->db->select('value');
		$direction = "ASC";
		$order_field = "value";

		if($order!=null){
			//extract($options);
			if($order['direction']){
				$direction = $order['direction'];
			}
			if($order['field']){
				//          $order="ORDER BY $sortField $sortOrder";
				$order_field = $order['field'];
			}
		}

		$this->db->order_by($order_field, $direction);
		$this->db->from('menu');
		$result = $this->db->get()->result();
		return $result;
		
	}

}