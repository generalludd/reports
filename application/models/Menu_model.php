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

	function prepare_variables()
	{
		$variables = array("category","label","value");

		for($i = 0; $i < count($variables); $i++){
			$myVariable = $variables[$i];
			if($this->input->post($myVariable)){
				$post = $this->input->post($myVariable);
				$this->$myVariable = $post;
			}
		}
	}

	function get_all($category = NULL)
	{
	    if($category){
	        $this->db->where("category",$category);
	    }
		$this->db->order_by("category");
		$this->db->order_by("label");
		$this->db->from("menu");
		$result = $this->db->get()->result();
		return $result;
	}

	function get($kMenu)
	{
		$this->db->from("menu");
		$this->db->where("kMenu",$kMenu);
		$row = $this->db->get()->row();
		return $row;
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

	function get_label($category,$value){
$this->db->from("menu");
$this->db->where("category", $category);
$this->db->where("value" , $value);
$result = $this->db->get()->row();
return $result->label;
	}

	function get_categories()
	{
		$this->db->from("menu");
		$this->db->select("`category` as 'key',`category` as 'value'");
		$this->db->distinct("category");
		$this->db->order_by("category","ASC");
		$result = $this->db->get()->result();
		return $result;
	}

	function item_exists($label, $value)
	{
		$this->db->where("label",$label);
		$this->db->where("value",$value);
		$this->db->from("menu");
		$result = $this->db->get()->num_rows();
		return $result;
	}

	function insert()
	{
		$this->prepare_variables();
		if(!$this->item_exists($this->label,$this->value)){
			$this->db->insert("menu", $this);
		}
	}

	function update($kMenu)
	{
		$this->db->where("kMenu", $kMenu);
		$this->prepare_variables();
		$this->db->update("menu",$this);

	}

}