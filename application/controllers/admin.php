<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller
{
	public $zone_name = CUSTOM_ZONE_NAME;
	
	// construct call
	public function __construct()
	{
	parent::__construct();
	$this->load->helper(array('form', 'url'));
	$this->load->helper('date');
	$this->load->helper('file');
	$this->load->library('form_validation');
	$this->load->model('Model_admin','home');
	$this->load->database();
	$this->load->library('session');
	$this->load->library('image_lib');
	$this->load->helper('cookie');
	$this->load->helper('url');
	$this->load->library('email');
	session_start();
	}

	// permission call
	public function permission()
	{
		//$data=$_POST;
		$permission="";

		if(($this->session->userdata('permission'))) {
			$ff = $this->router->fetch_method();

			$pm = $this->db->query("SELECT * FROM  pages WHERE pages='$ff'");

			if($pm->num_rows == 1) {
				$upm = $pm->row('p_id');
				$id=explode(',',$this->session->userdata('permission'));
				if(in_array($upm,$id)) {
					$permission = "access";
				} else {
					$permission = "failed";
					redirect('admin/not_admin');
				}
			} else {
				$permission = "failed";
			}
		}
		return $permission;
	}

	// index page call
	public function index()
	{
   	$this->load->view('admin-login');
	}

	// admin login call
	public function adminlogin()
	{
		$data=$_POST;
		$result = $this->home->login($data);
		echo $result;
	}

	// admin logout call
	public function logout()
	{
		$this->session->unset_userdata('username-admin');
		//redirect('/', 'refresh');
		delete_cookie('username-admin');
		redirect('/admin', 'refresh');
	}

	// drivesignup call
	public function driversignup()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
			if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				$this->load->view('driver_signup');
			}else{
				redirect('admin/not_admin');
			}
		}else{
			redirect('admin/index');
		}
	}

	// admin profile call
	public function profile()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
			if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				$this->load->view('admin-profile');
			}else{
				redirect('admin/not_admin');
			}
		}else{
			redirect('admin/index');
		}
	}

	// admin password change call
	public function password_change()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
			if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				$this->load->view('admin-change-password');
			}else{
				redirect('admin/not_admin');
			}
		}else{
			redirect('admin/index');
		}
	}

	//dashboard call
	public function dashboard()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
			if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				$this->load->view('dashboard');
			}else{
				redirect('admin/not_admin');
			}
		}else{
			redirect('admin/index');
		}
	}

	// manage user call
	public function manage_user()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
			if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				if($this->input->get('flag')){
					$filter='flag';
					$data['query']=$filter;
				}
				else{
					$data['query']= NULL;
				}
				$this->load->view('manage-user',$data);
			}else{
				redirect('admin/not_admin');
			}
		}else{
			redirect('admin/index');
		}
	}

	// add user call
	/*public function adduser()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
			if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				$this->load->view('admin-add-userdetails');
			}else{
				redirect('admin/not_admin');
			}
		}else{
			redirect('admin/index');
		}
	}*/

	// insert user call
	/*public function insertuser()
	{
		$data=$_POST;
		//echo $data['value'];exit;
		$res=$this->home->userinsert($data);
		// print_r($res);
		echo $res;
	}*/

	// view user details call
	public function view_userdetails()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission=$this->permission();
			if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				$this->load->view('user-details');
			}else{
				redirect('admin/not_admin');
			}
		}else{
			redirect('admin/index');
		}
	}

	// get user data call
	public function get_user_data()
	{
		// storing  request (ie, get/post) global array to a variable
		$requestData= $_REQUEST;
		$filterData=$_POST['data_id'];
		if($filterData=='yes'){
			$flagfilter=$filterData;
		}
		else{
			$flagfilter='';
		}
		$user=$this->home->getuser($requestData,$flagfilter,$where=null);
		echo $user;
	}

	//delete user data call
	public function delete_user_data()
	{
		$data_ids = $_REQUEST['data_ids'];
		$this->home->deluser($data_ids);
	}

	//delete single user data call
	public function delete_single_user_data()
	{
		$data_id = $_REQUEST['data_id'];
		$this->home->delsingleuser($data_id);
	}

	// manage booking call
	public function manage_booking()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
			if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				if($this->input->get('user_id')){
					$filter='user_id';
					$data['query']=$filter;
				}
				else if($this->input->get('status_code')){
					$filter='status_code';
					$data['query']=$filter;
				}
				else{
					$data['query']= NULL;
				}
				$this->load->view('manage-booking',$data);
			}else{
				redirect('admin/not_admin');
			}
		}else{
			redirect('admin/index');
		}
	}

	// booking details call
	public function view_booking_details()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
			if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				$data['query']=$this->home->get_booking_details($this->input->get('id'));
				if($data['query']){
				$data['query4']=$this->home->get_explicit_selected_drivers($this->input->get('id'));
				$data['query1']=$this->home->get_car_list();
				$data['query2']=$this->home->get_driver_list();
				}
				$this->load->view('booking-details',$data);
			}else{
				redirect('admin/not_admin');
			}
		}else{
			redirect('admin/index');
		}
	}

	// get booking data call
	public function get_booking_data()
	{
		// storing  request (ie, get/post) global array to a variable
		$requestData= $_REQUEST;
		$filterData=$_POST['data_id'];
		if($filterData=='user-cancelled'){
			$filterstatusid='4';
			$filterbookingid='';
		}
		else if($filterData=='driver-unavailable'){
			$filterstatusid='6';
			$filterbookingid='';
		}
		else if($filterData=='completed'){
			$filterstatusid='9';
			$filterbookingid='';
		}
		else if(is_numeric($filterData)){
			$filterstatusid='';
			$filterbookingid=$filterData;
		}
		else{
			$filterstatusid='';
			$filterbookingid='';
		}
		$booking=$this->home->getbooking($requestData,$filterstatusid,$filterbookingid,$where=null);
		echo $booking;
	}
	// get non disp booking data call
	public function get_nondisp_booking_data()
	{
		// storing  request (ie, get/post) global array to a variable
		$requestData= $_REQUEST;
		$booking=$this->home->getnondispbooking($requestData,$where=null);
		echo $booking;
	}

	//update booking call
	public function update_booking_data()
	{
		// storing  request (ie, get/post) global array to a variable
		$id= $_POST['id'];
		$data_id= $_POST['data_id'];
		$taxi_type = $_POST['taxi_type'];
		$amount = $_POST['amount'];
		$updatebooking=$this->home->updatebooking($id,$data_id,$taxi_type,$amount);
		echo $updatebooking;
	}

	// multi booking delete call
	public function multi_booking_delete()
	{
		$data=$_POST['result'];
		$data=json_decode("$data",true);
		//print_r($data);exit;
		//echo $data['value'];exit;
		$user=$this->home->deletemultibooking($data);
		// print_r($res);
		echo $user;
	}

	//delete booking data call
	public function delete_booking_data()
	{
		$data_ids = $_REQUEST['data_ids'];
		$this->home->delbooking($data_ids);
	}

	//delete single booking call
	public function delete_single_booking_data()
	{
		$data_id = $_REQUEST['data_id'];
		$this->home->delsinglebooking($data_id);
	}

	// manage driver call
	public function manage_driver()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
			if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				if($this->input->get('flag')){
					$filter='flag';
					$data['query']=$filter;
				}
				else{
					$data['query']= NULL;
				}
				$this->load->view('manage-driver',$data);
			}else{
				redirect('admin/not_admin');
			}
		}else{
			redirect('admin/index');
		}
	}

	// manage flagged driver call
	public function manage_flagged_driver()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
			if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				$this->load->view('manage-flagged-driver');
			}else{
				redirect('admin/not_admin');
			}
		}else{
			redirect('admin/index');
		}
	}

	// driver details call
	public function view_driver_details()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
			if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				$this->load->view('driver-details');
			}else{
				redirect('admin/not_admin');
			}
		}else{
			redirect('admin/index');
		}
	}

	// add driver call
	public function add_driver()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
			if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				$this->load->view('add-driver');
			}else{
				redirect('admin/not_admin');
			}
		}else{
			redirect('admin/index');
		}
	}

	// insert driver data call
	public function insert_driver()
	{
		if(isset($_POST['save']))
		{
			$config['upload_path'] = './driverimages/';
			$config['allowed_types'] = 'gif|jpg|jpeg|png';
			$config['max_size']    = '2000';
			$config['max_width']  = '1024';
			$config['max_height']  = '768';

			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('driverimage'))
			{
				$response = $this->session->set_flashdata('error_msg', $this->upload->display_errors());
				redirect(base_url().'admin/add_driver');
				// uploading failed. $error will holds the errors.
			}
			else {
				$email=$_POST['email'];
				$username=$_POST['username'];
				$check_email_username=$this->home->checkemailusername($email,$username);
				if($check_email_username) {
					$response = $this->session->set_flashdata('error_msg', 'email or username already exists');
					redirect(base_url().'admin/add_driver');
				}
				else {
					$upload_data = $this->upload->data();
					$data = array(
						'name' => $_POST['driverName'],
						'user_name' => $_POST['username'],
						'phone' => $_POST['driverPhone'],
						'address' => $_POST['driverAddress'],
						'email' => $_POST['email'],
						'license_no' => $_POST['licenseno'],
						'car_type' => $_POST['car_type'],
						'car_no' => $_POST['carno'],
						'gender' => $_POST['gender'],
						'dob' => $_POST['dob'],
						'Lieasence_Expiry_Date' => $_POST['licennex'],
						'license_plate' => $_POST['licenseplate'],
						'Insurance' => $_POST['insurance'],
						'Car_Model' => $_POST['car_model'],
						'Car_Make' => $_POST['car_make'],
						'image' => $upload_data['file_name'],
						'status' => 'Active'
					);
					$response = $this->home->insertdriverdata($data);
					redirect(base_url() . 'admin/manage_driver');
				}
			}
		}
	}
	// get driver data call
	public function get_driver_data()
	{
		$requestData= $_REQUEST;
		$filterData=$_POST['data_id'];
		if($filterData=='yes'){
			$flagfilter=$filterData;
		}
		else{
			$flagfilter='';
		}
		// storing  request (ie, get/post) global array to a variable
		$requestData= $_REQUEST;
		$driver=$this->home->getdriver($requestData,$flagfilter,$where=null);
		echo $driver;
	}

	//get select driver data call
	public function get_select_driver_data()
	{
		// storing  request (ie, get/post) global array to a variable
		$requestData= $_REQUEST;
		$booking_id=$_POST['booking_id'];
		$user=$this->home->getselectdriver($requestData,$booking_id,$where=null);
		echo $user;
	}

	// get car type data call
	public function get_cartype_data()
	{
		$cab_id=$_POST['cab_id'];
		$cab_details=$this->home->getcartypedata($cab_id);
		if($cab_details){
			echo json_encode($cab_details);
		}
	}
	//delete driver data call
	public function delete_driver_data()
	{
		$data_ids = $_REQUEST['data_ids'];
		$this->home->deldriver($data_ids);
	}

	//delete single driver data call
	public function delete_single_driver_data()
	{
		$data_id = $_REQUEST['data_id'];
		$this->home->delsingledriver($data_id);
	}

	// manage car type call
	public function manage_car_type()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
			if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				$this->load->view('manage-cartype');
			}else{
				redirect('admin/not_admin');
			}
		}else{
			redirect('admin/index');
		}
	}

	// view car call
	/*public function view_car()
	{

		if ($this->session->userdata('username-admin') || $this->input->cookie('username-admin', false)) {
			$permission = $this->permission();
			if (($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				$this->load->view('view_car');
			} else {
				redirect('admin/not_admin');
			}
		} else {
			redirect('admin/index');
		}
	}*/

	// edit car type call
	public function view_cartype_details()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
			if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				$this->load->view('cartype-details');
			}else{
				redirect('admin/not_admin');
			}
		}else{
			redirect('admin/index');
		}
	}
	
	// add car call
	public function add_car()
	{

		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
			if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				$this->load->view('add-car');
			}else{
				redirect('admin/not_admin');
			}
		}else{
			redirect('admin/index');
		}
	}

	// insert car data call
	public function insert_car()
	{
		if(isset($_POST['save']))
		{
			$config['upload_path'] = './car_image/';
			$config['allowed_types'] = 'gif|jpg|jpeg|png';
			$config['max_size']    = '2000';
			$config['max_width']  = '1024';
			$config['max_height']  = '768';

			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('uploadImageFile'))
			{
				$response = $this->session->set_flashdata('error_msg', $this->upload->display_errors());
				redirect(base_url().'admin/add_car');
				// uploading failed. $error will holds the errors.
			}
			else {
				$upload_data = $this->upload->data();
				$data = array(
					'cartype' => $_POST['cartype'],
					'car_rate' => $_POST['carrate'],
					'transfertype' => $_POST['transfertype'],
					'intialkm' => $_POST['intialkm'],
					'fromintialkm' => $_POST['fromintialkm'],
					'fromintailrate' => $_POST['fromintailrate'],
					'night_fromintailrate' => $_POST['night_fromintailrate'],
					'timetype' => $_POST['timetype'],
					'icon' => $upload_data['file_name'],
					'description' => $_POST['description'],
					'ride_time_rate' => $_POST['ride_time_rate'],
					'night_ride_time_rate' => $_POST['night_ride_time_rate'],
					'night_intailrate' => $_POST['night_intailrate'],
					'seat_capacity' => $_POST['seating_capacity']
				);
				$response = $this->home->insertcardata($data);
				redirect(base_url().'admin/manage_car_type');
			}
		}
	}
	// get car data call
	public function get_car_data()
	{
		// storing  request (ie, get/post) global array to a variable
		$requestData= $_REQUEST;
		$user=$this->home->getcar($requestData,$where=null);
		echo $user;
	}

	//delete car data call
	public function delete_car_data()
	{
		$data_ids = $_REQUEST['data_ids'];
		$this->home->delcar($data_ids);
	}

	//delete single car data call
	public function delete_single_car_data()
	{
		$data_id = $_REQUEST['data_id'];
		$this->home->delsinglecar($data_id);
	}

	//manage time type call
	public function manage_time_type()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
			if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				$this->load->view('manage-daytime');
			}else{
				redirect('admin/not_admin');
			}
		}else{
			redirect('admin/index');
		}
	}

	// edit time type call
	public function edit_time_type()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
			if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				$this->load->view('daytime-details');
			}else{
				redirect('admin/not_admin');
			}
		}else{
			redirect('admin/index');
		}
	}

	//get time type call
	public function get_time_type_data()
	{
		// storing  request (ie, get/post) global array to a variable
		$requestData= $_REQUEST;
		$user=$this->home->gettimetype($requestData,$where=null);
		echo $user;
	}

	// manage delay reasons call
	public function manage_delay_reasons()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
			if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				$this->load->view('manage-delay-reason');
			}else{
				redirect('admin/not_admin');
			}
		}else{
			redirect('admin/index');
		}
	}

	// edit delay reason call
	public function view_delayreason_details()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
			if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				$this->load->view('delayreason-details');
			}else{
				redirect('admin/not_admin');
			}
		}else{
			redirect('admin/index');
		}
	}

	// add delay reason call
	public function add_reason()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
			if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				$this->load->view('add-reason');
			}else{
				redirect('admin/not_admin');
			}
		}else{
			redirect('admin/index');
		}
	}

	//get reason data call
	public function get_reason_data()
	{
		// storing  request (ie, get/post) global array to a variable
		$requestData= $_REQUEST;
		$reason=$this->home->getreasons($requestData,$where=null);
		echo $reason;
	}

	//delete reason data call
	public function delete_reason_data()
	{
		$data_ids = $_REQUEST['data_ids'];
		$this->home->delres($data_ids);
	}

	//delete single reason data call
	public function delete_single_reason_data()
	{
		$data_id = $_REQUEST['data_id'];
		$this->home->delsingleres($data_id);
	}

	// update settings call
	public function update_settings()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
			if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {

				$this->load->view('update_settings');
			}else{
				redirect('admin/not_admin');
			}
		}else{
			redirect('admin/index');
		}
	}

	public function update_user_status()
	{
		$data_id = $_REQUEST['data_id'];
		$this->home->statususer($data_id);
	}
	public function update_driver_status()
	{
		$data_id = $_REQUEST['data_id'];
		$result=$this->home->statusdriver($data_id);
		if($result){
			$json_array = array(
                            //'driverId' => (int)$driveridarr,
                            'driverId' => $data_id,
                            'driver_status' => 0
                        );
                        $new_json_array = json_encode($json_array,JSON_UNESCAPED_SLASHES);
                        //print_r($new_json_array);
                        //exit;
                        $url = "162.243.225.225:4040/changeDriverStatus?".$new_json_array;
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
                        if($response)
                        {
                        	return true;
                        }
		}
	}
	public function calculate_ride_rates()
	{
		if(isset($_POST['pickup_date_time']) && isset($_POST['cab_id']) && isset($_POST['approx_distance']) && isset($_POST['approx_time']))
		{
			//echo 'test';
			$result=$this->home->calculaterates($_POST['pickup_date_time'],$_POST['cab_id'],$_POST['approx_distance'],$_POST['approx_time']);
			echo $result;
		}
	}
	/*public function delete()
	{
		$data=$_POST;
		//print_r($data);exit;
		//echo $data['value'];exit;
       	$user=$this->home->deleteuser($data);
		// print_r($res);
       	echo $user;
	}
	public function multipledelete()
	{
		$data=$_POST;
		//print_r($data);exit;
		//echo $data['value'];exit;
		$user=$this->home->deleteuser($data);
		// print_r($res);
		echo $user;
	}*/



	/*public function pointview()
	{   
	   if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
		$permission=$this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		 $this->load->view('admin-point');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	public  function userpointview()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission=$this->permission();
			if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				$this->load->view('userpointview');
			}else{
				redirect('admin/not_admin');
			}
		}else{
			redirect('admin/index');
		}
	}
	public function cancelpointview()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission=$this->permission();
			if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				$this->load->view('cancelpointview');
			}else{
				redirect('admin/not_admin');
			}
		}else{
			redirect('admin/index');
		}
	}
	public function SuccessFully_Booking()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission=$this->permission();
			if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				$this->load->view('SuccessFully_Booking');
			}else{
				redirect('admin/not_admin');
			}
		}else{
			redirect('admin/index');
		}
	}
	 public function airportview()
	{   
	    if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission=$this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		 $this->load->view('admin-airport');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	 public function hourlyview()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission=$this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		 $this->load->view('admin-hourly');
		 }else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	 public function outstationview()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission=$this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		 $this->load->view('admin-outstation');
		 }else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	public function bookingdelete()
	{
		$data=$_POST;
             //print_r($data);exit;
              //echo $data['value'];exit;
       $user=$this->home->deletebook($data);
               // print_r($res);
       echo $user;
	}
	 public function edit_user()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission=$this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		$this->load->view('admin-edit-userdetails');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	public function updateuser()
	{
		$data=$_POST;
       
        $user=$this->home->edituser($data);
       
           echo $user;
	}
	 public function edit_point()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission=$this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		$this->load->view('edit-point');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}

	public function update_point()
	{
		$data=$_POST;
       //print_r($data);exit;
           //echo $data['value'];exit;
        $point=$this->home->pointupdate($data);
       // print_r($res);
           echo $point;
	}
	 public function edit_airport()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission=$this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		$this->load->view('edit-airport');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	 public function edit_hourly()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		$this->load->view('edit-hourly');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	 public function edit_outstation()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		$this->load->view('edit-outstation');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}

	public function promocode()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		$this->load->view('add-promocode');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	public function insert_promocode()
	{
		$data=$_POST;
   //echo $data['value'];exit;
   $prom=$this->home->pormoadd($data);
    // print_r($res);
    echo $prom;
	}
	public function view_promocode()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		$this->load->view('view-promocode');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	public function promo_delete()
	{
		$data=$_POST;
             //print_r($data);exit;
              //echo $data['value'];exit;
       $delete=$this->home->deleteprom($data);
               // print_r($res);
       echo $delete;
	}
	public function edit_promocode()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		$this->load->view('edit-promocode');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	public function update_promocode()
	{
		$data=$_POST;
       //print_r($data);exit;
           //echo $data['value'];exit;
        $pt=$this->home->promoupdate($data);
       // print_r($res);
           echo $pt;
	}
	 public function taxi_details()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		$this->load->view('add-taxi-details');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	public  function insert_status()
	{
		$data=$_POST;
		$status=$this->home->addstatus($data);
		echo $status;
	}
	public function insert_taxi()
	{
		$data=$_POST;
   //echo $data['value'];exit;

			$taxi = $this->home->taxiadd($data);
			echo $taxi;
    // print_r($res);

	}
	public function insert_time()
	{
		$data=$_POST;
		$taxi = $this->home->timeadd($data);
		echo $taxi;
	}
	public  function insert_new_taxi5july()
	{
		$data=$_POST;
		$taxi=$this->home->addtaxi($data);
		// print_r($res);
		echo $taxi;
	}
	public  function insert_new_taxi()
	{
		$data=$_POST;

		$config = array(
			'upload_path'   => $path,
			'allowed_types' => 'jpg|gif|png',
			'overwrite'     => 1,
		);

		$this->load->library('upload', $config);

		$images = array();

		foreach ($files['name'] as $key => $image) {
			$_FILES['images[]']['name']= $files['name'][$key];
			$_FILES['images[]']['type']= $files['type'][$key];
			$_FILES['images[]']['tmp_name']= $files['tmp_name'][$key];
			$_FILES['images[]']['error']= $files['error'][$key];
			$_FILES['images[]']['size']= $files['size'][$key];

			$fileName = $title .'_'. $image;

			$images[] = $fileName;

			$config['file_name'] = $fileName;

			$this->upload->initialize($config);

			if ($this->upload->do_upload('images[]')) {
				$this->upload->data();
			} else {
				return false;
			}
		}

		return $images;
	}

	 public function taxi_view()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		$this->load->view('view-cab-details');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}

	public function edit_taxi()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		$this->load->view('edit-cab-details');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}


	public function update_car()
	{
		$data=$_POST;
		//print_r($data);exit;
		//echo $data['value'];exit;
		$taxi=$this->home->updatecar($data);
		// print_r($res);
		echo $taxi;
	}

	public function update_taxi()
	{
		$data=$_POST;
       //print_r($data);exit;
           //echo $data['value'];exit;
        $taxi=$this->home->updatetaxi($data);
       // print_r($res);
           echo $taxi;
	}
	public function update_status()
	{
		$data=$_POST;
		//print_r($data);exit;
		//echo $data['value'];exit;
		$status=$this->home->update_status($data);
		// print_r($res);
		echo $status;
	}
	public function update_time()
	{
		$data=$_POST;
		$time=$this->home->updatetime($data);
		echo $time;
	}
	public function delete_taxi()
	{
		$data=$_POST;
             //print_r($data);exit;
              //echo $data['value'];exit;
       $user=$this->home->delcabdetails($data);
               // print_r($res);
       echo $user;
	}
	public  function delete_status()
	{
		$data=$_POST;
		$status=$this->home->deletestatus($data);
		echo $status;
	}
	public function delete_car()
	{
		$data=$_POST;
		//print_r($data);exit;
		//echo $data['value'];exit;
		$user=$this->home->delcardetails($data);
		// print_r($res);
		echo $user;
	}
	public function change_password()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
		
		$this->load->view('change-password');
		}else{
			redirect('admin/index');
		}
		
	}
	public function check_password()
	{
		$data=$_POST;
       //print_r($data);exit;
           //echo $data['value'];exit;
        $pass=$this->home->updatepass($data);
       // print_r($res);
           echo $pass;
	}
	 public function taxi_airport()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		$this->load->view('view-cab-airport');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	 public function taxi_details_air()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		$this->load->view('add-taxi-air');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	 public function edit_airport_taxi()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		$this->load->view('edit-taxi-air');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	 public function taxi_hourly()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		$this->load->view('view-cab-hourly');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	 public function taxi_details_hourly()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		$this->load->view('add-taxi-hourly');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}

	public function add_new_status()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
			if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				$this->load->view('add_status');
			}else{
				redirect('admin/not_admin');
			}
		}else{
			redirect('admin/index');
		}
	}
	 public function edit_hourly_taxi()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		$this->load->view('edit-taxi-hourly');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	public function edit_status()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
			if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				$this->load->view('edit_status');
			}else{
				redirect('admin/not_admin');
			}
		}else{
			redirect('admin/index');
		}
	}
	 public function  taxi_details_outstation()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		$this->load->view('add-taxi-outstation');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	public function taxi_outstation()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		$this->load->view('view-cab-outstation');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	  public function edit_outstation_taxi()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		$this->load->view('edit-taxi-outstation');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
	     redirect('admin/index');
         }
	}

	public function Driver_Status()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
			if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				$this->load->view('view_driver_status');
			}else{
				redirect('admin/not_admin');
			}
		}else{
			redirect('admin/index');
		}

	}
	public function insert_driver()
	{
		$data=$_POST;
  //print_r($data);exit;
   $taxi=$this->home->driveradd($data);
    // print_r($res);
    echo $taxi;
	}
	  public function view_driver()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		$this->load->view('view-driver-details');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}

	  public function edit_driver()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
			
		$this->load->view('edit-driver');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	public function update_driver()
	{
		$data=$_POST;
       //print_r($data);exit;
           //echo $data['value'];exit;
        $taxi=$this->home->updatedriver($data);
       // print_r($res);
           echo $taxi;
	}
	public function delete_driver()
	{
		$data=$_POST;
             //print_r($data);exit;
              //echo $data['value'];exit;
       $user=$this->home->deletedriver($data);
               // print_r($res);
       echo $user;
	}
	  public function add_settings()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
			
		$this->load->view('add-settings',array('error'=>''));
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	public function  set_time()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
			if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {

				$this->load->view('view_time',array('error'=>''));
			}else{
				redirect('admin/not_admin');
			}
		}else{
			redirect('admin/index');
		}
	}
	public function  edit_time()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
			if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {

				$this->load->view('set_time',array('error'=>''));
			}else{
				redirect('admin/not_admin');
			}
		}else{
			redirect('admin/index');
		}
	}
	
	
	public function upload()
	{
		$data=$_POST;
		
		
		
		if($_FILES['logo']['name']){
		
		$config = $this->set_upload_options();
		//load the upload library
		$this->load->library('upload');
   
        $this->upload->initialize($config);
    
 $imgInfo = getimagesize($_FILES["logo"]["tmp_name"]);

		
	 $extension = image_type_to_extension($imgInfo[2]);
if ($extension != '.png' ){
   $this->session->set_flashdata('item', array('message' => 'select only png image types','class' => 'error') );
		
			$d = $this->session->flashdata('item');

			redirect('admin/add_settings');
}
           		   
	
else if (($imgInfo[0] != 130) && ($imgInfo[1] != 117)){
   $this->session->set_flashdata('item', array('message' => 'select images of 130/117 size(logo)','class' => 'error') );
		
			$d = $this->session->flashdata('item');

			redirect('admin/add_settings');
}else{
	if ( !$this->upload->do_upload('logo'))
		{
			
			$this->session->set_flashdata('item', array('message' => $this->upload->display_errors('logo') ,'class' => 'error') );
			
			$d = $this->session->flashdata('item');

			redirect('admin/add_settings');

		}
		else{
			$data2 = array('upload_data' => $this->upload->data('logo'));
			
			 $data['logo']=$config['upload_path']."/logo.png";
			
		}
}
}if($_FILES['favicon']['name']){
			$config = $this->set_upload_favicon();
		//load the upload library
		$this->load->library('upload');
    
            $this->upload->initialize($config);
	
			if ( !$this->upload->do_upload('favicon'))
		{
			
			$this->session->set_flashdata('item', array('message' => $this->upload->display_errors('favicon'),'class' => 'error') );
			
			$d = $this->session->flashdata('item');

			redirect('admin/add_settings');
		}
			else{
		 $this->upload->overwrite = true;
			$data1 = array('upload_datas' => $this->upload->data('favicon'));

         $data['favicon']=$config['upload_path']."/".$data1['upload_datas']['file_name'];
           	}
		}
		if(!$this->session->flashdata('item')){
			
			
			
		$taxi=$this->home->settings($data);
		}else{
			
			$d=$this->session->flashdata('item');
			redirect('admin/add_settings');
		}
		}
 public function set_upload_options()
	{
		$config['file_name']='logo';
		$config['upload_path'] = 'upload';
        $config['allowed_types'] = 'png';
	   
		$config['maintain_ratio'] = TRUE;
	   
		$config['overwrite'] = 'TRUE';
		return $config;
	}	
public function set_upload_favicon()
	{
		$config['file_name']='favicon';
		$config['upload_path'] = 'upload';
        $config['allowed_types'] = '*';
	   
		$config['maintain_ratio'] = TRUE;
	    
		$config['overwrite'] = 'TRUE';
		return $config;
	}
	  public function dashboard()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
			
		$this->load->view('dashbord');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	 
	public function insert_role()
	{
		$data=$_POST;
   //echo $data['value'];exit;
        $role=$this->home->roleadd($data);
    // print_r($res);
    echo $role;
	}
	 
	public function role_delete()
	{
		$data=$_POST;
             //print_r($data);exit;
              //echo $data['value'];exit;
       $delete=$this->home->deleterole($data);
               // print_r($res);
       echo $delete;
	}
	 
	public function update_role()
	{
		$data=$_POST;
       //print_r($data);exit;
           //echo $data['value'];exit;
        $role=$this->home->updaterole($data);
       // print_r($res);
           echo $role;
	}
	public function add_role()
	{
		$data=$_POST;
       //print_r($data);exit;
           //echo $data['value'];exit;
        $role=$this->home->addrole($data);
       // print_r($res);
           echo $role;
	}
	public function not_admin()
	{
	
	 $this->load->view('admin-404');
	}
	public function role_management()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		$this->load->view('role-management');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	        redirect('admin/index');
         }
	}
	
	public function backened_user()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
			
   $this->load->view('backend-user-lists');
		
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	public function delete_backend()
	{
		$data=$_POST;
             //print_r($data);exit;
              //echo $data['value'];exit;
       $user=$this->home->delete_backend_user($data);
               // print_r($res);
       echo $user;
	}
	 public function edit_bakend_user()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
		$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		$this->load->view('backend-edit-userdetails');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	
 public function add_backend_user()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		 $this->load->view('backend-add-userdetails');
		 }else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}	
	public function insert_backend_user()
	{
		$data=$_POST;
   //echo $data['value'];exit;
   $res=$this->home->user_backend_insert($data);
    // print_r($res);
    echo $res;
	}
		public function update_backend_user()
	{
		$data=$_POST;
       //print_r($data);exit;
           //echo $data['value'];exit;
        $user=$this->home->edit_backend_user($data);
       // print_r($res);
           echo $user;
	}
	 public function view_airmanage()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		 $this->load->view('airport-details');
		 }else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	 public function view_package()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		 $this->load->view('package-details');
		 }else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	 public function edit_air_manage()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
		$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		$this->load->view('edit-air-manage');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}	
	public function edit_package()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
		$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		$this->load->view('edit-package');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}	
	public function delete_air_manage()
	{
		$data=$_POST;
             //print_r($data);exit;
              //echo $data['value'];exit;
       $user=$this->home->delete_air($data);
               // print_r($res);
       echo $user;
	}
	public function delete_package()
	{
		$data=$_POST;
             //print_r($data);exit;
              //echo $data['value'];exit;
       $user=$this->home->delete_package($data);
               // print_r($res);
       echo $user;
	}
	public function add_airmanage()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		 $this->load->view('add-airmanage');
		 }else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}	
	public function add_package()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		 $this->load->view('add-package');
		 }else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}	
	
	public function update_airmanage()
	{
		$data=$_POST;
       //print_r($data);exit;
           //echo $data['value'];exit;
        $pt=$this->home->airmanage_update($data);
       // print_r($res);
           echo $pt;
	}
	public function update_package()
	{
		$data=$_POST;
       //print_r($data);exit;
           //echo $data['value'];exit;
        $pt=$this->home->package_update($data);
       // print_r($res);
           echo $pt;
	}
	public function places_add()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
			
   $this->load->view('add-places');
   }else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	public function insert_places()
	{
		$data=$_POST;
   //echo $data['value'];exit;
   $res=$this->home->places_insert($data);
    // print_r($res);
    echo $res;
	}
	public function view_places()
	{
	if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				
   $this->load->view('view-places');
   }else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	public function delete_places()
	{
		$data=$_POST;
             //print_r($data);exit;
              //echo $data['value'];exit;
       $user=$this->home->deleteplaces($data);
               // print_r($res);
       echo $user;
	}
	public function update_places()
	{
		$data=$_POST;
       //print_r($data);exit;
           //echo $data['value'];exit;
        $role=$this->home->updateplace($data);
       // print_r($res);
           echo $role;
	}
	public function edit_places()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
					
   $this->load->view('edit-places');
   }else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	public function auto_places()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				
			
   $this->load->view('auto-places');
   }else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	public function insert_airmanag()
	{
		$data=$_POST;
       //print_r($data);exit;
           //echo $data['value'];exit;
        $role=$this->home->insertairport($data);
       // print_r($res);
           echo $role;
	}
	public function insert_package()
	{
		$data=$_POST;
       //print_r($data);exit;
           //echo $data['value'];exit;
        $role=$this->home->insertpackage($data);
       // print_r($res);
           echo $role;
	}
public function searchs_p()
	{
		
   $this->load->view('spoint');
   
	}
	public function bookingstatus()
	{
		$data=$_POST;
       //print_r($data);exit;
           //echo $data['value'];exit;
        $status=$this->home->status_update($data);
       
           echo $status;
	}
	public function pointdriver()
	{
	if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false))
	{
		$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				
				
   $this->load->view('admin-point-driver');
   }else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	public function airportdriver()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				
			
		
   $this->load->view('admin-airport-driver');
   }else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	public function hourlydriver()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				
			
		
   $this->load->view('admin-hourly-driver');
   }else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	public function outdriver()
	{
		
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				
			
   $this->load->view('admin-out-driver');
   }else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	public function addpoint()
	{
		
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				
			
   $this->load->view('admin-add-point');
   }else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	public function admin_book()
	{
		$data=$_POST;
       
        $status=$this->home->book_admin($data);
      
           echo $status;
	}
	public function upload1()
	{
		$data=$_POST;
		 $delete=$this->home->insta($data);
	}
	public function addair()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				
			
		
   $this->load->view('admin-add-air');
   }else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	
	
	public function addhourly()
	{
		
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				
			
   $this->load->view('admin-add-hourly');
   }else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	public function addout()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				
			
		
   $this->load->view('admin-add-out');
   }else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	
	public function view_page()
	{
		
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				
			
   $this->load->view('admin-view-static');
   }else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	
	
	
	
	public function view_language()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		$this->load->view('view-language');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	
	public function edit_language()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		$this->load->view('edit-language');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	
	
	
	public function update_language_set()
	{
		$data=$_POST;
       //print_r($data);exit;
           //echo $data['value'];exit;
        $pt=$this->home->languagesetupdate($data);
       // print_r($res);
           echo $pt;
	}
	
	public function add_language()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		 $this->load->view('add-language');
		 }else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	
	public function insert_language()
	{
		$data=$_POST;
    
        $role=$this->home->insertlanguage($data);
 
          echo $role;
	}
	public function upload_blog()
	{
		$data=$_POST;
    
        $role=$this->home->blog_upload($data);
 
          echo $role;
	}
	
	
	public function add_select_language()
	{
		//$data=$_POST;
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
		$this->load->view('add-select-language');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
	}
	
	public function insert_addnew_languages()
	{
		$data=$_POST;
   //echo $data['value'];exit;
   $taxi=$this->home->languagesadd($data);
    // print_r($res);
    echo $taxi;
	}
	
	public function languages_delete()
	{
		$data=$_POST;
            
       $user=$this->home->delete_langauge($data);
              
       echo $user;
	}
	public function add_page()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				
			
		
   $this->load->view('admin-add-static');
   }else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
   
	}
	public function add_banner()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				
			
		
   $this->load->view('admin-add-banner');
   }else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
   
	}
	
	public function set_upload_baner()
	{
		$config['file_name']='banner-inner';
		$config['upload_path'] = 'assets/images/images';
        $config['allowed_types'] = 'png';
	   
		$config['maintain_ratio'] = TRUE;
	   
		$config['overwrite'] = 'TRUE';
		return $config;
	}
	public function set_upload_taxi()
	{
		$config['file_name']='banner-taxi';
		$config['upload_path'] = 'img';
		$config['allowed_types'] = 'jpeg';

		$config['maintain_ratio'] = TRUE;

		$config['overwrite'] = 'TRUE';
		return $config;
	}
	public function set_upload_car()
	{
		$config['file_name']='car';
		$config['upload_path'] = 'application/views/img/';
        $config['allowed_types'] = 'png';
	   
		$config['maintain_ratio'] = TRUE;
	   
		$config['overwrite'] = 'TRUE';
		return $config;
	}	
	public function banner()
	{
		$data=$_POST;
		
		if(isset($_FILES['blog_content']['name'])){
		
		$config = $this->set_upload_baner();
		$this->load->library('upload');
        $this->upload->initialize($config);
    
        $imgInfo = getimagesize($_FILES["blog_content"]["tmp_name"]);
        $extension = image_type_to_extension($imgInfo[2]);
        if ($extension != '.png' ){
           $this->session->set_flashdata('item', array('message' => 'select only png image types','class' => 'error') );
		
			$d = $this->session->flashdata('item');

			redirect('admin/add_banner');
        }
	
        else if (($imgInfo[0] != 361) && ($imgInfo[1] != 403)){
            $this->session->set_flashdata('item', array('message' => 'select images of 361/403 size(baner1)','class' => 'error') );
		
			$d = $this->session->flashdata('item');

			redirect('admin/add_banner');
        }else{
	        if ( !$this->upload->do_upload('blog_content'))
		    {
			
			   $this->session->set_flashdata('item', array('message' => $this->upload->display_errors('blog_content') ,'class' => 'error') );
			
			   $d = $this->session->flashdata('item');

			   redirect('admin/add_banner');

		    }
		    else{
			   $data2 = array('upload_data' => $this->upload->data('blog_content'));
			
			   echo $data['blog_content']=$config['upload_path']."/banner-inner.png";
			
		    }
        }
}  if(isset($_FILES['baner_car']['name'])){
		$config = $this->set_upload_car();
		
		$this->load->library('upload');
    
        $this->upload->initialize($config);
	    $imgInfo = getimagesize($_FILES["baner_car"]["tmp_name"]);
        $extension = image_type_to_extension($imgInfo[2]);
        if ($extension != '.png' ){
           $this->session->set_flashdata('item', array('message' => 'select only png image types','class' => 'error') );
		
			$d = $this->session->flashdata('item');

			redirect('admin/add_banner');
        }
	
        else if (($imgInfo[0] != 466) && ($imgInfo[1] != 264)){
            $this->session->set_flashdata('item', array('message' => 'select images of 466/264 size(banercar)','class' => 'error') );
		
			$d = $this->session->flashdata('item');

			redirect('admin/add_banner');
        }else{
		if ( !$this->upload->do_upload('baner_car'))
		{
			
			$this->session->set_flashdata('item', array('message' => $this->upload->display_errors('favicon'),'class' => 'error') );
			
			$d = $this->session->flashdata('item');

			redirect('admin/add_banner');
		}
		else{
		    $this->upload->overwrite = true;
			$data1 = array('upload_datas' => $this->upload->data('baner_car'));
            echo $data['baner_car']=$config['upload_path']."/car.png";
	
			}
		}
}
		if(!$this->session->flashdata('item')){
		
		$taxi=$this->home->baners($data);
		}else{
			
			$d=$this->session->flashdata('item');
			redirect('admin/add_banner');
		}
}
   public function add_pages()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			$permission = $this->permission();
		if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				
			
		
		$this->load->view('add-pages');
		}else{
			redirect('admin/not_admin');
		}
		}else{
	     redirect('admin/index');
         }
   
	}
	 
	public function insert_page()
	{
		$data=$_POST;
    
        $role=$this->home->page_insert($data);
 
          echo $role;
	}
	public function view_pages()
	{
		$this->load->view('view-pages');
	}
	public function delete_pages()
	{
		$data=$_POST;
             //print_r($data);exit;
              //echo $data['value'];exit;
       $user=$this->home->deletepages($data);
               // print_r($res);
       echo $user;
	}
	public function edit_pages()
	{
		$this->load->view('edit-pages');
	}
	public function update_pages()
	{
		$data=$_POST;
             //print_r($data);exit;
              //echo $data['value'];exit;
       $user=$this->home->pages_updates($data);
               // print_r($res);
       echo $user;
	}
	public function wallet_list()
	{
	
   $this->load->view('wallet_lists');
  
	}public function select_driver()
	{
		$data=$_POST;

$paypal=$this->home->driver_assign_auto($data);

echo $paypal;
	}
	public function callback_list()
	{
	
   $this->load->view('callback_lists');
  
	}
	public function approval_driver()
	{
		$data=$_POST;
             //print_r($data);exit;
              //echo $data['value'];exit;
       $user=$this->home->driver_approvel($data);
               // print_r($res);
       echo $user;
	}
	public function callback_delete()
	{
		$data=$_POST;
             //print_r($data);exit;
              //echo $data['value'];exit;
       $user=$this->home->delete_callback($data);
               // print_r($res);
       echo $user;
	}public function rating()
	{
		$data=$_POST;
		 $user=$this->home->rate_driver($data);
		 if($user==true){
			 $this->load->view('rating');
		 }
		
	}*/


	// Language change code for mobile apps Edited
	public function languageChageForDriverApp(){
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			 $permission = $this->permission();
				if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				//$this->load->helper('language_helper');
				$this->db->select('language_name');
				$query = $this->db->get('app_languages');
				$allLanguages = $query->result_array();

				if(isset($allLanguages[0]['language_name'])){
					  $currentlanguage=$allLanguages[0]['language_name'];
				}

				$viewData['allLanguages']=$allLanguages;
				//$viewData['languageMeta']=$languageMeta;

				$this->load->view('view-appLanguage',$viewData);
			}else{
				redirect('admin/not_admin');
			}
  		}else{
 		redirect('admin/index');
		}
	}

	// Show stored language call
	public function showStoredLanguage(){
		$request = $this->input->post();
		$currentlanguage= $request['fetchLanguage'];

		$app= $request['app'];
		if($app=='user'){
				$table='user_app_language';
		}else{
				$table='app_languages';
		}

		$this->db->select('language_meta');
		$this->db->where('language_name', $currentlanguage);
		$query = $this->db->get($table);
		$languageMeta = $query->row();
		$languageMeta=json_decode($languageMeta->language_meta, true);
		//var_dump($languageMeta);
		print json_encode($languageMeta);
	}

	// Save new language call
	public function saveNewLanguage()
	{
		$request = $this->input->post();
		$newLanguage= $request['newLanguage'];
		// $this->load->helper('language_helper');
		// $getArray=getLanguageForDriverApp();
		// $getArray=json_encode($getArray);
		$app= $request['app'];
		if($app=='user'){
				$table='user_app_language';
		}else{
				$table='app_languages';
		}

		$this->db->select("count(*) as count");
		$this->db->where("language_name",$newLanguage);
		$this->db->from($table);
		$count = $this->db->get()->row();
		if($count->count > 0) {
			$this->db->where("language_name",language_name);
			$result = $this->db->update('language_name', $newLanguage);
		}else {
			$ins = array(
										'language_name' => $newLanguage,
										'language_meta' => '',
										'status'  => '0'
									);
		 $result=$this->db->insert($table, $ins);
		}
		if($result){
			echo 1;
		}else{
			echo 0;
		}
	}

	// Save driver app language call
	public function saveDriverApplang()
	{
 		ob_start();
		$request = $this->input->post();

		$hidden_lang=$request['hidden_lang'];
		$languageMeta=json_encode($request);



		 $data = array( 'language_meta' => $languageMeta);
		 $this->db->where('language_name', $hidden_lang);
		 $result=$this->db->update('app_languages', $data);
		 redirect(base_url().'admin/languageChageForDriverApp');
	}

	// Delete app langauge all
	public function deleteAppLanguage(){
		$request = $this->input->post();
		$id=$request['id'];
		$this->db->where('id', $id);
		$del=$this->db->delete('app_languages');
		if($del){
			echo 1;
		}else {
			echo 0;
		}
	}

	// Language change for user app call
	public function languageChageForUserApp()
	{
		if($this->session->userdata('username-admin') ||   $this->input->cookie('username-admin', false)){
			 	$permission = $this->permission();
				if(($this->session->userdata('role-admin') == 'admin') || ($permission == "access")) {
				//$this->load->helper('language_helper');
				$this->db->select('language_name');
				$query = $this->db->get('user_app_language');
				$allLanguages = $query->result_array();

				if(isset($allLanguages[0]['language_name'])){
						$currentlanguage=$allLanguages[0]['language_name'];
				}

				$viewData['allLanguages']=$allLanguages;
				//$viewData['languageMeta']=$languageMeta;

				$this->load->view('view-userAppLanguage',$viewData);
			}else{
			redirect('admin/not_admin');
			}
		}else{
		redirect('admin/index');
		}
	}

	// Save user app language call
	public function saveUserApplang()
	{
 		ob_start();
		$request = $this->input->post();

		$hidden_lang=$request['hidden_lang'];
		$languageMeta=json_encode($request);
	 	$data = array( 'language_meta' => $languageMeta);
	 	$this->db->where('language_name', $hidden_lang);
	 	$result=$this->db->update('user_app_language', $data);
	 	redirect(base_url().'admin/languageChageForUserApp');
	}

	// Delete user app langauge call
	public function deleteUserAppLanguage(){
		$request = $this->input->post();
		$id=$request['id'];
		$this->db->where('id', $id);
		$del=$this->db->delete('user_app_language');
		if($del){
			echo 1;
		}else {
			echo 0;
		}
	}

	// Set app default language call
	public function setAppDefaultLanguage()
	{
		$request = $this->input->post();
		$language=$request['language'];
		$app=$request['app'];
		if($app=='user'){
				$table='user_app_language';
		}else{
				$table='app_languages';
		}

		$data = array( 'status' => '0');
		$this->db->where('status', '1');
		$result=$this->db->update($table, $data);

		if($result){
			$data = array( 'status' => '1');
			$this->db->where('language_name', $language);
			$setLanguage=$this->db->update($table, $data);
		}
		if($setLanguage)	{	echo 1;	}else{	echo 0;	}
	}
     
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>