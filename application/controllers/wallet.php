<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wallet extends CI_Controller
{

	public function __construct()
{
parent::__construct();


$this->load->model('Model_wallet');
$this->load->library('session');

$this->load->helper('url');

date_default_timezone_set("Asia/Calcutta");
session_start();
}

	public function index()
	{
	
   $this->load->view('add_wallet');
  
	}
	public function set_session()
	{
		
		 $id = $_POST["id"];
		
		$this->session->set_userdata('walletamounts',$id);
		$data["s"]=$this->session->userdata('walletamounts');
		$user =$this->session->userdata('username');
		
		if($user==''){
			return "Please Login";
			
		}else{
			return $this->load->view('paypal_wallet',$data);
		}
		
	}public function result_wallet()
	{
	
   $this->load->view('result_wallet');
  
	}public function cancel_wallet()
	{
	
   $this->load->view('cancel_wallet');
  
	}public function add_amount_wallet()
	{
		
		 $data= $_POST;
		 
		 $res=$this->Model_wallet->add_wallet($data);
}
}