<?php
class Model_admin extends CI_Model{

	public $zone_name = CUSTOM_ZONE_NAME;
	
	// Construct call
	function __construct()
	{
		parent::__construct();
	}

	// Login call
	function login($data)
	{
	 	// grab user input
	
        $username = $data['email'];
        $password = md5($data['password']);
		$remember='';
		if(isset($data['rememberme'])){
        $remember = $data['rememberme'];
		}
        // Prep the query

        // Run the query
        $query = $this->db->query("select * from adminlogin where binary username ='$username' and binary password = '$password'");
        // Let's check if there are any results
	
        if($query->num_rows == 1)
        {
            // If there is a user, then create session data
            //$row = $query->result_array();
			if($remember=='on' && $remember!=''){
			
				$cookie = array(
					'name'   => 'username-admin',
					'value'  => $username,
					'expire' => 86500
				);
				//  $this->ci->db->insert("UserCookies", array("CookieUserEmail"=>$userEmail, "CookieRandom"=>$randomString));
				$this->input->set_cookie($cookie);

				$this->input->cookie('username-admin', false);
			}

         	$this->session->set_userdata('username-admin',$data['email']);
		  	$user = $this->session->userdata('username-admin');
		  
		 	foreach($query->result_array() as $row){
		
		  		$this->session->set_userdata('role-admin',$row['role']);
		 	}
		  	$user1 = $this->session->userdata('role-admin');
		  
		   	$this->db->select('B.rolename as rolename,A.role_id,A.page_id as pages');
			$this->db->from('role B');// I use aliasing make joins easier
			$this->db->join('role_permission A', ' B.r_id = A.role_id');
			$this->db->where('B.rolename',$user1);

			$query1 = $this->db->get();
		  		foreach($query1->result_array() as $row1){
		  			$this->session->set_userdata('permission',$row1['pages']);
		 		}
		 		$user2 = $this->session->userdata('permission');
            	//return $row;
				echo $user1;
		}
        // If the previous process did not validate
        // then return false.
		else
		{
        //return false;
		echo 1;
		}
	}

	// Get user list call
	function getuser($requestData,$flagfilter,$where)
	{
		$columns = array(
			// datatable column index  => database column name
			0 => 'id',
			1 => 'image',
			2 => 'username',
			3 => 'email',
			4 => 'gender',
			5 => 'mobile',
			6 => 'user_status'
		);
		$flag_disp = $flagfilter;
		// getting total number records without any search
		$this->db->select('*');
		if($flag_disp!='' || $flag_disp!=NULL){
			$this->db->where('flag',$flag_disp);
		}
		$totalData=$this->db->get('userdetails')->num_rows();
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

		$this->db->select('id, image, username, email, gender, mobile, user_status, facebook_id, flag');
		$this->db->from('userdetails');
		if($flag_disp!='' || $flag_disp!=NULL){
			$this->db->where('flag',$flag_disp);
		}
		if ($where !== null) {
			$this->db->where($where);
		}
		if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
			$keywords=$requestData['search']['value'].'%';
			if($flag_disp!='' || $flag_disp!=NULL){
				$this->db->where('flag',$flag_disp);
			}
			//$this->db->where('status','1');
			$this->db->where("(username LIKE '$keywords' OR email LIKE '$keywords' OR gender LIKE '$keywords' OR mobile LIKE '$keywords')");
		}
		$totalFiltered=$this->db->get()->num_rows();
		$this->db->select('id, image, username, email, gender, mobile, user_status, facebook_id, flag');
		$this->db->from('userdetails');
		if($flag_disp!='' || $flag_disp!=NULL){
			$this->db->where('flag',$flag_disp);
		}
		if ($where !== null) {
			$this->db->where($where);
		}
		if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
			$keywords=$requestData['search']['value'].'%';
			if($flag_disp!='' || $flag_disp!=NULL){
				$this->db->where('flag',$flag_disp);
			}
			$this->db->where("(username LIKE '$keywords' OR email LIKE '$keywords' OR gender LIKE '$keywords' OR mobile LIKE '$keywords')");
		}
		//echo $columns[$requestData['order'][0]['column']];
		$this->db->order_by($columns[$requestData['order'][0]['column']],$requestData['order'][0]['dir']);
		$this->db->limit($requestData['length'],$requestData['start']);
		$resultarray=$this->db->get()->result_array();

		$data = array();
		$i=1+$requestData['start'];
		foreach($resultarray as $item)
		{
			// preparing an array
			$nestedData=array();

			$nestedData[] = "<input type='checkbox'  class='deleteRow' value='".$item['id']."'  />" ;
			if($item['image']) {
				$nestedData[] = '<img src=' . base_url() . 'user_image/' . $item["image"] . '>';
			}
			else{
				$nestedData[] = '<img src="' . base_url() . 'upload/no-image-icon.png">';
			}
			$nestedData[] = $item["username"];
			$nestedData[] = $item["email"];
			$nestedData[] = $item["gender"];
			$nestedData[] = $item["mobile"];
			if($item['user_status']=='Active')
			{
				$nestedData[] = '<span class="label label-success"><a href="javascript:void(0)" onclick="status('.$item["id"].')"  style="color: white;">Active</a></span>';
			}
			else
			{
				$nestedData[] = '<span class="label label-default"><a href="javascript:void(0)" onclick="status('.$item["id"].')"  style="color: white;" >Inactive</a></span></span>';
			}
			$nestedData[] = '<!--<a class="table-link" href="javascript:void(0);" onclick="window.location.href=\'view_userdetails?id='.$item['id'].'\'">
				<span class="fa-stack">
					<i class="fa fa-square fa-stack-2x"></i>
					<i class="fa fa-search-plus fa-stack-1x fa-inverse"></i>
				</span>
			</a>-->
			<a onclick="window.location.href=\'view_userdetails?id='.$item['id'].'\'" href="javascript:void(0);" class="table-link">
				<span class="fa-stack">
					<i class="fa fa-square fa-stack-2x"></i>
					<i class="fa fa-search-plus fa-stack-1x fa-inverse"></i>
				</span>
			</a>
            <a data-target="#uidemo-modals-alerts-delete-user" data-toggle="modal" class="table-link danger" href="javascript:void(0);" onclick="delete_single_user('.$item["id"].')">
				<span class="fa-stack">
					<i class="fa fa-square fa-stack-2x"></i>
					<i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
				</span>
			</a>';

			$data[] = $nestedData;
			$i++;
		}

		$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
		);

		return json_encode($json_data);  // send data as json format
	}

	// Delete user call
	function deluser($data_ids)
	{
		$data_id_array = explode(",", $data_ids);
		if(!empty($data_id_array)) {
			foreach($data_id_array as $id) {
				$this->db->where('id',$id);
				$this->db->delete('userdetails');
			}
		}
	}

	// Delete single user call
	function delsingleuser($data_id)
	{
		if(!empty($data_id)) {
			$this->db->where('id',$data_id);
			$this->db->delete('userdetails');
		}
	}

	// Delete user call
	/*function deleteuser($data)
	{
		$id = $data['id'];
		$this->db->where('id', $id);
		if($this->db->delete('userdetails'))
		{
            echo 1;
		}
		else
		{
			echo 0;
		}
	}*/

	function get_booking_details($id){

		$this->db->select('*');
		$this->db->from('bookingdetails');
		//$this->db->join('bookingdetails', 'driver_details.id = bookingdetails.assigned_for','right');
		$this->db->where('bookingdetails.id',$id);
		$query = $this->db->get();
		$result=$query->row();
		return $result;
	}
	//get explicit selected driver data call
	function get_explicit_selected_drivers($id){
		$this->db->select('*');
		$this->db->from('driver_status');
		$this->db->join('driver_details','driver_details.id=driver_status.driver_id','right');
		$this->db->where('driver_status.booking_id',$id);
		$this->db->order_by('driver_status.start_time');
		$query = $this->db->get()->result_array();
		if($query){
			/*foreach($query as $result)
			{
				$this->db->select('*');
				$this->db->from('driver_details');
				$this->db
				$this->db->where('id',$result['driver_id']);
				$query1 = $this->db->get()->result_array();
			}*/
			return $query;
		}
		else{
			return false;
			//$query[]=null;
		}
		//return $query;
	}
	// Get car list call
	function get_car_list()
	{
		$this->db->select('*');
		$query=$this->db->get('cabdetails')->result_array();
		return $query;
	}

	// Get driver list call
	function get_driver_list()
	{
		$this->db->select('user_name');
		$query=$this->db->get('driver_details')->result_array();
		return $query;
	}

	// Get booking list call
	function getbooking($requestData,$filterData,$filterBooking,$where)
	{
		$columns = array(
			// datatable column index  => database column name
			0 => 'id',
			1 => 'username',
			2 => 'user_id',
			3 => 'id',
			4 => 'taxi_type',
			5 => 'pickup_area',
			6 => 'drop_area',
			7 => 'pickup_date_time',
			8 => 'status_code'
		);
		$status_disp = $filterData;
		$book_disp = $filterBooking;
		// getting total number records without any search
		$this->db->select('*');
		if($status_disp!='' || $status_disp!=NULL){
			$this->db->where('status',$status_disp);
		}
		else if($book_disp!='' || $book_disp!=NULL){
			$this->db->where('user_id',$book_disp);
		}
		//$status_disp=array('1','2','3','6','7','8');
		//$this->db->where_in('status',$status_disp);
		$totalData=$this->db->get('bookingdetails')->num_rows();
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

		$this->db->select('username, user_id, id, taxi_type, pickup_area, drop_area, pickup_date_time, status, status_code');
		$this->db->from('bookingdetails');
		if($status_disp!='' || $status_disp!=NULL){
		$this->db->where('status',$status_disp);
		}
		else if($book_disp!='' || $book_disp!=NULL){
			$this->db->where('user_id',$book_disp);
		}
		if ($where !== null) {
			$this->db->where($where);
		}
		if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
			$keywords=$requestData['search']['value'].'%';
			if($status_disp!='' || $status_disp!=NULL){
			$this->db->where('status',$status_disp);
			}
			else if($book_disp!='' || $book_disp!=NULL){
			$this->db->where('user_id',$book_disp);
			}
			//$this->db->where('status','1');
			$this->db->where("(username LIKE '$keywords' OR user_id LIKE '$keywords' OR id LIKE '$keywords' OR taxi_type LIKE '$keywords' OR pickup_area LIKE '$keywords' OR drop_area LIKE '$keywords' OR status_code LIKE '$keywords')");
		}
		$totalFiltered=$this->db->get()->num_rows();
		$this->db->select('username, user_id, id, taxi_type, pickup_area, drop_area, pickup_date_time, status, status_code');
		$this->db->from('bookingdetails');
		if($status_disp!='' || $status_disp!=NULL){
		$this->db->where('status',$status_disp);
		}
		else if($book_disp!='' || $book_disp!=NULL){
			$this->db->where('user_id',$book_disp);
		}
		if ($where !== null) {
			$this->db->where($where);
		}
		if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
			$keywords=$requestData['search']['value'].'%';
			if($status_disp!='' || $status_disp!=NULL){
			$this->db->where('status',$status_disp);
			}
			else if($book_disp!='' || $book_disp!=NULL){
			$this->db->where('user_id',$book_disp);
			}
			//$this->db->where('status','1');
			$this->db->where("(username LIKE '$keywords' OR user_id LIKE '$keywords' OR id LIKE '$keywords' OR taxi_type LIKE '$keywords' OR pickup_area LIKE '$keywords' OR drop_area LIKE '$keywords' OR status_code LIKE '$keywords')");
		}
		//echo $columns[$requestData['order'][0]['column']];
		$this->db->order_by($columns[$requestData['order'][0]['column']],$requestData['order'][0]['dir']);
		$this->db->limit($requestData['length'],$requestData['start']);
		$resultarray=$this->db->get()->result_array();

		$data = array();
		$i=1+$requestData['start'];
		foreach($resultarray as $item)
		{
			// preparing an array
			$nestedData=array();

			$nestedData[] = "<input type='checkbox'  class='deleteRow' value='".$item['id']."'  />" ;
			$nestedData[] = $item["username"];
			$nestedData[] = $item["user_id"];
			$nestedData[] = $item["id"];
			$nestedData[] = $item["taxi_type"];
			$nestedData[] = $item["pickup_area"];
			$nestedData[] = $item["drop_area"];
			$nestedData[] = $item["pickup_date_time"];
			if($item['status_code']=='pending') {
				$nestedData[] = "<span class='label label-default'><a href='#' style='color: white;'>Pending</a></span>";
			}
			else if($item['status_code']=='waiting'){
				$nestedData[] = "<span class='label label-waiting'><a href='#' style='color: white;'>Waiting</a></span>";
			}
			else if($item['status_code']=='accepted'){
				$nestedData[] = "<span class='label label-accepted'><a href='#' style='color: white;'>Accepted</a></span>";
			}
			else if($item['status_code']=='user-cancelled'){
				$nestedData[] = "<span class='label label-user-cancelled'><a href='#' style='color: white;'>User Cancelled</a></span>";
			}
			else if($item['status_code']=='driver-cancelled'){
				$nestedData[] = "<span class='label label-driver-cancelled'><a href='#' style='color: white;'>Driver Cancelled</a></span>";
			}
			else if($item['status_code']=='driver-unavailable'){
				$nestedData[] = "<span class='label label-driver-unavailable'><a href='#' style='color: white;'>Driver Unavailable</a></span>";
			}
			else if($item['status_code']=='driver-arrived'){
				$nestedData[] = "<span class='label label-driver-arrived'><a href='#' style='color: white;'>Driver Arrived</a></span>";
			}
			else if($item['status_code']=='on-trip'){
				$nestedData[] = "<span class='label label-on-trip'><a href='#' style='color: white;'>On Trip</a></span>";
			}
			else if($item['status_code']=='completed'){
				$nestedData[] = "<span class='label label-success'><a href='#' style='color: white;'>Completed</a></span>";
			}
			$nestedData[] = '<!--<a class="table-link" href="javascript:void(0);">
				<span class="fa-stack">
					<i class="fa fa-square fa-stack-2x"></i>
					<i class="fa fa-search-plus fa-stack-1x fa-inverse"></i>
				</span>
			</a>-->
			<a onclick="window.location.href=\'view_booking_details?id='.$item['id'].'\'" href="javascript:void(0);" class="table-link">
				<span class="fa-stack">
					<i class="fa fa-square fa-stack-2x"></i>
					<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
				</span>
			</a>
            <a data-target="#uidemo-modals-alerts-delete-user" data-toggle="modal" class="table-link danger" href="javascript:void(0);" onclick="delete_single_booking('.$item["id"].')">
				<span class="fa-stack">
					<i class="fa fa-square fa-stack-2x"></i>
					<i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
				</span>
			</a>';

			$data[] = $nestedData;
			$i++;
		}

		$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
		);

		return json_encode($json_data);  // send data as json format
	}

	// Get non disp booking list call
	function getnondispbooking($requestData,$where)
	{
		$columns = array(
			// datatable column index  => database column name
			0 => 'id',
			1 => 'username',
			2 => 'user_id',
			3 => 'id',
			4 => 'taxi_type',
			5 => 'pickup_area',
			6 => 'drop_area',
			7 => 'pickup_date_time',
			8 => 'status_code'
		);
		$status_disp=array('1','2','3','7','8');
		// getting total number records without any search
		$this->db->select('*');
		$this->db->where_in('status',$status_disp);
		//$status_disp=array('1','2','3','6','7','8');
		//$this->db->where_in('status',$status_disp);
		$totalData=$this->db->get('bookingdetails')->num_rows();
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

		$this->db->select('username, user_id, id, taxi_type, pickup_area, drop_area, pickup_date_time, status, status_code');
		$this->db->from('bookingdetails');
		$this->db->where_in('status',$status_disp);
		if ($where !== null) {
			$this->db->where($where);
		}
		if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
			$keywords=$requestData['search']['value'].'%';
			$this->db->where_in('status',$status_disp);
			//$this->db->where('status','1');
			$this->db->where("(username LIKE '$keywords' OR user_id LIKE '$keywords' OR id LIKE '$keywords' OR taxi_type LIKE '$keywords' OR pickup_area LIKE '$keywords' OR drop_area LIKE '$keywords')");
		}
		$totalFiltered=$this->db->get()->num_rows();
		$this->db->select('username, user_id, id, taxi_type, pickup_area, drop_area, pickup_date_time, status, status_code');
		$this->db->from('bookingdetails');
		$this->db->where_in('status',$status_disp);
		if ($where !== null) {
			$this->db->where($where);
		}
		if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
			$keywords=$requestData['search']['value'].'%';
			$this->db->where_in('status',$status_disp);
			//$this->db->where('status','1');
			$this->db->where("(username LIKE '$keywords' OR user_id LIKE '$keywords' OR id LIKE '$keywords' OR taxi_type LIKE '$keywords' OR pickup_area LIKE '$keywords' OR drop_area LIKE '$keywords')");
		}
		//echo $columns[$requestData['order'][0]['column']];
		$this->db->order_by($columns[$requestData['order'][0]['column']],$requestData['order'][0]['dir']);
		$this->db->limit($requestData['length'],$requestData['start']);
		$resultarray=$this->db->get()->result_array();

		$data = array();
		$i=1+$requestData['start'];
		foreach($resultarray as $item)
		{
			// preparing an array
			$nestedData=array();

			$nestedData[] = "<input type='checkbox'  class='deleteRow' value='".$item['id']."'  />" ;
			$nestedData[] = $item["username"];
			$nestedData[] = $item["user_id"];
			$nestedData[] = $item["id"];
			$nestedData[] = $item['taxi_type'];
			$nestedData[] = $item["pickup_area"];
			$nestedData[] = $item["drop_area"];
			$nestedData[] = $item["pickup_date_time"];
			if($item['status_code']=='pending') {
				$nestedData[] = "<span class='label label-default'><a href='#' style='color: white;'>Pending</a></span>";
			}
			else if($item['status_code']=='waiting'){
				$nestedData[] = "<span class='label label-waiting'><a href='#' style='color: white;'>Waiting</a></span>";
			}
			else if($item['status_code']=='accepted'){
				$nestedData[] = "<span class='label label-accepted'><a href='#' style='color: white;'>Accepted</a></span>";
			}
			else if($item['status_code']=='user-cancelled'){
				$nestedData[] = "<span class='label label-user-cancelled'><a href='#' style='color: white;'>User Cancelled</a></span>";
			}
			else if($item['status_code']=='driver-cancelled'){
				$nestedData[] = "<span class='label label-driver-cancelled'><a href='#' style='color: white;'>Driver Cancelled</a></span>";
			}
			else if($item['status_code']=='driver-unavailable'){
				$nestedData[] = "<span class='label label-driver-unavailable'><a href='#' style='color: white;'>Driver Unavailable</a></span>";
			}
			else if($item['status_code']=='driver-arrived'){
				$nestedData[] = "<span class='label label-driver-arrived'><a href='#' style='color: white;'>Driver Arrived</a></span>";
			}
			else if($item['status_code']=='on-trip'){
				$nestedData[] = "<span class='label label-on-trip'><a href='#' style='color: white;'>On Trip</a></span>";
			}
			else if($item['status_code']=='completed'){
				$nestedData[] = "<span class='label label-success'><a href='#' style='color: white;'>Completed</a></span>";
			}
			$nestedData[] = '<!--<a class="table-link" href="javascript:void(0);">
				<span class="fa-stack">
					<i class="fa fa-square fa-stack-2x"></i>
					<i class="fa fa-search-plus fa-stack-1x fa-inverse"></i>
				</span>
			</a>-->
			<a onclick="window.location.href=\'view_booking_details?id='.$item['id'].'\'" href="javascript:void(0);" class="table-link">
				<span class="fa-stack">
					<i class="fa fa-square fa-stack-2x"></i>
					<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
				</span>
			</a>
            <a data-target="#uidemo-modals-alerts-delete-user" data-toggle="modal" class="table-link danger" href="javascript:void(0);" onclick="delete_single_booking('.$item["id"].')">
				<span class="fa-stack">
					<i class="fa fa-square fa-stack-2x"></i>
					<i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
				</span>
			</a>';

			$data[] = $nestedData;
			$i++;
		}

		$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
		);

		return json_encode($json_data);  // send data as json format
	}

	// update driver details call
	function updatebooking($id,$data_id,$taxi_type,$amount)
	{
		//$zone_name = 'Asia/Calcutta';
		$date = new DateTime("now", new DateTimeZone($this->zone_name));
		$startTime = $date->format('Y-m-d H:i:s');
		$date = new DateTime("now", new DateTimeZone($this->zone_name));
		$date->add(new DateInterval('PT60S'));
		//$endTime = date('Y-m-d H:i:s',strtotime('+60 seconds',strtotime($startTime)));
		$endTime = $date->format('Y-m-d H:i:s');
		if($taxi_type!='' && $taxi_type!=null)
		{
			$booking_update=array(
				'taxi_type' => $taxi_type,
				'amount'	=> $amount
				);
			$this->db->where('id',$id);
			$update=$this->db->update('bookingdetails',$booking_update);
		}
		if($data_id!='' && $data_id!=null){
			$data=array(
				'driver_id' => $data_id,
				'booking_id' => $id,
				'start_time' => $startTime,
				'end_time' => $endTime
			);
			$insert=$this->db->insert('driver_status',$data);
		}
		if($insert){
			echo 1;
		}
		else{
			echo 0;
		}
		/*$data=array(
			'assigned_for' => $data_id
		);
		$this->db->where('id',$id);
		if($this->db->update('bookingdetails',$data)){
			echo 1;
		}
		else{
			echo 0;
		}*/

	}
	
	// Delete booking call
	function deletemultibooking($data){
		//$id = $data['id'];
		$this->db->where_in('id', $data);
		if($this->db->delete('bookingdetails'))
		{
			print_r($data);
		}
		else{
			print_r($data);
		}
	}

	// Delete booking call
	function delbooking($data_ids)
	{
		$data_id_array = explode(",", $data_ids);
		if(!empty($data_id_array)) {
			foreach($data_id_array as $id) {
				$this->db->where('id',$id);
				$this->db->delete('bookingdetails');
			}
		}
	}

	// Delete single booking call
	function delsinglebooking($data_id)
	{
		if (!empty($data_id)) {
			$this->db->where('id', $data_id);
			$this->db->delete('bookingdetails');
		}
	}

	// Get driver list call
	function getdriver($requestData,$flagfilter,$where)
	{
		$columns = array(
			// datatable column index  => database column name
			0 => 'id',
			1 => 'image',
			2 => 'name',
			3 => 'phone',
			4 => 'address',
			5 => 'license_no',
			6 => 'car_type',
			7 => 'car_no',
			8 => 'socket_status',
			9 => 'status'
		);

		$flag_disp = $flagfilter;
		// getting total number records without any search
		$this->db->select('*');
		if($flag_disp!='' || $flag_disp!=NULL){
			$this->db->where('flag',$flag_disp);
		}
		$totalData=$this->db->get('driver_details')->num_rows();
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

		$this->db->select('id, image, name, phone, address, license_no,car_type,car_no,socket_status,status,flag');
		$this->db->from('driver_details');
		$this->db->join('cabdetails','cabdetails.cab_id=driver_details.car_type');
		if($flag_disp!='' || $flag_disp!=NULL){
			$this->db->where('flag',$flag_disp);
		}
		if ($where !== null) {
			$this->db->where($where);
		}
		if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
			$keywords=$requestData['search']['value'].'%';
			if($flag_disp!='' || $flag_disp!=NULL){
				$this->db->where('flag',$flag_disp);
			}
			//$this->db->where('status','1');
			$this->db->where("(name LIKE '$keywords' OR phone LIKE '$keywords' OR address LIKE '$keywords' OR license_no LIKE '$keywords' OR cabdetails.cartype LIKE '$keywords' OR car_no LIKE '$keywords')");
		}
		$totalFiltered=$this->db->get()->num_rows();
		$this->db->select('id, image, name, phone, address, license_no,car_type,car_no,socket_status,status,flag');
		$this->db->from('driver_details');
		$this->db->join('cabdetails','cabdetails.cab_id=driver_details.car_type');
		if($flag_disp!='' || $flag_disp!=NULL){
			$this->db->where('flag',$flag_disp);
		}
		if ($where !== null) {
			$this->db->where($where);
		}
		if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
			$keywords=$requestData['search']['value'].'%';
			if($flag_disp!='' || $flag_disp!=NULL){
				$this->db->where('flag',$flag_disp);
			}
			$this->db->where("(name LIKE '$keywords' OR phone LIKE '$keywords' OR address LIKE '$keywords' OR license_no LIKE '$keywords' OR cabdetails.cartype LIKE '$keywords' OR car_no LIKE '$keywords')");
		}
		//echo $columns[$requestData['order'][0]['column']];
		$this->db->order_by($columns[$requestData['order'][0]['column']],$requestData['order'][0]['dir']);
		$this->db->limit($requestData['length'],$requestData['start']);
		$resultarray=$this->db->get()->result_array();

		$data = array();
		$i=1+$requestData['start'];
		foreach($resultarray as $item)
		{
			// preparing an array
			$nestedData=array();

			$nestedData[] = "<input type='checkbox'  class='deleteRow' value='".$item['id']."'  />" ;
			if($item['image']) {
				$nestedData[] = '<img src=' . base_url() . 'driverimages/'. $item["image"] . '>';
			}
			else{
				$nestedData[] = '<img src="' . base_url() . 'upload/no-image-icon.png">';
			}
			$nestedData[] = $item["name"];
			$nestedData[] = $item["phone"];
			$nestedData[] = $item["address"];
			$nestedData[] = $item["license_no"];
			if($item['car_type'])
			{
				$this->db->where('cab_id',$item['car_type']);
				$getcartype=$this->db->get('cabdetails')->row();
			}
			$nestedData[] = $getcartype->cartype;
			$nestedData[] = $item["car_no"];
			if($item['socket_status']=='Active')
			{
				$nestedData[] = '<span class="label label-success"><a href="javascript:void(0)" style="color: white;">Online</a></span>';
			}
			else
			{
				$nestedData[] = '<span class="label label-default"><a href="javascript:void(0)" style="color: white;">Offline</a></span></span>';
			}
			if($item['status']=='Active')
			{
				$nestedData[] = '<span class="label label-success"><a href="javascript:void(0)" onclick="driverstatus('.$item["id"].')"  style="color: white;">Active</a></span>';
			}
			else
			{
				$nestedData[] = '<span class="label label-default"><a href="javascript:void(0)" onclick="driverstatus('.$item["id"].')"  style="color: white;">Inactive</a></span></span>';
			}
			$nestedData[] = '<a class="table-link" href="javascript:void(0);" onclick="window.location.href=\'view_driver_details?id='.$item['id'].'\'">
				<span class="fa-stack">
					<i class="fa fa-square fa-stack-2x"></i>
					<i class="fa fa-search-plus fa-stack-1x fa-inverse"></i>
				</span>
			</a>

            <a data-target="#uidemo-modals-alerts-delete-user" data-toggle="modal" class="table-link danger" href="javascript:void(0);" onclick="delete_single_user('.$item["id"].')">
				<span class="fa-stack">
					<i class="fa fa-square fa-stack-2x"></i>
					<i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
				</span>
			</a>';

			$data[] = $nestedData;
			$i++;
		}

		$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
		);

		return json_encode($json_data);  // send data as json format
	}

	// Get select driver list call
	function getselectdriver($requestData,$booking_id,$where)
	{
		$columns = array(
			// datatable column index  => database column name
			0 => 'id',
			1 => 'id',
			2 => 'name',
			3 => 'phone',
			4 => 'license_no',
			5 => 'car_type',
			6 => 'car_no',
			7 => 'status'
		);

		$this->db->select('*');
		$this->db->from('driver_status');
		$this->db->where('booking_id',$booking_id);
		$this->db->or_where('driver_flag','0');
		$this->db->or_where('driver_flag','1');
		$driver_list=$this->db->get()->result_array();
		if($driver_list){
			foreach($driver_list as $dl){
				$filterData[]=$dl['driver_id'];
			}
		}
		else{
			$filterData[]='';
		}
		// getting total number records without any search
		$this->db->select('*');
		$this->db->where('status','Active');
		$this->db->where('socket_status','Active');
		$this->db->where_not_in('id',$filterData);
		$totalData=$this->db->get('driver_details')->num_rows();
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

		$this->db->select('id,name,phone,license_no,car_type,car_no,status');
		$this->db->from('driver_details');
		$this->db->join('cabdetails','cabdetails.cab_id=driver_details.car_type');
		$this->db->where('status','Active');
		$this->db->where('socket_status','Active');
		$this->db->where_not_in('id',$filterData);
		if ($where !== null) {
			$this->db->where($where);
		}
		if(!empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
			$keywords=$requestData['search']['value'].'%';
			$this->db->where('status','Active');
			$this->db->where('socket_status','Active');
			$this->db->where_not_in('id',$filterData);
			$this->db->where("(id LIKE '$keywords' OR name LIKE '$keywords' OR phone LIKE '$keywords' OR license_no LIKE '$keywords' OR cabdetails.cartype LIKE '$keywords' OR car_no LIKE '$keywords')");
		}
		$totalFiltered=$this->db->get()->num_rows();
		$this->db->select('id,name,phone,license_no,car_type,car_no,status');
		$this->db->from('driver_details');
		$this->db->join('cabdetails','cabdetails.cab_id=driver_details.car_type');
		$this->db->where('status','Active');
		$this->db->where('socket_status','Active');
		$this->db->where_not_in('id',$filterData);
		if ($where !== null) {
			$this->db->where($where);
		}
		if(!empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
			$keywords=$requestData['search']['value'].'%';
			$this->db->where('status','Active');
			$this->db->where('socket_status','Active');
			$this->db->where_not_in('id',$filterData);
			$this->db->where("(id LIKE '$keywords' OR name LIKE '$keywords' OR phone LIKE '$keywords' OR license_no LIKE '$keywords' OR cabdetails.cartype LIKE '$keywords' OR car_no LIKE '$keywords')");
		}
		//echo $columns[$requestData['order'][0]['column']];
		$this->db->order_by($columns[$requestData['order'][0]['column']],$requestData['order'][0]['dir']);
		$this->db->limit($requestData['length'],$requestData['start']);
		$resultarray=$this->db->get()->result_array();

		$data = array();
		$i=1+$requestData['start'];
		foreach($resultarray as $item)
		{
			// preparing an array
			$nestedData=array();

			$nestedData[] = "" ;
			$nestedData[] = $item["id"];
			$nestedData[] = $item["name"];
			$nestedData[] = $item["phone"];
			$nestedData[] = $item["license_no"];
			if($item['car_type'])
			{
				$this->db->where('cab_id',$item['car_type']);
				$getcartype=$this->db->get('cabdetails')->row();
				$nestedData[]=$getcartype->cartype;
			}
			$nestedData[] = $item["car_no"];
			if($item['status']=='Active')
			{
				$nestedData[] = '<span class="label label-success"><a onclick="driverstatus('.$item["id"].')"  style="color: white;">Active</a></span>';
			}
			else
			{
				$nestedData[] = '<span class="label label-default"><a onclick="driverstatus('.$item["id"].')"  style="color: white;">Inactive</a></span></span>';
			}
			$data[] = $nestedData;
			$i++;
		}

		$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
		);

		return json_encode($json_data);  // send data as json format
	}

	// Delete driver call
	function deldriver($data_ids)
	{
		$data_id_array = explode(",", $data_ids);
		if(!empty($data_id_array)) {
			foreach($data_id_array as $id) {
				$this->db->where('id',$id);
				$this->db->delete('driver_details');
			}
		}
	}

	// Delete single driver call
	function delsingledriver($data_id)
	{
		if(!empty($data_id)) {
			$this->db->where('id',$data_id);
			$this->db->delete('driver_details');
		}
	}

	// get car type data call
	function getcartypedata($cab_id)
	{
		$this->db->select('*');
		$this->db->where('cab_id',$cab_id);
		$result=$this->db->get('cabdetails')->row();
		if($result){
			return $result;
		}
		else{
			return false;
		}
	}
	// Get car list call
	/*function getcar($requestData,$where)
	{
		$columns = array(
			// datatable column index  => database column name
			0 => 'car_id',
			1 => 'car_type',
			2 => 'icon',
			3 => 'car_rate',
			4 => 'seating_capecity'
		);

		// getting total number records without any search
		$this->db->select('*');
		$totalData=$this->db->get('Car_Type')->num_rows();
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

		$this->db->select('car_id, car_type, icon, car_rate, seating_capecity');
		$this->db->from('Car_Type');
		if ($where !== null) {
			$this->db->where($where);
		}
		if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
			$this->db->like('car_type',$requestData['search']['value'],'after');
			$this->db->or_like('icon',$requestData['search']['value'],'after');
			$this->db->or_like('car_rate',$requestData['search']['value'],'after');
			$this->db->or_like('seating_capecity',$requestData['search']['value'],'after');
		}
		$totalFiltered=$this->db->get()->num_rows();
		$this->db->select('car_id, car_type, icon, car_rate, seating_capecity');
		$this->db->from('Car_Type');
		if ($where !== null) {
			$this->db->where($where);
		}
		if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
			$this->db->like('car_type',$requestData['search']['value'],'after');
			$this->db->or_like('icon',$requestData['search']['value'],'after');
			$this->db->or_like('car_rate',$requestData['search']['value'],'after');
			$this->db->or_like('seating_capecity',$requestData['search']['value'],'after');
		}
		//echo $columns[$requestData['order'][0]['column']];
		$this->db->order_by($columns[$requestData['order'][0]['column']],$requestData['order'][0]['dir']);
		$this->db->limit($requestData['length'],$requestData['start']);
		$resultarray=$this->db->get()->result_array();

		$data = array();
		$i=1+$requestData['start'];
		foreach($resultarray as $item)
		{
			// preparing an array
			$nestedData=array();

			$nestedData[] = "<input type='checkbox'  class='deleteRow' value='".$item['car_id']."'  />" ;
			if($item['icon']) {
				$nestedData[] = '<img src=' . base_url() .'car_image/'. $item["icon"] . '>';
			}
			else{
				$nestedData[] = '<img src="' . base_url() . 'upload/no-car-image.png">';
			}
			$nestedData[] = $item["car_type"];
			$nestedData[] = $item["car_rate"];
			$nestedData[] = $item["seating_capecity"];

			$nestedData[] = '

			<a onclick="window.location.href=\'view_cartype_details?id='.$item['car_id'].'\'" href="javascript:void(0);" class="table-link">
				<span class="fa-stack">
					<i class="fa fa-square fa-stack-2x"></i>
					<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
				</span>
			</a>
            <a data-target="#uidemo-modals-alerts-delete-user" data-toggle="modal" class="table-link danger" href="javascript:void(0);" onclick="delete_single_user('.$item["car_id"].')">
				<span class="fa-stack">
					<i class="fa fa-square fa-stack-2x"></i>
					<i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
				</span>
			</a>';

			$data[] = $nestedData;
			$i++;
		}

		$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
		);

		return json_encode($json_data);  // send data as json format
	}*/

	// Insert car data call
	function insertcardata($data)
	{
		$insert=$this->db->insert('cabdetails',$data);
		if($insert){
			return $data;
		}
		else{
			return false;
		}
	}

	// Check email and username call
	function checkemailusername($email,$username)
	{
		$this->db->where('email',$email);
		$this->db->or_where('user_name',$username);
		$result=$this->db->get('driver_details')->result_array();
		if($result)
		{
			return $result;
		}
		else{
			return 0;
		}
	}
	// Insert driver data call
	function insertdriverdata($data)
	{
		$insert=$this->db->insert('driver_details',$data);
		if($insert){
			return $data;
		}
		else{
			return false;
		}
	}

	// Get car list call
	function getcar($requestData,$where)
	{
		$columns = array(
			// datatable column index  => database column name
			0 => 'cab_id',
			1 => 'cartype',
			2 => 'icon',
			3 => 'car_rate',
			4 => 'seat_capacity'

		);

		// getting total number records without any search
		$this->db->select('*');
		$totalData=$this->db->get('cabdetails')->num_rows();
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

		$this->db->select('cab_id,cartype, icon, car_rate, seat_capacity');
		$this->db->from('cabdetails');
		if ($where !== null) {
			$this->db->where($where);
		}
		if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
			$this->db->like('cartype',$requestData['search']['value'],'after');
			$this->db->or_like('icon',$requestData['search']['value'],'after');
			$this->db->or_like('car_rate',$requestData['search']['value'],'after');
			$this->db->or_like('seat_capacity',$requestData['search']['value'],'after');
		}
		//$totalFiltered=$this->db->get()->num_rows();
		//echo $columns[$requestData['order'][0]['column']];
		$this->db->order_by($columns[$requestData['order'][0]['column']],$requestData['order'][0]['dir']);
		$this->db->limit($requestData['length'],$requestData['start']);
		$resultarray=$this->db->get()->result_array();

		$data = array();
		$i=1+$requestData['start'];
		foreach($resultarray as $item)
		{
			// preparing an array
			$nestedData=array();

			$nestedData[] = "<input type='checkbox'  class='deleteRow' value='".$item['cab_id']."'  />" ;
			$nestedData[] = '<img src='.base_url().'car_image/'.$item["icon"].'>';
			$nestedData[] = $item["cartype"];
			$nestedData[] = $item["car_rate"];
			$nestedData[] = $item["seat_capacity"];

			$nestedData[] = '

			<a onclick="window.location.href=\'view_cartype_details?id='.$item['cab_id'].'\'" href="javascript:void(0);" class="table-link">
				<span class="fa-stack">
					<i class="fa fa-square fa-stack-2x"></i>
					<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
				</span>
			</a>
            <a data-target="#uidemo-modals-alerts-delete-user" data-toggle="modal" class="table-link danger" href="javascript:void(0);" onclick="delete_single_user('.$item["cab_id"].')">
				<span class="fa-stack">
					<i class="fa fa-square fa-stack-2x"></i>
					<i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
				</span>
			</a>';

			$data[] = $nestedData;
			$i++;
		}

		$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
		);

		return json_encode($json_data);  // send data as json format
	}

	// Delete car call
	function delcar($data_ids)
	{
		$data_id_array = explode(",", $data_ids);
		if(!empty($data_id_array)) {
			foreach($data_id_array as $id) {
				$this->db->where('cab_id',$id);
				$this->db->delete('cabdetails');
			}
		}
	}

	// Delete single car call
	function delsinglecar($data_id)
	{
		if(!empty($data_id)) {
			$this->db->where('cab_id',$data_id);
			$this->db->delete('cabdetails');
		}
	}

	// Get time type list call
	function gettimetype($requestData,$where)
	{
		$columns = array(
			// datatable column index  => database column name
			0 => 'tid',
			1 => 'day_start_time',
			2 => 'day_end_time'
		);

		// getting total number records without any search
		$this->db->select('*');
		$totalData=$this->db->get('time_detail')->num_rows();
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

		$this->db->select('tid, day_start_time, day_end_time');
		$this->db->from('time_detail');
		if ($where !== null) {
			$this->db->where($where);
		}
		if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
			$this->db->like('day_start_time',$requestData['search']['value'],'after');
			$this->db->or_like('day_end_time',$requestData['search']['value'],'after');
		}
		$totalFiltered=$this->db->get()->num_rows();
		$this->db->select('tid, day_start_time, day_end_time');
		$this->db->from('time_detail');
		if ($where !== null) {
			$this->db->where($where);
		}
		if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
			$this->db->like('day_start_time',$requestData['search']['value'],'after');
			$this->db->or_like('day_end_time',$requestData['search']['value'],'after');
		}
		//echo $columns[$requestData['order'][0]['column']];
		$this->db->order_by($columns[$requestData['order'][0]['column']],$requestData['order'][0]['dir']);
		$this->db->limit($requestData['length'],$requestData['start']);
		$resultarray=$this->db->get()->result_array();

		$data = array();
		$i=1+$requestData['start'];
		foreach($resultarray as $item)
		{
			// preparing an array
			$nestedData=array();

			$nestedData[] = "<input type='checkbox'  class='deleteRow' value='".$item['tid']."'  />" ;
			$nestedData[] = $item["day_start_time"];
			$nestedData[] = $item["day_end_time"];

			$nestedData[] = '<a onclick="window.location.href=\'edit_time_type?tid='.$item['tid'].'\'" href="javascript:void(0);" class="table-link">
				<span class="fa-stack">
					<i class="fa fa-square fa-stack-2x"></i>
					<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
				</span>
			</a>';

			$data[] = $nestedData;
			$i++;
		}

		$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
		);

		return json_encode($json_data);  // send data as json format
	}

	// Get reasons list call
	function getreasons($requestData,$where)
	{
		$columns = array(
			// datatable column index  => database column name
			0 => 'reason_id',
			1 => 'reason_id',
			2 => 'reason_title',
			3 => 'reason_text'
		);

		// getting total number records without any search
		$this->db->select('*');
		$totalData=$this->db->get('delay_reasons')->num_rows();
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

		$this->db->select('reason_id, reason_title, reason_text');
		$this->db->from('delay_reasons');
		if ($where !== null) {
			$this->db->where($where);
		}
		if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
			$this->db->like('reason_id',$requestData['search']['value'],'after');
			$this->db->or_like('reason_title',$requestData['search']['value'],'after');
			$this->db->or_like('reason_text',$requestData['search']['value'],'after');
		}
		$totalFiltered=$this->db->get()->num_rows();
		$this->db->select('reason_id, reason_title, reason_text');
		$this->db->from('delay_reasons');
		if ($where !== null) {
			$this->db->where($where);
		}
		if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
			$this->db->like('reason_id',$requestData['search']['value'],'after');
			$this->db->or_like('reason_title',$requestData['search']['value'],'after');
			$this->db->or_like('reason_text',$requestData['search']['value'],'after');
		}
		//echo $columns[$requestData['order'][0]['column']];
		$this->db->order_by($columns[$requestData['order'][0]['column']],$requestData['order'][0]['dir']);
		$this->db->limit($requestData['length'],$requestData['start']);
		$resultarray=$this->db->get()->result_array();

		$data = array();
		$i=1+$requestData['start'];
		foreach($resultarray as $item)
		{
			// preparing an array
			$nestedData=array();

			$nestedData[] = "<input type='checkbox'  class='deleteRow' value='".$item['reason_id']."'  />" ;
			$nestedData[] = $item["reason_id"];
			$nestedData[] = $item["reason_title"];
			$nestedData[] = $item["reason_text"];

			$nestedData[] = '

			<a onclick="window.location.href=\'view_delayreason_details?id='.$item['reason_id'].'\'" href="javascript:void(0);" class="table-link">
				<span class="fa-stack">
					<i class="fa fa-square fa-stack-2x"></i>
					<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
				</span>
			</a>
            <a data-target="#uidemo-modals-alerts-delete-user" data-toggle="modal" class="table-link danger" href="javascript:void(0);" onclick="delete_single_reason('.$item["reason_id"].')">
				<span class="fa-stack">
					<i class="fa fa-square fa-stack-2x"></i>
					<i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
				</span>
			</a>';

			$data[] = $nestedData;
			$i++;
		}

		$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
		);

		return json_encode($json_data);  // send data as json format
	}

	// Delete reason call
	function delres($data_ids)
	{
		$data_id_array = explode(",", $data_ids);
		if(!empty($data_id_array)) {
			foreach($data_id_array as $id) {
				$this->db->where('reason_id',$id);
				$this->db->delete('delay_reasons');
			}
		}
	}

	// Delete single reason call
	function delsingleres($data_id)
	{
		if(!empty($data_id)) {
			$this->db->where('reason_id',$data_id);
			$this->db->delete('delay_reasons');
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
		$data=array(
			'driver_id' => $driver_id,
			'booking_id' => $booking_id,
			'start_time' => $startTime,
			'end_time' => $endTime
		);
		$insert=$this->db->insert('driver_status',$data);
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
			'status'	=> 6
		);
		$this->db->where('id',$booking_id);
		$this->db->update('bookingdetails',$data);
	}
	/*function convert_from_another_time($source, $source_timezone, $dest_timezone){
		$offset = $dest_timezone - $source_timezone;
		if($offset == 0)
			return $source;
		$target = new DateTime($source->format(Y-m-d H:i:s));
    	$target->modify($offset+' hours');
    	return $target;
	}*/

	// update user status call
	function statususer($data_id)
	{
		if(!empty($data_id)) {
			$this->db->where('id',$data_id);
			$row=$this->db->get('userdetails')->row();
			if($row->user_status == 'Active'){
				$data=array(
					'user_status' => 'Inactive'
				);
				$this->db->where('id',$data_id);
				$this->db->update('userdetails',$data);
			}
			else{
				$data=array(
					'user_status' => 'Active'
				);
				$this->db->where('id',$data_id);
				$this->db->update('userdetails',$data);
			}
		}

		/*$url=$_SERVER['REQUEST_URI'];
		$id1=explode('id=',$url);
		$id = $id1[1];
		$query=$this->db->query("SELECT * FROM `userdetails` WHERE id=$id");
		$row=$query->row();
		$status= $row->user_status;
		if($status=='Active') {
			$query1 = $this->db->query("update userdetails set user_status='Inactive' WHERE id=$id");
		}
		else
		{
			$query1 = $this->db->query("update userdetails set user_status='Active' WHERE id=$id");
		}
		if($query1)

		{
			//echo "0";
			//redirect('http://192.168.1.7/gagaji/WebApp/Source/admin/manage_user');
			redirect("http://$_SERVER[HTTP_HOST]/WebApp/Source/admin/manage_user");
		}
		else{

			//echo 0;
			//redirect('http://192.168.1.7/gagaji/WebApp/Source/admin/manage_user');
			redirect("http://$_SERVER[HTTP_HOST]/WebApp/Source/admin/manage_user");
		}*/

	}

	// update driver status call
	function statusdriver($data_id)
	{
		if(!empty($data_id)) {
			$this->db->where('id',$data_id);
			$row=$this->db->get('driver_details')->row();
			if($row->status == 'Active'){
				$data=array(
					'status' => 'Inactive'
				);
				$this->db->where('id',$data_id);
				if($this->db->update('driver_details',$data)){
					return true;
				}
				else{
					return false;
				}
			}
			else{
				$data=array(
					'status' => 'Active'
				);
				$this->db->where('id',$data_id);
				if($this->db->update('driver_details',$data)){
					return false;
				}
				else{
					return false;
				}
			}
		}
		/*$url=$_SERVER['REQUEST_URI'];
		$id1=explode('id=',$url);
		$id = $id1[1];
		$query=$this->db->query("SELECT * FROM `driver_details` WHERE id=$id");
		$row=$query->row();
		$status= $row->status;
		if($status=='Active') {
			$query1 = $this->db->query("update driver_details set status='Inactive' WHERE id=$id");
		}
		else
		{
			$query1 = $this->db->query("update driver_details set status='Active' WHERE id=$id");
		}
		if($query1)

		{
			//echo "0";
			//redirect('http://192.168.1.7/gagaji/WebApp/Source/admin/manage_user');
			redirect("http://$_SERVER[HTTP_HOST]/WebApp/Source/admin/manage_driver");
		}
		else{

			//echo 0;
			//redirect('http://192.168.1.7/gagaji/WebApp/Source/admin/manage_user');
			redirect("http://$_SERVER[HTTP_HOST]/WebApp/Source/admin/manage_driver");
		}*/

	}

	// calculate ride rates
		function calculaterates($pickup_date_time,$cab_id,$approx_distance,$approx_time)
		{
			$this->db->where('cab_id',$cab_id);
			$query=$this->db->get('cabdetails');
			$row = $query->row();
			$initial_km=$row->intialkm;
			$initial_rate=$row->car_rate;
			$night_initial_rate=$row->night_intailrate;
			$after_initial_km_rate=$row->fromintailrate;
			$night_after_initial_km_rate=$row->night_fromintailrate;
			$per_minute_rate=$row->ride_time_rate;
			$night_per_minute_rate=$row->night_ride_time_rate;

			$myDate = new DateTime();
        	//$myDate->setTimestamp(strtotime($request->book_date));
        	$myDate->setTimestamp(strtotime($pickup_date_time));

        	$time = $myDate->format("H");

        	$query1=$this->db->get('time_detail');
			$row1 = $query1->row();
        	//if ($time >= 22 || $time <= 6)
        	if((float)$time >= $row1->day_end_time || (float)$time <= $row1->day_start_time)
        	{
            	$timetype = 'night';
        	} else {
            	$timetype = 'day';
        	}

			if($approx_distance && (float)$approx_distance>$initial_km)
			{
				$remaining_km=$approx_distance-$initial_km;
				if($timetype=='day'){
					// calculate distance rate
					$new_after_initial_km_rate=$after_initial_km_rate*$remaining_km;
					$total_distance_rate=$initial_rate+$new_after_initial_km_rate;
					// calculate driver rate
					$total_driver_rate=$per_minute_rate*$approx_time;
					// total rate
					$total_rate=$total_distance_rate+$total_driver_rate;
				}
				else if($timetype=='night'){
					// calculate night distance rate
					$night_new_after_initial_km_rate=$night_after_initial_km_rate*$remaining_km;
					$night_total_distance_rate=$night_initial_rate+$night_new_after_initial_km_rate;
					// calculate night driver rate
					$night_total_driver_rate=$night_per_minute_rate*$approx_time;
					// total rate
					$total_rate=$night_total_distance_rate+$night_total_driver_rate;
				}
			}
			else{
				if($timetype=='day'){
					// calculate distance rate
					$total_distance_rate=$initial_rate;
					// calculate driver rate
					$total_driver_rate=$per_minute_rate*$approx_time;
					// total rate
					$total_rate=$total_distance_rate+$total_driver_rate;
				}
				else if($timetype=='night'){
					// calculate night distance rate
					$night_total_distance_rate=$night_initial_rate;
					// calculate night driver rate
					$night_total_driver_rate=$night_per_minute_rate*$approx_time;
					// total night rate
					$total_rate=$night_total_distance_rate+$night_total_driver_rate;
				}
			}
			return $total_rate;
		}
	/*
	// user insert call
	function userinsert($data)
	{
	  $username = $data['username'];
	  $email = $data['email'];
	  $this->db->where('username', $username);
	  $query = $this->db->get('userdetails');
	 
	   if($query->num_rows == 0)
        {
			 $this->db->where('email',  $email);
			 $query1 = $this->db->get('userdetails');
			if($query1->num_rows == 0)
        	{
				if($this->db->insert('userdetails',$data))
				{
	 				$this->session->set_userdata('username-admin',$data['username']);
	 				$user1 = $this->session->userdata('username-admin');
					echo 1;
				}
				else{
					echo 0;
				}
			}
			else
			{
					echo 4;
			}
		}else{
					echo 3;
	   }
	}

	function addtaxi($data)
	{
		$insert = $this->db->insert('Car_Type',$data);
		if($insert){
			return $this->db->insert_id();
		}else{
			return false;
		}
	}

	function addtaxi5july($data,$img)
	{
		$car_icon= $data['car_icon'];

		$car_type= $data['car_type'];
		//$car_icon= $data['car_icon'];
		$this->db->where('car_type',$car_type);
		$this->db->where('car_icon',$car_icon);
		$query = $this->db->get('Car_Type');
		if($query->num_rows == 0)
		{
			if($this->db->insert('Car_Type',$data))
			{
				echo 1;
			}
			else{
				echo 0;
			}
		}else{
			echo 2;
		}
	}

	function deletebook($data){

	
		$id = $data['id'];
		
		$this->db->where('id', $id);
		 
		if($this->db->delete('bookingdetails'))
	
		{
	
           echo 1;
					 // redirect("http://$_SERVER[HTTP_HOST]/gagaji/WebApp/Source/admin/pointview");
        }
        else{
	
              echo 0;
				  // redirect("http://$_SERVER[HTTP_HOST]/gagaji/WebApp/Source/admin/pointview");
        }
	}

	function edituser($data)
	{
	    $email = $data['email'];
		$id = $data['id'];
		
		$this->db->where('id', $id);
		if($this->db->update('userdetails',$data))
	
		{

		echo 1;
		}
		else{

		echo 0;
		}

	}

	function pointupdate($data)
	{
		 	$driver = $data['assigned_for'];
		 	$username =$data['username'];
		
			if(isset($data['id'])){
				$id = $data['id'];
				$this->db->where('id', $id);
			}
			if(isset($data['uneaque_id'])){
				$bookid = $data['uneaque_id'];
				$this->db->where('uneaque_id', $bookid);
			}
		
			if($this->db->update('bookingdetails',$data))
			{

			$query3 = $this->db->query(" SELECT * FROM `settings` order by id DESC ");
			$row3 = $query3->row('settings');
			$from= $row3->smtp_username;
			$name=$row3->title;
		   $urls= base_url();
			$sub="Driver Details";
			 $query = $this->db->query("SELECT * FROM  userdetails WHERE  username ='$username'");
				 $row = $query->row('userdetails');
			$email= $row->email;
			 $query1 = $this->db->query("SELECT * FROM  driver_details WHERE  id ='$driver'");
				 $row1 = $query1->row('driver_details');
			 $mailTemplate='<div style="width:660px; height:400px; margin:0 auto; background:#f2c21e; padding:20px 20px 20px 20px; font-family: Century Gothic,Verdana,Geneva,sans-serif; border:solid #c79e13 1px;">
			
			<div style="width:100%; float:left; padding:0 0 10px 0;">
			<div style="width:138px; height:73px; float:left; margin:0 0 0 20px;"> <img src="'.$urls.'assets/images/carss.png" alt="" /></div>
			<div style="width:350px; float:left; padding:25px 0 0 0; text-align:center; font-size:18px; "> BOOKING DETAILS </div>
			</div>
				<div style="background:#fff;  float:left; width:96.3%;   border-top-right-radius: 8px; border-top-left-radius: 8px; padding:15px 12px 0 12px;  ">
					<div style="width:100%; padding:10px 0 10px 0; float:left; color:#666261; font-size:14px; text-align:center;"> Driver Details </div>
						 <div style="width:100%; float:left; padding:15px 0 15px 0; border-bottom:solid #cdcdcd 1px; border-top:solid #cdcdcd 1px;"> 
						 <div style="width:100%; float:left; font-size:14px; text-align:center;"> Thank you for choosing our service. We are happy to serve you!!!</div>
					</div>
				</div>
				<div style="background:#3a3a3c;     float:left; width:96.3%;  padding:5px 12px 10px 12px;">
				  <div style="width:100%; float:left;">
					
					  <div style="width:100%;   float:left; background:#585858; padding:15px 0 15px 0; margin:5px 0 0 0; "> 
						<div style="width:100%; float:left; text-align:center; color:#ffdd1a; font-size:16px;">Driver Name:  <div style="color:#fff; font-size:14px; display:inline;">'. $row1->name.'</div></div>
						</div>
						
				  <div style="width:100%;   float:left; background:#585858; padding:15px 0 15px 0; margin:5px 0 0 0; "> 
						<div style="width:100%; float:left; text-align:center; color:#ffdd1a; font-size:16px;"> Mobile: <div style="color:#fff; font-size:14px; display:inline;">'. $row1->phone.'</div></div>
						</div>
						
				  <div style="width:100%;   float:left; background:#585858; padding:15px 0 15px 0; margin:5px 0 0 0; "> 
						<div style="width:100%; float:left; text-align:center; color:#ffdd1a; font-size:16px;"> Address: <div style="color:#fff; font-size:14px; display:inline;">'. $row1->address.'</div></div>
						</div>
					</div>
			   </div>
				<div style="background:#fff;  float:left; width:96.3%;    border-bottom-right-radius: 8px; border-bottom-left-radius: 8px; padding:20px 12px 20px 12px;  "></div>
				</div>
		</div>';

			$this->home->send_mail($from,$name,$email,$sub,$mailTemplate);


			$email1= $row1->email;
			$sub1="Passenger Details";

			if(isset($data['id'])){
					$id = $data['id'];
					$s ="WHERE  id ='$id'";
				}
				if(isset($data['uneaque_id'])){
					$bookid = $data['uneaque_id'];
					$s ="WHERE  uneaque_id ='$bookid'";

				}
			 $query31 = $this->db->query("SELECT * FROM  bookingdetails ".$s );
				 $row31 = $query31->row('bookingdetails');
			$mailTemplate1='<div style="width:660px; height:400px; margin:0 auto; background:#f2c21e; padding:20px 20px 20px 20px; font-family: Century Gothic,Verdana,Geneva,sans-serif; border:solid #c79e13 1px;">
			
			<div style="width:100%; float:left; padding:0 0 10px 0;">
			<div style="width:138px; height:73px; float:left; margin:0 0 0 20px;"> <img src="'.$urls.'assets/images/carss.png" alt="" /></div>
			<div style="width:350px; float:left; padding:25px 0 0 0; text-align:center; font-size:18px; "> BOOKING DETAILS </div>
			
			
			</div>
			
			
			
			
				<div style="background:#fff;  float:left; width:96.3%;   border-top-right-radius: 8px; border-top-left-radius: 8px; padding:15px 12px 0 12px;  ">
					<div style="width:100%; padding:10px 0 10px 0; float:left; color:#666261; font-size:14px; text-align:center;"> Passenger Details </div>
						 <div style="width:100%; float:left; padding:15px 0 15px 0; border-bottom:solid #cdcdcd 1px; border-top:solid #cdcdcd 1px;"> 
						 <div style="width:100%; float:left; font-size:14px; text-align:center;"> Congrats!!! You got a new trip.</div>
					</div>
				</div>
				
				
				
				
				
				<div style="background:#3a3a3c;     float:left; width:96.3%;  padding:5px 12px 10px 12px;">
				  <div style="width:100%; float:left;">
					
					  <div style="width:100%;   float:left; background:#585858; padding:15px 0 15px 0; margin:5px 0 0 0; "> 
						<div style="width:100%; float:left; text-align:center; color:#ffdd1a; font-size:16px;">Passenger Name:  <div style="color:#fff; font-size:14px; display:inline;">'. $row->username.'</div></div>
						</div>
						
				  <div style="width:100%;   float:left; background:#585858; padding:15px 0 15px 0; margin:5px 0 0 0; "> 
						<div style="width:100%; float:left; text-align:center; color:#ffdd1a; font-size:16px;">Pickup-Drop Area: <div style="color:#fff; font-size:14px; display:inline;">'. $row31->pickup_area.'-'. $row31->drop_area.'</div></div>
						</div>
						
				  <div style="width:100%;   float:left; background:#585858; padding:15px 0 15px 0; margin:5px 0 0 0; "> 
						<div style="width:100%; float:left; text-align:center; color:#ffdd1a; font-size:16px;"> Pickup date & Time: <div style="color:#fff; font-size:14px; display:inline;">'. date('D, d M',strtotime($row31->pickup_date)).', '. $row31->pickup_time.'</div></div>
						</div>
						
						
						
						
					</div>
			   </div>
					
					
					
					
				<div style="background:#fff;  float:left; width:96.3%;    border-bottom-right-radius: 8px; border-bottom-left-radius: 8px; padding:20px 12px 20px 12px;  "></div>
					
					
					
					
					
					
					
				</div>
		
		
		
		
		
		
		
		</div>';

		$this->home->send_mail($from,$name,$email1,$sub1,$mailTemplate1);
				echo 1;




		}
		else{

		echo 0;
		}

	}
	function pormoadd($data)
	{

		if($this->db->insert('promocode',$data))
		{

		echo 1;
		}
		else{
		echo 0;
		}

	}

	function deleteprom($data)
	{
	
	
		$id = $data['id'];
		
		$this->db->where('id', $id);
		 
		if($this->db->delete('promocode'))
	
                  {
	
            echo 1;
                    }
               else{
	
              echo 0;
                    }

	}

	function promoupdate($data)
	{
	    
		$id = $data['id'];
		
		$this->db->where('id', $id);
		if($this->db->update('promocode',$data))
	
		{
	
			echo 1;
		}
		else{
	
			echo 0;
		}

	}

	function timeadd($data)
	{
		$day_start_time=$data['day_start_time'];
		$day_end_time=$data['day_end_time'];
		$this->db->where('day_start_time',$day_start_time);
		$this->db->where('day_end_time',$day_end_time);
		$query=$this->db->get('time_detail',$data);
		if($query->num_rows == 0)
		{
			if($this->db->insert('time_detail',$data))
			{

				echo 1;
			}
			else{
				echo 0;
			}
		}else{
			echo 2;
		}
	}

	function taxiadd1july($data)
	{

		$cartype= $data['cartype'];
		if(isset($data['timetype'])){
			$timetype= $data['timetype'];
		}

		$transfertype = $data['transfertype'];
		if(isset($data['package'])){
			$package = $data['package'];
			$this->db->where("package",$package);
		}
		if(isset($data['timetype'])){
			$this->db->where('timetype', $timetype);
		}
		$this->db->where('cartype', $cartype);
		$this->db->where('transfertype', $transfertype);
		$query = $this->db->get('cabdetails');
		if($query->num_rows == 0)
		{
			if($this->db->insert('cabdetails',$data))
			{

				echo 1;
			}
			else{
				echo 0;
			}
		}else{
			echo 2;
		}

	}

	function  addstatus($data)
	{
		$status=$data['status'];
		$this->db->where('status', $status);
		$query = $this->db->get('Driver_status');
		if($query->num_rows == 0)
		{

			if($this->db->insert('Driver_status',$data))
			{

				echo 1;
			}
			else{
				echo 0;
			}
		}else{
			echo 2;
		}
	}

	function taxiadd($data)
	{
	
			$cartype= $data['cartype'];
		//	$daystarttime= $data['daystarttime'];
		//	//$start_time= '123';
		//	$day_end_time= $data['day_end_time'];
		//	//$end_time= '456';
			$night_intailrate= $data['night_intailrate'];
			$night_standardrate= $data['night_standardrate'];
			$night_fromintialkm= $data['night_fromintialkm'];
			$night_fromintailrate = $data['night_fromintailrate'];
			$night_package = $data['night_package'];
			$ride_time_rate = $data['ride_time_rate'];
			$night_ride_time_rate = $data['night_ride_time_rate'];
			if(isset($data['timetype'])){
			$timetype= $data['timetype'];
			}

			$transfertype = $data['transfertype'];
			if(isset($data['package'])){
				$package = $data['package'];
				$this->db->where("package",$package);
			}
			if(isset($data['timetype'])){
			 $this->db->where('timetype', $timetype);
			}
			 $this->db->where('cartype', $cartype);

			  $this->db->where('night_intailrate', $night_intailrate);
			  $this->db->where('night_standardrate', $night_standardrate);
			  $this->db->where('night_fromintialkm', $night_fromintialkm);
			  $this->db->where('night_fromintailrate', $night_fromintailrate);
			  $this->db->where('night_package', $night_package);
			  $this->db->where('ride_time_rate', $ride_time_rate);
			  $this->db->where('night_ride_time_rate', $night_ride_time_rate);
			  $this->db->where('transfertype', $transfertype);
			  $query = $this->db->get('cabdetails');
			  if($query->num_rows == 0)
				{

		if($this->db->insert('cabdetails',$data))
		{

		echo 1;
		}
		else{
		echo 0;
		}
				}else{
					echo 2;
				}

	}

	function update_status($data)
	{
		$id = $data['id'];
		$status= $data['status'];
		$this->db->where("id !=",$id);
		$this->db->where('status', $status);
		$query = $this->db->get('status');
		if($query->num_rows > 0)
		{
			echo 2;
		}
		else
		{
			$sql="UPDATE Driver_status SET status='$status' where id=$id";
			$rs=mysql_query($sql);
			if($rs)
			{
				echo 1;
			}
			else{
				echo 0;
			}

		}
	}

	function updatecar($data)
	{
		$id = $data['id'];
		$cartype= $data['car_type'];
		print_r($data);
		//$cartype= 'testss';
		$this->db->where("car_id !=",$id);
		$this->db->where('car_type', $cartype);
		$query = $this->db->get('Car_Type');
		if($query->num_rows > 0)
		{
			echo 2;
		}
		else
		{
			$sql="UPDATE Car_Type SET car_type='$cartype' where car_id=$id";
			$rs=mysql_query($sql);
			if($rs)
			{
				echo 1;
			}
			else{
				echo 0;
			}


		}
	}

	function updatetime($data)
	{
		 $tid = $data['tid'];
		$day_start_time=$data['day_start_time'];
		$day_end_time=$data['day_end_time'];
		$this->db->where("tid !=",$tid);
		$this->db->where('day_start_time', $day_start_time);
		$this->db->where('day_end_time', $day_end_time);
		$query = $this->db->get('time_detail');
		if($query->num_rows > 0){
			echo 2;
		}else{
			$this->db->where('tid', $tid);
			if($this->db->update('time_detail',$data))
			{

				echo 1;
			}
			else{

				echo 0;
			}
		}

	}

	function updatetaxi($data)
	{
			$id = $data['id'];
			$cartype= $data['cartype'];
		//	$daystarttime= $data['daystarttime'];
		//	//$start_time= '123';
		//	$day_end_time= $data['day_end_time'];
		//	//$end_time= '456';
		//	$night_start_time= $data['night_start_time'];
		//	$night_end_time= $data['night_end_time'];
			$night_intailrate= $data['night_intailrate'];
			$night_standardrate= $data['night_standardrate'];
			$night_fromintialkm= $data['night_fromintialkm'];
			$night_fromintailrate = $data['night_fromintailrate'];
			$night_package = $data['night_package'];
			$ride_time_rate = $data['ride_time_rate'];
			$night_ride_time_rate = $data['night_ride_time_rate'];
			if(isset($data['timetype'])){
			$timetype= $data['timetype'];
			}
			$transfertype = $data['transfertype'];
			if(isset($data['package'])){
				$package = $data['package'];
				$this->db->where("package",$package);
			}
			if(isset($data['timetype'])){
			 $this->db->where('timetype', $timetype);
			}
			 $this->db->where("id !=",$id);

			 $this->db->where('cartype', $cartype);
		//	$this->db->where('daystarttime',$daystarttime);
		//	$this->db->where('day_end_time',$day_end_time);
		//	$this->db->where('night_start_time', $night_start_time);
		//	$this->db->where('night_end_time', $night_end_time);

			$this->db->where('night_intailrate', $night_intailrate);
			$this->db->where('night_standardrate', $night_standardrate);
			$this->db->where('night_fromintialkm', $night_fromintialkm);
			$this->db->where('night_fromintailrate', $night_fromintailrate);
			$this->db->where('night_package', $night_package);
			$this->db->where('ride_time_rate', $ride_time_rate);
			$this->db->where('night_ride_time_rate', $night_ride_time_rate);
			  $this->db->where('transfertype', $transfertype);
			   $query = $this->db->get('cabdetails');
			  if($query->num_rows > 0){
				 echo 2;
			  }else{
				  $this->db->where('id', $id);
			if($this->db->update('cabdetails',$data))

		{

		echo 1;
		}
		else{

		echo 0;
		}
			  }

	}

	function delcabdetails($data)
	{
		$id = $data['id'];
		
		$this->db->where('id', $id);
		 
	if($this->db->delete('Driver_status'))
 		{
	
            echo 1;
		}
       else
	   {
	      echo 0;
       }

	}

	function  deletestatus($data)
	{
		$id=$data['id'];
		$this->db->where('id',$id);
		if($this->db->delete('Driver_status'))
		{
			echo 1;
		}
		else
		{
			echo 0;
		}
	}

	function delcardetails($data){


		$id = $data['car_id'];

		$this->db->where('car_id', $id);

		if($this->db->delete('Car_Type'))

		{

			echo 1;
		}
		else{

			echo 0;
		}

	}

	function updatepass($data)
	{
	     if($username = $this->session->userdata('username-admin')){
		$username = $this->session->userdata('username-admin');
		}else{
		$username = $this->input->cookie('username-admin', false);
		}
		$pass = md5($data['password']);
		$change = md5($data['change']);
		$confirma = $data['confirma'];
		$this->db->where('username', $username);
		$this->db->where('password', $pass);
		$query = $this->db->get('adminlogin');
		if($query->num_rows == 1){
		if($data['change'] == $data['confirma'])
		{
			
		$value = array('password'=>$change );
      	$this->db->where('username',$username);
		if($this->db->update('adminlogin',$value))
	
		{

		echo 1;
		}
		else{

		echo 0;
		}

		} else{
			echo 2;
			//not e
		}

				}else{
					echo 3;
					//current pas no
				}

	}

	function driveradd($data)
	{
		$username= $data['user_name'];
		$license= $data['license_no'];

		$this->db->where('username', $username);
		$query = $this->db->get('driver_details');
		if($query->num_rows == 0)
		{
			$this->db->where('license_no', $license);
			$query = $this->db->get('driver_details');
			if($query->num_rows == 0)
			{
				if($this->db->insert('driver_details',$data))
				{

					echo 1;
				}
				else{
					echo 0;
				}
			}
			else{
				echo 2;
				//echo '<script language="javascript">alert("username is exit")</script>';
			}
		}
		else
		{
			echo 3;

		}

	}

	function driveradd12($data)
	{
			$username= $data['user_name'];
			$license= $data['license_no'];
			$Lieasence_Expiry_Date= $data['Lieasence_Expiry_Date'];
			$car_type=$data['car_type'];
			$Car_Model=$data['Car_Model'];
			$Car_Make=$data['Car_Make'];
			$Seating_Capacity=$data['Seating_Capacity'];
			$license_plate=$data['license_plate'];
			$Insurance=$data['Insurance'];
			$this->db->where('username', $username);
			$this->db->where('license_no', $license);
			$this->db->where('license_plate', $license_plate);
			$this->db->where('Lieasence_Expiry_Date', $Lieasence_Expiry_Date);
			$this->db->where('Insurance', $Insurance);
			$this->db->where('Seating_Capacity', $Seating_Capacity);
			$this->db->where('Car_Model', $Car_Model);
			$this->db->where('Car_Make', $Car_Make);
			  $query = $this->db->get('driver_details');
			  if($query->num_rows == 0)
				{

			  $query = $this->db->get('driver_details');
			  if($query->num_rows == 0)
				{
		if($this->db->insert('driver_details',$data))
		{

		echo 1;
		}
		else{
		echo 0;
		}
				}else{
					echo 2;
				}
				}else{
					echo 3;
				}
		
	}


	function deletedriver($data)
	{
	
	
		$id = $data['id'];
		
		$this->db->where('id', $id);
		 
		if($this->db->delete('driver_details'))
	
		{
	
            echo 1;
                    }
               else{
	
              echo 0;
                    }

	}

	function db_connect($data)
	{

		$filename = 'sql/techwbzd_callmycab.sql';
		// MySQL host
		 $mysql_host = $data['dbhost'];
		// MySQL username
		$mysql_username = $data['dbuser'];
		// MySQL password
		$mysql_password = $data['dbpass'];
		// Database name
		 $mysql_database = $data['dbname'];
		$con = mysqli_connect($mysql_host, $mysql_username, $mysql_password,$mysql_database) or die('Error connecting to MySQL server');
		// Select database


		// Temporary variable, used to store current query
		$templine = '';
		// Read in entire file
		$lines = file($filename);
		// Loop through each line
		foreach ($lines as $line)
		{
		// Skip it if it's a comment
		if (substr($line, 0, 2) == '--' || $line == '')
			continue;

		// Add this line to the current segment
		$templine .= $line;
		// If it has a semicolon at the end, it's the end of the query
		if (substr(trim($line), -1, 1) == ';')
		{
			// Perform the query
			mysqli_query($con,$templine);
			// Reset temp variable to empty
			$templine = '';
		}
		}

		 echo '<div id="dup-step1-dbconn-status" >
						<div style="padding: 0px 10px 10px 10px;">		
							<div id="dbconn-test-msg" style="min-height:80px;text-align:center"><div class="dup-db-test">Tables imported successfully</div><label>Database :</label>
		<div class="dup-fail">"'.$mysql_database.'"</div><br><label>Username :</label>
		<div class="dup-fail">"'.$mysql_username.'"</div><br><label>Password :</label>
		<div class="dup-fail">"'.$mysql_password.'"</div></div>
						</div>
						</div>';



		 $myfile = fopen("application/config/database.php", "w") or die("Unable to open file!");
		 //$myfile = fopen("application/config/database.php", "X+") or die("Unable to open file!");
		 $active_record='';
		$txt = '<?php  if ( ! defined("BASEPATH")) exit("No direct script access allowed");$active_group = "default";
		$active_record = TRUE;'."\r\n";

		$txt .='$db["default"]["hostname"] = "'.$mysql_host.'";'."\r\n";
		$txt .='$db["default"]["username"] = "'.$mysql_username.'";'."\r\n";
		$txt .='$db["default"]["password"] = "'. $mysql_password.'";'."\r\n";
		$txt .='$db["default"]["database"] ="'.$mysql_database.'";'."\r\n";
		$txt .='$db["default"]["dbdriver"] = "mysqli";
		$db["default"]["dbprefix"] = "";
		$db["default"]["pconnect"] = TRUE;
		$db["default"]["db_debug"] = FALSE;
		$db["default"]["cache_on"] = FALSE;
		$db["default"]["cachedir"] = "";
		$db["default"]["char_set"] = "utf8";
		$db["default"]["dbcollat"] = "utf8_general_ci";
		$db["default"]["swap_pre"] = "";
		$db["default"]["autoinit"] = TRUE;
		$db["default"]["stricton"] = FALSE;';

		fwrite($myfile, $txt);
		fclose($myfile);
	}

	function insta($data)
	{
		$id=$data['id'];
					$this->db->where('id', $id);
					if($this->db->update('settings',$data)){
					echo 0;
					}else{
		echo 1;
		}
	}

	function settings($data)
	{


		$query = $this->db->get('settings');
			  if($query->num_rows == 0)
				{


		if($this->db->insert('settings',$data))
		{




		 $this->session->set_flashdata('item', array('message' => 'Record updated successfully','class' => 'success') );
		$this->session->flashdata('item');

		//redirect to some function
		redirect("admin/add_settings");
		}
		else{
		$this->session->set_flashdata('item', array('message' => 'Error','class' => 'error') );
		$this->session->flashdata('item');
		redirect("admin/add_settings");
		}
				}else{
					$id=$data['id'];
					$this->db->where('id', $id);
					if($this->db->update('settings',$data)){
						 $this->session->set_flashdata('item', array('message' => 'Record updated successfully','class' => 'success') );
		$this->session->flashdata('item');

		//redirect to some function
		redirect("admin/add_settings");
					}else{
		$this->session->set_flashdata('item', array('message' => 'Error','class' => 'error') );
		$this->session->flashdata('item');
		redirect("admin/add_settings");
		}
				}
	}

	function roleadd($data)
	{
		$role= $data['rolename'];
	 
	
	  $this->db->where('rolename', $role);
	  $query = $this->db->get('role');  
	  if($query->num_rows == 0)
        {
		$data = array(
			'created_date' => date('Y-m-d H:i:s',now()),
			'rolename'  => $role
			);
			if($this->db->insert('role',$data))
			{
			$insert_id = $this->db->insert_id();
			//foreach ($this->input->post('page_id') as $key => $value)
			//{
			//	echo "Index {$key}'s value is {$value}.";
			//}
			//foreach ($this->input->post('page_id') as $attribute){
			//		$data['page_id'] = implode(',',$attribute);

			//}

			//if($this->input->post('page_id1')){
			//	$data1['page_id'] = implode(',',$this->input->post('page_id1'));
			//}else{
			//$data1['page_id'] =implode(',',$this->input->post('page_id'));
			//}

			if($this->input->post('page_id1')){
				$page_id = explode(",", $this->input->post('page_id1'));
			}else{
				$page_id = $this->input->post('page_id');
			}

			foreach ($page_id as $country)
			{
			$user_countries = array(
			'page_id' => $country,
			'rol_id' => $insert_id
			);


					$this->db->insert('role_permission',$user_countries);


			}



			echo 1;
			}
			else{
			echo 0;
			}

					}else{
						echo 2;
					}

	}

	function deleterole($data){
	
	
		$id = $data['rolename'];
		
		$this->db->where('rolename', $id);
		 
		if($this->db->delete('role'))
	
                  {
	
            echo "Rolename Removed Successfully";
                    }
               else{
	
              echo "error";
                    }

	}

	function updaterole($data){
	    
		$role =$data['role_id'];
	
			$role_permission = $data['page_id'];
			$selects =$this->db->query("select * from role_permission where role_id ='$role' ");
			if($selects->num_rows == 0) {
				$user_countries = array(
		'role_id' => $role,
		'page_id' => $role_permission

		);
		if( $this->db->insert('role_permission',$user_countries)){
			echo 1;
		}else{
			echo 2;
		}
		} else{
			 foreach($selects->result_array() as $row){
				 $r_id= $row['r_id'];

		 $this->db->where('r_id', $r_id);
		if($this->db->update('role_permission',$data)){

			echo 3;
		}else{
			echo 4;
		}
			 }
		}

	}

	function addrole($data)
	{
		$rolename= $data['rolename'];
		$created_date =date('Y-m-d H:i:s');
	 	$this->db->where('rolename', $rolename);
	 
	  	$query = $this->db->get('role');
	  	if($query->num_rows == 0)
        {
			if($this->db->insert('role',$data))
		{
	 
			echo" Rolename Added successfully";
		}
		else{
			echo "Error";
		}
		}else{
			echo "Rolename Already Added";
		}
		
	}

	function delete_backend_user($data)
	{
	
	
		$id = $data['id'];
		
		$this->db->where('id', $id);
		 
		if($this->db->delete('adminlogin'))
	
                  {
	
            echo 1;
                    }
               else{
	
              echo 0;
                    }

	}

	function user_backend_insert($data)
	{
	
	  $username = $data['username'];
	  $email = $data['email'];
	  $data['password']=md5($data['password']);
	  $this->db->where('username', $username);
	  $query = $this->db->get('adminlogin');
	 $rolename=$data['role'];
	   if($query->num_rows == 0)
        {
			 $this->db->where('email',  $email);
			 $query1 = $this->db->get('adminlogin');
			if($query1->num_rows == 0)
        {
			
	$date =date('Y-m-d H:i:s');
	


		if($this->db->insert('adminlogin',$data) )
		{
	 $this->db->where('rolename',  $rolename);
	 $query3 = $this->db->get('role');
			if($query3->num_rows == 0)
        {
			$this->db->set('rolename', $rolename);
			$this->db->insert('role');
			echo 1;
			}
		}
		else{
			echo 0;
		}
		}else{
			echo 4;
		}
		}else{
			echo 3;
		}
}

	function edit_backend_user($data){
	    $email = $data['email'];
		$id = $data['id'];
		
		$this->db->where('id', $id);
		if($this->db->update('adminlogin',$data))
	
		{
	
			echo 1;
		}
		else{
	
			echo 0;
		}

	}

	function delete_air($data){
	
	
		$id = $data['id'];
		
		$this->db->where('id', $id);
		 
		if($this->db->delete('airport_details'))
	
                  {
	
            echo 1;
                    }
               else{
	
              echo 0;
                    }

	}

	function delete_package($data){
	
	
		$id = $data['id'];
		
		$this->db->where('id', $id);
		 
		if($this->db->delete('package_details'))
	
                  {
	
            echo 1;
                    }
               else{
	
              echo 0;
                    }

	}

	function air_insert($data)
	{
		$name= $data['name'];
	
	
	  $this->db->where('name', $name);
	  $query = $this->db->get('airport_details');  
	  if($query->num_rows == 0)
        {
		if($this->db->insert('airport_details',$data))
		{
	 
		echo 1;
		}
		else{
		echo 0;
		}
				}else{
					echo 2;
				}
		
	}

	function airmanage_update($data){
	    
		$id = $data['id'];
		
		$this->db->where('id', $id);
		if($this->db->update('airport_details',$data))
	
		{

		echo 1;
		}
		else{

		echo 0;
		}

	}

	function package_update($data){
	    
		$id = $data['id'];
		
		$this->db->where('id', $id);
		if($this->db->update('package_details',$data))

		{

		echo 1;
		}
		else{

		echo 0;
		}

	}
	function places_insert($data)
	{
		$name= $data['location'];
	
	
	  $this->db->where('location', $name);
	  $query = $this->db->get('places');  
	  if($query->num_rows == 0)
        {
		if($this->db->insert('places',$data))
		{
	 
		echo 1;
		}
		else{
		echo 0;
		}
		}else{
			echo 2;
		}
		
	}
	function deleteplaces($data){
	
	
		$id = $data['id'];
		
		$this->db->where('id', $id);
		 
	if($this->db->delete('places'))
	
                  {
	
            echo 1;
                    }
               else{
	
              echo 0;
                    }

	}


	function updateplace($data){

			$id = $data['id'];
			$places = $data['location'];
			 $this->db->where("id !=",$id);

		  $this->db->where('location', $places);
		   $query = $this->db->get('places');
		  if($query->num_rows > 0){
			 echo 2;
		  }else{

			$this->db->where('id', $id);
		if($this->db->update('places',$data))

		{

		echo 1;
		}
		else{

		echo 0;
		}

		}
	}

	function insertairport($data)
	{
		$name= $data['name'];


		  $this->db->where('name', $name);
		  $query = $this->db->get('airport_details');
		  if($query->num_rows == 0)
			{
		if($this->db->insert('airport_details',$data))
		{

		echo 1;
		}
		else{
		echo 0;
		}
			}else{
				echo 2;
			}

	}

	function insertpackage($data)
	{
		$name= $data['package'];
	
	
	  	$this->db->where('package', $name);
	  	$query = $this->db->get('package_details');
	  	if($query->num_rows == 0)
        {
			if($this->db->insert('package_details',$data))
			{
				echo 1;
			}
			else
			{
				echo 0;
			}
		}else{
			echo 2;
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

	function status_update($data)
	{
	    
		$id = $data['id'];
		 
		$status ='Complete';
		$value=array('status'=>'Complete');
		$book_details = $this->send_rating($id);
		if($book_details==true){
			$this->db->where('id', $id);
			if($this->db->update('bookingdetails',$value))
			{
	
				echo 1;
			}
			else{
				echo 0;
			}
		}
	}

	function send_rating($id)
	{
		$book_details = $this->get_booking_details($id);
		$settings =$this->get_settings_details();
		$user_details =$this->get_user_details($book_details->username);
		$driver_details =$this->get_driver_details($book_details->assigned_for);
		$str = $settings->currency;
		$curr = explode(',',$str);
		$from= $settings->smtp_username;
		$s = base_url();
		$name=$settings->title;
		$msg='Rating';
		$sub="Rating Driver";
		$email = $user_details->email;
		 if(isset($book_details->pickup_area)){
				$pickup_area =$book_details->pickup_area;
				}else{
					$pickup_area =$book_details->pickup_address;
		 }if(isset(	$driver_details->name)){
			 $driver_name=$driver_details->name;
		 }else{
			  $driver_name=$driver_details->email;
		 }
		$template='<div style="width:660px; height:900px; margin:0 auto; background:#f2c21e; padding:20px 20px 20px 20px; font-family: Century Gothic,Verdana,Geneva,sans-serif; border:solid #c79e13 1px;">
		
		<div style="width:100%; float:left; padding:0 0 10px 0;">
		<div style="width:138px; height:73px; float:left; margin:0 0 0 20px;"> <img src="'.$s.'assets/images/carss.png" alt="" /></div>
		<div style="width:350px; float:left; padding:25px 0 0 0; text-align:center; font-size:18px; "> BOOKING DETAILS </div>
		</div>
			<div style="background:#fff;  float:left; width:96.3%;   border-top-right-radius: 8px; border-top-left-radius: 8px; padding:15px 12px 0 12px;  ">
				<div style="width:100%; padding:10px 0 10px 0; float:left; color:#666261; font-size:14px;"> Hi '.$book_details->username.', thanks for booking with us. </div>
					 <div style="width:100%; float:left; padding:20px 0 20px 0; border-bottom:solid #cdcdcd 1px; border-top:solid #cdcdcd 1px;"> 
					 <div style="width:30%; float:left; font-size:17px;"> Trip#1 </div>
					 <div style="width:40%; float:left; font-size:17px;"> '.$book_details->uneaque_id.'</div>
					 <div style="width:30%; float:left;">  
					 
					 <a href="#"> <div style="width:100px; height:30px; background:#58585a; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; font-size:12px; color:#fff; 
					 text-align:center; line-height:25px; text-decoration:none; float:right;"> Track Booking </div> </a>
					 
					  </div>
				</div>
			</div>

			<div style="background:#3a3a3c;     float:left; width:96.3%;  padding:0px 12px 10px 12px;">
				<div style="width:100%; float:left; padding:35px 0 30px 0;">
				<div style="width:43%; float:left; color:#ffdd1a; font-size:16px; padding:5px 0 0 0; line-height:22px;">'.$pickup_area.'</div>
				<div style="width:15%; float:left; text-align:center;"> <img src="'.$s.'assets/images/arrow.png" alt="" /> </div>
				<div style="width:42%; float:left; color:#ffdd1a; font-size:16px; padding:5px 0 0 0; line-height:22px;">'.$book_details->drop_area.'</div>
				</div>

				<div style="width:100%; float:left;">
					<div style="width:32%;   float:left; background:#585858; padding:24px 0 24px 0; "> 
					<div style="width:100%; float:left; text-align:center; color:#fff; font-size:15px;"> '.$book_details->taxi_type.' </div>
					<div style="width:100%; float:left; text-align:center; color:#bbbbbb; font-size:14px; padding:5px 0 0 0;"> AC </div> 
					</div>
					
					<div style="width:32%; margin:0 12px 0 12px;   float:left; background:#585858; padding:24px 0 24px 0; "> 
					<div style="width:100%; float:left; text-align:center; color:#fff; font-size:15px;"> '. $curr[1].''.$book_details->amount.' </div>
					<div style="width:100%; float:left; text-align:center; color:#bbbbbb; font-size:14px; padding:5px 0 0 0; ">  </div> 
					</div>
					
					<div style="width:32%;   float:left; background:#585858; padding:24px 0 24px 0; "> 
					<div style="width:100%; float:left; text-align:center; color:#fff; font-size:15px;">'.$book_details->pickup_time.' </div>
					<div style="width:100%; float:left; text-align:center; color:#bbbbbb; font-size:14px; padding:5px 0 0 0;">'. date('D, d M',strtotime( $book_details->pickup_time)).'</div> 
					</div>
				</div>
				
				<div style="width:100%; float:left; color:#bbbbbb; padding:14px 0 8px 0; font-size:12px;"> *All complaints with regard to fares should be referred to us within 2 days of completion of the trip. </div>
		   </div>
		   
		   <div style="background:#fff;  float:left; width:96.3%;    border-bottom-right-radius: 8px; border-bottom-left-radius: 8px; padding:12px 12px 20px 12px;  ">
			<div style="background:#3a3a3c; float:left;  width:96.3%;  padding:18px 12px 10px 12px;  border-radius: 8px;">
			  <div style="width:33%; height:175px; float:left; border-right: solid #585858 1px; ">
			  <form method="post" onsubmit="try {return window.confirm("You are submitting information to an external page.\nAre you sure?");} catch (e) {return false;}" target="_blank" action="'.base_url().'admin/rating">
				<div  style="width:100%; float:left">
				<input type="hidden" value="'.$user_details->id.'" name="users">
				<input type="hidden" value="'.$book_details->assigned_for.'" name="driver_id">
				  <div style="width:15%; float:left;"><img src="'.$s.'assets/images/star.png" alt="" />
					<input name="rating" type="radio" value="1" />
				  </div>
				  <div style="width:15%; float:left;"><img src="'.$s.'assets/images/star.png" alt="" />
					<input name="rating" type="radio" value="2" />
				  </div>
				  <div style="width:15%; float:left;"><img src="'.$s.'assets/images/star.png" alt="" />
					<input name="rating" type="radio" value="3" />
				  </div>
				  <div style="width:15%; float:left;"><img src="'.$s.'assets/images/star.png" alt="" />
					<input name="rating" type="radio" value="4" />
				  </div>
				  <div style="width:15%; float:left;"><img src="'.$s.'assets/images/star.png" alt="" />
					<input name="rating" type="radio" value="5" />
				  </div>
				</div>
				<div style="width:100%; margin:10px 0 0 0; float:left;">
				  <textarea  placeholder=" Leave a comment..." cols="" rows=""></textarea>
				</div>
				<div style=" float:left;"> 
				 
			<input type="submit" value="submit" style="width:80px; margin:5px 0 0 0; height:30px; background:#ffdd1a; 
			-webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; font-size:12px; color:#1f1f1f; 
			text-align:center; line-height:25px; text-decoration:none; float:right;"
				  ></div>
				  
			  </div>
			  </form>
			  <div style="width:32%; height:40px; float:left;   border-right: solid #585858 1px;  padding:65px 5px 65px 10px;">
				<div style="width:100%; float:left; color:#fff; font-size:12px;"> Billed by: </div>
				<div style="width:100%; float:left; color:#ffdd1a; font-size:16px;"> '.$driver_name.' </div>
				<div style="width:100%; float:left; color:#fff; font-size:13px;"> '.$driver_details->car_no.'  ('.$driver_details->car_type.') </div>
			  </div>
			  <div style="width:30%; height:40px; float:left; padding:65px 0 60px 10px;">
				<div style="width:100%; float:left; color:#fff; font-size:12px;"> Taxi Operator: </div>
				<div style="width:100%; float:left; color:#fff; font-size:16px;"> Sansar Media </div>
			  </div>
			</div>

			<div style="width:100%; float:left; font-size:16px; padding:0 0 10px 0;"> Extra Charges: </div> 
			<div style="width:100%; float:left; color:#666262; font-size:11px; line-height:22px;">
			
			* Maximum of 4 passengers allowed for Indica & Sedan. <br />
			* Cancellation charges of Rs.100 applicable if cancelled within 30 mins of pickup time. <br />
			* Any Toll, Parking, as applicable. <br />
			* No waiting charges upto 15 mins after scheduled pickup time. Rs.50 per 30 mins after that. <br /> 
			* Final fare payable will include Service Tax
			
			</div>
	 
			</div>
		</div>
		</div>';
		   $this->home->send_mail($from,$name,$email,$sub,$template);
		   return true;
	}

	function get_driver_details($id){
		$select_data="*";
		$where_data = array(	// ----------------Array for check data exist ot not
					'id'     => $id
				);

		$table="driver_details";
		$result = $this->get_table_where( $select_data, $where_data, $table );
		return $result;
	}

	function get_user_details($username)
	{
		$select_data="*";
		$where_data = array(	// ----------------Array for check data exist ot not
					'username'     => $username
				);

		$table="userdetails";
		$result = $this->get_table_where( $select_data, $where_data, $table );
		return $result;
	}

	function get_settings_details(){
		$select_data="*";
		$where_data = array(	// ----------------Array for check data exist ot not
					'id'     => 1
				);

		$table="settings";
		$result = $this->get_table_where( $select_data, $where_data, $table );
		return $result;
	}



	function blog_upload($data)
	{
	  //var_dump($data);
		$id = $data['id'];
		$content = $data['blog_content'];
		$name = $data['block_name'];
	    $value=array('blog_content'=>$content,'block_name'=>$name);


		$this->db->where('id', $id);
		if($this->db->update('blogs',$value))
	
		{
	
			echo 1;
		}
		else{
	
			echo 0;
		}
	}

	function book_admin($data){
	
        //$data['uneaque_id'] = 'CMC'.strtotime(date('m/d/Y H:i:s'));
		//$data['status'] = "Processing";
		
		if($this->db->insert('bookingdetails',$data))
		{
			return 0;
		}else {
			return 1;
		}
	}

	function languagesetupdate($data){
	    
		$id = $data['id'];
		
		$this->db->where('id', $id);
		if($this->db->update('language_set',$data))
	
		{

		echo 1;
		}
		else{

		echo 0;
		}

	}


	function insertlanguage($data)
	{
		$textFile= $data['languages'];
		//var_dump($data);

  		$extension = ".php";
  		$filename='includes/'.$textFile.$extension;
  
	  	$myfile = fopen($filename, "wb") or die("Unable to open file!");
		$txt = '<?php $point_topoint = "'.$data['languagesw'].'";'."\r\n";
		$txt .='$airport_transfer = "'.$data['Airport'].'";'."\r\n";
		$txt .='$hourly_rental = "'.$data['Hourly'].'";'."\r\n";
		$txt .='$out_station = "'.$data['Outstations'].'";'."\r\n";

		$txt .='$locations = "'.$data['locations'].'";'."\r\n";
		$txt .='$select_taxi = "'.$data['SelectTaxi'].'";'."\r\n";
		$txt .='$confirm_booking = "'.$data['ConfirmBooking'].'";'."\r\n";
		$txt .='$pickup_area = "'.$data['PickupArea'].'";'."\r\n";
		$txt .='$pickup_date = "'.$data['PickupDate'].'";'."\r\n";

		$txt .='$drop_area = "'.$data['DropArea'].'";'."\r\n";
		$txt .='$pickup_time = "'.$data['PickupTime'].'";'."\r\n";
		$txt .='$select_car = "'.$data['SelectCar'].'";'."\r\n";
		$txt .='$Fares = "'.$data['fare'].'";'."\r\n";
		$txt .='$fare_detail = "'.$data['FareDetail'].'";'."\r\n";

		$txt .='$area = "'.$data['area'].'";'."\r\n";
		$txt .='$landmarks = "'.$data['landmark'].'";'."\r\n";
		$txt .='$pickups_addr = "'.$data['pickupaddress'].'";'."\r\n";
		$txt .='$flight_number = "'.$data['flightnumber'].'";'."\r\n";
		$txt .='$Packages = "'.$data['package'].'";'."\r\n";


		$txt .='$promocodes = "'.$data['Enter_apromocode'].'";'."\r\n";
		$txt .='$oneway_trip = "'.$data['one_waytrip'].'";'."\r\n";
		$txt .='$roundtrips = "'.$data['round_trip'].'";'."\r\n";
		$txt .='$to = "'.$data['to'].'";'."\r\n";
		$txt .='$departure_date = "'.$data['departure_date'].'";'."\r\n";


		$txt .='$return_date = "'.$data['returndate'].'";'."\r\n";
		$txt .='$cab_not_available = "'.$data['cabdetails_notavailable'].'";'."\r\n";
		$txt .='$going_airport = "'.$data['going_toairport'].'";'."\r\n";
		$txt .='$Coming_from_Airports = "'.$data['coming_fromairport'].'";'."\r\n";
		$txt .='$login_Register = "'.$data['login_register'].'";'."\r\n";


		$txt .='$callbacks = "'.$data['callback'].'";'."\r\n";
		$txt .='$fare_charts = "'.$data['farechart'].'";'."\r\n";
		$txt .='$abouts = "'.$data['about'].'";'."\r\n";
		$txt .='$contacts = "'.$data['contact'].'";'."\r\n";
		$txt .='$pls_login_registers = "'.$data['please_login'].'";'."\r\n";

		$txt .='$logos = "'.$data['logo'].'";'."\r\n";
		$txt .='$log_out = "'.$data['logout'].'";'."\r\n";
		$txt .='$profiles = "'.$data['profile'].'";'."\r\n";
		$txt .='$personal_detaile = "'.$data['personal_details'].'";'."\r\n";

		$txt .='$change_password = "'.$data['change_password'].'";'."\r\n";
		$txt .='$contact_information = "'.$data['contact_information'].'";'."\r\n";
		$txt .='$names = "'.$data['name'].'";'."\r\n";
		$txt .='$mobiles_numbers = "'.$data['mobile_number'].'";'."\r\n";
		$txt .='$emails = "'.$data['email'].'";'."\r\n";

		$txt .='$basics_informations = "'.$data['basic_information'].'";'."\r\n";
		$txt .='$genders = "'.$data['gender'].'";'."\r\n";
		$txt .='$males = "'.$data['male'].'";'."\r\n";
		$txt .='$females = "'.$data['female'].'";'."\r\n";
		$txt .='$dob = "'.$data['dob'].'";'."\r\n";
		$txt .='$Anniversary = "'.$data['anivdate'].'";'."\r\n";
		$txt .='$cancel = "'.$data['cancel'].'";'."\r\n";
		$txt .='$bookings_detailes = "'.$data['bkdetail'].'";'."\r\n";
		$txt .='$actives = "'.$data['active'].'";'."\r\n";
		$txt .='$pasts = "'.$data['Past'].'";'."\r\n";
		$txt .='$verification_code = "'.$data['EVC'].'";'."\r\n";
		$txt .='$error_message = "'.$data['EMH'].'";'."\r\n";
		$txt .='$OTP = "'.$data['DRO'].'";'."\r\n";
		$txt .='$resends = "'.$data['resend'].'";'."\r\n";
		$txt .='$re_code = "'.$data['PCEV'].'";'."\r\n";
		$txt .='$close = "'.$data['Close'].'";'."\r\n";
		$txt .='$sign_in = "'.$data['signin'].'";'."\r\n";
		$txt .='$new_accounts = "'.$data['newact'].'";'."\r\n";
		$txt .='$username = "'.$data['Username'].'";'."\r\n";
		$txt .='$passwords = "'.$data['Password'].'";'."\r\n";
		$txt .='$hide = "'.$data['Hide'].'";'."\r\n";
		$txt .='$remember_me = "'.$data['remenber'].'";'."\r\n";
		$txt .='$forgot_pswd = "'.$data['FYP'].'";'."\r\n";
		//$save_address="Save This Address";
		$txt .='$save_address = "'.$data['save_addr'].'";'."\r\n";
		$txt .='$PayNow = "'.$data['PayNow'].'";'."\r\n";
		$txt .='$BookNow = "'.$data['BookNow'].'";'."\r\n";
		$txt .='$FindmyTaxi = "'.$data['FindmyTaxi'].'";?>';
	 	fwrite($myfile, $txt);
		fclose($myfile);

		$user = array(
		'languages' => $textFile
		);

		if(isset($data['id'])){
		  $id =  $data['id'];
			$this->db->where("id !=",$id);
	  	}
	  	$this->db->where('languages', $textFile);
	  	$query = $this->db->get('language_set');
	  	if($query->num_rows == 0)
        {
			if(isset($data['id']))
			{
				$user1 = array(
					'languages' => $textFile
				);
				$this->db->where('id', $id);
				if($this->db->update('language_set',$user1))
				{
					echo 1;
				}
				else
				{
					echo 0;
				}
			}
			else
			{
				if($this->db->insert('language_set',$user))
				{
					echo 1;
				}
				else
				{
					echo 0;
				}
			}
		}
		else
		{
			echo 2;
		}
	}


	function delete_langauge($data){
	
	
		$id = $data['id'];
		
		$this->db->where('id', $id);
		 
		if($this->db->delete('language_set'))
                  {
	
            echo 1;
                    }
               else{
	
              echo 0;
                    }

	}
	
	function baners($data)
	{


		$id=$data['id'];
		$this->db->where('id', $id);
		if($this->db->update('blogs',$data)){
		   $this->session->set_flashdata('item', array('message' => 'Record updated successfully','class' => 'success') );
		   $this->session->flashdata('item');

			//redirect to some function
			redirect("admin/add_banner");
		}else{
			$this->session->set_flashdata('item', array('message' => 'Error','class' => 'error') );
			$this->session->flashdata('item');
			redirect("admin/add_banner");
		}	
	}

	function page_insert($data)
	{
		if($this->db->insert('static_pages',$data))
		{
		echo 0;
		}else{
		echo 1;
		}
	}

	function deletepages($data)
	{
	
	
		$id = $data['id'];
		
		$this->db->where('id', $id);
		 
		if($this->db->delete('static_pages'))
	
                  {
	
            echo 1;
                    }
               else{
	
              echo 0;
                    }

	}
	
	function pages_updates($data)
	{
	    
		$id = $data['id'];
		
		$this->db->where('id', $id);
		if($this->db->update('static_pages',$data))
		{
	
		echo 1;
		}
		else{

		echo 0;
		}

	}

	function driver_assign_auto($data)
	{
		$query1 = $this->db->query("SELECT driver_details.* FROM driver_details where NOT EXISTS(select * from bookingdetails where driver_details.id=bookingdetails.assigned_for and bookingdetails.status='Processing') group by driver_details.id ");
		$count = $query1->result_array();
		if(count($count)!=0)
		{
		$datas['assigned_for'] =$count[0]['id'];
		$datas['username']= $this->session->userdata('username');
		$datas['uneaque_id']=$data['c'];
		$datas['status']='Processing';
		$result = $this->pointupdate( $datas);
		
		}
	}
	function settings_details()
	{
		$select_data = "*";
		$where_data = array(	// ----------------Array for check data exist ot not
			'id'     => 1
	    );
			
		$table = "settings";  //------------ Select table
		$result = $this->get_table_where( $select_data, $where_data, $table );
		return $result;
	}

	function get_table_where( $select_data, $where_data, $table)
	{
        
		$this->db->select($select_data);
		$this->db->where($where_data);
		
		$query  = $this->db->get($table); 
       		//--- Table name = User
		$result = $query->row(); 
		
		return $result;	
   	}

	function driver_approvel($data)
   	{
	   
		$id = $data['id'];
		
		$datas = array(
                'user_status'   => ''
               
            );
		$this->db->where('id', $id);
	if($this->db->update('driver_details',$datas))
	
		{

		echo 1;
		}
		else{

		echo 0;
		}

	}
	function delete_callback($data)
	{
		$id = $data['id'];
		
		$this->db->where('id', $id);
		 
		if($this->db->delete('callback'))
	
                  {
	
            echo 1;
                    }
               else{
	
              echo 0;
                    }

	}

	function get_driver_rating($id,$user){
	$select_data = "*"; 
		$where_data = array(	// ----------------Array for check data exist ot not
			'driver_id'     => $id,
			'username'     => $user
	    );
			
		$table = "driver_rating";  //------------ Select table
		$result = $this->get_table_where( $select_data, $where_data, $table );
		return $result;
	}

	function get_average_rating($id)
	{
		$select_data = "driver_id, AVG(rating) as average";
		
		$where_data = array(	// ----------------Array for check data exist ot not
			'driver_id'     => $id
		       );
		
		  $table       = "driver_rating"; //------------ Select table
		$result = $this->get_table_where( $select_data, $where_data, $table );
	 $this->update_average_driver($id,$result->average);
	 return true;
	}

	function update_average_driver($id,$average){
	      $table       = "driver_details";
             
             $update_data = array(
			 'rating'      => $average
		     );
		
		$where_data = array(
			'id'   => $id
			
		  );
		
		$this->update_table_where( $update_data, $where_data, $table);
		return true;
	
	}

	function rate_driver($data)
	{
		$driver_details =$this->get_driver_details($data['driver_id']);
		$driver_rating =$this->get_driver_rating($data['driver_id'],$data['users']);
		if(count($driver_details)>0){
			if($data['rating']!=''){
			if(count($driver_rating)>0){

				  $table       = "driver_rating";

				 $update_data = array(
				 'rating'      => $data['rating']
				 );

			$where_data = array(
				'driver_id'   => $data['driver_id'],
				'username'   => $data['users'],
			  );

			$this->update_table_where( $update_data, $where_data, $table);

			$driver_rating =$this->get_average_rating($data['driver_id']);

				 return true;
		}else{
				$table       = "driver_rating";
				   $insert_data = array(
					'rating'      => $data['rating'],
				   'username'     => $data['users'],
				   'driver_id'    =>$data['driver_id']
					);
				$this->db->insert($table , $insert_data);

				$driver_rating =$this->get_average_rating($data['driver_id']);
				 return true;
			}
			}

		}
	}
	function update_table_where( $update_data, $where_data, $table){
		$this->db->where($where_data);
		$this->db->update($table, $update_data);
	}*/
}
?>