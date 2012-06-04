<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Preference extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("preference_model");

	}

	function view()
	{
		$kTeach = $this->uri->segment(3);
		$data["preferences"] = $this->preference_model->get_all($kTeach);
		$data["kTeach"] = $kTeach;
		$data["title"] = "View and Change Preferences";
		$data["target"] = "preference/view";
		$this->load->view("page/index", $data);

	}

	function update()
	{
		if($this->input->post("kTeach")){
			$kTeach = $this->input->post("kTeach");
			if($kTeach == $this->session->userdata("userID") || $this->session->userdata("userID") == 1000){
				$type = $this->input->post("type");
				$value = $this->input->post("value");
				$output = $this->preference_model->update($kTeach, $type, $value);
				if($output){
					echo OK;
				}else{
					echo "The preference update did not work because of an error";
				}
			}
		}
	}



}