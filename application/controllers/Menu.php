<?php defined('BASEPATH') OR exit('No direct script access allowed');
//menu.php is an administrative tool restricted to the administrator user
class Menu extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		if($this->session->userdata("userID") == ROOT_USER){
			$this->load->model("menu_model","menu");
		}else{
			redirect("/");
		}
	}

	function index()
	{
		$this->show();
	}

	/**
	 * Show all the menu items
	 */
	function show()
	{
		$data["categories"] = $this->menu->get_all();
		$data["target"] = "menu/list";
		$data["title"] = "Edit Menu Items";
		$this->load->view("page/index",$data);

	}

	/**
	 * Edit a single menu item
	 * Restricted to the administrator account
	 */
	function edit()
	{
		$kMenu = $this->input->get("kMenu");
		if($this->session->userdata("userID") == ROOT_USER){
			$data["menu_item"] = $this->menu->get($kMenu);
			$data["target"] = "menu/edit";
			$data["action"] = "update";
			$data["title"] = "Edit Menu Item";
			if($this->input->get("ajax")){
				$this->load->view($data["target"],$data);
			}else{
				$this->load->view("page/index",$data);
			}
		}
	}

	/**
	 * update a menu item
	 */
	function update()
	{
		$kMenu = $this->input->post("kMenu");
		$this->menu->update($kMenu);
		redirect("menu");
	}

	/**
	 * show blank form to create a menu item
	 */
	function create()
	{
		$data["action"] = "insert";
		$data["target"] = "menu/edit";
		$data["title"] = "Insert Menu Item";
		$data["menu_item"] = NULL;
		$categories = $this->menu->get_categories();
		$data["categories"] = get_keyed_pairs($categories, array("key","value"),NULL,TRUE);
		$this->load->view("menu/edit",$data);

	}

	function insert()
	{
		if($this->session->userdata("userID") == ROOT_USER){
			$this->menu->insert();
		}
		$this->index();
	}

}