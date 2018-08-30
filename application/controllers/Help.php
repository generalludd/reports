<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Help extends MY_Controller {

	function __construct()
	{
		parent::__construct ();
		$this->load->model ( "help_model" );
	}
	function get($kHelp){
		$data['message'] = $this->help_model->get_by_id ( $kHelp );
		$data['target'] = "help/dialog";
		$data['title'] = "Help";
		$this->load->view("help/dialog",$data);
	}
	/**
	 * shows help dialog in tandem with jQuery and css code
	 */
	function _get()
	{
		$helpTopic = $this->input->get ( "helpTopic" );
		$helpSubtopic = $this->input->get ( "helpSubtopic" );
		echo $this->help_model->get ( $helpTopic, $helpSubtopic );
	}
}