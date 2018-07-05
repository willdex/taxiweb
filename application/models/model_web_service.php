<?php
class model_web_service extends CI_Model
{
    public $zone_name = CUSTOM_ZONE_NAME;

    function __construct()
    {
        parent::__construct();
    }

    function login($request)
    {
        $url = $_SERVER['REQUEST_URI'];
        $email1 = explode('email=', $url);
        $email2 = $email1[1];
        $email3 = explode('&', $email2);
        $email = urldecode($email3[0]);
        $pass = explode('password=', $url);
        $pass1 = $pass[1];
        $pass2 = explode('&', $pass1);
        $password = urldecode($pass2[0]);

//		$email = urldecode($request[email]);
//		$Password = urldecode($request[Password]);

        $table = 'userdetails';
        $select_data = "*";

        $this->db->select($select_data);
        //$this->db->where("(email = '$request->Email' OR username = '$request->Email' OR mobile = '$request->Email' )");
        $this->db->where("(email = '$email' OR username = '$email' OR mobile = '$email' )");
        //$this->db->where("(email = 'shibilabs23@gmail.com' OR username = 'baby' OR mobile = '8559848609' )");

        // $this->db->where('Password', md5($request->Password));
        // $this->db->where('Password','$password');
        //$this->db->where('Password','6848d756da66e55b42f79c0728e351ad');
        $this->db->where('Password', md5($password));

//		$this->db->where('password', $request->Password);
        $query = $this->db->get($table);  //--- Table name = User
        $result = $query->result_array();

        if (count($result) > 0) { // user credential is success

            return $result[0];

        } else { // user credential failed
            return false;
        }

    }

    function update_device_id($device_id, $user_id)
    {
        $table = 'userdetails';

        $update_data = array(
            'device_id' => $device_id
        );

        $where_data = array(
            'id' => $user_id,
        );

        $this->update_table_where($update_data, $where_data, $table);
    }

    function authenticate_key($request)
    {
        $table = 'settings';
        $select_data = "serv_secret_key";

        $this->db->select($select_data);
        //$this->db->where( 'serv_secret_key', $request->secret_key );
        $this->db->where('serv_secret_key', 'My_key');

        $query = $this->db->get($table);  //--- Table name = User
        $result = $query->result_array();

        if (count($result) > 0) { // user credential is success

            return true;

        } else { // user credential failed
            return false;
        }

    }

    function social_login($request)
    {
        $url = $_SERVER['REQUEST_URI'];
        $email1 = explode('email=', $url);
        $email2 = $email1[1];
        $email3 = explode('&', $email2);
        $email = urldecode($email3[0]);
//		$password=$email1[2];
        $table = 'userdetails';
        $select_data = "*";

        $this->db->select($select_data);
        //$this->db->where("(email = '$request->Email' OR username = '$request->Email' OR mobile = '$request->Email' )");
        //$this->db->where("(email = 'kuntham@gmail.com' OR username = 'kuntham@gmail.com' OR mobile = '1222222222' )");
        $this->db->where("(email = '$email' OR username = '$email' OR mobile = '$email' )");

        $query = $this->db->get($table);  //--- Table name = User
        $result = $query->result_array();

        if (count($result) > 0) { // user credential is success

            return $result[0];

        } else { // user credential failed
            return false;
        }

    }
function facebook_login($facebook_id)
    {
        /*$url = $_SERVER['REQUEST_URI'];
        $facebook_id1 = explode('facebook_id=', $url);
        $facebook_id2 = $facebook_id1[1];
        $facebook_id3 = explode('&', $facebook_id2);
        $facebook_id = urldecode($facebook_id3[0]);
        //$pass=explode('password=',$url);
        //$pass1=$pass[1];
        //$pass2=explode('&',$pass1);
        //$password=$pass2[0];*/

        $table = 'userdetails';
        $select_data = "*";

        $this->db->select($select_data);
        $this->db->where("facebook_id",$facebook_id);
        //$this->db->where('Password',md5($password));

        $query = $this->db->get($table);  //--- Table name = User
        //$result = $query->result_array();
        $result = $query->num_rows();
        if ($result > 0) { // user credential is success
            $res= $query->result_array();
            return $res[0];

        } else { // user credential failed
            return false;
        }
    }
    function facebook_login30jun($request)
    {
        $url = $_SERVER['REQUEST_URI'];
        $facebook_id1 = explode('facebook_id=', $url);
        $facebook_id2 = $facebook_id1[1];
        $facebook_id3 = explode('&', $facebook_id2);
        $facebook_id = urldecode($facebook_id3[0]);
        //$pass=explode('password=',$url);
        //$pass1=$pass[1];
        //$pass2=explode('&',$pass1);
        //$password=$pass2[0];

        $table = 'userdetails';
        $select_data = "*";

        $this->db->select($select_data);
        $this->db->where("(facebook_id = '$facebook_id' )");
        //$this->db->where('Password',md5($password));

        $query = $this->db->get($table);  //--- Table name = User
        $result = $query->result_array();

        if (count($result) > 0) { // user credential is success

            return $result[0];

        } else { // user credential failed
            return false;
        }
    }

    public function twitter_login($twitter_id)
    {
        /*$url = $_SERVER['REQUEST_URI'];
        $twitter_id1 = explode('twitter_id=', $url);
        $twitter_id2 = $twitter_id1[1];
        $twitter_id3 = explode('&', $twitter_id2);
        $twitter_id = urldecode($twitter_id3[0]);*/
        //$pass=explode('password=',$url);
        //$pass1=$pass[1];
        //$pass2=explode('&',$pass1);
        //$password=$pass2[0];

        $table = 'userdetails';
        $select_data = "*";

        $this->db->select($select_data);
        $this->db->where("(twitter_id = '$twitter_id' )");
        //$this->db->where('Password',md5($password));

        $query = $this->db->get($table);  //--- Table name = User
        $result = $query->result_array();

        if (count($result) > 0) { // user credential is success

            return $result[0];

        } else { // user credential failed
            return false;
        }
    }

    function fetch_cabs($request)
    {
        $url = $_SERVER['REQUEST_URI'];
        $transfertype1 = explode('transfertype=', $url);
        $transfertype2 = $transfertype1[1];
        $transfertype3 = explode('&', $transfertype2);
        $transfertype = urldecode($transfertype3[0]);
        $timetype1 = explode('timetype=', $url);
        $timetype2 = $timetype1[1];
        $timetype3 = explode('&', $timetype2);
        $timetype = urldecode($timetype3[0]);
        $table = 'cabdetails';
        $select_data = "*";

        $this->db->select($select_data);

        //$this->db->where('transfertype', $request->transfertype);
        //$this->db->where('transfertype', 'Hourly Rental');
        $this->db->where('transfertype', $transfertype);
        //$this->db->where('timetype', $request->timetype);
        //$this->db->where('timetype', 'night');
        $this->db->where('timetype', $timetype);

        $query = $this->db->get($table);  //--- Table name = User
        $result = $query->result_array();

        return $result;

    }
    function get_trip_detail($request)
    {
        $url = $_SERVER['REQUEST_URI'];
        $booking_id1=explode('booking_id=',$url);
        $booking_id2=$booking_id1[1];
        $booking_id3=explode('&',$booking_id2);
        $booking_id=urldecode($booking_id3[0]);
        $table='bookingdetails';
        $select_data='*';
        $this->db->select($select_data);
        $this->db->where('id',$booking_id);
        $query=$this->db->get($table);
        $result=$query->result_array();
        return $result;

    }
    function get_cancel_trip($request)
    {
        $url = $_SERVER['REQUEST_URI'];
        $booking_id1=explode('booking_id=',$url);
        $booking_id2=$booking_id1[1];
        $booking_id3=explode('&',$booking_id2);
        $booking_id=urldecode($booking_id3[0]);
        $table = 'bookingdetails';

        $update_data = array(
            'status' => 'user-cancelled'
        );

        $where_data = array(
            'uneaque_id' => $booking_id,
        );

        $this->update_table_where($update_data, $where_data, $table);


    }
    function get_reject_trip($request)
    {
        $url = $_SERVER['REQUEST_URI'];
        $booking_id1=explode('booking_id=',$url);
        $booking_id2=$booking_id1[1];
        $booking_id3=explode('&',$booking_id2);
        $booking_id=urldecode($booking_id3[0]);
        $table = 'bookingdetails';

        $driver_id1=explode('driver_id=',$url);
        $driver_id2=$driver_id1[1];
        $driver_id3=explode('&',$driver_id2);
        $driver_id=urldecode($driver_id3[0]);

        $update_data = array(
            'status' => '5',
	        'status_code' => 'driver-cancelled'
        );

        $where_data = array(
            'id' => $booking_id,
        );
        $this->update_table_where($update_data, $where_data, $table);

        $update_data1 = array(
            'driver_flag' => '2'
        );
        
        $this->db->where('booking_id',$booking_id);
        $this->db->where('driver_id',$driver_id);
        $this->db->update('driver_status',$update_data1);
    }
	function get_arrived_trip($request)
    {
        $url = $_SERVER['REQUEST_URI'];
        $booking_id1=explode('booking_id=',$url);
        $booking_id2=$booking_id1[1];
        $booking_id3=explode('&',$booking_id2);
        $booking_id=urldecode($booking_id3[0]);
        $table = 'bookingdetails';

        $update_data = array(
            'status' => '7',
            'status_code' => 'driver-arrived'
        );

        $where_data = array(
            'id' => $booking_id,
        );

        $this->update_table_where($update_data, $where_data, $table);
    }
    function get_on_trip($request)
    {
        $url = $_SERVER['REQUEST_URI'];
        $booking_id1=explode('booking_id=',$url);
        $booking_id2=$booking_id1[1];
        $booking_id3=explode('&',$booking_id2);
        $booking_id=urldecode($booking_id3[0]);
        $table = 'bookingdetails';

        $update_data = array(
            'status' => '8',
            'status_code' => 'on-trip'
        );

        $where_data = array(
            'id' => $booking_id,
        );

        $this->update_table_where($update_data, $where_data, $table);
    }
    function get_completed_trip($request)
    {
        $url = $_SERVER['REQUEST_URI'];
        $booking_id1=explode('booking_id=',$url);
        $booking_id2=$booking_id1[1];
        $booking_id3=explode('&',$booking_id2);
        $booking_id=urldecode($booking_id3[0]);
        $table = 'bookingdetails';

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

        $update_data = array(
            'status' => '9',
            'status_code' => 'completed',
            'final_amount' => $final_amt,
            'reason' => $reason
        );

        $where_data = array(
            'id' => $booking_id
        );

        $this->update_table_where($update_data, $where_data, $table);

        $data=array(
            'driver_flag' => '3'
        );

        $this->db->where('driver_id',$driver_id);
        $this->db->where('booking_id',$booking_id);
        $this->db->update('driver_status',$data);
    }
	function driver_unavailable_cancelled_book($request)
    {
        $url = $_SERVER['REQUEST_URI'];
        $booking_id1=explode('booking_id=',$url);
        $booking_id2=$booking_id1[1];
        $booking_id3=explode('&',$booking_id2);
        $booking_id=urldecode($booking_id3[0]);
        $table = 'bookingdetails';

        $update_data = array(
            'status' => '5'
        );

        $where_data = array(
            'id' => $booking_id,
        );

        $this->update_table_where($update_data, $where_data, $table);
    }
	 function user_reject_trip($request)
    {
        $url = $_SERVER['REQUEST_URI'];
        $booking_id1=explode('booking_id=',$url);
        $booking_id2=$booking_id1[1];
        $booking_id3=explode('&',$booking_id2);
        $booking_id=urldecode($booking_id3[0]);
        $table = 'bookingdetails';

        $update_data = array(
        'status' => '4',
	     'status_code' => 'user-cancelled'
        );

        $where_data = array(
            'id' => $booking_id,
        );

        $this->update_table_where($update_data, $where_data, $table);


    }
    function get_accept_trip($request)
    {
        $url = $_SERVER['REQUEST_URI'];
        $booking_id1=explode('booking_id=',$url);
        $booking_id2=$booking_id1[1];
        $booking_id3=explode('&',$booking_id2);
        $booking_id=urldecode($booking_id3[0]);

	    $driver_id1=explode('driver_id=',$url);
        $driver_id2=$driver_id1[1];
        $driver_id3=explode('&',$driver_id2);
        $driver_id=urldecode($driver_id3[0]);
        $table = 'bookingdetails';

        if($booking_id!='' && $driver_id!='')
        {
            $update_data = array(
            'status' => '3',
    	    'status_code' => 'accepted',
    	    'assigned_for' => $driver_id
            );

            $where_data = array(
                'id' => $booking_id,
            );

            $this->update_table_where($update_data, $where_data, $table);

            $table1='driver_status';
            $where_data1 = array(
                'booking_id' =>$booking_id,
                'driver_id' => $driver_id
            );
            $update_data1 = array(
                'driver_flag' => '1',
            );
            $this->update_table_where($update_data1, $where_data1, $table1);
            
        }
    }

   function load_trips($request)
    {
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
        

        $this->db->select('*');
        $this->db->from('bookingdetails');
        $this->db->join('cabdetails', 'cabdetails.cartype = bookingdetails.taxi_type');
        $this->db->where('bookingdetails.user_id', $id);
        $this->db->order_by("bookingdetails.id", "desc");
        $this->db->limit($lim,$off);
        $query = $this->db->get();

        $result = $query->result_array();
        $result = $query->result_array();

        $perpageTmp = $off;
        $perpage = '';
        if ($perpageTmp != '') {
            $perpage = $perpageTmp;
        } else {
            $perpage = 10;
        }

        $num = $query->num_rows();

        $new_off = $off + $num;

        return $result;
	return $new_off;
        //$finresult['offset']=$new_off;

       //print json_encode($finresult);

    }
    function load_all_cabs($request)
    {
        $url = $_SERVER['REQUEST_URI'];
        $transfertype1 = explode('transfertype=', $url);
        $transfertype2 = $transfertype1[1];
        $transfertype3 = explode('&', $transfertype2);
        $transfertype = urldecode($transfertype3[0]);

        $table = 'cabdetails';
        $select_data = "*";

        $this->db->select($select_data);

        //$this->db->where('transfertype', $request->transfertype );
        //$this->db->where('transfertype','day');
        $this->db->where('transfertype', $transfertype);

        $query = $this->db->get($table);  //--- Table name = User
        $result = $query->result_array();

        return $result;

    }

    function load_settings()
    {

        $table = 'settings';
        $select_data = "country,places";

        $this->db->select($select_data);

        $this->db->where('id', 1);

        $query = $this->db->get($table);  //--- Table name = User
        $result = $query->result_array();

        return $result;

    }

    function get_forgot_password($request)
    {

        $url = $_SERVER['REQUEST_URI'];
        $email1=explode('email=',$url);
        $email2=$email1[1];
        $email3=explode('&',$email2);
        $email=urldecode($email3[0]);
        $uid1 = explode('uid=', $url);
        $uid2 = $uid1[1];
        $uid3 = explode('&', $uid2);
        $uid = urldecode($uid3[0]);

    	$str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $newPassword = '';
        for ($i = 0; $i < 8; $i++) $newPassword .= substr($str, rand(0, strlen($str)), 1);
        //echo $newPassword;
    	$password=md5($newPassword);

        $this->db->select('email');
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
        if($email_user == $email)
        {

            $data = $this->update_table_where($update_data, $where_data, $table);

            // echo $data;
            if (count($data) > 0) 
		    {
		 
                $result_details = $this->model_web_service->get_table_where($select_data, $where_data, $table);
                if($result_details)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
               return false;
            }
        }
	    else
        {
            return false;
        }
    }
	 function driver_profile_edit($username, $email,$phone,$img, $password, $name, $license_no, $Lieasence_Expiry_Date, $license_plate, $Insurance, $Seating_Capacity, $Car_Model, $Car_Make, $car_no, $car_type, $address, $gender, $dob,$uid)
    	{   	   
	
        $table = 'driver_details';
        $select_data='*';

        $update_data = array(
	   'name' => $name,
            'user_name' => $username,
'password' => $password,
'phone' => $phone,
 'address'	    => $address,
'email' => $email,
'license_no' => $license_no,
'car_type'	      => $car_type,
'car_no'	      =>  $car_no,
'gender'	    => $gender,
'dob'	  => $dob,
'status'	  => 'Active',
'Lieasence_Expiry_Date' => $Lieasence_Expiry_Date,
'license_plate' => $license_plate,            
'Insurance' => $Insurance,
'image' => $img,
'Seating_Capacity' => $Seating_Capacity,
'Car_Model' => $Car_Model,
'Car_Make' => $Car_Make
        );

        $where_data = array(
            'id' => $uid,
        );

       // $data= $this->update_table_where($update_data, $where_data, $table);
        $status="SELECT * FROM `driver_details` where id=$uid";
        $statusrs=mysql_query($status);
        $datastatus=mysql_fetch_array($statusrs);
        $statuschk=$datastatus['status'];
        if($statuschk=='Active')
        {
            // echo $data;
            if($this->update_table_where($update_data, $where_data, $table))
            {
                    $table_setting_details ='settings';
                    $result_setting = $this->model_web_service->get_table('country,currency',  $table_setting_details);
                    $result_details = $this->get_table_where($select_data,$where_data,$table);
                    $finresult['status'] = 'success';
                    $finresult['message'] = 'Your profile details have been successfully updated.';
                    $finresult['Driver_detail'] = $result_details;
                    $finresult['country_detail'] = $result_setting;
                    print json_encode($finresult);
            }
            else
            {
                    $finresult['status'] ='failed'; 
                    $finresult['message']='Please enter correct login details';
                    $finresult['code']='profile Update failed';
                    print json_encode($finresult);
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

    }
    function get_profile_edit($username,$email,$mobile,$name,$gender,$dob,$isdevice,$img,$uid)
    {
        $table = 'userdetails';
        $select_data='*';

        $update_data = array(
            'image'	  => $img,
	   // 'facebook_id' => $facebook_id,
            //'twitter_id' => $twitter_id,
            'name' => $name,
            'username' => $username,
            'gender' => $gender,
            'mobile' => $mobile,
            'dob'   => $dob,
            'email' => $email,
            //'password' => md5($password),
            'user_status' => "Active",
            'isdevice' => $isdevice,
        );

        $where_data = array(
            'id' => $uid,
        );

        if ($this->update_table_where($update_data, $where_data, $table)) {
                    $table="userdetails";
                    $select_data="*";
                    $where_data=array(
                        'id' => $uid
                        );
                    $result_details = $this->get_table_where($select_data, $where_data, $table);
                    $finresult['status'] = 'success';
                    $finresult['message'] = 'Your profile details have been successfully updated.';
                    $finresult['Isactive'] = $statuschk;
                    $finresult['user_detail'] = $result_details;

                    print json_encode($finresult);
        }
        else
        {
                    $finresult['status'] = 'failed';
                    // $user['query1'] =$query;
                    $finresult['message'] = 'Please enter correct login details';
		    $finresult['error code']='2';
                    $finresult['Isactive'] = $statuschk;
                    $finresult['code'] = 'profile Update failed';
                    print json_encode($finresult);
        }
    }


    function is_mail_exists($mail,$uid)
    {
        /* function return
         ---------------------------------
         'true'   if user exist
         'false'  if user does not exist

        */
        $table = 'userdetails';
        $select_data = "*";

        $this->db->select($select_data);

       // $this->db->where('user_name',$username);
        $this->db->where('email',$mail);
        if($uid!="")
        {
            $this->db->where('id !=', $uid);
        }
        $query = $this->db->get($table);  //--- Table name = User
        $result = $query->num_rows();

        if ($result > 0) {
            return true;  //already exist
        } else {
            return false; //Not exist
        }
    }

    function is_username_exists($user_name,$uid)
    {
        /* function return
         ---------------------------------
         'true'   if user exist
         'false'  if user does not exist

        */
        $table = 'userdetails';
        $select_data = "*";

        $this->db->select($select_data);

       // $this->db->where('user_name',$username);
        $this->db->where('username',$user_name);
        if($uid!="")
        {
            $this->db->where('id !=', $uid);
        }
        $query = $this->db->get($table);  //--- Table name = User
        $result = $query->num_rows();

        if ($result > 0) {
            return true;  //already exist
        } else {
            return false; //Not exist
        }
    }

    function is_mobile_exists($mobile,$uid)
    {
        /* function return
         ---------------------------------
         'true'   if mobile exist
         'false'  if mobile does not exist

        */
         $table = 'userdetails';
        $select_data = "*";

        $this->db->select($select_data);

       // $this->db->where('user_name',$username);
        $this->db->where('mobile',$mobile);
        if($uid!="")
        {
            $this->db->where('id !=', $uid);
        }
        $query = $this->db->get($table);  //--- Table name = User
        $result = $query->num_rows();

        if ($result > 0) {
            return true;  //already exist
        } else {
            return false; //Not exist
        }
    }

    function insert_user_details($username,$email,$mobile,$password,$name,$gender,$dob,$img,$facebook_id,$twitter_id,$isdevice)
    {
        $table = 'userdetails';
        $insert_data = array(
            'image'	  => $img,
	       'facebook_id' => $facebook_id,
            'twitter_id' => $twitter_id,
            'name' => $name,
            'username' => $username,
            'gender' => $gender,
            'mobile' => $mobile,
            'dob'   => $dob,
            'email' => $email,
            //'password'	  => md5 ($request->Password),
            'password' => md5($password),
            'user_status' => "Active",
            'isdevice' => $isdevice,
//'device_id'	=> $request->device_id,
        );
        //echo 'tes11'.$insert_data[0];

        $user = $this->insert_table($insert_data, $table);
        if ($insert_data) {
           
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
    function insert_facebook_user($request)
    {
        $url = $_SERVER['REQUEST_URI'];
        $facebook_id1 = explode('facebook_id=', $url);
        $facebook_id2 = $facebook_id1[1];
        $facebook_id3 = explode('&', $facebook_id2);
        $facebook_id = urldecode($facebook_id3[0]);

        $device_token1 = explode('device_token=', $url);
        $device_token2 = $device_token1[1];
        $device_token3 = explode('&', $device_token2);
        $device_token = urldecode($device_token3[0]);

        $is_device1 = explode('is_device=', $url);
        $is_device2 = $is_device1[1];
        $is_device3 = explode('&', $is_device2);
        $is_device = urldecode($is_device3[0]);
        //$pass=explode('password=',$url);
        //$pass1=$pass[1];
        //$pass2=explode('&',$pass1);
        //$password=$pass2[0];
        $table = 'userdetails';
        $insert_data = array(
            //'username'	  => $request->Email,
            'facebook_id' => $facebook_id,
            'device_token' => $device_token,
            'isdevice' => $is_device,
            //'password'        =>md5($password),
            'user_status' => "Active",

        );

        $ins = $this->insert_table($insert_data, $table);
    }
    function insert_facebook_user30jun($request)
    {
        $url = $_SERVER['REQUEST_URI'];
        $facebook_id1 = explode('facebook_id=', $url);
        $facebook_id2 = $facebook_id1[1];
        $facebook_id3 = explode('&', $facebook_id2);
        $facebook_id = urldecode($facebook_id3[0]);
        //$pass=explode('password=',$url);
        //$pass1=$pass[1];
        //$pass2=explode('&',$pass1);
        //$password=$pass2[0];
        $table = 'userdetails';
        $insert_data = array(
            //'username'	  => $request->Email,
            'facebook_id' => $facebook_id,
            //'password'        =>md5($password),
            'user_status' => "Active",

        );

        $ins = $this->insert_table($insert_data, $table);
    }

    function insert_twitter_user($request)
    {
        $url = $_SERVER['REQUEST_URI'];
        $twitter_id1 = explode('twitter_id=', $url);
        $twitter_id2 = $twitter_id1[1];
        $twitter_id3 = explode('&', $twitter_id2);
        $twitter_id = urldecode($twitter_id3[0]);
        //$pass=explode('password=',$url);
        //$pass1=$pass[1];
        //$pass2=explode('&',$pass1);
        //$password=$pass2[0];

        $table = 'userdetails';
        $insert_data = array(
            //'username'	  => $request->Email,
            'twitter_id' => $twitter_id,
            //'password'        =>md5($password),
            'user_status' => "Active",

        );

        $ins = $this->insert_table($insert_data, $table);


    }

    function insert_user_social($request)
    {
        $url = $_SERVER['REQUEST_URI'];
        $email1 = explode('=', $url);
        $email2 = $email1[1];
        $email3 = explode('&', $email2);
        $email = urldecode($email3[0]);

        $table = 'userdetails';
        $insert_data = array(
            //'username'	  => $request->Email,
            'username' => $email,
            'user_status' => "Active",

        );

        $ins = $this->insert_table($insert_data, $table);


    }
	

    function book($user_id,$username,$pickup_date_time,$drop_area,$pickup_area,$time_type,$amount,$km,$pickup_lat,$pickup_longs,$drop_lat,$drop_longs,$isdevice,$approx_time,$flag,$taxi_type,$taxi_id,$purpose,$comment,$person,$payment_type,$transaction_id,$book_create_date_time)
    {
        /*$url = $_SERVER['REQUEST_URI'];
        $username1 = explode("username=", $url);
        $username2 = $username1[1];
        $username3 = explode('&', $username2);
        $username = urldecode($username3[0]);
      
        $pickup_date1 = explode("pickup_date_time=", $url);
        $pickup_date2 = $pickup_date1[1];
        $pickup_date3 = explode('&', $pickup_date2);
       $pickup_date_time = urldecode($pickup_date3[0]);
        $pickup_time1 = explode("pickup_time=", $url);
        $pickup_time2 = $pickup_time1[1];
        $pickup_time3 = explode('&', $pickup_time2);
        $pickup_time = urldecode($pickup_time3[0]);
        $drop_area1 = explode("drop_area=", $url);
        $drop_area2 = $drop_area1[1];
        $drop_area3 = explode('&', $drop_area2);
        $drop_area = urldecode($drop_area3[0]);

        $pickup_area1 = explode("pickup_area=", $url);
        $pickup_area2 = $pickup_area1[1];
        $pickup_area3 = explode('&', $pickup_area2);
        $pickup_area = urldecode($pickup_area3[0]);

        $taxi_type1 = explode("taxi_type=", $url);
        $taxi_type2 = $taxi_type1[1];
        $taxi_type3 = explode('&', $taxi_type2);
        $taxi_type = urldecode($taxi_type3[0]);
        $time_type1 = explode("time_type=", $url);
        $time_type2 = $time_type1[1];
        $time_type3 = explode('&', $time_type2);
        $time_type = urldecode($time_type3[0]);
        $amount1 = explode("amount=", $url);
        $amount2 = $amount1[1];
        $amount3 = explode('&', $amount2);
        $amount = urldecode($amount3[0]);
        $km1 = explode("km=", $url);
        $km2 = $km1[1];
        $km3 = explode('&', $km2);
        $km = urldecode($km3[0]);
        $purpose1 = explode('purpose=', $url);
        $purpose2 = $purpose1[1];
        $purpose3 = explode('&', $purpose2);
        $purpose = urldecode($purpose3[0]);
        $comment1=explode('comment=',$url);
        $comment2=$comment1[1];
        $comment3=explode('&',$comment2);
        $comment=urldecode($comment3[0]);
	
        $isdevice1=explode("isdevice=",$url);
	$isdevice2=$isdevice1[1];
	$isdevice3=explode('&',$isdevice2);
	$isdevice=urldecode($isdevice3[0]);
	
	$pickup_lat1=explode("pickup_lat=",$url);
	$pickup_lat2=$pickup_lat1[1];
	$pickup_lat3=explode('&',$pickup_lat2);
	 $pickup_lat=urldecode($pickup_lat3[0]);

	$pickup_long1=explode("pickup_longs=",$url);
	$pickup_long2=$pickup_long1[1];
	$pickup_long3=explode('&',$pickup_long2);
	$pickup_long=urldecode($pickup_long3[0]);

	$drop_lat1=explode("drop_lat=",$url);
	$drop_lat2=$drop_lat1[1];
	$drop_lat3=explode('&',$drop_lat2);
	$drop_lat=urldecode($drop_lat3[0]);
		
	$drop_longs1=explode("drop_longs=",$url);
	$drop_longs2=$drop_longs1[1];
	$drop_longs3=explode('&',$drop_longs2);
	$drop_long=urldecode($drop_longs3[0]);
	
	$flag1=explode("flag=",$url);
	$flag2=$flag1[1];
	$flag3=explode('&',$flag2);
	$flag=urldecode($flag3[0]);

	$person1=explode("person=",$url);
	$person2=$person1[1];
	$person3=explode('&',$person2);
	$person=urldecode($person3[0]);

	$payment_type1=explode("payment_type=",$url);
	$payment_type2=$payment_type1[1];
	$payment_type3=explode('&',$payment_type2);
	$payment_type=urldecode($payment_type3[0]);

	$transaction_id1=explode("transaction_id=",$url);
	$transaction_id2=$transaction_id1[1];
	$transaction_id3=explode('&',$transaction_id2);
	$transaction_id=urldecode($transaction_id3[0]);

	$user_id1=explode("user_id=",$url);
	$user_id2=$user_id1[1];
	$user_id3=explode('&',$user_id2);
	$user_id=urldecode($user_id3[0]);*/
        $table = 'bookingdetails';
        $insert_data = array(
            //'username'	  => $request->token,
            'username' => $username,           
            'purpose' => $purpose,            
            'pickup_date_time' => $pickup_date_time,           
            'drop_area' => $drop_area,
            //'pickup_area' => $request->pickup_area,
            'pickup_area' => $pickup_area,
            //'taxi_type'   => $request->taxi_type,
            'taxi_type' => $taxi_type,
            'taxi_id' => $taxi_id,
            'status' => "1",
	       'status_code' => "pending",
             //'book_create_date_time' => date('Y-m-d H:i:s'),

            //'timetype'		=> $request->timetype,
            'timetype' => $time_type,
            //'amount'		  => $request->amount,
            'amount' => $amount,
            'comment' => $comment,           
            'km' => $km,
	    'pickup_lat' => $pickup_lat,
	    'pickup_long' => $pickup_longs,
	    'drop_lat' => $drop_lat,
	    'drop_long' => $drop_longs,
	    'flag'=>$flag,
	    'person'=>$person,
	    'payment_type'=>$payment_type,
	    'transaction_id'=>$transaction_id,
	    'user_id'=>$user_id,
	    'isdevice' => $isdevice,
        'approx_time' => $approx_time
        );
	//print_r($insert_data);

         $this->insert_table($insert_data, $table);

    }

    // find push user
    function findpushuser($booking_id)
    {
        $this->db->where('id',$booking_id);
        $query=$this->db->get('bookingdetails')->row();
        $row=$query->user_id;
        return $row;
    }

    /* ------------------- COMMON --------------------------------------------------------
    ******************************************************************************************/

    /*  WHETHER TABLE EXIST A DATA
    ===================================================*/
    function is_exists($data, $table)
    {
        $this->db->where($data);
        $query = $this->db->get($table);

        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /*	INSERT INTO TABLE
     *=========================================================================*/
    function insert_table($insert_data, $table)
    {
        $this->db->insert($table, $insert_data);
    }

    /* GET FROM TABLE
     *=====================================*/

    function get_table_where($select_data, $where_data, $table)
    {

        $this->db->select($select_data);
        $this->db->where($where_data);
        $query = $this->db->get($table);  //--- Table name = User
        $result = $query->result_array();
        if($result){
            return $result;
        }
        else{
            return false;
        }
    }

    /* GET WHERE IN*/
    function get_table_where_in_Q($select_data, $where_data, $table)
    {

        $this->db->select($select_data);
        $this->db->where_in('Q_id', $where_data);
        $query = $this->db->get($table);  //--- Table name = User
        $result = $query->result_array();
        return $result;
    }

    /* GET FROM TABLE OR WHERE
    *=====================================*/

    function get_table_or_where($select_data, $where_data, $or_where_data, $table)
    {

        $this->db->select($select_data);
        $this->db->where($where_data);
        $this->db->or_where($or_where_data);
        $query = $this->db->get($table);  //--- Table name = User
        $result = $query->result_array();
        return $result;
    }

    /* UPDATE TABLE
    ===================================*/
    function update_table_where($update_data, $where_data, $table)
    {
        $this->db->where($where_data);
       // $this->db->update($table, $update_data);
        $data=$this->db->update($table, $update_data);
        if($data == 1)
        {
            return true;
        }
        else
        {
            return false;
        }


    }

    /* JOIN TABLE
    =======================*/
    function get_table_join($select_data, $table, $join_table, $join_data, $join_type, $where_data)
    {

        $this->db->select($select_data);
        $this->db->from($table);
        $this->db->join($join_table, $join_data, $join_type);
        $this->db->where($where_data);
        $this->db->order_by("sub1_id", "asc");

        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function delete_roles($id)
    {

        $table = 'user_role';
        $where_data = array('User_Id' => $id);

        $this->delete_table($table, $where_data);
    }

    function delete_instituts($id)
    {

        $table = 'users_institutions';
        $where_data = array('User_Id' => $id);

        $this->delete_table($table, $where_data);
    }

    function delete_table($table, $where_data)
    {
        $this->db->delete($table, $where_data);
    }

    /* Arun common */
    /*	INSERT INTO TABLE with Return
    *=========================================================================*/
    function insert_table_r($insert_data, $table)
    {
        return $this->db->insert($table, $insert_data);
    }

    /*Web services for call my cab driver App ****Edited by shajeer*/

    function driverlogin($username,$password)
    {
        /*$url = $_SERVER['REQUEST_URI'];
        $username1 = explode("username=", $url);
        $username2 = $username1[1];
        $username3 = explode("&", $username2);
        $username = urldecode($username3[0]);
        $password1 = explode("password=", $url);
        $password2 = $password1[1];
        $password3 = explode('&', $password2);
        $password = urldecode($password3[0]);*/
        $table = 'driver_details';
        $select_data = "*";

        $this->db->select($select_data);
        //$this->db->where("(user_name = '$request->Email' )");
        //$this->db->where("(user_name = 'sha@gmail.com' )");
        $this->db->where("user_name",$username);
        //$this->db->where('password', $request->Password);
        //$this->db->where('password', 'sha123');
        $this->db->where("password", $password);
        $query = $this->db->get($table);  //--- Table name = User
        $result = $query->num_rows();

        if ($result > 0) { // user credential is success
            return true;
        } else { // user credential failed
            return false;
        }
    }
  function driver_sign_up_model($username, $email, $phone, $password, $name, $license_no, $Lieasence_Expiry_Date, $license_plate, $Insurance, $Seating_Capacity, $Car_Model, $Car_Make, $car_no, $car_type, $address, $gender, $dob, $img)
    {
	//$newpassword=md5($password);
        $table = 'driver_details';
        $insert_data = array(
 'name' => $name,
  'user_name' => $username,
'password' => $password,
'phone' => $phone,
 'address'	    => $address,
'email' => $email,
'license_no' => $license_no,
'car_type'	=> $car_type,
'car_no'    =>  $car_no,
'gender'	    => $gender,
'dob'	  => $dob,
'status'	  => 'Active',
'Lieasence_Expiry_Date' => $Lieasence_Expiry_Date,
'license_plate' => $license_plate,            
'Insurance' => $Insurance,
'image' => $img,
'Seating_Capacity' => $Seating_Capacity,
'Car_Model' => $Car_Model,
'Car_Make' => $Car_Make
        );
        return $this->db->insert($table, $insert_data);

    }
   

     function driver_id_exist($email,$username)
    {
        /*$url = $_SERVER['REQUEST_URI'];
        $url = $_SERVER['REQUEST_URI'];
        $username1 = explode("username=", $url);
        $username2 = $username1[1];
        $username3 = explode("&", $username2);
        $username = urldecode($username3[0]);

        $email1 = explode("email=", $url);
        $email2 = $email1[1];
        $email3 = explode("&", $email2);
        $email = urldecode($email3[0]);*/

        $table = 'driver_details';
        $select_data = "*";

        $this->db->select($select_data);

       // $this->db->where('user_name',$username);
        $this->db->where('email',$email);
        $this->db->or_where('user_name',$username);
        $query = $this->db->get($table);  //--- Table name = User
        $result = $query->num_rows();

        if ($result > 0) {
            return true;  //already exist
        } else {
            return false; //Not exist
        }
    }
    function driver_email_id_exist($email,$uid)
    {
        $table = 'driver_details';
        $select_data = "*";

        $this->db->select($select_data);

       // $this->db->where('user_name',$username);
        $this->db->where('email',$email);
        if($uid!="")
        {
            $this->db->where('id !=', $uid);
        }
        $query = $this->db->get($table);  //--- Table name = User
        $result = $query->num_rows();

        if ($result > 0) {
            return true;  //already exist
        } else {
            return false; //Not exist
        }
    }
    function driver_user_id_exist($username,$uid)
    {
        $table = 'driver_details';
        $select_data = "*";

        $this->db->select($select_data);

       // $this->db->where('user_name',$username);
        $this->db->where('user_name',$username);
        if($uid!="")
        {
            $this->db->where('id !=', $uid);
        }
        $query = $this->db->get($table);  //--- Table name = User
        $result = $query->num_rows();

        if ($result > 0) {
            return true;  //already exist
        } else {
            return false; //Not exist
        }
    }
    function driver_car_no_exist($car_no,$uid)
    {
        $table = 'driver_details';
        $select_data = "*";

        $this->db->select($select_data);

       // $this->db->where('user_name',$username);
        $this->db->where('car_no',$car_no);
        if($uid!="")
        {
            $this->db->where('id !=', $uid);
        }
        $query = $this->db->get($table);  //--- Table name = User
        $result = $query->num_rows();

        if ($result > 0) {
            return true;  //already exist
        } else {
            return false; //Not exist
        }
    }

    function driver_license_no_exist($license_no,$uid)
    {
        $table = 'driver_details';
        $select_data = "*";

        $this->db->select($select_data);

       // $this->db->where('user_name',$username);
        $this->db->where('license_no',$license_no);
        if($uid!="")
        {
            $this->db->where('id !=', $uid);
        }
        $query = $this->db->get($table);  //--- Table name = User
        $result = $query->num_rows();

        if ($result > 0) {
            return true;  //already exist
        } else {
            return false; //Not exist
        }
    }

    function driver_phone_no_exist($phone_no,$uid)
    {
        $table = 'driver_details';
        $select_data = "*";

        $this->db->select($select_data);

       // $this->db->where('user_name',$username);
        $this->db->where('phone',$phone_no);
        if($uid!="")
        {
            $this->db->where('id !=', $uid);
        }
        $query = $this->db->get($table);  //--- Table name = User
        $result = $query->num_rows();

        if ($result > 0) {
            return true;  //already exist
        } else {
            return false; //Not exist
        }
    }

    function driver_bookings($request)
    {
        $url = $_SERVER['REQUEST_URI'];
        $driver_id1 = explode('driver_id=', $url);
        $driver_id2 = $driver_id1[1];
        $driver_id3 = explode('&', $driver_id2);
        $driver_id = urldecode($driver_id3[0]);
        $table = 'bookingdetails';
        $select_data = "*";

        $this->db->select($select_data);
        //$this->db->where("(assigned_for = '$request->driver_id' )");
        //$this->db->where('assigned_for', $request->driver_id);
        $this->db->where('assigned_for', $driver_id);
        $this->db->order_by("pickup_date", "asc");
        $query = $this->db->get($table);  //--- Table name = User
        $result = $query->result_array();

        return $result;


    }

    /* GET ALL RECORD FROM TABLE
 *=====================================*/
    //function get_table( $select_data, $where_data, $table){
    function get_table($select_data, $table)
    {

        $this->db->select($select_data);
        //$this->db->where($where_data);
        $query = $this->db->get($table);  //--- Table name = User
        $result = $query->result_array();
        return $result;
    }

    function update_driver_password($request)
    {
        $url = $_SERVER['REQUEST_URI'];
        $pass1 = explode('Password=', $url);
        $pass2 = urldecode($pass1[1]);
        $pass3 = explode('&', $pass2);
        $Password = urldecode(md5($pass3[0]));
        $username1 = explode('username=', $url);
        $username2 = $username1[1];
        $username3 = explode('&', $username2);
        $username = urldecode($username3[0]);
        $uid1 = explode('uid=', $url);
        $uid2 = $uid1[1];
        $uid3 = explode('&', $uid2);
        $uid = urldecode($uid3[0]);
//		$table = 'driver_details';
//
//		$select_data = "*";
//
//		$this->db->select($select_data);
//		//$this->db->where("(user_name= '$request->username' )");
//		$this->db->where("(user_name= '$username' )");
//		//$this->db->where('password', $request->old_pass);
//		$this->db->where('password', $Password);
//		$query  = $this->db->get($table);
//		$result = $query->result_array();

//		//if(count($result) > 0){
//		if($result){
//			// user credential is success
//
//			     $update_data = array(
//			//'password'     => $request->Password
//			'password'     => $Password
//		               );
//
//		         $where_data = array(
//			      //'user_name'            => $request->username,
//			      'user_name'            => $user_name,
//		              );
//
//		         // $this->update_table_where( $update_data, $where_data, $table);
//			//"UPDATE MyGuests SET lastname='Doe' WHERE id=2
        $table = 'driver_details';

        $update_data = array(
            //'password'     => md5($request->Password )
            'password' => md5($Password)
        );

        $where_data = array(
            //'username'            => $request->token,
            'username' => $username,
        );

        //$upt=$this->update_table_where( $update_data, $where_data, $table);
        //"UPDATE MyGuests SET lastname='Doe' WHERE id=2
        $upt = "update userdetails set username='$username' , password='$Password' where id='$uid' ";
        $qr = mysql_query($upt);
        if ($qr != '') {
            return 1;
        }
    }


    /* GET COMPLETED BOOKINGS FROM TABLE
    *=====================================*/
    function getcompletedbookings($driver_id)
    {
        $query=$this->db->query("SELECT driver_status.driver_id, driver_status.booking_id, bookingdetails.final_amount FROM driver_status JOIN bookingdetails ON driver_status.booking_id = bookingdetails.id WHERE driver_status.driver_flag=3 AND bookingdetails.status=9 AND driver_status.driver_id='$driver_id'");
        $result=$query->result_array();
        if($result)
        {
            return $result;
        }
        else{
            return 0;
        }
    }

    function gettotalearnings($driver_id)
    {
        $query=$this->db->query("SELECT driver_status.driver_id, SUM(bookingdetails.final_amount) as sum_amount FROM driver_status JOIN bookingdetails ON driver_status.booking_id = bookingdetails.id WHERE driver_status.driver_flag=3 AND bookingdetails.status=9 AND driver_status.driver_id='$driver_id'");
        $result=$query->result_array();
        if($result[0]['sum_amount'])
        {
            return $result;
        }
        else{
            return 0;
        }
    }

    function getlastmonthdriverstats($driver_id)
    {
        $query=$this->db->query("SELECT driver_status.driver_id, SUM(bookingdetails.final_amount) as sum_amount FROM driver_status JOIN bookingdetails ON driver_status.booking_id = bookingdetails.id WHERE driver_status.driver_flag=3 AND bookingdetails.status=9 AND (driver_status.end_time > DATE_SUB(NOW(), INTERVAL 1 MONTH)) AND driver_status.driver_id='$driver_id'");
        $result=$query->result_array();
        if($result[0]['sum_amount'])
        {
            return $result;
        }
        else{
            return 0;
        }
    }

    function getlastweekdriverstats($driver_id)
    {
        $query=$this->db->query("SELECT driver_status.driver_id, SUM(bookingdetails.final_amount) as sum_amount FROM driver_status JOIN bookingdetails ON driver_status.booking_id = bookingdetails.id WHERE driver_status.driver_flag=3 AND bookingdetails.status=9 AND (driver_status.end_time > DATE_SUB(NOW(), INTERVAL 1 WEEK)) AND driver_status.driver_id='$driver_id'");
        $result=$query->result_array();
        if($result[0]['sum_amount'])
        {
            return $result;
        }
        else{
            return 0;
        }
    }

    function gettotalrides($driver_id)
    {
        //$query=$this->db->query("SELECT driver_status.driver_id, driver_status.booking_id, bookingdetails.final_amount FROM driver_status JOIN bookingdetails ON driver_status.booking_id = bookingdetails.id WHERE driver_status.driver_flag=3 AND bookingdetails.status=9 AND driver_status.driver_id='$driver_id'");
        $query=$this->db->query("SELECT driver_status.driver_id,driver_status.booking_id, COUNT(bookingdetails.id) as total_rides FROM driver_status JOIN bookingdetails ON driver_status.booking_id = bookingdetails.id WHERE driver_status.driver_flag=3 AND bookingdetails.status=9 AND driver_status.driver_id='$driver_id' GROUP BY driver_status.booking_id");
        $result=$query->result_array();
        if($result[0]['total_rides'])
        {
            return $result;
        }
        else{
            return 0;
        }
    }

    function getlastmonthrides($driver_id)
    {
        $query=$this->db->query("SELECT driver_status.driver_id, driver_status.booking_id, COUNT(bookingdetails.id) as total_rides FROM driver_status JOIN bookingdetails ON driver_status.booking_id = bookingdetails.id WHERE driver_status.driver_flag=3 AND bookingdetails.status=9 AND (driver_status.end_time > DATE_SUB(NOW(), INTERVAL 1 MONTH)) AND driver_status.driver_id='$driver_id' GROUP BY driver_status.booking_id");
        $result=$query->result_array();
        if($result[0]['total_rides'])
        {
            return $result;
        }
        else{
            return 0;
        }
    }

    function getlastweekrides($driver_id)
    {
        $query=$this->db->query("SELECT driver_status.driver_id, driver_status.booking_id, COUNT(bookingdetails.id) as total_rides FROM driver_status JOIN bookingdetails ON driver_status.booking_id = bookingdetails.id WHERE driver_status.driver_flag=3 AND bookingdetails.status=9 AND (driver_status.end_time > DATE_SUB(NOW(), INTERVAL 1 WEEK)) AND driver_status.driver_id='$driver_id' GROUP BY driver_status.booking_id");
        $result=$query->result_array();
        if($result[0]['total_rides'])
        {
            return $result;
        }
        else{
            return 0;
        }
    }

    function getridesratio($driver_id,$accepted_rides)
    {
        $query=$this->db->query("SELECT driver_status.driver_id,driver_status.booking_id, COUNT(bookingdetails.id) as total_rides FROM driver_status JOIN bookingdetails ON driver_status.booking_id = bookingdetails.id WHERE driver_status.driver_id='$driver_id'");
        $result=$query->result_array();
        if($result[0]['total_rides'])
        {
            $new_result['ratio']=(($accepted_rides * 100) / $result[0]['total_rides']);
            return $new_result;
        }
        else{
            return 0;
        }
    }
    /*Callmycab driver app webservice ends here */


    // fetchbookingcron call
    function fetchbookingcron()
    {
        //$zone_name = 'Asia/Calcutta';
        $date = new DateTime("now", new DateTimeZone($this->zone_name));
        $startTime = $date->format('Y-m-d H:i:s');
        //echo 'CURRENT TIME: '.$startTime.'<br/>';
        $date = new DateTime("now", new DateTimeZone($this->zone_name));
        $date->sub(new DateInterval('PT15M'));
        $beforeTime = $date->format('Y-m-d H:i:s');
        //echo 'BEFORE TIME: '.$beforeTime.'<br/>';
        $date = new DateTime("now", new DateTimeZone($this->zone_name));
        $date->add(new DateInterval('PT15M'));
        $afterTime = $date->format('Y-m-d H:i:s');

        $onlydate = new DateTime("now", new DateTimeZone($this->zone_name));
        $getonlyDate = $onlydate->format('Y-m-d');

        //echo 'AFTER TIME: '.$afterTime.'<br/>';

        //$query=$this->db->query("SELECT bookingdetails.id,bookingdetails.pickup_lat,bookingdetails.pickup_long,driver_status.driver_id,driver_status.driver_flag from bookingdetails LEFT JOIN driver_status ON bookingdetails.id=driver_status.booking_id WHERE bookingdetails.status=1 AND (bookingdetails.pickup_date_time BETWEEN '$beforeTime' AND '$startTime' OR bookingdetails.pickup_date_time BETWEEN '$startTime' AND '$afterTime') OR bookingdetails.status=5 AND (bookingdetails.pickup_date_time BETWEEN '$beforeTime' AND '$startTime' OR bookingdetails.pickup_date_time BETWEEN '$startTime' AND '$afterTime') ORDER BY bookingdetails.pickup_date_time DESC");

        // working query
        //$query=$this->db->query("SELECT bookingdetails.id,bookingdetails.pickup_lat,bookingdetails.pickup_long from bookingdetails WHERE bookingdetails.status=1 AND (bookingdetails.pickup_date_time BETWEEN '$beforeTime' AND '$startTime' OR bookingdetails.pickup_date_time BETWEEN '$startTime' AND '$afterTime') OR bookingdetails.status=5 AND (bookingdetails.pickup_date_time BETWEEN '$beforeTime' AND '$startTime' OR bookingdetails.pickup_date_time BETWEEN '$startTime' AND '$afterTime') ORDER BY bookingdetails.id");

        //new query

        //"SELECT id,pickup_lat,pickup_long FROM bookingdetails WHERE (status=1 OR status=5) AND id IN (SELECT DISTINCT(booking_id) FROM driver_status WHERE driver_flag=2) OR (status=1 AND (pickup_date_time BETWEEN DATE_SUB(NOW(), INTERVAL 15 MINUTE) AND NOW() OR pickup_date_time BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 15 MINUTE)) OR status=5 AND (pickup_date_time BETWEEN DATE_SUB(NOW(), INTERVAL 15 MINUTE) AND NOW() OR pickup_date_time BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 15 MINUTE))) ORDER BY id"

        // final query
       //$query=$this->db->query("SELECT id,pickup_area,drop_area,pickup_date_time,pickup_lat,pickup_long,taxi_type,taxi_id FROM bookingdetails WHERE (status=1 OR status=5) AND id IN(SELECT DISTINCT(booking_id) FROM driver_status WHERE driver_flag=2 AND DATE(start_time)=$getonlyDate AND DATE(end_time)=$getonlyDate) OR (status=1 AND (pickup_date_time BETWEEN '$beforeTime' AND '$startTime' OR pickup_date_time BETWEEN '$startTime' AND '$afterTime') OR status=5 AND (pickup_date_time BETWEEN '$beforeTime' AND '$startTime' OR pickup_date_time BETWEEN '$startTime' AND '$afterTime')) ORDER BY id");

        $query=$this->db->query("SELECT id,pickup_area,drop_area,pickup_date_time,pickup_lat,pickup_long,taxi_type,taxi_id FROM bookingdetails WHERE (status=1 OR status=5) AND id NOT IN(SELECT booking_id FROM driver_status WHERE driver_flag IN(0,1,3) AND DATE(start_time)='$getonlyDate' AND DATE(end_time)='$getonlyDate') AND(pickup_date_time BETWEEN '$beforeTime' AND '$startTime' OR pickup_date_time BETWEEN '$startTime' AND '$afterTime') ORDER BY id");

       //$query=$this->db->query("SELECT b.id,b.pickup_area,b.drop_area,b.pickup_date_time,b.pickup_lat,b.pickup_long,b.taxi_type,b.taxi_id FROM((SELECT id as bookingId FROM bookingdetails WHERE status=1 OR status=5) UNION (SELECT booking_id as bookingId FROM driver_status WHERE driver_flag=2 GROUP BY booking_id)) as tmpTable LEFT JOIN bookingdetails as b ON tmpTable.bookingId = b.id LEFT JOIN driver_status as ds ON tmpTable.id=ds.booking_id WHERE (b.pickup_date_time BETWEEN '$beforeTime' AND '$startTime' OR b.pickup_date_time BETWEEN '$startTime' AND '$afterTime') AND ds.driver_flag!=0 ORDER BY b.id");
       
       //$query=$this->db->query("SELECT b.id,b.pickup_area,b.drop_area,b.pickup_date_time,b.pickup_lat,b.pickup_long,b.taxi_type,b.taxi_id FROM((SELECT bookingdetails.id as bookingId FROM bookingdetails LEFT JOIN driver_status ds ON bookingdetails.id=ds.booking_id WHERE (bookingdetails.status=1 OR bookingdetails.status=5) AND ds.driver_flag NOT IN(0,1,3)) UNION (SELECT booking_id as bookingId FROM driver_status WHERE driver_flag=2)) as tmpTable LEFT JOIN bookingdetails as b ON tmpTable.bookingId = b.id WHERE (b.pickup_date_time BETWEEN '$beforeTime' AND '$startTime' OR b.pickup_date_time BETWEEN '$startTime' AND '$afterTime') ORDER BY b.id");        

        //$query=$this->db->query("SELECT bookingdetails.id,bookingdetails.pickup_lat,bookingdetails.pickup_long from bookingdetails WHERE bookingdetails.status=1 AND (bookingdetails.pickup_date_time BETWEEN '$beforeTime' AND '$startTime' OR bookingdetails.pickup_date_time BETWEEN '$startTime' AND '$afterTime') OR bookingdetails.status=5 AND (bookingdetails.pickup_date_time BETWEEN '$beforeTime' AND '$startTime' OR bookingdetails.pickup_date_time BETWEEN '$startTime' AND '$afterTime') ORDER BY bookingdetails.pickup_date_time DESC");
            $result = $query->result_array();

        //print_r($result);
            /*if($result)
            {
                //return $result;
            }
            else
            {
                $query=$this->db->query("SELECT id,pickup_lat,pickup_long from bookingdetails WHERE status=1 AND (pickup_date_time BETWEEN '$beforeTime' AND '$startTime' OR pickup_date_time BETWEEN '$startTime' AND '$afterTime') OR status=5 AND (pickup_date_time BETWEEN '$beforeTime' AND '$startTime' OR pickup_date_time BETWEEN '$startTime' AND '$afterTime') ORDER BY pickup_date_time DESC");
                $result = $query->result_array();
            }*/
            return $result;
            //print_r($result);
    }

    // fetch current bookingcron call
    function fetchcurrentbookingcron($current_booking_id)
    {
        //$zone_name = 'Asia/Calcutta';
        $date = new DateTime("now", new DateTimeZone($this->zone_name));
        $startTime = $date->format('Y-m-d H:i:s');
        //echo 'CURRENT TIME: '.$startTime.'<br/>';
        $date = new DateTime("now", new DateTimeZone($this->zone_name));
        $date->sub(new DateInterval('PT15M'));
        $beforeTime = $date->format('Y-m-d H:i:s');
        //echo 'BEFORE TIME: '.$beforeTime.'<br/>';
        $date = new DateTime("now", new DateTimeZone($this->zone_name));
        $date->add(new DateInterval('PT15M'));
        $afterTime = $date->format('Y-m-d H:i:s');
        //echo 'AFTER TIME: '.$afterTime.'<br/>';

        $onlydate = new DateTime("now", new DateTimeZone($this->zone_name));
        $getonlyDate = $onlydate->format('Y-m-d');

        //$query=$this->db->query("SELECT bookingdetails.id,bookingdetails.pickup_lat,bookingdetails.pickup_long,driver_status.driver_id,driver_status.driver_flag from bookingdetails LEFT JOIN driver_status ON bookingdetails.id=driver_status.booking_id WHERE bookingdetails.status=1 AND (bookingdetails.pickup_date_time BETWEEN '$beforeTime' AND '$startTime' OR bookingdetails.pickup_date_time BETWEEN '$startTime' AND '$afterTime') OR bookingdetails.status=5 AND (bookingdetails.pickup_date_time BETWEEN '$beforeTime' AND '$startTime' OR bookingdetails.pickup_date_time BETWEEN '$startTime' AND '$afterTime') ORDER BY bookingdetails.pickup_date_time DESC");
        
        //working query
        //$query=$this->db->query("SELECT bookingdetails.id,bookingdetails.pickup_lat,bookingdetails.pickup_long from bookingdetails WHERE bookingdetails.id='$current_booking_id' AND bookingdetails.status=1 AND (bookingdetails.pickup_date_time BETWEEN '$beforeTime' AND '$startTime' OR bookingdetails.pickup_date_time BETWEEN '$startTime' AND '$afterTime') OR bookingdetails.status=5 AND (bookingdetails.pickup_date_time BETWEEN '$beforeTime' AND '$startTime' OR bookingdetails.pickup_date_time BETWEEN '$startTime' AND '$afterTime')");

        $query=$this->db->query("SELECT id,pickup_lat,pickup_long FROM bookingdetails WHERE (status=1 OR status=5) AND id IN(SELECT DISTINCT(booking_id) FROM driver_status WHERE driver_flag=2 AND DATE(start_time)=$getonlyDate AND DATE(end_time)=$getonlyDate) OR (status=1 AND (pickup_date_time BETWEEN '$beforeTime' AND '$startTime' OR pickup_date_time BETWEEN '$startTime' AND '$afterTime') OR status=5 AND (pickup_date_time BETWEEN '$beforeTime' AND '$startTime' OR pickup_date_time BETWEEN '$startTime' AND '$afterTime')) ORDER BY id");

        // new test query
        //$query=$this->db->query("SELECT bookingdetails.id,bookingdetails.pickup_lat,bookingdetails.pickup_long,driver_status.driver_flag from bookingdetails INNER JOIN driver_status ON bookingdetails.id=driver_status.booking_id WHERE bookingdetails.id='$current_booking_id' AND driver_status.driver_flag=2 AND bookingdetails.status=1 AND (bookingdetails.pickup_date_time BETWEEN '$beforeTime' AND '$startTime' OR bookingdetails.pickup_date_time BETWEEN '$startTime' AND '$afterTime') OR bookingdetails.status=5 AND (bookingdetails.pickup_date_time BETWEEN '$beforeTime' AND '$startTime' OR bookingdetails.pickup_date_time BETWEEN '$startTime' AND '$afterTime')");

        //$query=$this->db->query("SELECT bookingdetails.id,bookingdetails.pickup_lat,bookingdetails.pickup_long from bookingdetails WHERE bookingdetails.status=1 AND (bookingdetails.pickup_date_time BETWEEN '$beforeTime' AND '$startTime' OR bookingdetails.pickup_date_time BETWEEN '$startTime' AND '$afterTime') OR bookingdetails.status=5 AND (bookingdetails.pickup_date_time BETWEEN '$beforeTime' AND '$startTime' OR bookingdetails.pickup_date_time BETWEEN '$startTime' AND '$afterTime') ORDER BY bookingdetails.pickup_date_time DESC");
            $result = $query->result_array();

        //print_r($result);
            /*if($result)
            {
                //return $result;
            }
            else
            {
                $query=$this->db->query("SELECT id,pickup_lat,pickup_long from bookingdetails WHERE status=1 AND (pickup_date_time BETWEEN '$beforeTime' AND '$startTime' OR pickup_date_time BETWEEN '$startTime' AND '$afterTime') OR status=5 AND (pickup_date_time BETWEEN '$beforeTime' AND '$startTime' OR pickup_date_time BETWEEN '$startTime' AND '$afterTime') ORDER BY pickup_date_time DESC");
                $result = $query->result_array();
            }*/
            return $result;
            //print_r($result);
    }

    //fetch particular driver status for booking id call
    function fetchparticulardriverstatus($booking_id)
    {
        //$query = $this->db->query("SELECT driver_id from driver_status where booking_id='$booking_id' AND driver_flag=2");
        $query = $this->db->query("SELECT driver_id from driver_status where booking_id='$booking_id' AND (driver_flag=0 OR driver_flag=2)");
        //$query = $this->db->query("SELECT driver_id from driver_status WHERE driver_flag=0");
        $result = $query->result_array();
        if ($result) {
            foreach ($result as $row) {
                $driver_array[] = $row['driver_id'];
            }
            if ($driver_array) {
                //return implode(',', $driver_array);
                return $driver_array;
            }
        }
        else{
            return 0;
        }
    }

    //fetch other driver status call
    function fetchotherdriverstatus()
    {
        $query = $this->db->query("SELECT driver_id from driver_status where driver_flag=0 OR driver_flag=1");
        //$query = $this->db->query("SELECT driver_id from driver_status WHERE driver_flag=0");
        $result = $query->result_array();
        if ($result) {
            foreach ($result as $row) {
                $driver_array[] = $row['driver_id'];
            }
            if ($driver_array) {
                //return implode(',', $driver_array);
                return $driver_array;
            }
        }
        else{
            return 0;
        }
    }
    // getbookingcount
    function getbookingidcount($booking_id)
    {
        $this->db->select('*');
        $this->db->where('booking_id',$booking_id);
        $query = $this->db->get('driver_status');
        $num = $query->num_rows();
        return $num;
    }

    // check admin email
    function checkadminemail()
    {
        $this->db->select('*');
        $query=$this->db->get('adminlogin');
        $row = $query->row();
        return $row->email;
    }

    // check book status
    function getbookstatus($booking_id)
    {
        $this->db->select('*');
        $this->db->where('id',$booking_id);
        $query = $this->db->get('bookingdetails');
        $row = $query->row();
        return $row->status;   
    }
    // update immidiate driver status call
    function updateimmidiatedriverstatus($booking_id)
    {
        $this->db->where('booking_id',$booking_id);
        $result=$this->db->get('driver_status')->result_array();
        if($result)
        {
            foreach($result as $row)
            {
                $data=array(
                    'driver_flag' => 2
                );
                $this->db->where('booking_id',$booking_id);
                $this->db->where('driver_id',$row['driver_id']);
                $this->db->update('driver_status',$data);
            }
        }
    }
    // update driver status table call
    function updatedriverstatus($booking_id,$driver_id)
    {
        //$zone_name = 'Asia/Calcutta';
        $date = new DateTime("now", new DateTimeZone($this->zone_name));
        $startTime = $date->format('Y-m-d H:i:s');
        $date = new DateTime("now", new DateTimeZone($this->zone_name));
        $date->add(new DateInterval('PT60S'));
        //$endTime = date('Y-m-d H:i:s',strtotime('+60 seconds',strtotime($startTime)));
        $endTime = $date->format('Y-m-d H:i:s');
        $this->db->where('booking_id',$booking_id);
        $this->db->where_in('driver_flag',array('0','1','3'));
        $query=$this->db->get('driver_status');
        if($query->num_rows()>0)
        {
        }
        else
        {
            $data=array(
                'driver_id' => $driver_id,
                'booking_id' => $booking_id,
                'start_time' => $startTime,
                'end_time' => $endTime
            );
            $insert=$this->db->insert('driver_status',$data);
        }
            if($insert){
            return $data;
        }
        else{
            return false;
        }
    }

    // update driver flag call
    function updatedriverflag($booking_id)
    {
        $data=array(
            'status_code' => 'driver-unavailable',
            'status' => 6
        );
        $this->db->where('id',$booking_id);
        if($this->db->update('bookingdetails',$data)){
            return $this->db->affected_rows();
        }
        else{
            return false;
        }
    }

    // get unavailable booking data call
    function getunavailablebooking($booking_id)
    {
        $this->db->where('booking_id',$booking_id);
        $result=$this->db->get('driver_status')->result_array();
        if($result)
        {
            return $result;
        }
        else{
            return false;
        }
    }
    // cancel booking by driver side
    function cancelbookingbydriverside($booking_id)
    {
        $this->db->where('booking_id',$booking_id);
        $this->db->where_in('driver_flag',array('0','1','3'));
        $query=$this->db->get('driver_status');
        if($query->num_rows()>0){
        }
        else{
            $data=array(
                'status_code' => 'driver-unavailable',
                'status' => 6
            );
            $this->db->where('id',$booking_id);
            if($this->db->update('bookingdetails',$data)){
                return $this->db->affected_rows();
            }
            else{
                return false;
            }
        }
    }
    // fetchdrivercron call
    function fetchdrivercron()
    {
        //$zone_name = 'Asia/Calcutta'; 
        $date = new DateTime("now", new DateTimeZone($this->zone_name));
        $startTime = $date->format('Y-m-d H:i:s');
        //echo $startTime;
        $query=$this->db->query("SELECT id,booking_id,driver_id,end_time as enddatetime from driver_status where driver_flag not in(1,2,3)");
        $result=$query->result_array();
        foreach($result as $row){
            $date1=strtotime($startTime);
            $date2=strtotime($row['enddatetime']);
            if($date2 <= $date1)
            {
            //if($row['datetime'] <= NOW()) {
                $data=array(
                    'driver_flag' => 2
                );
                $this->db->where('id',$row['id']);
                if($this->db->update('driver_status',$data)){
                    $upcount=1;
                }
                else{
                    $upcount=0;
                }
                //update status of booking driver unavailable

                //echo $row['id'].' '.$row['datetime'] . '<br/>';
            }
        }
        /*$query=$this->db->query("UPDATE driver_status SET driver_flag=0 WHERE UNIX_TIMESTAMP(end_time) <= NOW()");
        if($query){
            return 1;
        }
        else{
            return 0;
        }*/
        return $upcount;
    }

    // update driver socket call
    function update_driver_socket_call($driver_id,$socket_status)
    {
        if($driver_id!='' && $socket_status!=''){
            $data=array(
                'socket_status' => $socket_status
            );
            $this->db->where('id',$driver_id);
            $this->db->update('driver_details',$data);
            return true;
        }else{
            return false;
        }
    }

    // check latest book call
    function checklatestbook($user_id)
    {
        if($user_id!='')
        {
            //$zone_name = 'Asia/Calcutta';
            $date = new DateTime("now", new DateTimeZone($this->zone_name));
            $startTime = $date->format('Y-m-d H:i:s');
            //echo 'CURRENT TIME: '.$startTime.'<br/>';
            $date = new DateTime("now", new DateTimeZone($this->zone_name));
            $date->sub(new DateInterval('PT10S'));
            $beforeTime = $date->format('Y-m-d H:i:s');

            $query=$this->db->query("SELECT * FROM bookingdetails WHERE user_id='$user_id' AND book_create_date_time > DATE_SUB(NOW(), INTERVAL 10 SECOND)");
            $result=$query->num_rows();
            if($result>0)
            {
                return true;
            }
            else{
                return false;
            }

        }
        else{
            return false;
        }
    }

    // get flagged drivers call
    function getflaggeddrivers()
    {
        $this->db->select('driver_id,COUNT(driver_id) as cancelled_trips');
        $this->db->where('driver_flag','2');
        $this->db->where('`driver_id` NOT IN (SELECT `id` FROM `driver_details` WHERE flag="yes")', NULL, FALSE);
        $this->db->group_by('driver_id');
        $result=$this->db->get('driver_status')->result_array();
        if($result){
            foreach($result as $row){
                if($row['cancelled_trips']>=5)
                {
                    $data=array(
                        'flag' => 'yes'
                    );
                    $this->db->where('id',$row['driver_id']);
                    if($this->db->update('driver_details',$data)){
                        $affected_rows['driver_id']=$row['driver_id'];
                        $affected_rows_array[]=$affected_rows;
                    }
                }
            }
            return $affected_rows_array;
        }
        else{
            return false;
        }
    }

    // get flagged users call
    function getflaggedusers()
    {
        $this->db->select('user_id,COUNT(user_id) as user_cancelled_trips');
        $this->db->where('status','4');
        $this->db->where('`user_id` NOT IN (SELECT `id` FROM `userdetails` WHERE flag="yes")', NULL, FALSE);
        $this->db->group_by('user_id');
        $result=$this->db->get('bookingdetails')->result_array();
        if($result){
            foreach($result as $row){
                if($row['user_cancelled_trips']>=2)
                {
                    $data=array(
                        'flag' => 'yes'
                    );
                    $this->db->where('id',$row['user_id']);
                    if($this->db->update('userdetails',$data)){
                        $affected_user_rows['user_id']=$row['user_id'];
                        $affected_user_rows_array[]=$affected_user_rows;
                    }
                }
            }
            return $affected_user_rows_array;
        }
        else{
            return false;
        }
    }
}//--------------- END Class

