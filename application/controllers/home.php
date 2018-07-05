<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller
{

	public function __construct()
{
parent::__construct();
$this->load->model('home_model');
$this->load->helper('url');

date_default_timezone_set("Asia/Calcutta");
session_start();
}
function index(){
// Authorize.net lib
		$this->load->library('authorize_net');
		
			$auth_net = $_POST;
			
		$this->authorize_net->setData($auth_net);
		// Try to AUTH_CAPTURE
		
		if( $this->authorize_net->authorizeAndCapture())
		{
			$result =$this->authorize_net->getTransactionId() ;
			
			$s = $this->home_model->update_status_authorize_net($result);
			if($s==true){
				$finresult[] = array( 'status'  => 'success','message' =>"Payment Sucessfully", 'code'    => 'success' 						
			);
				print json_encode($finresult);
			}
			
		}
		else
		{
			$s = $this->home_model->update_status_authorize_net_cancel();
			if($s==true){
			
			$finresult[] = array( 'status'  => 'failed','message' =>$this->authorize_net->getError() , 'code'    => 'failed' 						
			);
				print json_encode($finresult);
			}
		}
	}
	function confirm_booking(){
		return $this->load->view('confirm_book');
	}function call_add(){
		$id = $_POST;
		$s = $this->home_model->add_call($id);
		$finresult[] = array( 'status'  => 'success','message' =>"Successfully verified. You should get a Call from our Call Center immediately", 'code'    => 'success' 						
			);
				print json_encode($finresult);
	}function braintree(){
		$data =$_POST;
		$this->load->library('braintree_lib');

		$result = Braintree_Transaction::sale(array(
'amount' => $data['x_amount'],
'creditCard' => array(
'number' => $data['x_card_num'],
'cardholderName' =>  $data['x_card_name'],
'expirationDate' => $data['x_exp_date'],
'cvv' => $data['x_card_code']

)
));

if ($result->success)
{
if($result->transaction->id)
{
 $result =$result->transaction->id ;
			
			$s = $this->home_model->update_status_authorize_net($result);
			if($s==true){
				$finresult[] = array( 'status'  => 'success','message' =>"Payment Sucessfully", 'code'    => 'success' 						
			);
				print json_encode($finresult);
			}
}
}

else 
{
$finresult[] = array( 'status'  => 'failed','message' =>$result->message , 'code'    => 'failed' 						
			);
				print json_encode($finresult);
}

		
		
	}

}
?>