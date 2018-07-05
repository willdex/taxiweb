<?php
class Model_cab extends CI_Model{
function __construct() {
parent::__construct();
}

function cmc_sms($mob_no,$msg)
   {
	   $query = $this->db->query(" SELECT * FROM `settings` order by id DESC ");
		$row = $query->row('settings');
		
		
       $sender_id = $row->sender_id; // sender id    
       $pwd = $row->sms_password;  
	   $user = $row->sms_username; //your SMS gatewayhub account password        
       $str = trim(str_replace(" ", "%20", $msg));
       // to replace the space in message with  ‘%20’
       $url="http://api.smsgatewayhub.com/smsapi/pushsms.aspx?user=".$user."&pwd=".$pwd."&to=91".$mob_no."&sid=".$sender_id."&msg=".$str."&fl=0&gwid=2";
       // create a new cURL resource
       $ch = curl_init();
       
       // set URL and other appropriate options
       curl_setopt($ch, CURLOPT_URL,$url);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       // grab URL and pass it to the browser
       curl_exec($ch);
       // close cURL resource, and free up system resources
       curl_close($ch);
	   //return true;
     }
function test($data)
{
	  $username = $data['username'];
	  $email = $data['email'];
	  $mobile =  $data['mobile'];
	  if($data['type']=='user'){
		  
	  
	  $this->db->where('username', $username);
	  $query = $this->db->get('userdetails');
	 
	   if($query->num_rows == 0)
        {
			 $this->db->where('email',  $email);
			 $query1 = $this->db->get('userdetails');
			if($query1->num_rows == 0)
        {
			 $this->db->where('mobile', $mobile);
			 $query1 = $this->db->get('userdetails');
			if($query1->num_rows == 0)
        {
			$data['active_id'] = mt_rand(100000,999999); 
			
//if($this->db->insert('userdetails',$data))
//{
	
	/* $this->session->set_userdata('username',$data['username']);
	 $user1 = $this->session->userdata('username');*/
/*
$from='no-reply@techware.co.in';
$name='Calmycab';
$msg='test';
$sub="Account Details";
 if ($this->home->send_mail($from,$name,$email,$sub,$msg)) {
                      echo "";
                    }
					  echo $user1;*/
					  $smobile = $data['mobile'];
					  $code = $data['active_id'];
					  
					  $query3 = $this->db->query(" SELECT * FROM `settings` order by id DESC ");
	$row3 = $query3->row('settings');
	$communication1 = $row3->communication;
	$verification = $row3->verification;
	if($verification == 'on'){
	   if($communication1 == 'sms'){
			  
		   $sms="Your Call MY Cab verification code is ".$code." ";
           $this->home->cmc_sms($smobile,$sms);
	   }else{
		
	      $from= $row3->smtp_username;
          $name=$row3->title;
          $msg="Your Call MY Cab verification code is ".$code."";
          $sub="Verification Code";
	      $email = $email;
		
		  $this->home->send_mail($from,$name,$email,$sub,$msg);
	    }
	   $this->session->set_userdata('last_reg_details',json_encode($data));
	}else{
		
		
		 $data['user_status'] = 'Active';
	     $data['password'] =md5($data['password']);
         if($this->db->insert('userdetails',$data))
    
        {
		 $this->session->set_userdata('username',$data['username']);
		 $this->session->set_userdata('type',$data['type']);
		 $user1 = $this->session->userdata('username');
		 
		 
			  echo $user1;
		 
		
        }else{
	      echo 2;
        }
   
	
	}
					  
					 
//}
//else{
//echo 0;
//}  
        }else{
			echo 5;
		}
		}else{
			echo 4;
		}
		}else{
			echo 3;
		}
	  }else{
		  
		 
	  $this->db->where('user_name', $username);
	  $query = $this->db->get('driver_details');
	 
	   if($query->num_rows == 0)
        {
			 $this->db->where('email',  $email);
			 $query1 = $this->db->get('	driver_details');
			if($query1->num_rows == 0)
        {
			 $this->db->where('phone', $mobile);
			 $query1 = $this->db->get('	driver_details');
			if($query1->num_rows == 0)
        {
			$data['active_id'] = mt_rand(100000,999999); 
			
//if($this->db->insert('userdetails',$data))
//{
	
	/* $this->session->set_userdata('username',$data['username']);
	 $user1 = $this->session->userdata('username');*/
/*
$from='no-reply@techware.co.in';
$name='Calmycab';
$msg='test';
$sub="Account Details";
 if ($this->home->send_mail($from,$name,$email,$sub,$msg)) {
                      echo "";
                    }
					  echo $user1;*/
					  $smobile = $data['mobile'];
					  $code = $data['active_id'];
					  
					  $query3 = $this->db->query(" SELECT * FROM `settings` order by id DESC ");
	$row3 = $query3->row('settings');
	$communication1 = $row3->communication;
	$verification = $row3->verification;
	if($verification == 'on'){
	   if($communication1 == 'sms'){
			  
		   $sms="Your Call MY Cab verification code is ".$code." ";
           $this->home->cmc_sms($smobile,$sms);
	   }else{
		
	      $from= $row3->smtp_username;
          $name=$row3->title;
          $msg="Your Call MY Cab verification code is ".$code."";
          $sub="Verification Code";
	      $email = $email;
		
		  $this->home->send_mail($from,$name,$email,$sub,$msg);
	    }
	   $this->session->set_userdata('last_reg_details',json_encode($data));
	}else{
		
		
		 
		 $datas = array(
'user_status'=>'Inactive',
'user_name'=>$data['username'],
'phone'=>$data['mobile'],
'email'=>$data['email'],
'password'=>$data['password']
);

         if($this->db->insert('driver_details',$datas))
    
        {
		 $this->session->set_userdata('username',$data['username']);
		 $this->session->set_userdata('type',$data['type']);
		 $user1 = $this->session->userdata('username');
		 
		 
			  echo 'driver';
		 
		
        }else{
	      echo 2;
        }
   
	
	}
					  
					 
//}
//else{
//echo 0;
//}  
        }else{
			echo 5;
		}
		}else{
			echo 4;
		}
		}else{
			echo 3;
		} 
	  }
}

function signup($data){
	 // grab user input
	//print_r($data);exit;
        $username = $data['username'];
        $password = md5($data['password']);
		$remember='';
		if(isset($data['rememberme'])){
        $remember = $data['rememberme'];
		}
        // Prep the query
		
        if($data["user"]=='user')
		{
			
		
        // Run the query
		$query = $this->db->query("select * from userdetails where binary username ='$username' and binary password = '$password' and user_status='Active'");
        }else{
			$password1=$data['password'];
			$query = $this->db->query("select * from driver_details where binary user_name ='$username' and binary password = '$password1' ");
		}
        // Let's check if there are any results
	
        if($query->num_rows == 1)
        {
            // If there is a user, then create session data
            //$row = $query->result_array();
		if($remember=='on' && $remember!=''){
			
	$cookie = array(
                'name'   => 'username',
                'value'  => $username,
                'expire' => 86500
            );
        //  $this->ci->db->insert("UserCookies", array("CookieUserEmail"=>$userEmail, "CookieRandom"=>$randomString));
            $this->input->set_cookie($cookie);

$this->input->cookie('username', false);    
 

			
		}
	
           
         $this->session->set_userdata('username',$data['username']);
		 $this->session->set_userdata('type',$data["user"]);
		  $user = $this->session->userdata('username');
		  $type = $this->session->userdata('type');
            //return $row;
			if($type=="user"){
				echo $user;
				
			}else{
				echo $type;
			}
			
        }
        // If the previous process did not validate
        // then return false.
		else
		{
        //return false;
		echo 1;
		}

}
function booking($data){
	//var_dump($data);
	 $query3 = $this->db->query(" SELECT * FROM `settings` order by id DESC ");
	$row3 = $query3->row('settings');
	if($this->session->userdata('username') || $this->input->cookie('username', false)){
		if($this->session->userdata('username')){
		$data['username']=$this->session->userdata('username');
		}else{
			$data['username']= $this->input->cookie('username', false);
		}
		$data['uneaque_id'] = 'CMC'.strtotime(date('m/d/Y H:i:s'));
		
		
		$wh="";
		if(isset($data['timetype'])){
		$timetype=$data['timetype'];
		$type=$data['timetype'];
		$wh ="AND timetype='$type'";
		}
		if(isset($data['package'])){
		$pack =$data['package'];
		$wh ="AND package='$pack'";
        }
		 $username =$data['username'];
		 $purpose = $data['purpose'];
		 $car= $data['taxi_type'];
        $time =$data['pickup_time'];
		
		$query = $this->db->query("SELECT * FROM  cabdetails WHERE     cartype ='$car' AND transfertype ='$purpose' ".$wh);
        $row = $query->row('cabdetails');
		
		if($purpose =='Point to Point Transfer'){
			if($query->num_rows > 0){
			$km=$data['km'];
			$Ik = $row->intialkm;
		$Ir = $row->intailrate;
		$Sr= $row->standardrate;
			if($Ik > $km){
				$amount1 = ($Ik-$km)*$Sr + $Ir;
			}else if($km > $Ik){
				$amount1 = ($km-$Ik)*$Sr + $Ir;
			}else{
				$amount1 =  $Ir;
			}
			
			}
		}elseif($purpose =='Airport Transfer'){
			$km=$data['km'];
			$transfertype = $data["transfer"];
			if($query->num_rows > 0){
			if($transfertype='going'){
				$Ik = $row->intialkm;
		        $Ir = $row->intailrate;
		        $Sr= $row->standardrate;
		     if($Ik > $km){
				$amount1 = ($Ik-$km)*$Sr + $Ir;
			}else if($km > $Ik){
				$amount1 = ($km-$Ik)*$Sr + $Ir;
			}else{
				$amount1 =  $Ir;
			}
		
			
				
			}else{
				$Ik = $row->fromintialkm;
		        $Ir = $row->fromintialrate;
		        $Sr= $row->fromstandardrate;
		     if($Ik > $km){
				$amount1 = ($Ik-$km)*$Sr + $Ir;
			  }else if($km > $Ik){
				$amount1 = ($km-$Ik)*$Sr + $Ir;
			  }else{
				$amount1 =  $Ir;
			    }
			}
			}
			
		}else if($purpose =='Outstation Transfer'){
			$transfertype = $data["transfer"];
			if($query->num_rows > 0){
			if($transfertype=='oneway'){
				
				 $Sr= $row->standardrate;
				
				  $amount1 = $Sr;
				
			}else{
				
				 $Sr= $row->fromstandardrate;
				
				  $amount1 = $Sr;
				
			}
			}
		}else{
			
			
		if($query->num_rows > 0){
			 $Sr=$row->standardrate;
			 $amount1 = $Sr;
		}
		}
		
		$data['amount']=$amount1;
		
		
	if($this->db->insert('bookingdetails',$data))
{
	$this->session->set_userdata('uneaqueid',$data['uneaque_id']);
	$id= $this->db->insert_id();
 $this->session->set_userdata('bookid',$id);
 $this->session->set_userdata('amount',$amount1);
 // $this->session->userdata('bookid');
 $this->db->where('username', $data['username']);
	$query2 = $this->db->get('userdetails');
	
	$from= $row3->smtp_username;
	$paypal = $row3->paypal_option;
    $name=$row3->title;
    $msg='Booking';
    $sub="Booking Details";
	$email = $query2->row('email');
    $km1 =	$row3->measurements;
	$str = $row3->currency;
	$curr = explode(',',$str);
	$mailTemplate='<div style="width:660px; height:640px; margin:0 auto; background:#f2c21e; padding:20px 20px 20px 20px; font-family: Century Gothic,Verdana,Geneva,sans-serif; border:solid #c79e13 1px;">
	
    <div style="width:100%; float:left; padding:0 0 10px 0;">
    <div style="width:138px; height:73px; float:left; margin:0 0 0 20px;"> <img src="images/logo.png" alt="" /></div>
    <div style="width:350px; float:left; padding:25px 0 0 0; text-align:center; font-size:18px; "> BOOKING DETAILS </div>
    
    
    </div>
    
    
    
    
        <div style="background:#fff;  float:left; width:96.3%;   border-top-right-radius: 8px; border-top-left-radius: 8px; padding:15px 12px 0 12px;  ">
    		<div style="width:100%; padding:10px 0 10px 0; float:left; color:#666261; font-size:14px;"> Hi '. $this->session->userdata('username').' , thanks for booking with us. </div>
                 <div style="width:100%; float:left; padding:20px 0 20px 0; border-bottom:solid #cdcdcd 1px; border-top:solid #cdcdcd 1px;"> 
                 <div style="width:30%; float:left; font-size:17px;"> Trip#1 </div>
                 <div style="width:40%; float:left; font-size:17px;">'.$data['uneaque_id'].' </div>
                 <div style="width:30%; float:left;">  
                 
                 <a href="#"> <div style="width:100px; height:30px; background:#58585a; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; font-size:12px; color:#fff; 
                 text-align:center; line-height:25px; text-decoration:none; float:right;"> Track Booking </div> </a>
                 
                  </div>
            </div>
        </div>
        
        
        
        
        
        <div style="background:#3a3a3c;     float:left; width:96.3%;  padding:0px 12px 10px 12px;">
        	<div style="width:100%; float:left; padding:35px 0 30px 0;">
            <div style="width:43%; float:left; color:#ffdd1a; font-size:16px; padding:5px 0 0 0; line-height:22px;">';
			if(isset($data["pickup_area"])){ $data["pickup_area"];}else{$data["pickup_address"]; }
		$mailTemplate .=' </div>
            <div style="width:15%; float:left; text-align:center;"> <img src="images/arrow.png" alt="" /> </div>
            <div style="width:42%; float:left; color:#ffdd1a; font-size:16px; padding:5px 0 0 0; line-height:22px;">'; 
			if (isset($data["drop_area"])){ $data["drop_area"]; } 
			$mailTemplate .= '</div>
            </div>
            
            
            <div style="width:100%; float:left;">
                <div style="width:32%;   float:left; background:#585858; padding:24px 0 24px 0; "> 
                <div style="width:100%; float:left; text-align:center; color:#fff; font-size:15px;"> '. $data["taxi_type"].'  </div>
                <div style="width:100%; float:left; text-align:center; color:#bbbbbb; font-size:14px; padding:5px 0 0 0;"> AC </div> 
                </div>';
				$car= $data["taxi_type"];
				     if(isset($data['timetype'])){
						 	$package =$data['timetype'];
					$this->db->where('timetype', $timetype);
					}
					if(isset($data['package'])){
						$package =$data['package'];
					$this->db->where('package', $package);
					}
					$purpose = $data['purpose'];
					$this->db->where('transfertype', $purpose);
					$this->db->where('cartype', $car);
					
	                $query4 = $this->db->get('cabdetails');
					$row4 = $query4->row();
	               
                if($data['purpose']=='Point to Point Transfer'){
					
               $mailTemplate .= '<div style="width:32%; margin:0 12px 0 12px;   float:left; background:#585858; padding:24px 0 24px 0; "> 
                <div style="width:100%; float:left; text-align:center; color:#fff; font-size:15px;">
				'. $curr[1].''. $row4->intailrate.' for '.$row4->intialkm.' '.$km1.''; 
				$mailTemplate .='</div>
                <div style="width:100%; float:left; text-align:center; color:#bbbbbb; font-size:14px; padding:5px 0 0 0; ">
				'. $curr[1].''. $row4->standardrate.'.00 per extra '.$km1.' ';
				$mailTemplate .='</div> 
                </div>';
               
				}
				
				else if($data['purpose']=='Airport Transfer'){
					 $mailTemplate .= '<div style="width:32%; margin:0 12px 0 12px;   float:left; background:#585858; padding:24px 0 24px 0; "> 
                <div style="width:100%; float:left; text-align:center; color:#fff; font-size:15px;">';
				if($data['transfer'] == 'going'){
				$mailTemplate .= ''. $curr[1].'.'.$row4->intailrate.'.00 for'. $row4->intialkm.''.$km1.''; 
				}else{
				$mailTemplate .= ''. $curr[1].'.'.$row4->fromintailrate.'.00 for'. $row4->fromintialkm.''.$km1.'';	
				}
			$mailTemplate .= '	</div>
			
                <div style="width:100%; float:left; text-align:center; color:#bbbbbb; font-size:14px; padding:5px 0 0 0; ">';
				if($data['transfer'] == 'going'){
				$mailTemplate .= ''. $curr[1].'.'. $row4->standardrate.'.00 per extra '.$km1.'';
				}else{
				$mailTemplate .= ''. $curr[1].'.'.$row4->fromstandardrate.'.00 per extra '.$km1.' ';
				}
				$mailTemplate .= '	</div> 
                </div>';
				
				
				}else if($data['purpose']=='Hourly Rental'){
					 $mailTemplate .= '<div style="width:32%; margin:0 12px 0 12px;   float:left; background:#585858; padding:24px 0 24px 0; "> 
                <div style="width:100%; float:left; text-align:center; color:#fff; font-size:15px;">'. $curr[1].'.'.$row4->standardrate.'.00 for '.$row4->package.' </div>
                 
                </div>';
				}else {
					$mailTemplate .= '<div style="width:32%; margin:0 12px 0 12px;   float:left; background:#585858; padding:24px 0 24px 0; "> 
                <div style="width:100%; float:left; text-align:center; color:#fff; font-size:15px;">';
				if($data['transfer'] == 'oneway'){
				$mailTemplate .= 'ONEWAY TRIP ';
				}else{
				$mailTemplate .= '	ROUND TRIP';
				}
				$mailTemplate .= '</div>
                <div style="width:100%; float:left; text-align:center; color:#bbbbbb; font-size:14px; padding:5px 0 0 0; ">';
				if($data['transfer'] == 'oneway'){
			$mailTemplate .= '	'. $curr[1].'.'.  $row4->standardrate.'.0' ;
                }else{
			$mailTemplate .= '	'. $curr[1].'.'.  $row4->fromstandardrate.' .0 ';
				}
				$mailTemplate .= '</div> 
                </div>';
					
					
					
					
				}
			  
				
                
               $mailTemplate .= '<div style="width:32%;   float:left; background:#585858; padding:24px 0 24px 0; "> 
                <div style="width:100%; float:left; text-align:center; color:#fff; font-size:15px;"> '. $data["pickup_time"].'. </div>
                <div style="width:100%; float:left; text-align:center; color:#bbbbbb; font-size:14px; padding:5px 0 0 0;">'. date('D, d M',strtotime( $data["pickup_date"])).' </div> 
                </div>
            </div>
            
            <div style="width:100%; float:left; color:#bbbbbb; padding:14px 0 8px 0; font-size:12px;"> *The driver’s details will be sent to you 15 mins prior to pick up time. </div>
       </div>
            
            
            
            
        <div style="background:#fff;  float:left; width:96.3%;    border-bottom-right-radius: 8px; border-bottom-left-radius: 8px; padding:20px 12px 20px 12px;  ">
        <div style="width:100%; float:left; font-size:16px; padding:0 0 10px 0;"> Extra Charges: </div> 
        <div style="width:100%; float:left; color:#666262; font-size:11px; line-height:22px;">
        
        * Maximum of 4 passengers allowed for Indica & Sedan. <br />
        * Cancellation charges of Rs.100 applicable if cancelled within 30 mins of pickup time. <br />
        * Any Toll, Parking, as applicable. <br />
        * No waiting charges upto 15 mins after scheduled pickup time. Rs.50 per 30 mins after that. <br /> 
        * Final fare payable will include Service Tax
        
        </div>
 
        </div>
            
            
            
            
            
            
            
        </div>';
 if ($this->home->send_mail($from,$name,$email,$sub,$mailTemplate)) {
                      echo "";
                    }	
	$this->session->set_userdata('last_booking_details',json_encode($data));
 $this->load->view('select_payment');				
					
	/*if($paypal=='PayPal'){
$data = $_POST;
 $this->load->view('paypal');
	}else{
		$data = $_POST;
		 $this->load->view('confirm_book');
	}*/
}
else{
echo 0;
}
}else{
	echo 2;
}
}

function contacted($data){
	$username = $this->session->userdata('username');
	$type= $this->session->userdata('type');
	if($type =="user"){
		 $this->db->where('username', $username);
	if($this->db->update('userdetails',$data)){
		
		echo 1;
	}else{
		echo 0;
	}
	}else{
			 $datas = array(

'user_name'=>$data['username'],
'phone'=>$data['mobile'],
'email'=>$data['email'],
'gender'=>$data['gender'],
'anniversary_date'=>$data['anniversary_date'],
'dob'=>$data['dob']
);
		 $this->db->where('user_name', $username);
	if($this->db->update('driver_details',$datas)){
		
		echo 1;
	}else{
		echo 0;
	}
	}
	
}

function update_book($data){
	
	    if($username = $this->session->userdata('username')){
		$username = $this->session->userdata('username');
		}else{
		$username = $this->input->cookie('username', false);
		}
		
		$id = $data['id'];
		if(isset($data['timetype'])){
		$timetype=$data['timetype'];
		$type=$data['timetype'];
		$wh ="AND timetype='$type'";
		}
		if(isset($data['package'])){
		$pack =$data['package'];
		$wh ="AND package='$pack'";
		}
		 $purpose = $data['purpose'];
		 $car= $data['taxi_type'];
        $time =$data['pickup_time'];
		
		$query = $this->db->query("SELECT * FROM  cabdetails WHERE     cartype ='$car' AND transfertype ='$purpose' ".$wh);
        $row = $query->row('cabdetails');
		
		if($purpose =='Point to Point Transfer'){
			if($query->num_rows > 0){
			$km=$data['km'];
			$Ik = $row->intialkm;
		$Ir = $row->intailrate;
		$Sr= $row->standardrate;
			if($Ik > $km){
				echo $amount1 = ($Ik-$km)*$Sr + $Ir;
			}else if($km > $Ik){
				echo $amount1 = ($km-$Ik)*$Sr + $Ir;
			}else{
				echo $amount1 =  $Ir;
			}
			
			}
		}elseif($purpose =='Airport Transfer'){
			$km=$data['km'];
			$transfertype = $data["transfer"];
			if($query->num_rows > 0){
			if($transfertype='going'){
				$Ik = $row->intialkm;
		        $Ir = $row->intailrate;
		        $Sr= $row->standardrate;
		     if($Ik > $km){
				$amount1 = ($Ik-$km)*$Sr + $Ir;
			}else if($km > $Ik){
				$amount1 = ($km-$Ik)*$Sr + $Ir;
			}else{
				$amount1 =  $Ir;
			}
		
			
				
			}else{
				$Ik = $row->fromintialkm;
		        $Ir = $row->fromintialrate;
		        $Sr= $row->fromstandardrate;
		     if($Ik > $km){
				$amount1 = ($Ik-$km)*$Sr + $Ir;
			  }else if($km > $Ik){
				$amount1 = ($km-$Ik)*$Sr + $Ir;
			  }else{
				$amount1 =  $Ir;
			    }
			}
			}
			
		}else if($purpose =='Outstation Transfer'){
			$transfertype = $data["transfer"];
			if($query->num_rows > 0){
			if($transfertype='oneway'){
				
				 $Sr= $row->standardrate;
				
				$amount1 = $Sr;
				
			}else{
				
				 $Sr= $row->fromstandardrate;
				
				$amount1 = $Sr;
				
			}
			}
		}else{
			
			
		if($query->num_rows > 0){
			 $Sr=$row->standardrate;
			$amount1 = $Sr;
		}
		}
		$data['amount']=$amount1;
		$this->db->where('username', $username);
		$this->db->where('id', $id);
	if($this->db->update('bookingdetails',$data))
	
{
	 $this->db->where('username', $username);
	$query2 = $this->db->get('userdetails');
	 $query3 = $this->db->query(" SELECT * FROM `settings` order by id DESC ");
	$row3 = $query3->row('settings');
	$from= $row3->smtp_username;
	$paypal = $row3->paypal_option;
	$str = $row3->currency;
	$s = explode(',',$str);
	$km1 =	$row3->measurements;
    $name=$row3->title;
    $msg='Booking';
    $sub="Booking Details";
	 $km1 =	$row3->measurements;
	 foreach ($query2->result() as $row)
   {
      $email= $row->email;
     
   }
  
   $this->db->where('username', $username);
   	$this->db->where('id', $id);
	$urls= base_url();
	$query3 = $this->db->get('bookingdetails');
	 foreach ($query3->result() as $row1)
   {
	    $this->session->set_userdata('uneaqueid',$row1->uneaque_id);
	
 $this->session->set_userdata('bookid',$id);
 if(isset($row1->pickup_area)){ 
 $pickup_area =$row1->pickup_area;
 }
 else{
	 
$pickup_area =$row1->pickup_address; 
}
	$mailTemplate='<div style="width:660px; height:640px; margin:0 auto; background:#f2c21e; padding:20px 20px 20px 20px; font-family: Century Gothic,Verdana,Geneva,sans-serif; border:solid #c79e13 1px;">
	
    <div style="width:100%; float:left; padding:0 0 10px 0;">
    <div style="width:138px; height:73px; float:left; margin:0 0 0 20px;"> <img src="'.$urls.'assets/images/carss.png" alt="" /></div>
    <div style="width:350px; float:left; padding:25px 0 0 0; text-align:center; font-size:18px; "> BOOKING DETAILS </div>
    
    
    </div>
    
    
    
    
        <div style="background:#fff;  float:left; width:96.3%;   border-top-right-radius: 8px; border-top-left-radius: 8px; padding:15px 12px 0 12px;  ">
    		<div style="width:100%; padding:10px 0 10px 0; float:left; color:#666261; font-size:14px;"> Hi '. $this->session->userdata('username').' , thanks for booking with us. </div>
                 <div style="width:100%; float:left; padding:20px 0 20px 0; border-bottom:solid #cdcdcd 1px; border-top:solid #cdcdcd 1px;"> 
                 <div style="width:30%; float:left; font-size:17px;"> Trip#1 </div>
                 <div style="width:40%; float:left; font-size:17px;">'. $this->session->userdata('bookid').' </div>
                 <div style="width:30%; float:left;">  
                 
                 <a href="#"> <div style="width:100px; height:30px; background:#58585a; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; font-size:12px; color:#fff; 
                 text-align:center; line-height:25px; text-decoration:none; float:right;"> Track Booking </div> </a>
                 
                  </div>
            </div>
        </div>
        
        
        
        
        
        <div style="background:#3a3a3c;     float:left; width:96.3%;  padding:0px 12px 10px 12px;">
        	<div style="width:100%; float:left; padding:35px 0 30px 0;">
            <div style="width:43%; float:left; color:#ffdd1a; font-size:16px; padding:5px 0 0 0; line-height:22px;">'.$pickup_area.' </div>
            <div style="width:15%; float:left; text-align:center;"> <img src="'.$urls.'assets/images/arrow.png" alt="" /> </div>
            <div style="width:42%; float:left; color:#ffdd1a; font-size:16px; padding:5px 0 0 0; line-height:22px;">'. $row1->drop_area.'</div>
            </div>
            
            
            <div style="width:100%; float:left;">
                <div style="width:32%;   float:left; background:#585858; padding:24px 0 24px 0; "> 
                <div style="width:100%; float:left; text-align:center; color:#fff; font-size:15px;"> '. $row1->taxi_type.'  </div>
                <div style="width:100%; float:left; text-align:center; color:#bbbbbb; font-size:14px; padding:5px 0 0 0;"> AC </div> 
                </div>';
				$car= $row1->taxi_type;
					$purpose = $row1->purpose;
					$this->db->where('transfertype', $purpose);
					
					$this->db->where('cartype', $car);
					if(isset($timetype)){
					$this->db->where('timetype', $timetype);
					}
	                $query4 = $this->db->get('cabdetails');
	                foreach ($query4->result() as $row4)
               {
                if($data['purpose']=='Point to Point Transfer'){
					
               $mailTemplate .= '<div style="width:32%; margin:0 12px 0 12px;   float:left; background:#585858; padding:24px 0 24px 0; "> 
                <div style="width:100%; float:left; text-align:center; color:#fff; font-size:15px;">
				'. $s[1].''. $row4->intailrate.' for '.$row4->intialkm.' '.$km1.''; 
				$mailTemplate .='</div>
                <div style="width:100%; float:left; text-align:center; color:#bbbbbb; font-size:14px; padding:5px 0 0 0; ">
				'. $s[1].'.'. $row4->standardrate.'.00 per extra '.$km1.' ';
				$mailTemplate .='</div> 
                </div>';
               
				}
				
				else if($data['purpose']=='Airport Transfer'){
					 $mailTemplate .= '<div style="width:32%; margin:0 12px 0 12px;   float:left; background:#585858; padding:24px 0 24px 0; "> 
                <div style="width:100%; float:left; text-align:center; color:#fff; font-size:15px;">';
				if($data['transfer'] == 'going'){
				$mailTemplate .= ''. $s[1].'.'.$row4->intailrate.'.00 for'. $row4->intialkm.' '.$km1.''; 
				}else{
				$mailTemplate .= ''. $s[1].'.'.$row4->fromintailrate.'.00 for'. $row4->fromintialkm.''.$km1.' ';	
				}
			$mailTemplate .= '	</div>
			
                <div style="width:100%; float:left; text-align:center; color:#bbbbbb; font-size:14px; padding:5px 0 0 0; ">';
				if($data['transfer'] == 'going'){
				$mailTemplate .= ''. $s[1].'.'. $row4->standardrate.'.00 per extra '.$km1.' ';
				}else{
				$mailTemplate .= '	'. $s[1].'.'.$row4->fromstandardrate.'.00 per extra '.$km1.' ';
				}
				$mailTemplate .= '	</div> 
                </div>';
				
				
				}else if($data['purpose']=='Hourly Rental'){
					 $mailTemplate .= '<div style="width:32%; margin:0 12px 0 12px;   float:left; background:#585858; padding:24px 0 24px 0; "> 
               <div style="width:100%; float:left; text-align:center; color:#fff; font-size:15px;"> Rs.'.$row4->standardrate.'.00 for '.$row4->package.' </div>
                </div>';
				}else {
					$mailTemplate .= '<div style="width:32%; margin:0 12px 0 12px;   float:left; background:#585858; padding:24px 0 24px 0; "> 
                <div style="width:100%; float:left; text-align:center; color:#fff; font-size:15px;">';
				if($data['transfer'] == 'oneway'){
				$mailTemplate .= 'ONEWAY TRIP ';
				}else{
				$mailTemplate .= '	ROUND TRIP';
				}
				$mailTemplate .= '</div>
                <div style="width:100%; float:left; text-align:center; color:#bbbbbb; font-size:14px; padding:5px 0 0 0; ">';
				if($data['transfer'] == 'oneway'){
			$mailTemplate .= ''. $s[1].'.'.  $row4->standardrate.'.0' ;
                }else{
			$mailTemplate .= ''. $s[1].'.'.  $row4->fromstandardrate.' .0 ';
				}
				$mailTemplate .= '</div> 
                </div>';
					
					
					
					
				}
			   }
				
                
               $mailTemplate .= '<div style="width:32%;   float:left; background:#585858; padding:24px 0 24px 0; "> 
                <div style="width:100%; float:left; text-align:center; color:#fff; font-size:15px;"> '. $row1->pickup_time.'. </div>
                <div style="width:100%; float:left; text-align:center; color:#bbbbbb; font-size:14px; padding:5px 0 0 0;">'. date('D, d M',strtotime( $row1->pickup_date)).' </div> 
                </div>
            </div>
            
            <div style="width:100%; float:left; color:#bbbbbb; padding:14px 0 8px 0; font-size:12px;"> *The driver’s details will be sent to you 15 mins prior to pick up time. </div>
       </div>
            
            
            
            
        <div style="background:#fff;  float:left; width:96.3%;    border-bottom-right-radius: 8px; border-bottom-left-radius: 8px; padding:20px 12px 20px 12px;  ">
        <div style="width:100%; float:left; font-size:16px; padding:0 0 10px 0;"> Extra Charges: </div> 
        <div style="width:100%; float:left; color:#666262; font-size:11px; line-height:22px;">
        
        * Maximum of 4 passengers allowed for Indica & Sedan. <br />
        * Cancellation charges of Rs.100 applicable if cancelled within 30 mins of pickup time. <br />
        * Any Toll, Parking, as applicable. <br />
        * No waiting charges upto 15 mins after scheduled pickup time. Rs.50 per 30 mins after that. <br /> 
        * Final fare payable will include Service Tax
        
        </div>
 
        </div>
            
            
            
            
            
            
            
        </div>';
   }
		 if ($this->home->send_mail($from,$name,$email,$sub,$mailTemplate)) {
                      echo "";
                    }	
	
		$data = $_POST;
		 $this->load->view('confirm_book');
	
  
}
else{
	
echo 0;
}
}
function rating($data){
	/*$data['ip'] =$_SERVER['REMOTE_ADDR'];
	
	$this->db->where('ip', $data['ip']);
	$query = $this->db->get('visits');
	if($query->num_rows == 0)
	
    {
	  if($this->db->insert('visits',$data))
      {
		  echo 0;
	  }
    }*/
}
function update_change($data){
	
	//print_r($data);
		 if($username = $this->session->userdata('username')){
		$username = $this->session->userdata('username');
		}else{
		$username = $this->input->cookie('username', false);
		}
		$type = $this->session->userdata('type');
		
		$select_data = "*"; 
		if($type =="user"){
				$where_data = array(	// ----------------Array for check data exist ot not
				'username'     => $username,
				'password'     => md5($data['current'])
			);
			
			$table = "userdetails";  //------------ Select table
			$update_data = array(
				'password'     => md5($data['newpass'])
			);
			
			$where_datas = array(
				'username'     => $username
			);
		}else{
				$where_data = array(	// ----------------Array for check data exist ot not
				'user_name'     => $username,
				'password'     => $data['current']
			);
			
			$table = "driver_details";  //------------ Select table
			$update_data = array(
				'password'     => $data['newpass']
			);
			
			$where_datas = array(
				'user_name'     => $username
			);
		}
		
		$result = $this->get_table_where( $select_data, $where_data, $table );
		
	if( count($result) == 1)
	
{
	

if($data['newpass'] == $data['confirmpass'])
{
	
	
	
		
		
	$s =	$this->update_table_where( $update_data, $where_datas, $table);


	echo 2;

}else{
	echo 0;
}

}else{
	echo 3;
}
}
 function update_table_where( $update_data, $where_datas, $table){	
	$this->db->where($where_datas);
	$this->db->update($table, $update_data);
         
   }    


function update_status($data){
	
	 if($username = $this->session->userdata('username')){
		$username = $this->session->userdata('username');
		}else{
		$username = $this->input->cookie('username', false);
		}
		$id = $data['id'];
		$status = $data['status'];
		
		$this->db->where('username', $username);
		$this->db->where('id', $id);
	if($this->db->update('bookingdetails',$data))
	
{
	
	$query3 = $this->db->get('userdetails');
	$this->db->where('username',$username);
	$query2 = $this->db->get('userdetails');
	 $query3 = $this->db->query(" SELECT * FROM `settings` order by id DESC ");
	$row3 = $query3->row('settings');
	$from= $row3->smtp_username;
    $name=$row3->title;
   
    $sub="Cancel Details";
	 foreach ($query2->result() as $row)
   {
      $email= $row->email;
     
   }
    $this->db->where('username', $username);
   	$this->db->where('id', $id);
	$query3 = $this->db->get('bookingdetails');
    foreach ($query3->result() as $row1)
   {
   $mailTemplate='<div style="width:660px; height:355px; margin:0 auto; background:#f2c21e; padding:20px 20px 20px 20px; font-family: Century Gothic,Verdana,Geneva,sans-serif; border:solid #c79e13 1px;">
	
    <div style="width:100%; float:left; padding:0 0 10px 0;">
    <div style="width:138px; height:73px; float:left; margin:0 0 0 20px;"> <img src="images/logo.png" alt="" /></div>
    <div style="width:350px; float:left; padding:25px 0 0 0; text-align:center; font-size:18px; "> BOOKING DETAILS </div>
    
    
    </div>
    
    
    
    
        <div style="background:#fff;  float:left; width:96.3%;   border-top-right-radius: 8px; border-top-left-radius: 8px; padding:15px 12px 0 12px;  ">
    		<div style="width:100%; padding:10px 0 10px 0; float:left; color:#666261; font-size:14px; text-align:center;"> Booking is cancelled</div>
                 <div style="width:100%; float:left; padding:2px 0 0px 0; border-bottom:solid #cdcdcd 1px; border-top:solid #cdcdcd 1px;"> 
                  
            </div>
        </div>
        
        
        
        
        
        <div style="background:#3a3a3c;     float:left; width:96.3%;  padding:5px 12px 10px 12px;">
          <div style="width:100%; float:left;">
            
              <div style="width:100%;   float:left; background:#585858; padding:15px 0 15px 0; margin:5px 0 0 0; "> 
                <div style="width:100%; float:left; text-align:center; color:#ffdd1a; font-size:16px;"> Pickup: <div style="color:#fff; font-size:14px; display:inline;"> '.$row1->pickup_area.' </div></div>
                </div>
                
          <div style="width:100%;   float:left; background:#585858; padding:15px 0 15px 0; margin:5px 0 0 0; "> 
                <div style="width:100%; float:left; text-align:center; color:#ffdd1a; font-size:16px;"> Drop: <div style="color:#fff; font-size:14px; display:inline;"> '.$row1->drop_area.'</div></div>
                </div>
                
          <div style="width:100%;   float:left; background:#585858; padding:15px 0 15px 0; margin:5px 0 0 0; "> 
                <div style="width:100%; float:left; text-align:center; color:#ffdd1a; font-size:16px;"> Pickup Time: <div style="color:#fff; font-size:14px; display:inline;"> '. date('D, d M',strtotime($row1->pickup_date)).', '. $row1->pickup_time.'</div></div>
                </div>
                
                
                
                
            </div>
       </div>
            
            
            
            
        <div style="background:#fff;  float:left; width:96.3%;    border-bottom-right-radius: 8px; border-bottom-left-radius: 8px; padding:20px 12px 20px 12px;  "></div>
            
            
            
            
            
            
            
        </div>';
   }
	
 if ($this->home->send_mail($from,$name,$email,$sub,$mailTemplate)) {
                      echo "dd";
                    }	
	
	
echo 1;
}
else{
	
echo 0;
}

}
function send_mail($from,$name,$mail,$sub, $msg)
{
	$this->db->order_by("id","desc");
	$query2 = $this->db->get('settings');
	 foreach ($query2->result() as $row)
   {
      $host= $row->smtp_host;
	  $pass= $row->smtp_password;
	  $username = $row->smtp_username;
     
   }
	
$config['protocol'] = 'smtp';
$config['smtp_host'] = $host;
$config['smtp_user'] = $username;
$config['smtp_pass'] = $pass;
$config['smtp_port'] = 25;
$config['mailtype'] = 'html';

$this->email->initialize($config);
$this->email->from($from, $name);
$this->email->to($mail);

  $this->email->subject($sub);
        $this->email->message($msg);
        
        $this->email->send();
        
        //echo	 $this->email->print_debugger();
    }

function promocode($data)
{
    
    $promocode = $data['promocode'];
    $this->db->where('promocode',$promocode);
     $query2 = $this->db->get('promocode');
    if($query2->num_rows == 0)
    {
        echo 0;
    }
    else
    {
        
        echo 1;
    }
}
function update_address($data){
    
        if($username = $this->session->userdata('username')){
        $username = $this->session->userdata('username');
        }else{
        $username = $this->input->cookie('username', false);
        }
        
        $this->db->where('username', $username);
        
    if($this->db->update('userdetails',$data))
    
{
    
echo 1;
}
else{
    
echo 0;
}
}

function update_otp($data){
	
	$details=$this->session->userdata('last_reg_details');
	$datails_arr=json_decode($details,true);
	$otp = $data['active_id'];
	
	
    if($data['active_id'] == $datails_arr['active_id'] && $datails_arr['username'] == $datails_arr['username'])
    {    
if($datails_arr['type']=='user'){
	   $datails_arr['active_id']='0';
	     $datails_arr['user_status'] = 'Active';
		 $datails_arr['password']=md5($datails_arr['password']);
	    
         if($this->db->insert('userdetails',$datails_arr))
    
{
	 $this->session->set_userdata('username',$datails_arr['username']);
	 $this->session->set_userdata('type',$datails_arr['type']);
	 $user1 = $this->session->userdata('username');
	
	echo $user1;
}else{
	echo 3;
}
}else{
	   
		
		
		 $datas = array(
'user_status'=>'Inactive',
'user_name'=>$datails_arr['username'],
'phone'=>$datails_arr['mobile'],
'email'=>$datails_arr['email'],
'active_id'=>'0',
'password'=>$datails_arr['password']
);
         if($this->db->insert('driver_details',$datas))
    
{
	 $this->session->set_userdata('username',$datails_arr['username']);
	 $this->session->set_userdata('type',$datails_arr['type']);
	 $user1 = $this->session->userdata('username');
	
	echo 'driver';
}else{
	echo 3;
}
}
      
    }else{                                                                                                                         
		echo 1;
		}	 
}


function update_resend_otp($data){
	
	
	
	        $username = $data['username'];
	
	        $data['active_id'] = mt_rand(100000,999999); 
			
	      
	   $details=$this->session->userdata('last_reg_details');
	    $datails_arr=json_decode($details,true);
         $datails_arr['active_id']=  $data['active_id'];
		 
                $smobile=  $datails_arr['mobile'];
     
        $this->session->set_userdata('last_reg_details',json_encode($datails_arr));
	                  
					  $code = $data['active_id'];
					  $sms="Your Call MY Cab verification code is ".$code." ";
        $this->home->cmc_sms($smobile,$sms);
			echo 1;
  	 
}

 function getMovies($limit=null,$offset=NULL){
	   if($username = $this->session->userdata('username')){
        $username = $this->session->userdata('username');
        }else{
        $username = $this->input->cookie('username', false);
        }
	 
 $this->db->where('username', $username);
 $this->db->where('status', 'Booking');
$query = $this->db->get('bookingdetails');
  
  
  return $query->num_rows();
 }
function record_count() {
	  if($username = $this->session->userdata('username')){
        $username = $this->session->userdata('username');
        }else{
        $username = $this->input->cookie('username', false);
        }
	 
 $this->db->where('username', $username);
 $this->db->where('status', 'Booking');
$query = $this->db->get('bookingdetails');
  
  
  return $query->num_rows();
 } 
 function fetch_countries($limit, $start) {
	  if($username = $this->session->userdata('username')){
        $username = $this->session->userdata('username');
        }else{
        $username = $this->input->cookie('username', false);
        }
         $this->db->where('username', $username);
        $this->db->where('status', 'Booking');  
        $this->db->limit($limit, $start);
            
        $query = $this->db->get("bookingdetails");


        if ($query->num_rows() > 0) {

            foreach ($query->result() as $row) {

                $data[] = $row;

            }
            return $data;

        }

        return false;

   }
   function record_count1() {
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
 
$query = $this->db->get('bookingdetails');
  
  return $query->num_rows();
 } 
 function fetch_countries1($limit,$start) {
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
        $this->db->limit($limit, $start);
            
        $query = $this->db->get("bookingdetails");


        if ($query->num_rows() > 0) {

            foreach ($query->result() as $row) {

                $data[] = $row;

            }
            return $data;

        }

        return false;

   }
   function update_reset_pass($data){
	
	     $scomunicatn = $data['communictn'];
		 
		 if($data['type']=='user'){
		 if($scomunicatn =='sms'){
	
	     $smobile = $data['email'];
	       
	         $query1 = $this->db->query("select * from userdetails where mobile ='$smobile'");
			  if ($query1->num_rows() =='1'){
			$row3 = $query1->row('userdetails');
	
	$username=$row3->username;
	$password = mt_rand(100000,999999); 
	 
	$code = $password;
     $sms="Your new password is ".$code."Please change your password next time you log in. ";
       $data1=array('password'=>md5($password));
	   $this->db->where('mobile',$smobile);
	if($this->db->update('userdetails',$data1)){	
	  
	
		$this->home->cmc_sms($smobile,$sms);
		
		echo 4;
	}else{
		echo 2;
		}}else{
				  echo 1;
			  }
		 }else{
			 
			 
			 $sub="Password Recovery";
	     $smobile = $data['email'];
		  $query3 = $this->db->query(" SELECT * FROM `settings` order by id DESC ");
	$row3 = $query3->row('settings');
	$from= $row3->smtp_username;
		
		 $name=$row3->title;
	       
	         $query1 = $this->db->query("select * from userdetails where email ='$smobile'");
			  if ($query1->num_rows() =='1'){
			$row3 = $query1->row('userdetails');
	
	$username=$row3->username;
	$password = mt_rand(100000,999999); 
	 
	$code = $password;
     $sms="Your new password is ".$code." Please change your password next time you log in. ";
      	 $data1=array('password'=>md5($password));
	   $this->db->where('email',$smobile);
	if($this->db->update('userdetails',$data1)){	
	  
	

     
        
		$this->home->send_mail($from,$name,$smobile,$sub,$sms);
		echo 3;
	}else{
		echo 2;
		}}else{
				  echo 0;
			  }
  	  
			 
			 
			 
			 
			 
			 
			 
			 
			 
			 
		 }
		 }else{
			 
			if($scomunicatn =='sms'){
	
	     $smobile = $data['email'];
	       
	         $query1 = $this->db->query("select * from driver_details where phone ='$smobile'");
			  if ($query1->num_rows() =='1'){
			$row3 = $query1->row('driver_details');
	
	$username=$row3->user_name;
	$password = mt_rand(100000,999999); 
	 
	$code = $password;
     $sms="Your new password is ".$code."Please change your password next time you log in. ";
       $data1=array('password'=>$password);
	   $this->db->where('phone',$smobile);
	if($this->db->update('driver_details',$data1)){	
	  
	
		$this->home->cmc_sms($smobile,$sms);
		
		echo 4;
	}else{
		echo 2;
		}}else{
				  echo 1;
			  }
		 }else{
			 
			 
			 $sub="Password Recovery";
	     $smobile = $data['email'];
		  $query3 = $this->db->query(" SELECT * FROM `settings` order by id DESC ");
	$row3 = $query3->row('settings');
	$from= $row3->smtp_username;
		
		 $name=$row3->title;
	       
	         $query1 = $this->db->query("select * from driver_details where email ='$smobile'");
			  if ($query1->num_rows() =='1'){
			$row3 = $query1->row('driver_details');
	
	$username=$row3->user_name;
	$password = mt_rand(100000,999999); 
	 
	$code = $password;
     $sms="Your new password is ".$code." Please change your password next time you log in. ";
      	 $data1=array('password'=>$password);
	   $this->db->where('email',$smobile);
	if($this->db->update('driver_details',$data1)){	
	  
	

     
        
		$this->home->send_mail($from,$name,$smobile,$sub,$sms);
		echo 3;
	}else{
		echo 2;
		}}else{
				  echo 0;
			  }
  	  
			 
			 
			 
			 
			 
			 
			 
			 
			 
			 
		 } 
			 
			 
		 }
  	 
}







function update_paypal($data){
	
         $bookid =$data['c'];
		 $transaction =$data['a'];
		 $status =$data['b'];
		
		 
        if($username = $this->session->userdata('username')){
        $username = $this->session->userdata('username');
        }else{
        $username = $this->input->cookie('username', false);
        }
       $value = array(
			                'item_status'=>$status,
							'transaction'=>$transaction,
							'status'=>'Booking'
							
							
							
							
			 );
        $this->db->where('username', $username);
		$this->db->where('uneaque_id', $bookid);
        
    if($this->db->update('bookingdetails', $value))
    
{
	$table="userdetails";
	$wallet=$this->session->userdata('wallet-balance');
	$update_data = array(
			'wallet_amount'     => $wallet
		);
		
		$where_datas = array(
			'username'     => $username,
		);
	$this->update_table_where( $update_data, $where_datas, $table);
    
echo 1;
}
else{
    
echo 0;
}
}

   
function update_itemstatus($data){
	
        
		 $item_status =$data['item_status'];
		 
		 
        if($username = $this->session->userdata('username')){
        $username = $this->session->userdata('username');
        }else{
        $username = $this->input->cookie('username', false);
        }
       $value = array(
			                 
							'item_status'=>"Cancelled",
							'status'=>"Cancelled"
							
			 );
        $this->db->where('id', $item_status);
		
        
    if($this->db->update('bookingdetails', $value))
    
{
    
echo 1;
}
else{
    
echo 0;
}
}
function get_values($page){
 $this->db->where('page_name', $page);
  $query = $this->db->get('static_pages');	    
//$query = $this->db->query("SELECT * FROM  static_pages WHERE page_title='$page'");
	return $query->row();
 	
}
function update_contact_us_details($data){
	$query3 = $this->db->query(" SELECT * FROM `settings` order by id DESC ");
	$row3 = $query3->row('settings');
	$email = $row3->smtp_username;;
  $from = $data['email'];
  $msg ="Contact us";
  $subject = $data['message'].'<br>'.'phone :'.$data['phone'];
  $phone = $data['phone'];
  $name=$data['name'];
	
$this->home->send_mail($from,$name,$email,$msg,$subject);

return true;
}
function get_table_where( $select_data, $where_data, $table){
        
		$this->db->select($select_data);
		$this->db->where($where_data);
		$query  = $this->db->get($table);  //--- Table name = User
		$result = $query->result_array(); 
		
		return $result;	
   }	


}
?>