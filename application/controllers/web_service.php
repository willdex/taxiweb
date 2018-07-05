<?php
error_reporting(0);
ob_start();
//error_reporting(0);
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}


class web_service extends CI_Controller
{
    public $zone_name = CUSTOM_ZONE_NAME;
    //public $zone_name = 'Asia/Kuwait';

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper('file');
        $this->load->helper('JWT');
        // $this->load->library('form_validation');
        $this->load->model('model_web_service');
        $this->load->database();
        // $this->load->library('session');
        $this->load->library('image_lib');
        // $this->load->helper('cookie');
        //$this->load->library('email');
        // $this->load->library('pagination');

        //date_default_timezone_set("Asia/Kolkata");
        // session_start();
    }

    public function index()
    {
        // echo "dd";
    }

    public function login()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $key_status = $this->model_web_service->authenticate_key($request);

        if ($key_status) {
            $this->do_login($request);
        } else {
            $finresult[] = array('status' => 'failed', 'message' => 'Secret key miss match', 'code' => 'Login failed',
            );
            print json_encode($finresult);
        }

    }

    function do_login($request)
    {
        $url = $_SERVER['REQUEST_URI'];
        $email1 = explode('email=', $url);
        $email2 = $email1[1];
        $email3 = explode('&', $email2);
        $email = urldecode($email3[0]);

        $username1 = explode('username=', $url);
        $username2 = $username1[1];
        $username3 = explode('&', $username2);
        $username = urldecode($username3[0]);
        $table_time_details='time_detail';
        $table = 'userdetails';
        $select_data = "*";
        $this->db->select($select_data);
        $this->db->where("(email = '$email' OR username = '$email' OR mobile = '$email' )");
        $query = $this->db->get($table);
        $result = $query->result_array();
        $result = $this->model_web_service->login($request);
        $table_cab_details = 'cabdetails';

        $status="SELECT * FROM `userdetails` where email='$email' OR username='$email'";
        $statusrs=mysql_query($status);
        $datastatus=mysql_fetch_array($statusrs);
        $statuschk=$datastatus['user_status'];

        if ($result &&  $statuschk=='Active')
        {
            $this->db->select('*');
            $this->db->from('cabdetails');
            // $this->db->join('Car_Type', 'Car_Type.car_type = cabdetails.cartype');
            $this->db->order_by("cab_id", "desc");
            $query = $this->db->get();

            $result_cab_details= $query->result_array();
            $result_cab_time = $this->model_web_service->get_table('*',  $table_time_details);
            $table_setting_details ='settings';
            $result_setting = $this->model_web_service->get_table('country,currency',  $table_setting_details);
            $finresult['status']='success';
            $finresult['message']='Successfully Logged in';
            $finresult['code']='success';
            $finresult['Isactive']=$statuschk;
            $finresult['time_detail']=$result_cab_time;
            $finresult['country_detail']=$result_setting;
            $finresult['userdetail']=$result;
            $finresult['cabDetails']=$result_cab_details;
            echo json_encode($finresult);
        }
        elseif($statuschk =='Inactive')
        {

            $finresult['status']='false';
            $finresult['message']='Your account has been temporarily locked. Please contact our admin for further details.';
	    $finresult['error code']='1';
            $finresult['Isactive']=$statuschk;
            echo json_encode($finresult);

        }
        else
        {
            $finresult['status']='failed';
            $finresult['message']='Please enter correct login details';
	    $finresult['error code']='2';
            $finresult['code']='Login failed';
            print json_encode($finresult);
        }
    }

    public function social_login()
    {
        //http://192.168.1.2/gagaji/WebApp/Source/web_service/social_login?email=sarju@gmail.com
        $url = $_SERVER['REQUEST_URI'];
        $email1 = explode('email=', $url);
        $email2 = $email1[1];
        $email3 = explode('&', $email2);
        $email = urldecode($email3[0]);

        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $table_cab_details = 'cabdetails';
        $result = $this->model_web_service->social_login($request);
        if ($result) {
            $result_cab_details = $this->model_web_service->get_table('*', $table_cab_details);
            $finresult = array('status' => 'success', 'message' => 'Successfully Logged in', 'code' => 'success',

                'id' => $result['id'],
                'username' => $result['username'],
                'isdevice' => $result['isdevice'],
                //'icon' => $result['icon'],
                'token' => $this->token_gen($result['username']),
                'cabDetails' => $result_cab_details

            );
            print json_encode($finresult);

        } else {

            $this->model_web_service->insert_user_social($request);
            $result_cab_details = $this->model_web_service->get_table('*', $table_cab_details);

            $finresult = array('status' => 'success', 'message' => 'Successfully registered', 'code' => 'registered',
                'token' => $this->token_gen($email)

            );
            if ($finresult != '') {
                $finresult = array('status' => 'success', 'message' => 'Successfully Register in', 'code' => 'success',

//						'id'    		=> $result['id'],
//						'username'	=> $result['username'],
//						'token'			=> $this->token_gen( $result['username'] )
                    'isdevice' => $result['isdevice'],
                    //  'icon' => $result['icon'],
                    'cabDetails' => $result_cab_details

                );
                print json_encode($finresult);
            }

            //print json_encode($finresult);
        }
    }
 public function facebook_login()
    {

        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $key_status = $this->model_web_service->authenticate_key($request);

        if ($key_status) {
            $this->get_facebook_login($request);
        } else {
            $finresult[] = array('status' => 'failed', 'message' => 'Secret key miss match', 'code' => 'Login failed',

            );
            print json_encode($finresult);
        }
    }
 public function get_facebook_login($request)
    {
        //http://localhost/gagaji/WebApp/Source/web_service/facebook_login?facebook_id=123456789

        /*$url = $_SERVER['REQUEST_URI'];
        $facebook_id1 = explode('facebook_id=', $url);
        $facebook_id2 = $facebook_id1[1];
        $facebook_id3 = explode('&', $facebook_id2);
        $facebook_id = urldecode($facebook_id3[0]);*/

        $facebook_id = $this->input->post('facebook_id');
        $result = $this->model_web_service->facebook_login($facebook_id);
    
	    $table_time_details='time_detail';
        $table_cab_details = 'cabdetails';

	    $status="SELECT * FROM `userdetails` where facebook_id='$facebook_id'";
        $statusrs=mysql_query($status);
        $datastatus=mysql_fetch_array($statusrs);
        $statuschk=$datastatus['user_status'];
	
        if ($result && $facebook_id != ''  &&  $statuschk=='Active' ) 
	    {        
	        $this->db->select('*');
            $this->db->from('cabdetails');
            // $this->db->join('Car_Type', 'Car_Type.car_type = cabdetails.cartype');      
            $this->db->order_by("cab_id", "desc");      
            $query = $this->db->get();
            $result_cab_details= $query->result_array();
	       $result_cab_time = $this->model_web_service->get_table('*',  $table_time_details);
            $table_setting_details ='settings';
           $result_setting = $this->model_web_service->get_table('country,currency',  $table_setting_details);
	       $finresult['status']='success';
            $finresult['message']='Successfully Logged in';
            $finresult['code']='success';
	       $finresult['Isactive']=$statuschk;
	       $finresult['time_detail']=$result_cab_time;
           $finresult['country_detail']=$result_setting;
            $finresult['userdetail']=$result;
            $finresult['cabDetails']=$result_cab_details;
            $finresult['facebook_id']=$facebook_id;
			//echo json_encode(array_slice(json_decode($finresult, true), 2));
	       echo json_encode($finresult);
        } 
	   else if($statuschk =='Inactive')
        {
            $finresult['status']='false';
            $finresult['message']='Your account has been temporarily locked. Please contact our admin for further details.';
	    $finresult['error code']='1';
            $finresult['Isactive']=$statuschk;
            $finresult['facebook_id']='';
            echo json_encode($finresult);
        }
	   else if (!$result && $facebook_id != '') {

            $finresult['status']='failed';
            $finresult['message']='Please enter correct login details';
            $finresult['code']='Login failed';
	    $finresult['error code']='2';
            $finresult['facebook_id']='';
            print json_encode($finresult);
        }
    }
   

    public function twitter_login()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $key_status = $this->model_web_service->authenticate_key($request);

        if ($key_status) {
            $this->get_twitter_login($request);
        } else {
            $finresult[] = array('status' => 'failed', 'message' => 'Secret key miss match', 'code' => 'Login failed',

            );
            print json_encode($finresult);
        }
    }
 public function get_twitter_login($request)
    {
        /*$url = $_SERVER['REQUEST_URI'];
        $twitter_id1 = explode('twitter_id=', $url);
        $twitter_id2 = $twitter_id1[1];
        $twitter_id3 = explode('&', $twitter_id2);
        $twitter_id = urldecode($twitter_id3[0]);*/
        
        $twitter_id = $this->input->post('twitter_id');
        $result = $this->model_web_service->twitter_login($twitter_id);
        $sql = "select * from userdetails where twitter_id=$twitter_id";
        $rs = mysql_query($sql);
        $data = mysql_fetch_array($rs);
        $tid = $data['twitter_id'];
        $table_cab_details = 'cabdetails';

        $table = 'userdetails';
        $select_data = "*";
        $where_data = array
        (
            'twitter_id' => $twitter_id,
        );
	$table_time_details='time_detail';
        $result_details = $this->model_web_service->get_table_where($select_data, $where_data, $table);

	$status="SELECT * FROM `userdetails` where twitter_id='$twitter_id'";
        $statusrs=mysql_query($status);
        $datastatus=mysql_fetch_array($statusrs);
        $statuschk=$datastatus['user_status'];
        if ($result && $twitter_id != '' && $tid = $twitter_id &&  $statuschk=='Active') 
	{            
            $this->db->select('*');
        $this->db->from('cabdetails');
        //$this->db->join('Car_Type', 'Car_Type.car_type = cabdetails.cartype');      
        $this->db->order_by("cab_id", "desc");      
        $query = $this->db->get();
        $result_cab_details= $query->result_array();
		$result_cab_time = $this->model_web_service->get_table('*',  $table_time_details);
         $table_setting_details ='settings';
           $result_setting = $this->model_web_service->get_table('country,currency',  $table_setting_details);
	  $finresult['status']='success';
            $finresult['message']='Successfully Logged in';
            $finresult['code']='success';
	    $finresult['Isactive']=$statuschk;
	    $finresult['time_detail']=$result_cab_time;
        $finresult['country_detail']=$result_setting;
            $finresult['userdetail']=$result;
            $finresult['cabDetails']=$result_cab_details;
	echo json_encode($finresult);
            
        } 
	elseif($statuschk =='Inactive')
        {
           $finresult['status']='failed';
            $finresult['message']='Please enter correct login details';
	    $finresult['error code']='5';
            $finresult['code']='Login failed';
            echo json_encode($finresult);
        }
	elseif (!$result && $twitter_id != '' && $tid != $twitter_id) 
	{
           $finresult['status']='failed';
            $finresult['message']='Please enter correct login details';
	    $finresult['error code']='2';
            $finresult['code']='Login failed';
            print json_encode($finresult);
        }
    }

    public function sign_up()
    {

        //$postdata = file_get_contents("php://input");
        $postdata = json_decode(file_get_contents('php://input'), true);
        $request = json_decode($postdata);

        $key_status = $this->model_web_service->authenticate_key($request);

        if ($key_status) {
            $this->do_sign_up($request);
        } else {
            $error_list[] = array(
                'message' => 'Secret key miss match',
                'code' => 'Secret key miss match'
            );
            $finresult = array(
                'status' => 'failed',
                'error_list' => $error_list
            );
            print json_encode($finresult);
        }

    }

function do_sign_up($request)
    	{
        //http://192.168.1.2/gagaji/WebApp/Source/web_service/sign_up?name=test&username=nakul&mobile=9979988788&email=nakul@gmail.com&password=456&isdevice=android        
	$name= $this->input->post('name');
	$username= $this->input->post('username');
    $email= $this->input->post('email');
    $mobile= $this->input->post('mobile');
    $password= $this->input->post('password');       
    $isdevice= $this->input->post('isdevice');
    $gender= $this->input->post('gender');
    $dob= $this->input->post('dob');
	$facebook_id= $this->input->post('facebook_id');
	$twitter_id= $this->input->post('twitter_id');
    $img=$_FILES['image'];

        $mail_status = $this->model_web_service->is_mail_exists($email,$uid="");
        $user_status = $this->model_web_service->is_username_exists($username,$uid="");
        $mobile_status = $this->model_web_service->is_mobile_exists($mobile,$uid="");
        $table_cab_details = 'cabdetails';
	    $table_time_details='time_detail';
        if ($mail_status || $user_status || $mobile_status) //CHECK MAIL ID OR USER NAME EXIST
        {

            //$error_list = array();
            if ($mail_status && $user_status && $mobile_status) 
		      {     
		          $finresult = array(
                'status' => 'failed',
                'message' => 'email address,username and mobile number already exist.',
		'error code'=> '7',
                'code' => 'exists'
		
                );                  
            }
            else if($mail_status && $user_status) 
	           {
               $finresult = array(
                'status' => 'failed',
                'message' => 'email address,username already in exist',
		'error code'=> '8',
                'code' => 'exists'
                );   
            }
            else if ($mobile_status && $mail_status) {
                $finresult = array(
                'status' => 'failed',
                'message' => 'email address,mobile number already in exist',
		'error code'=> '9',
                 'code' => 'exists'
                );           
            }
		else if ($mobile_status && $user_status) {
                $finresult = array(
                'status' => 'failed',
                'message' => 'mobile number,username already in exist',
		'error code'=> '10',
                 'code' => 'exists'
                );    
            }
	     else if ($mail_status) {
                $finresult = array(
                'status' => 'failed',
                'message' => 'Email id already in exist',
		'error code'=> '11',
                 'code' => 'exists'
                );   
            }
	     else if ($user_status) {
                $finresult = array(
                'status' => 'failed',
                'message' => 'Username already in exist',
		'error code'=> '12',
                 'code' => 'exists'
                );
            }    
	    else if ($mobile_status) {
                $finresult = array(
                'status' => 'failed',
                'message' => 'Mobile number already in exist',
		'error code'=> '13',
                 'code' => 'exists'
                );
            }
        else{
            $finresult = array(
                'status' => 'failed',
                'message' => 'Something went wrong',
		'error code'=> '14',
                 'code' => 'error'
                );
        }            
            print json_encode($finresult);
    } 
	else 
	{
        // $finresult = array(
        //         'status' => 'failed',
        //         'message' => 'Something went wrong',
        //          'code' => 'error'
        //         );
        // echo json_encode($finresult);

        if($img !='') 
        {
            $imgflag=$this->upload_image($img,"user_image/","","user");
        }
        else
        {
            $imgflag='';
        }
        
            $driver_register = $this->model_web_service->insert_user_details($username,$email,$mobile,$password,$name,$gender,$dob,$imgflag,$facebook_id,$twitter_id,$isdevice);
            //$this->model_web_service->insert_user_details($request);
        $result_cab_time = $this->model_web_service->get_table('*',  $table_time_details);
            
        $this->db->select('*');
        $this->db->from('cabdetails');
        //$this->db->join('Car_Type', 'Car_Type.car_type = cabdetails.cartype');      
        $this->db->order_by("cab_id", "desc");      
        $query = $this->db->get();
        $result_cab_details= $query->result_array();
        $table = 'userdetails';
        $select_data = "*";
        $this->db->select($select_data);
        $this->db->where("email",$email);
        $query = $this->db->get($table);  //--- Table name = User
        $user_detatil = $query->result_array();
              
        $config = Array(
        'protocol' => 'smtp',
        'smtp_host' => 'ssl://smtp.googlemail.com',
        'smtp_port' => 465,
        'smtp_user' => 'sarju@techintegrity.in',
        'smtp_pass' => '',
        'mailtype'  => 'html', 
        'charset' => 'utf-8',
        'wordwrap' => TRUE
        );
        //$this->email->set_mailtype("html");
        //$this->load->library('email', $config);
        //$this->email->set_newline("\r\n");
        //$email_body =file_get_contents('mailform.php', true);
        //$this->email->from('sarju@techintegrity.in', 'sarju tank');

        //$list = array($email);
        //$this->email->to($list);
        //$this->email->subject('User Register Sucessfully');
       // $this->email->message($email_body);
            /*if ($this->email->send()) {*/
                //echo 'Email sent.';
                //$finresult = array('status' => 'success', 'message' => 'Successfully Signed up', 'Mail' => 'mail Send Sucessfully',
                  //  'user_Detail' => $user_detatil,

                    //'cabDetails' => $result_cab_details
                //);
        $subject = 'Naqilcom New User Registration.';
        $admin_email=$this->model_web_service->checkadminemail();
		$send_email = $admin_email;
        $email_body ='<div style="background-color: #00bcd4; color: #0b0b0b;">
     			<table style="background-color:#292A6B;border:1px solid #20264a;padding:10px;font-family:Verdana;font-size:12px" width="100%"><tbody><tr><td><img class="CToWUd" src="http://138.68.5.43/upload/logo.png" style="min-height:25%;width:25%"></td></tr><tr><td>&nbsp;</td></tr><tr> <td> <table style="padding:10px;font-size:12px;background-color:#fff;border:1px solid #2d62ac" cellpadding="5" width="100%"> <tbody> <tr><td colspan="4">&nbsp;</td></tr> <tr><td colspan="4" style="font-family:Verdana,Geneva,sans-serif;font-size:12px;text-align:left"> Hey, '.$email.'</td></tr> <tr><td colspan="4" style="font-family:Verdana,Geneva,sans-serif;font-size:12px;text-align:left"> <br><br> Your Email_id: '.$email.' and new Your Password: '.$password.' for Naqil are as below. </td></tr> <tr> <td colspan="4"> <table style="font-family:Verdana,Geneva,sans-serif;font-size:12px;width:600px;border-collapse:collapse" height="30"> <tbody> <tr> <th style="border:1px solid #808080!important;font-size:1.1em;text-align:left;padding-top:5px;padding-left:10px;padding-bottom:5px;background-color:#cdcdcd!important;color:#000000!important" width="20%">Email</th> <th style="border:1px solid #808080!important;font-size:1.1em;text-align:left;padding-top:5px;padding-left:10px;padding-bottom:5px;background-color:#cdcdcd!important;color:#000000!important" width="35%">'.$email.'</th> </tr> <tr> <th style="border:1px solid #808080!important;font-size:1.1em;text-align:left;padding-top:5px;padding-left:10px;padding-bottom:5px;background-color:#cdcdcd!important;color:#000000!important" width="20%">Password</th> <th style="border:1px solid #808080!important;font-size:1.1em;text-align:left;padding-top:5px;padding-left:10px;padding-bottom:5px;background-color:#cdcdcd!important;color:#000000!important" width="35%"> '.$password.'</th> </tr> <tr><td colspan="4" style="font-family:Verdana,Geneva,sans-serif;font-size:12px;text-align:left"></td></tr> <tr><td colspan="4" style="font-family:Verdana,Geneva,sans-serif;font-size:12px;text-align:left"><br><br> If you have any questions please contact <a href="mailto:pollvaultadm@gmail.com" target="_blank">sarju@techintegrity.in</a>. <br><br><br>Thanks. </td></tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody></table>
     			</div>';        
        $response=$this->send_mail($subject,$send_email,$email_body);        
        $table_setting_details ='settings';        
        $result_setting = $this->model_web_service->get_table('country,currency',  $table_setting_details);  
        $finresult['status']='success';
        $finresult['message']='Successfully Signed up';
        $finresult['Mail']='mail Send Sucessfully';
        $finresult['country_detail']=$result_setting;
        $finresult['user_Detail']=$user_detatil;
        $finresult['time_detail']=$result_cab_time;
        $finresult['cabDetails']=$result_cab_details;
               // print json_encode($finresult);
        echo json_encode($finresult);
            /*} else {
                //show_error($this->email->print_debugger());
            }*/
        }
    }

    public function fetch_cab_details()
    {
        //http://192.168.1.2/gagaji/WebApp/Source/web_service/fetch_cab_details?transfertype=Outstation%20Transfer&timetype=day
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $myDate = new DateTime();
        //$myDate->setTimestamp(strtotime($request->book_date));
        $myDate->setTimestamp(strtotime('07/07/2007'));

        $time = $myDate->format("H");

        if ($time >= 22 || $time <= 6) {
            $timetype = 'night';
        } else {
            $timetype = 'day';
        }

        //$request->timetype	= $timetype;
        //$request->timetype	= $timetype;
        //$timetype = 'night';

        $result = $this->model_web_service->fetch_cabs($request);

        $finresult = array(
            'status' => 'success',
            'cabs' => $result
        );
        print json_encode($finresult);

    }
    public function trip_detail()
    {
        //http://localhost/gagaji/WebApp/Source/web_service/trip_detail?booking_id=CMC1447321810
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $result=$this->model_web_service->get_trip_detail($request);
	
	if( $result)
	{
	  $result_detail['status']='success';
	  $result_detail['trip_detail'] = $result;
	}
	else
	{
	  $result_detail['status']='failed';
	  $result_detail['error code']='15';
          $result_detail['trip_detail'] = 'Data Not Founds';
	}

        print json_encode($result_detail);
    }
    public  function  cancel_trip()
    {
        //http://localhost/gagaji/WebApp/Source/web_service/cancel_trip?booking_id=CMC1447321810
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        // $data='uneaque_id'='CMC1447321810';
        $select_data = "*";

        $where_data = array(    // ----------------Array for check data exist ot not
            'uneaque_id' => 'CMC1447321810'
        );

        $table = "bookingdetails";
        $result_cab_details = $this->model_web_service->get_table_where($select_data,$where_data,$table);
        $result=$this->model_web_service->get_cancel_trip($request);
        $result_detail=array(
            'status' => 'success',
            'trip_detail' => $result_cab_details
        );

        print json_encode($result_detail);

    }
    public function  driver_accept_trip()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
	
        $url = $_SERVER['REQUEST_URI'];

        $booking_id1=explode('booking_id=',$url);
        $booking_id2=$booking_id1[1];
        $booking_id3=explode('&',$booking_id2);
        $booking_id=urldecode($booking_id3[0]);

        $driver_id1=explode('driver_id=',$url);
        $driver_id2=$driver_id1[1];
        $driver_id3=explode('&',$driver_id2);
        $driver_id=urldecode($driver_id3[0]);

        // $data='uneaque_id'='CMC1447321810';
        $select_data = "*";

        $where_data = array(    // ----------------Array for check data exist ot not
            //'uneaque_id' => 'CMC1447321810'
	    'id' =>  $booking_id
        );



        $status="SELECT * FROM driver_details WHERE id='$driver_id'";
        $statusrs=mysql_query($status);
        $datastatus=mysql_fetch_array($statusrs);
        $statuschk=$datastatus['status'];
        if($statuschk=='Active')
        {
            $table = "bookingdetails";
            $result = $this->model_web_service->get_accept_trip($request);
            $result_cab_details = $this->model_web_service->get_table_where($select_data, $where_data, $table);
            $result_detail['status'] = 'success';
            $result_detail['Isactive'] = $statuschk;
            $result_detail['trip_detail'] = $result_cab_details;
            $find_user_id=$this->model_web_service->findpushuser($booking_id);
            $uid='u_'.$find_user_id;
            $description=sprintf("Your Booking ID : %s is been assigned to Driver Mr. ".$datastatus['name']." and is been reaching to your location soon.",$booking_id);
            //---- Notification Start ---------//
            $urlNotification = "162.243.225.225:8001/send";
                $data_json= sprintf('{
                    "users": ["%s"],  
                    "android": {
                        "collapseKey": "optional",
                        "data": {
                            "message": "%s"
                            }
                        },
                        "ios": {
                            "badge": 0,
                            "alert": "%s",
                            "sound": "soundName"
                        }
                    }',$uid,$description,$description);                                     
                    //print_r($data_json);
                    $ch1 = curl_init();
                    curl_setopt($ch1, CURLOPT_URL, $urlNotification);
                    curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                    curl_setopt($ch1, CURLOPT_POST, 1);
                    curl_setopt($ch1, CURLOPT_POSTFIELDS,$data_json);
                    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
                    $notification_response  = curl_exec($ch1);
                    //print_r($response );
                    //---- Notification End ---------//

            print json_encode($result_detail);
        }
        elseif($statuschk =='Inactive')
        {
            $finresult['status']='false';
            $finresult['message']='Your account has been temporarily locked. Please contact our admin for further details.';
	    $finresult['error code']='1';
            $finresult['Isactive']=$statuschk;
            echo json_encode($finresult);
        }
    }
    public function driver_status()
   {
	$select_data = "*";
	 $table = "Driver_status";
	$result_status_details = $this->model_web_service->get_table($select_data,$table);
	if($result_status_details)
	{
	    $result_detail['status']='success';
	    $result_detail['driver_status']=$result_status_details;
	}
	else
	{
           $result_detail['status']='failed';
	   $result_detail['error code']='16';
	    $result_detail['trip_detail']='No data founds';
	}
	 print json_encode($result_detail);
   }
   public function driver_status_update()
  {
	 $url = $_SERVER['REQUEST_URI'];
        $did1=explode('did=',$url);
        $did2=$did1[1];
        $did3=explode('&',$did2);
        $did=urldecode($did3[0]);

	$sid1=explode('sid=',$url);
        $sid2=$sid1[1];
        $sid3=explode('&',$sid2);
        $sid=urldecode($sid3[0]);
  	$table = 'driver_details';

        $update_data = array(
            'driver_status_id' => $sid
        );

        $where_data = array(
            'id' => $did,
        );

       $result= $this->model_web_service->update_table_where($update_data, $where_data, $table);
	if($result)
	{
	 $select_data = "*";

        $where_data = array(    // ----------------Array for check data exist ot not
            //'uneaque_id' => 'CMC1447321810'
	    'id' => $did
        );

        $table = "driver_details";
        $result_driver_details = $this->model_web_service->get_table_where($select_data,$where_data,$table);
	$result_detail['status']='success';
	$result_detail['Driver_detail']= $result_driver_details;
	}
	else
	{
	$result_detail['status']='failed';
	$result_detail['error code']='2';
	$result_detail['Driver_detail']= 'Please enter correct login details';
	}
        print json_encode($result_detail);
  }
    public function driver_reject_trip()
    {
        //http://v1technology.co.uk/demo/naqil/naqilcom/Source/web_service/driver_reject_trip?booking_id=778
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
	    $url = $_SERVER['REQUEST_URI'];
        $booking_id1=explode('booking_id=',$url);
        $booking_id2=$booking_id1[1];
        $booking_id3=explode('&',$booking_id2);
        $booking_id=urldecode($booking_id3[0]);

        $driver_id1=explode('driver_id=',$url);
        $driver_id2=$driver_id1[1];
        $driver_id3=explode('&',$driver_id2);
        $driver_id=urldecode($driver_id3[0]);

        // $data='uneaque_id'='CMC1447321810';
        $select_data = "*";

        $where_data = array(    // ----------------Array for check data exist ot not
            //'uneaque_id' => 'CMC1447321810'
	    'id' => $booking_id
        );

        $status="SELECT * FROM driver_details WHERE id='$driver_id'";
        $statusrs=mysql_query($status);
        $datastatus=mysql_fetch_array($statusrs);
        $statuschk=$datastatus['status'];
        if($statuschk=='Active')
        {
            $table = "bookingdetails";

            $result = $this->model_web_service->get_reject_trip($request);
            $result_cab_details = $this->model_web_service->get_table_where($select_data, $where_data, $table);
            $result_detail['status'] = 'success';
            $result_detail['Isactive'] = $statuschk;
            $result_detail['trip_detail'] = $result_cab_details;
            print json_encode($result_detail);
            $this->fetch_booking_cronjob();
        }
        elseif($statuschk =='Inactive')
        {
            $finresult['status']='false';
            $finresult['message']='Your account has been temporarily locked. Please contact our admin for further details.';
	    $finresult['error code']='1';
            $finresult['Isactive']=$statuschk;
            echo json_encode($finresult);
        }
    }
	 public function driver_arrived_trip()
    {
        //http://v1technology.co.uk/demo/naqil/naqilcom/Source/web_service/driver_reject_trip?booking_id=778
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $url = $_SERVER['REQUEST_URI'];
        $booking_id1=explode('booking_id=',$url);
        $booking_id2=$booking_id1[1];
        $booking_id3=explode('&',$booking_id2);
        $booking_id=urldecode($booking_id3[0]);

        $driver_id1=explode('driver_id=',$url);
        $driver_id2=$driver_id1[1];
        $driver_id3=explode('&',$driver_id2);
        $driver_id=urldecode($driver_id3[0]);

        // $data='uneaque_id'='CMC1447321810';
        $select_data = "*";

        $where_data = array(    // ----------------Array for check data exist ot not
            //'uneaque_id' => 'CMC1447321810'
            'id' => $booking_id
        );

        $status="SELECT * FROM driver_details WHERE id='$driver_id'";
        $statusrs=mysql_query($status);
        $datastatus=mysql_fetch_array($statusrs);
        $statuschk=$datastatus['status'];
        if($statuschk=='Active')
        {
            $table = "bookingdetails";

            $result = $this->model_web_service->get_arrived_trip($request);
            $result_cab_details = $this->model_web_service->get_table_where($select_data, $where_data, $table);
            $result_detail['status'] = 'success';
            $result_detail['Isactive'] = $statuschk;
            $result_detail['trip_detail'] = $result_cab_details;

            $find_user_id=$this->model_web_service->findpushuser($booking_id);
            $uid='u_'.$find_user_id;
            $description=sprintf("Mr.".$datastatus['name']." has reached pickup point.");
		    //---- Notification Start ---------//
		    $urlNotification = "162.243.225.225:8001/send";
				$data_json= sprintf('{
					"users": ["%s"],  
					"android": {
						"collapseKey": "optional",
						"data": {
							"message": "%s"
							}
						},
						"ios": {
							"badge": 0,
							"alert": "%s",
							"sound": "soundName"
						}
					}',$uid,$description,$description);								        
					//print_r($data_json);
					$ch1 = curl_init();
					curl_setopt($ch1, CURLOPT_URL, $urlNotification);
					curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
					curl_setopt($ch1, CURLOPT_POST, 1);
					curl_setopt($ch1, CURLOPT_POSTFIELDS,$data_json);
					curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
					$notification_response  = curl_exec($ch1);
					//print_r($response );
					//---- Notification End ---------//

            print json_encode($result_detail);
        }
        elseif($statuschk =='Inactive')
        {
            $finresult['status']='false';
            $finresult['message']='Your account has been temporarily locked. Please contact our admin for further details.';
	    $finresult['error code']='1';
            $finresult['Isactive']=$statuschk;
            echo json_encode($finresult);
        }
    }
    public function driver_on_trip()
    {
        //http://v1technology.co.uk/demo/naqil/naqilcom/Source/web_service/driver_reject_trip?booking_id=778
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $url = $_SERVER['REQUEST_URI'];
        $booking_id1=explode('booking_id=',$url);
        $booking_id2=$booking_id1[1];
        $booking_id3=explode('&',$booking_id2);
        $booking_id=urldecode($booking_id3[0]);

        $driver_id1=explode('driver_id=',$url);
        $driver_id2=$driver_id1[1];
        $driver_id3=explode('&',$driver_id2);
        $driver_id=urldecode($driver_id3[0]);

        // $data='uneaque_id'='CMC1447321810';
        $select_data = "*";

        $where_data = array(    // ----------------Array for check data exist ot not
            //'uneaque_id' => 'CMC1447321810'
            'id' => $booking_id
        );

        $status="SELECT * FROM driver_details WHERE id='$driver_id'";
        $statusrs=mysql_query($status);
        $datastatus=mysql_fetch_array($statusrs);
        $statuschk=$datastatus['status'];
        if($statuschk=='Active')
        {
            $table = "bookingdetails";

            $result = $this->model_web_service->get_on_trip($request);
            $result_cab_details = $this->model_web_service->get_table_where($select_data, $where_data, $table);
            $result_detail['status'] = 'success';
            $result_detail['Isactive'] = $statuschk;
            $result_detail['trip_detail'] = $result_cab_details;
            $find_user_id=$this->model_web_service->findpushuser($booking_id);
            $uid='u_'.$find_user_id;
            $description=sprintf("Welcome Abroad. Your Trip with booking id %s has began. We wish you safe journey.",$booking_id);
		    //---- Notification Start ---------//
		    $urlNotification = "162.243.225.225:8001/send";
				$data_json= sprintf('{
					"users": ["%s"],  
					"android": {
						"collapseKey": "optional",
						"data": {
							"message": "%s"
							}
						},
						"ios": {
							"badge": 0,
							"alert": "%s",
							"sound": "soundName"
						}
					}',$uid,$description,$description);								        
					//print_r($data_json);
					$ch1 = curl_init();
					curl_setopt($ch1, CURLOPT_URL, $urlNotification);
					curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
					curl_setopt($ch1, CURLOPT_POST, 1);
					curl_setopt($ch1, CURLOPT_POSTFIELDS,$data_json);
					curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
					$notification_response  = curl_exec($ch1);
					//print_r($response );
					//---- Notification End ---------//
            print json_encode($result_detail);
        }
        elseif($statuschk =='Inactive')
        {
            $finresult['status']='false';
            $finresult['message']='Your account has been temporarily locked. Please contact our admin for further details.';
	    $finresult['error code']='1';
            $finresult['Isactive']=$statuschk;
            echo json_encode($finresult);
        }
    }
    public function driver_completed_trip()
    {
        //http://v1technology.co.uk/demo/naqil/naqilcom/Source/web_service/driver_reject_trip?booking_id=778
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $url = $_SERVER['REQUEST_URI'];
        $booking_id1=explode('booking_id=',$url);
        $booking_id2=$booking_id1[1];
        $booking_id3=explode('&',$booking_id2);
        $booking_id=urldecode($booking_id3[0]);

        $driver_id1=explode('driver_id=',$url);
        $driver_id2=$driver_id1[1];
        $driver_id3=explode('&',$driver_id2);
        $driver_id=urldecode($driver_id3[0]);

        $final_amt1=explode('final_amount=',$url);
        $final_amt2=$final_amt1[1];
        $final_amt3=explode('&',$final_amt2);
        $final_amt=urldecode($final_amt3[0]);

        $reason1=explode('delay_reason=',$url);
        $reason2=$reason1[1];
        $reason3=explode('&',$reason2);
        $reason=urldecode($reason3[0]);
        // $data='uneaque_id'='CMC1447321810';
        $select_data = "*";

        $where_data = array(    // ----------------Array for check data exist ot not
            //'uneaque_id' => 'CMC1447321810'
            'id' => $booking_id
        );

        $status="SELECT * FROM driver_details WHERE id='$driver_id'";
        $statusrs=mysql_query($status);
        $datastatus=mysql_fetch_array($statusrs);
        $statuschk=$datastatus['status'];
        if($statuschk=='Active')
        {
            $table = "bookingdetails";
            $result = $this->model_web_service->get_completed_trip($request);
            $result_trip_details = $this->model_web_service->get_table_where($select_data, $where_data, $table);
            $result_detail['status'] = 'success';
            $result_detail['Isactive'] = $statuschk;
            $result_detail['trip_detail'] = $result_trip_details;
            $find_user_id=$this->model_web_service->findpushuser($booking_id);
            $uid='u_'.$find_user_id;
            $description=sprintf("Your Booking ID: %s has been completed with Mr. %s. We hope to serve your soon in the future.",$booking_id,$datastatus['name']);
            //---- Notification Start ---------//
            $urlNotification = "162.243.225.225:8001/send";
                $data_json= sprintf('{
                    "users": ["%s"],  
                    "android": {
                        "collapseKey": "optional",
                        "data": {
                            "message": "%s"
                            }
                        },
                        "ios": {
                            "badge": 0,
                            "alert": "%s",
                            "sound": "soundName"
                        }
                    }',$uid,$description,$description);                                     
                    //print_r($data_json);
                    $ch1 = curl_init();
                    curl_setopt($ch1, CURLOPT_URL, $urlNotification);
                    curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                    curl_setopt($ch1, CURLOPT_POST, 1);
                    curl_setopt($ch1, CURLOPT_POSTFIELDS,$data_json);
                    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
                    $notification_response  = curl_exec($ch1);
                    //print_r($response );
                    //---- Notification End ---------//
            print json_encode($result_detail);
        }
        else if($statuschk =='Inactive')
        {
            $finresult['status']='false';
            $finresult['message']='Your account has been temporarily locked. Please contact our admin for further details.';
	    $finresult['error code']='1';
            $finresult['Isactive']=$statuschk;
            echo json_encode($finresult);
        }
    }
	public function driver_unavailable_cancelled_book()
    {
        //http://v1technology.co.uk/demo/naqil/naqilcom/Source/web_service/driver_reject_trip?booking_id=778
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
	 $url = $_SERVER['REQUEST_URI'];
        $booking_id1=explode('booking_id=',$url);
        $booking_id2=$booking_id1[1];
        $booking_id3=explode('&',$booking_id2);
        $booking_id=urldecode($booking_id3[0]);
        // $data='uneaque_id'='CMC1447321810';
        $select_data = "*";

        $where_data = array(    // ----------------Array for check data exist ot not
            //'uneaque_id' => 'CMC1447321810'
	    'id' => $booking_id
        );

        $table = "bookingdetails";
        
        $result=$this->model_web_service->driver_unavailable_cancelled_book($request);
	$result_cab_details = $this->model_web_service->get_table_where($select_data,$where_data,$table);
        $result_detail['status']='success';
	$result_detail['trip_detail']=$result_cab_details;
        print json_encode($result_detail);
    }
	
	public function user_reject_trip()
    {
        //http://v1technology.co.uk/demo/naqil/naqilcom/Source/web_service/driver_reject_trip?booking_id=778
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
	     $url = $_SERVER['REQUEST_URI'];
        $booking_id1=explode('booking_id=',$url);
        $booking_id2=$booking_id1[1];
        $booking_id3=explode('&',$booking_id2);
        $booking_id=urldecode($booking_id3[0]);

        $uid1=explode('uid=',$url);
        $uid2=$uid1[1];
        $uid3=explode('&',$uid2);
        $uid=urldecode($uid3[0]);
        // $data='uneaque_id'='CMC1447321810';
        $select_data = "*";

        $where_data = array(    // ----------------Array for check data exist ot not
            //'uneaque_id' => 'CMC1447321810'
	    'id' => $booking_id
        );

        $table = "bookingdetails";
       

        $status="SELECT * FROM `userdetails` where id='$uid'";
        $statusrs=mysql_query($status);
        $datastatus=mysql_fetch_array($statusrs);
        $statuschk=$datastatus['user_status'];
        if($statuschk =='Active')
        {
            $result=$this->model_web_service->user_reject_trip($request);
            $result_cab_details = $this->model_web_service->get_table_where($select_data,$where_data,$table);

            $result_detail['status']='success';
            $result_detail['Isactive']=$statuschk;
            $result_detail['trip_detail']=$result_cab_details;

            $bookstatus=$this->model_web_service->getbookstatus($booking_id);
            if($bookstatus==4)
            {
                $this->model_web_service->updateimmidiatedriverstatus($booking_id);
            }
            echo json_encode($result_detail);
        }
        elseif($statuschk =='Inactive')
        {
            $finresult['status']='false';
            $finresult['message']='Your account has been temporarily locked. Please contact our admin for further details.';
	    $finresult['error code']='1';
            $finresult['Isactive']=$statuschk;
            echo json_encode($finresult);
        }

    }
	public function load_trips()
    {
        //http://v1technology.co.uk/demo/naqil/naqilcom/Source/web_service/load_trips?user_id=223&off=
        //$postdata = file_get_contents("php://input");
        //$request = json_decode($postdata);

        $myDate = new DateTime();

        $current_date = $myDate->format("m/d/Y");
        /*$url = $_SERVER['REQUEST_URI'];
        $id1 = explode('user_id=', $url);
        $id2 = $id1[1];
        $id3 = explode('&', $id2);
        $id = urldecode($id3[0]);

        $off1 = explode('off=', $url);
        $off2 = $off1[1];
        $off3 = explode('&', $off2);
        $off = urldecode($off3[0]);*/
        $id = $this->input->post('user_id');
        $off = $this->input->post('off');

        $status="SELECT * FROM `userdetails` where id=$id";
        $statusrs=mysql_query($status);
    $datastatus=mysql_fetch_array($statusrs);
    $statuschk=$datastatus['user_status'];

        $lim = 10;
        if ($off == '' || $off == '0')
        {
            $off = 0;
        }
        $perpageTmp = $off;
        $perpage = '';
        if ($perpageTmp != '')
        {
            $perpage = $perpageTmp;
        }
        else
        {
            $perpage = 10;
        }



        $sql="SELECT * FROM `bookingdetails` b INNER JOIN cabdetails c on b.taxi_type=c.cartype WHERE b.user_id=$id ORDER by b.id DESC LIMIT $off,$lim";
        $qry=mysql_query($sql);
        $num = mysql_num_rows($qry);
        $new_off = $off + $num;

            if (mysql_num_rows($qry) > 0) {
                $succArr = 1;
                $i = 0;
                while ($row = mysql_fetch_array($qry))
                {
                    $fields[$i][id] = $row['id'];
                    $fields[$i][username] = $row['username'];
                    $fields[$i][user_id] = $row['user_id'];
                    $fields[$i][pickup_date] = $row['pickup_date'];
                    $fields[$i][pickup_area] = $row['pickup_area'];
                    $fields[$i][drop_area] = $row['drop_area'];
                    $fields[$i][pickup_time] = $row['pickup_time'];
                    $fields[$i][pickup_date_time] = $row['pickup_date_time'];
                    $fields[$i][area] = $row['area'];
                    $fields[$i][landmark] = $row['landmark'];
                    $fields[$i][pickup_address] = $row['pickup_address'];
                    $fields[$i][taxi_type] = $row['taxi_type'];
                    $fields[$i][taxi_id] = $row['taxi_id'];
                    $fields[$i][departure_time] = $row['departure_time'];
                    $fields[$i][departure_date] = $row['departure_date'];
                    $fields[$i][return_date] = $row['return_date'];
                    $fields[$i][flight_number] = $row['flight_number'];
                    $fields[$i][package] = $row['package'];
                    $fields[$i][status] = $row['status'];
                    $fields[$i][promo_code] = $row['promo_code'];
                    $fields[$i][payment_type] = $row['payment_type'];
                    $fields[$i][book_create_date_time] = $row['book_create_date_time'];
                    $fields[$i][create_date_time] = $row['create_date_time'];
                    $fields[$i][distance] = $row['distance'];
                    $fields[$i][isdevice] = $row['isdevice'];
                    $fields[$i][approx_time] = $row['approx_time'];
                    if($row['status']==9)
                    {
                        $fields[$i][amount] = $row['final_amount'];
                    }
                    else
                    {
                        $fields[$i][amount] = $row['amount'];
                    }
                    $fields[$i][address] = $row['address'];
                    $fields[$i][transfer] = $row['transfer'];
                    $fields[$i][assigned_for] = $row['assigned_for'];
                    $fields[$i][item_status] = $row['item_status'];
                    $fields[$i][transaction] = $row['transaction'];
                    $fields[$i][km] = $row['km'];
                    $fields[$i][timetype] = $row['timetype'];
                    $fields[$i][comment] = $row['comment'];
                    $fields[$i][driver_status] = $row['driver_status'];
                    $fields[$i][pickup_lat] = $row['pickup_lat'];
                    $fields[$i][pickup_longs] = $row['pickup_long'];
                    $fields[$i][drop_lat] = $row['drop_lat'];
                    $fields[$i][drop_longs] = $row['drop_long'];
                    $fields[$i][flag] = $row['flag'];
                    $fields[$i][car_id] = $row['id'];
                    $fields[$i][car_type] = $row['cartype'];
                    $fields[$i][icon] = $row['icon'];
                    $fields[$i][seat_capacity] = $row['seat_capacity'];
                    $dri_id= $row['assigned_for'];
                    $sql1="SELECT * from driver_details WHERE id=$dri_id";
                    $qry1=mysql_query($sql1);
                    $rows=mysql_num_rows($qry1);
                    if($rows !=0) {
                        while ($data = mysql_fetch_array($qry1)) {
                            $fields[$i]['driver_detail'] = array(
                                'id' => (int)$data['id'],
                                'name' => $data['name'],
                                'user_name' => $data['user_name'],
                                'phone' => $data['phone'],
                                'address' => $data['address'],
                                'email' => $data['email'],
                                'license_no' => $data['license_no'],
                                'car_type' => $data['car_type'],
                                'car_no' => $data['car_no'],
                                'gender' => $data['gender'],
                                'dob' => $data['dob'],
                                'wallet_amount' => $data['wallet_amount'],
                                'Lieasence_Expiry_Date' => $data['Lieasence_Expiry_Date'],
                                'license_plate' => $data['license_plate'],
                                'Insurance' => $data['license_plate'],
                                'Car_Model' => $data['Car_Model'],
                                'Car_Make' => $data['Car_Make'],
                                'image' => $data['image']

                            );

                        }
                    }
                    else
                    {
                        $fields[$i]['driver_detail'] = null;
                    }

                    $i++;
                }
            }
        if($num!=0 && $statuschk=='Active')
        {
            $this->db->select('');
            $this->db->from('bookingdetails');
            $this->db->join('cabdetails', 'cabdetails.cartype = bookingdetails.taxi_type');
            $this->db->where('bookingdetails.user_id', $id);
            $query = $this->db->get();
            $rowcount = $query->num_rows();

            $finresult['status']='success';
            $finresult['offset']=$new_off;
            $finresult['Count']=$rowcount;
	       	$finresult['Isactive']=$statuschk;
            $finresult['all_trip']=$fields;

            echo json_encode($finresult);

            //'booking' => $booking,
            //'Cancelled' => $Cancelled,
            //'Completed' => $success,
        }
	   	else if($statuschk =='Inactive')
        {
            $finresult['status']='false';
            $finresult['message']='Your account has been temporarily locked. Please contact our admin for further details.';
	    $finresult['error code']='1';
            $finresult['Isactive']=$statuschk;
            echo json_encode($finresult);
        }
        else
        {
            $finresult['status']='failed';
            $finresult['message']='Data Not Found';
	    $finresult['error code']='15';
            echo json_encode($finresult);

        }


        }
     public function load_trips21july2016()
    {
        //http://v1technology.co.uk/demo/naqil/naqilcom/Source/web_service/load_trips?user_id=223&off=
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $myDate = new DateTime();

        $current_date = $myDate->format("m/d/Y");
	 $url = $_SERVER['REQUEST_URI'];
        $id1 = explode('user_id=', $url);
        $id2 = $id1[1];
        $id3 = explode('&', $id2);
        $id = urldecode($id3[0]);

        $off1 = explode('off=', $url);
        $off2 = $off1[1];
        $off3 = explode('&', $off2);
        $off = urldecode($off3[0]);

        $lim = 10;
        if ($off == '' || $off == '0')
        {
            $off = 0;
        }
        //$result = $this->model_web_service->load_trips($request);
	$this->db->select('*');
        $this->db->from('bookingdetails');
        $this->db->join('cabdetails', 'cabdetails.cartype = bookingdetails.taxi_type');
	//$this->db->join('driver_details', 'driver_details.id = bookingdetails.assigned_for' OR 'driver_details', 'driver_details.id != bookingdetails.assigned_for');	
        $this->db->where('bookingdetails.user_id', $id);
        $this->db->order_by("bookingdetails.id", "desc");
        $this->db->limit($lim,$off);
        $query = $this->db->get();

        $result = $query->result_array();
 $result = $query->result_array();

        $perpageTmp = $off;
        $perpage = '';
        if ($perpageTmp != '') 
	{
            $perpage = $perpageTmp;
        } 
	else 
	{
            $perpage = 10;
        }

        $num = $query->num_rows();

        $new_off = $off + $num;
	
        $success = array();
        $booking = array();
        $Cancelled = array();
        foreach ($result as $item) {
            if ($item['status'] == '4') {
                $success[] = $item;
            }
            else if ($item['status'] == '1') {
                $booking[] = $item;
            }
            else if ($item['status'] == '2') {
                $booking[] = $item;
            }
            else if ($item['status'] == '3') {
                $Cancelled[] = $item;
            }
        }
	//$this->db->select('d.*,b.id as b_id ');
	//$this->db->from('bookingdetails');
	//$this->db->join('driver_details', 'driver_details.id = bookingdetails.assigned_for');	
	//$this->db->where('bookingdetails.user_id', $id);
	//$this->db->order_by("bookingdetails.id", "desc");
	//$this->db->limit($lim,$off);
	//$query1 = $this->db->get();
	//$driver_result = $query1->result_array();
	$sql="SELECT * FROM bookingdetails b INNER JOIN driver_details d on d.id=b.assigned_for WHERE b.id=227 ORDER by b.id DESC";
	$qry=mysql_query($sql);
	$driver=[];
	if (mysql_num_rows($qry) != 0) 
	{
        while ($rowImg = mysql_fetch_array($qry)) 
	{
           array_push($driver, $rowImg['user_name']);           
        }
    }
    else 
    {
        $driver=='';
    }           

        if($result)
        {
	$this->db->select('');
        $this->db->from('bookingdetails');
        $this->db->join('cabdetails', 'cabdetails.cartype = bookingdetails.taxi_type');
        $this->db->where('bookingdetails.user_id', $id);
	$query = $this->db->get();
	$rowcount = $query->num_rows();

            $finresult['status']='success';
	    $finresult['offset']=$new_off;
	    $finresult['Count']=$rowcount;
	    $finresult['assigned_driver']=$driver;
            $finresult['all_trip']=$result;

            //'booking' => $booking,
            //'Cancelled' => $Cancelled,
            //'Completed' => $success,
        }
        else
        {
            $finresult['status']='failed';	    
            $finresult['all_trip']='Data Not Found';
	   $finresult['error code']='15';

        }
        print json_encode($finresult);

    }
public  function  filter_book()
    {
        //http://v1technology.co.uk/demo/naqil/naqilcom/Source/web_service/load_trips?user_id=223&off=
        /*$postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $myDate = new DateTime();

        $url = $_SERVER['REQUEST_URI'];
        $filter1 = explode('filter=', $url);
        $filter2 = $filter1[1];
        $filter3 = explode('&', $filter2);
        $filter = urldecode($filter3[0]);
        //echo $comma_separated = "('" . implode("','", $filter) . "')";
        $search= explode(',',$filter);
        $search1=$search[0];
        $search2=$search[1];
        $search3=$search[2];
        $search4=$search[3];

        $id1 = explode('user_id=', $url);
        $id2 = $id1[1];
        $id3 = explode('&', $id2);
        $id = urldecode($id3[0]);


        $off1 = explode('off=', $url);
        $off2 = $off1[1];
        $off3 = explode('&', $off2);
        $off = urldecode($off3[0]);*/

        $filter = $this->input->post('filter');
        $search= explode(',',$filter);
        $search1=$search[0];
        $search2=$search[1];
        $search3=$search[2];
        $search4=$search[3];
        $id = $this->input->post('user_id');
        $off = $this->input->post('off');

        $lim = 10;
        if ($off == '' || $off == '0')
        {
            $off = 0;
        }
        $perpageTmp = $off;
        $perpage = '';
        if ($perpageTmp != '')
        {
            $perpage = $perpageTmp;
        }
        else
        {
            $perpage = 10;
        }
        if($search1 !='' && $search2 !='' && $search3 !='' && $search4 !='')
        {
            $sql="SELECT * FROM `bookingdetails` b INNER JOIN cabdetails c on b.taxi_type=c.cartype WHERE b.user_id=$id AND status in ($search1,$search2,$search3,$search4) ORDER by b.id";

           // $this->db->where("status in ($search1,$search2,$search3,$search4)");
        }
        elseif($search1 !='' && $search2 !='' && $search3 !='')
        {
            $sql="SELECT * FROM `bookingdetails` b INNER JOIN cabdetails c on b.taxi_type=c.cartype WHERE b.user_id=$id AND status in ($search1,$search2,$search3) ORDER by b.id";

            //$this->db->where("status in ($search1,$search2,$search3)");
        }
        elseif($search1 !='' && $search2 !='')
        {
            $sql="SELECT * FROM `bookingdetails` b INNER JOIN cabdetails c on b.taxi_type=c.cartype WHERE b.user_id=$id AND status in ($search1,$search2) ORDER by b.id";
            //$this->db->where("status in ($search1,$search2)");
        }
        elseif($search1 !='')
        {
            $sql="SELECT * FROM `bookingdetails` b INNER JOIN cabdetails c on b.taxi_type=c.cartype WHERE b.user_id=$id AND status in ($search1) ORDER by b.id";
           // $this->db->where("status in ($search1)");
        }
        else
        {
            $sql="SELECT * FROM `bookingdetails` b INNER JOIN cabdetails c on b.taxi_type=c.cartype WHERE b.user_id=$id  ORDER by b.id";
        }

        $qry=mysql_query($sql);


        $num = mysql_num_rows($qry);

        $new_off = $off + $num;

        if (mysql_num_rows($qry) > 0) {
            $succArr = 1;
            $i = 0;
            $sql .= " DESC LIMIT $off,$lim";
            $qry =mysql_query($sql);
            $new_count=mysql_num_rows($qry);
            $new_off = $off + $new_count;
            while ($row = mysql_fetch_array($qry))
            {
                $fields[$i][id] = $row['id'];
                $fields[$i][username] = $row['username'];
                $fields[$i][user_id] = $row['user_id'];
                $fields[$i][pickup_date] = $row['pickup_date'];
                $fields[$i][pickup_area] = $row['pickup_area'];
                $fields[$i][drop_area] = $row['drop_area'];
                $fields[$i][pickup_time] = $row['pickup_time'];
                $fields[$i][pickup_date_time] = $row['pickup_date_time'];
                $fields[$i][area] = $row['area'];
                $fields[$i][landmark] = $row['landmark'];
                $fields[$i][pickup_address] = $row['pickup_address'];
                $fields[$i][taxi_id] = $row['taxi_id'];
                $fields[$i][taxi_type] = $row['taxi_type'];
                $fields[$i][departure_time] = $row['departure_time'];
                $fields[$i][departure_date] = $row['departure_date'];
                $fields[$i][return_date] = $row['return_date'];
                $fields[$i][flight_number] = $row['flight_number'];
                $fields[$i][package] = $row['package'];
                $fields[$i][status] = $row['status'];
                $fields[$i][promo_code] = $row['promo_code'];
                $fields[$i][payment_type] = $row['payment_type'];
                $fields[$i][book_create_date_time] = $row['book_create_date_time'];
                $fields[$i][create_date_time] = $row['create_date_time'];
                $fields[$i][distance] = $row['distance'];
                $fields[$i][isdevice] = $row['isdevice'];
                $fields[$i][approx_time] = $row['approx_time'];
                if($row['status']==9)
                {
                    $fields[$i][amount] = $row['final_amount'];
                }
                else
                {
                    $fields[$i][amount] = $row['amount'];
                }
                $fields[$i][address] = $row['address'];
                $fields[$i][transfer] = $row['transfer'];
                $fields[$i][assigned_for] = $row['assigned_for'];
                $fields[$i][item_status] = $row['item_status'];
                $fields[$i][transaction] = $row['transaction'];
                $fields[$i][km] = $row['km'];
                $fields[$i][timetype] = $row['timetype'];
                $fields[$i][comment] = $row['comment'];
                $fields[$i][driver_status] = $row['driver_status'];
                $fields[$i][pickup_lat] = $row['pickup_lat'];
                $fields[$i][pickup_longs] = $row['pickup_longs'];
                $fields[$i][drop_lat] = $row['drop_lat'];
                $fields[$i][drop_longs] = $row['drop_longs'];
                $fields[$i][flag] = $row['flag'];
                $fields[$i][car_id] = $row['cab_id'];
                $fields[$i][car_type] = $row['cartype'];
                $fields[$i][icon] = $row['icon'];
                $fields[$i][seat_capacity] = $row['seat_capacity'];
                $dri_id= $row['assigned_for'];
                $sql1="SELECT * from driver_details WHERE id=$dri_id";
                $qry1=mysql_query($sql1);
                $rows=mysql_num_rows($qry1);
                if($rows !=0) {
                    while ($data = mysql_fetch_array($qry1)) {
                        $fields[$i]['driver_detail'] = array(
                            'id' => (int)$data['id'],
                            'name' => $data['name'],
                            'user_name' => $data['user_name'],
                            'phone' => $data['phone'],
                            'address' => $data['address'],
                            'email' => $data['email'],
                            'license_no' => $data['license_no'],
                            'car_type' => $data['car_type'],
                            'car_no' => $data['car_no'],
                            'gender' => $data['gender'],
                            'dob' => $data['dob'],
                            'wallet_amount' => $data['wallet_amount'],
                            'Lieasence_Expiry_Date' => $data['Lieasence_Expiry_Date'],
                            'license_plate' => $data['license_plate'],
                            'Insurance' => $data['license_plate'],
                            'Car_Model' => $data['Car_Model'],
                            'Car_Make' => $data['Car_Make'],
                            'image' => $data['image'],

                        );

                    }
                }
                else
                {
                    $fields[$i]['driver_detail'] = null;
                }

                $i++;
            }
        }
	$status="SELECT * FROM `userdetails` where id=$id";
        $statusrs=mysql_query($status);
        $datastatus=mysql_fetch_array($statusrs);
        $statuschk=$datastatus['user_status'];
        if($num !=0 && $new_off<=$num && $statuschk=='Active')
        {
            if($fields){
            //$this->db->select('');
            //$this->db->from('bookingdetails');
            //$this->db->join('Car_Type', 'Car_Type.car_type = bookingdetails.taxi_type');
            //$this->db->where('bookingdetails.user_id', $id);
            //$query = $this->db->get();
           

                $finresult['status']='success';
                $finresult['offset']=$new_off;
                $finresult['Count']=$num;
    	        $finresult['Isactive']=$statuschk;
                $finresult['all_trip']=$fields;
            }
            else{
                $finresult['status']='failed';
                $finresult['message']='Data Not Found';
            }
            echo json_encode($finresult);

            //'booking' => $booking,
            //'Cancelled' => $Cancelled,
            //'Completed' => $success,
        }
	elseif($statuschk =='Inactive')
        {
            $finresult['status']='false';
            $finresult['message']='Your account has been temporarily locked. Please contact our admin for further details.';
	    $finresult['error code']='1';
            $finresult['Isactive']=$statuschk;
            echo json_encode($finresult);
        }
        else
        {
            $finresult['status']='failed';

            $finresult['message']='Data Not Found';
	   $finresult['error code']='15';
            echo json_encode($finresult);

        }
    }
   

	public function driver_filter_book()
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $myDate = new DateTime();

        $url = $_SERVER['REQUEST_URI'];
        $filter1 = explode('filter=', $url);
        $filter2 = $filter1[1];
        $filter3 = explode('&', $filter2);
        $filter = urldecode($filter3[0]);
        //echo $comma_separated = "('" . implode("','", $filter) . "')";
        $search= explode(',',$filter);
        $search1=$search[0];
        $search2=$search[1];
        $search3=$search[2];
        //$search4=$search[3];

        $id1 = explode('driver_id=', $url);
        $id2 = $id1[1];
        $id3 = explode('&', $id2);
        $id = urldecode($id3[0]);


        $off1 = explode('off=', $url);
        $off2 = $off1[1];
        $off3 = explode('&', $off2);
        $off = urldecode($off3[0]);


        //$zone_name = 'Asia/Calcutta';
        $date = new DateTime("now", new DateTimeZone($this->zone_name));
        $server_time = $date->format('Y-m-d H:i:s');

        $lim = 10;
        if ($off == '' || $off == '0')
        {
            $off = 0;
        }
        $perpageTmp = $off;
        $perpage = '';
        if ($perpageTmp != '')
        {
            $perpage = $perpageTmp;
        }
        else
        {
            $perpage = 10;
        }
        if($search1 !='' && $search2 !='' && $search3!='')
        {
             //$sql="SELECT * FROM `bookingdetails` b INNER JOIN cabdetails c on b.taxi_type=c.cartype WHERE b.assigned_for=$id AND status in ($search1,$search2,$search3) ORDER by b.id DESC LIMIT $off,$lim";
            $sql="SELECT * FROM `bookingdetails` b INNER JOIN cabdetails c on b.taxi_type=c.cartype INNER JOIN `driver_status` d on b.id=d.booking_id WHERE d.driver_id='$id' AND driver_flag in($search1,$search2,$search3) ORDER BY b.id";

            // $this->db->where("status in ($search1,$search2,$search3,$search4)");
        }
//        elseif($search1 !='' && $search2 !='' && $search3 !='')
//        {
//            $sql="SELECT * FROM `bookingdetails` b INNER JOIN Car_Type c on b.taxi_type=c.car_type WHERE b.assigned_for=$id AND status in ($search1,$search2,$search3) ORDER by b.id DESC LIMIT $off,$lim";
//
//            //$this->db->where("status in ($search1,$search2,$search3)");
//        }
        elseif($search1 !='' && $search2 !='')
        {
            //$sql="SELECT * FROM `bookingdetails` b INNER JOIN cabdetails c on b.taxi_type=c.cartype WHERE b.assigned_for=$id AND status in ($search1,$search2) ORDER by b.id DESC LIMIT $off,$lim";
            $sql="SELECT * FROM `bookingdetails` b INNER JOIN cabdetails c on b.taxi_type=c.cartype INNER JOIN `driver_status` d on b.id=d.booking_id WHERE d.driver_id='$id' AND driver_flag in($search1,$search2) ORDER BY b.id";
            //$this->db->where("status in ($search1,$search2)");
       }
        elseif($search1 !='')
        {
            //$sql="SELECT * FROM `bookingdetails` b INNER JOIN cabdetails c on b.taxi_type=c.cartype WHERE b.assigned_for=$id AND status in ($search1) ORDER by b.id DESC LIMIT $off,$lim";
            $sql="SELECT * FROM `bookingdetails` b INNER JOIN cabdetails c on b.taxi_type=c.cartype INNER JOIN `driver_status` d on b.id=d.booking_id WHERE d.driver_id='$id' AND driver_flag in($search1) ORDER BY b.id";
            // $this->db->where("status in ($search1)");
        }
        else
        {
            //$sql="SELECT * FROM `bookingdetails` b INNER JOIN cabdetails c on b.taxi_type=c.cartype WHERE b.assigned_for=$id  ORDER by b.id DESC LIMIT $off,$lim";
            $sql="SELECT * FROM `bookingdetails` b INNER JOIN cabdetails c on b.taxi_type=c.cartype INNER JOIN `driver_status` d on b.id=d.booking_id WHERE d.driver_id='$id' ORDER BY b.id";
        }

        $qry=mysql_query($sql);


        $num = mysql_num_rows($qry);

        $new_off = $off + $num;

        if (mysql_num_rows($qry) > 0) {
            $succArr = 1;
            $i = 0;
            $sql .= " DESC LIMIT $off,$lim";
            $qry =mysql_query($sql);
            $new_count=mysql_num_rows($qry);
            $new_off = $off + $new_count;
            while ($row = mysql_fetch_array($qry))
            {
                $table = 'cabdetails';
                $select_data = "*";
                $where_data = array
                (
                    'cartype' => $row['taxi_type'],
                );
                $cab_details = $this->model_web_service->get_table_where($select_data, $where_data, $table);
                $per_minute_rate = $cab_details[0]['ride_time_rate'];
                $night_per_minute_rate = $cab_details[0]['night_ride_time_rate'];

                $fields[$i][id] = $row['booking_id'];
                $fields[$i][start_time]= $row['start_time'];
                $fields[$i][end_time]= $row['end_time'];
                $fields[$i][server_time] = $server_time;
                $fields[$i][driver_flag]= $row['driver_flag'];
                $fields[$i][username] = $row['username'];
                $fields[$i][user_id] = $row['user_id'];
                $fields[$i][pickup_date] = $row['pickup_date'];
                $fields[$i][pickup_area] = $row['pickup_area'];
                $fields[$i][drop_area] = $row['drop_area'];
                $fields[$i][pickup_time] = $row['pickup_time'];
                $fields[$i][pickup_date_time] = $row['pickup_date_time'];
                $fields[$i][area] = $row['area'];
                $fields[$i][landmark] = $row['landmark'];
                $fields[$i][pickup_address] = $row['pickup_address'];
                $fields[$i][taxi_type] = $row['taxi_type'];
                $fields[$i][departure_time] = $row['departure_time'];
                $fields[$i][departure_date] = $row['departure_date'];
                $fields[$i][return_date] = $row['return_date'];
                $fields[$i][flight_number] = $row['flight_number'];
                $fields[$i][package] = $row['package'];
                $fields[$i][status] = $row['status'];
                $fields[$i][promo_code] = $row['promo_code'];
                $fields[$i][payment_type] = $row['payment_type'];
                $fields[$i][book_create_date_time] = $row['book_create_date_time'];
                $fields[$i][create_date_time] = $row['create_date_time'];
                $fields[$i][distance] = $row['distance'];
                $fields[$i][isdevice] = $row['isdevice'];
                $fields[$i][approx_time] = $row['approx_time'];
                if($row['status']==9){
                    $fields[$i][amount] = $row['final_amount'];
                }
                else{
                    $fields[$i][amount] = $row['amount'];
                }
                $fields[$i][address] = $row['address'];
                $fields[$i][transfer] = $row['transfer'];
                $fields[$i][assigned_for] = $row['assigned_for'];
                $fields[$i][item_status] = $row['item_status'];
                $fields[$i][transaction] = $row['transaction'];
                $fields[$i][km] = $row['km'];
                $fields[$i][timetype] = $row['timetype'];
                $fields[$i][comment] = $row['comment'];
                $fields[$i][driver_status] = $row['driver_status'];
                $fields[$i][pickup_lat] = $row['pickup_lat'];
                $fields[$i][pickup_longs] = $row['pickup_longs'];
                $fields[$i][drop_lat] = $row['drop_lat'];
                $fields[$i][drop_longs] = $row['drop_longs'];
                $fields[$i][flag] = $row['flag'];
                $fields[$i][car_id] = $row['car_id'];
                $fields[$i][car_type] = $row['cartype'];
                $fields[$i][icon] = $row['icon'];
                $fields[$i][seat_capacity] = $row['seat_capacity'];
                if($row['timetype']=='day'){
                    $fields[$i][per_minute_rate]=$per_minute_rate;
                }
                else{
                    $fields[$i][per_minute_rate]=$night_per_minute_rate;
                }
                $user_id= $row['user_id'];
                $sql1="SELECT * from userdetails WHERE id=$user_id";
                $qry1=mysql_query($sql1);
                $rows=mysql_num_rows($qry1);
                if($rows !=0) {
                    while ($data = mysql_fetch_array($qry1)) {
                        $fields[$i]['user_detail'] = array(
                            'id' => $data['id'],
                            'name' => $data['name'],
                            'username' => $data['username'],
                            'mobile' => $data['mobile'],
                            'address' => $data['address'],
                            'email' => $data['email'],
                            'gender' => $data['gender'],
                            'dob' => $data['dob'],
                            'pickupadd' => $data['pickupadd'],
                            'wallet_amount' => $data['wallet_amount'],
                            'device_id' => $data['device_id'],
                            'facebook_id' => $data['facebook_id'],
                            'twitter_id' => $data['twitter_id'],
                            'isdevice' => $data['isdevice'],
                            'image' => $data['image'],

                        );

                    }
                }
                else
                {
                    $fields[$i]['user_detail'] = '';
                }

                $i++;
            }
        }
        $status="SELECT * FROM `driver_details` where id=$id";
        $statusrs=mysql_query($status);
        $datastatus=mysql_fetch_array($statusrs);
        $statuschk=$datastatus['status'];
        if($num !=0 && $new_off<=$num && $statuschk=='Active')
        {
            if($fields){
            $finresult['status']='success';
            $finresult['offset']=$new_off;
            $finresult['Count']=$num;
            $finresult['Isactive']=$statuschk;
            $finresult['all_trip']=$fields;
            $table_setting_details ='settings';
            $result_setting = $this->model_web_service->get_table('country,currency',  $table_setting_details);
            $finresult['Country']=$result_setting;
            }
            else{
                $finresult['status']='failed';
                $finresult['message']='Data Not Found';
		$finresult['error code']='15';
            }
            echo json_encode($finresult);
        }
        elseif($statuschk =='Inactive')
        {
            $finresult['status']='false';
            $finresult['message']='Your account has been temporarily locked. Please contact our admin for further details.';
	    $finresult['error code']='1';
            $finresult['Isactive']=$statuschk;
            echo json_encode($finresult);
        }
        else
        {
            $finresult['status']='failed';

            $finresult['message']='Data Not Found';
	    $finresult['error code']='15';
            echo json_encode($finresult);

        }
    }

    public function load_card_rate()
    {
        //http://192.168.1.3/gagaji/WebApp/Source/web_service/load_card_rate?transfertype=Point%20to%20Point%20Transfer

        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $result = $this->model_web_service->load_all_cabs($request);

        $day = array();
        $night = array();

        foreach ($result as $item) {
            if ($item['timetype'] == 'day') {
                $day[] = $item;
            } else if ($item['timetype'] == 'night') {
                $night[] = $item;
            }
        }

        $finresult = array(
            'status' => 'success',
            'day' => $day,
            'night' => $night
        );
        print json_encode($finresult);
    }
	public function change_password()
    {       
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $uid = $this->input->post('uid');
        $password = $this->input->post('password');
	//$isdevice= $this->input->post('isdevice');
	//$uid= $this->input->post('uid');

	    /*$url = $_SERVER['REQUEST_URI'];
	    $password1 = explode('password=', $url);
        $password2 = $password1[1];
        $password3 = explode('&', $password2);
        $password = urldecode($password3[0]);
	    $uid1 = explode('uid=', $url);
        $uid2 = $uid1[1];
        $uid3 = explode('&', $uid2);
        $uid = urldecode($uid3[0]);*/
	        
        $table = 'userdetails';
        $select_data='*';
	    $update_data = array(         
            'password' => md5($password),
        );

        $where_data = array(
            'id' => $uid,
        );
	    $status="SELECT * FROM `userdetails` where id=$uid";
            $statusrs=mysql_query($status);
            $datastatus=mysql_fetch_array($statusrs);
            $statuschk=$datastatus['user_status'];
	    if($statuschk=='Active')
        {
    	    $result=$this->model_web_service->update_table_where($update_data, $where_data, $table);
            if($result)
            {
                //$result_details = $this->get_table_where($select_data,$where_data,$table);
                $user['status'] = 'success';
        	    $user['message'] = 'Password Change Successfully';
        	    $user['Isactive'] = $statuschk;
                //$user['user_detail'] = $result_details;
                print json_encode($user);
            }
            else
            {
                $user['status'] ='failed'; 
        	    $user['message']='Please enter correct login details';
		    $user['error code'] ='2';
        	    $user['code']='Password Change failed';
        	    $user['Isactive'] = $statuschk;
                print json_encode($user);
            }
	    }
        else if($statuschk =='Inactive')
        {
            $finresult['status']='false';
            $finresult['message']='Your account has been temporarily locked. Please contact our admin for further details.';
	   $finresult['error code']='1';
            $finresult['Isactive']=$statuschk;
            echo json_encode($finresult);
        }

    }
        
        public function driver_change_password()
        {       
                $postdata = file_get_contents("php://input");
                $request = json_decode($postdata);
                //$password= $this->input->post('password');
                //$isdevice= $this->input->post('isdevice');
                //$uid= $this->input->post('uid');
                $url = $_SERVER['REQUEST_URI'];
                $password1 = explode('password=', $url);
                $password2 = $password1[1];
                $password3 = explode('&', $password2);
                $password = urldecode($password3[0]);
                $uid1 = explode('did=', $url);
                $uid2 = $uid1[1];
                $uid3 = explode('&', $uid2);
                $uid = urldecode($uid3[0]);
                    
                $table = 'driver_details';
                $select_data='*';
                $update_data = array(         
                    'password' => $password
                );

                $where_data = array(
                    'id' => $uid,
                );
                    $status="SELECT * FROM `driver_details` where id=$uid";
                    $statusrs=mysql_query($status);
                    $datastatus=mysql_fetch_array($statusrs);
                    $statuschk=$datastatus['status'];
                    if($statuschk=='Active')
                    {
                        $result=$this->model_web_service->update_table_where($update_data, $where_data, $table);
                        if($result)
                        {
                            //$result_details = $this->get_table_where($select_data,$where_data,$table);
                            $user['status'] = 'success';
                            $user['message'] = 'Password Change Successfully';
                            $user['Isactive'] = $statuschk;
                            //$user['user_detail'] = $result_details;
                  
                            print json_encode($user);
                        }
                        else
                        {
                            $user['status'] ='failed'; 
                            $user['message']='Please enter correct login details';
			    $user['error code'] ='2'; 
                            $user['code']='Password Change failed';
                            $user['Isactive'] = $statuschk;
                            print json_encode($user);
                        }
                    }
                    else if($statuschk =='Inactive')
                    {
                            $finresult['status']='false';
                            $finresult['message']='Your account has been temporarily locked. Please contact our admin for further details.';
			    $finresult['error code']='1';
                            $finresult['Isactive']=$statuschk;
                            echo json_encode($finresult);
                    }

        }

	 public function profile_edit()
    {
        //http://v1technology.co.uk/demo/naqil/naqilcom/Source/web_service/profile_edit?username=gagaji78787&Password=123456&uid=213
        $username= $this->input->post('username');
        $email= $this->input->post('email');
        $mobile= $this->input->post('mobile');
        //$password= $this->input->post('password');
        $name= $this->input->post('name');
        //$address= $this->input->post('address');
        $gender= $this->input->post('gender');
        $dob= $this->input->post('dob');
	    $isdevice= $this->input->post('isdevice');
        $img=$_FILES['image'];
        
	    $uid= $this->input->post('uid');
		
        $mail_status = $this->model_web_service->is_mail_exists($email,$uid);
        $user_status = $this->model_web_service->is_username_exists($username,$uid);
        $mobile_status = $this->model_web_service->is_mobile_exists($mobile,$uid);
        $table_cab_details = 'cabdetails';
        $table_time_details='time_detail';
        if ($mail_status || $user_status || $mobile_status) //CHECK MAIL ID OR USER NAME EXIST
        {
            //$error_list = array();
            if ($mail_status && $user_status && $mobile_status) 
              {     
                  $finresult = array(
                'status' => 'failed',
                'message' => 'email address,username and mobile number already exist.',
		'error code' => '7',
                'code' => 'exists'
                );                  
            }
            else if($mail_status && $user_status) 
               {
               $finresult = array(
                'status' => 'failed',
                'message' => 'email address,username already in exist',
		'error code' => '8',
                'code' => 'exists'
                );   
            }
            else if ($mobile_status && $mail_status) {
                $finresult = array(
                'status' => 'failed',
                'message' => 'email address,mobile number already in exist',
		'error code' => '9',
                 'code' => 'exists'
                );           
            }
            else if ($mobile_status && $user_status) {
                $finresult = array(
                'status' => 'failed',
                'message' => 'mobile number,username already in exist',
		'error code' => '10',
                 'code' => 'exists'
                );    
            }
            else if ($mail_status) {
                $finresult = array(
                'status' => 'failed',
                'message' => 'Email id already in exist',
		'error code' => '11',
                 'code' => 'exists'
                );   
            }
            else if ($user_status) {
                $finresult = array(
                'status' => 'failed',
                'message' => 'Username already in exist',
		'error code' => '12',
                 'code' => 'exists'
                );
            }    
            else if ($mobile_status) {
                $finresult = array(
                'status' => 'failed',
                'message' => 'Mobile number already in exist',
		'error code' => '13',
                 'code' => 'exists'
                );
            }
            else{
                $finresult = array(
                'status' => 'failed',
                'message' => 'Something went wrong',
		'error code' => '14',
                 'code' => 'error'
                );
            }
            print json_encode($finresult);
        }
        else
        {
            $status="SELECT * FROM `userdetails` where id=$uid";
            $statusrs=mysql_query($status);
            $datastatus=mysql_fetch_array($statusrs);
            $statuschk=$datastatus['user_status'];
            if($statuschk=='Active')
            {
        		if($img !='') 
        		{
        			$imgflag=$this->upload_image($img,"user_image/","","user");
        		}		
        		else
        		{
        			$sql="SELECT * FROM `userdetails` where id=$uid";
        			$query=mysql_query($sql);
        			$data=mysql_fetch_array($query);
        			$imgflag=$data['image'];
        		}              
                $result = $this->model_web_service->get_profile_edit($username,$email,$mobile,$name,$gender,$dob,$isdevice,$imgflag,$uid);
            }
            else if($statuschk=='Inactive')
            {
                $finresult['status']='false';
                $finresult['message']='Your account has been temporarily locked. Please contact our admin for further details.';
		$finresult['error code']='1';
                $finresult['Isactive']=$statuschk;
                echo json_encode($finresult);
            }
    	}
	}
	 public function driver_profile_edit()
    	{
        //http://v1technology.co.uk/demo/naqil/naqilcom/Source/web_service/profile_edit?username=gagaji78787&Password=123456&uid=213
       /* $url = $_SERVER['REQUEST_URI'];
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);*/
	    $username= $this->input->post('username');
        $email= $this->input->post('email');
        $phone= $this->input->post('phone');
        $password= $this->input->post('password');
        $name= $this->input->post('name');
        $license_no= $this->input->post('license_no');
        $Lieasence_Expiry_Date= $this->input->post('Lieasence_Expiry_Date');
        $license_plate= $this->input->post('license_plate');
        $Insurance= $this->input->post('Insurance');
        $Seating_Capacity= $this->input->post('Seating_Capacity');
        $Car_Model= $this->input->post('Car_Model');
        $Car_Make= $this->input->post('Car_Make');
        $car_no= $this->input->post('Car_no');
        $car_type= $this->input->post('car_type');
        $address= $this->input->post('address');
        $gender= $this->input->post('gender');
        $dob= $this->input->post('dob');
        $img=$_FILES['image'];
	    $uid= $this->input->post('uid');
	
        $email_already_exist = $this->model_web_service->driver_email_id_exist($email,$uid);
        $already_exist = $this->model_web_service->driver_user_id_exist($username,$uid);
        $car_no_exist = $this->model_web_service->driver_car_no_exist($car_no,$uid);
        $license_no_exist = $this->model_web_service->driver_license_no_exist($license_no,$uid);
        $phone_exist = $this->model_web_service->driver_phone_no_exist($phone,$uid);
        //print_r($already_exist);
        if($email_already_exist)
        {
            $finresult = array(
                'status' => 'failed',
		'error code' => '17',
                'message' => 'Email id already exists'
            );
            print json_encode($finresult);
        }
        else if ($already_exist)
        {   
            $finresult = array(
                'status' => 'failed',
		'error code' => '18',
                'message' => 'Username already exists'
            );
            print json_encode($finresult); 
        }
        else if($car_no_exist)
        {
            $finresult = array(
                    'status' => 'failed',
		    'error code' => '19',
                    'message' => 'Vehicle number already exists'
                );
            print json_encode($finresult);
        }
        else if($license_no_exist)
        {
            $finresult = array(
                    'status' => 'failed',
		    'error code' => '20',
                    'message' => 'License number already exists'
                );
            print json_encode($finresult); 
        }
        else if($phone_exist)
        {
            $finresult = array(
                    'status' => 'failed',
		    'error code' => '21',
                    'message' => 'Mobile number already exists'
                );
            print json_encode($finresult); 
        }
        else
        {
            if($img !='') 
            {
                $imgflag=$this->upload_image($img,"driverimages/","","driver");
            }       
            else
            {
                $sql="SELECT * FROM `driver_details` where id=$uid";
                $query=mysql_query($sql);
                $data=mysql_fetch_array($query);
                $imgflag=$data['image'];
            }
		
            $driver_register = $this->model_web_service->driver_profile_edit($username, $email, $phone,$imgflag, $password, $name, $license_no, $Lieasence_Expiry_Date, $license_plate, $Insurance, $Seating_Capacity, $Car_Model, $Car_Make, $car_no, $car_type, $address, $gender, $dob,$uid);
        }
        //$result = $this->model_web_service->driver_profile_edit($request);

    }
	public function forgot_password()
    {
        $url = $_SERVER['REQUEST_URI'];
        $email1=explode('email=',$url);
        $email2=$email1[1];
        $email3=explode('&',$email2);
        $email=urldecode($email3[0]);
    	
    	$str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $newPassword = '';
        for ($i = 0; $i < 8; $i++) $newPassword .= substr($str, rand(0, strlen($str)), 1);
        //echo $newPassword;
	    $password=$newPassword;

        //$password=$newPassword;

        $this->db->select('*');
        $this->db->from('userdetails');
        $this->db->where('email',$email);
        $price=$this->db->get();
        $email_user1=json_encode($price->row()->email);

        //$email_user='gagajikhambhla15@gmail.com';
        $email_user=trim($email_user1, '"');;

        $table = 'userdetails';
        $select_data = "*";;
        $update_data = array(
            'password' => $password
        );

        $where_data = array
        (
            'email' => $email,
        );
        $status="SELECT * FROM `userdetails` where email='$email'";
        $statusrs=mysql_query($status);
        $datastatus=mysql_fetch_array($statusrs);
        $statuschk=$datastatus['user_status'];

        if($statuschk=='Active')
        {
            if($email_user == $email)
            {
                $data = $this->model_web_service->update_table_where($update_data, $where_data, $table);
                if (count($data) > 0) 
                {  
                        $subject = 'Naqilcom Forget Password Request.';
                        $admin_email=$this->model_web_service->checkadminemail();
                        $send_email = $email;
                        $email_body ='<div style="background-color: #00bcd4; color: #0b0b0b;">
                    <table style="background-color:#292A6B;border:1px solid #20264a;padding:10px;font-family:Verdana;font-size:12px" width="100%"><tbody><tr><td><img class="CToWUd" src="http://138.68.5.43/upload/logo.png" style="min-height:25%;width:25%"></td></tr><tr><td>&nbsp;</td></tr><tr> <td> <table style="padding:10px;font-size:12px;background-color:#fff;border:1px solid #2d62ac" cellpadding="5" width="100%"> <tbody> <tr><td colspan="4">&nbsp;</td></tr> <tr><td colspan="4" style="font-family:Verdana,Geneva,sans-serif;font-size:12px;text-align:left"> Hey, '.$email.'</td></tr> <tr><td colspan="4" style="font-family:Verdana,Geneva,sans-serif;font-size:12px;text-align:left"> <br><br> Your email and new password for Naqil are as below. </td></tr> <tr> <td colspan="4"> <table style="font-family:Verdana,Geneva,sans-serif;font-size:12px;width:600px;border-collapse:collapse" height="30"> <tbody> <tr> <th style="border:1px solid #808080!important;font-size:1.1em;text-align:left;padding-top:5px;padding-left:10px;padding-bottom:5px;background-color:#cdcdcd!important;color:#000000!important" width="20%">Email</th> <th style="border:1px solid #808080!important;font-size:1.1em;text-align:left;padding-top:5px;padding-left:10px;padding-bottom:5px;background-color:#cdcdcd!important;color:#000000!important" width="35%">'.$email.'</th> </tr> <tr> <th style="border:1px solid #808080!important;font-size:1.1em;text-align:left;padding-top:5px;padding-left:10px;padding-bottom:5px;background-color:#cdcdcd!important;color:#000000!important" width="20%">Password</th> <th style="border:1px solid #808080!important;font-size:1.1em;text-align:left;padding-top:5px;padding-left:10px;padding-bottom:5px;background-color:#cdcdcd!important;color:#000000!important" width="35%">'.$password.'</th> </tr> <tr><td colspan="4" style="font-family:Verdana,Geneva,sans-serif;font-size:12px;text-align:left"></td></tr> <tr><td colspan="4" style="font-family:Verdana,Geneva,sans-serif;font-size:12px;text-align:left"><br><br> If you have any questions please contact <a href="mailto:pollvaultadm@gmail.com" target="_blank">sarju@techintegrity.in</a>. <br><br><br>Thanks. </td></tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody></table>
                    </div>';
                    $response = $this->send_mail($subject,$send_email,$email_body);
                    if($response){
                        $result_details = $this->model_web_service->get_table_where($select_data, $where_data, $table);
                        $user = array(
                            'status' => 'success',
                            "message"=>"Please check your email, including index."
                        );
                        print json_encode($user);
                    }            
                }
                else
                {
                   // $user[] = array('status' => 'failed', 'message' => 'Unknown credential , please try again!', 'code' => 'Password Update failed',);
                    $finresult['status']='failed';
                    $finresult['message']='Please enter correct login details';
		    $finresult['error code']='2';
                    $finresult['code']='Password Update failed';
                    print json_encode($finresult);
                }
                
            }
        }
        else if($statuschk=='Inactive')
        {
            $finresult['status']='false';
            $finresult['message']='Your account has been temporarily locked. Please contact our admin for further details.';
	   $finresult['error code']='1';
            $finresult['Isactive']=$statuschk;
            echo json_encode($finresult);
        }
        else
        {
                $finresult['status']='failed';
                $finresult['message']='Please enter correct login details';
		$finresult['error code']='2';
                $finresult['code']='Password Update failed';
                print json_encode($finresult);
        }     
    }
	public function driver_forgot_password()
    {
        $url = $_SERVER['REQUEST_URI'];
        $email1=explode('email=',$url);
        $email2=$email1[1];
        $email3=explode('&',$email2);
        $email=urldecode($email3[0]);

        $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $newPassword = '';
        for ($i = 0; $i < 8; $i++) $newPassword .= substr($str, rand(0, strlen($str)), 1);
        //echo $newPassword;
        $password=$newPassword;

        $this->db->select('*');
        $this->db->from('driver_details');
        $this->db->where('email',$email);
        $price=$this->db->get();
        $email_user1=json_encode($price->row()->email);

        //$email_user='gagajikhambhla15@gmail.com';
        $email_user=trim($email_user1, '"');

        $table = 'driver_details';
        $select_data = "*";;
        $update_data = array(
            'password' => $password
        );

        $where_data = array
        (
            'email' => $email,
        );
        $status="SELECT * FROM `driver_details` where email='$email'";
        $statusrs=mysql_query($status);
        $datastatus=mysql_fetch_array($statusrs);
        $statuschk=$datastatus['status'];
        if($statuschk=='Active')
        {
            if($email_user == $email)
            {
                $data = $this->model_web_service->update_table_where($update_data, $where_data, $table);
                // echo $data;
                if (count($data) > 0) 
                {
                        $subject = 'Naqilcom Forget Password Request.';
                        $admin_email=$this->model_web_service->checkadminemail();
                        $send_email = $email;
                        $email_body ='<div style="background-color: #00bcd4; color: #0b0b0b;">
                    <table style="background-color:#292A6B;border:1px solid #20264a;padding:10px;font-family:Verdana;font-size:12px" width="100%"><tbody><tr><td><img class="CToWUd" src="http://138.68.5.43/upload/logo.png" style="min-height:25%;width:25%"></td></tr><tr><td>&nbsp;</td></tr><tr> <td> <table style="padding:10px;font-size:12px;background-color:#fff;border:1px solid #2d62ac" cellpadding="5" width="100%"> <tbody> <tr><td colspan="4">&nbsp;</td></tr> <tr><td colspan="4" style="font-family:Verdana,Geneva,sans-serif;font-size:12px;text-align:left"> Hey, '.$email.'</td></tr> <tr><td colspan="4" style="font-family:Verdana,Geneva,sans-serif;font-size:12px;text-align:left"> <br><br> Your email and new password for Naqil are as below. </td></tr> <tr> <td colspan="4"> <table style="font-family:Verdana,Geneva,sans-serif;font-size:12px;width:600px;border-collapse:collapse" height="30"> <tbody> <tr> <th style="border:1px solid #808080!important;font-size:1.1em;text-align:left;padding-top:5px;padding-left:10px;padding-bottom:5px;background-color:#cdcdcd!important;color:#000000!important" width="20%">Email</th> <th style="border:1px solid #808080!important;font-size:1.1em;text-align:left;padding-top:5px;padding-left:10px;padding-bottom:5px;background-color:#cdcdcd!important;color:#000000!important" width="35%">'.$email.'</th> </tr> <tr> <th style="border:1px solid #808080!important;font-size:1.1em;text-align:left;padding-top:5px;padding-left:10px;padding-bottom:5px;background-color:#cdcdcd!important;color:#000000!important" width="20%">Password</th> <th style="border:1px solid #808080!important;font-size:1.1em;text-align:left;padding-top:5px;padding-left:10px;padding-bottom:5px;background-color:#cdcdcd!important;color:#000000!important" width="35%">'.$password.'</th> </tr> <tr><td colspan="4" style="font-family:Verdana,Geneva,sans-serif;font-size:12px;text-align:left"></td></tr> <tr><td colspan="4" style="font-family:Verdana,Geneva,sans-serif;font-size:12px;text-align:left"><br><br> If you have any questions please contact <a href="mailto:pollvaultadm@gmail.com" target="_blank">sarju@techintegrity.in</a>. <br><br><br>Thanks. </td></tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody></table>
                    </div>';
                    $response = $this->send_mail($subject,$send_email,$email_body);
                    if($response){
                        $result_details = $this->model_web_service->get_table_where($select_data, $where_data, $table);
                        $user = array(
                            'status' => 'success',
                            "message"=>"Please check your email, including index."
                        );
                        print json_encode($user);
                    }            
                }
                else
                {
                   // $user[] = array('status' => 'failed', 'message' => 'Unknown credential , please try again!', 'code' => 'Password Update failed',);
                    $finresult['status']='failed';
                    $finresult['message']='Please enter correct login details';
		    $finresult['error code']='2';
                    $finresult['code']='Password Update failed';
                    print json_encode($finresult);
                }
            }
        }
        else if($statuschk=='Inactive')
        {
            $finresult['status']='false';
            $finresult['message']='Your account has been temporarily locked. Please contact our admin for further details.';
	    $finresult['error code']='1';
            $finresult['Isactive']=$statuschk;
            echo json_encode($finresult);
        }
        else
        {
                //$user[] = array('status' => 'failed', 'message' => 'Unknown credential , please try again!', 'code' => 'Password Update failed',);
                //print json_encode($user);
                $finresult['status']='failed';
                $finresult['message']='Please enter correct login details';
		$finresult['error code']='2';
                $finresult['code']='Password Update failed';
                print json_encode($finresult);
        }     
    }

    //fetchbooking cronjob call
    public function fetch_booking_cronjob()
    {
                       $getdriverstatus=array();
                $data['query']=$this->model_web_service->fetchbookingcron();
                if($data['query']){
                    //print_r($data['query']);
                    //exit;
                    foreach ($data['query'] as $row) {
                        $particular_driverids=$this->model_web_service->fetchparticulardriverstatus($row['id']);
                        //print_r($particular_driverids);
                        $other_driverids=$this->model_web_service->fetchotherdriverstatus();
                        //print_r($other_driverids);
                        //echo '<br/>';
                        //echo $row['id'].'<br/>';
                        //print_r($particular_driverids).'<br/>';
                        //print_r($other_driverids).'<br/>';
                        if(($particular_driverids!='' || $particular_driverids!=0) && ($other_driverids!='' || $other_driverids!=0)) {
                            $total_driverids = implode(',', array_unique(array_merge($particular_driverids, $other_driverids), SORT_NUMERIC));
                        }
                        else if(($particular_driverids!='' || $particular_driverids!=0) && ($other_driverids=='' || $other_driverids==0)){
                            $total_driverids=implode(',',$particular_driverids);
                        }
                        else if(($particular_driverids=='' || $particular_driverids==0) && ($other_driverids!='' || $other_driverids!=0)) {
                            $total_driverids = implode(',', $other_driverids);
                        }
                        else if(($particular_driverids=='' || $particular_driverids==0) && ($other_driverids=='' || $other_driverids==0)){
                            $total_driverids = 0;
                        }
                        //echo '<br/>';
                        //print_r($total_driverids);
                        //exit();
                        if($total_driverids==0){
                            $integerIDs=0;
                        }
                        else{
                            $integerIDs = array_map('intval', explode(',', $total_driverids));
                        }
                        $lat = $row['pickup_lat'];
                        $long = $row['pickup_long'];
                        $pickup_add = $row['pickup_area'];
                        $cron_booking_id = $row['id'];
                        $car_type = $row['taxi_id'];
                        $date = new DateTime("now", new DateTimeZone($this->zone_name));
				        $cron_starttime = $date->format('Y-m-d H:i:s');
				        $date = new DateTime("now", new DateTimeZone($this->zone_name));
				        $date->add(new DateInterval('PT60S'));
				        //$endTime = date('Y-m-d H:i:s',strtotime('+60 seconds',strtotime($startTime)));
				        $cron_endtime = $date->format('Y-m-d H:i:s');
                        $json_array = array(
                            //'driverId' => (int)$driveridarr,
                            'driverId' => $integerIDs,
                            'coords' => array((float)$lat, (float)$long),
                            'pickup' => urlencode($pickup_add),
                            'booking_id' => $cron_booking_id,
                            'start_time' => urlencode($cron_starttime),
                            'end_time' => urlencode($cron_endtime),
                            'car_type' => urlencode($car_type)
                        );
                        $new_json_array = json_encode($json_array,JSON_UNESCAPED_SLASHES);
                        //print_r($new_json_array);
                        //exit;
                        $url = "162.243.225.225:4040/searchDriver?".$new_json_array;
                        //$url = "192.168.1.118:4040/searchDriver?".$new_json_array;
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                        // This is what solved the issue (Accepting gzip encoding)
                        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");
                        $response = curl_exec($ch);
                        curl_close($ch);
                        //echo $response;
                        //exit();
                        if($response) {
                            $response_data = json_decode($response, true);
                            //echo "<pre>";
                            //print_r($response_data);
                            if($response_data['data'])
                            {
                                foreach ($response_data['data'] as $res)
                                {
                                    if (isset($res['driver_id']))
                                    {
	                                        $ct = $this->model_web_service->getbookingidcount($row['id']);
	                                        if ($ct < 5)
	                                        {
	                                                $data['query1'] = $this->model_web_service->updatedriverstatus($row['id'], $res['driver_id']);
	                                                if($data['query1']){
		                                                $did='d_'.$res['driver_id'];
		                                                $description=sprintf("Booking ID:%s New Booking Request Arrived.",$row['id']);
		                                                //---- Notification Start ---------//
		                                                $urlNotification = "162.243.225.225:8002/send";
												        $data_json= sprintf('{
												            "users": ["%s"],  
												            "android": {
												                      "collapseKey": "optional",
												                      "data": {
												                        "message": "%s"
												                      }
												                    },
												            "ios": {
												              "badge": 0,
												              "alert": "%s",
												              "sound": "soundName"
												            }
												        }',$did,$description,$description);
												        //print_r($data_json);
												        $ch1 = curl_init();
												        curl_setopt($ch1, CURLOPT_URL, $urlNotification);
												        curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
												        curl_setopt($ch1, CURLOPT_POST, 1);
												        curl_setopt($ch1, CURLOPT_POSTFIELDS,$data_json);
												        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
												        $notification_response  = curl_exec($ch1);
												        //print_r($response );
												        //---- Notification End ---------//
											    	}
	                                        } 
	                                        else if($ct==5)
	                                        {
	                                            $data['query1'] = $this->model_web_service->updatedriverflag($row['id']);
	                                            if($data['query1'])
	                                            {
                                                        $find_user_id=$this->model_web_service->findpushuser($row['id']);
                                                        $uid='u_'.$find_user_id;
                                                        $description=sprintf("Oops! Booking ID %s is cancelled as all our drivers are busy handling other client’s. We regret for inconvenience caused.",$row['id']);
                                                        //---- Notification Start ---------//
                                                        $urlNotification = "162.243.225.225:8001/send";
                                                            $data_json= sprintf('{
                                                                "users": ["%s"],  
                                                                "android": {
                                                                    "collapseKey": "optional",
                                                                    "data": {
                                                                        "message": "%s"
                                                                        }
                                                                    },
                                                                    "ios": {
                                                                        "badge": 0,
                                                                        "alert": "%s",
                                                                        "sound": "soundName"
                                                                    }
                                                                }',$uid,$description,$description);                                     
                                                                //print_r($data_json);
                                                                $ch1 = curl_init();
                                                                curl_setopt($ch1, CURLOPT_URL, $urlNotification);
                                                                curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                                                                curl_setopt($ch1, CURLOPT_POST, 1);
                                                                curl_setopt($ch1, CURLOPT_POSTFIELDS,$data_json);
                                                                curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
                                                                $notification_response  = curl_exec($ch1);
                                                                //print_r($response );
                                                                //---- Notification End ---------//
	                                                  $cancelled_booking_data[]=$this->model_web_service->getunavailablebooking($row['id']);
	                                                  if($cancelled_booking_data)
	                                                 	{
		                                                      $subject = 'Booking ID:'.$row['id'].' - Driver Unavailable History.';
		                                                      $admin_email=$this->model_web_service->checkadminemail();
		                                                      $email = $admin_email;
		                                                      $body  = '<p>Booking ID:'.$row['id'].'</p>';
		                                                      $body .= '<p>Pickup Location:'.$row['pickup_area'].'</p>';
		                                                      $body .= '<p>Drop Location:'.$row['drop_area'].'</p>';
		                                                      $body .= '<p>Pickup Time:'.$row['pickup_date_time'].'</p>';
		                                                      $body .= '<p><strong>Driver Assigned:</strong></p>';
		                                                      $body .= '<table border="1" style="text-align:center;">';
		                                                      $body .= '<tr>';
		                                                      $body .= '<th>Driver ID</th>';
		                                                      $body .= '<th>Assigned Time</th>';
		                                                      $body .= '<th>Cancelled Time</th>';
		                                                      $body .= '<th>Driver Username</th>';
		                                                      $body .= '<th>Driver Name</th>';
		                                                      $body .= '<th>Phone</th>';
		                                                      $body .= '<th>Email</th>';
		                                                      $body .= '<th>License No</th>';
		                                                      $body .= '<th>Car Type</th>';
		                                                      $body .= '<th>Car No</th>';
		                                                      $body .= '<th>Status</th>';
		                                                      $body .= '<th>Is Flagged</th>';
		                                                      $body .= '</tr>';
		                                                      foreach($cancelled_booking_data as $single_cancelled_booking){  
		                                                            foreach($single_cancelled_booking as $booking){
		                                                                $table = 'driver_details';
		                                                                $select_data = "*";
		                                                                $where_data = array
		                                                                (
		                                                                    'id' => $booking['driver_id'],
		                                                                );
		                                                                $driver_details = $this->model_web_service->get_table_where($select_data, $where_data, $table);
		                                                                if($driver_details && !empty($driver_details[0])){
		                                                                $body .= '<tr>';
		                                                                $body .= '<td>'.$driver_details[0]['id'].'</td>';
		                                                                $table = 'driver_status';
		                                                                $select_data = "*";
		                                                                $where_data = array
		                                                                (
		                                                                    'driver_id' => $driver_details[0]['id']
		                                                                );
    		                                                                $driver_status_details = $this->model_web_service->get_table_where($select_data, $where_data, $table);
                                                                            if($driver_status_details && !empty($driver_status_details[0])){
    		                                                                $start_time=$driver_status_details[0]['start_time'];
    		                                                                $end_time=$driver_status_details[0]['end_time'];
    		                                                                $body .= '<td>'.$start_time.'</td>';
    		                                                                $body .= '<td>'.$end_time.'</td>';
    		                                                                $body .= '<td>'.$driver_details[0]['user_name'].'</td>';
    		                                                                $body .= '<td>'.$driver_details[0]['name'].'</td>';
    		                                                                $body .= '<td>'.$driver_details[0]['phone'].'</td>';
    		                                                                $body .= '<td>'.$driver_details[0]['email'].'</td>';
    		                                                                $body .= '<td>'.$driver_details[0]['license_no'].'</td>';
    		                                                                $body .= '<td>'.$driver_details[0]['car_type'].'</td>';
    		                                                                $body .= '<td>'.$driver_details[0]['car_no'].'</td>';
    		                                                                $body .= '<td>'.$driver_details[0]['status'].'</td>';
    		                                                                $body .= '<td>'.$driver_details[0]['flag'].'</td>';
    		                                                                $body .= '</tr>';
                                                                            }
                                                                            else{
                                                                                $body .= '<tr><td colspan="10" align="center">Not any driver assigned.</td></tr>';
                                                                            }
		                                                                }
		                                                                else{
		                                                                    $body .= '<tr><td colspan="10" align="center">Not any driver assigned.</td></tr>';
		                                                                }
		                                                            }    
		                                                        }
		                                                        $body .= '</table>';
	                                                      		$response=$this->send_mail($subject,$email,$body);
	                                                		}
	                                            }
	                                        }
                                        if ($data['query1']) {
                                            $getdriverstatus[] = $data['query1'];
                                        } else {
                                            $getdriverstatus[] = NULL;
                                        }
                                    }
                                }
                            }
                            else
                            {
                                $data['query2'] = $this->model_web_service->cancelbookingbydriverside($row['id']);
                                if ($data['query2']) 
                                {
                                    $find_user_id=$this->model_web_service->findpushuser($booking_id);
                                    $uid='u_'.$find_user_id;
                                    $description=sprintf("Oops! Booking ID %s is cancelled as all our drivers are busy handling other client’s. We regret for inconvenience caused.",$row['id']);
                                    //---- Notification Start ---------//
                                    $urlNotification = "162.243.225.225:8001/send";
                                    $data_json= sprintf('{
                                        "users": ["%s"],  
                                        "android": {
                                            "collapseKey": "optional",
                                            "data": {
                                                "message": "%s"
                                            }
                                        },
                                        "ios": {
                                            "badge": 0,
                                            "alert": "%s",
                                            "sound": "soundName"
                                        }
                                    }',$uid,$description,$description);                                     
                                    //print_r($data_json);
                                    $ch1 = curl_init();
                                    curl_setopt($ch1, CURLOPT_URL, $urlNotification);
                                    curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                                    curl_setopt($ch1, CURLOPT_POST, 1);
                                    curl_setopt($ch1, CURLOPT_POSTFIELDS,$data_json);
                                    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
                                    $notification_response  = curl_exec($ch1);
                                    //print_r($response );
                                    //---- Notification End ---------//
                                    $getdriverstatus[] = $data['query2'];
                                     $cancelled_booking_data[]=$this->model_web_service->getunavailablebooking($row['id']);
                                                  if($cancelled_booking_data){
                                                      $subject = 'Booking ID:'.$row['id'].' - Driver Unavailable History.';
                                                      $admin_email=$this->model_web_service->checkadminemail();
                                                      $email = $admin_email;
                                                      $body  = '<p>Booking ID:'.$row['id'].'</p>';
                                                      $body .= '<p>Pickup Location:'.$row['pickup_area'].'</p>';
                                                      $body .= '<p>Drop Location:'.$row['drop_area'].'</p>';
                                                      $body .= '<p>Pickup Time:'.$row['pickup_date_time'].'</p>';
                                                      $body .= '<p><strong>Driver Assigned:</strong></p>';
                                                      $body .= '<table border="1" style="text-align:center;">';
                                                      $body .= '<tr>';
                                                      $body .= '<th>Driver ID</th>';
                                                      $body .= '<th>Assigned Time</th>';
                                                      $body .= '<th>Cancelled Time</th>';
                                                      $body .= '<th>Driver Username</th>';
                                                      $body .= '<th>Driver Name</th>';
                                                      $body .= '<th>Phone</th>';
                                                      $body .= '<th>Email</th>';
                                                      $body .= '<th>License No</th>';
                                                      $body .= '<th>Car Type</th>';
                                                      $body .= '<th>Car No</th>';
                                                      $body .= '<th>Status</th>';
                                                      $body .= '<th>Is Flagged</th>';
                                                      $body .= '</tr>';
                                                      foreach($cancelled_booking_data as $single_cancelled_booking){  
                                                            foreach($single_cancelled_booking as $booking){
                                                                $table = 'driver_details';
                                                                $select_data = "*";
                                                                $where_data = array
                                                                (
                                                                    'id' => $booking['driver_id'],
                                                                );
                                                                $driver_details = $this->model_web_service->get_table_where($select_data, $where_data, $table);
                                                                if($driver_details && !empty($driver_details[0])){
                                                                $body .= '<tr>';
                                                                $body .= '<td>'.$driver_details[0]['id'].'</td>';
                                                                $table = 'driver_status';
                                                                $select_data = "*";
                                                                $where_data = array
                                                                (
                                                                    'driver_id' => $driver_details[0]['id']
                                                                );
                                                                    $driver_status_details = $this->model_web_service->get_table_where($select_data, $where_data, $table);
                                                                    if($driver_status_details && !empty($driver_status_details[0])){
                                                                    $start_time=$driver_status_details[0]['start_time'];
                                                                    $end_time=$driver_status_details[0]['end_time'];
                                                                    $body .= '<td>'.$start_time.'</td>';
                                                                    $body .= '<td>'.$end_time.'</td>';
                                                                    $body .= '<td>'.$driver_details[0]['user_name'].'</td>';
                                                                    $body .= '<td>'.$driver_details[0]['name'].'</td>';
                                                                    $body .= '<td>'.$driver_details[0]['phone'].'</td>';
                                                                    $body .= '<td>'.$driver_details[0]['email'].'</td>';
                                                                    $body .= '<td>'.$driver_details[0]['license_no'].'</td>';
                                                                    $body .= '<td>'.$driver_details[0]['car_type'].'</td>';
                                                                    $body .= '<td>'.$driver_details[0]['car_no'].'</td>';
                                                                    $body .= '<td>'.$driver_details[0]['status'].'</td>';
                                                                    $body .= '<td>'.$driver_details[0]['flag'].'</td>';
                                                                    $body .= '</tr>';
                                                                    }
                                                                    else{
                                                                        $body .= '<tr><td colspan="10" align="center">Not any driver assigned.</td></tr>';
                                                                    }
                                                                }
                                                                else{
                                                                    $body .= '<tr><td colspan="10" align="center">Not any driver assigned.</td></tr>';
                                                                }
                                                            }    
                                                        }
                                                        $body .= '</table>';
                                                      $response=$this->send_mail($subject,$email,$body);
                                                  }
                                } else {
                                    $getdriverstatus[] = NULL;
                                }
                            }
                        }
                    }
                    $data1['query']=$getdriverstatus;
                    $data1['flagged_drivers']=$this->flagged_drivers_call();
                    if($data1['flagged_drivers']){
                            foreach($data1['flagged_drivers'] as $flag_driver)
                            {
                                    $subject = '(New Flagged Driver ID:'.$flag_driver['driver_id'].') Details.';
                                    $table = 'driver_details';
                                    $select_data = "*";
                                    $where_data = array
                                    (
                                        'id' => $flag_driver['driver_id'],
                                    );
                                    $driver_details = $this->model_web_service->get_table_where($select_data, $where_data, $table);
                                    $this->db->where('driver_id',$flag_driver['driver_id']);
                                    $this->db->order_by('booking_id','desc');
                                    $this->db->limit('5');
                                    $booking_details=$this->db->get('driver_status')->result_array();
                                    $all_affected_bookings=array();
                                    foreach($booking_details as $affected_booking)
                                    {
                                        $table = 'bookingdetails';
                                        $select_data = "*";
                                        $where_data = array
                                        (
                                            'id' => $affected_booking['booking_id'],
                                        );
                                    $affected_booking_details = $this->model_web_service->get_table_where($select_data, $where_data, $table);
                                    $all_affected_bookings[] = $affected_booking_details; 
                                    }
                                    $admin_email=$this->model_web_service->checkadminemail();
                                    $email = $admin_email;
                                    $body = '<p><strong>Driver Details:</strong></p>';
                                    $body .= '<table border="1">';
                                    $body .= '<tr>';
                                    $body .= '<th>Driver ID</th>';
                                    $body .= '<th>Driver User Name</th>';
                                    $body .= '<th>Name</th>';
                                    $body .= '<th>Phone</th>';
                                    $body .= '<th>Email</th>';
                                    $body .= '<th>License No</th>';
                                    $body .= '<th>Car Type</th>';
                                    $body .= '<th>Car No</th>';
                                    $body .= '<th>Is Flagged</th>';
                                    $body .= '</tr>';
                                    foreach($driver_details as $driver_data)
                                    {
                                        $body .= '<tr>';
                                        $body .='<td>'.$driver_data['id'].'</td>';
                                        $body .='<td>'.$driver_data['user_name'].'</td>';
                                        $body .='<td>'.$driver_data['name'].'</td>';
                                        $body .='<td>'.$driver_data['phone'].'</td>';
                                        $body .='<td>'.$driver_data['email'].'</td>';
                                        $body .='<td>'.$driver_data['license_no'].'</td>';
                                        $body .='<td>'.$driver_data['car_type'].'</td>';
                                        $body .='<td>'.$driver_data['car_no'].'</td>';
                                        $body .='<td>'.$driver_data['flag'].'</td>';
                                        $body .= '</tr>';
                                    }
                                    $body .= '</table>';
                                    $body .= '<p><strong>Last 5 Booking Details:</strong></p>';
                                    $body .= '<table border="1">';
                                    $body .= '<tr>';
                                    $body .= '<th>Booking ID</th>';
                                    $body .= '<th>User Name</th>';
                                    $body .= '<th>User ID</th>';
                                    $body .= '<th>Pickup Area</th>';
                                    $body .= '<th>Drop Area</th>';
                                    $body .= '<th>Booking Time</th>';
                                    $body .= '<th>Pickup Time</th>';
                                    $body .= '</tr>';
                                    foreach($all_affected_bookings as $affected_book_data)
                                    {
                                        foreach($affected_book_data as $book_data){
                                            $body .= '<tr>';
                                            $body .='<td>'.$book_data['id'].'</td>';
                                            $body .='<td>'.$book_data['username'].'</td>';
                                            $body .='<td>'.$book_data['user_id'].'</td>';
                                            $body .='<td>'.$book_data['pickup_area'].'</td>';
                                            $body .='<td>'.$book_data['drop_area'].'</td>';
                                            $body .='<td>'.$book_data['book_create_date_time'].'</td>';
                                            $body .='<td>'.$book_data['pickup_date_time'].'</td>';
                                            $body .= '</tr>';
                                        }
                                    }
                                    $body .= '</table>';
                                    $response=$this->send_mail($subject,$email,$body); 
                            }
                    }
                    $data1['flagged_users']=$this->flagged_users_call();
                    if($data1['flagged_users']){
                            foreach($data1['flagged_users'] as $flag_user)
                            {
                                    $subject = '(New Flagged User ID:'.$flag_user['user_id'].') Details.';
                                    $table = 'userdetails';
                                    $select_data = "*";
                                    $where_data = array
                                    (
                                        'id' => $flag_user['user_id'],
                                    );
                                    $user_details = $this->model_web_service->get_table_where($select_data, $where_data, $table);
                                    $this->db->where('user_id',$flag_user['user_id']);
                                    $this->db->order_by('id','desc');
                                    $this->db->limit('2');
                                    $booking_details=$this->db->get('bookingdetails')->result_array();
                                    $admin_email=$this->model_web_service->checkadminemail();
                                    $email = $admin_email;
                                    $body = '<p><strong>User Details:</strong></p>';
                                    $body .= '<table border="1">';
                                    $body .= '<tr>';
                                    $body .= '<th>User ID</th>';
                                    $body .= '<th>User Name</th>';
                                    $body .= '<th>Name</th>';
                                    $body .= '<th>Phone</th>';
                                    $body .= '<th>Email</th>';
                                    $body .= '<th>Is Flagged</th>';
                                    $body .= '</tr>';
                                    foreach($user_details as $user_data)
                                    {
                                        $body .= '<tr>';
                                        $body .='<td>'.$user_data['id'].'</td>';
                                        $body .='<td>'.$user_data['username'].'</td>';
                                        $body .='<td>'.$user_data['name'].'</td>';
                                        $body .='<td>'.$user_data['mobile'].'</td>';
                                        $body .='<td>'.$user_data['email'].'</td>';
                                        $body .='<td>'.$user_data['flag'].'</td>';
                                        $body .= '</tr>';
                                    }
                                    $body .= '</table>';
                                    $body .= '<p><strong>Last 2 Booking Details:</strong></p>';
                                    $body .= '<table border="1">';
                                    $body .= '<tr>';
                                    $body .= '<th>Booking ID</th>';
                                    $body .= '<th>User Name</th>';
                                    $body .= '<th>User ID</th>';
                                    $body .= '<th>Pickup Area</th>';
                                    $body .= '<th>Drop Area</th>';
                                    $body .= '<th>Booking Time</th>';
                                    $body .= '<th>Pickup Time</th>';
                                    $body .= '</tr>';
                                    foreach($booking_details as $book_data){
                                        $body .= '<tr>';
                                        $body .='<td>'.$book_data['id'].'</td>';
                                        $body .='<td>'.$book_data['username'].'</td>';
                                        $body .='<td>'.$book_data['user_id'].'</td>';
                                        $body .='<td>'.$book_data['pickup_area'].'</td>';
                                        $body .='<td>'.$book_data['drop_area'].'</td>';
                                        $body .='<td>'.$book_data['book_create_date_time'].'</td>';
                                        $body .='<td>'.$book_data['pickup_date_time'].'</td>';
                                        $body .= '</tr>';
                                    }
                                    $body .= '</table>';
                                    $response=$this->send_mail($subject,$email,$body); 
                            }
                    }
                }
                else{
                    $data1['query']= NULL;
                }


                /*if($data['query']) {
                    foreach ($data['query'] as $row) {

                        if($row['driver_id']) {
                            $driver_id_array[]=$row['driver_id'];
                            $url = "162.243.225.225:4040/searchDriver?driverId=" . $row['driver_id'] . "&lat=" . $row['pickup_lat'] . "&long=" . $row['pickup_long'] . "&distance=10";
                        }
                        else{
                            $url = "162.243.225.225:4040/searchDriver?driverId=0&lat=" . $row['pickup_lat'] . "&long=" . $row['pickup_long'] . "&distance=10";
                        }

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                        // This is what solved the issue (Accepting gzip encoding)
                        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");
                        $response = curl_exec($ch);
                        curl_close($ch);
                        echo $response;
                        if($response) {
                            $response_data = json_decode($response, true);
                            foreach ($response_data['data'] as $res) {
                                //echo $row['id'];
                                //echo $res['driver_id'];
                                if (isset($res['driver_id'])) {
                                    $ct=$this->home->getbookingidcount($row['id']);
                                    if($ct<5) {
                                        $data['query1'] = $this->home->updatedriverstatus($row['id'], $res['driver_id']);
                                    }
                                    else{
                                        $data['query1']= NULL;
                                    }
                                    if ($data['query1']) {
                                        $getdriverstatus[] = $data['query1'];
                                    }
                                    else{
                                        $getdriverstatus[] = NULL;
                                    }
                                }
                            }
                        }
                    }
                    $data1['query']=$getdriverstatus;
                }
                else{
                    $data1['query']= NULL;
                }*/
                //$this->load->view('fetchbooking_cronjob',$data1);
                //echo json_encode($data1);
    }

    //fetchdriver cronjob call
    public function fetch_driver_cronjob()
    {
                $data['query']=$this->model_web_service->fetchdrivercron();
                if($data['query']) {
                    $this->fetch_booking_cronjob();
                }
                //print_r($data['query']);
    }

    // send mail call
    public function send_mail($subject,$email,$body) {
        $this->load->library('My_PHPMailer');
        $mail = new PHPMailer();
        $mail->IsSMTP(); // we are going to use SMTP
        //$mail->SMTPDebug = 3;
        $mail->SMTPAuth   = true; // enabled SMTP authentication
        $mail->SMTPSecure = "tls";  // prefix for secure protocol to connect to the server
        $mail->Host       = "smtp.gmail.com";      // setting GMail as our SMTP server
        $mail->Port       = 587;                   // SMTP port to connect to GMail
        $mail->Username   = "sarju@techintegrity.in";  // user email address
        $mail->Password   = "Welcometis1";            // password in GMail
        $mail->SetFrom('sarju@techintegrity.in', 'Sarju Tank');  //Who is sending the email
        $mail->AddReplyTo("sarju@techintegrity.in","TIS");  //email address that receives the response
        $mail->Subject    = $subject;
        $mail->Body      = $body;
        $mail->AltBody    = "Driver Unavailable Booking History";
        $admin_email=$this->model_web_service->checkadminemail();
        $destino = $admin_email; // Who is addressed the email to
        $mail->AddAddress($destino, "Receiver");

        //$mail->AddAttachment("images/phpmailer.gif");      // some attached files
        //$mail->AddAttachment("images/phpmailer_mini.gif"); // as many as you want
        if(!$mail->Send()) {
            $data["message"] = "Error: " . $mail->ErrorInfo;
        } else {
            $data["message"] = "Message sent correctly!";
        }
        //print_r($data);
        return $data;
        //$this->load->view('sent_mail',$data);
    }	
	
    public function book_cab()
    {
        //http://v1technology.co.uk/demo/naqil/naqilcom/Source/web_service/book_cab?username=gagaji&pickup_date=15/07/1995&pickup_time=5:30&drop_area=sss&pickup_area=aaaa&taxi_type=nano&time_type=day&amout=360&km=40&purpose=Point%20to%20Point%20Transfer&isdevice=0&pickup_lat=123456&pickup_longs=123456&drop_lat=45678&drop_longs=78945&flag=1&user_id=12&comment=test
        $user_id=(int)$_POST['user_id'];
        $username=$this->input->post('username');
        $pickup_date_time=$this->input->post('pickup_date_time');
         $drop_area=$this->input->post('drop_area');
          $pickup_area=$this->input->post('pickup_area');
           $time_type=$this->input->post('time_type');
            $amount=$this->input->post('amount');
             $km=$this->input->post('km');
              $pickup_lat=$this->input->post('pickup_lat');
               $pickup_longs=$this->input->post('pickup_longs');
                $drop_lat=$this->input->post('drop_lat');
                 $drop_longs=$this->input->post('drop_longs');
                  $isdevice=$this->input->post('isdevice');
                  $approx_time=$this->input->post('approx_time');
                   $flag=$this->input->post('flag');
                    $taxi_type=$this->input->post('taxi_type');
                    $taxi_id=$this->input->post('taxi_id');
                     $purpose=$this->input->post('purpose');
                      $comment=$this->input->post('comment');
                       $person=$this->input->post('person');
                       $payment_type=$this->input->post('payment_type');
                       $transaction_id=$this->input->post('transaction_id');
                       $book_create_date_time=$this->input->post('book_create_date_time');

        /*$url = $_SERVER['REQUEST_URI'];
        $username1 = explode("username=", $url);
        $username2 = $username1[1];
        $username3 = explode('&', $username2);
        $username = urldecode($username3[0]);
        $uneque_id1 = explode('user_id=', $url);
        $uneque_id2 = $uneque_id1[1];
        $uneque_id3 = explode('&', $uneque_id2);
        $uneque_id = urldecode($uneque_id3[0]);
        $pickup_date1 = explode("pickup_date=", $url);
        $pickup_date2 = $pickup_date1[1];
        $pickup_date3 = explode('&', $pickup_date2);
        $pickup_date = urldecode($pickup_date3[0]);

        $time_type1 = explode("time_type=", $url);
        $time_type2 = $time_type1[1];
        $time_type3 = explode('&', $time_type2);
        $time_type = urldecode($time_type3[0]);

        $user_id1 = explode("user_id=", $url);
        $user_id2 = $user_id1[1];
        $user_id3 = explode('&', $user_id2);
        $user_id = urldecode($user_id3[0]);

        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);*/

        //$request->uneaque_id = 'CMC'.strtotime(date('m/d/Y H:i:s'));
        $uneque_id = 'CMC' . strtotime(date('m/d/Y H:i:s'));


        $myDate = new DateTime();
        //$myDate->setTimestamp( strtotime( $request->book_date) );
        $myDate->setTimestamp(strtotime($pickup_date_time));

        $time = $myDate->format("H");

        $query1=$this->db->get('time_detail');
        $row1 = $query1->row();
        if ((float)$time >= $row1->day_end_time || (float)$time <= $row1->day_start_time) {
            //$request->timetype = 'night';
            $time_type = 'night';
        } else {
            //$request->timetype = 'day';
            $time_type = 'day';
        }

        //$request->book_date   =  $myDate->format("m/d/Y");
        $pickup_date = $myDate->format("m/d/Y");
        //$request->pickup_time =  $myDate->format("h:i a");
        $pickup_date = $myDate->format("h:i a");

        //$request->token = $this->extract_token( $request->token );
        /*$uid1 = explode('user_id=', $url);
        $uid2 = $uid1[1];
        $uid3 = explode('&', $uid2);
        $uid = urldecode($uid3[0]);*/

        //$zone_name = 'Asia/Calcutta';
        $date = new DateTime("now", new DateTimeZone($this->zone_name));
        $date->add(new DateInterval('PT10M'));
        $afterTime = $date->format('Y-m-d H:i:s');

        $difference = strtotime( $afterTime ) - strtotime( $pickup_date_time );

        $status = "SELECT * FROM `userdetails` where id='$user_id'";
        $statusrs = mysql_query($status);
        $datastatus = mysql_fetch_array($statusrs);
        $statuschk = $datastatus['user_status'];
        if ($statuschk == 'Active')
        {
            $finresult=[];
            $chk=$this->model_web_service->checklatestbook($user_id);
            if($chk){
                $finresult = array('status' => 'failed',
                    'message' => 'We have received your booking recently and found this could be a duplicate of previous one. please try after some time.',
		    'error code' => '22',
                    'Isactive' => $statuschk,
                    'code' => 'Invalid'
                );
            }
            else{
                $this->model_web_service->book($user_id,$username,$pickup_date_time,$drop_area,$pickup_area,$time_type,$amount,$km,$pickup_lat,$pickup_longs,$drop_lat,$drop_longs,$isdevice,$approx_time,$flag,$taxi_type,$taxi_id,$purpose,$comment,$person,$payment_type,$transaction_id,$book_create_date_time);

                $query = $this->db->query("SELECT * FROM `bookingdetails` ORDER BY `id` DESC LIMIT 1 ");
                $result = $query->result_array();
                $finresult = array('status' => 'success',
                    'message' => 'Successfully Booking',
                    'Isactive' => $statuschk,
                    'code' => 'Booking'
                );
                $finresult['bookingdetails'] = $result;
                if($book_create_date_time == 'now' || $book_create_date_time == 'NOW' || $book_create_date_time == 'Now' || $difference>=0){
                        $this->fetch_booking_cronjob();
                }   
            }
            print json_encode($finresult);
        }
        else if($statuschk =='Inactive')
        {
                $finresult['status']='false';
                $finresult['message']='Your account has been temporarily locked. Please contact our admin for further details.';
		$finresult['error code']='1';
                $finresult['Isactive']=$statuschk;
                echo json_encode($finresult);
        }
        else
        {
                $finresult = array('status' => 'false',
                    'message' => 'Fail Booking',
		    'error code' => 23,
		            'Isactive' => $statuschk,
                    'code' => 'Not Booking'
                );
                print json_encode($finresult);
        }


    }
    public function book_cabold()
    {
        //http://v1technology.co.uk/demo/naqil/naqilcom/Source/web_service/book_cab?username=test&user_id=227&pickup_date_time=2016-07-12%2010:57:00&drop_area=sss&pickup_area=aaaa&taxi_type=nano&time_type=day&amount=360&km=40&purpose=Point%20to%20Point%20Transfer&comment=hello&pickup_lat=123&pickup_longs=123456&drop_lat=123456&drop_longs=456&flag=0&person=1&payment_type=case&isdevice=1&transaction_id=123456789
        $url = $_SERVER['REQUEST_URI'];
        $username1 = explode("username=", $url);
        $username2 = $username1[1];
        $username3 = explode('&', $username2);
        $username = urldecode($username3[0]);
        $uneque_id1 = explode('uneaque_id=', $url);
        $uneque_id2 = $uneque_id1[1];
        $uneque_id3 = explode('&', $uneque_id2);
        $uneque_id = urldecode($uneque_id3[0]);
        $pickup_date1 = explode("pickup_date=", $url);
        $pickup_date2 = $pickup_date1[1];
        $pickup_date3 = explode('&', $pickup_date2);
        $pickup_date = urldecode($pickup_date3[0]);
        
        $time_type1 = explode("time_type=", $url);
        $time_type2 = $time_type1[1];
        $time_type3 = explode('&', $time_type2);
        $time_type = urldecode($time_type3[0]);
			
	
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        //$request->uneaque_id = 'CMC'.strtotime(date('m/d/Y H:i:s'));
        $uneque_id = 'CMC' . strtotime(date('m/d/Y H:i:s'));


        $myDate = new DateTime();
        //$myDate->setTimestamp( strtotime( $request->book_date) );
        $myDate->setTimestamp(strtotime($pickup_date));

        $time = $myDate->format("H");

        if ($time >= 22 || $time <= 6) {
            //$request->timetype = 'night';
            $time_type = 'night';
        } else {
            //$request->timetype = 'day';
            $time_type = 'day';
        }

        //$request->book_date   =  $myDate->format("m/d/Y");
        $pickup_date = $myDate->format("m/d/Y");
        //$request->pickup_time =  $myDate->format("h:i a");
        $pickup_date = $myDate->format("h:i a");

        //$request->token = $this->extract_token( $request->token );
	
        $this->model_web_service->book($request);
	$query =$this->db->query("SELECT * FROM `bookingdetails` ORDER BY `id` DESC LIMIT 1 ");
       $result = $query->result_array();
	 $finresult = array('status' => 'success', 'message' => 'Successfully Booking',
            'code' => 'Booking'
        );
	$finresult['bookingdetails']= $result;
        print json_encode($finresult);
    	}
	

    function token_gen($item)
    {
        // $token = array();
        // $token['id'] = 1;
        return JWT::encode($item, APP_SECRET_KEY);
    }

    function extract_token($item)
    {
        $token = JWT::decode($item, APP_SECRET_KEY);
        //$token = 'AIzaSyACM3HdH130A55-6pP1eQDkyi69USk1nHo';
        return $token;
    }

    public function settings()
    {
        $result = $this->model_web_service->load_settings();
        print json_encode($result[0]);
    }

    /*Shajeer Callmycab driver app starts here*/
    public function driver_login()
    {
        //http://192.168.1.3/gagaji/WebApp/Source/web_service/driver_login?username=sha@gmail.com&password=sha123
        //$postdata = file_get_contents("php://input");
        //$request = json_decode($postdata);
        $params['username']=$this->input->get('username');
        $params['password']=$this->input->get('password');
    	//$params = json_decode(file_get_contents('php://input'), TRUE);
    	$username = $params['username'];
    	$password = $params['password'];
        $result = $this->model_web_service->driverlogin($username,$password);

        if ($result)
		{
		    $table = 'driver_details';
            $select_data = "*";

            $this->db->select($select_data);
            $this->db->where("user_name",$username);
            $this->db->where("status","Active");
            $query = $this->db->get($table);  //--- Table name = User
            $driver_detatil = $query->result_array();
            if($driver_detatil)
            {
                $table_setting_details ='settings';
                $result_setting = $this->model_web_service->get_table('country,currency',  $table_setting_details);

                $finresult['status']='success';
                $finresult['message']='Successfully Logged in';
                $finresult['code']='success';
                $finresult['Driver_detail']=$driver_detatil;
                $finresult['country_detail']=$result_setting;   
            }
            else
            {
                $finresult['status']='false';
                $finresult['message']='Your account has been temporarily locked. Please contact our admin for further details.';
		$finresult['error code']='1';
                $finresult['Isactive']='Inactive';   
            }
            echo json_encode($finresult);
        } else {
           // $finresult[] = array('status' => 'failed', 'message' => 'Unknown credential , please try again!', 'code' => 'Login failed',
//
 //           );
  //          print json_encode($finresult);
			$finresult['status']='failed';
            $finresult['message']='Please enter correct login details';
	    $finresult['error code']='2';
            $finresult['code']='Login failed';           
            echo json_encode($finresult);
        }

        //var_dump($request);
    }

 public function car_type()
    {
//http://v1technology.co.uk/demo/naqil/naqilcom/Source/web_service/car_type
        $table = 'cabdetails';
        $select_data = "*";
        $this->db->select($select_data);
        $query = $this->db->get($table);
        $result = $query->result_array();
        if ($result) {

            $finresult['status']='success';

            //$finresult['userdetail']=$result_details;
            $finresult['Car_Type']=$result;

            echo json_encode($finresult);
        }
        else
        {
            $finresult['status']='failed';
            $finresult['message']='No Data Found';
	    $finresult['error code']='16';
            print json_encode($finresult);
        }
    }
    public function driver_sign_up()
    {
        // to check in local
        /*$url=$_REQUEST['data'];
        $params=json_decode($url,TRUE);
       	$username= $params['username'];
        $email= $params['email'];
        $phone= $params['phone'];
        $password= $params['password'];
        $name= $params['name'];
        $license_no= $params['license_no'];
        $Lieasence_Expiry_Date= $params['Lieasence_Expiry_Date'];
        $license_plate= $params['license_plate'];
        $Insurance= $params['Insurance'];
        $Seating_Capacity= $params['Seating_Capacity'];
        $Car_Model= $params['Car_Model'];
        $Car_Make= $params['Car_Make'];
        $car_no= $params['Car_no'];
        $car_type= $params['car_type'];
        $address= $params['address'];
        $gender= $params['gender'];
        $dob= $params['dob'];*/

        /*$params = json_decode(file_get_contents('php://input'), TRUE);
        $username = $params['username'];
        $email = $params['email'];*/

        $username= $this->input->post('username');
        $email= $this->input->post('email');
        $phone= $this->input->post('phone');
        $password= $this->input->post('password');
        $name= $this->input->post('name');
        $license_no= $this->input->post('license_no');
        $Lieasence_Expiry_Date= $this->input->post('Lieasence_Expiry_Date');
        $license_plate= $this->input->post('license_plate');
        $Insurance= $this->input->post('Insurance');
        $Seating_Capacity= $this->input->post('Seating_Capacity');
        $Car_Model= $this->input->post('Car_Model');
        $Car_Make= $this->input->post('Car_Make');
        $car_no= $this->input->post('car_no');
        $car_type= $this->input->post('car_type');
        $address= $this->input->post('address');
        $gender= $this->input->post('gender');
        $dob= $this->input->post('dob');
        $img=$_FILES['image'];

        /*$json_array=array(
        	'name'=> $name,
        	'username'=>$username,
        	'phone'	=>$phone,
        	'email'=>$email,
        	'password'=>$password,
        	'dob'=>$dob,
        	'gender'=>$gender,
        	'address'=>$address,
        	'Car_Make'=>$Car_Make,
        	'Car_Model'=>$Car_Model,
        	'car_type'=>$car_type,
        	'car_no'=>$car_no,
        	'Seating_Capacity'=>$Seating_Capacity,
        	'license_no'=>$license_no,
        	'Lieasence_Expiry_Date'=>$Lieasence_Expiry_Date,
        	'license_plate'=>$license_plate,
        	'Insurance'=>$Insurance
        	);
        echo json_encode($json_array);*/

        $already_exist = $this->model_web_service->driver_id_exist($email,$username);
        $car_no_exist = $this->model_web_service->driver_car_no_exist($car_no,$uid="");
        $license_no_exist = $this->model_web_service->driver_license_no_exist($license_no,$uid="");
        $phone_exist = $this->model_web_service->driver_phone_no_exist($phone,$uid="");
        //print_r($already_exist);
        if ($already_exist)
        {   
            $finresult = array(
                'status' => 'failed',	
		'error code' => '17',
                'message' => 'Email id already exists'
            );
            print json_encode($finresult); 
        }
        else if($car_no_exist)
	    {
	    	$finresult = array(
	                'status' => 'failed',
			'error code' => '19',
	                'message' => 'Vehicle number already exists'
	            );
	            print json_encode($finresult);
	    }
	    else if($license_no_exist)
	    {
	    	$finresult = array(
	                'status' => 'failed',
			'error code' => '20',
	                'message' => 'License number already exists'
	            );
	            print json_encode($finresult); 
	    }
	    else if($phone_exist)
	    {
	    	$finresult = array(
	                'status' => 'failed',
			'error code' => '21',
	                'message' => 'Mobile number already exists'
	            );
	            print json_encode($finresult); 
	    }
        else
        {
        	if($img !='') 
			{
				$imgflag=$this->upload_image($img,"driverimages/","","driver");
			}
			else
			{
				$imgflag='0';
			}
                $driver_register = $this->model_web_service->driver_sign_up_model($username, $email, $phone, $password, $name, $license_no, $Lieasence_Expiry_Date, $license_plate, $Insurance, $Seating_Capacity, $Car_Model, $Car_Make, $car_no, $car_type, $address, $gender,$dob,$imgflag);

                $table = 'driver_details';
                $select_data = "*";

                $this->db->select($select_data);
                $this->db->where("email",$email);
                $query = $this->db->get($table);  //--- Table name = User
                $driver_detatil = $query->result_array();
                //$table_setting_details ='settings';
                //$result_setting = $this->model_web_service->get_table('country,currency',  $table_setting_details);
                 $subject = 'Naqilcom New Driver Registration.';
                 $admin_email=$this->model_web_service->checkadminemail();
				 $send_email = $admin_email;
                 $email_body ='<div style="background-color: #00bcd4; color: #0b0b0b;">
     			<table style="background-color:#292A6B;border:1px solid #20264a;padding:10px;font-family:Verdana;font-size:12px" width="100%"><tbody><tr><td><img class="CToWUd" src="http://138.68.5.43/upload/logo.png" style="min-height:25%;width:25%"></td></tr><tr><td>&nbsp;</td></tr><tr> <td> <table style="padding:10px;font-size:12px;background-color:#fff;border:1px solid #2d62ac" cellpadding="5" width="100%"> <tbody> <tr><td colspan="4">&nbsp;</td></tr> <tr><td colspan="4" style="font-family:Verdana,Geneva,sans-serif;font-size:12px;text-align:left"> Hey, '.$email.'</td></tr> <tr><td colspan="4" style="font-family:Verdana,Geneva,sans-serif;font-size:12px;text-align:left"> <br><br> Your Email_id: '.$email.' and new Your Password: '.$password.' for Naqil are as below. </td></tr> <tr> <td colspan="4"> <table style="font-family:Verdana,Geneva,sans-serif;font-size:12px;width:600px;border-collapse:collapse" height="30"> <tbody> <tr> <th style="border:1px solid #808080!important;font-size:1.1em;text-align:left;padding-top:5px;padding-left:10px;padding-bottom:5px;background-color:#cdcdcd!important;color:#000000!important" width="20%">Email</th> <th style="border:1px solid #808080!important;font-size:1.1em;text-align:left;padding-top:5px;padding-left:10px;padding-bottom:5px;background-color:#cdcdcd!important;color:#000000!important" width="35%">'.$email.'</th> </tr> <tr> <th style="border:1px solid #808080!important;font-size:1.1em;text-align:left;padding-top:5px;padding-left:10px;padding-bottom:5px;background-color:#cdcdcd!important;color:#000000!important" width="20%">Password</th> <th style="border:1px solid #808080!important;font-size:1.1em;text-align:left;padding-top:5px;padding-left:10px;padding-bottom:5px;background-color:#cdcdcd!important;color:#000000!important" width="35%">'.$password.'</th> </tr> <tr><td colspan="4" style="font-family:Verdana,Geneva,sans-serif;font-size:12px;text-align:left"></td></tr> <tr><td colspan="4" style="font-family:Verdana,Geneva,sans-serif;font-size:12px;text-align:left"><br><br> If you have any questions please contact <a href="mailto:pollvaultadm@gmail.com" target="_blank">sarju@techintegrity.in</a>. <br><br><br>Thanks. </td></tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody></table>
     			</div>';
                 //$message_email =  preg_replace("{email}",$email,$email_body);
                 //$message =  preg_replace("/\{[^}]+\/",'$email',$message_email1);
                 //$message =  preg_replace("{password}",$password,$message_email);
		 	//$message1 =  str_replace(array('{','}'),'',$message);
                 //$this->email->from('sarju@techintegrity.in', 'sarju tank');

                 //$list = array('sarju@techintegrity.in');
                 //$this->email->to($list);
                 //$this->email->subject('Driver Register Sucessfully');
                 //$this->email->message($message1);
     			$response=$this->send_mail($subject,$send_email,$email_body);
                $table_setting_details ='settings';
                $result_setting = $this->model_web_service->get_table('country,currency',  $table_setting_details);
                 //$this->email->send();
                 // $this->email->print_debugger();
                /*if ($this->email->send())
                 {*/
                     //echo 'Email sent.';*/
                $finresult = array('status' => 'success', 'message' => 'Successfully Signed up', 'code' => 'success', 'Mail' => 'mail Send Sucessfully','Driver_detail' => $driver_detatil,'country_detail' => $result_setting/*,'cabDetails' => $result_cab_details*/);

                print json_encode($finresult);
           
            	/*}
          		else
            	{
                	show_error($this->email->print_debugger());
          		}*/
        }
    }

 function upload_image($files, $dir, $oldfile ,$prefix)
    {
        //print_r($files);
        if($files[tmp_name]!='')
        {
            if (!is_dir ($dir))
            {
                mkdir($dir,0777);
                chmod($dir,0777);
            }

            if ($oldfile != "" && is_file($dir.$oldfile))
            {
                unlink($dir.$oldfile);
            }

            $filename = $prefix."".rand(0,999999999999)."-".$files[name];

            if (is_file($dir.$filename))
                $filename = $prefix."".rand(0,999999999999)."-".rand(0,999999999999)."-".$files[name];

            if (@move_uploaded_file($files[tmp_name],$dir.$filename))
                return $filename;
            else
                return false;
        }
    }

	public function driver_bookings()
    {
        //http://v1technology.co.uk/demo/naqil/naqilcom/Source/web_service/load_trips?user_id=223&off=
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $myDate = new DateTime();

        $current_date = $myDate->format("m/d/Y");
        $url = $_SERVER['REQUEST_URI'];
        $id1 = explode('driver_id=', $url);
        $id2 = $id1[1];
        $id3 = explode('&', $id2);
        $id = urldecode($id3[0]);

        $off1 = explode('off=', $url);
        $off2 = $off1[1];
        $off3 = explode('&', $off2);
        $off = urldecode($off3[0]);

        $lim = 10;
        if ($off == '' || $off == '0')
        {
            $off = 0;
        }
        $perpageTmp = $off;
        $perpage = '';
        if ($perpageTmp != '')
        {
            $perpage = $perpageTmp;
        }
        else
        {
            $perpage = 10;
        }


        //$zone_name = 'Asia/Calcutta';
        $date = new DateTime("now", new DateTimeZone($this->zone_name));
        $server_time = $date->format('Y-m-d H:i:s');
       //$sql="SELECT * FROM `bookingdetails` b INNER JOIN cabdetails c on b.taxi_type=c.cartype WHERE b.assigned_for=$id ORDER by b.id DESC LIMIT $off,$lim";
        $sql="SELECT * FROM `bookingdetails` b INNER JOIN cabdetails c on b.taxi_type=c.cartype INNER JOIN `driver_status` d on b.id=d.booking_id WHERE d.driver_id='$id' ORDER BY d.start_time";
        $qry=mysql_query($sql);
        $num = mysql_num_rows($qry);
        if ($num > 0) 
        {
            $sql .= " DESC LIMIT $off,$lim";
            $qry =mysql_query($sql);
            $new_count=mysql_num_rows($qry);
            $new_off = $off + $new_count;
            $succArr = 1;
            $i = 0;
            while ($row = mysql_fetch_array($qry))
            {
                $table = 'cabdetails';
                $select_data = "*";
                $where_data = array
                (
                    'cartype' => $row['taxi_type'],
                );
                $cab_details = $this->model_web_service->get_table_where($select_data, $where_data, $table);
                $per_minute_rate = $cab_details[0]['ride_time_rate'];
                $night_per_minute_rate = $cab_details[0]['night_ride_time_rate'];
                
                $fields[$i][id] = $row['booking_id'];
                $fields[$i][start_time] = $row['start_time'];
                $fields[$i][end_time] = $row['end_time'];
                $fields[$i][server_time] = $server_time;
                $fields[$i][driver_flag] = $row['driver_flag'];
                $fields[$i][username] = $row['username'];
                $fields[$i][user_id] = $row['user_id'];
                $fields[$i][pickup_date] = $row['pickup_date'];
                $fields[$i][pickup_area] = $row['pickup_area'];
                $fields[$i][drop_area] = $row['drop_area'];
                $fields[$i][pickup_time] = $row['pickup_time'];
                $fields[$i][pickup_date_time] = $row['pickup_date_time'];
                $fields[$i][area] = $row['area'];
                $fields[$i][landmark] = $row['landmark'];
                $fields[$i][pickup_address] = $row['pickup_address'];
                $fields[$i][taxi_type] = $row['taxi_type'];
                $fields[$i][taxi_id] = $row['taxi_id'];
                $fields[$i][departure_time] = $row['departure_time'];
                $fields[$i][departure_date] = $row['departure_date'];
                $fields[$i][return_date] = $row['return_date'];
                $fields[$i][flight_number] = $row['flight_number'];
                $fields[$i][package] = $row['package'];
                $fields[$i][status] = $row['status'];
                $fields[$i][promo_code] = $row['promo_code'];
                $fields[$i][payment_type] = $row['payment_type'];
                $fields[$i][book_create_date_time] = $row['book_create_date_time'];
                $fields[$i][create_date_time] = $row['create_date_time'];
                $fields[$i][distance] = $row['distance'];
                $fields[$i][isdevice] = $row['isdevice'];
                $fields[$i][approx_time] = $row['approx_time'];
                if($row['status']==9){
                    $fields[$i][amount] = $row['final_amount'];
                }
                else{
                    $fields[$i][amount] = $row['amount'];
                }
                $fields[$i][address] = $row['address'];
                $fields[$i][transfer] = $row['transfer'];
                $fields[$i][assigned_for] = $row['assigned_for'];
                $fields[$i][item_status] = $row['item_status'];
                $fields[$i][transaction] = $row['transaction'];
                $fields[$i][km] = $row['km'];
                $fields[$i][timetype] = $row['timetype'];
                $fields[$i][comment] = $row['comment'];
                $fields[$i][driver_status] = $row['driver_status'];
                $fields[$i][pickup_lat] = $row['pickup_lat'];
                $fields[$i][pickup_longs] = $row['pickup_long'];
                $fields[$i][drop_lat] = $row['drop_lat'];
                $fields[$i][drop_longs] = $row['drop_long'];
                $fields[$i][flag] = $row['flag'];
                $fields[$i][car_id] = $row['car_id'];
                $fields[$i][car_type] = $row['cartype'];
                if($row['timetype']=='day'){
                    $fields[$i]['per_minute_rate']=$per_minute_rate;
                }
                else{
                    $fields[$i]['per_minute_rate']=$night_per_minute_rate;
                }
                $fields[$i][icon] = $row['icon'];
                $fields[$i][seat_capacity] = $row['seat_capacity'];
                $user_id= $row['user_id'];
                $sql1="SELECT * from userdetails WHERE id=$user_id";
                $qry1=mysql_query($sql1);
                $rows=mysql_num_rows($qry1);
                if($rows!=0) {
                    while ($data = mysql_fetch_array($qry1)) {
                        $fields[$i]['user_detail'] = array(
                            'id' => $data['id'],
                            'name' => $data['name'],
                            'username' => $data['username'],
                            'mobile' => $data['mobile'],
                            'address' => $data['address'],
                            'email' => $data['email'],
                            'gender' => $data['gender'],
                            'dob' => $data['dob'],
                            'pickupadd' => $data['pickupadd'],
                            'wallet_amount' => $data['wallet_amount'],
                            'device_id' => $data['device_id'],
                            'facebook_id' => $data['facebook_id'],
                            'twitter_id' => $data['twitter_id'],
                            'isdevice' => $data['isdevice'],
                            'image' => $data['image'],

                        );
                    }
                }
                else
                {
                    $fields[$i]['user_detail'] = '';
                }

                $i++;
            }
        }
        $status="SELECT * FROM `driver_details` where id='$id'";
        $statusrs=mysql_query($status);
        $datastatus=mysql_fetch_array($statusrs);
        $statuschk=$datastatus['status'];
        if($num!=0 && $new_off<=$num && $statuschk =='Active')
        {
            if($fields){
                $finresult['status']='success';
                $finresult['offset']=$new_off;
                $finresult['Count']=$num;
                $finresult['Isactive']=$statuschk;
                $finresult['all_trip']=$fields;
            }
            else{
                $finresult['status']='failed';
                $finresult['message']='Data Not Found';
		$finresult['error code']='16';
            }
            echo json_encode($finresult);

            //'booking' => $booking,
            //'Cancelled' => $Cancelled,
            //'Completed' => $success,
        }
        else if($statuschk =='Inactive')
        {
            $finresult['status']='false';
            $finresult['message']='Your account has been temporarily locked. Please contact our admin for further details.';
	    $finresult['error code']='1';
            $finresult['Isactive']=$statuschk;
            echo json_encode($finresult);
        }
        else
        {
            $finresult['status']='failed';
            $finresult['message']='Data Not Found';
	    $finresult['error code']='15';
            echo json_encode($finresult);

        }

    }
    public function driver_bookingsolds()
    {
        //http://v1technology.co.uk/demo/naqil/naqilcom/Source/web_service/driver_bookings?driver_id=11&off=
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $myDate = new DateTime();

        $current_date = $myDate->format("m/d/Y");
	 $url = $_SERVER['REQUEST_URI'];
        $id1 = explode('driver_id=', $url);
        $id2 = $id1[1];
        $id3 = explode('&', $id2);
        $id = urldecode($id3[0]);

        $off1 = explode('off=', $url);
        $off2 = $off1[1];
        $off3 = explode('&', $off2);
        $off = urldecode($off3[0]);

        $lim = 10;
        if ($off == '' || $off == '0')
        {
            $off = 0;
        }
        //$result = $this->model_web_service->load_trips($request);
	$this->db->select('*');
        $this->db->from('bookingdetails');
        $this->db->join('cabdetails', 'cabdetails.cartype = bookingdetails.taxi_type');
        $this->db->where('bookingdetails.assigned_for', $id);
        $this->db->order_by("bookingdetails.id", "desc");
        $this->db->limit($lim,$off);
        $query = $this->db->get();

        $result = $query->result_array();
 	$result = $query->result_array();

        $perpageTmp = $off;
        $perpage = '';
        if ($perpageTmp != '') 
	{
            $perpage = $perpageTmp;
        } 
	else 
	{
            $perpage = 10;
        }

        $num = $query->num_rows();

        $new_off = $off + $num;
	
        $success = array();
        $booking = array();
        $Cancelled = array();
        foreach ($result as $item) {
            if ($item['status'] == '4') {
                $success[] = $item;
            }
            else if ($item['status'] == '1') {
                $booking[] = $item;
            }
            else if ($item['status'] == '2') {
                $booking[] = $item;
            }
            else if ($item['status'] == '3') {
                $Cancelled[] = $item;
            }
        }
        if($result)
        {
            $finresult['status']='success';
	    $finresult['offset']=$new_off;
            $finresult['all_trip']=$result;

            //'booking' => $booking,
            //'Cancelled' => $Cancelled,
            //'Completed' => $success,
        }
        else
        {
            $finresult['status']='failed';
	    
            $finresult['all_trip']='Data Not Found';
	    $finresult['error code']='15';

        }
        print json_encode($finresult);

    }

    /*Fetch data ride rate
    sends JSON as Post data, which holds the entire row data of current booking
    */
    public function getRide_rate()
    {
        //http://192.168.1.18/gagaji/WebApp/Source/web_service/getRide_rate?uneaque_id=1&taxi_type=Sedan&timetypes=day&transfertype=Outstation%20Transfer
        $url = $_SERVER['REQUEST_URI'];
        $uneaque_id1 = explode('uneaque_id=', $url);
        $uneaque_id2 = $uneaque_id1[1];
        $uneaque_id3 = explode('&', $uneaque_id2);
        $uneaque_id = urldecode($uneaque_id3[0]);
        $transfertype1 = explode('transfertype=', $url);
        $transfertype2 = $transfertype1[1];
        $transfertype3 = explode('&', $transfertype2);
        $transfertype = urldecode($transfertype3[0]);
        $taxi_type1 = explode('taxi_type=', $url);
        $taxi_type2 = $taxi_type1[1];
        $taxi_type3 = explode('&', $taxi_type2);
        $taxi_types = urldecode($taxi_type3[0]);
        $timetype1 = explode('timetypes=', $url);
        $timetype2 = $timetype1[1];
        $timetype3 = explode('&', $timetype2);
        $timetypes = urldecode($timetype3[0]);
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        //$booking_id= $request->uneaque_id;
        $booking_id = $uneaque_id;

        //$purpose=$request->purpose;
        $purpose = $transfertype;
        //$taxi_type=$request->taxi_type;
        $taxi_type = $taxi_types;
        //$package=$request->package;
        $package = '';
        //$transfer=$request->transfer;
        $transfer = $transfertype;
        //$timetype=$request->timetype;
        $timetype = $timetypes;

        $table = 'cabdetails';
        $select_data = "";
        // echo $transfer;

        if ($purpose == "Point to Point Transfer") {
            $select_data = "intialkm,intailrate,standardrate";
        }

        if ($purpose == "Airport Transfer") {

            if ($transfer == "going") {
                $select_data = "intialkm,intailrate,standardrate";
            }

            if ($transfer == "coming") {
                $select_data = "fromintialkm,fromintailrate,fromstandardrate";
            }
        }

        if ($purpose == "Outstation Transfer") {

            if ($transfer == "oneway") {
                $select_data = "standardrate";
            }

            if ($transfer == "round") {
                $select_data = "fromstandardrate";
            }
        }

        if ($purpose == "Hourly Rental") {
            $select_data = "standardrate";
        }

        $where_con = array();

        if ($purpose != "") {
            $where_con = array_merge($where_con, array('transfertype' => $purpose));
        }
        if ($taxi_type != "") {
            $where_con = array_merge($where_con, array('cartype' => $taxi_type));
        }
        if ($taxi_type != "") {
            $where_con = array_merge($where_con, array('cartype' => $taxi_type));
        }
        if ($package != "") {
            $where_con = array_merge($where_con, array('package' => $package));
        }
        if ($timetype != "") {
            $where_con = array_merge($where_con, array('timetype' => $timetype));
        }

        $this->db->select($select_data);
        $this->db->where($where_con);
        $query = $this->db->get($table);
        $result = $query->result_array();
        if (count($result) > 0) {
            $final_result = array(
                'status' => "success",
                'purpose' => $purpose,
                'transfer_tyepe' => $transfer,
                'booking_id' => $booking_id,
                'raw_data' => $result
            );
        } else {
            $final_result = array(
                'status' => "failed",
		'error code' => "24",
                'message' => "No fields exist"
            );
        }

        print json_encode($final_result);

    }

    public function update_driver_pwd()
    {
        //http://192.168.1.3/gagaji/WebApp/Source/web_service/update_driver_pwd?username=sha@gmail.com&Password=sha123&uid=48

        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        //$request->token = $this->extract_token( $request->token );

        $result = $this->model_web_service->update_driver_password($request);

        if ($result == 1) {
            $finresult = array('status' => 'success',);
        } else {
            $finresult = array('status' => 'fail',);
        }


        print json_encode($finresult);
    }



    public function set_ride_as_complete()
    {
        //http://192.168.1.3/gagaji/WebApp/Source/web_service/set_ride_as_complete?book_id=779
        $url = $_SERVER['REQUEST_URI'];
        $book_id1 = explode('book_id=', $url);
        $book_id2 = $book_id1[1];
        $book_id3 = explode('&', $book_id2);
        $book_id = urldecode($book_id3[0]);

        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $table = "bookingdetails";
        $data = array(
            'status' => '2'
        );

        //$this->db->where('uneaque_id', $request->current_ride_id);
        //$this->db->where('uneaque_id','CMC1447321810');
        $this->db->where('id', $book_id);
        $result = $this->db->update($table, $data);
        if (1) 
	{

            //$d = array();
            $d = array('Complate' => "sucessfully", 'status' => "sucess");
            $json = json_encode($d);
            print_r($json);
        } else {
            $d = array('Complate' => "false", 'status' => "fail");
            $json = json_encode($d);
            print_r($json);
        }


    }

    // fetch driver statistics api call
    public function fetch_driver_statistics()
    {
        // get completed booking call
        echo 'PARTICULARS<br/>###############################################<br/><br/>';
        $data['query']=$this->model_web_service->getcompletedbookings($this->input->get('driver_id'));
        if($data['query']) {
            $sum_amount[]=0;
            foreach ($data['query'] as $row) {
                $sum_amount[]=(int)$row['final_amount'];
                echo '-----------------------------<br/>';
                echo 'DRIVER ID: ' . $row['driver_id'] . '<br/>';
                echo 'BOOKING ID: ' . $row['booking_id'] . '<br/>';
                echo 'FINAL AMOUNT: ' . $row['final_amount'] . '<br/>';
            }
            echo '<br/>SUM AMOUNT: '.array_sum($sum_amount);
        }
        else{
            echo '<br/>NO DATA<br/>';
        }

        // get total earnings call
        echo '<br/><br/>TOTAL EARNINGS<br/>###############################################<br/><br/>';
        $data['query1']=$this->model_web_service->gettotalearnings($this->input->get('driver_id'));
        if($data['query1']) {
            foreach ($data['query1'] as $row1) {
                echo '-----------------------------<br/>';
                echo 'DRIVER ID: ' . $row1['driver_id'] . '<br/>';
                echo 'TOTAL EARNINGS: ' . $row1['sum_amount'] . '<br/><br/>';
            }
        }
        else{
            echo '<br/>NO DATA<br/>';
        }

        // get last month earnings call
        echo '<br/><br/>LAST MONTH EARNINGS<br/>###############################################<br/><br/>';
        $data['query2']=$this->model_web_service->getlastmonthdriverstats($this->input->get('driver_id'));
        if($data['query2']) {
            foreach ($data['query2'] as $row2) {
                echo '-----------------------------<br/>';
                echo 'DRIVER ID: ' . $row2['driver_id'] . '<br/>';
                echo 'TOTAL EARNINGS: ' . $row2['sum_amount'] . '<br/><br/>';
            }
        }
        else{
            echo '<br/>NO DATA<br/>';
        }

        // get last week earnings call
        echo '<br/><br/>LAST WEEK EARNINGS<br/>###############################################<br/><br/>';
        $data['query3']=$this->model_web_service->getlastweekdriverstats($this->input->get('driver_id'));
        if($data['query3']) {
            foreach ($data['query3'] as $row3) {
                echo '-----------------------------<br/>';
                echo 'DRIVER ID: ' . $row3['driver_id'] . '<br/>';
                echo 'TOTAL EARNINGS: ' . $row3['sum_amount'] . '<br/><br/>';
            }
        }
        else{
            echo '<br/>NO DATA<br/>';
        }

        // get total rides call
        echo '<br/><br/>TOTAL RIDES<br/>###############################################<br/><br/>';
        $data['query4']=$this->model_web_service->gettotalrides($this->input->get('driver_id'));
        if($data['query4']) {
            foreach ($data['query4'] as $row4) {
                $total_rides[]=$row4['total_rides'];
                echo '-----------------------------<br/>';
                echo 'DRIVER ID: ' . $row4['driver_id'] . '<br/>';
                echo 'BOOKING ID: ' . $row4['booking_id'] . '<br/>';
                echo 'RIDE: ' . count($total_rides) . '<br/><br/>';
            }
            echo 'TOTAL RIDES: ' . count($total_rides) . '<br/><br/>';
        }
        else{
            echo '<br/>NO DATA<br/>';
        }

        //get last month rides call
        echo '<br/><br/>LAST MONTH RIDES<br/>###############################################<br/><br/>';
        $data['query5']=$this->model_web_service->getlastmonthrides($this->input->get('driver_id'));
        if($data['query5']) {
            foreach ($data['query5'] as $row5) {
                $last_month_rides[]=$row5['total_rides'];
                echo '-----------------------------<br/>';
                echo 'DRIVER ID: ' . $row5['driver_id'] . '<br/>';
                echo 'BOOKING ID: ' . $row5['booking_id'] . '<br/>';
                echo 'RIDE: ' . count($last_month_rides) . '<br/><br/>';
            }
            echo 'LAST MONTH RIDES: ' . count($last_month_rides) . '<br/><br/>';
        }
        else{
            echo '<br/>NO DATA<br/>';
        }

        //get last week rides call
        echo '<br/><br/>LAST WEEK RIDES<br/>###############################################<br/><br/>';
        $data['query6']=$this->model_web_service->getlastweekrides($this->input->get('driver_id'));
        if($data['query6']) {
            foreach ($data['query6'] as $row6) {
                $last_week_rides[]=$row6['total_rides'];
                echo '-----------------------------<br/>';
                echo 'DRIVER ID: ' . $row6['driver_id'] . '<br/>';
                echo 'BOOKING ID: ' . $row6['booking_id'] . '<br/>';
                echo 'RIDE: ' . count($last_week_rides) . '<br/><br/>';
            }
            echo 'LAST WEEK RIDES: ' . count($last_week_rides) . '<br/><br/>';
        }
        else{
            echo '<br/>NO DATA<br/>';
        }

        //get accepted vs rejected rides call
        echo '<br/><br/>ACCEPTED VS REJECTED RIDES RATIO<br/>###############################################<br/><br/>';
        $data['query7']=$this->model_web_service->getridesratio($this->input->get('driver_id'),count($total_rides));
        if($data['query7']) {
            foreach ($data['query7'] as $row7) {
                echo '-----------------------------<br/>';
                echo 'RATIO: ' . number_format($row7,2) . '%<br/>';
            }
        }
        else{
            echo '<br/>NO DATA<br/>';
        }
    }

    // fetch flagged drivers call
    public function flagged_drivers_call()
    {
        $result=$this->model_web_service->getflaggeddrivers();
        if($result)
        {
            //print_r($result);
           return $result;
        }
        else{
            return false;
        }
    }

    // fetch flagged users call
    public function flagged_users_call()
    {
        $result=$this->model_web_service->getflaggedusers();
        if($result)
        {
           return $result;
        }
        else{
            return false;
        }
    }
    // update driver socket call
    public function update_driver_socket_status()
    {
        //$driver_id=$this->input->get('driver_id');
        //$status=$this->input->get('driver_status');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata,true);
        $result = $this->model_web_service->update_driver_socket_call($request['driver_id'],$request['driver_status']);
        if($result){
            $socket_status = array('message' => "sucessfully", 'status' => "success");
            echo json_encode($socket_status);
        }
        else{
            $socket_status = array('message' => "not updated", 'error code' => "25", 'status' => "failed");
            echo json_encode($socket_status);
        }
    }
    public function fetchDriverAppLanguage()
    {
        //http://192.168.1.18/gagaji/WebApp/Source/web_service/fetchDriverAppLanguage
        $this->db->select('language_meta');
        $this->db->where('status', '1');
        $query = $this->db->get('app_languages');
        $result = $query->row();
        if (count($result)) {
            echo $result->language_meta;
        } else {
//				 echo "No data";
            $d = array();
            $d[] = array('' => "No data found");
            $json = json_encode($d);
            print_r($json);
        }
    }

    public function fetchUserAppLanguage()
    {
        //http://192.168.1.18/gagaji/WebApp/Source/web_service/fetchUserAppLanguage
        $this->db->select('language_meta');
        $this->db->where('status', '1');
        $query = $this->db->get('user_app_language');
        $result = $query->row();
        if (count($result)) {
            echo $result->language_meta;
        } else {
            //echo "No data";
            $d = array();
            $d[] = array('' => "No data found");
            $json = json_encode($d);
            print_r($json);
        }
    }
}

