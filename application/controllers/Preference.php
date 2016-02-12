<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author chrisdart
 * allow users to manage preferences
 */

class Preference extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("preference_model","preference");

	}

	/**
	 * view preference based on the teacher id from the uri
	 */
	function view()
	{
		$kTeach = $this->uri->segment(3);
		$data["preferences"] = $this->preference->get_all($kTeach);
		$data["kTeach"] = $kTeach;
		$this->load->model("teacher_model","teacher");
		$data["teacher"] = $this->teacher->get($kTeach);
		$data["title"] = "View and Change Preferences";
		$data["target"] = "preference/view";
		$this->load->view("page/index", $data);

	}

	/**
	 * update a preference
	 */
	function update()
	{
		if($this->input->post("kTeach")){
			$kTeach = $this->input->post("kTeach");
			if($kTeach == $this->session->userdata("userID") || $this->session->userdata("userID") == ROOT_USER){
				$type = $this->input->post("type");
				$value = $this->input->post("value");
				$output = $this->preference->update($kTeach, $type, $value);
				bake_cookie($type, $value);
				if($output){
					echo OK;
				}else{
					echo "The preference update did not work because of an error";
				}
			}
		}
	}

}