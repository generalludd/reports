<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* preference_type provides an administrative interface for
 * managing the preference options for the users
* preferences created do not have any effect unless they are coded into the system.
* This is just a tool for making the creation of preferences easier without
* having to go into the database. But it does allow modifying preference texts and
* other features.
*/
class preference_type extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		if($this->session->userdata("userID") == ROOT_USER){
			$this->load->model("preference_type_model","preference");

		}else{
			redirect();
		}
	}

	/**
	 * create a populated dialog for creating a new preference
	 */
	function create()
	{
		if($this->session->userdata("userID") == ROOT_USER){
			$data["preference"] = NULL;
			$formats = $this->preference->get_formats();
			$data["formats"] = get_keyed_pairs($formats, array("format","format"));
			$data["action"] = "insert";
			$this->load->view("preference_type/edit",$data);
		}else{
			echo "<div class='notice'>You do not have authorization to edit preference types</div>";
		}
	}

	/**
	 * list all the types of preferences available for the system.
	 * if the type is included in the uri, the type will be highlighted in the list
	 */
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

	/**
	 * insert a new preference type
	 */
	function insert()
	{
		$type = $this->preference->insert();
		redirect("preference_type/type_list/$type/#type");
	}

	/**
	 * displays a dialog for editing a particular preference_type
	 */
	function edit()
	{
		if($this->session->userdata("userID") == ROOT_USER){
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

	/**
	 * update preference type
	 */
	function update()
	{
		$type = $this->input->post("type");
		$this->preference->update($this->input->get_post("type"));
		redirect("preference_type/type_list/$type/#$type");

	}

	/**
	 * delete a preference_type.
	 */
	function delete()
	{
		if($this->session->userdata("userID") == ROOT_USER)
		{
			$type = $this->input->post("type");
			$this->preference->delete($type);
			if($this->input->post("ajax") == 1){
				echo "The preference type $type has been deleted.";
			}else{
				redirect("preference_type/type_list");
			}
		}
	}

}