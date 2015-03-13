<?php defined('BASEPATH') OR exit('No direct script access allowed');

// My_Model.php Chris Dart Feb 4, 2015 1:31:39 PM chrisdart@cerebratorium.com

class MY_Model extends CI_Model {

    function __construct(){
        parent::__construct();
    }

    function _log($target = "notice",$live_server = FALSE){
       if($_SERVER['HTTP_HOST'] == "test-reports.server.fsmn" || $live_server == TRUE){
           $this->session->set_flashdata($target, $this->db->last_query());
       }
    }
}