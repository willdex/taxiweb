<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Callmycab extends CI_Controller
{

	public function __construct()
{
parent::__construct();

$this->load->helper('form');
$this->load->helper('url');
$this->load->helper('file');
$this->load->library('form_validation');
$this->load->model('Model_cab','home');
$this->load->database();
$this->load->library('session');
$this->load->library('image_lib');
$this->load->helper('cookie');
$this->load->library('email');
 $this->load->library('pagination');

date_default_timezone_set("Asia/Calcutta");
session_start();
}

	public function index()
	{
	$s = file_exists(APPPATH.'controllers/installer.php');
	if($s ==1)
	{
		redirect('installer');
	}
	
   $this->load->view('callmycab_home');
  
	}
	
	public function visits()
	{
		$data=$_POST;
//print_r($data);exit;
//echo $data['value'];exit;
$res=$this->home->rating($data);
// print_r($res);
echo $res;
	}
	public function testajax()
	{
		$data=$_POST;

$res=$this->home->test($data);
// print_r($res);
echo $res;
	}

    public function userlogin()
	{
		$data=$_POST;
		$result = $this->home->signup($data);
		echo $result;
	}
	 public function select()
	 {
		 $this->load->view('cmc_selecttaxi');
	 }
		//User logged in, dothe magic
		 public function timepickera()
	{
		//$data=$_POST;
		$this->load->view('pickers');
	}	
	 public function convert()
	{
		//$data=$_POST;
		$this->load->view('convert');
	}		
		 public function timepicker()
	{
		//$data=$_POST;
		$this->load->view('picker');
	}
		 public function timepicker1()
	{
		//$data=$_POST;
		$this->load->view('picker1');
	}
	 public function timepicker2()
	{
		//$data=$_POST;
		$this->load->view('picker2');
	}
	 public function timepicker3()
	{
		//$data=$_POST;
		$this->load->view('picker3');
	}
	 public function timepicker4()
	{
		//$data=$_POST;
		$this->load->view('picker4');
	}
	 public function timepicker5()
	{
		//$data=$_POST;
		$this->load->view('picker5');
	}
	 public function paypal_select()
	{
		//$data=$_POST;
		$this->load->view('select_payment');
	}
	public function book()
	{
		$data=$_POST;
//print_r($data);exit;
//echo $data['value'];exit;
$book=$this->home->booking($data);
// print_r($res);
echo $book;
	}
	
	public function logout()
	{
		$this->session->unset_userdata('username');
			 //redirect('/', 'refresh');
		delete_cookie('username');
		 redirect('/', 'refresh');
	}
	
	
	public function account($offset=0)
	{
	 

  
  $config['base_url'] = base_url()."callmycab/account";
     $config["total_rows"] = $this->home->record_count();

$config['per_page'] = 4;
 $config["uri_segment"] = 3;


$this->pagination->initialize($config);
  $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data["results"] = $this->home->

            fetch_countries($config["per_page"], $page);

        $data["links"] = $this->pagination->create_links();

 

        $this->load->view('myaccount', $data);

    }

  
	
	public function contact()
	{
		$data=$_POST;
//print_r($data);exit;
//echo $data['value'];exit;
$contact=$this->home->contacted($data);
// print_r($res);
echo $contact;
	}
		
		 public function search()
	{
		//$data=$_POST;
		
		
		$data=$_POST;
//print_r($data);exit;
//echo $data['value'];exit;
$limit="";
$start="";

$cancel=$this->home->fetch_countries1($limit,$start,$data);

// print_r($res);
echo $cancel;
		
		
		
	}
	 public function search1($offset=0)
	{
		
		 $data=$_POST;
	if($data){
		   $this->session->set_userdata('status_date',json_encode($data));
	}
$config['base_url'] = base_url()."callmycab/search1";
$config["total_rows"] = $this->home->record_count1();
$config['per_page'] = 4;
$config["uri_segment"] = 3;
$choice = $config["total_rows"] / $config["per_page"];
$config["num_links"] = round($choice);
 

$this->pagination->initialize($config);
 
  $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data["results"] = $this->home->

            fetch_countries1($config["per_page"], $page);

        $data["links"] = $this->pagination->create_links();

 
			 
			  $this->load->view('search',$data);
			   
		
	}
	
	
	
		 public function edit_booking()
	{
		//$data=$_POST;
		
		$this->load->view('edit_book');
		
	}
	
	public function update_booking()
	{
		$data=$_POST;
//print_r($data);exit;
//echo $data['value'];exit;
$cancel=$this->home->update_book($data);
// print_r($res);
echo $cancel;
	}
	
	
	
	public function cancel()
	{
		$data=$_POST;
//print_r($data);exit;
//echo $data['value'];exit;
$book1=$this->home->update_status($data);
// print_r($res);
echo $book1;
	}
	public function changepassword()
	{
		$data=$_POST;
//print_r($data);exit;
//echo $data['value'];exit;
$password=$this->home->update_change($data);
// print_r($res);
echo $password;
	}
	
	
	public function details()
	{
			
   $this->load->view('details');
	}
public function promo()
    {
        $data=$_POST;
        $res = $this->home->promocode($data);
        echo $res;
    }
	public function address()
    {
        $data=$_POST;
//print_r($data);exit;
//echo $data['value'];exit;
$add=$this->home->update_address($data);
// print_r($res);
echo $add;
    }
    public function pagination()
	{
			
   $this->load->view('pagination');
	}
	public function otp_verify()
	{
		$data=$_POST;
//print_r($data);exit;
//echo $data['value'];exit;
$otp=$this->home->update_otp($data);
// print_r($res);
echo $otp;
	}
	public function resend_otp()
	{
		$data=$_POST;
//print_r($data);exit;
//echo $data['value'];exit;
$otp=$this->home->update_resend_otp($data);
// print_r($res);
echo $otp;
	}
	 public function notfound()
	{
			
   $this->load->view('404');
	}
function query(){
	$data=$_POST;
	if($data){
		   $this->session->set_userdata('status_date',json_encode($data));
	}
	  $details=$this->session->userdata('status_date');
	$datails_arr=json_decode($details,true);
	    $status =$datails_arr['status'];
        $date =$datails_arr['date'];
	  if($username = $this->session->userdata('username')){
        $username = $this->session->userdata('username');
        }else{
        $username = $this->input->cookie('username', false);
        }
	 if($date){
 $this->db->where('pickup_date', $date);
 }
 $this->db->where('username', $username);
 
 $this->db->where('status', $status); 
 
$result = $this->db->get('bookingdetails');
       

    $items_per_page =4;
    $this->load->library('pagination');
    $config['base_url'] =base_url()."callmycab/account";
    $config['total_rows'] = $result->num_rows;
    $config['per_page'] = $items_per_page;
    $this->pagination->initialize($config);

    $current_page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
    $offset = $items_per_page * ($current_page - 1);
$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
  
 if($date){
 $this->db->where('pickup_date', $date);
 }
 $this->db->where('username', $username);
 
 $this->db->where('status', $status); 
 $this->db->limit($page, $items_per_page);

 $query2 = $this->db->get('bookingdetails');
   

    echo "<table border='1'>
    <tr>
    <th>date</th>";
    

    

   foreach($query2->result() as $row1){
   
        echo "<tr>";
        echo "<td>";echo $row1->pickup_area;echo "</td>";
       

        echo "</tr>";
   }
echo "<tr><td>" . $this->pagination->create_links() . "</td></tr>";
    echo "</table>";
}
public function reset_email()
	{
		$data=$_POST;
//print_r($data);exit;
//echo $data['value'];exit;
$otp=$this->home->update_reset_pass($data);
// print_r($res);
echo $otp;
	}
		 public function about()
	{
		//$data=$_POST;
		$this->load->view('about-us');
	}
	 public function page_index($page = null)
	{
		$data = array();
		
		 $data['num']  = $this->db->query("SELECT * FROM  static_pages WHERE page_name='$page'")->num_rows();	
		$data['new']  = $this->db->query("SELECT * FROM  static_pages WHERE page_name='$page'")->row();	 
		  if($data['num']>0){
		  $this->load->view('about-us',$data);	
		  }else{
           redirect('callmycab/notfound');
		  }		  
	}
	
		 public function search_s()
	{
		$data=$_POST;
		$type =$this->session->userdata('type');
		if($type=="user"){
			$this->load->view('search1');
		}else{
			$this->load->view('driver');
		}
		
	}
	 public function paypal()
	{
	$data=$_POST;		
   $this->load->view('paypal');
	}
	public function result_paypal()
	{
		if($this->session->userdata('username')){
        
       
	$data=$_POST;
	
   $this->load->view('result_paypal');
		}else{
		redirect(base_url());
		}
	}
	public function addpaypal()
	{
		$data=$_POST;
//print_r($data);exit;
//echo $data['value'];exit;
$paypal=$this->home->update_paypal($data);
// print_r($res);
echo $paypal;
	}
	public function cancel_paypal()
	{
		if($this->session->userdata('username')){
	$data=$_POST;		
   $this->load->view('cancel_paypal');
		}else{
   redirect(base_url());
		}
	}
	public function update_itemstatus()
	{
		$data=$_POST;

$paypal=$this->home->update_itemstatus($data);

echo $paypal;
	}
	public function contact_us_details()
	{
		$data=$_POST;

$result=$this->home->update_contact_us_details($data);

if($result=='true'){
           
            $finresult = array( 'status'  => 'success','message' => 'Mail send  Successfully ','code'    => 'registered');
          print json_encode( $finresult );
            }else{
				$finresult = array( 'status'  => 'failed','message' => 'error','code'    => 'registered');
         print json_encode( $finresult );
			}
	}
	
	
public function first_show(){
	unlink(APPPATH.'controllers/installer.php');
	redirect(base_url());
}	
	
	public function select_driver()
	{
		$data=$_POST;

$paypal=$this->home->update_itemstatus($data);

echo $paypal;
	}
} 


	


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>