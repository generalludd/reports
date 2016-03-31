<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

    /**
     * verify if a user is logged in.
     * This global function provides a core element of security to the
     * application
     */
    function __construct ()
    {
        parent::__construct();
        if (! is_logged_in($this->session->all_userdata())) {
            // determine the query to redirect after login.
            $uri = $_SERVER["REQUEST_URI"];
            if ($uri != "/auth") {
                bake_cookie("uri", $uri);
            }
            redirect("auth");
            die();
        }else{
        	$this->load->model("config_model");
        	define("YEAR_START", get_current_year() . "-" . $this->config_model->get_by_key ( "year-start" )->config_value);
        	define("MID_YEAR", get_current_year() + 1 . "-" .  $this->config_model->get_by_key ( "mid-year" )->config_value);
        	//currently editing cut-off is not used. 
        	define("EDITS_MID_YEAR",get_current_year() + 1 . "-" . $this->config_model->get_by_key("edits_mid-year")->config_value);
        	define("EDITS_YEAR_END",get_current_year() + 1 . "-" . $this->config_model->get_by_key("edits_year-end")->config_value);
        	 //attendance values for notification thresholds
        	define('TRUANCY_THRESHOLD',$this->config_model->get_by_key("truancy_threshold")->config_value);
        	define('UNEXCUSED_THRESHOLD',$this->config_model->get_by_key("unexcused_threshold")->config_value);
        	define('ILLNESS_THRESHOLD',$this->config_model->get_by_key("illness_threshold")->config_value);
        	//user info for permissions
        	 define('USER_ID',$this->session->userdata("userID"));
        	 define('DB_ROLE',$this->session->userdata('dbRole'));
        }
    }

    function index ()
    {
        redirect();
    }
    
    function _view($data){
    	if($this->input->get("ajax")){
    		$this->load->view($data["target"],$data);
    	}else{
    		$this->load->view("page/index",$data);
    	}
    }
}