<?php

class Default_con extends CI_Controller
{

public function __construct(){
	parent::__construct();
	$this->load->helper('url');
}

public function index(){
	$s = file_exists(APPPATH.'controllers/installer.php');
	if($s ==1)
	{

		redirect('installer');
	}	else{
		redirect('callmycab');
	}	
   //$this->load->view('admin-login');
}
}