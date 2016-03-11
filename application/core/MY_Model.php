<?php defined('BASEPATH') OR exit('No direct script access allowed');

// My_Model.php Chris Dart Feb 4, 2015 1:31:39 PM chrisdart@cerebratorium.com

class MY_Model extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    function _log($target = "log",$live_server = FALSE){
       if($_SERVER['HTTP_HOST'] == "reports" || $live_server == TRUE){
           $this->session->set_flashdata($target, $this->db->last_query());
       }
    }
    
    function _replace_into($table,$fields){
    	if(!is_array($fields)){
    		$fields = (array)$fields;
    	}
    	$keys = array_keys($fields);
    	$values = array_values($fields);
    	$query = sprintf("REPLACE INTO %s (`%s`) VALUES('%s')",$table, implode("`,`",$keys),implode("','",$values));
    	$this->db->query($query);
    	return $this->db->insert_id();

    }
}