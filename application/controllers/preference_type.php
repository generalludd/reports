<?php defined('BASEPATH') OR exit('No direct script access allowed');


class preference_type extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		if($this->session->userdata("userID") == 1000){
			$this->load->model("preference_type_model","preference");
				
		}else{
			redirect("/");
		}
	}

	function create()
	{
		if($this->session->userdata("userID") == 1000){
			$data["preference"] = NULL;
			$formats = $this->preference->get_formats();
			$data["formats"] = get_keyed_pairs($formats, array("format","format"));
			$data["action"] = "insert";
			$this->load->view("preference_type/edit",$data);
		}else{
			echo "<div class='notice'>You do not have authorization to edit preference types</div>";
		}
	}
	
	function type_list()
	{
		$data["type"] = NULL;
		if($this->uri->segment(3) != ""){
			$data["type"] = $this->uri->segment(3);
		}
		$data["preferences"] = $this->preference->get_all();
		$data["title"] = "Administer Preference Types";
		$data["target"] = "preference_type/list";
		$this->load->view("page/index", $data);
	}

	function insert()
	{
		$type = $this->preference->insert();
		redirect("preference_type/type_list/$type/#type");
	}


	function edit()
	{
		if($this->session->userdata("userID") == 1000){
			$type = $this->input->get("type");
			$data["preference"] = $this->preference->get($type);
			$formats = $this->preference->get_formats();
			$data["formats"] = get_keyed_pairs($formats, array("format","format"));
			$data["action"] = "update";
			$this->load->view("preference_type/edit",$data);

		}else{
			echo "<div class='notice'>You do not have authorization to edit preference types</div>";

		}

	}


	function update()
	{
		$type = $this->input->post("type");
		$this->preference->update($this->input->get_post("type"));
		redirect("preference_type/type_list/$type/#$type");

	}

}