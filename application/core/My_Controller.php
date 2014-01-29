<<<<<<< HEAD
<?php

if (! defined('BASEPATH')) exit('No direct script access allowed');
=======
<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
>>>>>>> Commenting and minor cleanup.

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
        }
    }

    function index ()
    {
        redirect();
    }
}