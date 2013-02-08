<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		if($this->session->userdata("userID") == 1000){
			$this->load->model("menu_model","menu");
		}else{
			redirect("/");
		}
	}

	function index()
	{
		$this->show();
	}

	function show()
	{
		$data["categories"] = $this->menu->get_all();
		$data["target"] = "menu/list";
		$data["title"] = "Edit Menu Items";
		$this->load->view("page/index",$data);

	}

	function edit()
	{
		$kMenu = $this->input->get("kMenu");
		if($this->session->userdata("userID") == 1000){
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

	function update()
	{
		$kMenu = $this->input->post("kMenu");
		$this->menu->update($kMenu);
		redirect("menu");
	}

}